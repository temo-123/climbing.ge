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
            get_services_data: function(){
                this.data_for_tab = []
                axios
                .get("/get_service/get_all_services/")
                .then(response => {
                    this.data_for_tab.push({
                                            'id': 1,
                                            'table_name': this.$t('admin.services.services_table'),
                                            'list_page': (process.env.MIX_APP_SSH || 'http://') + (process.env.MIX_SHOP_URL || '') + '/services',
                                            'add_action': {
                                                'action': 'route',
                                                'link': 'serviceAdd', 
                                                'class': 'btn btn-primary'
                                            },
                                            'tab_data': {
                                                'data': response.data, 
                                                'tab': {
                                                    'head': [
                                                        this.$t('common.id'),
                                                        this.$t('common.name'),
                                                        this.$t('admin.common.public'),
                                                        this.$t('common.edit'),
                                                        this.$t('common.delete'),
                                                    ],
                                                    'body': [
                                                        ['data', ['id']],
                                                        ['data', ['url_title']],
                                                        ['data', ['published'], 'bool'],
                                                        ['action_router', 'serviceEdit', 'btn btn-primary', '<i aria-hidden="true" class="fa fa-pencil"></i>'],
                                                        ['action_fun_id', 'del_service', 'btn btn-danger', '<i aria-hidden="true" class="fa fa-trash"></i>'],
                                                    ],
                                                    'perm': [
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
        }
    }
</script>