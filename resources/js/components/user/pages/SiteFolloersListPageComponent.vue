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
                    <!-- <galleryTab /> -->
                    <!-- followers -->
                    <tabsComponent 
                        :table_data="this.data_for_tab"
                        @update="get_site_followers"
                        @filtr="filtr"

                        @del_site_followers="del_site_followers"
                    />
                </div>
            </div>
         </div>
    </div>
</template>

<script>
    import breadcrumb from '../items/BreadcrumbComponent.vue'
    import tabsComponent  from '../items/data_table/TabsComponent.vue'

    // import galleryTab from '../items/data_tabs/GalleryTabComponent.vue';
    export default {
        data(){
            return {
                users: [],
                data_for_tab: []
            }
        },
        components: {
            // galleryTab,
            tabsComponent,
            breadcrumb
        },
        mounted() {
            this.get_site_followers()
        },
    //     watch: {
    //         '$route' (to, from) {
    //             this.categories = [],
    //             this.get_images_categories()
    //             window.scrollTo(0,0)
    //         }
    //     },
        methods: {
            filtr(event){
                // this.get_site_followers(event)
                this.get_site_followers()
            },
            get_site_followers(){
                this.data_for_tab = []
                axios
                .get("/follow/following_users_list/")
                .then(response => {
                    this.users = response.data
                    this.data_for_tab.push
                                        // ({'id': 1,
                                        //     'data': this.users, 
                                        //     'table_name': 'Site Followers', 
                                        // })
                                        ({  
                                            'id': 1,
                                            'table_name': 'Site Followers', 
                                            // 'add_action': {
                                            //     'action': 'route',
                                            //     'link': 'articleAdd', 
                                            //     'class': 'btn btn-primary'
                                            // },
                                            'tab_data': {
                                                'data': response.data, 
                                                'tab': {
                                                    'head': [
                                                        'ID',
                                                        'Email',
                                                        'Service',
                                                        'Delite',
                                                    ],
                                                    'body': [
                                                        ['data', ['id']],
                                                        ['data', ['email']],
                                                        ['data', ['service']],
                                                        ['action_fun_id', 'del_site_followers', 'btn btn-danger', '<i aria-hidden="true" class="fa fa-trash"></i>'],
                                                    ],
                                                    'perm': [
                                                        ['no'],
                                                        ['no'],
                                                        ['no'],
                                                        ['site_folloers', 'del'],
                                                    ]
                                                }
                                            },
                                        });
                })
                .catch(
                    error => console.log(error)
                );
            },

            del_site_followers(id){
                if(confirm('Are you sure, you want delite it?')){
                    axios
                    .post('/follow/del_follower/'+id, {
                        _method: 'DELETE'
                    })
                    .then(Response => {
                        this.get_site_followers()
                    })
                    .catch(error => console.log(error))
                }
            }
        }
    }
</script>
