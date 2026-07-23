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
                        @update="get_all_tours_data"

                        @del_category="del_category"
                        @show_edit_category_modal="show_edit_category_modal"
                        @show_add_category_modal="show_add_category_modal"

                        @show_user_change_modal="show_user_change_modal"
                        @del_tour="del_tour"

                        @filtr_tour_category="filtr_tour_category"
                    />
                </div>
            </div>
        </div>

        <editTourCategoryModal
            ref="tour_category_edit_modal"
            @restart="get_all_tours_data"
        />
        <addTourCategoryModal
            ref="tour_category_add_modal"
            @restart="get_all_tours_data"
        />

        <tour_guide_modal
            ref="tour_guide_modal"
            @updated="get_all_tours_data"
        />
    </div>
</template>

<script>
    import tabsComponent  from '../../../../items/data_table/TabsComponent.vue'
    import breadcrumb from '../../../../items/BreadcrumbComponent.vue'

    import editTourCategoryModal from "../../../../items/modal/tab_modals/edit/EditTourCategoryModal"
    import addTourCategoryModal from "../../../../items/modal/tab_modals/add/AddTourCategoryModal"
    import tour_guide_modal from '../../../../items/modal/tab_modals/TourGuideRelationModalComponent.vue'

    export default {
        components:{
            editTourCategoryModal,
            addTourCategoryModal,
            tabsComponent,
            breadcrumb,
            tour_guide_modal,
        },
        data() {
            return {
                data_for_tab: [],
                tours: [],
                categories: [],
            }
        },
        mounted() {
            this.get_all_tours_data();
        },
        methods: {
            get_all_tours_data() {
                this.data_for_tab = []
                Promise.all([
                    this.loadTours(),
                    this.loadCategories()
                ]).then(() => {
                    this.buildToursTab()
                    this.buildCategoriesTab()
                }).catch(error => console.log(error));
            },

            loadTours() {
                return axios.get("/get_tour/get_all_tours/")
                    .then(response => {
                        this.tours = response.data
                    });
            },

            loadCategories() {
                return axios.get("/get_tour/get_category/get_all_categories/")
                    .then(response => {
                        this.categories = response.data
                    });
            },

            buildToursTab() {
                this.data_for_tab.push({
                    'id': 1,
                    'table_name': this.$t('admin.tour.tours_table'),
                    list_page: process.env.MIX_APP_SSH
                        ? (process.env.MIX_APP_SSH || '').replace(/\/$/, '') + '/' + (process.env.MIX_SHOP_URL || '').replace(/^\/|\/$/g, '') + '/tours'
                        : window.location.origin + '/tours',
                    'add_action': {
                        'action': 'route',
                        'link': 'tourAdd',
                        'class': 'btn btn-primary'
                    },
                    'filter_data': {
                        'title': this.$t('admin.tour.filter_by_category'),
                        'data': this.categories,
                        'action_fun_id': 'filtr_tour_category',
                        'array_key': 'us_name'
                    },
                    'tab_data': {
                        'data': this.tours,
                        'tab': {
                            'head': [
                                this.$t('common.id'),
                                this.$t('admin.tour.url_title_col'),
                                this.$t('admin.common.public'),
                                this.$t('admin.tour.guides_col'),
                                this.$t('admin.tour.edit_guides_col'),
                                this.$t('common.edit'),
                                this.$t('common.delete'),
                            ],
                            'body': [
                                ['data', ['global_data', 'id']],
                                ['data', ['global_data', 'url_title']],
                                ['data', ['global_data', 'published'], 'bool'],
                                ['data', [['user', 'name'], ['user', 'surname']]],
                                ['action_fun_id', 'show_user_change_modal', 'btn btn-secondary', '<i class="fa fa-users" aria-hidden="true"></i>', ['global_data', 'id']],
                                ['action_router', 'tourEdit', 'btn btn-primary', '<i aria-hidden="true" class="fa fa-pencil"></i>', ['global_data', 'id']],
                                ['action_fun_id', 'del_tour', 'btn btn-danger', '<i aria-hidden="true" class="fa fa-trash"></i>', ['global_data', 'id']],
                            ],
                            'perm': [
                                ['no'],
                                ['no'],
                                ['no'],
                                ['no'],
                                ['tour', 'edit'],
                                ['tour', 'edit'],
                                ['tour', 'del'],
                            ]
                        }
                    },
                });
            },

            buildCategoriesTab() {
                this.data_for_tab.push({
                    'id': 2,
                    'table_name': this.$t('admin.tour.tour_categories_table'),
                    'add_action': {
                        'action': 'fun',
                        'link': 'show_add_category_modal',
                        'class': 'btn btn-primary'
                    },
                    'tab_data': {
                        'data': this.categories,
                        'tab': {
                            'head': [this.$t('common.id'), this.$t('common.name'), this.$t('common.edit'), this.$t('common.delete')],
                            'body': [
                                ['data', ['id']],
                                ['data', ['us_name']],
                                ['action_fun_id', 'show_edit_category_modal', 'btn btn-primary', '<i aria-hidden="true" class="fa fa-pencil"></i>'],
                                ['action_fun_id', 'del_category', 'btn btn-danger', '<i aria-hidden="true" class="fa fa-trash"></i>'],
                            ],
                            'perm': [
                                ['no'],
                                ['no'],
                                ['tour', 'edit'],
                                ['tour', 'del'],
                            ]
                        }
                    },
                });
            },

            filtr_tour_category(category_id) {
                if (category_id == 0 || category_id == 'all' || category_id == 'All' || category_id == null) {
                    if (this.data_for_tab[0]) {
                        this.data_for_tab[0].tab_data.data = this.tours;
                    }
                } else {
                    if (this.data_for_tab[0]) {
                        this.data_for_tab[0].tab_data.data = this.tours.filter(item => item && item.global_data && item.global_data.category_id == category_id);
                    }
                }
            },

            show_edit_category_modal(category_id) {
                this.$refs.tour_category_edit_modal.open_modal(category_id)
            },
            show_add_category_modal() {
                this.$refs.tour_category_add_modal.open_modal()
            },
            del_category(id) {
                if (confirm(this.$t('admin.tour.confirm_delete_category'))) {
                    axios.delete('/set_tour/set_category/del_category/' + id)
                        .then(() => this.get_all_tours_data())
                        .catch(error => console.log(error))
                }
            },
            del_tour(id) {
                if (confirm(this.$t('admin.tour.confirm_delete_tour'))) {
                    axios.delete('/set_tour/del_tour/' + id)
                        .then(() => this.get_all_tours_data())
                        .catch(error => console.log(error))
                }
            },
            show_user_change_modal(tour_id) {
                this.$refs.tour_guide_modal.show_modal(tour_id)
            },
        }
    }
</script>
