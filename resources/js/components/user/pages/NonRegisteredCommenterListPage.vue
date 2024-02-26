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
                        @update-data="get_users"
                    />
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import tabsComponent  from '../items/data_tabs/DataTab/TabsComponent'

    import breadcrumb from '../items/BreadcrumbComponent.vue'
    export default {
        components: {
            tabsComponent ,
            breadcrumb,
        },
        data(){
            return {
                data_for_tab: [],
            }
        },
        mounted() {
            this.get_users()
        },
        watch: {
            '$route' (to, from) {
                this.data_for_tab = [],
                this.get_users()
                window.scrollTo(0,0)
            }
        },
        methods: {
            get_users(){
                this.data_for_tab = [],

                axios
                .get("non_registered_commenter/get_non_registered_commenter/")
                .then(response => {
                    this.data_for_tab.push({"id": 1,
                                            "data": response.data, 
                                            "table_name": "Non Registered Commenter List",
                                            "table_add_url": "del_url", 
                                            "table_del_url": "del_url", 
                                            "table_edit_url": "edit_url"
                                        });
                })
                .catch(
                    error => console.log(error)
                );
            },
        }
    }
</script>