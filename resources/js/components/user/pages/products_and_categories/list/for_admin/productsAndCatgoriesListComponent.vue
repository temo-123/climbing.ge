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
                    />
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import tabsComponent  from '../../../../items/data_table/TabsComponent.vue'
    import breadcrumb from '../../../../items/BreadcrumbComponent.vue'
    export default {
        components: {
            tabsComponent ,
            breadcrumb
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
                .get("/product/get_all_products")
                // .get("/products/en/")
                .then(response => {
                    this.data_for_tab.push({
                                            'id': 1,
                                            'table_name': 'Products', 
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
                                                        'Sale type',
                                                        'User',
                                                        'Options',
                                                        'Edit',
                                                        'Delite',
                                                    ],
                                                    'body': [
                                                        ['data', ['id']],
                                                        ['data', ['url_title']],
                                                        ['data', ['Published']],
                                                        ['data', ['sale_type']],
                                                        ['data', ['sale_type']],
                                                        ['data_action_id', ['title'], 'show_local_image_modal'],
                                                        ['action_router', 'productEdit', 'btn btn-primary', '<i aria-hidden="true" class="fa fa-pencil"></i>'],
                                                        ['action_fun_id', 'del_sector_local_images', 'btn btn-danger', '<i aria-hidden="true" class="fa fa-trash"></i>'],
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
                    this.get_categories_data()
                })
                .catch(
                    error => console.log(error)
                );

            },
            get_categories_data: function(){
                axios
                .get("/product_category")
                .then(response => {
                    this.data_for_tab.push({
                                            'id': 2,
                                            'table_name': 'Categories', 
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
                                                        ['data_action_id', ['title'], 'show_local_image_modal'],
                                                        ['action_router', 'productCategoryEdit', 'btn btn-primary', '<i aria-hidden="true" class="fa fa-pencil"></i>'],
                                                        ['action_fun_id', 'del_sector_local_images', 'btn btn-danger', '<i aria-hidden="true" class="fa fa-trash"></i>'],
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
                .get("/brand/get_all_brands")
                .then(response => {
                    this.data_for_tab.push({
                                            'id': 3,
                                            'table_name': 'Brands', 
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
                                                        'Name',
                                                        'Discout',
                                                        'Edit',
                                                        'Delite',
                                                    ],
                                                    'body': [
                                                        ['data', ['id']],
                                                        ['data_action_id', ['title'], 'show_local_image_modal'],
                                                        ['action_router', 'sectorLocalImagesListEdit', 'btn btn-primary', '<i aria-hidden="true" class="fa fa-pencil"></i>'],
                                                        ['action_fun_id', 'del_sector_local_images', 'btn btn-danger', '<i aria-hidden="true" class="fa fa-trash"></i>'],
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
                    this.get_sale_codes_data()
                })
                .catch(
                    error => console.log(error)
                );

            },
            get_sale_codes_data: function(){
                axios
                .get("/sale_code")
                .then(response => {
                    this.data_for_tab.push({
                                            'id': 4,
                                            'table_name': 'Sale codes', 
                                            'add_action': {
                                                'action': 'fun',
                                                'link': 'open_sale_code_add_modal', 
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
                                                        ['data_action_id', ['title'], 'show_local_image_modal'],
                                                        ['action_router', 'sectorLocalImagesListEdit', 'btn btn-primary', '<i aria-hidden="true" class="fa fa-pencil"></i>'],
                                                        ['action_fun_id', 'del_sector_local_images', 'btn btn-danger', '<i aria-hidden="true" class="fa fa-trash"></i>'],
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
                })
                .catch(
                    error => console.log(error)
                );

            },

            product_del(itemId) {
                axios
                .post('/products/del/' + itemId, {
                    id: itemId,
                })
                .then(Response => {
                    console.log(response)
                    this.get_data_in_table_1()
                })
                .catch(error => console.log(error))
            },

            callback(id){
                if(id == 1){
                    this.get_articles()
                }
            }
        }
    }
</script>