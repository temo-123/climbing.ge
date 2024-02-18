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
                    />
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import tabsComponent  from '../../../items/data_tabs/DataTab/TabsComponent'
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
            this.get_all_guide_comments_data()
        },
        methods: {
            get_all_guide_comments_data: function(){
                axios
                .get("/guide_comment/get_all_comments/")
                .then(response => {
                    this.data_for_tab.push({'id': 1,
                                            'data': response.data, 
                                            'table_name': 'Guide comments', 
                                            // 'table_category': this.$route.params.article_category, 
                                            'table_del_url': 'del_url', 
                                            'table_edit_url': 'edit_url'
                                        });
                    this.get_all_climbing_routes_review_data()
                })
                .catch(
                    error => console.log(error)
                );

            },
            get_all_climbing_routes_review_data: function(){
                axios
                .get("/route_review/get_all_review/")
                .then(response => {
                    this.data_for_tab.push({'id': 2,
                                            'data': response.data, 
                                            'table_name': 'Climbing routes review', 
                                            // 'table_category': this.$route.params.article_category, 
                                            'table_del_url': 'del_url', 
                                            'table_edit_url': 'edit_url'
                                        });
                    this.get_all_shop_feedbacks_data()
                })
                .catch(
                    error => console.log(error)
                );
            },
            get_all_shop_feedbacks_data: function(){
                axios
                .get("/product_feedback/get_all_feedbacks/")
                .then(response => {
                    this.data_for_tab.push({'id': 3,
                                            'data': response.data, 
                                            'table_name': 'Product feedbacks', 
                                            // 'table_category': this.$route.params.article_category, 
                                            'table_del_url': 'del_url', 
                                            'table_edit_url': 'edit_url'
                                        });
                    // this.get_all_products_review_data()
                })
                .catch(
                    error => console.log(error)
                );

            },
            // get_all_products_review_data: function(){
            //     axios
            //     .get("/product_review/get_all_review/")
            //     .then(response => {
            //         this.data_for_tab.push({'id': 4,
            //                                 'data': response.data, 
            //                                 'table_name': 'Products review', 
            //                                 // 'table_category': this.$route.params.article_category, 
            //                                 'table_del_url': 'del_url', 
            //                                 'table_edit_url': 'edit_url'
            //                             });
            //     })
            //     .catch(
            //         error => console.log(error)
            //     );
            // },
        }
    }
</script>
