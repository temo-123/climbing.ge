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
                    <!-- <content-loader
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
                    </content-loader> -->
                </div>
                <div class="col-sm-12" v-else>
                    <tabsComponent 
                        :table_data="this.data_for_tab"
                        @update="get_posts"

                        @del_post="del_post"
                    />
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import tabsComponent  from '../../items/data_table/TabsComponent.vue'
    import breadcrumb from '../../items/BreadcrumbComponent.vue'
    import { ContentLoader } from 'vue-content-loader'
    export default {
        components: {
            tabsComponent,
            breadcrumb,
            ContentLoader,
        },
        
        data() {
            return {
                data_for_tab:[],
                article_loading: false,
            }
        },

        mounted() {
            this.get_posts();
        },
         
        methods: {
            get_posts(){
                this.article_loading = true;
                axios
                .get("/post/get_posts/")
                .then(response => {
                    this.data_for_tab = []
    
                    this.data_for_tab.push({
                                            'id': 1,
                                            'table_name': 'Posts', 
                                            // 'list_page': process.env.MIX_BASE_URL_SSH + '/' + this.$route.params.article_category,
                                            'add_action': {
                                                'action': 'route',
                                                'link': 'add_post', 
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
                                                        ['data', ['url_title']],
                                                        ['data', ['published'], 'bool'],
                                                        ['action_router', 'edit_post', 'btn btn-primary', '<i aria-hidden="true" class="fa fa-pencil"></i>'],
                                                        ['action_fun_id', 'del_post', 'btn btn-danger', '<i aria-hidden="true" class="fa fa-trash"></i>'],
                                                    ],
                                                    'perm': [
                                                        ['no'],
                                                        ['no'],
                                                        ['no'],
                                                        ['post', 'edit'],
                                                        ['post', 'del'],
                                                    ]
                                                }
                                            },
                                        });
                })
                .finally(() => {
                    this.article_loading = false;
                });
            },
            del_post(id){
                if (confirm("Are you sure you want to delete this article?")) {
                    axios
                    .delete("/post/del_post/" + id)
                    .then(response => {
                        this.get_posts()
                    })
                }
            },
        }
    }
</script>