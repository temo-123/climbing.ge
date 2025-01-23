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
                        
                        @filtr_mount="filtr_mount"

                        @update="get_unfilted_articles"

                        @del_article="del_article"
                        @del_mount_masive="del_mount"
                    />
                </div>
            </div>
         </div>
    </div>
</template>

<script>
    import tabsComponent  from '../../items/data_table/TabsComponent.vue'
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
                article_loading: false,
            }
        },
        mounted() {
            this.get_unfilted_articles()
        },
        watch: {
            '$route' (to, from) {
                this.data_for_tab = [],
                this.get_unfilted_articles()
                window.scrollTo(0,0)
            }
        },
        methods: {
            get_filtred_articles(id){
                axios
                .get("/mount_route/get_filtred_mount_route_for_admin/" + id)
                .then(response => {
                    this.data_for_tab = []
                    this.data_for_tab.push({'id': 1,
                                            'table_name': 'Mountaineering routes', 
                                            'list_page': process.env.MIX_BASE_URL_SSH + '/mountaineering',
                                            'add_action': {
                                                'action': 'route',
                                                'link': 'articleAdd', 
                                                'class': 'btn btn-primary'
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
                                                        ['data', ['published'], 'bool'],
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

                    this.get_mounts()
                })
                .catch(
                    error => console.log(error)
                )
                .finally(() => this.article_loading = false);
            },

            get_unfilted_articles(){
                axios
                .get("/article/get_category_articles/mount_route")
                .then(response => {
                    this.data_for_tab = []
                    this.data_for_tab.push({
                                            'id': 1,
                                            'table_name': 'Mountaineering routes', 
                                            'list_page': process.env.MIX_BASE_URL_SSH + '/mountaineering',
                                            'add_action': {
                                                'action': 'route',
                                                'link': 'articleAdd', 
                                                'class': 'btn btn-primary'
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
                                                        ['data', ['published'], 'bool'],
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

                    this.get_mounts()
                })
                .catch(
                    error => console.log(error)
                )
                .finally(() => this.article_loading = false);
            },

            // get_unfilted_articles(filtr_id = 'all'){
            //     if (filtr_id === 'all' || filtr_id === 'All') {
            //         this.get_unfilted_articles()
            //     }
            //     else{
            //         this.get_filtred_articles(filtr_id) 
            //     }
            // },

            // filtr(event){
            //     this.get_unfilted_articles(event)
            // },

            get_mounts(){
                axios
                .get("/mount/mount")
                .then(response => {
                    this.data_for_tab.push({'id': 2,
                                            'data': response.data, 
                                            'table_name': 'Mount vasives', 
                                            'table_category': 'Mount vasives', 
                                            'table_add_url': 'mount_massive_add', 
                                            // 'table_edit_url': 'edit_url',
                                            // 'table_del_url': 'del_url', 
                                            
                                            'id': 2,
                                                'table_name': 'Mount vasives', 
                                                'add_action': {
                                                    'action': 'route',
                                                    'link': 'mount_massive_add', 
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
                                                            ['data', ['global_data', 'id']],
                                                            ['data', ['locale_data', 'title']],
                                                            ['action_router', 'mount_massive_edit', 'btn btn-primary', 'Edit', ['global_data', 'id']],
                                                            ['action_fun_id', 'del_mount_massive', 'btn btn-danger', 'Del', ['global_data', 'id']],
                                                        ],
                                                        'perm': [
                                                            ['no'],
                                                            ['no'],
                                                            ['mount_massive', 'edit',],
                                                            ['mount_massive', 'del',],
                                                        ]
                                                    }
                                                },
                                        });

                    this.data_for_tab[0].filter_data = {
                                                'title': 'Filter by Mount Masive',
                                                'data': response.data,
                                                'action_fun_id': 'filtr_outdoors',
                                                'array_key': 'us_name'
                                            }

                })
                .catch(
                    error => console.log(error)
                );
            },

            del_article(id){
                if(confirm('Are you sure, you want delite it?')){
                    axios
                    .post('../../api/article/del_article/'+id, {
                        _method: 'DELETE'
                    })
                    .then(Response => {
                        this.get_unfilted_articles()
                    })
                    .catch(error => console.log(error))
                }
            },
            quick_wiev_action(article_id){
                alert('it`s article quick view window ( article ID - '+article_id+')')
            },
            del_mount(id){
                if(confirm('Are you sure, you want delite it?')){
                    axios
                    .post('/mount/mount/'+id, {
                        _method: 'DELETE'
                    })
                    .then(Response => {
                        this.$emit('restart')
                    })
                    .catch(error => console.log(error))
                }
            },
            filtr_mount(event){
                if(event != 0){
                    this.get_filtred_articles(event)
                }
                else if(event == 0){
                    this.get_unfilted_articles(event)
                }
            },
        }
    }
</script>

<style>

</style>