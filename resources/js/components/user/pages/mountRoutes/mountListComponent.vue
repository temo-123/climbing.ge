<template>
    <div class="row">
        <div class="col-sm-3">
            <left-menu />
        </div>
        <div class="col-sm-9">
            <div class="row">
                <div class="col-md-12">
                    <breadcrumb />
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12">
                    <tabsComponent 
                        :table_data="this.data_for_tab"
                        @filtr="filtr"
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
            // this.get_mounts()
        },
        watch: {
            '$route' (to, from) {
                this.data_for_tab = [],
                this.get_articles()
                // this.get_mounts()
                window.scrollTo(0,0)
            }
        },
        methods: {
            get_filtred_articles(id){
                axios
                .get("../api/mount_route/get_filtred_mount_route_for_admin/" + id)
                .then(response => {
                    // this.mount_routes = response.data

                    this.data_for_tab = []
                    this.data_for_tab.push({'id': 1,
                                            'data': response.data, 
                                            'table_name': 'Mountaineering routes', 
                                            'table_category': 'Mountaineering routes', 
                                            'table_add_url': 'add_url', 
                                            'table_edit_url': 'edit_url',
                                            'table_del_url': 'del_url', 
                                        });

                    this.get_mounts()
                })
                .catch(
                    error => console.log(error)
                )
                .finally(() => this.article_loading = false);
            },

            get_unfilted_articles(){
                axios
                .get('../api/articles/mount_route/'+localStorage.getItem('lang'))
                .then(response => {
                    // this.mount_routes = response.data
                    // this.filter_mount_routes()

                    this.data_for_tab = []
                    this.data_for_tab.push({'id': 1,
                                            'data': response.data, 
                                            'table_name': 'Mountaineering routes', 
                                            'table_category': 'Mountaineering routes', 
                                            'table_add_url': 'add_url', 
                                            'table_edit_url': 'edit_url',
                                            'table_del_url': 'del_url', 
                                        });

                    this.get_mounts()
                })
                .catch(error =>{
                })
            },

            get_articles(filtr_id = 'all'){
                if (filtr_id === 'all' || filtr_id === 'All') {
                    this.get_unfilted_articles()
                }
                else{
                    this.get_filtred_articles(filtr_id) 
                }
            },

            filtr(event){
                this.get_articles(event)
            },


            // get_articles(){
            //     axios
            //     .post("../api/article/", {
            //         category: 'mount_route',
            //     })
            //     .then(response => {
            //         this.data_for_tab.push({'id': 1,
            //                                 'data': response.data, 
            //                                 'table_name': 'Mountaineering routes', 
            //                                 'table_category': 'Mountaineering routes', 
            //                                 'table_add_url': 'add_url', 
            //                                 'table_edit_url': 'edit_url',
            //                                 'table_del_url': 'del_url', 
            //                             });
            //     })
            //     .catch(
            //         error => console.log(error)
            //     );
            // },

            get_mounts(){
                axios
                .get("../api/mount/")
                .then(response => {
                    this.data_for_tab.push({'id': 2,
                                            'data': response.data, 
                                            'table_name': 'Mounts', 
                                            'table_category': 'Mounts', 
                                            'table_add_url': 'add_url', 
                                            'table_edit_url': 'edit_url',
                                            'table_del_url': 'del_url', 
                                        });
                })
                .catch(
                    error => console.log(error)
                );
            },

            // table_1_del(itemId) {
            //     axios
            //     .post(this.table_1_del_url + itemId, {
            //         id: itemId,
            //     })
            //     .then(Response => {
            //         if (this.table_1_name == 'Sector') {
            //             this.get_sectors_data();
            //         }
            //         if (this.table_1_name == 'Products') {
            //             this.get_product_data();
            //         }
            //         if (this.table_1_name == 'Mount routes') {
            //             this.get_mount_route_data();
            //         }

            //         this.get_data_in_table_1()
            //     })
            //     .catch(error => console.log(error))
            // },

            // show_parmission_edit_madel(user_id){
            //     this.roles_modal=true;
            //     this.user_id_for_rditing_parmission = user_id
            // },
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
            // get_user_role: function(user_id){
            //     axios
            //     .get('users/get_role/', {

            //     })
            //     .then(Response => {
            //         console.log(Response.data);
            //         this.user_roles = Response.data
            //     })
            //     .catch(error => {
            //         // this.user_role = "error"
            //     })
            // },
        }
    }
</script>

<style>

</style>