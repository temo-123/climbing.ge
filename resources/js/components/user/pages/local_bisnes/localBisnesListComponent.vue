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
                        @update="get_local_bisnes_data"
                        @del_local_bisnes="del_bisnes"
                        @delete_selected="bulk_delete_local_bisnes"
                        @publish_selected="bulk_publish_local_bisnes"
                        @unpublish_selected="bulk_unpublish_local_bisnes"
                        @show_bisnes_modal="show_bisnes_modal"
                    />
                </div>
            </div>
        </div>

        <LocalBisnesModal
            v-if="activeUrlTitle"
            :url-title="activeUrlTitle"
            v-model="showBisnesModal"
        />
    </div>
</template>

<script>
    import tabsComponent  from '../../items/data_table/TabsComponent.vue'
    import breadcrumb from '../../items/BreadcrumbComponent.vue'
    import LocalBisnesModal from '../../../guide/items/modals/LocalBisnesModalComponent.vue'
    export default {
        components: {
            tabsComponent ,
            breadcrumb,
            LocalBisnesModal,
        },

        data() {
            return {
                data_for_tab:[],
                showBisnesModal: false,
                activeUrlTitle: null,
            }
        },

        mounted() {
            this.get_local_bisnes_data();
        },
         
        methods: {
            get_local_bisnes_data: function(){
                this.data_for_tab = []
                axios
                .get("/get_bisnes/get_local_bisneses")
                .then(response => {
                    this.data_for_tab.push({'id': 1,
                                            'table_name': this.$t('admin.local_business.local_business_table'),
                                            'has_published': true,
                                            'add_action': {
                                                'action': 'route',
                                                'link': 'localBisnesAdd', 
                                                'class': 'btn btn-primary'
                                            },
                                            'tab_data': {
                                                'data': response.data, 
                                                'tab': {
                                                    'head': [
                                                        this.$t('common.id'),
                                                        this.$t('common.title'),
                                                        this.$t('admin.common.public'),
                                                        this.$t('admin.local_business.public_data_col'),
                                                        this.$t('admin.local_business.total_public_col'),
                                                        this.$t('admin.local_business.enable_message_form_label'),
                                                        this.$t('admin.local_business.show_in_index_label'),
                                                        this.$t('common.edit'),
                                                        this.$t('common.delete'),
                                                    ],
                                                    'body': [
                                                        ['data', ['id']],
                                                        ['data_action_id', ['url_title'], 'show_bisnes_modal'],
                                                        ['data', ['published'], 'bool'],
                                                        ['data', ['published_data']],
                                                        ['data', ['public_totaly'], 'bool'],
                                                        ['data', ['enable_message_form'], 'bool'],
                                                        ['data', ['show_in_index'], 'bool'],
                                                        ['action_router', 'localBisnesEdit', 'btn btn-primary', '<i aria-hidden="true" class="fa fa-pencil"></i>'],
                                                        ['action_fun_id', 'del_local_bisnes', 'btn btn-danger', '<i aria-hidden="true" class="fa fa-trash"></i>'],
                                                    ],
                                                    'perm': [
                                                        ['no'],
                                                        ['no'],
                                                        ['no'],
                                                        ['no'],
                                                        ['no'],
                                                        ['no'],
                                                        ['no'],
                                                        ['local_bisnes', 'edit'],
                                                        ['local_bisnes', 'del'],
                                                    ]
                                                }
                                            },
                                        });
                })
                .catch(
                    error => console.log(error)
                );
            },
            row_action(data){
                    let end_day = Number(moment(data).format("D"))
                    let end_month = Number(moment(data).format("MM"))
                    let end_year = Number(moment(data).format("YYYY"))

                    if( new Date().getDate() > end_day && 
                        new Date().getMonth() >= end_month && 
                        new Date().getFullYear() >= end_year
                    ){
                        return 'completed_event'
                    }
                    if( new Date().getDate() > end_day && 
                        new Date().getMonth() == end_month && 
                        new Date().getFullYear() == end_year
                    ){
                        return 'completed_event'
                    }
                    else if( 
                        new Date().getDate() == end_day && 
                        new Date().getMonth() >= end_month && 
                        new Date().getFullYear() >= end_year
                    ){
                        return 'completed_event'
                    }
                    else if(
                        new Date().getMonth() > end_month && 
                        new Date().getFullYear() > end_year
                    ){
                        return 'completed_event'
                    }
                    else if(
                        new Date().getMonth() > end_month
                    ){
                        return 'completed_event'
                    }
                    else if(
                        new Date().getFullYear() > end_year
                    ){
                        return 'completed_event'
                    }
            },
            show_bisnes_modal(id){
                let bisnes = null
                this.data_for_tab.forEach(tab => {
                    if (tab?.tab_data?.data) {
                        const found = tab.tab_data.data.find(item => item?.id === id)
                        if (found?.id !== undefined) {
                            bisnes = found
                        }
                    }
                })
                if (!bisnes) return

                this.showBisnesModal = false
                this.activeUrlTitle = bisnes.url_title
                this.$nextTick(() => {
                    this.showBisnesModal = true
                })
            },
            del_bisnes(id){
                if(confirm(this.$t('admin.common.confirm_delete'))){
                    axios
                    .post('/set_bisnes/del_local_bisnes/'+id, {
                        _method: 'DELETE'
                    })
                    .then(Response => {
                        this.get_local_bisnes_data()
                    })
                    .catch(error => console.log(error))
                }
            },
            bulk_delete_local_bisnes(ids){
                axios.post('/set_bisnes/bulk_delete', { ids }).then(() => this.get_local_bisnes_data()).catch(error => console.log(error))
            },
            bulk_publish_local_bisnes(ids){
                axios.post('/set_bisnes/bulk_publish', { ids }).then(() => this.get_local_bisnes_data()).catch(error => console.log(error))
            },
            bulk_unpublish_local_bisnes(ids){
                axios.post('/set_bisnes/bulk_unpublish', { ids }).then(() => this.get_local_bisnes_data()).catch(error => console.log(error))
            },
        }
    }
</script>