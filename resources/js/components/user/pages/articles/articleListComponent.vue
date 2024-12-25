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
    import tabsComponent  from '../../items/data_table/TabsComponent.vue'
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
                    .get("/outdoor/region/")
                    .then(response => {
                        this.data_for_tab.push({'id': 2,
                                                'tab_data': {
                                                    'data': response.data, 
                                                    'tab': {
                                                        'head': [
                                                            'title',
                                                            'Public',
                                                            'Edit',
                                                            'Delite',
                                                    ],
                                                        'body': {
                                                            1: ['data', 'url_title'],
                                                            2: ['data', 'published'],
                                                            2: ['action_url', 'edit_url'],
                                                            2: ['action_fun', 'del'],
                                                        },
                                                    }
                                                },
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
            get_ice_sectors(category){
                if(category == 'ice'){
                    axios
                    .get("/ice_sectors/get_all_sectors/")
                    .then(response => {
                        this.data_for_tab.push({'id': 2,
                                                'tab_data': {
                                                    'data': response.data, 
                                                    'tab': {
                                                        'head': [
                                                            'title',
                                                            'Public',
                                                            'Edit',
                                                            'Delite',
                                                    ],
                                                        'body': {
                                                            1: ['data', 'url_title'],
                                                            2: ['data', 'published'],
                                                            2: ['action_url', 'edit_url'],
                                                            2: ['action_fun', 'del'],
                                                        },
                                                    }
                                                },
                                                'table_name': "Ice sectors",
                                                'table_add_url': 'iceSectorAdd', 
                                            });
                        this.get_ice_routes()
                    })
                    .catch(
                        error => console.log(error)
                    );
                }
            },
            get_ice_routes(){
                axios
                .get("/ice_routes/get_all_routes/")
                .then(response => {
                    this.data_for_tab.push({'id': 3,
                                                'tab_data': {
                                                    'data': response.data, 
                                                    'tab': {
                                                        'head': [
                                                            'title',
                                                            'Public',
                                                            'Edit',
                                                            'Delite',
                                                    ],
                                                        'body': {
                                                            1: ['data', 'url_title'],
                                                            2: ['data', 'published'],
                                                            2: ['action_url', 'edit_url'],
                                                            2: ['action_fun', 'del'],
                                                        },
                                                    }
                                                },
                                            'table_name': "Ice routes",
                                            'table_add_url': 'iceRouteAdd', 
                                        });
                })
                .catch(
                    error => console.log(error)
                );
            },
            
            filtr(event){
                this.get_articles(event)
            },

            get_filtred_articles(id){
                axios
                .get("/outdoor/get_filtred_outdoor_spots_for_admin/"+id, {
                    category: this.$route.params.article_category,
                })
                .then(response => {
                    this.data_for_tab = []
    
                    this.data_for_tab.push({'id': 1,
                                                'tab_data': {
                                                    'data': response.data, 
                                                    'tab': {
                                                        'head': [
                                                            'title',
                                                            'Public',
                                                            'Edit',
                                                            'Delite',
                                                    ],
                                                        'body': {
                                                            1: ['data', 'url_title'],
                                                            2: ['data', 'published'],
                                                            2: ['action_url', 'edit_url'],
                                                            2: ['action_fun', 'del'],
                                                        },
                                                    }
                                                },
                                            'table_name': this.$route.params.article_category, 
                                            'table_category': this.$route.params.article_category, 
                                            'table_add_url': 'articleAdd', 
                                            // 'table_edit_url': 'articleEdit',
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
                .get("/article/get_category_articles/"+this.$route.params.article_category)
                .then(response => {
                    this.data_for_tab = []
                    this.data_for_tab.push({'id': 1,
                                                'tab_data': {
                                                    'data': response.data, 
                                                    'tab': {
                                                        'head': [
                                                            'title',
                                                            'Public',
                                                            'Edit',
                                                            'Delite',
                                                    ],
                                                        'body': {
                                                            1: ['data', 'url_title'],
                                                            2: ['data', 'published'],
                                                            2: ['action_url', 'edit_url'],
                                                            2: ['action_fun', 'del'],
                                                        },
                                                    }
                                                },
                                            'table_name': this.$route.params.article_category, 
                                            'table_category': this.$route.params.article_category, 
                                            'table_add_url': 'articleAdd', 
                                            'table_edit_url': 'articleEdit',
                                        });


                    if(this.$route.params.article_category == 'outdoor'){
                        this.get_regions(this.$route.params.article_category)
                    }
                    if(this.$route.params.article_category == 'ice'){
                        this.get_ice_sectors(this.$route.params.article_category)
                    }
                })
                .catch(
                    error => console.log(error)
                )
                .finally(() => this.article_loading = false);
            },
        }
    }
</script>

<style>

</style>