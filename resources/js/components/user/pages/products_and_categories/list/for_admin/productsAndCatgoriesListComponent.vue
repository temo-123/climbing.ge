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
                        :table_data="this.data_for_tab"
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
                    />
                </div>
            </div>
        </div>
        <saleCodeAddModal ref="saleCodeAddModal" @update="get_products_data"/>
        <saleCodeEditModal ref="saleCodeEditModal" @update="get_products_data"/>

        <editProductBrandModal ref="editProductBrandModal" @update="get_products_data"/>
        <addProductBrandModal ref="addProductBrandModal" @update="get_products_data"/>

        <change_user_modal ref="userRelationModal" @update="get_products_data" />
    </div>
</template>

<script>
    import tabsComponent  from '../../../../items/data_table/TabsComponent.vue'
    import breadcrumb from '../../../../items/BreadcrumbComponent.vue'

    import saleCodeEditModal from "../../../../items/modal/tab_modals/edit/EditSaleCodeModalComponen.vue";
    import saleCodeAddModal from "../../../../items/modal/tab_modals/add/AddSaleCodeModalComponen.vue";
    
    import editProductBrandModal from "../../../../items/modal/tab_modals/edit/EditProductBrandModal.vue";
    import addProductBrandModal from "../../../../items/modal/tab_modals/add/AddProductBrandModal.vue";

    import change_user_modal from '../../../../items/modal/tab_modals/ChangeUserModalComponent.vue'
    export default {
        components: {
            tabsComponent ,
            breadcrumb,

            saleCodeEditModal,
            saleCodeAddModal,

            editProductBrandModal,
            addProductBrandModal,

            change_user_modal,
        },
        
        data() {
            return {
                data_for_tab:[],
            }
        },

        mounted() {
            this.get_products_data();
        },
         
        methods: {
            get_products_data: function(){
                this.data_for_tab = []
                axios
                .get("/get_product/get_all_products")
                // .get("/products/en/")
                .then(response => {
                    this.data_for_tab.push({
                                            'id': 1,
                                            'table_name': 'Products', 
                                            'list_page': process.env.MIX_APP_SSH + process.env.MIX_SHOP_URL + '/products',
                                            'add_action': {
                                                'action': 'route',
                                                'link': 'productAdd', 
                                                'class': 'btn btn-primary'
                                            },
                                            'tab_data': {
                                                'data': response.data, 
                                                'tab': {
                                                    'head': [
                                                        'ID',
                                                        'Title',
                                                        'Public',
                                                        'Georgia',
                                                        'Donation',
                                                        'Options',
                                                        'User',
                                                        'Edit user',
                                                        'Edit Options',
                                                        'Edit',
                                                        'Delite',
                                                    ],
                                                    'body': [
                                                        ['data', ['product', 'id']],
                                                        ['data', ['product', 'url_title']],
                                                        ['data', ['product', 'published'], 'bool'],
                                                        ['data', ['product', 'made_in_georgia'], 'bool'],
                                                        ['data', ['product', 'is_donation_product'], 'bool'],
                                                        ['data', ['options']],
                                                        ['data', [['user', 'name'], ['user', 'surname']]],
                                                        ['action_fun_id', 'show_user_change_modal', 'btn btn-secondary', '<i class="fa fa-user-plus" aria-hidden="true"></i>', [['user', 'id'], ['product', 'id']]],
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
                                                        ['product', 'edit'],
                                                        ['no'],
                                                        ['product', 'edit'],
                                                        ['product', 'del'],
                                                    ]
                                                }
                                            },
                                        });
                    this.get_categories_data()
                })
                .catch(
                    error => console.log(error)
                );

            },
            get_categories_data: function(){
                axios
                .get("/get_product/get_product_category/get_all_product_category/")
                .then(response => {
                    this.data_for_tab.push({
                                            'id': 2,
                                            'table_name': 'Product Categories', 
                                            'add_action': {
                                                'action': 'route',
                                                'link': 'productCategoryAdd', 
                                                'class': 'btn btn-primary'
                                            },
                                            'tab_data': {
                                                'data': response.data, 
                                                'tab': {
                                                    'head': [
                                                        'ID',
                                                        'Name',
                                                        'Edit',
                                                        'Delite',
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
                                                        ['sector_local_images', 'edit'],
                                                        ['sector_local_images', 'del'],
                                                    ]
                                                }
                                            },
                                        });
                    this.get_all_brands_data()
                })
                .catch(
                    error => console.log(error)
                );

            },
            get_all_brands_data: function(){
                axios
                .get("/get_product/get_brand/get_all_brands")
                .then(response => {
                    this.data_for_tab.push({
                                            'id': 3,
                                            'table_name': 'Brands', 
                                            'add_action': {
                                                'action': 'fun',
                                                'link': 'show_product_brand_add_modal', 
                                                'class': 'btn btn-primary'
                                            },
                                            'tab_data': {
                                                'data': response.data, 
                                                'tab': {
                                                    'head': [
                                                        'ID',
                                                        'Name',
                                                        'Edit',
                                                        'Delite',
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
                    this.get_sale_codes_data()
                })
                .catch(
                    error => console.log(error)
                );

            },
            get_sale_codes_data: function(){
                axios
                .get("/get_sale_code/get_all_sale_code")
                .then(response => {
                    this.data_for_tab.push({
                                            'id': 4,
                                            'table_name': 'Sale codes', 
                                            'add_action': {
                                                'action': 'fun',
                                                'link': 'show_sale_code_add_modal', 
                                                'class': 'btn btn-primary'
                                            },
                                            'tab_data': {
                                                'data': response.data, 
                                                'tab': {
                                                    'head': [
                                                        'ID',
                                                        'Code',
                                                        'Discount',
                                                        'one_time_code',
                                                        'action_data',
                                                        'Edit',
                                                        'Delite',
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
                if(confirm('Are you sure, you want delite it?')){
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
                if(confirm('Are you sure, you want delite it?')){
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
                if(confirm('Are you sure, you want delite it?')){
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
                if(confirm('Are you sure, you want delite it?')){
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
            show_user_change_modal(event){
                console.log("🚀 ~ show_user_change_modal ~ event:", event)
                this.$refs.userRelationModal.show_modal(event)
            },
        }
    }
</script>