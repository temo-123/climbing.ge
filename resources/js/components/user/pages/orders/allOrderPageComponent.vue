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
                            @update-data="get_orders"
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
            breadcrumb,
            tabsComponent ,
            // leftMenu,
        },
        data(){
            return {
                data_for_tab: [],
            }
        },
        mounted() {
            this.get_orders()
        },
        watch: {
            '$route' (to, from) {
                this.data_for_tab = [],
                this.get_orders()
                window.scrollTo(0,0)
            }
        },
        methods: {
            get_orders(){
                this.data_for_tab = []
                axios
                .get("/order/")
                .then(response => {
                    this.data_for_tab.push({'id': 1,
                                            'data': response.data, 
                                            'table_name': 'Orders', 
                                            'table_category': '', 
                                            'table_edit': '',
                                            'table_del': ''
                                        });
                    this.get_shipd_regions()
                })
                .catch(
                    error => console.log(error)
                );
            },
            get_shipd_regions(){
                axios
                .get("/shiped_region/get_all_shiped_regions/")
                .then(response => {
                    this.data_for_tab.push({'id': 2,
                                            'data': response.data, 
                                            'table_name': 'Shiped regions', 
                                            'table_category': '', 
                                            'table_edit': '',
                                            'table_del': ''
                                        });
                })
                .catch(
                    error => console.log(error)
                );
            },
        }
    }
</script>