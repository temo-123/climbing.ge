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
                        @update="get_services_data"
                        @del_service="del_service"
                        @delete_selected="delete_selected"
                        @publish_selected="publish_selected"
                        @unpublish_selected="unpublish_selected"
                    />
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import tabsComponent  from '../../items/data_table/TabsComponent.vue'
    import breadcrumb from '../../items/BreadcrumbComponent.vue'
    import moment from "moment"; // https://www.npmjs.com/package/vue-moment
    export default {
        components: {
            moment,
            tabsComponent ,
            breadcrumb
        },
    
        data() {
            return {
                data_for_tab:[],
            }
        },

        mounted() {
            this.get_services_data();
        },
         
        methods: {
            // services.link_type → "Gear rental prices · Main warehouse", "Climbing wall calculator", "—"
            linked_label(row, types){
                if (!row.link_type) return '—'
                const key = 'admin.services.link_type_' + row.link_type
                const name = this.$te(key) ? this.$t(key) : row.link_type
                if (row.link_type !== 'gear_rental') return name
                const targets = (types.find(t => t.key === 'gear_rental') || {}).targets || []
                const w = row.link_id ? targets.find(t => t.id === row.link_id) : targets.find(t => t.general)
                return name + ' · ' + (w ? w.name : this.$t('admin.services.link_general_warehouse'))
            },
            get_services_data: function(){
                this.data_for_tab = []
                Promise.all([
                    axios.get("/get_service/get_all_services/"),
                    // warehouse names for "Gear rental prices · <warehouse>"
                    axios.get('/set_service/get_link_targets', { _silent: true }).catch(() => ({ data: { types: [] } })),
                ])
                .then(([response, targets]) => {
                    const rows = (response.data || []).map(row => ({ ...row, linked_label: this.linked_label(row, targets.data.types || []) }))
                    this.data_for_tab.push({
                                            'id': 1,
                                            'table_name': this.$t('admin.services.services_table'),
                                            'list_page': (process.env.MIX_APP_SSH || 'http://') + (process.env.MIX_SHOP_URL || '') + '/services',
                                            'has_published': true,
                                            'add_action': {
                                                'action': 'route',
                                                'link': 'serviceAdd',
                                                'class': 'btn btn-primary'
                                            },
                                            'tab_data': {
                                                'data': rows, 
                                                'tab': {
                                                    'head': [
                                                        this.$t('common.id'),
                                                        this.$t('common.name'),
                                                        this.$t('admin.common.public'),
                                                        this.$t('admin.services.linked_col'),
                                                        this.$t('common.edit'),
                                                        this.$t('common.delete'),
                                                    ],
                                                    'body': [
                                                        ['data', ['id']],
                                                        ['data', ['url_title']],
                                                        ['data', ['published'], 'bool'],
                                                        ['data', ['linked_label']],
                                                        ['action_router', 'serviceEdit', 'btn btn-primary', '<i aria-hidden="true" class="fa fa-pencil"></i>'],
                                                        ['action_fun_id', 'del_service', 'btn btn-danger', '<i aria-hidden="true" class="fa fa-trash"></i>'],
                                                    ],
                                                    'perm': [
                                                        ['no'],
                                                        ['no'],
                                                        ['no'],
                                                        ['no'],
                                                        ['services', 'edit'],
                                                        ['services', 'del'],
                                                    ]
                                                }
                                            },
                                        });
                })
                .catch(
                    error => console.log(error)
                );
            },
            del_service(id){
                if(confirm(this.$t('admin.common.confirm_delete'))){
                    axios
                    .post('/set_service/del_service/'+id, {
                        _method: 'DELETE'
                    })
                    .then(Response => {
                        this.get_services_data();
                    })
                    .catch(error => console.log(error))
                }
            },
            delete_selected(ids){
                axios
                .post('/set_service/bulk_delete', { ids })
                .then(Response => {
                    this.get_services_data();
                })
                .catch(error => console.log(error))
            },
            publish_selected(ids){
                axios
                .post('/set_service/bulk_publish', { ids })
                .then(Response => {
                    this.get_services_data();
                })
                .catch(error => console.log(error))
            },
            unpublish_selected(ids){
                axios
                .post('/set_service/bulk_unpublish', { ids })
                .then(Response => {
                    this.get_services_data();
                })
                .catch(error => console.log(error))
            },
        }
    }
</script>