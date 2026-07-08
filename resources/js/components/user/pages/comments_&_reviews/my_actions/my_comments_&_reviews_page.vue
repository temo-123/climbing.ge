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
                        @update="get_my_guide_comments_data"

                        @show_comment_modal="on_show_comment"
                        @toggle_comment="on_toggle_comment"
                        @edit_comment_modal="on_edit_comment"
                        @del_comment="on_del_comment"

                        @show_feedback="on_show_feedback"
                        @toggle_feedback="on_toggle_feedback"
                        @edit_feedback_modal="on_edit_feedback_modal"
                        @del_feedback="on_del_feedback"
                    />
                </div>
            </div>
        </div>

        <comment_show_modal ref="show_comment_modal" @restart="get_my_guide_comments_data" />
        <userCommentEditModal ref="comment_edit_modal" @restart="get_my_guide_comments_data" />
        <feedbackShowModal ref="feedback_show_modal" />
        <feedbackEditModal ref="feedback_edit_modal" @restart="get_my_guide_comments_data" />
    </div>
</template>

<script>
import tabsComponent from "../../../items/data_table/TabsComponent.vue";
import breadcrumb from "../../../items/BreadcrumbComponent.vue";

import comment_show_modal from "../../../items/modal/comments/CommentShowModal.vue";
import userCommentEditModal from "../../../items/modal/comments/UserCommentEditModal.vue";
import feedbackShowModal from '../../../items/modal/feedback/FeedbackShowModal.vue';
import feedbackEditModal from '../../../items/modal/feedback/FeedbackEditModal.vue';

