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
    import tabsComponent  from '../items/data_table/TabsComponent.vue'

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
                .get("../api/users/")
                .then(response => {
                    this.data_for_tab.push({'id': 1,
                                            'data': response.data, 
                                            'table_name': 'Users',
                                            'table_add_url': 'del_url', 
                                            'table_del_url': 'del_url', 
                                            'table_edit_url': 'edit_url'
                                        });
                    
                    this.get_roles()
                })
                .catch(
                    error => console.log(error)
                );
            },
            get_roles(){
                axios
                .get("../api/role/")
                .then(response => {
                    this.data_for_tab.push({'id': 3,
                                            'data': response.data, 
                                            'table_name': 'Roles', 
                                            'table_category': '', 
                                            'table_del_url': 'del_url', 
                                            'table_edit_url': 'edit_url'
                                        });
                    
                    this.get_parmisions()
                })
                .catch(
                    error => console.log(error)
                );
            },
            get_parmisions(){
                axios
                .get("../api/parmisions_list/")
                .then(response => {
                    this.data_for_tab.push({'id': 2,
                                            'data': response.data, 
                                            'table_name': 'Parmissions',
                                        });
                })
                .catch(
                    error => console.log(error)
                );
            },
        }
    }
</script>

<style>

</style>