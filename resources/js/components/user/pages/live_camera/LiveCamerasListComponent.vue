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
                        @update="get_live_camera_data"

                        @show_live_camera_edit_medal="show_live_camera_edit_medal"
                        @show_live_camera_add_medal="show_live_camera_add_medal"
                        @del_live_camera="del_live_camera"
                    />
                </div>
            </div>
        </div>

        <live_camera_add_medal
            ref="show_live_camera_add_medal"
            @update="get_live_camera_data"
        />
        <live_camera_edit_medal
            ref="show_live_camera_edit_medal"
            @update="get_live_camera_data"
        />
    </div>
</template>

<script>
    import tabsComponent  from '../../items/data_table/TabsComponent.vue'
    import breadcrumb from '../../items/BreadcrumbComponent.vue'
    import { ContentLoader } from 'vue-content-loader'

    import live_camera_add_medal from '../../items/modal/tab_modals/add/AddLiveCameraModalComponent.vue'
    import live_camera_edit_medal from '../../items/modal/tab_modals/edit/EditLiveCameraModalComponent.vue'
    export default {
        components: {
            tabsComponent,
            breadcrumb,
            ContentLoader,
            live_camera_add_medal,
            live_camera_edit_medal,
        },
        
        data() {
            return {
                data_for_tab:[],
                article_loading: false,
            }
        },

        mounted() {
            this.get_live_camera_data();
        },
         
        methods: {
            get_live_camera_data(){
                this.article_loading = true;

                axios
                .get("/live_camera/get_live_cameras/")
                .then(response => {
                    this.data_for_tab = []
    
                    this.data_for_tab.push({
                                            'id': 1,
                                            'table_name': 'Live Cameras', 
                                            // 'list_page': process.env.MIX_BASE_URL_SSH + '/' + this.$route.params.article_category,
                                            'add_action': {
                                                'action': 'function',
                                                'link': 'show_live_camera_add_medal', 
                                                'class': 'btn btn-primary'
                                            },
                                            'tab_data': {
                                                'data': response.data, 
                                                'tab': {
                                                    'head': [
                                                        'ID',
                                                        'Name',
                                                        'Articlre ID',
                                                        'Public',
                                                        'Edit',
                                                        'Delite',
                                                    ],
                                                    'body': [
                                                        ['data', ['id']],
                                                        ['data', ['name']],
                                                        ['data', ['article_id']],
                                                        ['data', ['published'], 'bool'],
                                                        ['action_fun_id', 'show_live_camera_edit_medal', 'btn btn-primary', '<i aria-hidden="true" class="fa fa-pencil"></i>'],
                                                        ['action_fun_id', 'del_live_camera', 'btn btn-danger', '<i aria-hidden="true" class="fa fa-trash"></i>'],
                                                    ],
                                                    'perm': [
                                                        ['no'],
                                                        ['no'],
                                                        ['no'],
                                                        ['no'],
                                                        ['live_camera', 'edit'],
                                                        ['live_camera', 'del'],
                                                    ]
                                                }
                                            },
                                        });
                })
                .catch(
                    error => console.log(error)
                )
                .finally(() => {
                    this.article_loading = false;
                });
            },

            del_live_camera(id){
                if(confirm('Are you sure, you want delite it?')){
                    axios
                    .post('/live_camera/del_live_camera/' + id, {
                        _method: 'DELETE'
                    })
                    .then(Response => {
                        this.get_live_camera_data()
                    })
                    .catch(error => console.log(error))
                }
            },

            show_live_camera_add_medal(){
                this.$refs.show_live_camera_add_medal.show_modal();
            },

            show_live_camera_edit_medal(id){
                this.$refs.show_live_camera_edit_medal.show_modal(id);
            },
        }
    }
</script>