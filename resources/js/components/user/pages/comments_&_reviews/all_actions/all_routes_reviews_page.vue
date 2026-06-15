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
                        @hide_review="on_hide_review"
                        @show_mtp_review="show_mtp_review_modal"
                        @hide_mtp_review="on_hide_mtp_review"
                    />
                </div>
            </div>
        </div>

        <reviewShowModal ref="review_show_modal" />
        <mtpReviewShowModal ref="mtp_review_show_modal" />
        <adminHideModal ref="admin_hide_modal" @restart="load_data" />
    </div>
</template>

<script>
import tabsComponent from "../../../items/data_table/TabsComponent.vue";
import breadcrumb from "../../../items/BreadcrumbComponent.vue";
import reviewShowModal from '../../../items/modal/review/ReviewShowModal.vue';
import mtpReviewShowModal from '../../../items/modal/review/MtpReviewShowModal.vue';
import adminHideModal from '../../../items/modal/AdminHideModal.vue';

export default {
    components: { tabsComponent, breadcrumb, reviewShowModal, mtpReviewShowModal, adminHideModal },
    data() {
        return { data_for_tab: [] };
    },
    mounted() {
        this.load_data();
    },
    methods: {
        load_data() {
            this.data_for_tab = [];
            this.load_all_route_reviews();
            this.load_all_mtp_reviews();
        },
        load_all_route_reviews() {
            axios.get('/get_route/get_route_review/get_all_review').then(response => {
                this.data_for_tab.push({
                    id: 1,
                    table_name: 'All climbing route reviews',
                    tab_data: {
                        data: (response.data || []).map(item => ({ ...item, id: item.review?.id, _row_class: item.review?.admin_hidden ? 'table-danger' : (item.review?.published == 0 ? 'table-warning' : '') })),
                        tab: {
                            head: ['ID', 'Name', 'Email', 'Stars', 'Climbing route', 'Read more', 'Hide'],
                            body: [
                                ['data', ['review', 'id']],
                                ['data', [['user', 'name'], ['user', 'surname']]],
                                ['data', ['user', 'email']],
                                ['stars', ['review', 'stars']],
                                ['data', ['route', 'name']],
                                ['action_fun_id', 'show_review', 'btn btn-info btn-sm', '<i class="fa fa-eye"></i>', ['review', 'id']],
                                ['action_fun_id', 'hide_review', 'btn btn-warning btn-sm', '<i class="fa fa-eye-slash" aria-hidden="true"></i>', ['review', 'id']],
                            ],
                            perm: [['no'], ['no'], ['no'], ['no'], ['no'], ['no'], ['no']],
                        },
                    },
                });
            }).catch(e => console.log(e));
        },
        load_all_mtp_reviews() {
            axios.get('/set_mtp_review/get_all_mtp_reviews_admin').then(response => {
                this.data_for_tab.push({
                    id: 2,
                    table_name: 'All Multi-Pitch reviews',
                    tab_data: {
                        data: (response.data || []).map(item => ({ ...item, id: item.review?.id, _row_class: item.review?.admin_hidden ? 'table-danger' : (item.review?.published == 0 ? 'table-warning' : '') })),
                        tab: {
                            head: ['ID', 'Name', 'Email', 'Multi-Pitch', 'Stars', 'Read more', 'Hide'],
                            body: [
                                ['data', ['review', 'id']],
                                ['data', [['user', 'name'], ['user', 'surname']]],
                                ['data', ['user', 'email']],
                                ['data', ['mtp', 'name']],
                                ['stars', ['review', 'stars']],
                                ['action_fun_id', 'show_mtp_review', 'btn btn-info btn-sm', '<i class="fa fa-eye"></i>', ['review', 'id']],
                                ['action_fun_id', 'hide_mtp_review', 'btn btn-warning btn-sm', '<i class="fa fa-eye-slash" aria-hidden="true"></i>', ['review', 'id']],
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
        on_hide_review(id) {
            this.$refs.admin_hide_modal.show_modal(
                '/set_route/set_route_review/hide_route_review/',
                { id }
            );
        },
        on_hide_mtp_review(id) {
            this.$refs.admin_hide_modal.show_modal(
                '/set_mtp_review/hide_mtp_review/',
                { id }
            );
        },
        show_review_modal(id) {
            const row = this.find_row(id, 1);
            if (row) this.$refs.review_show_modal.show_modal(row);
        },
        show_mtp_review_modal(id) {
            const row = this.find_row(id, 2);
            if (row) this.$refs.mtp_review_show_modal.show_modal(row);
        },
    },
};
</script>
