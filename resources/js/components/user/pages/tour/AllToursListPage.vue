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
                        @update-data="get_all_tours_data"
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
            this.get_all_tours_data();
        },
         
        methods: {
            get_all_tours_data: function(){
                this.data_for_tab = []
                axios
                .get("/tour/get_all_tours/")
                .then(response => {
                    this.data_for_tab.push({'id': 1,
                                            'data': response.data, 
                                            'table_name': 'Tours', 
                                            'table_add_url': 'tourAdd', 
                                        });
                this.get_all_tours_categories()
                })
                .catch(
                    error => console.log(error)
                );
            },

            get_all_tours_categories: function(){
                axios
                .get("/tour/category/get_all_categories/")
                .then(response => {
                    this.data_for_tab.push({'id': 2,
                                            'data': response.data, 
                                            'table_name': 'Tours categories', 
                                            'table_add_url': 'tourCategoryAdd', 
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