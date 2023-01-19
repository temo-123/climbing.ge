<template>
    <div class="row">
        <div class="col-sm-3">
            <left-menu />
        </div>
        <div class="col-sm-9"> 
            <div class="row">
                <div class="col-md-12">
                    <breadcrumb />
                </div>
            </div>
            <div class="row">
                <div class="col-md-12" v-if="event_loading">
                    <content-loader
                        viewBox="0 0 500 150"
                        primaryColor="#f3f3f3"
                        secondaryColor="#7427bb75"
                    >
                        <rect x="0" y="0" rx="2" ry="2" width="100%" height="25" />

                        <rect x="0" y="45" rx="3" ry="3" width="100%" height="10" />
                        <rect x="0" y="60" rx="3" ry="3" width="100%" height="10" />
                        <rect x="0" y="75" rx="3" ry="3" width="100%" height="10" />
                        <rect x="0" y="90" rx="3" ry="3" width="100%" height="10" />
                        <rect x="0" y="105" rx="3" ry="3" width="100%" height="10" />
                    </content-loader>
                </div>
                <div class="col-sm-12" v-else>
                    <tabsComponent 
                        :table_data="this.data_for_tab"
                        @update-data="get_events"
                    />
                </div>
            </div>
         </div>
    </div>
</template>

<script>
    import tabsComponent  from '../../items/data_tabs/DataTab/TabsComponent'
    import { ContentLoader } from 'vue-content-loader'
    import breadcrumb from '../../items/BreadcrumbComponent.vue'
    export default {
        components: {
            breadcrumb,
            tabsComponent,
            ContentLoader
        },
        // props: [
        //     'status',
        // ],
        data(){
            return {
                data_for_tab: [],
                articles: [],
                event_loading: true,
            }
        },
        mounted() {
            this.get_events()
        },
        watch: {
            '$route' (to, from) {
                this.data_for_tab = [],
                this.get_events()
                window.scrollTo(0,0)
            }
        },
        methods: {
            get_events(){
                this.data_for_tab = []
                axios
                .get("../api/event/get_all_events/")
                .then(response => {
                    this.data_for_tab.push({'id': 1,
                                            'data': response.data, 
                                            'table_name': "Events",
                                            'table_add_url': 'eventAdd', 
                                            // 'table_edit_url': 'spot_category_edit',
                                        });
                })
                .catch(
                    error => console.log(error)
                )
                .finally(() => this.event_loading = false);
            },
        }
    }
</script>

<style>

</style>