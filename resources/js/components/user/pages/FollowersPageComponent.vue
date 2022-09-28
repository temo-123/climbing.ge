<template>
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
</template>

<script>
    import tabsComponent  from '../items/data_tabs/DataTab/TabsComponent'
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
                this.get_folowing()
                this.get_folowers()
        },
        watch: {
            '$route' (to, from) {
                this.data_for_tab = [],
                this.get_folowing()
                this.get_folowers()
                window.scrollTo(0,0)
            }
        },
        methods: {
            get_folowing(){
                axios
                .get("../api/following_users_list/")
                .then(response => {
                    this.data_for_tab.push({'id': 1,
                                            'data': response.data, 
                                            'table_name': 'Folowing users', 
                                            'table_category': '', 
                                            'table_del_url': 'del_url', 
                                            'table_edit_url': 'edit_url'
                                        });
                })
                .catch(
                    error => console.log(error)
                );
            },
            get_folowers(){
                axios
                .get("../api/followers_list/")
                .then(response => {
                    this.data_for_tab.push({'id': 2,
                                            'data': response.data, 
                                            'table_name': 'Folowers', 
                                            'table_category': '', 
                                            'table_del_url': 'del_url', 
                                            'table_edit_url': 'edit_url'
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