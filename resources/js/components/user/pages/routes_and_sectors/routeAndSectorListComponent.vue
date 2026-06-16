<template>
    <div class="row">
        <left-menu />
        <div class="col-sm-12">
            <div class="row">
                <div class="col-md-12">
                    <breadcrumb />
                </div>
            </div>
            <div class="row" v-if="loading">
                <div class="col-md-12">
                    <div class="text-center py-4">
                        <i class="fa fa-spinner fa-spin fa-3x"></i>
                        <p>Loading...</p>
                    </div>
                </div>
            </div>
            <div class="row" v-else>
                <div class="col-sm-12">
                    <tabsComponent 
                        :table_data="table_data"
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
import tabsComponent from '../../items/data_table/TabsComponent.vue'
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
    data(){
        return {
            data_for_tab: [],
            loading: true,
            sectors: [],
            routes: [],
            mtp: [],
            mtp_pitchs: [],
            outdoor_articles: []
        }
    },
    computed: {
        table_data() {
            return (this.data_for_tab || []).filter(tab => {
                if (!tab || typeof tab !== 'object' || tab.id === undefined) return false;
                if (!tab.tab_data || !Array.isArray(tab.tab_data.data)) return false;
                return tab.tab_data.data.every(item => item && item.id !== undefined);
            });
        }
    },
    watch: {
        table_data(newVal, oldVal) {
                    const safeData = this.table_data;
            if (safeData.length > 0 && this.$refs.tabsComponent && !this.$refs.tabsComponent.tab_num) {
                this.$refs.tabsComponent.tab_num = safeData[0].id;
            }
        },
        '$route' (to, from) {
            this.data_for_tab = [];
            this.loading = true;
            this.get_sectors();
            window.scrollTo(0,0)
        }
    },
    mounted() {
        this.get_outdoor_articles()
        this.get_sectors()
    },
    methods: {
        get_outdoor_articles(){
            axios
            .get("/get_article/get_category_articles/outdoor")
            .then(response => {
                this.outdoor_articles = Array.isArray(response.data) ? response.data.filter(item => item && item.id !== undefined) : [];
            })
            .catch(error => {
                console.error('Get outdoor articles error:', error);
                this.outdoor_articles = [];
            });
        },
        get_sectors(){
            this.loading = true;
            axios
            .get("/get_sector/get_sectors_by_article_category/outdoor/")
            .then(response => {
                this.data_for_tab = [];
                this.sectors = response.data || [];
                const validSectors = Array.isArray(response.data) ? response.data.filter(item => item && item.id !== undefined) : [];
                this.data_for_tab.push({
                    'id': 1,
                    'table_name': 'Sectors', 
                    'add_action': {
                        'action': 'url',
                        'link': 'sector/add/outdoor', 
                        'class': 'btn btn-primary'
                    },
                    'tab_data': {
                        'data': validSectors, 
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
                    'filter_data': {
                        'title': 'Filter by Article',
                        'data': this.outdoor_articles,
                        'action_fun_id': 'filtr_sector',
                        'array_key': 'url_title'
                    }
                });
                this.get_routes()
            })
            .catch(error => {
                console.error('Get sectors error:', error);
                this.loading = false;
            });
        },
        get_routes(){
            axios
            .get("/get_route/get_routes_by_category_array/", { params: { categories: ['sport climbing', 'top rope', 'tred climbing', 'bouldering'] } })
            .then(response => {
                this.routes = response.data || [];
                const validRoutes = Array.isArray(response.data) ? response.data.filter(item => item && item.id !== undefined) : [];
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
                        'data': validRoutes, 
                        'tab': {
                            'head': [
                                'ID',
                                'Name',
                                'Category',
                                'Grade',
                                'Height',
                                'Bolts',
                                'Edit',
                                'Delite',
                            ],
                            'body': [
                                ['data', ['id']],
                                ['data', ['name']],
                                ['data', ['category']],
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
                                ['no'],
                                ['route', 'edit'],
                                ['route', 'del'],
                            ]
                        }
                    },
                    'filter_data': {
                        'title': 'Filter by Sector',
                        'data': this.sectors,
                        'action_fun_id': 'filtr_route',
                        'array_key': 'name'
                    }
                });
                this.get_mtp()
            })
            .catch(error => {
                console.error('Get routes error:', error);
                this.loading = false;
            });
        },
        get_mtp(){
            axios
            .get("/get_mtp/get_all_mtp/")
            .then(response => {
                this.mtp = response.data || [];
                const validMtp = Array.isArray(response.data) ? response.data.filter(item => item && item.id !== undefined) : [];
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
                        'data': validMtp, 
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
                    'filter_data': {
                        'title': 'Filter by Sector',
                        'data': this.sectors,
                        'action_fun_id': 'filtr_mtp',
                        'array_key': 'name'
                    }
                });
                this.loading = false;
            })
            .catch(error => {
                console.error('Get mtp error:', error);
                this.loading = false;
            });
        },
        filtr_sector(article_id){
            if (article_id == 0 || article_id == 'all' || article_id == 'All' || article_id == null) {
                if (this.data_for_tab[0]) {
                    this.data_for_tab[0].tab_data.data = this.sectors.filter(item => item && item.id !== undefined);
                }
            } else {
                if (this.data_for_tab[0]) {
                    this.data_for_tab[0].tab_data.data = this.sectors.filter(item => item && item.article_id == article_id);
                }
            }
        },
        filtr_route(sector_id){
            if (sector_id == 0 || sector_id == 'all' || sector_id == 'All' || sector_id == null) {
                if (this.data_for_tab[1]) {
                    this.data_for_tab[1].tab_data.data = this.routes.filter(item => item && item.id !== undefined);
                }
            } else {
                if (this.data_for_tab[1]) {
                    this.data_for_tab[1].tab_data.data = this.routes.filter(item => item && item.sector_id == sector_id);
                }
            }
        },
        filtr_mtp(sector_id){
            if (sector_id == 0 || sector_id == 'all' || sector_id == 'All' || sector_id == null) {
                if (this.data_for_tab[2]) {
                    this.data_for_tab[2].tab_data.data = this.mtp.filter(item => item && item.id !== undefined);
                }
            } else {
                if (this.data_for_tab[2]) {
                    this.data_for_tab[2].tab_data.data = this.mtp.filter(item => item && item.sector_id == sector_id);
                }
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
                .catch(error => {
                    console.error('Del sector error:', error);
                })
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
                    this.get_sectors()
                })
                .catch(error => {
                    console.error('Del route error:', error);
                })
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
                .catch(error => {
                    console.error('Del mtp error:', error);
                })
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
