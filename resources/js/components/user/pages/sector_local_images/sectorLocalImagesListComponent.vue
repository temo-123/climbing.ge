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
                        @update-data="update"
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
            tabsComponent ,
            breadcrumb
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
            this.get_sectors()
        },
        watch: {
            '$route' (to, from) {
                this.data_for_tab = [],
                this.get_sectors()
                window.scrollTo(0,0)
            }
        },
        methods: {
            get_sectors(){
                axios
                .get("../api/sector_local_images/")
                .then(response => {
                    this.data_for_tab = [];

                    this.data_for_tab.push({'id': 1,
                                            'data': response.data, 
                                            'table_name': 'Sectors local images', 
                                            // 'table_category': this.$route.params.article_category, 
                                            'table_add_url': 'sectorLocalImagesListAdd', 
                                            // 'table_del_url': 'del_url', 
                                            // 'table_edit_url': 'edit_url'
                                        });
                })
                .catch(
                    error => console.log(error)
                );
            },
            update(id){
                this.get_sectors()
            }
        }
    }
</script>

<style>

</style>