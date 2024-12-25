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
                        @update-data="get_local_bisnes_data"
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
            this.get_local_bisnes_data();
        },
         
        methods: {
            get_local_bisnes_data: function(){
                this.data_for_tab = []
                axios
                .get("../api/bisnes/get_local_bisneses")
                .then(response => {
                    this.data_for_tab.push({'id': 1,
                                            'data': response.data, 
                                            'table_name': 'Local bisnes', 
                                            'table_add_url': 'localBisnesAdd', 
                                        });
                })
                .catch(
                    error => console.log(error)
                );

            },
        }
    }
</script>