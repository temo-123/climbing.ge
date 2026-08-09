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
                        @update="get_trainings_data"
                        @del_training="del_training"
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
            this.get_trainings_data();
        },

        methods: {
            get_trainings_data() {
                this.is_loading = true;
                axios
                .get("/set_training/get_all_trainings")
                .then(response => {
                    this.data_for_tab = [{
                        id: 1,
                        table_name: this.$t('admin.training.trainings_table'),
                        has_published: true,
                        add_action: {
                            action: 'route',
                            link: 'trainingAdd',
                            class: 'btn btn-primary'
                        },
                        tab_data: {
                            data: response.data,
                            tab: {
                                head: [
                                    this.$t('common.id'),
                                    this.$t('common.name'),
                                    this.$t('admin.training.col_type'),
                                    this.$t('admin.training.col_difficulty'),
                                    this.$t('admin.common.public'),
                                    this.$t('common.edit'),
                                    this.$t('common.delete'),
                                ],
                                body: [
                                    ['data', ['id']],
                                    ['data', ['name']],
                                    ['data', ['type']],
                                    ['data', ['difficulty']],
                                    ['data', ['is_published'], 'bool'],
                                    ['action_router', 'trainingEdit', 'btn btn-primary', '<i aria-hidden="true" class="fa fa-pencil"></i>'],
                                    ['action_fun_id', 'del_training', 'btn btn-danger', '<i aria-hidden="true" class="fa fa-trash"></i>'],
                                ],
                                perm: [
                                    ['no'],
                                    ['no'],
                                    ['no'],
                                    ['no'],
                                    ['no'],
                                    ['training', 'edit'],
                                    ['training', 'del'],
                                ]
                            }
                        }
                    }];
                })
                .catch(error => console.log(error))
                .finally(() => this.is_loading = false);
            },
            del_training(id) {
                if (confirm(this.$t('admin.training.confirm_delete_training'))) {
                    axios
                    .delete('/set_training/del_training/' + id)
                    .then(() => {
                        this.get_trainings_data();
                    })
                    .catch(error => {
                        if (error.response && error.response.status == 422) {
                            alert(error.response.data.error);
                        } else {
                            console.log(error);
                        }
                    })
                }
            },
            delete_selected(ids) {
                axios
                .post('/set_training/bulk_delete', { ids })
                .then(() => this.get_trainings_data())
                .catch(error => console.log(error))
            },
            publish_selected(ids) {
                axios
                .post('/set_training/bulk_publish', { ids })
                .then(() => this.get_trainings_data())
                .catch(error => console.log(error))
            },
            unpublish_selected(ids) {
                axios
                .post('/set_training/bulk_unpublish', { ids })
                .then(() => this.get_trainings_data())
                .catch(error => console.log(error))
            },
        }
    }
</script>
