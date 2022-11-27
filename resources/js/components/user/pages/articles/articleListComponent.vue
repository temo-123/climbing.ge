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
                <div class="col-md-12" v-if="article_loading">
                    <content-loader
                        viewBox="0 0 500 150"
                        primaryColor="#f3f3f3"
                        secondaryColor="#7427bb75"
                    >
                        <rect x="0" y="0" rx="2" ry="2" width="100%" height="25" />

                        <rect x="0" y="45" rx="3" ry="3" width="100%" height="10" />
                        <rect x="0" y="60" rx="3" ry="3" width="100%" height="10" />
                        <rect x="0" y="75" rx="3" ry="3" width="100%" height="10" />
                        <rect x="0" y="90" rx="3" ry="3" width="100%" height="10" />
                        <rect x="0" y="105" rx="3" ry="3" width="100%" height="10" />
                    </content-loader>
                </div>
                <div class="col-sm-12" v-else>
                    <tabsComponent 
                        :table_data="this.data_for_tab"
                        @update-data="get_articles"
                        @filtr="filtr"
                    />
                </div>
            </div>
         </div>
    </div>
</template>

<script>
    import tabsComponent  from '../../items/data_tabs/DataTab/TabsComponent'
    import { ContentLoader } from 'vue-content-loader'
    import breadcrumb from '../../items/BreadcrumbComponent.vue'
    export default {
        components: {
            breadcrumb,
            tabsComponent,
            ContentLoader
        },
        // props: [
        //     'status',
        // ],
        data(){
            return {
                data_for_tab: [],
                articles: [],
                article_loading: true,
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
            get_articles(filt_id = 'all'){
                if (filt_id === 'all' || filt_id === 'All') {
                    this.get_unfilted_articles()
                }else{
                    this.get_filtred_articles(filt_id)
                }
            },

            get_regions(category){
                if(category == 'outdoor'){
                    axios
                    .get("../api/region/")
                    .then(response => {
                        this.data_for_tab.push({'id': 2,
                                                'data': response.data, 
                                                'table_name': "Regions",
                                                'table_add_url': 'spot_category_add', 
                                                'table_edit_url': 'spot_category_edit',
                                            });
                    })
                    .catch(
                        error => console.log(error)
                    );
                }
            },
            
            filtr(event){
                this.get_articles(event)
            },

            get_filtred_articles(id){
                axios
                .get("../api/outdoor/get_filtred_outdoor_spots_for_admin/"+id, {
                    category: this.$route.params.article_category,
                })
                .then(response => {
                    this.data_for_tab = []
                    this.data_for_tab.push({'id': 1,
                                            'data': response.data, 
                                            'table_name': this.$route.params.article_category, 
                                            'table_category': this.$route.params.article_category, 
                                            'table_add_url': 'articleAdd', 
                                            'table_edit_url': 'articleEdit',
                                        });


                    if(this.$route.params.article_category){
                        this.get_regions(this.$route.params.article_category)
                    }
                })
                .catch(
                    error => console.log(error)
                )
                .finally(() => this.article_loading = false);
            },

            get_unfilted_articles(){
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
                                            'table_edit_url': 'articleEdit',
                                        });


                    if(this.$route.params.article_category){
                        this.get_regions(this.$route.params.article_category)
                    }
                })
                .catch(
                    error => console.log(error)
                )
                .finally(() => this.article_loading = false);
            },

            // get_regions(){
            //     axios
            //     .get('../api/regions/en')
            //     .then(response => {
            //         this.regions = response.data
            //     })
            //     .catch(error =>{
            //     })
            //     .finally(() => this.region_loading = false)
            // },

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

            // update(id){
            //     if(id == 1){
            //         this.get_articles()
            //     }
            // }
        }
    }
</script>

<style>

</style>