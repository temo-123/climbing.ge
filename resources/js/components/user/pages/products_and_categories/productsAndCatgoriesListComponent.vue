<template>
<div class="row">
        <div class="col-sm-3">
            <left-menu />
        </div>
        <div class="col-sm-9">
            <div class="col-sm-12">
                <tabsComponent 
                    :table_data="this.data_for_tab"
                    @update-data="callback"
                />
            </div>
        </div>
    </div>
</template>

<script>
    import tabsComponent  from '../../items/data_tabs/DataTab/TabsComponent'
    export default {
        components: {
            tabsComponent ,
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
                axios
                .get("../api/products/")
                .then(response => {
                    this.data_for_tab.push({'id': 1,
                                            'data': response.data, 
                                            'table_name': 'Products', 
                                            // 'table_category': this.$route.params.article_category, 
                                            'table_del_url': 'del_url', 
                                            'table_edit_url': 'edit_url'
                                        });
                    this.get_categories_data()
                })
                .catch(
                    error => console.log(error)
                );

            },
            get_categories_data: function(){
                axios
                .get("../api/categories/")
                .then(response => {
                    this.data_for_tab.push({'id': 2,
                                            'data': response.data, 
                                            'table_name': 'Categories', 
                                            // 'table_category': this.$route.params.article_category, 
                                            'table_del_url': 'del_url', 
                                            'table_edit_url': 'edit_url'
                                        });
                })
                .catch(
                    error => console.log(error)
                );

            },

            product_del(itemId) {
                axios
                .post('../api/products/del/' + itemId, {
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