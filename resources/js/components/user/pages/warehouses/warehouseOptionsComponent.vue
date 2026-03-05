<template>
    <div class="row">
        <div class="col-md-12">
            <h1>Warehouse: {{ warehouse.name || 'Loading...' }}</h1>
            <h2 v-if="warehouse.general">This warehouse is general!</h2>
        </div>
        <div class="col-md-12 mb-3">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-center mb-2">
                <div class="d-flex align-items-center mb-2 mb-md-0">
                    <button class="btn btn-success mr-2" @click="go_back()">Go back</button>
                </div>
                <div class="d-flex align-items-center flex-column flex-md-row">
                    <button class="btn btn-success mb-2 mb-md-0 mr-md-3" @click="get_data()">Refresh</button>
                    <button class="btn btn-primary" @click="showAddOptionModal()">
                        Add Product Option
                    </button>
                </div>
            </div>
            <div class="d-flex align-items-center">
                <div class="btn-group">
                    <button 
                        class="btn" 
                        :class="viewMode === 'flat' ? 'btn-primary' : 'btn-secondary'"
                        @click="viewMode = 'flat'"
                    >
                        Flat View
                    </button>
                    <button 
                        class="btn" 
                        :class="viewMode === 'grouped' ? 'btn-primary' : 'btn-secondary'"
                        @click="viewMode = 'grouped'"
                    >
                        Grouped by Product
                    </button>
                </div>
            </div>
        </div>

        <div class="col-md-12">
            <h3>Warehouse Product Options</h3>
        </div>

        <div class="col-md-12">
            <!-- Loading state -->
            <div v-if="loading" class="text-center">
                <div class="spinner-border" role="status">
                    <span class="sr-only">Loading...</span>
                </div>
            </div>

            <!-- Grouped View -->
            <div v-else-if="viewMode === 'grouped' && grouped_options.length > 0">
                <div v-for="productGroup in grouped_options" :key="productGroup.product_id" class="card mb-3">
                    <div class="card-header bg-light">
                        <h5 class="mb-0">
                            <span class="badge badge-info mr-2">Product</span>
                            {{ productGroup.product_name }}
                            <span class="badge badge-secondary ml-2">{{ productGroup.options.length }} options</span>
                        </h5>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-striped mb-0">
                                <thead>
                                    <tr>
                                        <th>Image</th>
                                        <th>ID</th>
                                        <th>Option Name</th>
                                        <th>Price</th>
                                        <th>Quantity</th>
                                        <th v-if="warehouse.general">Task</th>
                                        <th>Migration</th>
                                        <th>Delete</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="option in productGroup.options" :key="option.id">
                                        <td>
                                            <img :src="getOptionImage(option)" :alt="option.name" style="width: 50px; height: 50px; object-fit: cover;" />
                                        </td>
                                        <td class="d-none d-md-table-cell">{{ option.id }}</td>
                                        <td>{{ option.name }}</td>
                                        <td class="d-none d-md-table-cell">{{ option.price }} {{ option.currency }}</td>
                                        <td>
                                            <div class="quantity-controls d-flex align-items-center justify-content-center">
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
                                                    class="form-control form-control-sm quantity-input mx-1 mx-md-2"
                                                    min="0"
                                                    style="width: 70px; text-align: center; height: 36px;"
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
                                        <td v-if="warehouse.general" class="d-none d-md-table-cell">
                                            <button class="btn btn-sm btn-success ml-1" @click="make_prodaction_task(option)">
                                                Make task
                                            </button>
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
                    </div>
                </div>
            </div>

            <!-- Flat View -->
            <div v-else-if="viewMode === 'flat' && product_options.length > 0" class="table-responsive">
                <table class="table table-striped" style="overflow-x: hidden;">
                    <thead>
                        <tr>
                            <th>Image</th>
                            <th class="d-none d-md-table-cell">ID</th>
                            <th>Product Name</th>
                            <th class="d-none d-md-table-cell">Option Name</th>
                            <th class="d-none d-md-table-cell">Price</th>
                            <th>Quantity</th>
                            <th v-if="warehouse.general" class="d-none d-md-table-cell">Task</th>
                            <th>Migration</th>
                            <th>Delete</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="option in product_options" :key="option.id">
                            <td>
                                <img :src="getOptionImage(option)" :alt="option.name" style="width: 50px; height: 50px; object-fit: cover;" />
                            </td>
                            <td class="d-none d-md-table-cell">{{ option.id }}</td>
                            <td>{{ option.product.url_title }}</td>
                            <td class="d-none d-md-table-cell">{{ option.name }}</td>
                            <td class="d-none d-md-table-cell">{{ option.price }} {{ option.currency }}</td>
                            <td>
                                <div class="quantity-controls d-flex align-items-center justify-content-center">
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
                                        class="form-control form-control-sm quantity-input mx-1 mx-md-2"
                                        min="0"
                                        style="width: 70px; text-align: center; height: 36px;"
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
                            <td v-if="warehouse.general" class="d-none d-md-table-cell">
                                <button class="btn btn-sm btn-success ml-1" @click="make_prodaction_task(option)">
                                    Make task
                                </button>
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
                grouped_options: [],
                loading: false,
                warehouse: [],
                viewMode: 'grouped' // 'flat' or 'grouped'
            }
        },

        mounted() {
            this.get_data()

            document.querySelector('body').style.marginLeft = '0';
            document.querySelector('.admin_page_header_navbar').style.marginLeft = '0';
        },

        methods: {
            go_back(){
                // if (window.confirm('Added information will be deleted!!! Are you sure, you want go back?')) {
                    this.$router.go(-1)
                // }
            },

            make_prodaction_task(){
                alert('This feature is coming soon!')
            },

            get_data(){
                this.getWarehouseProductOptionsGrouped();
                this.getWarehouseProductOptions();
                this.getCurentWarehouse();
            },

            showAddOptionModal(){
                this.$refs.addOptionModalRef.show_modal();
            },

            getWarehouseProductOptionsGrouped() {
                this.loading = true;
                axios.get(`/get_warehouse/get_warehouse_product_options_grouped/${this.$route.params.id}`)
                .then(response => {
                    this.grouped_options = response.data
                    this.loading = false;
                })
                .catch(error => {
                    alert(error);
                    this.loading = false;
                });
            },

            getWarehouseProductOptions() {
                this.loading = true;
                axios.get(`/get_warehouse/get_warehouse_product_options/${this.$route.params.id}`)
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
                .get('/get_warehouse/get_warehouse_data/' + this.$route.params.id)
                .then(response => {
                    this.warehouse = response.data
                })
                .catch(error => console.log(error))
            },

            getAvailableOptions() {
                // alert(this.newOption.product_id)
                if (this.newOption.product_id) {
                    axios.get(`/get_product/get_product_option/get_activ_product_options/${this.newOption.product_id}`)
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
                    axios.delete(`/set_warehouse/delete_product_option_from_warehouse/${this.$route.params.id}/${option.id}`)
                    .then(response => {
                        this.getWarehouseProductOptions();
                        this.getWarehouseProductOptionsGrouped();
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
                    axios.post(`/set_warehouse/edit_product_option_quantity/${this.$route.params.id}/${option.id}`, {
                        quantity: newQuantity
                    })
                    .then(response => {
                        // Update successful
                        console.log('Quantity updated successfully');
                        // Refresh the list to get updated data
                        this.getWarehouseProductOptions();
                        this.getWarehouseProductOptionsGrouped();
                    })
                    .catch(error => {
                        alert(error);
                    });
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
                    axios.post(`/set_warehouse/edit_product_option_quantity/${this.$route.params.id}/${option.id}`, {
                        quantity: newQuantity
                    })
                    .then(response => {
                        console.log('Quantity updated successfully');
                    })
                    .catch(error => {
                        alert(error);
                    });

                    this.getWarehouseProductOptions();
                    this.getWarehouseProductOptionsGrouped();
                }
            },
        }
}
</script>
