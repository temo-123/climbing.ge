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

                        @filtr="filtr"
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

                sectors: {},
                routes: {},
                mtp: {},
                mtp_pitchs: {}
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
            filtr(event){
                if(event.filtr_category == "sector_filtr"){
                    this.filtr_sector(event.filtr_id)
                }
                else if(event.filtr_category == "route_filtr"){
                    this.filtr_route(event.filtr_id)
                }
                else if(event.filtr_category == "mtp_filtr"){
                    this.filtr_mtp(event.filtr_id)
                }
                else if(event.filtr_category == "mtp_pitch_filtr"){
                    this.filtr_mtp_pitch(event.filtr_id)
                }
            },

            filtr_sector(region_id){
                if (region_id == 'all' || region_id == 'All' || region_id == 0 || region_id == null) {
                    this.data_for_tab[0]['data'] = this.sectors
                }
                else{
                    this.data_for_tab[0]['data'] = this.sectors.filter(function (item){
                        return item.article_id == region_id
                    })
                }
            },
            get_sectors(){
                axios
                .get("../api/sector/")
                .then(response => {
                    this.data_for_tab = [];

                    this.sectors = response.data

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

            filtr_route(sector_id){
                if (sector_id == 'all' || sector_id == 'All' || sector_id == 0 || sector_id == null) {
                    this.data_for_tab[1]['data'] = this.routes
                }
                else{
                    this.data_for_tab[1]['data'] = this.routes.filter(function (item){
                        return item.sector_id == sector_id
                    })
                }
            },
            get_routes(){
                axios
                .get("route/get_all_routes/")
                .then(response => {
                    this.routes = response.data

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

            filtr_mtp(sector_id){
                if (sector_id == 'all' || sector_id == 'All' || sector_id == 0 || sector_id == null) {
                    this.data_for_tab[2]['data'] = this.mtp
                }
                else{
                    this.data_for_tab[2]['data'] = this.routes.filter(function (item){
                        return item.sector_id == sector_id
                    })
                }
            },
            get_mtp(){
                axios
                .get("../api/mtp/")
                .then(response => {
                    this.mtp = response.data

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

            filtr_mtp_pitch(mtp_id){
                if (mtp_id == 'all' || mtp_id == 'All' || mtp_id == 0 || mtp_id == null) {
                    this.data_for_tab[3]['data'] = this.mtp_pitchs
                }
                else{
                    this.data_for_tab[3]['data'] = this.mtp_pitchs.filter(function (item){
                        return item.mtp_id == mtp_id
                    })
                }
            },
            get_mtp_pitch(){
                axios
                .get("../api/mtp/mtp_pitch/")
                .then(response => {
                    this.mtp_pitchs = response.data

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