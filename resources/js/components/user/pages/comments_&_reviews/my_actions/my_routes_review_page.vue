<template>
    <div class="row">
        <left-menu />
        <div class="col-md-12">
            <div class="row">
                <div class="col-md-12">
                    <breadcrumb />
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12">
                    <tabsComponent
                        :table_data="data_for_tab"
                        @update="load_data"
                        @show_review="show_review_modal"
                        @toggle_review="toggle_review"
                        @edit_review_modal="edit_review_modal"
                        @del_review="del_review"
                        @show_mtp_review="show_mtp_review_modal"
                        @toggle_mtp_review="toggle_mtp_review"
                        @edit_mtp_review_modal="edit_mtp_review_modal_fn"
                        @del_mtp_review="del_mtp_review"
                    />
                </div>
            </div>
        </div>

        <reviewEditModal ref="review_edit_modal" @restart="load_data" />
        <reviewShowModal ref="review_show_modal" />
        <mtpReviewShowModal ref="mtp_review_show_modal" />
        <mtpReviewEditModal ref="mtp_review_edit_modal" @restart="load_data" />
    </div>
</template>

<script>
import tabsComponent from "../../../items/data_table/TabsComponent.vue";
import breadcrumb from "../../../items/BreadcrumbComponent.vue";
import reviewEditModal from '../../../items/modal/review/ReviewEditModal.vue';
import reviewShowModal from '../../../items/modal/review/ReviewShowModal.vue';
import mtpReviewShowModal from '../../../items/modal/review/MtpReviewShowModal.vue';
import mtpReviewEditModal from '../../../items/modal/review/MtpReviewEditModal.vue';

