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
                <div class="col-md-12" v-if="coefficients_loading">
                    <skeleton-loader
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
                    </skeleton-loader>
                </div>
                <div class="col-sm-12" v-else>
                    <tabsComponent
                        :table_data="data_for_tab"
                        :selection_functions="false"
                        @update="get_coefficients"

                        @show_coefficient_add_modal="show_coefficient_add_modal"
                        @show_coefficient_edit_modal="show_coefficient_edit_modal"
                        @del_coefficient="del_coefficient"
                    />
                </div>
            </div>
        </div>

        <coefficient_add_modal
            ref="coefficient_add_modal"
            @update="get_coefficients"
        />
        <coefficient_edit_modal
            ref="coefficient_edit_modal"
            @update="get_coefficients"
        />
    </div>
</template>

<script>
    import tabsComponent from '../../../items/data_table/TabsComponent.vue'
    import breadcrumb from '../../../items/BreadcrumbComponent.vue'

    import coefficient_add_modal from './Modals/AddCoefficientModal.vue'
    import coefficient_edit_modal from './Modals/EditCoefficientModal.vue'
    export default {
        components: {
            tabsComponent,
            breadcrumb,

            coefficient_add_modal,
            coefficient_edit_modal,
        },

        data() {
            return {
                data_for_tab: [],
                coefficients: [],
                coefficients_loading: false,
            }
        },

        mounted() {
            this.get_coefficients();
        },

        methods: {
            get_coefficients(){
                this.coefficients_loading = true;

                axios
                .get('/set_coefficient/get_all')
                .then(response => {
                    // Table shows the admin-entered description; for a known slug
                    // left without one, fall back to the built-in i18n text.
                    // Kept in its own field so the edit modal still gets the raw
                    // DB description, not the fallback.
                    this.coefficients = response.data.map(item => ({
                        ...item,
                        description_label: item.description
                            || (this.$te('admin.coefficients.descriptions.' + item.slug)
                                ? this.$t('admin.coefficients.descriptions.' + item.slug)
                                : ''),
                    }))

                    this.data_for_tab = []

                    this.data_for_tab.push({
                                            'id': 1,
                                            'table_name': this.$t('admin.coefficients.table'),
                                            'add_action': this.$can('add', 'coefficient') ? {
                                                'action': 'function',
                                                'link': 'show_coefficient_add_modal',
                                                'class': 'btn btn-primary'
                                            } : null,
                                            'tab_data': {
                                                'data': this.coefficients,
                                                'tab': {
                                                    'head': [
                                                        this.$t('common.id'),
                                                        this.$t('admin.coefficients.slug'),
                                                        this.$t('admin.coefficients.value'),
                                                        this.$t('admin.coefficients.description'),
                                                        this.$t('common.edit'),
                                                        this.$t('common.delete'),
                                                    ],
                                                    'body': [
                                                        ['data', ['id']],
                                                        ['data', ['slug']],
                                                        ['data', ['value']],
                                                        ['data', ['description_label']],
                                                        ['action_fun_id', 'show_coefficient_edit_modal', 'btn btn-primary', '<i aria-hidden="true" class="fa fa-pencil"></i>'],
                                                        ['action_fun_id', 'del_coefficient', 'btn btn-danger', '<i aria-hidden="true" class="fa fa-trash"></i>'],
                                                    ],
                                                    'perm': [
                                                        ['no'],
                                                        ['no'],
                                                        ['no'],
                                                        ['no'],
                                                        ['coefficient', 'edit'],
                                                        ['coefficient', 'del'],
                                                    ]
                                                }
                                            },
                                        });
                })
                .catch(
                    error => console.log(error)
                )
                .finally(() => {
                    this.coefficients_loading = false;
                });
            },

            del_coefficient(id){
                if(confirm(this.$t('admin.common.confirm_delete'))){
                    axios
                    .delete('/set_coefficient/del/' + id)
                    .then(() => {
                        this.get_coefficients()
                    })
                    .catch(error => console.log(error))
                }
            },

            show_coefficient_add_modal(){
                this.$refs.coefficient_add_modal.show_modal();
            },

            show_coefficient_edit_modal(id){
                const item = this.coefficients.find(c => c.id == id)
                if (item) {
                    this.$refs.coefficient_edit_modal.show_modal_with_data(item);
                }
            },
        }
    }
</script>
