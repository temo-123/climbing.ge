<template>
    <div class="row">
        <div class="col-sm-3">
            <left-menu />
        </div>
        <div class="col-sm-9">
            <div class="col-sm-12">
                <tabsComponent 
                    :table_data="this.data_for_tab"
                    @update-data="get_services_data"
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
                this.data_for_tab = []
                axios
                .get("../api/service/")
                .then(response => {
                    this.data_for_tab.push({'id': 1,
                                            'data': response.data, 
                                            'table_name': 'services', 
                                            'table_add_url': 'serviceAdd', 
                                        });
                })
                .catch(
                    error => console.log(error)
                );
            },
        }
    }
</script>