<template>
    <div class="row">
        <div class="col-md-12">
            <h1>Warehouse: {{ warehouse.name || 'Loading...' }}</h1>
        </div>
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
                <button class="btn btn-primary" @click="showAddOptionModal()">
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
                            <th>Migration</th>
                            <th>Delete</th>
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
                                <div class="quantity-controls d-flex align-items-center">
                                    <button 
                                        type="button" 
                                        class="btn btn-sm btn-danger"
                                        @click="decrementQuantity(option)"
                                        :disabled="option.pivot.quantity <= 0"
                                        title="Decrease quantity"
                                    >
                                        -
                                    </button>
                                    
                                    <input
                                        type="number"
                                        v-model="option.pivot.quantity"
                                        @change="updateQuantityQuick(option)"
                                        class="form-control form-control-sm quantity-input mx-2"
                                        min="0"
                                        style="width: 100px; text-align: center; height: 36px;"
                                    />
                                    
                                    <button 
                                        type="button" 
                                        class="btn btn-sm btn-info"
                                        @click="incrementQuantity(option)"
                                        title="Increase quantity"
                                    >
                                        +
                                    </button>
                                </div>
                            </td>
                            <td>
                                <button class="btn btn-sm btn-warning ml-1" @click="openMigrateModal(option, option.pivot.quantity)">
                                    Migrate
                                </button>
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
                <button class="btn btn-primary" @click="showAddOptionModal()">
                    Add First Product Option
                </button>
            </div>
        </div>

        <addOptionModal ref="addOptionModalRef" @update="get_data()" />
        <migrateOptionModal ref="migrateOptionModalRef" @update="get_data()" />

    </div>
</template>

<script>
    import addOptionModal from './modals/addProductOptionModalComponent.vue'
    import migrateOptionModal from './modals/migratePtroductOptionModalComponent.vue'

    export default {
        components: {
            addOptionModal,
            migrateOptionModal
        },
        props: [
            // 'warehouse_id'
        ],
        data(){
            return {
                product_options: [],
                loading: false,
                warehouse: []
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
                this.getCurentWarehouse();
            },

            showAddOptionModal(){
                this.$refs.addOptionModalRef.show_modal();
            },

            getWarehouseProductOptions() {
                this.loading = true;
                axios.get(`/warehouse/get_warehouse_product_options/${this.$route.params.id}`)
                .then(response => {
                    this.product_options = response.data
                    
                    this.loading = false;
                })
                .catch(error => {
                    alert(error);
                    this.loading = false;
                });
            },

            getCurentWarehouse(){
                axios
                .get('/warehouse/get_warehouse_data/' + this.$route.params.id)
                .then(response => {
                    this.warehouse = response.data
                })
                .catch(error => console.log(error))
            },

            getAvailableOptions() {
                // alert(this.newOption.product_id)
                if (this.newOption.product_id) {
                    axios.get(`/product_option/get_activ_product_options/${this.newOption.product_id}`)
                    .then(response => {
                        this.availableOptions = response.data.options || [];
                    })
                    .catch(error => {
                        alert(error);
                        this.availableOptions = [];
                    });
                }
            },

            // addProductOption() {
            //     if (!this.newOption.product_id || !this.newOption.product_option_id) {
            //         alert('Please select both product and product option');
            //         return;
            //     }

            //     axios.post('/warehouse/add_product_option_to_warehouse/' + this.$route.params.id, {
            //         product_option_id: this.newOption.product_option_id,
            //         quantity: this.newOption.quantity || 0
            //     })
            //     .then(response => {
            //         this.closeAddModal();
            //         this.getWarehouseProductOptions();
            //     })
            //     .catch(error => {
            //         alert(error);
            //     });
            // },

            openMigrateModal(option, maxQuantity) {
                this.$refs.migrateOptionModalRef.show_modal(option, maxQuantity);
            },

            deleteOption(option) {
                if (confirm(`Are you sure you want to delete "${option.name}" from this warehouse?`)) {
                    axios.delete(`/warehouse/delete_product_option_from_warehouse/${this.$route.params.id}/${option.id}`)
                    .then(response => {
                        this.getWarehouseProductOptions();
                    })
                    .catch(error => {
                        alert(error);
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
                    })
                    .catch(error => {
                        alert(error);
                    });
                        
                    this.getWarehouseProductOptions();
                }
            },

            incrementQuantity(option) {
                const currentQuantity = parseInt(option.pivot.quantity) || 0;
                const newQuantity = currentQuantity + 1;
                option.pivot.quantity = newQuantity;
                this.updateQuantity(option, newQuantity);
            },

            decrementQuantity(option) {
                const currentQuantity = parseInt(option.pivot.quantity) || 0;
                if (currentQuantity > 0) {
                    const newQuantity = currentQuantity - 1;
                    option.pivot.quantity = newQuantity;
                    this.updateQuantity(option, newQuantity);
                }
            },

            updateQuantity(option, newQuantity) {
                if (newQuantity <= 0) {
                    // Automatically delete the option if quantity is 0 or negative
                    this.deleteOption(option);
                } else {
                    // Update the quantity
                    axios.post(`/warehouse/edit_product_option_quantity/${this.$route.params.id}/${option.id}`, {
                        quantity: newQuantity
                    })
                    .then(response => {
                        console.log('Quantity updated successfully');
                    })
                    .catch(error => {
                        alert(error);
                    });

                    this.getWarehouseProductOptions();
                }
            },
        }
}
</script>
