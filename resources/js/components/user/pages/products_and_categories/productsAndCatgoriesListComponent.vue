<template>
    <div class="row">
        <!-- <div class="col-sm-3"> -->
            <left-menu />
        <!-- </div> -->
        <div class="col-sm-12">
            <div class="row">
                <div class="col-md-12">
                    <breadcrumb />
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12">
                    <tabsComponent
                        :table_data="data_for_tab"
                        @update="get_products_data"

                        @show_sale_code_add_modal="show_sale_code_add_modal"
                        @show_sale_code_edit_modal="show_sale_code_edit_modal"
                        @del_sale_code="del_sale_code"

                        @show_product_brand_add_modal="show_product_brand_add_modal"
                        @show_product_brand_edit_modal="show_product_brand_edit_modal"
                        @del_product_brand="del_product_brand"

                        @del_product_category="del_product_category"

                        @show_user_change_modal="show_user_change_modal"
                        @del_product="del_product"

                        @filter_by_brand_with_multi_id="filter_by_brand_with_multi_id"
                        @filter_by_sale_type_with_multi_id="filter_by_sale_type_with_multi_id"
                        @filter_by_subcategory_with_multi_id="filter_by_subcategory_with_multi_id"

                        @show_product_quick_view="show_product_quick_view"
                    />
                </div>
            </div>
        </div>
        <saleCodeAddModal ref="saleCodeAddModal" @update="get_products_data"/>
        <saleCodeEditModal ref="saleCodeEditModal" @update="get_products_data"/>

        <editProductBrandModal ref="editProductBrandModal" @update="get_products_data"/>
        <addProductBrandModal ref="addProductBrandModal" @update="get_products_data"/>

        <change_user_modal ref="userRelationModal" @update="get_products_data" />
        <productAdminQuickViewModal ref="productAdminQuickViewModal" />
    </div>
</template>

