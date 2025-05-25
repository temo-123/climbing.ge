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
                    />
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import tabsComponent  from '../..//items/data_table/TabsComponent.vue'
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
            this.get_films_data();
        },
         
        methods: {
            get_films_data: function(){
                axios
                .get("../api/films/")
                .then(response => {
                    this.data_for_tab.push({'id': 1,
                                            'data': response.data, 
                                            'table_name': 'Films', 
                                            'table_category': this.$route.params.article_category, 
                                            'table_del_url': 'del_url', 
                                            'table_edit_url': 'edit_url'
                                        });
                    this.get_films_tegs_data()
                    this.get_categories_data()
                })
                .catch(
                    error => console.log(error)
                );

            },

            get_categories_data: function(){
                axios
                .get("../api/film/get_films_categories/us/")
                .then(response => {
                    this.data_for_tab.push({'id': 3,
                                            'data': response.data, 
                                            'table_name': 'Film categories', 
                                            // 'table_category': this.$route.params.article_category, 
                                            // 'table_del_url': 'del_url', 
                                            // 'table_edit_url': 'edit_url'
                                        });
                })
                .catch(
                    error => console.log(error)
                );

            },

            get_films_tegs_data: function(){
                axios
                .get("../api/film_tags/")
                .then(response => {
                    this.data_for_tab.push({'id': 2,
                                            'data': response.data, 
                                            'table_name': 'Films tags', 
                                            // 'table_category': this.$route.params.article_category, 
                                            // 'table_del_url': 'del_url', 
                                            // 'table_edit_url': 'edit_url'
                                        });
                })
                .catch(
                    error => console.log(error)
                );

            },

            // product_del(itemId) {
            //     axios
            //     .post('../api/films/del/' + itemId, {
            //         id: itemId,
            //     })
            //     .then(Response => {
            //         console.log(response)
            //         this.get_data_in_table_1()
            //     })
            //     .catch(error => console.log(error))
            // },
        }
    }
</script>