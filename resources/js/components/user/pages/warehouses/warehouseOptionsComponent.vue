<template>
    <div class="row">
        <div class="col-md-12">
            <div class="form-group">
                <button class="btn btn-success float-right" @click="go_back()">Go back</button>
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

            <!-- Product Options List -->
            <div v-else-if="product_options.length > 0" class="row">
                <div v-for="option in product_options" :key="option.id" class="col-md-4 mb-3">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">{{ option.name }}</h5>
                            <p class="card-text">
                                <strong>Price:</strong> {{ option.price }} {{ option.currency }}<br>
                                <strong>Available Quantity:</strong> {{ getWarehouseQuantity(option.id) }}<br>
                                <strong>Product:</strong> {{ option.product ? option.product.name : 'N/A' }}
                            </p>
                            <div class="d-flex justify-content-between">
                                <button class="btn btn-sm btn-warning" @click="editQuantity(option)">
                                    Edit Quantity
                                </button>
                                <button class="btn btn-sm btn-danger" @click="deleteOption(option)">
                                    Delete
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
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
                        <form @submit.prevent="addProductOption">
                            <div class="form-group">
                                <label for="product_id">Select Product</label>
                                <select v-model="newOption.product_id" class="form-control" required>
                                    <option value="">Choose a product...</option>
                                    <option v-for="product in availableProducts" :key="product.id" :value="product.id">
                                        {{ product.name }}
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
                        <button type="button" class="btn btn-primary" @click="addProductOption">Add Option</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Edit Quantity Modal -->
        <div v-if="showEditModal" class="modal" tabindex="-1" role="dialog" style="display: block;">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Edit Quantity</h5>
                        <button type="button" class="close" @click="closeEditModal">
                            <span>&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <p><strong>Product Option:</strong> {{ editingOption.name }}</p>
                        <form @submit.prevent="updateQuantity">
                            <div class="form-group">
                                <label for="edit_quantity">New Quantity</label>
                                <input type="number" v-model="editingOption.newQuantity" class="form-control" min="0" required>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" @click="closeEditModal">Cancel</button>
                        <button type="button" class="btn btn-primary" @click="updateQuantity">Update Quantity</button>
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
            'warehouse_id'
        ],
        data(){
            return {
                product_options: [],
                availableProducts: [],
                availableOptions: [],
                loading: false,
                showAddOptionModal: false,
                showEditModal: false,
                newOption: {
                    product_id: '',
                    product_option_id: '',
                    quantity: 0
                },
                editingOption: {
                    id: null,
                    name: '',
                    newQuantity: 0
                }
            }
        },

        mounted() {
            this.getWarehouseProductOptions();
            this.getAvailableProducts();
            document.querySelector('body').style.marginLeft = '0';
            document.querySelector('.admin_page_header_navbar').style.marginLeft = '0';
        },

        methods: {
            go_back(){
                if (window.confirm('Added information will be deleted!!! Are you sure, you want go back?')) {
                    this.$router.go(-1)
                }
            },

            getWarehouseProductOptions() {
                this.loading = true;
                axios.get(`/api/user/warehouse/get_warehouse_product_options`, {
                    params: { warehouse_id: this.warehouse_id }
                })
                .then(response => {
                    this.product_options = response.data;
                    this.loading = false;
                })
                .catch(error => {
                    console.error('Error fetching warehouse product options:', error);
                    this.loading = false;
                });
            },

            getAvailableProducts() {
                axios.get('/api/shop/product/get_all_products')
                .then(response => {
                    this.availableProducts = response.data;
                })
                .catch(error => {
                    console.error('Error fetching products:', error);
                });
            },

            getAvailableOptions() {
                if (this.newOption.product_id) {
                    axios.get(`/api/shop/product_option/get_activ_product_options/${this.newOption.product_id}`)
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
                if (!this.newOption.product_id || !this.newOption.product_option_id || !this.newOption.quantity) {
                    alert('Please fill in all fields');
                    return;
                }

                axios.post('/api/user/warehouse/add_product_option_to_warehouse', {
                    warehouse_id: this.warehouse_id,
                    product_option_id: this.newOption.product_option_id
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

            editQuantity(option) {
                this.editingOption = {
                    id: option.id,
                    name: option.name,
                    newQuantity: this.getWarehouseQuantity(option.id)
                };
                this.showEditModal = true;
            },

            updateQuantity() {
                axios.post(`/api/user/warehouse/edit_product_option_quantity/${this.warehouse_id}/${this.editingOption.id}`, {
                    quantity: this.editingOption.newQuantity
                })
                .then(response => {
                    this.closeEditModal();
                    this.getWarehouseProductOptions();
                })
                .catch(error => {
                    console.error('Error updating quantity:', error);
                    alert('Error updating quantity');
                });
            },

            deleteOption(option) {
                if (confirm(`Are you sure you want to delete "${option.name}" from this warehouse?`)) {
                    axios.delete(`/api/user/warehouse/delete_product_option_from_warehouse/${this.warehouse_id}/${option.id}`)
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
                // This would need to be implemented based on how quantities are stored
                // For now, returning a placeholder
                return 0;
            },

            closeAddModal() {
                this.showAddOptionModal = false;
                this.newOption = {
                    product_id: '',
                    product_option_id: '',
                    quantity: 0
                };
                this.availableOptions = [];
            },

            closeEditModal() {
                this.showEditModal = false;
                this.editingOption = {
                    id: null,
                    name: '',
                    newQuantity: 0
                };
            }
        },

        watch: {
            'newOption.product_id': function(newVal) {
                if (newVal) {
                    this.getAvailableOptions();
                } else {
                    this.availableOptions = [];
                    this.newOption.product_option_id = '';
                }
            }
        }
    }
</script>
