<template>
    <div class="row">
        <left-menu />
        <div class="col-sm-12">
            <div class="row">
                <div class="col-md-12">
                    <breadcrumb />
                </div>
            </div>
            <div class="row">
                <div class="col-md-12" v-if="loading">
                    <div class="text-center py-4">
                        <i class="fa fa-spinner fa-spin fa-3x"></i>
                    </div>
                </div>
                <div class="col-sm-12" v-else>
                    <tabsComponent
                        ref="tabsComponent"
                        :table_data="data_for_tab"
                        @update="get_data"
                        @del_organization="del_organization"
                        @del_member="del_member"
                        @delete_selected="bulk_delete_dispatch"
                    />
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import tabsComponent from '../../items/data_table/TabsComponent.vue'
import breadcrumb from '../../items/BreadcrumbComponent.vue'

export default {
    components: {
        breadcrumb,
        tabsComponent,
    },
    data() {
        return {
            data_for_tab: [],
            loading: true,
        }
    },
    mounted() {
        this.get_data()
    },
    methods: {
        get_data() {
            this.loading = true
            Promise.all([
                axios.get('/set_partner_organization/get_all_organizations'),
                axios.get('/set_partner_organization_member/get_all_members'),
            ]).then(([organizations, members]) => {
                this.data_for_tab = [
                    {
                        id: 1,
                        table_name: this.$t('admin.partner_organizations.organizations_table'),
                        add_action: {
                            action: 'route',
                            link: 'partnerOrganizationAdd',
                            class: 'btn btn-primary'
                        },
                        tab_data: {
                            data: organizations.data,
                            tab: {
                                head: [
                                    this.$t('common.id'),
                                    this.$t('common.name'),
                                    this.$t('admin.partner_organizations.discount_col'),
                                    this.$t('common.edit'),
                                    this.$t('common.delete'),
                                ],
                                body: [
                                    ['data', ['id']],
                                    ['data', ['name']],
                                    ['data', ['discount']],
                                    ['action_router', 'partnerOrganizationEdit', 'btn btn-primary', '<i aria-hidden="true" class="fa fa-pencil"></i>'],
                                    ['action_fun_id', 'del_organization', 'btn btn-danger', '<i aria-hidden="true" class="fa fa-trash"></i>'],
                                ],
                                perm: [
                                    ['no'],
                                    ['no'],
                                    ['no'],
                                    ['partner_organization', 'edit'],
                                    ['partner_organization', 'del'],
                                ]
                            }
                        }
                    },
                    {
                        id: 2,
                        table_name: this.$t('admin.partner_organizations.members_table'),
                        add_action: {
                            action: 'route',
                            link: 'partnerOrganizationMemberAdd',
                            class: 'btn btn-primary'
                        },
                        tab_data: {
                            data: members.data,
                            tab: {
                                head: [
                                    this.$t('common.id'),
                                    this.$t('common.name'),
                                    this.$t('common.surname'),
                                    this.$t('common.email'),
                                    this.$t('common.phone_number'),
                                    this.$t('admin.partner_organizations.organization_col'),
                                    this.$t('admin.partner_organizations.linked_user_col'),
                                    this.$t('common.edit'),
                                    this.$t('common.delete'),
                                ],
                                body: [
                                    ['data', ['id']],
                                    ['data', ['name']],
                                    ['data', ['surname']],
                                    ['data', ['email']],
                                    ['data', ['phone_number']],
                                    ['data', ['organization', 'name']],
                                    ['data', ['user_id'], 'bool'],
                                    ['action_router', 'partnerOrganizationMemberEdit', 'btn btn-primary', '<i aria-hidden="true" class="fa fa-pencil"></i>'],
                                    ['action_fun_id', 'del_member', 'btn btn-danger', '<i aria-hidden="true" class="fa fa-trash"></i>'],
                                ],
                                perm: [
                                    ['no'],
                                    ['no'],
                                    ['no'],
                                    ['no'],
                                    ['no'],
                                    ['no'],
                                    ['no'],
                                    ['partner_organization', 'edit'],
                                    ['partner_organization', 'del'],
                                ]
                            }
                        }
                    },
                ]
            }).catch(error => console.error('Error loading partner organizations:', error))
            .finally(() => this.loading = false)
        },

        del_organization(id) {
            if (confirm(this.$t('admin.common.confirm_delete'))) {
                axios.delete('/set_partner_organization/del_organization/' + id).then(() => this.get_data()).catch(error => console.error(error))
            }
        },

        del_member(id) {
            if (confirm(this.$t('admin.common.confirm_delete'))) {
                axios.delete('/set_partner_organization_member/del_member/' + id).then(() => this.get_data()).catch(error => console.error(error))
            }
        },

        bulk_delete_dispatch(ids) {
            const tab_num = this.$refs.tabsComponent?.tab_num
            if (tab_num === 2) {
                axios.post('/set_partner_organization_member/bulk_delete', { ids }).then(() => this.get_data()).catch(error => console.error(error))
            } else {
                axios.post('/set_partner_organization/bulk_delete', { ids }).then(() => this.get_data()).catch(error => console.error(error))
            }
        },
    }
}
</script>
