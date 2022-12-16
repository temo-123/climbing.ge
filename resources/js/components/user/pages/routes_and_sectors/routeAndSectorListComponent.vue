<template>
    <div class="row">
        <div class="col-sm-3">
            <left-menu />
        </div>
        <div class="col-sm-9">
            <div class="col-sm-12">
                <tabsComponent 
                    :table_data="this.data_for_tab"
                    @update-data="update"
                />
            </div>
        </div>
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
                .get("../api/sector/")
                .then(response => {
                    this.data_for_tab = [];

                    this.data_for_tab.push({'id': 1,
                                            'data': response.data, 
                                            'table_name': 'Sectors', 
                                            'table_category': this.$route.params.article_category, 
                                            'table_add_url': 'sectorAdd', 
                                            'table_del_url': 'del_url', 
                                            'table_edit_url': 'edit_url'
                                        });

                    this.get_routes()
                })
                .catch(
                    error => console.log(error)
                );
            },
            get_routes(){
                axios
                .get("../api/route/")
                .then(response => {
                    this.data_for_tab.push({'id': 2,
                                            'data': response.data, 
                                            'table_name': 'Routes', 
                                            'table_category': this.$route.params.article_category, 
                                            'table_add_url': 'routeAdd',
                                            'table_del_url': 'del_url', 
                                            'table_edit_url': 'edit_url'
                                        });
                    this.get_mtp()
                })
                .catch(
                    error => console.log(error)
                );
            },
            get_mtp(){
                axios
                .get("../api/mtp/")
                .then(response => {
                    this.data_for_tab.push({'id': 3,
                                            'data': response.data, 
                                            'table_name': 'Multi-pitchs', 
                                            'table_category': this.$route.params.article_category, 
                                            'table_add_url': 'MTPAdd',
                                            'table_del_url': 'del_url', 
                                            'table_edit_url': 'edit_url'
                                        });
                    this.get_mtp_pitch()
                })
                .catch(
                    error => console.log(error)
                );
            },
            get_mtp_pitch(){
                axios
                .get("../api/mtp/mtp_pitch/")
                .then(response => {
                    this.data_for_tab.push({'id': 4,
                                            'data': response.data, 
                                            'table_name': 'Pitches', 
                                            'table_category': this.$route.params.article_category, 
                                            'table_add_url': 'MTPPitchAdd',
                                            'table_del_url': 'del_url', 
                                            'table_edit_url': 'edit_url'
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