<template>
    <div class="row">
        <div class="col-sm-3">
            <left-menu />
        </div>
        <div class="col-sm-9">
            <div class="col-sm-12">
                <tabsComponent 
                    :table_data="this.data_for_tab"
                    @update-data="update"
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
        // props: [
        //     'status',
        // ],
        data(){
            return {
                data_for_tab: [],
            }
        },
        mounted() {
            this.get_articles()
        },
        watch: {
            '$route' (to, from) {
                this.data_for_tab = [],
                this.get_articles()
                window.scrollTo(0,0)
            }
        },
        methods: {
            get_articles(){
                axios
                .post("../api/article/", {
                    category: this.$route.params.article_category,
                })
                .then(response => {
                    this.data_for_tab = []
                    this.data_for_tab.push({'id': 1,
                                            'data': response.data, 
                                            'table_name': this.$route.params.article_category, 
                                            'table_category': this.$route.params.article_category, 
                                            'table_add_url': 'articleAdd', 
                                            'table_edit_url': 'edit_url',
                                            'table_del_url': 'del_url', 
                                        });
                    this.get_regions(this.$route.params.article_category)
                })
                .catch(
                    error => console.log(error)
                );
            },

            get_regions(category){
                if(category == 'outdoor'){
                    axios
                    .get("../api/region/")
                    .then(response => {
                        this.data_for_tab.push({'id': 2,
                                                'data': response.data, 
                                                'table_name': "Regions",
                                                'table_add_url': 'add_region', 
                                                'table_edit_url': 'edit_region',
                                                'table_del_url': 'del_region', 
                                            });
                    })
                    .catch(
                        error => console.log(error)
                    );
                }
            },

            table_1_del(itemId) {
                axios
                .post(this.table_1_del_url + itemId, {
                    id: itemId,
                })
                .then(Response => {
                    if (this.table_1_name == 'Sector') {
                        this.get_sectors_data();
                    }
                    if (this.table_1_name == 'Products') {
                        this.get_product_data();
                    }
                    if (this.table_1_name == 'Mount routes') {
                        this.get_mount_route_data();
                    }

                    this.get_data_in_table_1()
                })
                .catch(error => console.log(error))
            },

            show_parmission_edit_madel(user_id){
                this.roles_modal=true;
                this.user_id_for_rditing_parmission = user_id
            },
            // edit_permission(id) {
            //     axios
            //     .post('users/edit_user_permission/' + id, {
            //         parmission: this.user_new_parmission,
            //     })
            //     .then((response)=> { 
            //         this.roles_modal = false
            //     })
            //     .catch(error =>{
            //         if (error.response.status == 422) {
            //             this.parmision_error = error.response.data.errors
            //         }
            //         this.is_parmision_error = true
            //     })
            // },
            get_user_role: function(user_id){
                axios
                .get('users/get_role/', {

                })
                .then(Response => {
                    console.log(Response.data);
                    this.user_roles = Response.data
                })
                .catch(error => {
                    // this.user_role = "error"
                })
            },

            update(id){
                if(id == 1){
                    this.get_articles()
                }
            }
        }
    }
</script>

<style>

</style>