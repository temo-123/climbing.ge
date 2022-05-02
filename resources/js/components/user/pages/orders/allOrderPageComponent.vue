<template>
    <div class="container">
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
    </div>
</template>

<script>
    import tabsComponent  from '../../items/data_tabs/DataTab/TabsComponent'
    export default {
        components: {
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
                axios
                .get("../api/order/")
                .then(response => {
                    this.data_for_tab.push({'id': 1,
                                            'data': response.data, 
                                            'table_name': 'Orders', 
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