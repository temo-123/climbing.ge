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
                        @update-data="get_all_toures_data"
                    />
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import tabsComponent  from '../../items/data_tabs/DataTab/TabsComponent'
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
            this.get_all_toures_data();
        },
         
        methods: {
            get_all_toures_data: function(){
                this.data_for_tab = []
                axios
                .get("/toure/get_all_tours/")
                .then(response => {
                    this.data_for_tab.push({'id': 1,
                                            'data': response.data, 
                                            'table_name': 'Tours', 
                                            'table_add_url': 'toureAdd', 
                                        });
                this.get_all_toures_categories()
                })
                .catch(
                    error => console.log(error)
                );
            },

            get_all_toures_categories: function(){
                axios
                .get("/toure/category/get_all_categories/")
                .then(response => {
                    this.data_for_tab.push({'id': 2,
                                            'data': response.data, 
                                            'table_name': 'Tours categories', 
                                            'table_add_url': 'toureCategoryAdd', 
                                            'tab_values': [
                                                'name',
                                                'published',
                                                'edit',
                                                'del'
                                            ]
                                        });
                })
                .catch(
                    error => console.log(error)
                );
            },
        }
    }
</script>