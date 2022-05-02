<template>
    <div class="row">
        <div class="col-sm-3">
            <left-menu />
        </div>
        <div class="col-sm-9">
            <div class="col-sm-12">
                <tabsComponent 
                    :table_data="this.data_for_tab"
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
            this.get_services_data();
        },
         
        methods: {
            get_services_data: function(){
                axios
                .get("../api/services/")
                .then(response => {
                    this.data_for_tab.push({'id': 1,
                                            'data': response.data, 
                                            'table_name': 'services', 
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

            product_del(itemId) {
                axios
                .post('../api/services/del/' + itemId, {
                    id: itemId,
                })
                .then(Response => {
                    console.log(response)
                    this.get_data_in_table_1()
                })
                .catch(error => console.log(error))
            },
        }
    }
</script>