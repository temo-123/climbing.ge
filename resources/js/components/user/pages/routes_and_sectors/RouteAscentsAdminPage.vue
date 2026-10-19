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
                <div class="col-sm-12">
                    <tabsComponent
                        :table_data="data_for_tab"
                        @update="load_ascents"
                        @del_ascent="del_ascent"
                        @delete_selected="bulk_delete_ascents"
                    />
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import tabsComponent from '../../items/data_table/TabsComponent.vue'
    import breadcrumb    from '../../items/BreadcrumbComponent.vue'

    export default {
        components: { tabsComponent, breadcrumb },

        data() {
            return {
                data_for_tab: [],
            }
        },

        mounted() {
            this.load_ascents()
        },

        methods: {
            load_ascents() {
                axios.get('/set_route_ascent/get_all_route_ascents_admin')
                    .then(response => {
                        this.data_for_tab = [{
                            id: 1,
                            table_name: this.$t('user.route_ascents.admin_table_name'),
                            tab_data: {
                                data: response.data.map(ascent => ({
                                    ...ascent,
                                    style_name: this.$t('guide.route_ascent.style.' + ascent.ascent_style),
                                })),
                                tab: {
                                    head: [
                                        this.$t('common.id'),
                                        this.$t('user.route_ascents.col_route'),
                                        this.$t('user.route_ascents.col_grade'),
                                        this.$t('common.name'),
                                        this.$t('common.email'),
                                        this.$t('common.date'),
                                        this.$t('user.route_ascents.col_style'),
                                        this.$t('user.route_ascents.col_suggested_grade'),
                                        this.$t('user.route_ascents.col_comment'),
                                        this.$t('common.delete'),
                                    ],
                                    body: [
                                        ['data', ['id']],
                                        ['data', ['route_name']],
                                        ['data', ['route_grade']],
                                        ['data', ['user_name']],
                                        ['data', ['user_email']],
                                        ['data', ['ascent_date']],
                                        ['data', ['style_name']],
                                        ['data', ['suggested_grade']],
                                        ['data', ['comment']],
                                        ['action_fun_id', 'del_ascent', 'btn btn-danger btn-sm', '<i aria-hidden="true" class="fa fa-trash"></i>', ['id']],
                                    ],
                                    perm: [
                                        ['no'], ['no'], ['no'], ['no'], ['no'], ['no'], ['no'], ['no'], ['no'],
                                        ['route', 'del'],
                                    ],
                                },
                            },
                        }]
                    })
                    .catch(error => console.log(error))
            },

            del_ascent(id) {
                if (!confirm(this.$t('user.ascents.confirm_delete'))) return
                axios.delete('/set_route_ascent/del_route_ascent_admin/' + id)
                    .then(() => this.load_ascents())
                    .catch(error => console.log(error))
            },

            bulk_delete_ascents(ids) {
                Promise.all(ids.map(id => axios.delete('/set_route_ascent/del_route_ascent_admin/' + id)))
                    .finally(() => this.load_ascents())
            },
        },
    }
</script>
