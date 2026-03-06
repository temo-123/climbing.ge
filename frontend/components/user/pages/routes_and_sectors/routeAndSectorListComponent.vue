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

                        @update="get_sectors"

                        @del_sector="del_sector"
                        @del_route="del_route"
                        @del_multi_pitch="del_multi_pitch"

                        @sector_modal="show_sector_model"
                        @mtp_modal="show_mtp_model"

                        @filtr_sector="filtr_sector"
                        @filtr_route="filtr_route"
                        @filtr_mtp="filtr_mtp"
                    />
                </div>
            </div>
        </div>

        <sectorModal ref="sector_modal" />
        <mtpModal ref="mtp_modal" />
    </div>
</template>

<script>
    import tabsComponent  from '../../items/data_table/TabsComponent.vue'
    import breadcrumb from '../../items/BreadcrumbComponent.vue'
    import sectorModal from "../../items/modal/tab_modals/SectorModalComponent.vue";
    import mtpModal from "../../items/modal/tab_modals/MTPPitchSequenceModalComponent.vue";
    export default {
        components: {
            tabsComponent,
            breadcrumb,
            sectorModal,
            mtpModal
        },
        // props: [
        //     'status',
        // ],
        data(){
            return {
                data_for_tab: [],

                sectors: [],
                routes: [],
                mtp: [],
                mtp_pitchs: [],
                outdoor_articles: []
            }
        },
        mounted() {
            this.get_outdoor_articles()
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
            get_outdoor_articles(){
                axios
                .get("/get_article/get_category_articles/outdoor")
                .then(response => {
                    this.outdoor_articles = response.data
                })
                .catch(
                    error => console.log(error)
                );
            },
            
            get_sectors(){
                axios
                .get("/get_sector/get_sectors_by_article_category/outdoor/")
                .then(response => {
                    this.data_for_tab = [];

                    this.sectors = response.data

                    this.data_for_tab.push({
                        'id': 1,
                        'table_name': 'Sectors', 
                        'add_action': {
                            'action': 'url',
                            'link': 'sector/add/outdoor', 
                            'class': 'btn btn-primary'
                        },
                        'tab_data': {
                            'data': response.data, 
                            'tab': {
                                'head': [
                                    'ID',
                                    'Name',
                                    'Public',
                                    'Edit routes',
                                    'Edit',
                                    'Delite',
                                ],
                                'body': [
                                    ['data', ['id']],
                                    ['data_action_id', ['name'], 'sector_modal'],
                                    ['data', ['published'], 'bool'],
                                    ['action_fun_id', 'sector_modal', 'btn btn-success', '<i aria-hidden="true" class="fa fa-list-ol"></i>'],
                                    ['action_router', 'sectorEdit', 'btn btn-primary', '<i aria-hidden="true" class="fa fa-pencil"></i>'],
                                    ['action_fun_id', 'del_sector', 'btn btn-danger', '<i aria-hidden="true" class="fa fa-trash"></i>'],
                                ],
                                'perm': [
                                    ['no'],
                                    ['no'],
                                    ['no'],
                                    ['sector', 'edit'],
                                    ['sector', 'edit'],
                                    ['sector', 'del'],
                                ]
                            }
                        },
                        // Add filter for sectors by outdoor articles
                        'filter_data': {
                            'title': 'Filter by Article',
                            'data': this.outdoor_articles,
                            'action_fun_id': 'filtr_sector',
                            'array_key': 'url_title'
                        }
                    });

                    this.get_routes()
                })
                .catch(
                    error => console.log(error)
                );
            },

            get_routes(){
                axios
                .get("/get_route/get_routes_by_category_array/", { params: { categories: ['sport climbing', 'top rope', 'tred climbing', 'bouldering'] } })
                .then(response => {
                    this.routes = response.data

                    this.data_for_tab.push({
                        'id': 2,
                        'table_name': 'Routes', 
                        'add_action': {
                            'action': 'url',
                            'link': '/route/add/outdoor', 
                            'class': 'btn btn-primary',
                            'name': 'Add Route'
                        },
                        'tab_data': {
                            'data': response.data, 
                            'tab': {
                                'head': [
                                    'ID',
                                    'Name',
                                    'Grade',
                                    'Height',
                                    'Bolts',
                                    'Edit',
                                    'Delite',
                                ],
                                'body': [
                                    ['data', ['id']],
                                    ['data', ['name']],
                                    ['data', ['grade'], ['or_grade']],
                                    ['data', ['height']],
                                    ['data', ['bolts']],
                                    ['action_router', 'routeEdit', 'btn btn-primary', '<i aria-hidden="true" class="fa fa-pencil"></i>'],
                                    ['action_fun_id', 'del_route', 'btn btn-danger', '<i aria-hidden="true" class="fa fa-trash"></i>'],
                                ],
                                'perm': [
                                    ['no'],
                                    ['no'],
                                    ['no'],
                                    ['no'],
                                    ['no'],
                                    ['route', 'edit'],
                                    ['route', 'del'],
                                ]
                            }
                        },
                        // Add filter for routes by sectors
                        'filter_data': {
                            'title': 'Filter by Sector',
                            'data': this.sectors,
                            'action_fun_id': 'filtr_route',
                            'array_key': 'name'
                        }
                    });

                    this.get_mtp()
                })
                .catch(
                    error => console.log(error)
                );
            },

            get_mtp(){
                axios
                .get("/get_mtp/get_all_mtp/")
                .then(response => {
                    this.mtp = response.data

                    this.data_for_tab.push({
                        'id': 3,
                        'table_name': 'Multi-pitchs', 
                        'add_action': {
                            'action': 'route',
                            'link': 'MTPAdd', 
                            'class': 'btn btn-primary',
                            'name': 'Add Route'
                        },
                        'tab_data': {
                            'data': response.data, 
                            'tab': {
                                'head': [
                                    'ID',
                                    'Name',
                                    'Height',
                                    'Edit pitchs',
                                    'Edit',
                                    'Delite',
                                ],
                                'body': [
                                    ['data', ['id']],
                                    ['data_action_id', ['name'], 'mtp_modal'],
                                    ['data', ['height']],
                                    ['action_fun_id', 'mtp_modal', 'btn btn-success', '<i aria-hidden="true" class="fa fa-list-ol"></i>'],
                                    ['action_router', 'MTPEdit', 'btn btn-primary', '<i aria-hidden="true" class="fa fa-pencil"></i>'],
                                    ['action_fun_id', 'del_multi_pitch', 'btn btn-danger', '<i aria-hidden="true" class="fa fa-trash"></i>'],
                                ],
                                'perm': [
                                    ['no'],
                                    ['no'],
                                    ['no'],
                                    ['mtp', 'edit'],
                                    ['mtp', 'edit'],
                                    ['mtp', 'del'],
                                ]
                            }
                        },
                        // Add filter for MTP by sectors
                        'filter_data': {
                            'title': 'Filter by Sector',
                            'data': this.sectors,
                            'action_fun_id': 'filtr_mtp',
                            'array_key': 'name'
                        }
                    });
                })
                .catch(
                    error => console.log(error)
                );
            },

            filtr_sector(article_id){
                // article_id 0 means "All"
                if (article_id == 0 || article_id == 'all' || article_id == 'All' || article_id == null) {
                    this.data_for_tab[0]['tab_data']['data'] = this.sectors
                }
                else{
                    this.data_for_tab[0]['tab_data']['data'] = this.sectors.filter(function (item){
                        return item.article_id == article_id
                    })
                }
            },

            filtr_route(sector_id){
                // sector_id 0 means "All"
                if (sector_id == 0 || sector_id == 'all' || sector_id == 'All' || sector_id == null) {
                    this.data_for_tab[1]['tab_data']['data'] = this.routes
                }
                else{
                    this.data_for_tab[1]['tab_data']['data'] = this.routes.filter(function (item){
                        return item.sector_id == sector_id
                    });
                }
            },

            filtr_mtp(sector_id){
                // sector_id 0 means "All"
                if (sector_id == 0 || sector_id == 'all' || sector_id == 'All' || sector_id == null) {
                    this.data_for_tab[2]['tab_data']['data'] = this.mtp
                }
                else{
                    // Filter MTP by checking which MTP records belong to the selected sector
                    this.data_for_tab[2]['tab_data']['data'] = this.mtp.filter(function (item){
                        return item.sector_id == sector_id
                    })
                }
            },

            del_sector(id){
                if(confirm('Are you sure, you want delite it?')){
                    axios
                    .post('/set_sector/del_sector/'+id, {
                        _method: 'DELETE'
                    })
                    .then(Response => {
                        this.get_sectors()
                    })
                    .catch(error => console.log(error))
                }
            },
            show_sector_model(sector_id){
                this.$refs.sector_modal.show_sector_modal(sector_id)
            },
            del_route(id){
                if(confirm('Are you sure, you want delite it?')){
                    axios
                    .post('/set_route/del_route/'+id, {
                        id: id,
                        _method: 'DELETE'
                    })
                    .then(Response => {
                        // this.update(this.tab_num)

                        this.get_sectors()
                    })
                    .catch(error => console.log(error))
                }
            },
            del_multi_pitch(id){
                if(confirm('Are you sure, you want delite it?')){
                    axios
                    .post('/set_mtp/del_mtp/'+id, {
                        _method: 'DELETE'
                    })
                    .then(Response => {
                        this.get_sectors()
                    })
                    .catch(error => console.log(error))
                }
            },
            show_mtp_model(sector_id){
                this.$refs.mtp_modal.show_modal(sector_id)
            },
        }
    }
</script>

<style>

</style>

