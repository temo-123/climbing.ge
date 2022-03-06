<template>
    <div class="col-sm-12">
        <tabsComponent 
            :table_data="this.data_for_tab"
        />
    </div>
</template>

<script>
    import tabsComponent  from '../../items/data_tabs/DataTab/TabsComponent'
    export default {
        components: {
            tabsComponent ,
        },
        // props: [
        //     'status',
        // ],
        data(){
            return {
                data_for_tab: [],
            }
        },
        mounted() {
            this.get_articles()
        },
        watch: {
            '$route' (to, from) {
                this.data_for_tab = [],
                this.get_articles()
                window.scrollTo(0,0)
            }
        },
        methods: {
            get_articles(){
                axios
                .post("../api/article/", {
                    category: this.$route.params.article_category,
                })
                .then(response => {
                    this.data_for_tab.push({'id': 1,
                                            'data': response.data, 
                                            'table_name': this.$route.params.article_category, 
                                            'table_category': this.$route.params.article_category, 
                                            'table_del_url': 'del_url', 
                                            'table_edit_url': 'edit_url'
                                        });
                })
                .catch(
                    error => console.log(error)
                );
            },

            // delete_article(itemId) {
            //     axios
            //     .delete('../api/gallery_image/' + itemId, {
            //         id: itemId,
            //     })
            //     .then(Response => {
            //         this.get_gallery_data()
            //     })
            //     .catch(error => console.log(error))
            // }
        }
    }
</script>

<style>

</style>