<script>
    import tabsComponent  from '../../items/data_table/TabsComponent.vue'
    import breadcrumb from '../../items/BreadcrumbComponent.vue'

    import saleCodeEditModal from "../../items/modal/tab_modals/edit/EditSaleCodeModalComponen.vue";
    import saleCodeAddModal from "../../items/modal/tab_modals/add/AddSaleCodeModalComponen.vue";
    
    import editProductBrandModal from "../../items/modal/tab_modals/edit/EditProductBrandModal.vue";
    import addProductBrandModal from "../../items/modal/tab_modals/add/AddProductBrandModal.vue";

    import change_user_modal from '../../items/modal/tab_modals/ChangeUserModalComponent.vue'
    import productAdminQuickViewModal from '../../items/modal/ProductAdminQuickViewModal.vue'
    export default {
        components: {
            tabsComponent ,
            breadcrumb,

            saleCodeEditModal,
            saleCodeAddModal,

            editProductBrandModal,
            addProductBrandModal,

            change_user_modal,
            productAdminQuickViewModal,
        },
        
        data() {
            return {
                data_for_tab:[],
                // Store data for filters
                brands_data: [],
                categories_data: [],
                subcategories_data: [],
                // Store current filter values
                filters: {
                    brand_id: 0,
                    sale_type_id: 0,
                    subcategory_id: 0
                },
                // Store original unfiltered data
                original_products_data: [],
                // Track loading state
                brands_loaded: false,
                categories_loaded: false,
                subcategories_loaded: false,
                products_loaded: false,
            }
        },

        mounted() {
            // Load all data in parallel, then build tabs
            Promise.all([
                this.loadProducts(),
                this.loadCategories(),
                this.loadSubcategories(),
                this.loadBrands()
            ]).then(() => {
                this.buildProductsTab();
                this.buildCategoriesTab();
                this.buildBrandsTab();
                this.get_sale_codes_data();
            }).catch(error => {
                console.log('Error loading data:', error);
            });
        },
        
        methods: {
            // Load products data
            loadProducts() {
                return axios.get("/get_product/get_all_products")
                    .then(response => {
                        this.original_products_data = response.data;
                        this.products_loaded = true;
                        return response;
                    });
            },
            
            // Load categories data
            loadCategories() {
                return axios.get("/get_product/get_product_category/get_all_product_category/")
                    .then(response => {
                        this.categories_data = response.data;
                        this.categories_loaded = true;
                        return response;
                    });
            },

            // Load subcategories data (this is what Product.subcategory_id actually points to)
            loadSubcategories() {
                return axios.get("/get_product/get_product_category/get_subcategory/get_all_subcategories")
                    .then(response => {
                        this.subcategories_data = response.data;
                        this.subcategories_loaded = true;
                        return response;
                    });
            },

            // Load brands data
            loadBrands() {
                return axios.get("/get_product/get_brand/get_all_brands")
                    .then(response => {
                        this.brands_data = response.data;
                        this.brands_loaded = true;
                        return response;
                    });
            },
            
            // Build products tab with all data loaded
            buildProductsTab() {
                // Prepare filter data (now brands and categories are loaded)
                const filter_data = this.get_multi_filter_data();
                
                this.data_for_tab.push({
                    'id': 1,
                    'table_name': this.$t('admin.shop.products_table'),
                    'list_page': process.env.MIX_APP_SSH + process.env.MIX_SHOP_URL + '/products',
                    'add_action': {
                        'action': 'route',
                        'link': 'productAdd',
                        'class': 'btn btn-primary'
                    },
                    'filter_data': filter_data,
                    'tab_data': {
                        'data': this.apply_filters(this.original_products_data).map(item => ({ ...item, id: item.product?.id })),
                        'tab': {
                            'head': [
                                this.$t('common.id'),
                                this.$t('common.title'),
                                this.$t('admin.common.public'),
                                this.$t('admin.shop.col_made_in_georgia'),
                                this.$t('admin.shop.sale_type_label'),
                                this.$t('admin.shop.col_options'),
                                this.$t('admin.shop.col_user'),
                                this.$t('admin.shop.col_view'),
                                this.$t('admin.shop.col_edit_user'),
                                this.$t('admin.shop.col_edit_options'),
                                this.$t('common.edit'),
                                this.$t('common.delete'),
                            ],
                            'body': [
                                ['data', ['product', 'id']],
                                ['data', ['product', 'url_title']],
                                ['data', ['product', 'published'], 'bool'],
                                ['data', ['product', 'made_in_georgia'], 'bool'],
                                ['data', ['product', 'sale_type']],
                                ['data', ['options']],
                                ['data', [['user', 'name'], ['user', 'surname']]],
                                ['action_fun_id', 'show_product_quick_view', 'btn btn-info', '<i class="fa fa-eye" aria-hidden="true"></i>', ['product', 'id']],
                                ['action_fun_id', 'show_user_change_modal', 'btn btn-secondary', '<i class="fa fa-user-plus" aria-hidden="true"></i>', ['product', 'id']],
                                ['action_router', 'productOptionsControl', 'btn btn-success', '<i class="fa fa-list" aria-hidden="true"></i>', ['product', 'id']],
                                ['action_router', 'productEdit', 'btn btn-primary', '<i aria-hidden="true" class="fa fa-pencil"></i>', ['product', 'id']],
                                ['action_fun_id', 'del_product', 'btn btn-danger', '<i aria-hidden="true" class="fa fa-trash"></i>',['product', 'id']],
                            ],
                            'perm': [
                                ['no'],
                                ['no'],
                                ['no'],
                                ['no'],
                                ['no'],
                                ['no'],
                                ['no'],
                                ['no'],
                                ['product', 'edit'],
                                ['no'],
                                ['product', 'edit'],
                                ['product', 'del'],
                            ]
                        }
                    },
                });
            },
            
            // Build categories tab
            buildCategoriesTab() {
                this.data_for_tab.push({
                    'id': 2,
                    'table_name': this.$t('admin.shop.categories_table'),
                    'add_action': {
                        'action': 'route',
                        'link': 'productCategoryAdd',
                        'class': 'btn btn-primary'
                    },
                    'tab_data': {
                        'data': this.categories_data,
                        'tab': {
                            'head': [
                                this.$t('common.id'),
                                this.$t('common.name'),
                                this.$t('common.edit'),
                                this.$t('common.delete'),
                            ],
                            'body': [
                                ['data', ['id']],
                                ['data', ['us_name']],
                                ['action_router', 'productCategoryEdit', 'btn btn-primary', '<i aria-hidden="true" class="fa fa-pencil"></i>'],
                                ['action_fun_id', 'del_product_category', 'btn btn-danger', '<i aria-hidden="true" class="fa fa-trash"></i>'],
                            ],
                            'perm': [
                                ['no'],
                                ['no'],
                                ['product_category', 'edit'],
                                ['product_category', 'del'],
                            ]
                        }
                    },
                });
            },
            
            // Build brands tab
            buildBrandsTab() {
                this.data_for_tab.push({
                    'id': 3,
                    'table_name': this.$t('admin.shop.brands_table'),
                    'add_action': {
                        'action': 'fun',
                        'link': 'show_product_brand_add_modal',
                        'class': 'btn btn-primary'
                    },
                    'tab_data': {
                        'data': this.brands_data.map(item => ({ ...item, id: item.global_brand?.id })),
                        'tab': {
                            'head': [
                                this.$t('common.id'),
                                this.$t('common.name'),
                                this.$t('common.edit'),
                                this.$t('common.delete'),
                            ],
                            'body': [
                                ['data', ['global_brand', 'id']],
                                ['data', ['us_brand', 'title']],
                                ['action_fun_id', 'show_product_brand_edit_modal', 'btn btn-primary', '<i aria-hidden="true" class="fa fa-pencil"></i>', ['global_brand', 'id']],
                                ['action_fun_id', 'del_product_brand', 'btn btn-danger', '<i aria-hidden="true" class="fa fa-trash"></i>', ['global_brand', 'id']],
                            ],
                            'perm': [
                                ['no'],
                                ['no'],
                                ['product_brand', 'edit'],
                                ['product_brand', 'del'],
                            ]
                        }
                    },
                });
            },
            
            // Prepare multi-filter data structure
            get_multi_filter_data() {
                return [
                    {
                        'id': 'brand_filter',
                        'title': this.$t('admin.shop.filter_brand'),
                        'data': this.brands_data,
                        'action_fun_id': 'filter_by_brand_with_multi_id',
                        'id_key': 'global_brand.id',
                        'array_key': 'us_brand.title'
                    },
                    {
                        'id': 'sale_type_filter',
                        'title': this.$t('admin.shop.filter_sale_type'),
                        'data': [
                            { id: 'online_order', name: this.$t('admin.shop.online_order') },
                            { id: 'produced_by_order', name: this.$t('admin.shop.produced_by_order') },
                            { id: 'custom_production', name: this.$t('admin.shop.custom_production') },
                            { id: 'donation', name: this.$t('admin.shop.donation_option') },
                            { id: 'outlet', name: this.$t('admin.shop.outlet_option') }
                        ],
                        'action_fun_id': 'filter_by_sale_type_with_multi_id',
                        'array_key': 'name'
                    },
                    {
                        'id': 'subcategory_filter',
                        'title': this.$t('admin.shop.filter_subcategory'),
                        'data': this.subcategories_data,
                        'action_fun_id': 'filter_by_subcategory_with_multi_id',
                        'array_key': 'us_name'
                    }
                ];
            },

            // Apply all filters to products data
            apply_filters(products) {
                let filtered = [...products];

                // Filter by brand
                if (this.filters.brand_id > 0) {
                    filtered = filtered.filter(item =>
                        item.product && item.product.brand_id === this.filters.brand_id
                    );
                }

                // Filter by sale type (sale_type is a string column: online_order/produced_by_order/custom_production/donation/outlet)
                if (this.filters.sale_type_id !== 0) {
                    filtered = filtered.filter(item =>
                        item.product && item.product.sale_type === this.filters.sale_type_id
                    );
                }

                // Filter by subcategory
                if (this.filters.subcategory_id > 0) {
                    filtered = filtered.filter(item =>
                        item.product && item.product.subcategory_id === this.filters.subcategory_id
                    );
                }

                return filtered;
            },
            
            // Update products table with current filters
            update_products_table() {
                const productsTab = this.data_for_tab.find(tab => tab.id === 1);
                if (productsTab) {
                    productsTab.tab_data.data = this.apply_filters(this.original_products_data)
                        .map(item => ({ ...item, id: item.product?.id }));
                }
            },
            
            // Filter by brand
            filter_by_brand_with_multi_id(brand_id, filter_index) {
                this.filters.brand_id = brand_id;
                this.update_products_table();
            },
            
            // Filter by sale type
            filter_by_sale_type_with_multi_id(sale_type_id, filter_index) {
                this.filters.sale_type_id = sale_type_id;
                this.update_products_table();
            },
            
            // Filter by subcategory
            filter_by_subcategory_with_multi_id(subcategory_id, filter_index) {
                this.filters.subcategory_id = subcategory_id;
                this.update_products_table();
            },

            get_sale_codes_data: function(){
                axios
                .get("/get_sale_code/get_all_sale_code")
                .then(response => {
                    this.data_for_tab.push({
                                            'id': 4,
                                            'table_name': this.$t('admin.shop.sale_codes_table'),
                                            'add_action': {
                                                'action': 'fun',
                                                'link': 'show_sale_code_add_modal',
                                                'class': 'btn btn-primary'
                                            },
                                            'tab_data': {
                                                'data': response.data,
                                                'tab': {
                                                    'head': [
                                                        this.$t('common.id'),
                                                        this.$t('admin.shop.code_label'),
                                                        this.$t('admin.shop.discount_label'),
                                                        this.$t('admin.shop.one_time_code'),
                                                        this.$t('admin.shop.action_data'),
                                                        this.$t('common.edit'),
                                                        this.$t('common.delete'),
                                                    ],
                                                    'body': [
                                                        ['data', ['id']],
                                                        ['data', ['code']],
                                                        ['data', ['discount']],
                                                        ['data', ['one_time_code'], 'bool'],
                                                        ['data', ['action_data']],
                                                        ['action_fun_id', 'show_sale_code_edit_modal', 'btn btn-primary', '<i aria-hidden="true" class="fa fa-pencil"></i>'],
                                                        ['action_fun_id', 'del_sale_code', 'btn btn-danger', '<i aria-hidden="true" class="fa fa-trash"></i>'],
                                                    ],
                                                    'perm': [
                                                        ['no'],
                                                        ['no'],
                                                        ['no'],
                                                        ['no'],
                                                        ['no'],
                                                        ['sale_code', 'edit'],
                                                        ['sale_code', 'del'],
                                                    ]
                                                }
                                            },
                                        });
                })
                .catch(
                    error => console.log(error)
                );

            },

            del_sale_code(id){
                if(confirm(this.$t('admin.common.confirm_delete'))){
                    axios
                    .post('/set_product/set_sale_code/del_sale_code/'+id, {
                        _method: 'DELETE'
                    })
                    .then(Response => {
                        this.get_products_data()
                    })
                    .catch(error => console.log(error))
                }
            },
            show_sale_code_add_modal(){
                this.$refs.saleCodeAddModal.show_modal()
            },
            show_sale_code_edit_modal(id){
                this.$refs.saleCodeEditModal.show_modal(id)
            },

            del_product_brand(id){
                if(confirm(this.$t('admin.common.confirm_delete'))){
                    axios
                    .post('/set_product/set_brand/del_brand/'+id, {
                        _method: 'DELETE'
                    })
                    .then(Response => {
                        this.get_products_data()
                    })
                    .catch(error => console.log(error))
                }
            },
            show_product_brand_edit_modal(id){
                this.$refs.editProductBrandModal.show_modal(id)
            },
            show_product_brand_add_modal(){
                this.$refs.addProductBrandModal.show_modal()
            },


            del_product_category(id){
                if(confirm(this.$t('admin.common.confirm_delete'))){
                    axios
                    .post('/set_product/set_product_category/del_product_category/' + id, {
                        _method: 'DELETE'
                    })
                    .then(Response => {
                        this.get_products_data()
                    })
                    .catch(error => console.log(error))
                }
            },

            del_product(id){
                if(confirm(this.$t('admin.common.confirm_delete'))){
                    axios
                    .post('/set_product/del_product/'+id, {
                        _method: 'DELETE'
                    })
                    .then(Response => {
                        this.get_products_data()
                    })
                    .catch(error => console.log(error))
                }
            },
            show_product_quick_view(id) {
                this.$refs.productAdminQuickViewModal.show_modal(id)
            },
            show_user_change_modal(id){
                this.$refs.userRelationModal.show_modal(id)
            },
            
            get_products_data() {
                this.data_for_tab = [];
                Promise.all([
                    this.loadProducts(),
                    this.loadCategories(),
                    this.loadSubcategories(),
                    this.loadBrands()
                ]).then(() => {
                    this.buildProductsTab();
                    this.buildCategoriesTab();
                    this.buildBrandsTab();
                    this.get_sale_codes_data();
                }).catch(error => {
                    console.log('Error reloading data:', error);
                });
            },
        }
    }
</script>
