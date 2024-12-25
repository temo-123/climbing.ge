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
                        @update-data="get_products_data"
                    />
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import tabsComponent  from '../../items/data_table/TabsComponent.vue'
    import breadcrumb from '../../items/BreadcrumbComponent.vue'
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
                .get("/product/get_user_products")
                // .get("/products/en/")
                .then(response => {
                    this.data_for_tab.push({'id': 1,
                                            'data': response.data, 
                                            'table_name': 'My Products', 
                                            'table_add_url': 'productAdd', 
                                            'table_edit_url': 'productEdit',
                                            // 'table_del_url': 'del_url', 
                                        });
                    // this.get_categories_data()
                    // this.get_sale_codes_data()
                })
                .catch(
                    error => console.log(error)
                );

            },
            // get_categories_data: function(){
            //     axios
            //     .get("/product_category")
            //     .then(response => {
            //         this.data_for_tab.push({'id': 2,
            //                                 'data': response.data, 
            //                                 'table_name': 'Categories', 
            //                                 'table_add_url': 'productCategoryAdd', 
            //                                 'table_edit_url': 'productCategoryEdit',
            //                                 'table_del_url': 'del_url', 
            //                             });
            //     })
            //     .catch(
            //         error => console.log(error)
            //     );

            // },
            // get_sale_codes_data: function(){
            //     axios
            //     .get("/sale_code")
            //     .then(response => {
            //         this.data_for_tab.push({'id': 3,
            //                                 'data': response.data, 
            //                                 'table_name': 'Sale codes', 
            //                                 // 'table_add_url': 'saleCodeAdd', 
            //                                 // 'table_edit_url': 'saleCodeEdit',
            //                                 // 'table_del_url': 'del_url', 
            //                             });
            //     })
            //     .catch(
            //         error => console.log(error)
            //     );

            // },

            // product_del(itemId) {
            //     axios
            //     .post('/products/del/' + itemId, {
            //         id: itemId,
            //     })
            //     .then(Response => {
            //         console.log(response)
            //         this.get_data_in_table_1()
            //     })
            //     .catch(error => console.log(error))
            // },

            callback(id){
                if(id == 1){
                    this.get_articles()
                }
            }
        }
    }
</script>