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
                        :table_data="this.data_for_tab"
                        @update="get_all_guide_comments_data"

                        @show_comment_modal="on_show_comment"
                        @hide_comment="on_admin_hide_comment"

                        @show_feedback="on_show_feedback"
                        @show_feedback_hide="on_admin_hide_feedback"
                    />
                </div>
            </div>
        </div>

        <comment_show_modal ref="show_comment_modal" @restart="get_all_guide_comments_data" />
        <adminHideModal ref="admin_hide_modal" @restart="get_all_guide_comments_data" />
        <feedbackShowModal ref="feedback_show_modal" />
    </div>
</template>

<script>
import tabsComponent from "../../../items/data_table/TabsComponent.vue";
import breadcrumb from "../../../items/BreadcrumbComponent.vue";

import comment_show_modal from "../../../items/modal/comments/CommentShowModal.vue";
import adminHideModal from '../../../items/modal/AdminHideModal.vue';
import feedbackShowModal from '../../../items/modal/feedback/FeedbackShowModal.vue';

export default {
    components: {
        comment_show_modal,
        adminHideModal,
        feedbackShowModal,
        tabsComponent,
        breadcrumb,
    },
    data() {
        return {
            data_for_tab: [],
        };
    },
    mounted() {
        this.get_all_guide_comments_data();
    },
    methods: {
        get_all_guide_comments_data: function () {
            this.data_for_tab = [];
            axios
                .get("get_article/get_guide_comment/get_all_comments")
                .then((response) => {
                    this.data_for_tab.push({
                        id: 1,
                        table_name: this.$t('admin.comments.guidebook_comments_table'),
                        tab_data: {
                            data: (response.data || []).map(item => ({ ...item, id: item.comment?.id, _row_class: item.comment?.admin_hidden ? 'table-danger' : (item.comment?.published == 0 ? 'table-warning' : '') })),
                            tab: {
                                head: [this.$t('common.id'), this.$t('common.name'), this.$t('common.email'), this.$t('admin.common.published'), this.$t('admin.comments.col_article'), this.$t('admin.comments.col_read_more'), this.$t('admin.comments.col_hide')],
                                body: [
                                    ["data", ["comment", "id"]],
                                    ["data", [["comment", "name"], ["comment", "surname"]]],
                                    ["data", ["comment", "email"]],
                                    ["data", ["comment", "published"], "bool"],
                                    ["data", ["global_article", "url_title"]],
                                    ["action_fun_id", "show_comment_modal", "btn btn-info btn-sm", '<i class="fa fa-eye" aria-hidden="true"></i>', ["comment", "id"]],
                                    ["action_fun_id", "hide_comment", "btn btn-warning btn-sm", '<i class="fa fa-eye-slash" aria-hidden="true"></i>', ["comment", "id"]],
                                ],
                                perm: [["no"], ["no"], ["no"], ["no"], ["no"], ["no"], ["no"]],
                            },
                        },
                    });
                    this.get_all_shop_feedbacks_data();
                })
                .catch((error) => console.log(error));
        },
        get_all_shop_feedbacks_data: function () {
            axios
                .get("set_product/set_product_feedback/get_all_feedbacks")
                .then((response) => {
                    this.data_for_tab.push({
                        id: 2,
                        table_name: this.$t('admin.comments.product_feedbacks_table'),
                        tab_data: {
                            data: (response.data || []).map(item => ({ ...item, id: item.feedback?.id, _row_class: item.feedback?.admin_hidden ? 'table-danger' : (item.feedback?.published == 0 ? 'table-warning' : '') })),
                            tab: {
                                head: [this.$t('common.id'), this.$t('admin.common.published'), this.$t('admin.comments.col_product'), this.$t('admin.comments.col_stars'), this.$t('admin.comments.col_commenter'), this.$t('common.email'), this.$t('admin.comments.col_read_more'), this.$t('admin.comments.col_hide')],
                                body: [
                                    ["data", ["feedback", "id"]],
                                    ["data", ["feedback", "published"], "bool"],
                                    ["data", ["locale_product", "title"]],
                                    ["stars", ["feedback", "stars"]],
                                    ["data", [["feedback", "name"], ["feedback", "surname"]]],
                                    ["data", ["feedback", "email"]],
                                    ["action_fun_id", "show_feedback", "btn btn-info btn-sm", '<i class="fa fa-eye" aria-hidden="true"></i>', ["feedback", "id"]],
                                    ["action_fun_id", "show_feedback_hide", "btn btn-warning btn-sm", '<i class="fa fa-eye-slash" aria-hidden="true"></i>', ["feedback", "id"]],
                                ],
                                perm: [["no"], ["no"], ["no"], ["no"], ["no"], ["no"], ["no"], ["no"]],
                            },
                        },
                    });
                })
                .catch((error) => console.log(error));
        },
        find_feedback_row(id) {
            const tab = this.data_for_tab.find(t => t.id === 2);
            if (!tab) return null;
            return tab.tab_data.data.find(r => r.id == id) || null;
        },
        on_show_comment(id) {
            axios
                .get("set_article/set_guide_comment/get_actyve_comment/" + id)
                .then((response) => {
                    this.$refs.show_comment_modal.show_modal(response.data);
                })
                .catch((error) => console.log(error));
        },
        on_admin_hide_comment(id) {
            axios
                .get("set_article/set_guide_comment/get_actyve_comment/" + id)
                .then((response) => {
                    this.$refs.admin_hide_modal.show_modal(
                        'set_article/set_guide_comment/admin_hide_comment/',
                        response.data
                    );
                })
                .catch((error) => console.log(error));
        },
        on_show_feedback(id) {
            const row = this.find_feedback_row(id);
            if (row) this.$refs.feedback_show_modal.show_modal(row);
        },
        on_admin_hide_feedback(id) {
            axios
                .get('set_product/set_product_feedback/get_actyve_feedback/' + id)
                .then(response => {
                    this.$refs.admin_hide_modal.show_modal(
                        'set_product/set_product_feedback/admin_hide_feedback/',
                        response.data
                    );
                })
                .catch(error => console.log(error));
        },
        go_to_article_page(url_title, category) {
            window.open(this.MIX_APP_SSH + this.MIX_SITE_URL + "/" + category + "/" + url_title);
        },
        go_to_product_page(url_title) {
            window.open(this.MIX_APP_SSH + this.MIX_SHOP_URL + '/product/' + url_title);
        },
    },
};
</script>
