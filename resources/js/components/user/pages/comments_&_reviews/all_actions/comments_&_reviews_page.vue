<template>
    <div class="row">
        <!-- <div class="col-sm-3"> -->
        <left-menu />
        <!-- </div> -->
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

                        @del_comment="del_comment"
                        @show_comment="get_comment(event, 'show')"
                        @hide_comment="get_comment(event, 'hide')"

                        @del_review="del_review"

                        @del_feedback="del_feedback"
                        @edit_review_modal="edit_review_modal"
                    />
                </div>
            </div>
        </div>

        <comment_show_modal ref="show_comment_modal" @restart="get_all_guide_comments_data" />
        <comment_hide_modal ref="hide_comment_modal" @restart="get_all_guide_comments_data" />

        <reviewEditModal ref="review_edit_modal" @restart="get_all_guide_comments_data"/>
    </div>
</template>

<script>
import tabsComponent from "../../../items/data_table/TabsComponent.vue";
import breadcrumb from "../../../items/BreadcrumbComponent.vue";

import comment_show_modal from "../../../items/modal/comments/CommentShowModal.vue";
import comment_hide_modal from "../../../items/modal/comments/CommentHideModal.vue";

import reviewEditModal from '../../../items/modal/review/ReviewEditModal.vue'

export default {
    components: {
        comment_show_modal,
        comment_hide_modal,
        tabsComponent,
        breadcrumb,
        reviewEditModal,
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
                .get("/guide_comment/get_all_comments/")
                .then((response) => {
                    this.data_for_tab.push({
                        id: 1,
                        table_name: "Guidebook comments",
                        tab_data: {
                            data: response.data,
                            tab: {
                                head: [
                                    "ID",
                                    "Name",
                                    "Email",
                                    "Published",
                                    "Article",
                                    "Delite",
                                    "Hiden",
                                ],
                                body: [
                                    [
                                        "data",
                                        ["comment", "id"]
                                    ],
                                    [
                                        "data",
                                        [
                                            ["comment", "name"],
                                            ["comment", "surname"],
                                        ],
                                    ],
                                    [
                                        "data",
                                        ["comment", "email"]
                                    ],
                                    [
                                        "data",
                                        ["comment", "published"],
                                        "bool"
                                    ],
                                    [
                                        "data_action",
                                        ["global_article", "url_title"],
                                        "show_comment",
                                    ],
                                    [
                                        "action_fun_id",
                                        "del_comment",
                                        "btn btn-danger",
                                        '<i class="fa fa-trash" aria-hidden="true"></i>',
                                        ["comment", "id"],
                                    ],
                                    [
                                        "action_fun_id",
                                        "hide_comment",
                                        "btn btn-warning",
                                        '<i class="fa fa-eye-slash" aria-hidden="true"></i>',
                                        ["comment", "id"],
                                    ],
                                ],
                                perm: [
                                    ["no"],
                                    ["no"],
                                    ["no"],
                                    ["no"],
                                    ["no"],
                                    ["article", "del"],
                                    ["article", "del"],
                                ],
                            },
                        },
                    });
                    this.get_all_climbing_routes_review_data();
                })
                .catch((error) => console.log(error));
        },
        get_all_climbing_routes_review_data: function () {
            axios
                .get("/route_review/get_all_review/")
                .then((response) => {
                    this.data_for_tab.push({
                        id: 2,
                        table_name: "Climbing routes review",
                        tab_data: {
                            data: response.data,
                            tab: {
                                head: [
                                    "ID",
                                    "Climbing route",
                                    "Stars",
                                    "Edit",
                                    "Delite",
                                ],
                                body: [
                                    ["data", ["review", "id"]],
                                    ["data", ["route", "name"]],
                                    ["stars", ["review", "stars"]],
                                    [
                                        "action_fun_id",
                                        "edit_review_modal",
                                        "btn btn-primary",
                                        '<i class="fa fa-pencil" aria-hidden="true"></i>',
                                        ["review", "id"],
                                    ],
                                    [
                                        "action_fun_id",
                                        "del_review",
                                        "btn btn-danger",
                                        '<i class="fa fa-trash" aria-hidden="true"></i>',
                                        ["review", "id"],
                                    ],
                                ],
                                perm: [["no"], ["no"], ["no"], ["no"], ["no"]],
                            },
                        },
                    });
                    this.get_all_shop_feedbacks_data();
                })
                .catch((error) => console.log(error));
        },
        get_all_shop_feedbacks_data: function () {
            axios
                .get("/product_feedback/get_all_feedbacks/")
                .then((response) => {
                    this.data_for_tab.push({
                        id: 3,
                        table_name: "Product feedbacks",
                        tab_data: {
                            data: response.data,
                            tab: {
                                head: [
                                    "ID",
                                    "Published",
                                    "Product",
                                    "Stars",
                                    "Comententor",
                                    "Comententor Email",
                                    "Delite",
                                    "Hiden",
                                ],
                                body: [
                                    ["data", ["feedback", "id"]],
                                    ["data", ["feedback", "published"], 'bool'],
                                    ["data", ["locale_product", "title"]],
                                    ["stars", ["feedback", "stars"]],
                                    [
                                        "data",
                                        [
                                            ["feedback", "name"],
                                            ["feedback", "surname"],
                                        ],
                                    ],
                                    ["data", ["feedback", "email"]],
                                    [
                                        "action_fun_id",
                                        "del_feedback",
                                        "btn btn-danger",
                                        '<i class="fa fa-trash" aria-hidden="true"></i>',
                                        ["feedback", "id"],
                                    ],
                                    [
                                        "action_fun_id",
                                        "edit_review_modal",
                                        "btn btn-warning",
                                        '<i class="fa fa-eye-slash" aria-hidden="true"></i>',
                                        ["feedback", "id"],
                                    ],
                                ],
                                perm: [
                                    ["no"],
                                    ["no"],
                                    ["no"],
                                    ["no"],
                                    ["no"],
                                    ["no"],
                                    ["no"],
                                    ["no"],
                                ],
                            },
                        },
                    });
                    // this.get_all_products_review_data()
                })
                .catch((error) => console.log(error));
        },
        del_review(id){
            if(confirm('Are you sure, you want delite it?')){
                axios
                .post('/route_review/del_route_review/'+id, {
                    id: id,
                    _method: 'DELETE'
                })
                .then(Response => {
                    this.get_all_guide_comments_data()
                })
                .catch(error => console.log(error))
            }
        },
        del_comment(id) {
            if (
                confirm(
                    "Are you sure, you want delite this comment from page content?"
                )
            ) {
                axios
                    .delete("/guide_comment/del_comment/" + id, {
                        _method: "delete",
                    })
                    .then((Response) => {
                        this.is_user_comment_delite_model = false;
                        this.get_all_guide_comments_data();
                    })
                    .catch((error) => console.log(error));
            }
        },
        del_feedback(id){
            if(confirm('Are you sure, you want delite this comment from page content?')){
                axios
                .delete('/product_feedback/del_feedback/'+id, {
                    _method: 'delete'
                })
                .then(Response => {
                    this.is_user_comment_delite_model = false
                    this.get_all_guide_comments_data();
                })
                .catch(error => console.log(error))
            }
        },
        get_action_feedback(feedback_id, action){
            this.quick_feedback = []

            axios
            .get("/product_feedback/get_actyve_feedback/"+feedback_id)
            .then(response => {
                if(action == 'show'){
                    this.$refs.show_comment_modal.show_modal(response.data)
                }
                else if(action == 'hide'){
                    this.$refs.hide_comment_modal.show_modal('product_feedback/hide_feedback/', response.data)
                }
            })
            .catch(
                error => console.log(error)
            );
        },
        go_to_article_page(url_title, category) {
            window.open(this.MIX_APP_SSH + this.MIX_SITE_URL + "/" + category +  "/" + url_title );
        },
        go_to_product_page(url_title){
            window.open(this.MIX_APP_SSH + this.MIX_SHOP_URL + '/product/' + url_title)
        },
        get_comment(comment_id, action) {
            this.quick_comment = [];

            axios
                .get("/guide_comment/get_actyve_comment/" + comment_id)
                .then((response) => {
                    if (action == "show") {
                        this.$refs.show_comment_modal.show_modal(response.data);
                    } else if (action == "hide") {
                        this.$refs.hide_comment_modal.show_modal(
                            "guide_comment/hide_comment/",
                            response.data
                        );
                    }
                })
                .catch((error) => console.log(error));
        },
        show_hide_comment(id){
            this.$refs.hide_comment_modal.show_modal('product_feedback/hide_feedback', id)
        },
        edit_review_modal(id){
            this.$refs.review_edit_modal.show_modal(id)
        },
    },
};
</script>
