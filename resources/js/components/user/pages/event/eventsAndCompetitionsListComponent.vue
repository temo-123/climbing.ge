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
                <div class="col-md-12 text-center py-4" v-if="event_loading">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">{{ $t('admin.events.loading_ellipsis') }}</span>
                    </div>
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

    import breadcrumb from '../../items/BreadcrumbComponent.vue'
    export default {
        components: {
            breadcrumb,
            tabsComponent,
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
                                            'table_name': this.$t('admin.events.events_table'),
                                            'list_page': process.env.MIX_APP_SSH
                                                ? (process.env.MIX_APP_SSH || '').replace(/\/$/, '') + '/' + (process.env.MIX_GUIDBOOK_URL || '').replace(/^\/|\/$/g, '') + '/events'
                                                : window.location.origin + '/events',
                                            'add_action': {
                                                'action': 'route',
                                                'link': 'eventAdd',
                                                'class': 'btn btn-primary'
                                            },
                                            'tab_data': {
                                                'data': response.data,
                                                'tab': {
                                                    'head': [
                                                        this.$t('common.id'),
                                                        this.$t('common.title'),
                                                        this.$t('admin.events.start_date_col'),
                                                        this.$t('admin.events.end_date_col'),
                                                        this.$t('admin.common.public'),
                                                        this.$t('admin.events.completed_col'),
                                                        this.$t('common.edit'),
                                                        this.$t('common.delete'),
                                                    ],
                                                    'body': [
                                                        ['data', ['id']],
                                                        ['data', ['url_title']],
                                                        ['data', ['start_data']],
                                                        ['data', ['end_data']],
                                                        ['data', ['published'], 'bool'],
                                                        ['data', ['completed'], 'bool'],
                                                        ['action_router', 'eventEdit', 'btn btn-primary', this.$t('common.edit')],
                                                        ['action_fun_id', 'del_event', 'btn btn-danger', this.$t('common.delete')],
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
                                            'table_name': this.$t('admin.events.competitions_table'),
                                            'list_page': process.env.MIX_APP_SSH
                                                ? (process.env.MIX_APP_SSH || '').replace(/\/$/, '') + '/' + (process.env.MIX_GUIDBOOK_URL || '').replace(/^\/|\/$/g, '') + '/events'
                                                : window.location.origin + '/events',
                                            'add_action': {
                                                'action': 'route',
                                                'link': 'competitionAdd',
                                                'class': 'btn btn-primary'
                                            },
                                            'tab_data': {
                                                'data': response.data,
                                                'tab': {
                                                    'head': [
                                                        this.$t('common.id'),
                                                        this.$t('common.title'),
                                                        this.$t('admin.events.start_date_col'),
                                                        this.$t('admin.events.end_date_col'),
                                                        this.$t('admin.common.public'),
                                                        this.$t('admin.events.completed_col'),
                                                        this.$t('common.edit'),
                                                        this.$t('common.delete'),
                                                    ],
                                                    'body': [
                                                        ['data', ['id']],
                                                        ['data', ['url_title']],
                                                        ['data', ['start_data']],
                                                        ['data', ['end_data']],
                                                        ['data', ['published'], 'bool'],
                                                        ['data', ['completed'], 'bool'],
                                                        ['action_router', 'competitionEdit', 'btn btn-primary', this.$t('common.edit')],
                                                        ['action_fun_id', 'del_event', 'btn btn-danger', this.$t('common.delete')],
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
                if(confirm(this.$t('admin.common.confirm_delete'))){
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