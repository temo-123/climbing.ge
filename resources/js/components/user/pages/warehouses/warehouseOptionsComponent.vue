<template>
    <div class="row">
        <div class="col-md-12">
            <div class="form-group">
                <button class="btn btn-success float-right" @click="go_back()">Go back</button>
            </div>
        </div>

        <div class="col-md-12">
            <div class="form-group">
                <button class="btn btn-success float-right" @click="get_data()">Refresh</button>
            </div>
        </div>

        <div class="col-md-12">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h3>Warehouse Product Options</h3>
                <button class="btn btn-primary" @click="showAddOptionModal = true">
                    Add Product Option
                </button>
            </div>

            <!-- Loading state -->
            <div v-if="loading" class="text-center">
                <div class="spinner-border" role="status">
                    <span class="sr-only">Loading...</span>
                </div>
            </div>

            <!-- Product Options Table -->
            <div v-else-if="product_options.length > 0" class="table-responsive">
                <table class="table table-striped" style="overflow-x: hidden;">
                    <thead>
                        <tr>
                            <th>Image</th>
                            <th>ID</th>
                            <th>Product Name</th>
                            <th>Option Name</th>
                            <th>Price</th>
                            <th>Quantity</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="option in product_options" :key="option.id">
                            <td>
                                <img :src="getOptionImage(option)" :alt="option.name" style="width: 50px; height: 50px; object-fit: cover;" />
                            </td>
                            <td>{{ option.id }}</td>
                            <td>{{ option.product.url_title }}</td>
                            <td>{{ option.name }}</td>
                            <td>{{ option.price }} {{ option.currency }}</td>
                            <td>
                                <div class="row">
                                    <span class="input-group-btn col">
                                        <button type="button" class="btn btn-danger btn-number"  data-type="minus" data-field="quant[2]">
                                            <span class="glyphicon glyphicon-minus"></span>
                                        </button>
                                    </span>
                                    <input
                                        type="number"
                                        v-model="option.pivot.quantity"
                                        @change="updateQuantityQuick(option)"
                                        class="form-control form-control-sm col"
                                        style="width: 80px;"
                                        min="0"
                                    />
                                    <span class="input-group-btn">
                                        <button type="button" class="btn btn-success btn-number" data-type="plus" data-field="quant[2]">
                                            <span class="glyphicon glyphicon-plus col"></span>
                                        </button>
                                    </span>
                                </div>

                            </td>
                            <td>
                                <button class="btn btn-sm btn-danger" @click="deleteOption(option)">
                                    Delete
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Empty state -->
            <div v-else class="text-center">
                <p>No product options found in this warehouse.</p>
                <button class="btn btn-primary" @click="showAddOptionModal = true">
                    Add First Product Option
                </button>
            </div>
        </div>

        <!-- Add Product Option Modal -->
        <div v-if="showAddOptionModal" class="modal" tabindex="-1" role="dialog" style="display: block;">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Add Product Option</h5>
                        <button type="button" class="close" @click="closeAddModal">
                            <span>&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div v-if="availableProducts.length === 0" class="alert alert-warning">
                            No products available. Please add products first.
                        </div>
                        <div v-else-if="availableOptions.length === 0 && newOption.product_id" class="alert alert-warning">
                            No options available for the selected product.
                        </div>
                        <form v-else @submit.prevent="addProductOption">
                            <div class="form-group">
                                <label for="product_id">Select Product</label>
                                <select v-model="newOption.product_id" @change="getAvailableOptions()" class="form-control" required>
                                    <option value="">Choose a product...</option>
                                    <option v-for="product in availableProducts" :key="product.product.id" :value="product.product.id">
                                        {{ product.product.url_title }}
                                    </option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="option_id">Select Product Option</label>
                                <select v-model="newOption.product_option_id" class="form-control" required>
                                    <option value="">Choose an option...</option>
                                    <option v-for="option in availableOptions" :key="option.id" :value="option.id">
                                        {{ option.name }} - {{ option.price }} {{ option.currency }}
                                    </option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="quantity">Quantity</label>
                                <input type="number" v-model="newOption.quantity" class="form-control" min="0" required>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" @click="closeAddModal">Cancel</button>
                        <button type="button" class="btn btn-primary" @click="addProductOption" :disabled="availableProducts.length === 0 || (newOption.product_id && availableOptions.length === 0)">Add Option</button>
                    </div>
                </div>
            </div>
        </div>

    </div>
</template>

