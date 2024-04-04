<template>
    <div class="row">
        <!-- <div class="col-sm-3"> -->
            <left-menu />
        <!-- </div> -->
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
                        @filtr="filtr"

                        @update-data="get_articles"
                    />
                </div>
            </div>
         </div>
    </div>
</template>

<script>
    import tabsComponent  from '../../items/data_tabs/DataTab/TabsComponent'
	import breadcrumb from '../../items/BreadcrumbComponent.vue'
	export default {
		components: {
			breadcrumb,
            tabsComponent ,
        },
        // props: [
        //     'status',
        // ],
        data(){
            return {
                data_for_tab: [],
                article_loading: false,
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
            get_filtred_articles(id){
                axios
                .get("/mount_route/get_filtred_mount_route_for_admin/" + id)
                .then(response => {
                    this.data_for_tab = []
                    this.data_for_tab.push({'id': 1,
                                            'data': response.data, 
                                            'table_name': 'Mountaineering routes', 
                                            'table_category': 'mount_route', 
                                            'table_add_url': 'articleAdd', 
                                            // 'table_edit_url': 'edit_url',
                                            // 'table_del_url': 'del_url', 
                                        });

                    this.get_mounts()
                })
                .catch(
                    error => console.log(error)
                )
                .finally(() => this.article_loading = false);
            },

            get_unfilted_articles(){
                axios
                .get("/article/get_category_articles/mount_route")
                .then(response => {
                    this.data_for_tab = []
                    this.data_for_tab.push({'id': 1,
                                            'data': response.data, 
                                            'table_name': 'Mountaineering routes', 
                                            'table_category': 'mount_route', 
                                            'table_add_url': 'articleAdd', 
                                            // 'table_edit_url': 'edit_url',
                                            // 'table_del_url': 'del_url', 
                                        });

                    this.get_mounts()
                })
                .catch(
                    error => console.log(error)
                )
                .finally(() => this.article_loading = false);
            },

            get_articles(filtr_id = 'all'){
                if (filtr_id === 'all' || filtr_id === 'All') {
                    this.get_unfilted_articles()
                }
                else{
                    this.get_filtred_articles(filtr_id) 
                }
            },

            filtr(event){
                this.get_articles(event)
            },

            get_mounts(){
                axios
                .get("/mount/mount")
                .then(response => {
                    this.data_for_tab.push({'id': 2,
                                            'data': response.data, 
                                            'table_name': 'Mount vasives', 
                                            'table_category': 'Mount vasives', 
                                            'table_add_url': 'mount_massive_add', 
                                            // 'table_edit_url': 'edit_url',
                                            // 'table_del_url': 'del_url', 
                                        });
                })
                .catch(
                    error => console.log(error)
                );
            },
        }
    }
</script>

<style>

</style>