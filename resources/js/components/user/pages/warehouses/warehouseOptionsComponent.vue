<template>
    <div class="row">
        <div class="col-md-12">
            <h1>{{ $t('admin.warehouses.warehouse_prefix') }} {{ warehouse.name || $t('admin.export.loading_ellipsis') }}</h1>
            <h2 v-if="warehouse.general">{{ $t('admin.warehouses.this_warehouse_is_general') }}</h2>
        </div>
        <div class="col-md-12 mb-3">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-center mb-2">
                <div class="d-flex align-items-center mb-2 mb-md-0">
                    <button class="btn btn-success mr-2" @click="go_back()">{{ $t('common.back') }}</button>
                </div>
                <div class="d-flex align-items-center flex-column flex-md-row">
                    <button class="btn btn-success mb-2 mb-md-0 mr-md-3" @click="get_data()">{{ $t('common.refresh') }}</button>
                    <button class="btn btn-warning mb-2 mb-md-0 mr-md-3" @click="toggleScanMode()">
                        {{ scanMode ? $t('admin.warehouses.close_scanner_btn') : $t('admin.warehouses.scan_barcode_btn') }}
                    </button>
                    <button class="btn btn-primary" @click="showAddOptionModal()">
                        {{ $t('admin.warehouses.add_product_option_btn') }}
                    </button>
                </div>
            </div>

            <!-- Barcode scan panel -->
            <div v-if="scanMode" class="card border-warning mb-3">
                <div class="card-header bg-warning text-dark d-flex justify-content-between align-items-center">
                    <strong>{{ $t('admin.warehouses.barcode_scanner_title') }}</strong>
                    <button type="button" class="btn btn-sm btn-secondary" @click="toggleScanMode()">{{ $t('common.close') }}</button>
                </div>
                <div class="card-body">
                    <div class="d-flex align-items-center" style="gap: 10px;">
                        <input
                            ref="barcodeInput"
                            type="text"
                            v-model="scanBarcode"
                            @keydown.enter.prevent="submitScan()"
                            class="form-control"
                            :placeholder="$t('admin.warehouses.scan_barcode_placeholder')"
                            autocomplete="off"
                            style="max-width: 400px;"
                        />
                        <input
                            type="number"
                            v-model="scanQuantity"
                            class="form-control"
                            min="1"
                            style="width: 90px;"
                            :placeholder="$t('admin.warehouses.qty_placeholder')"
                        />
                        <button class="btn btn-primary" @click="submitScan()" :disabled="scanLoading || !scanBarcode.trim()">
                            <span v-if="scanLoading" class="spinner-border spinner-border-sm" role="status"></span>
                            {{ scanLoading ? '...' : $t('admin.warehouses.add_btn') }}
                        </button>
                    </div>
                    <div v-if="scanResult" class="mt-2 alert" :class="scanResult.success ? 'alert-success' : 'alert-danger'">
                        {{ scanResult.message }}
                    </div>
                </div>
            </div>
            <div class="d-flex align-items-center">
                <div class="btn-group">
                    <button
                        class="btn"
                        :class="viewMode === 'flat' ? 'btn-primary' : 'btn-secondary'"
                        @click="viewMode = 'flat'"
                    >
                        {{ $t('admin.warehouses.flat_view_btn') }}
                    </button>
                    <button
                        class="btn"
                        :class="viewMode === 'grouped' ? 'btn-primary' : 'btn-secondary'"
                        @click="viewMode = 'grouped'"
                    >
                        {{ $t('admin.warehouses.grouped_by_product_btn') }}
                    </button>
                </div>
            </div>
        </div>

        <div class="col-md-12 d-flex justify-content-between align-items-center">
            <h3>{{ $t('admin.warehouses.warehouse_product_options_title') }}</h3>
            <div class="alert alert-info mb-0 py-2 px-3">
                <strong>{{ $t('admin.warehouses.total_stock_value_prefix') }} {{ totalWarehousePrice }} ₾</strong>
            </div>
        </div>

        <!-- Filters -->
        <div class="col-md-12 mb-3">
            <div class="d-flex flex-wrap align-items-center" style="gap: 10px;">
                <div style="min-width: 180px;">
                    <select class="form-control" v-model="filterBrand">
                        <option value="">{{ $t('admin.warehouses.all_brands_option') }}</option>
                        <option v-for="brand in availableBrands" :key="brand.id" :value="brand.id">
                            {{ brand.name }}
                        </option>
                    </select>
                </div>
                <div style="min-width: 180px;">
                    <select class="form-control" v-model="filterCategory">
                        <option value="">{{ $t('admin.export.all_categories_option') }}</option>
                        <option v-for="cat in availableCategories" :key="cat.id" :value="cat.id">
                            {{ cat.name }}
                        </option>
                    </select>
                </div>
                <button v-if="filterBrand || filterCategory" class="btn btn-secondary btn-sm" @click="filterBrand = ''; filterCategory = ''">
                    {{ $t('admin.warehouses.clear_filters_btn') }}
                </button>
            </div>
        </div>

        <div class="col-md-12">
            <!-- Loading state -->
            <div v-if="loading" class="text-center">
                <div class="spinner-border" role="status">
                    <span class="sr-only">{{ $t('admin.export.loading_ellipsis') }}</span>
                </div>
            </div>

            <!-- Grouped View -->
            <div v-else-if="viewMode === 'grouped' && filtered_grouped_options.length > 0">
                <div v-for="productGroup in filtered_grouped_options" :key="productGroup.product_id" class="card mb-3">
                    <div class="card-header bg-light">
                        <h5 class="mb-0">
                            <span class="badge badge-info mr-2">{{ $t('admin.warehouses.product_badge') }}</span>
                            {{ productGroup.product_name }}
                            <span class="badge badge-secondary ml-2">{{ productGroup.options.length }} {{ $t('admin.warehouses.options_suffix') }}</span>
                        </h5>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-striped mb-0">
                                <thead>
                                    <tr>
                                        <th>{{ $t('admin.warehouses.col_image') }}</th>
                                        <th>{{ $t('common.id') }}</th>
                                        <th>{{ $t('admin.warehouses.col_option_name') }}</th>
                                        <th>{{ $t('common.price') }}</th>
                                        <th>{{ $t('admin.warehouses.col_quantity') }}</th>
                                        <th v-if="warehouse.general">{{ $t('admin.warehouses.col_task') }}</th>
                                        <th>{{ $t('admin.warehouses.col_migration') }}</th>
                                        <th>{{ $t('common.delete') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="option in productGroup.options" :key="option.id">
                                        <td>
                                            <img :src="getOptionImage(option)" :alt="option.name" style="width: 50px; height: 50px; object-fit: cover;" />
                                        </td>
                                        <td>{{ option.id }}</td>
                                        <td>{{ option.name }}</td>
                                        <td>{{ option.price }} {{ option.currency }}</td>
                                        <td>
                                            <div class="quantity-controls d-flex align-items-center justify-content-center">
                                                <button
                                                    type="button"
                                                    class="btn btn-sm btn-danger"
                                                    @click="decrementQuantity(option)"
                                                    :disabled="option.pivot.quantity <= 0"
                                                    :title="$t('admin.warehouses.decrease_quantity_tooltip')"
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
                                                    :title="$t('admin.warehouses.increase_quantity_tooltip')"
                                                >
                                                    +
                                                </button>
                                            </div>
                                        </td>
                                        <td v-if="warehouse.general">
                                            <button class="btn btn-sm btn-success ml-1" @click="make_prodaction_task(option)">
                                                {{ $t('admin.warehouses.make_task_btn') }}
                                            </button>
                                        </td>
                                        <td>
                                            <button class="btn btn-sm btn-warning ml-1" @click="openMigrateModal(option, option.pivot.quantity)">
                                                {{ $t('admin.warehouses.migrate_btn') }}
                                            </button>
                                        </td>
                                        <td>
                                            <button class="btn btn-sm btn-danger" @click="deleteOption(option)">
                                                {{ $t('common.delete') }}
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
            <div v-else-if="viewMode === 'flat' && filtered_options.length > 0" class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>{{ $t('admin.warehouses.col_image') }}</th>
                            <th>{{ $t('common.id') }}</th>
                            <th>{{ $t('admin.warehouses.col_product_name') }}</th>
                            <th>{{ $t('admin.warehouses.col_option_name') }}</th>
                            <th>{{ $t('common.price') }}</th>
                            <th>{{ $t('admin.warehouses.col_quantity') }}</th>
                            <th v-if="warehouse.general">{{ $t('admin.warehouses.col_task') }}</th>
                            <th>{{ $t('admin.warehouses.col_migration') }}</th>
                            <th>{{ $t('common.delete') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="option in filtered_options" :key="option.id">
                            <td>
                                <img :src="getOptionImage(option)" :alt="option.name" style="width: 50px; height: 50px; object-fit: cover;" />
                            </td>
                            <td>{{ option.id }}</td>
                            <td>{{ option.product.url_title }}</td>
                            <td>{{ option.name }}</td>
                            <td>{{ option.price }} {{ option.currency }}</td>
                            <td>
                                <div class="quantity-controls d-flex align-items-center justify-content-center">
                                    <button
                                        type="button"
                                        class="btn btn-sm btn-danger"
                                        @click="decrementQuantity(option)"
                                        :disabled="option.pivot.quantity <= 0"
                                        :title="$t('admin.warehouses.decrease_quantity_tooltip')"
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
                                        :title="$t('admin.warehouses.increase_quantity_tooltip')"
                                    >
                                        +
                                    </button>
                                </div>
                            </td>
                            <td v-if="warehouse.general">
                                <button class="btn btn-sm btn-success ml-1" @click="make_prodaction_task(option)">
                                    {{ $t('admin.warehouses.make_task_btn') }}
                                </button>
                            </td>
                            <td>
                                <button class="btn btn-sm btn-warning ml-1" @click="openMigrateModal(option, option.pivot.quantity)">
                                    {{ $t('admin.warehouses.migrate_btn') }}
                                </button>
                            </td>
                            <td>
                                <button class="btn btn-sm btn-danger" @click="deleteOption(option)">
                                    {{ $t('common.delete') }}
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Empty state -->
            <div v-else class="text-center">
                <p v-if="filterBrand || filterCategory">{{ $t('admin.warehouses.no_products_match_filters') }}</p>
                <p v-else>{{ $t('admin.warehouses.no_product_options_found') }}</p>
                <button v-if="!filterBrand && !filterCategory" class="btn btn-primary" @click="showAddOptionModal()">
                    {{ $t('admin.warehouses.add_first_product_option_btn') }}
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
        computed: {
            availableBrands() {
                const map = {};
                this.product_options.forEach(opt => {
                    const brand = opt.product?.brand;
                    if (brand) {
                        const name = brand.us_brand?.title || `Brand #${brand.id}`;
                        map[brand.id] = { id: brand.id, name };
                    }
                });
                return Object.values(map).sort((a, b) => a.name.localeCompare(b.name));
            },

            availableCategories() {
                const map = {};
                this.product_options.forEach(opt => {
                    const sub = opt.product?.product_subcategory;
                    if (sub) {
                        const cat = sub.category;
                        if (cat) {
                            map[cat.id] = { id: cat.id, name: cat.us_name || `Category #${cat.id}` };
                        }
                    }
                });
                return Object.values(map).sort((a, b) => a.name.localeCompare(b.name));
            },

            filtered_options() {
                return this.product_options.filter(opt => {
                    if (this.filterBrand && opt.product?.brand_id != this.filterBrand) return false;
                    if (this.filterCategory) {
                        const catId = opt.product?.product_subcategory?.category?.id;
                        if (catId != this.filterCategory) return false;
                    }
                    return true;
                });
            },

            filtered_grouped_options() {
                if (!this.filterBrand && !this.filterCategory) return this.grouped_options;
                return this.grouped_options.map(group => {
                    const filtered = group.options.filter(opt => {
                        if (this.filterBrand && opt.product?.brand_id != this.filterBrand) return false;
                        if (this.filterCategory) {
                            const catId = opt.product?.product_subcategory?.category?.id;
                            if (catId != this.filterCategory) return false;
                        }
                        return true;
                    });
                    return { ...group, options: filtered };
                }).filter(group => group.options.length > 0);
            },

            totalWarehousePrice() {
                return this.filtered_options.reduce((total, option) => {
                    const price = parseFloat(option.price) || 0;
                    const qty = parseInt(option.pivot?.quantity) || 0;
                    return total + (price * qty);
                }, 0).toFixed(2);
            }
        },
        data(){
            return {
                product_options: [],
                grouped_options: [],
                loading: false,
                warehouse: [],
                viewMode: 'grouped',
                filterBrand: '',
                filterCategory: '',
                scanMode: false,
                scanBarcode: '',
                scanQuantity: 1,
                scanLoading: false,
                scanResult: null,
            }
        },

        mounted() {
            this.get_data()

            document.querySelector('body').style.marginLeft = '0';
            document.querySelector('.admin_page_header_navbar').style.marginLeft = '0';
        },

        methods: {
            go_back(){
                // if (window.confirm(this.$t('common.confirm_leave_unsaved'))) {
                    this.$router.go(-1)
                // }
            },

            toggleScanMode() {
                this.scanMode = !this.scanMode;
                this.scanBarcode = '';
                this.scanResult = null;
                this.scanQuantity = 1;
                if (this.scanMode) {
                    this.$nextTick(() => {
                        this.$refs.barcodeInput && this.$refs.barcodeInput.focus();
                    });
                }
            },

            submitScan() {
                const barcode = this.scanBarcode.trim();
                if (!barcode) return;
                this.scanLoading = true;
                this.scanResult = null;
                axios.post(`/set_warehouse/scan_barcode/${this.$route.params.id}`, {
                    barcode: barcode,
                    quantity: this.scanQuantity || 1
                })
                .then(response => {
                    const opt = response.data.product_option;
                    const name = opt ? `${opt.product?.url_title || ''} — ${opt.name}` : '';
                    this.scanResult = { success: true, message: `${this.$t('admin.warehouses.added_prefix')} ${name}` };
                    this.scanBarcode = '';
                    this.get_data();
                    this.$nextTick(() => {
                        this.$refs.barcodeInput && this.$refs.barcodeInput.focus();
                    });
                })
                .catch(error => {
                    const msg = error.response?.data?.error || this.$t('admin.warehouses.scan_failed');
                    this.scanResult = { success: false, message: msg };
                    this.scanBarcode = '';
                    this.$nextTick(() => {
                        this.$refs.barcodeInput && this.$refs.barcodeInput.focus();
                    });
                })
                .finally(() => {
                    this.scanLoading = false;
                });
            },

            make_prodaction_task(){
                alert(this.$t('admin.warehouses.coming_soon_feature'))
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
                if (confirm(this.$t('admin.warehouses.confirm_delete_option', { name: option.name }))) {
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
                    this.deleteOption(option);
                } else {
                    axios.post(`/set_warehouse/edit_product_option_quantity/${this.$route.params.id}/${option.id}`, {
                        quantity: newQuantity
                    })
                    .then(() => {
                        this.getWarehouseProductOptions();
                        this.getWarehouseProductOptionsGrouped();
                    })
                    .catch(error => {
                        alert(error);
                    });
                }
            },
        }
}
</script>