export default {
    components: { tabsComponent, breadcrumb, reviewEditModal, reviewShowModal, mtpReviewShowModal, mtpReviewEditModal },
    data() {
        return { data_for_tab: [] };
    },
    mounted() {
        this.load_data();
    },
    methods: {
        load_data() {
            this.data_for_tab = [];
            this.load_route_reviews();
            this.load_mtp_reviews();
        },
        load_route_reviews() {
            axios.get('/get_route/get_route_review/get_user_review').then(response => {
                this.data_for_tab.push({
                    id: 1,
                    table_name: 'My climbing route reviews',
                    tab_data: {
                        data: (response.data || []).map(item => ({ ...item, id: item.review?.id, _row_class: item.review?.admin_hidden ? 'table-danger' : (item.review?.published == 0 ? 'table-warning' : '') })),
                        tab: {
                            head: ['ID', 'Climbing route', 'Stars', 'Read more', 'Hide', 'Edit', 'Delete'],
                            body: [
                                ['data', ['review', 'id']],
                                ['data', ['route', 'name']],
                                ['stars', ['review', 'stars']],
                                ['action_fun_id', 'show_review', 'btn btn-info btn-sm', '<i class="fa fa-eye"></i>', ['review', 'id']],
                                ['action_fun_id', 'toggle_review', 'btn btn-warning btn-sm', '<i class="fa fa-eye-slash"></i>', ['review', 'id']],
                                ['action_fun_id', 'edit_review_modal', 'btn btn-primary btn-sm', '<i class="fa fa-pencil"></i>', ['review', 'id']],
                                ['action_fun_id', 'del_review', 'btn btn-danger btn-sm', '<i class="fa fa-trash"></i>', ['review', 'id']],
                            ],
                            perm: [['no'], ['no'], ['no'], ['no'], ['no'], ['no'], ['no']],
                        },
                    },
                });
            }).catch(e => console.log(e));
        },
        load_mtp_reviews() {
            axios.get('/get_mtp_review/get_user_mtp_reviews').then(response => {
                this.data_for_tab.push({
                    id: 2,
                    table_name: 'My Multi-Pitch reviews',
                    tab_data: {
                        data: (response.data || []).map(item => ({ ...item, id: item.review?.id, _row_class: item.review?.admin_hidden ? 'table-danger' : (item.review?.published == 0 ? 'table-warning' : '') })),
                        tab: {
                            head: ['ID', 'Multi-Pitch', 'Stars', 'Read more', 'Hide', 'Edit', 'Delete'],
                            body: [
                                ['data', ['review', 'id']],
                                ['data', ['mtp', 'name']],
                                ['stars', ['review', 'stars']],
                                ['action_fun_id', 'show_mtp_review', 'btn btn-info btn-sm', '<i class="fa fa-eye"></i>', ['review', 'id']],
                                ['action_fun_id', 'toggle_mtp_review', 'btn btn-warning btn-sm', '<i class="fa fa-eye-slash"></i>', ['review', 'id']],
                                ['action_fun_id', 'edit_mtp_review_modal', 'btn btn-primary btn-sm', '<i class="fa fa-pencil"></i>', ['review', 'id']],
                                ['action_fun_id', 'del_mtp_review', 'btn btn-danger btn-sm', '<i class="fa fa-trash"></i>', ['review', 'id']],
                            ],
                            perm: [['no'], ['no'], ['no'], ['no'], ['no'], ['no'], ['no']],
                        },
                    },
                });
            }).catch(e => console.log(e));
        },
        find_row(id, tabId) {
            const tab = this.data_for_tab.find(t => t.id === tabId);
            if (!tab) return null;
            return tab.tab_data.data.find(r => r.id == id) || null;
        },
        toggle_review(id) {
            const row = this.find_row(id, 1);
            if (!row || !row.review) return;
            const r = row.review;
            if (r.published == 1) {
                if (confirm('Hide this review?')) {
                    axios.post('/set_route/set_route_review/user_hide_review/' + id)
                        .then(() => this.load_data()).catch(e => console.log(e));
                }
            } else if (r.admin_hidden) {
                this.$bus.$emit('toast', { type: 'warning', title: 'Cannot show', message: 'This review was hidden by an admin.' });
            } else {
                if (confirm('Make this review visible again?')) {
                    axios.post('/set_route/set_route_review/user_show_review/' + id)
                        .then(() => this.load_data()).catch(e => console.log(e));
                }
            }
        },
        toggle_mtp_review(id) {
            const row = this.find_row(id, 2);
            if (!row || !row.review) return;
            const r = row.review;
            if (r.published == 1) {
                if (confirm('Hide this review?')) {
                    axios.post('/set_mtp_review/user_hide_mtp_review/' + id)
                        .then(() => this.load_data()).catch(e => console.log(e));
                }
            } else if (r.admin_hidden) {
                this.$bus.$emit('toast', { type: 'warning', title: 'Cannot show', message: 'This review was hidden by an admin.' });
            } else {
                if (confirm('Make this review visible again?')) {
                    axios.post('/set_mtp_review/user_show_mtp_review/' + id)
                        .then(() => this.load_data()).catch(e => console.log(e));
                }
            }
        },
        del_review(id) {
            const row = this.find_row(id, 1);
            if (row?.review?.admin_hidden) {
                this.$bus.$emit('toast', { type: 'warning', title: 'Action blocked', message: 'This review was hidden by an admin and cannot be modified.' });
                return;
            }
            if (confirm('Are you sure you want to delete this review?')) {
                axios.delete('/set_route/set_route_review/del_route_review/' + id)
                    .then(() => this.load_data()).catch(e => console.log(e));
            }
        },
        del_mtp_review(id) {
            const row = this.find_row(id, 2);
            if (row?.review?.admin_hidden) {
                this.$bus.$emit('toast', { type: 'warning', title: 'Action blocked', message: 'This review was hidden by an admin and cannot be modified.' });
                return;
            }
            if (confirm('Are you sure you want to delete this review?')) {
                axios.delete('/set_mtp_review/del_mtp_review/' + id)
                    .then(() => this.load_data()).catch(e => console.log(e));
            }
        },
        edit_review_modal(id) {
            const row = this.find_row(id, 1);
            if (row?.review?.admin_hidden) {
                this.$bus.$emit('toast', { type: 'warning', title: 'Action blocked', message: 'This review was hidden by an admin and cannot be modified.' });
                return;
            }
            this.$refs.review_edit_modal.show_modal(id);
        },
        show_review_modal(id) {
            const row = this.find_row(id, 1);
            if (row) this.$refs.review_show_modal.show_modal(row);
        },
        show_mtp_review_modal(id) {
            const row = this.find_row(id, 2);
            if (row) this.$refs.mtp_review_show_modal.show_modal(row);
        },
        edit_mtp_review_modal_fn(id) {
            const row = this.find_row(id, 2);
            if (row?.review?.admin_hidden) {
                this.$bus.$emit('toast', { type: 'warning', title: 'Action blocked', message: 'This review was hidden by an admin and cannot be modified.' });
                return;
            }
            this.$refs.mtp_review_edit_modal.show_modal(id);
        },
    },
};
</script>
