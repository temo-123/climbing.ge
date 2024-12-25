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
                        @update-data="get_my_guide_comments_data"
                    />
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import tabsComponent  from '../../../items/data_table/TabsComponent.vue'
    import breadcrumb from '../../../items/BreadcrumbComponent.vue'
    export default {
        components: {
            tabsComponent ,
            breadcrumb
        },
        data(){
            return {
                data_for_tab: [],
            }
        },
        mounted() {
            this.get_my_guide_comments_data()
        },
        methods: {
            get_my_guide_comments_data: function(){
                this.data_for_tab = []
                axios
                .get("/guide_comment/get_user_comments/")
                .then(response => {
                    this.data_for_tab.push({'id': 1,
                                            'data': response.data, 
                                            'table_name': 'My guidebook comments', 
                                            // 'table_category': this.$route.params.article_category, 
                                            'table_del_url': 'del_url', 
                                            'table_edit_url': 'edit_url'
                                        });
                    this.get_my_climbing_route_review_comments_data()
                })
                .catch(
                    error => console.log(error)
                );

            },
            get_my_climbing_route_review_comments_data: function(){
                axios
                .get("/route_review/get_user_review/")
                .then(response => {
                    this.data_for_tab.push({'id': 2,
                                            'data': response.data, 
                                            'table_name': 'My climbing routes review', 
                                            // 'table_category': this.$route.params.article_category, 
                                            'table_del_url': 'del_url', 
                                            'table_edit_url': 'edit_url'
                                        });
                    this.get_my_products_feedbacks_data()
                })
                .catch(
                    error => console.log(error)
                );

            },
            get_my_products_feedbacks_data: function(){
                axios
                .get("/product_feedback/get_user_feedbacks/")
                .then(response => {
                    this.data_for_tab.push({'id': 3,
                                            'data': response.data, 
                                            'table_name': 'My products feedbacks', 
                                            // 'table_category': this.$route.params.article_category, 
                                            'table_del_url': 'del_url', 
                                            'table_edit_url': 'edit_url'
                                        });
                    // this.get_my_product_review_data()
                })
                .catch(
                    error => console.log(error)
                );

            },
        }
    }
</script>
