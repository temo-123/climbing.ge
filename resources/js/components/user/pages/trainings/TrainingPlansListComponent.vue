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
                        :table_data="this.data_for_tab"
                        :loading="is_loading"
                        @update="get_plans_data"
                        @del_plan="del_plan"
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
    import tabsComponent from '../../items/data_table/TabsComponent.vue'
    import breadcrumb from '../../items/BreadcrumbComponent.vue'

    export default {
        components: {
            tabsComponent,
            breadcrumb,
        },

        data() {
            return {
                data_for_tab: [],
                is_loading: false,
            }
        },

        mounted() {
            this.get_plans_data();
        },

        methods: {
            get_plans_data() {
                this.is_loading = true;
                axios
                .get("/set_training_plan/get_all_plans")
                .then(response => {
                    this.data_for_tab = [{
                        id: 1,
                        table_name: this.$t('admin.training.plans_table'),
                        has_published: true,
                        add_action: {
                            action: 'route',
                            link: 'trainingPlanAdd',
                            class: 'btn btn-primary'
                        },
                        tab_data: {
                            data: response.data,
                            tab: {
                                head: [
                                    this.$t('common.id'),
                                    this.$t('common.name'),
                                    this.$t('admin.training.col_level'),
                                    this.$t('admin.training.col_days_per_week'),
                                    this.$t('admin.common.public'),
                                    this.$t('common.edit'),
                                    this.$t('common.delete'),
                                ],
                                body: [
                                    ['data', ['id']],
                                    ['data', ['name']],
                                    ['data', ['level']],
                                    ['data', ['days_per_week']],
                                    ['data', ['is_published'], 'bool'],
                                    ['action_router', 'trainingPlanEdit', 'btn btn-primary', '<i aria-hidden="true" class="fa fa-pencil"></i>'],
                                    ['action_fun_id', 'del_plan', 'btn btn-danger', '<i aria-hidden="true" class="fa fa-trash"></i>'],
                                ],
                                perm: [
                                    ['no'],
                                    ['no'],
                                    ['no'],
                                    ['no'],
                                    ['no'],
                                    ['training_plan', 'edit'],
                                    ['training_plan', 'del'],
                                ]
                            }
                        }
                    }];
                })
                .catch(error => console.log(error))
                .finally(() => this.is_loading = false);
            },
            del_plan(id) {
                if (confirm(this.$t('admin.training.confirm_delete_plan'))) {
                    axios
                    .delete('/set_training_plan/del_plan/' + id)
                    .then(() => {
                        this.get_plans_data();
                    })
                    .catch(error => console.log(error))
                }
            },
            delete_selected(ids) {
                axios
                .post('/set_training_plan/bulk_delete', { ids })
                .then(() => this.get_plans_data())
                .catch(error => console.log(error))
            },
            publish_selected(ids) {
                axios
                .post('/set_training_plan/bulk_publish', { ids })
                .then(() => this.get_plans_data())
                .catch(error => console.log(error))
            },
            unpublish_selected(ids) {
                axios
                .post('/set_training_plan/bulk_unpublish', { ids })
                .then(() => this.get_plans_data())
                .catch(error => console.log(error))
            },
        }
    }
</script>
