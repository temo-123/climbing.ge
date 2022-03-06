<template>
    <div class="col-sm-12">
        <tabsComponent 
            :table_data="this.data_for_tab"
        />
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
            this.get_films_data();
        },
         
        methods: {
            get_films_data: function(){
                axios
                .get("../api/films/")
                .then(response => {
                    this.data_for_tab.push({'id': 1,
                                            'data': response.data, 
                                            'table_name': 'films', 
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
                .post('../api/films/del/' + itemId, {
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