export default {
    components: {
        comment_show_modal,
        userCommentEditModal,
        feedbackShowModal,
        feedbackEditModal,
        tabsComponent,
        breadcrumb,
    },
    data() {
        return {
            data_for_tab: [],
        };
    },
    mounted() {
        this.get_my_guide_comments_data();
    },
    methods: {
        get_my_guide_comments_data() {
            this.data_for_tab = [];
            axios.get("/get_article/get_guide_comment/get_user_comments")
                .then((response) => {
                    this.data_for_tab.push({
                        id: 1,
                        table_name: this.$t('user.my_comments.tab_guide_comments'),
                        tab_data: {
                            data: (response.data || []).map(item => ({ ...item, _row_class: item.comment?.admin_hidden ? 'table-danger' : (item.comment?.published == 0 ? 'table-warning' : '') })),
                            tab: {
                                head: [
                                    this.$t('common.id'),
                                    this.$t('common.name'),
                                    this.$t('common.email'),
                                    this.$t('user.my_comments.col_published'),
                                    this.$t('user.my_comments.col_article'),
                                    this.$t('user.my_comments.col_read_more'),
                                    this.$t('user.my_comments.col_hide'),
                                    this.$t('common.edit'),
                                    this.$t('common.delete'),
                                ],
                                body: [
                                    ["data", ["comment", "id"]],
                                    ["data", [["comment", "name"], ["comment", "surname"]]],
                                    ["data", ["comment", "email"]],
                                    ["data", ["comment", "published"], "bool"],
                                    ["data", ["global_article", "url_title"]],
                                    ["action_fun_id", "show_comment_modal", "btn btn-info btn-sm", '<i class="fa fa-eye"></i>', ["comment", "id"]],
                                    ["action_fun_id", "toggle_comment", "btn btn-warning btn-sm", '<i class="fa fa-eye-slash"></i>', ["comment", "id"]],
                                    ["action_fun_id", "edit_comment_modal", "btn btn-primary btn-sm", '<i class="fa fa-pencil"></i>', ["comment", "id"]],
                                    ["action_fun_id", "del_comment", "btn btn-danger btn-sm", '<i class="fa fa-trash"></i>', ["comment", "id"]],
                                ],
                                perm: [["no"], ["no"], ["no"], ["no"], ["no"], ["no"], ["no"], ["no"], ["no"]],
                            },
                        },
                    });
                    this.get_my_products_feedbacks_data();
                })
                .catch((error) => console.log(error));
        },
        get_my_products_feedbacks_data() {
            axios.get("/set_product/set_product_feedback/get_user_feedbacks")
                .then((response) => {
                    this.data_for_tab.push({
                        id: 2,
                        table_name: this.$t('user.my_comments.tab_product_reviews'),
                        tab_data: {
                            data: (response.data || []).map(item => ({ ...item, id: item.feedback?.id, _row_class: item.feedback?.admin_hidden ? 'table-danger' : (item.feedback?.published == 0 ? 'table-warning' : '') })),
                            tab: {
                                head: [
                                    this.$t('common.id'),
                                    this.$t('user.my_comments.col_published'),
                                    this.$t('common.product'),
                                    this.$t('user.my_comments.col_stars'),
                                    this.$t('user.my_comments.col_reviewer'),
                                    this.$t('common.email'),
                                    this.$t('user.my_comments.col_read_more'),
                                    this.$t('user.my_comments.col_hide'),
                                    this.$t('common.edit'),
                                    this.$t('common.delete'),
                                ],
                                body: [
                                    ["data", ["feedback", "id"]],
                                    ["data", ["feedback", "published"], "bool"],
                                    ["data", ["locale_product", "title"]],
                                    ["stars", ["feedback", "stars"]],
                                    ["data", [["feedback", "name"], ["feedback", "surname"]]],
                                    ["data", ["feedback", "email"]],
                                    ["action_fun_id", "show_feedback", "btn btn-info btn-sm", '<i class="fa fa-eye"></i>', ["feedback", "id"]],
                                    ["action_fun_id", "toggle_feedback", "btn btn-warning btn-sm", '<i class="fa fa-eye-slash"></i>', ["feedback", "id"]],
                                    ["action_fun_id", "edit_feedback_modal", "btn btn-primary btn-sm", '<i class="fa fa-pencil"></i>', ["feedback", "id"]],
                                    ["action_fun_id", "del_feedback", "btn btn-danger btn-sm", '<i class="fa fa-trash"></i>', ["feedback", "id"]],
                                ],
                                perm: [["no"], ["no"], ["no"], ["no"], ["no"], ["no"], ["no"], ["no"], ["no"], ["no"]],
                            },
                        },
                    });
                })
                .catch((error) => console.log(error));
        },
        find_comment_row(id) {
            const tab = this.data_for_tab.find(t => t.id === 1);
            return tab ? tab.tab_data.data.find(r => r.comment?.id == id) || null : null;
        },
        find_feedback_row(id) {
            const tab = this.data_for_tab.find(t => t.id === 2);
            return tab ? tab.tab_data.data.find(r => r.id == id) || null : null;
        },
        on_show_comment(id) {
            axios.get("/set_article/set_guide_comment/get_actyve_comment/" + id)
                .then(r => this.$refs.show_comment_modal.show_modal(r.data))
                .catch(e => console.log(e));
        },
        on_toggle_comment(id) {
            const row = this.find_comment_row(id);
            if (!row) return;
            const c = row.comment;
            if (c.published == 1) {
                if (confirm(this.$t('user.my_comments.confirm_hide_comment'))) {
                    axios.post('/set_article/set_guide_comment/user_hide_comment/' + id)
                        .then(() => this.get_my_guide_comments_data()).catch(e => console.log(e));
                }
            } else if (c.admin_hidden) {
                this.$bus.$emit('toast', { type: 'warning', title: this.$t('user.my_comments.cannot_show_title'), message: this.$t('user.my_comments.comment_hidden_by_admin') });
            } else {
                if (confirm(this.$t('user.my_comments.confirm_show_comment'))) {
                    axios.post('/set_article/set_guide_comment/user_show_comment/' + id)
                        .then(() => this.get_my_guide_comments_data()).catch(e => console.log(e));
                }
            }
        },
        on_edit_comment(id) {
            const row = this.find_comment_row(id);
            if (row?.comment?.admin_hidden) {
                this.$bus.$emit('toast', { type: 'warning', title: this.$t('user.my_comments.action_blocked_title'), message: this.$t('user.my_comments.comment_hidden_cannot_modify') });
                return;
            }
            this.$refs.comment_edit_modal.show_modal(id);
        },
        on_del_comment(id) {
            const row = this.find_comment_row(id);
            if (row?.comment?.admin_hidden) {
                this.$bus.$emit('toast', { type: 'warning', title: this.$t('user.my_comments.action_blocked_title'), message: this.$t('user.my_comments.comment_hidden_cannot_modify') });
                return;
            }
            if (confirm(this.$t('user.my_comments.confirm_delete_comment'))) {
                axios.delete('/set_article/set_guide_comment/user_del_comment/' + id)
                    .then(() => this.get_my_guide_comments_data()).catch(e => console.log(e));
            }
        },
        on_show_feedback(id) {
            const row = this.find_feedback_row(id);
            if (row) this.$refs.feedback_show_modal.show_modal(row);
        },
        on_toggle_feedback(id) {
            const row = this.find_feedback_row(id);
            if (!row || !row.feedback) return;
            const fb = row.feedback;
            if (fb.published == 1) {
                if (confirm(this.$t('user.my_comments.confirm_hide_feedback'))) {
                    axios.post('/set_product/set_product_feedback/user_hide_feedback/' + id)
                        .then(() => this.get_my_guide_comments_data()).catch(e => console.log(e));
                }
            } else if (fb.admin_hidden) {
                this.$bus.$emit('toast', { type: 'warning', title: this.$t('user.my_comments.cannot_show_title'), message: this.$t('user.my_comments.feedback_hidden_by_admin') });
            } else {
                if (confirm(this.$t('user.my_comments.confirm_show_feedback'))) {
                    axios.post('/set_product/set_product_feedback/user_show_feedback/' + id)
                        .then(() => this.get_my_guide_comments_data()).catch(e => console.log(e));
                }
            }
        },
        on_edit_feedback_modal(id) {
            const row = this.find_feedback_row(id);
            if (row?.feedback?.admin_hidden) {
                this.$bus.$emit('toast', { type: 'warning', title: this.$t('user.my_comments.action_blocked_title'), message: this.$t('user.my_comments.review_hidden_cannot_modify') });
                return;
            }
            this.$refs.feedback_edit_modal.show_modal(id);
        },
        on_del_feedback(id) {
            const row = this.find_feedback_row(id);
            if (row?.feedback?.admin_hidden) {
                this.$bus.$emit('toast', { type: 'warning', title: this.$t('user.my_comments.action_blocked_title'), message: this.$t('user.my_comments.review_hidden_cannot_modify') });
                return;
            }
            if (confirm(this.$t('user.my_comments.confirm_delete_feedback'))) {
                axios.delete('/set_product/set_product_feedback/del_feedback/' + id)
                    .then(() => this.get_my_guide_comments_data()).catch(e => console.log(e));
            }
        },
    },
};
</script>
