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
            this.get_my_comments_data()
        },
        methods: {
            get_my_comments_data: function(){
                axios
                .get("../api/get_my_comment/")
                .then(response => {
                    this.data_for_tab.push({'id': 1,
                                            'data': response.data, 
                                            'table_name': 'My comments', 
                                            // 'table_category': this.$route.params.article_category, 
                                            'table_del_url': 'del_url', 
                                            'table_edit_url': 'edit_url'
                                        });
                    // this.get_categories_data()
                })
                .catch(
                    error => console.log(error)
                );

            },

            // del_comment(itemId) {
            //     axios
            //     .post('/reviews_and_comments/delete_comment/' + itemId, {
            //         comment_id: itemId,
            //     })
            //     .then(Response => {
            //         console.log(response)
            //         this.get_all_comments_data()
            //     })
            //     .catch(
            //         error => console.log(error)
            //     )
            // },
        }
    }
</script>
