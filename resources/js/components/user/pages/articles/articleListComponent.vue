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
                        @del_ice_route="del_ice_route"
                        @del_ice_sector="del_ice_sector"

                        @filtr_outdoors="filtr_outdoors"
                        @show_spot_sectors_modal="show_spot_sectors_modal"
                        @quick_wiev_action="quick_wiev_action"
                    />
                </div>
            </div>
         </div>
        <spot_sectors_modal
            ref="show_spot_sectors_modal"
        />
    </div>
</template>

<script>
    import tabsComponent  from '../../items/data_table/TabsComponent.vue'
    import { ContentLoader } from 'vue-content-loader'
    import breadcrumb from '../../items/BreadcrumbComponent.vue'
    import spot_sectors_modal from "../../items/modal/tab_modals/ArticleSectorSequenceModalComponent.vue";
    export default {
        components: {
            breadcrumb,
            tabsComponent,
            ContentLoader,

            spot_sectors_modal
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
                                                        'body': [
                                                            ['data', ['id']],
                                                            ['data', ['us_name']],
                                                            ['action_router', 'spot_category_edit', 'btn btn-primary', 'Edit'],
                                                            ['action_fun_id', 'del_region', 'btn btn-danger', 'Del'],
                                                        ],
                                                        'perm': [
                                                            ['no'],
                                                            ['no'],
                                                            ['spot_region', 'edit',],
                                                            ['spot_region', 'del',],
                                                        ]
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
                        this.data_for_tab.push({
                                                'id': 2,
                                                'table_name': 'Ice sectors', 
                                                'add_action': {
                                                    'action': 'route',
                                                    'link': 'iceSectorAdd', 
                                                },
                                                'tab_data': {
                                                    'data': response.data, 
                                                    'tab': {
                                                        'head': [
                                                            'ID',
                                                            'Name',
                                                            'Public',
                                                            'Edit',
                                                            'Delite',
                                                        ],
                                                        'body': [
                                                            ['data', ['id']],
                                                            ['data', ['name']],
                                                            ['data', ['published']],
                                                            ['action_router', 'iceSectorEdit', 'btn btn-primary', '<i aria-hidden="true" class="fa fa-pencil"></i>'],
                                                            ['action_fun_id', 'del_ice_sector', 'btn btn-danger', '<i aria-hidden="true" class="fa fa-trash"></i>'],
                                                        ],
                                                        'perm': [
                                                            ['no'],
                                                            ['no'],
                                                            ['no'],
                                                            ['ice_sector', 'edit'],
                                                            ['ice_sector', 'del'],
                                                        ]
                                                    }
                                                },
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
                    this.data_for_tab.push({
                                            'id': 3,
                                            'table_name': 'Ice routes', 
                                            'add_action': {
                                                'action': 'route',
                                                'link': 'iceRouteAdd', 
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
                                                    'body': [
                                                        ['data', ['id']],
                                                        ['data', ['name']],
                                                        ['action_router', 'iceRouteEdit', 'btn btn-primary', '<i aria-hidden="true" class="fa fa-pencil"></i>'],
                                                        ['action_fun_id', 'del_ice_route', 'btn btn-danger', '<i aria-hidden="true" class="fa fa-trash"></i>'],
                                                    ],
                                                    'perm': [
                                                        ['no'],
                                                        ['no'],
                                                        ['article', 'edit'],
                                                        ['article', 'del'],
                                                    ]
                                                }
                                            },
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
    
                    this.data_for_tab.push({
                                            'id': 1,
                                            'table_name': this.$route.params.article_category, 
                                            'list_page': process.env.MIX_BASE_URL_SSH + '/' + this.$route.params.article_category,
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
                                                    'body': [
                                                        ['data', ['id']],
                                                        ['data_action', ['url_title'], 'quick_wiev_action'],
                                                        ['data', ['published']],
                                                        ['action_router', 'articleEdit', 'btn btn-primary', '<i aria-hidden="true" class="fa fa-pencil"></i>'],
                                                        ['action_fun_id', 'del_article', 'btn btn-danger', '<i aria-hidden="true" class="fa fa-trash"></i>'],
                                                    ],
                                                    'perm': [
                                                        ['no'],
                                                        ['no'],
                                                        ['no'],
                                                        ['article', 'edit'],
                                                        ['article', 'del'],
                                                    ]
                                                }
                                            },
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
                                            'list_page': process.env.MIX_BASE_URL_SSH + '/' + this.$route.params.article_category,
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
                                                    'body': [
                                                        ['data', ['id']],
                                                        ['data_action', ['url_title'], 'quick_wiev_action'],
                                                        ['data', ['published']],
                                                        ['action_router', 'articleEdit', 'btn btn-primary', '<i aria-hidden="true" class="fa fa-pencil"></i>'],
                                                        ['action_fun_id', 'del_article', 'btn btn-danger', '<i aria-hidden="true" class="fa fa-trash"></i>'],
                                                    ],
                                                    'perm': [
                                                        ['no'],
                                                        ['no'],
                                                        ['no'],
                                                        ['article', 'edit'],
                                                        ['article', 'del'],
                                                    ]
                                                }
                                            },
                                        });

                    if(this.$route.params.article_category == 'outdoor'){
                        this.get_regions(this.$route.params.article_category)
                        
                        this.data_for_tab[0].tab_data.tab.head.splice(3, 0, 'Sectors')
                        this.data_for_tab[0].tab_data.tab.body.splice(3, 0, ['action_fun_id', 'show_spot_sectors_modal', 'btn btn-success', '<i aria-hidden="true" class="fa fa-list-ol"></i>'])
                        this.data_for_tab[0].tab_data.tab.perm.splice(3, 0, ['article', 'edit'])
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

            del_article(id){
                if(confirm('Are you sure, you want delite it?')){
                    axios
                    .post('../../api/article/del_article/'+id, {
                        _method: 'DELETE'
                    })
                    .then(Response => {
                        this.get_articles()
                    })
                    .catch(error => console.log(error))
                }
            },

            del_region(id){
                if(confirm('Are you sure, you want delite it?')){
                    axios
                    .post('../../api/outdoor/del_spot/'+id, {
                        id: id,
                        _method: 'delete'
                    })
                    .then(Response => {
                        this.get_articles()
                    })
                    .catch(error => console.log(error))
                }
            },

            del_ice_route(id){
                if(confirm('Are you sure, you want delite it?')){
                    axios
                    .post('/ice_routes/del_route/'+id, {
                        _method: 'DELETE'
                    })
                    .then(Response => {
                        this.get_articles()
                    })
                    .catch(error => console.log(error))
                }
            },

            del_ice_sector(id){
                if(confirm('Are you sure, you want delite it?')){
                    axios
                    .post('/ice_sectors/del_sector/'+id, {
                        _method: 'DELETE'
                    })
                    .then(Response => {                        
                        this.get_articles()
                    })
                    .catch(error => console.log(error))
                }
            },

            filtr_outdoors(event){
                if(event != 0){
                    this.get_filtred_articles(event)
                }
                else if(event == 0){
                    this.get_unfilted_articles(event)
                }
            },

            show_spot_sectors_modal(article_id){
                this.$refs.show_spot_sectors_modal.show_spot_sectors_modal(article_id)
            },
            quick_wiev_action(article_id){
                alert('it`s article quick view window ( article ID - '+article_id+')')
            }
        }
    }
</script>

<style>

</style>