<script>
    import StackModal from '@innologica/vue-stackable-modal'

    export default {
        components: {
            StackModal,
        },
        props: [
            // 'warehouse_id'
        ],
        data(){
            return {
                product_options: [],
                availableProducts: [],
                availableOptions: [],
                loading: false,
                showAddOptionModal: false,
                newOption: {
                    product_id: '',
                    product_option_id: '',
                    quantity: 0
                }
            }
        },

        mounted() {
            this.get_data()

            document.querySelector('body').style.marginLeft = '0';
            document.querySelector('.admin_page_header_navbar').style.marginLeft = '0';
        },

        methods: {
            go_back(){
                if (window.confirm('Added information will be deleted!!! Are you sure, you want go back?')) {
                    this.$router.go(-1)
                }
            },

            get_data(){
                this.getWarehouseProductOptions();
                this.getAvailableProducts();
            },

            getWarehouseProductOptions() {
                this.loading = true;
                axios.get(`/warehouse/get_warehouse_product_options/${this.$route.params.id}`)
                .then(response => {
                    this.product_options = response.data
                    
                    this.loading = false;
                })
                .catch(error => {
                    console.error('Error fetching warehouse product options:', error);
                    this.loading = false;
                });
            },

            getAvailableProducts() {
                axios.get(`/product/get_all_products/`)
                .then(response => {
                    this.availableProducts = response.data;
                })
                .catch(error => {
                    console.error('Error fetching products:', error);
                });
            },

            getAvailableOptions() {
                // alert(this.newOption.product_id)
                if (this.newOption.product_id) {
                    axios.get(`/product_option/get_activ_product_options/${this.newOption.product_id}`)
                    .then(response => {
                        this.availableOptions = response.data.options || [];
                    })
                    .catch(error => {
                        console.error('Error fetching product options:', error);
                        this.availableOptions = [];
                    });
                }
            },

            addProductOption() {
                if (!this.newOption.product_id || !this.newOption.product_option_id) {
                    alert('Please select both product and product option');
                    return;
                }

                axios.post('/warehouse/add_product_option_to_warehouse/' + this.$route.params.id, {
                    product_option_id: this.newOption.product_option_id,
                    quantity: this.newOption.quantity || 0
                })
                .then(response => {
                    this.closeAddModal();
                    this.getWarehouseProductOptions();
                })
                .catch(error => {
                    console.error('Error adding product option:', error);
                    alert('Error adding product option');
                });
            },



            deleteOption(option) {
                if (confirm(`Are you sure you want to delete "${option.name}" from this warehouse?`)) {
                    axios.delete(`/warehouse/delete_product_option_from_warehouse/${this.$route.params.id}/${option.id}`)
                    .then(response => {
                        this.getWarehouseProductOptions();
                    })
                    .catch(error => {
                        console.error('Error deleting product option:', error);
                        alert('Error deleting product option');
                    });
                }
            },

            getWarehouseQuantity(optionId) {
                // Find the option in our filtered list and get its quantity from pivot
                const option = this.product_options.find(opt => opt.id === optionId);
                return option && option.pivot ? option.pivot.quantity || 0 : 0;
            },

            getOptionImage(option) {
                // Get the first image for the option
                if (option.images && option.images.length > 0) {
                    return '/images/product_option_img/' + option.images[0].image;
                }
                // Fallback to a default image or placeholder
                return '/images/placeholder.jpg'; // You can change this to your default image path
            },

            updateQuantityQuick(option) {
                const newQuantity = parseInt(option.pivot.quantity) || 0;

                if (newQuantity <= 0) {
                    // Automatically delete the option if quantity is 0 or negative
                    this.deleteOption(option);
                } else {
                    // Update the quantity
                    axios.post(`/warehouse/edit_product_option_quantity/${this.$route.params.id}/${option.id}`, {
                        quantity: newQuantity
                    })
                    .then(response => {
                        // Update successful
                        console.log('Quantity updated successfully');
                        // Refresh the list to get updated data
                        this.getWarehouseProductOptions();
                    })
                    .catch(error => {
                        console.error('Error updating quantity:', error);
                        alert('Error updating quantity');
                        // Revert the change on error
                        this.getWarehouseProductOptions();
                    });
                }
            },

            closeAddModal() {
                this.showAddOptionModal = false;
                this.newOption = {
                    product_id: '',
                    product_option_id: '',
                    quantity: 0
                };
                this.availableOptions = [];
            }
        }
    }
</script>
