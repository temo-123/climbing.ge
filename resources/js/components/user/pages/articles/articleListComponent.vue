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
                        @update="get_articles"

                        @del_article="del_article"
                        @del_region="del_region"

                        @filtr_outdoors="filtr_outdoors"
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
                        this.data_for_tab.push({
                                                'id': 2,
                                                'table_name': 'Regions', 
                                                'add_action': {
                                                    'action': 'route',
                                                    'link': 'spot_category_add', 
                                                },
                                                'tab_data': {
                                                    'data': response.data, 
                                                    'tab': {
                                                        'head': [
                                                            'ID',
                                                            'Name',
                                                            'Edit',
                                                            'Delite',
                                                        ],
                                                        'body': {
                                                            1: ['data', 'id'],
                                                            2: ['data', 'us_name'],
                                                            3: ['action_router', 'spot_category_edit', 'btn btn-primary', 'Edit'],
                                                            4: ['action_fun_id', 'del_region', 'btn btn-danger', 'Del'],
                                                        },
                                                        'perm': {
                                                            1: ['no'],
                                                            2: ['no'],
                                                            3: ['aricle', 'edit'],
                                                            4: ['aricle', 'del'],
                                                        }
                                                    }
                                                },
                                            });

                        this.data_for_tab[0].filter_data = {
                                                    'title': 'Filter by regions',
                                                    'data': response.data,
                                                    'action_fun_id': 'filtr_outdoors',
                                                    'array_key': 'us_name'
                                                }

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
                                                            3: ['action_url', 'edit_url', 'btn btn-primary', 'Edit'],
                                                            4: ['action_fun', 'del', 'btn btn-danger', 'Del'],
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
                                                            3: ['action_url', 'edit_url', 'btn btn-primary', 'Edit'],
                                                            4: ['action_fun', 'del', 'btn btn-danger', 'Del'],
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
                                                            3: ['action_url', 'edit_url', 'btn btn-primary', 'Edit'],
                                                            4: ['action_fun', 'del', 'btn btn-danger', 'Del'],
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
                    this.data_for_tab.push
                                        ({  
                                            'id': 1,
                                            'table_name': this.$route.params.article_category, 
                                            'list_page': process.env.MIX_BASE_URL_SSH + '/outdoor',
                                            'add_action': {
                                                'action': 'route',
                                                'link': 'articleAdd', 
                                            },
                                            'tab_data': {
                                                'data': response.data, 
                                                'tab': {
                                                    'head': [
                                                        'ID',
                                                        'Title',
                                                        'Public',
                                                        'Edit',
                                                        'Delite',
                                                ],
                                                    'body': {
                                                        1: ['data', 'id'],
                                                        2: ['data', 'url_title'],
                                                        3: ['data', 'published'],
                                                        4: ['action_router', 'articleEdit', 'btn btn-primary', 'Edit'],
                                                        5: ['action_fun_id', 'del_article', 'btn btn-danger', 'Del'],
                                                        // ['action_fun', 'del_article', 'btn btn-danger', 'Del'],
                                                    },
                                                    'perm': {
                                                        1: ['no'],
                                                        2: ['no'],
                                                        3: ['no'],
                                                        4: ['aricle', 'edit'],
                                                        5: ['aricle', 'del'],
                                                    }
                                                }
                                            },
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

            del_article(event){
                alert('del_article ' + event)
            },

            del_region(event){
                alert('del_region ' + event)
            },

            filtr_outdoors(event){
                alert('filtr_outdoors ' + event)
            }
        }
    }
</script>

<style>

</style>