<template>
    <div class="col-sm-12">
        <tabsComponent 
            :table_data="this.data_for_tab"
        />
    </div>
</template>

<script>
    import tabsComponent  from '../items/data_tabs/DataTab/TabsComponent'
    export default {
        components: {
            tabsComponent ,
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
                axios
                .get("../api/users/")
                .then(response => {
                    this.data_for_tab.push({'id': 1,
                                            'data': response.data, 
                                            'table_name': 'Ysers', 
                                            'table_category': this.$route.params.article_category, 
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