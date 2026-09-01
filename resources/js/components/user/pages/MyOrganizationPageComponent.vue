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

                <div class="col-sm-12" v-else-if="status && status.is_member">
                    <div class="jumbotron width_100">
                        <div class="container">
                            <h2 class="display-4">{{ status.organization.name }}</h2>
                            <p class="lead">{{ $t('admin.partner_organizations.your_discount') }}: {{ status.organization.discount }}%</p>
                        </div>
                    </div>

                    <tabsComponent :table_data="data_for_tab" :selection_functions="false" />
                </div>

                <div class="col-sm-12 text-center py-5" v-else>
                    <p class="text-muted">{{ $t('admin.partner_organizations.not_a_member') }}</p>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import tabsComponent from '../items/data_table/TabsComponent.vue'
import breadcrumb from '../items/BreadcrumbComponent.vue'

export default {
    components: {
        breadcrumb,
        tabsComponent,
    },
    data() {
        return {
            status: null,
            data_for_tab: [],
            loading: true,
        }
    },
    mounted() {
        this.load()
    },
    methods: {
        load() {
            this.loading = true
            axios.get('/partner_organization/my_status')
                .then(response => {
                    this.status = response.data
                    if (this.status.is_member) {
                        this.data_for_tab = [{
                            id: 1,
                            table_name: this.$t('admin.partner_organizations.other_members_table'),
                            tab_data: {
                                data: this.status.other_members,
                                tab: {
                                    head: [
                                        this.$t('common.name'),
                                        this.$t('common.surname'),
                                        this.$t('common.email'),
                                        this.$t('common.phone_number'),
                                    ],
                                    body: [
                                        ['data', ['name']],
                                        ['data', ['surname']],
                                        ['data', ['email']],
                                        ['data', ['phone_number']],
                                    ],
                                    perm: [
                                        ['no'], ['no'], ['no'], ['no'],
                                    ],
                                }
                            }
                        }]
                    }
                })
                .catch(error => console.error(error))
                .finally(() => this.loading = false)
        },
    }
}
</script>
