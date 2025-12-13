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
                <div class="col-md-12" v-if="event_loading">
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
                        @update="get_events"

                        @del_event="del_event"
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
                event_loading: true,
            }
        },
        mounted() {
            this.get_events()
        },
        watch: {
            '$route' (to, from) {
                this.data_for_tab = [],
                this.get_events()
                window.scrollTo(0,0)
            }
        },
        methods: {
            get_events(){
                this.data_for_tab = []
                axios
                .get("/get_event/get_all_events/")
                .then(response => {
                    response.data.forEach(event => {
                        event.completed = new Date(event.end_data) < new Date();
                    });
                    this.data_for_tab.push({
                                            'id': 1,
                                            'table_name': 'Events',
                                            'add_action': {
                                                'action': 'eventAdd',
                                                'link': 'spot_category_add',
                                                'class': 'btn btn-primary'
                                            },
                                            'tab_data': {
                                                'data': response.data,
                                                'tab': {
                                                    'head': [
                                                        'ID',
                                                        'Title',
                                                        'Start Date',
                                                        'End Date',
                                                        'Public',
                                                        'Completed',
                                                        'Edit',
                                                        'Delite',
                                                    ],
                                                    'body': [
                                                        ['data', ['id']],
                                                        ['data', ['url_title']],
                                                        ['data', ['start_data']],
                                                        ['data', ['end_data']],
                                                        ['data', ['published'], 'bool'],
                                                        ['data', ['completed'], 'bool'],
                                                        ['action_router', 'eventEdit', 'btn btn-primary', 'Edit'],
                                                        ['action_fun_id', 'del_event', 'btn btn-danger', 'Del'],
                                                    ],
                                                    'perm': [
                                                        ['no'],
                                                        ['no'],
                                                        ['no'],
                                                        ['no'],
                                                        ['no'],
                                                        ['no'],
                                                        ['event', 'edit',],
                                                        ['event', 'del',],
                                                    ]
                                                }
                                            },
                                        });
                    this.get_competitions()
                })
                .catch(
                    error => console.log(error)
                )
                .finally(() => this.event_loading = false);
            },
            get_competitions(){
                axios
                .get("/get_competition/get_all_competitions/")
                .then(response => {
                    response.data.forEach(competition => {
                        competition.completed = new Date(competition.end_data) < new Date();
                    });
                    this.data_for_tab.push({
                                            'id': 2,
                                            'table_name': 'Competitions',
                                            'add_action': {
                                                'action': 'competitionAdd',
                                                'link': 'spot_category_add',
                                                'class': 'btn btn-primary'
                                            },
                                            'tab_data': {
                                                'data': response.data,
                                                'tab': {
                                                    'head': [
                                                        'ID',
                                                        'Title',
                                                        'Start Date',
                                                        'End Date',
                                                        'Public',
                                                        'Completed',
                                                        'Edit',
                                                        'Delite',
                                                    ],
                                                    'body': [
                                                        ['data', ['id']],
                                                        ['data', ['url_title']],
                                                        ['data', ['start_data']],
                                                        ['data', ['end_data']],
                                                        ['data', ['published'], 'bool'],
                                                        ['data', ['completed'], 'bool'],
                                                        ['action_router', 'competitionEdit', 'btn btn-primary', 'Edit'],
                                                        ['action_fun_id', 'del_event', 'btn btn-danger', 'Del'],
                                                    ],
                                                    'perm': [
                                                        ['no'],
                                                        ['no'],
                                                        ['no'],
                                                        ['no'],
                                                        ['no'],
                                                        ['no'],
                                                        ['event', 'edit',],
                                                        ['event', 'del',],
                                                    ]
                                                }
                                            },
                                        });
                })
                .catch(
                    error => console.log(error)
                )
                .finally(() => this.event_loading = false);
            },

            del_event(id){
                if(confirm('Are you sure, you want delite it?')){
                    axios
                    .post('/set_event/del_event/'+id, {
                        _method: 'DELETE'
                    })
                    .then(Response => {
                        this.get_events()
                    })
                    .catch(error => console.log(error))
                }
            },
        }
    }
</script>

<style>

</style>