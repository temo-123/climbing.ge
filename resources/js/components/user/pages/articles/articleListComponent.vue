<template>
    <div class="row">
        <left-menu />
        <div class="col-sm-12"> 
            <div class="row">
                <div class="col-md-12">
                    <breadcrumb />
                </div>
            </div>
            <div class="row">
                <div class="col-md-12" v-if="article_loading">
                    <div class="text-center py-4">
                        <i class="fa fa-spinner fa-spin fa-3x"></i>
                        <p>Loading articles...</p>
                    </div>
                </div>
                <div class="col-sm-12" v-else-if="data_for_tab.length">
                    <tabsComponent 
                        :table_data="safeDataForTab"
                        @update="get_articles"
                        @del_article="del_article"
                        @del_region="del_region"
                        @del_route="del_route"
                        @del_sector="del_sector"
                        @del_mount_massive="del_mount_massive"
                        @filtr_outdoors="filtr_outdoors"
                        @show_spot_sectors_modal="show_spot_sectors_modal"
                        @quick_wiev_action="quick_wiev_action"
                        @sector_modal="show_sector_model"
                    />
                </div>
                <div v-else class="col-sm-12 text-center py-4">
                    No data available.
                </div>
            </div>
        </div>
        <spot_sectors_modal ref="show_spot_sectors_modal" />
        <sectorModal ref="sector_modal" />
        <ArticleQuickViewModal ref="quick_view_modal" />
    </div>
</template>

<script>
import tabsComponent from '../../items/data_table/TabsComponent.vue'
import breadcrumb from '../../items/BreadcrumbComponent.vue'
import spot_sectors_modal from "../../items/modal/tab_modals/ArticleSectorSequenceModalComponent.vue";
import sectorModal from "../../items/modal/tab_modals/SectorModalComponent.vue";
import ArticleQuickViewModal from "../../items/modal/ArticleQuickViewModal.vue";

export default {
    components: {
        breadcrumb,
        tabsComponent,
        sectorModal,
        spot_sectors_modal,
        ArticleQuickViewModal,
    },
    computed: {
        safeDataForTab() {
            return (this.data_for_tab || []).filter(tab => {
                if (!tab || typeof tab !== 'object' || tab.id === undefined) return false;
                if (!tab.tab_data || !Array.isArray(tab.tab_data.data)) return false;
                return tab.tab_data.data.every(item => item && item.id !== undefined);
            });
        }
    },
    data() {
        return {
            data_for_tab: [],
            articles: [],
            article_loading: true,
            current_article_category: 'mount_route',
        }
    },
    mounted() {
        this.current_article_category = this.$route.params.article_category || 'mount_route'
        this.get_articles()
    },
    watch: {
        table_data(newVal, oldVal) {
            const safeData = this.safeDataForTab;
            if (safeData.length > 0 && !this.tab_num) {
                this.tab_num = safeData[0].id;
                this.currentPage = 1;
            }
        },
        '$route' (to, from) {
            this.current_article_category = this.$route.params.article_category || 'mount_route'
            this.data_for_tab = []
            this.article_loading = true
            this.get_articles()
            window.scrollTo(0,0)
        }
    },
    methods: {
        get_articles(filt_id = 'all'){
            if (filt_id === 'all' || filt_id === 'All') {
                this.get_unfilted_articles()
            }else{
                this.get_filtred_articles(filt_id)
            }
        },
        validateData(rawData, idPath = 'id') {
    if (!rawData) return [];
        
            if (!rawData || !Array.isArray(rawData)) {
                console.warn('validateData: rawData is not array:', rawData);
                return [];
            }
            const validData = rawData
                .filter(item => {
                    if (!item || typeof item !== 'object') return false;
                    const hasId = item[idPath] !== undefined || item.global_data?.id !== undefined;
                    if (!hasId) console.warn('validateData: item missing id:', item);
                    return hasId;
                })
                .map(item => {
                    if (item.global_data && item.global_data.id !== undefined) {
                        item.id = item.global_data.id;
                    }
                    return item;
                })
                .filter(item => item.id !== undefined);
            
            if (validData.length === 0) {
                console.warn(`validateData: No valid items found from ${rawData.length} items`);
            }
            return validData;
        },
        get_regions(category){
            if(category == 'outdoor'){
                axios.get("/get_region/get_all_outdoor_regions").then(response => {
                    const validData = this.validateData(response.data)
                    this.data_for_tab.push({
                        id: 2,
                        table_name: 'Regions', 
                        add_action: {
                            action: 'route',
                            link: 'region_add', 
                            class: 'btn btn-primary'
                        },
                        tab_data: {
                            data: validData, 
                            tab: {
                                head: ['ID','Name','Edit','Delite'],
                                body: [
                                    ['data', ['id']],
                                    ['data', ['us_name']],
                                    ['action_router', 'region_edit', 'btn btn-primary', 'Edit'],
                                    ['action_fun_id', 'del_region', 'btn btn-danger', 'Del'],
                                ],
                                perm: [
                                    ['no'],
                                    ['no'],
                                    ['spot_region', 'edit'],
                                    ['spot_region', 'del'],
                                ]
                            }
                        },
                    })
                    if (this.data_for_tab[0] && Array.isArray(this.data_for_tab)) {
                        this.data_for_tab[0].filter_data = {
                            title: 'Filter by regions',
                            data: validData,
                            action_fun_id: 'filtr_outdoors',
                            array_key: 'us_name'
                        }
                    }
                }).catch(error => console.log(error))
            }
        },
        get_ice_sectors(category){
            if(category == 'ice'){
                axios.get("/get_sector/get_sectors_by_article_category/ice/").then(response => {
                    const validData = this.validateData(response.data)
                    this.data_for_tab.push({
                        id: 2,
                        table_name: 'Ice sectors', 
                        add_action: {
                            action: 'url',
                            link: '../sector/add/ice',
                            class: 'btn btn-primary'
                        },
                        tab_data: {
                            data: validData, 
                            tab: {
                                head: ['ID','Name','Public','Edit routes','Edit','Delite'],
                                body: [
                                    ['data', ['id']],
                                    ['data', ['name']],
                                    ['data', ['published'], 'bool'],
                                    ['action_fun_id', 'sector_modal', 'btn btn-success', '<i aria-hidden="true" class="fa fa-list-ol"></i>'],
['action_router', 'articleEdit', 'btn btn-primary', '<i aria-hidden="true" class="fa fa-pencil"></i>'],
                                    ['action_fun_id', 'del_sector', 'btn btn-danger', '<i aria-hidden="true" class="fa fa-trash"></i>'],
                                ],
                                perm: [
                                    ['no'],
                                    ['no'],
                                    ['no'],
                                    ['ice_sector', 'edit'],
                                    ['ice_sector', 'edit'],
                                    ['ice_sector', 'del'],
                                ]
                            }
                        },
                    })
                    this.get_ice_routes()
                }).catch(error => console.log(error))
            }
        },
        get_ice_routes(){
            axios.get("/get_route/get_routes_by_category_array", { params: { categories: ['ice climbing', 'dry tooling'] } })
                .then(response => {
                    const validData = this.validateData(response.data)
                    this.data_for_tab.push({
                        id: 3,
                        table_name: 'Ice routes', 
                        add_action: {
                            action: 'url',
                            link: '../route/add/ice', 
                            class: 'btn btn-primary'
                        },
                        tab_data: {
                            data: validData, 
                            tab: {
                                head: ['ID','Name','Grade','Height','Bolts','Edit','Delite'],
                                body: [
                                    ['data', ['id']],
                                    ['data', ['name']],
                                    ['data', ['grade'], ['or_grade']],
                                    ['data', ['height']],
                                    ['data', ['bolts']],
                                    ['action_router', 'routeEdit', 'btn btn-primary', '<i aria-hidden="true" class="fa fa-pencil"></i>'],
                                    ['action_fun_id', 'del_route', 'btn btn-danger', '<i aria-hidden="true" class="fa fa-trash"></i>'],
                                ],
                                perm: [
                                    ['no'],
                                    ['no'],
                                    ['no'],
                                    ['no'],
                                    ['no'],
                                    ['article', 'edit'],
                                    ['article', 'del'],
                                ]
                            }
                        },
                    })
                }).catch(error => console.log(error))
        },
        get_filtred_mount_routes(mount_id){
            axios.get("/set_mount/get_filtred_mount_route_for_admin/" + mount_id)
                .then(response => {
                    const validData = this.validateData(response.data, null) // no idPath for nested
                    if (this.data_for_tab[0]?.tab_data) {
                        this.data_for_tab[0].tab_data.data = validData
                    }
                }).catch(error => {
                    console.error('Error fetching filtered mount routes:', error)
                    if (this.data_for_tab[0]?.tab_data) this.data_for_tab[0].tab_data.data = []
                })
        },
        get_filtred_articles(id){
            axios.get("/set_outdoor/get_filtred_outdoor_spots_for_admin/"+id, {category: this.$route.params.article_category})
                .then(response => {
                    const validData = this.validateData(response.data)
                    if (this.data_for_tab[0]?.tab_data) {
                        this.data_for_tab[0].tab_data.data = validData
                    }
                }).catch(error => {
                    console.error('Error fetching filtered articles:', error)
                    if (this.data_for_tab[0]?.tab_data) this.data_for_tab[0].tab_data.data = []
                }).finally(() => this.article_loading = false)
        },
        get_unfilted_articles(){
            this.article_loading = true
            axios.get("/get_article/get_category_articles/"+this.$route.params.article_category)
                .then(response => {
                    const rawData = Array.isArray(response.data) ? response.data : []
                    const validData = rawData
                        .filter(item => item && typeof item === 'object')
                        .map(item => {
                            if (item.global_data && item.global_data.id !== undefined) {
                                item.id = item.global_data.id
                            } else if (item.id === undefined) {
                                console.warn('Article missing id:', item)
                                return null
                            }
                            return item
                        }).filter(item => item !== null && item.id !== undefined)
                    if (validData.length === 0) {
                        console.warn(`No valid articles found for category: ${this.$route.params.article_category}`)
                    }
                    this.data_for_tab = [{
                        id: 1,
                        table_name: this.$route.params.article_category,
                        list_page: process.env.MIX_BASE_URL_SSH + '/' + this.$route.params.article_category,
                        add_action: {
                            action: 'route',
                            link: 'articleAdd',
                            class: 'btn btn-primary'
                        },
                        tab_data: {
                            data: validData,
                            tab: {
                                head: ['ID','Title','Public','Edit','Delite'],
                                body: [
                                    ['data', ['id']],
                                    ['data_action_id', ['url_title'], 'quick_wiev_action'],
                                    ['data', ['published'], 'bool'],
                                    ['action_router', 'articleEdit', 'btn btn-primary', '<i aria-hidden="true" class="fa fa-pencil"></i>'],
                                    ['action_fun_id', 'del_article', 'btn btn-danger', '<i aria-hidden="true" class="fa fa-trash"></i>'],
                                ],
                                perm: [
                                    ['no'],
                                    ['no'],
                                    ['no'],
                                    ['article', 'edit'],
                                    ['article', 'del'],
                                ]
                            }
                        }
                    }]
                    if(this.$route.params.article_category == 'outdoor'){
                        this.get_regions(this.$route.params.article_category)
                        if (this.data_for_tab[0] && this.data_for_tab[0].tab_data?.data?.length > 0) {
                            this.data_for_tab[0].tab_data.tab.head.splice(3, 0, 'Sectors')
                            this.data_for_tab[0].tab_data.tab.body.splice(3, 0, ['action_fun_id', 'show_spot_sectors_modal', 'btn btn-success', '<i aria-hidden="true" class="fa fa-list-ol"></i>'])
                            this.data_for_tab[0].tab_data.tab.perm.splice(3, 0, ['article', 'edit'])
                        }
                    }
                    if(this.$route.params.article_category == 'ice'){
                        this.get_ice_sectors(this.$route.params.article_category)
                    }
                    if(this.$route.params.article_category == 'mount_route'){
                        this.get_mounts()
                    }
                }).catch(error => {
                    console.error('Error loading articles:', error)
                    this.data_for_tab = []
                }).finally(() => {
                    this.article_loading = false
                })
        },
        get_mounts(){
            axios.get("/get_mount/get_all_mount").then(response => {
                const validData = this.validateData(response.data, null)
                this.data_for_tab.push({
                    id: 2,
                    table_name: 'Mount Masives',
                    add_action: {
                        action: 'route',
                        link: 'mount_massive_add',
                        class: 'btn btn-primary'
                    },
                    tab_data: {
                        data: validData,
                        tab: {
                            head: ['ID','Name','Edit','Delite'],
                            body: [
                                ['data', ['id']],
                                ['data', ['locale_data', 'title']],
                                ['action_router', 'mount_massive_edit', 'btn btn-primary', 'Edit', ['id']],
                                ['action_fun_id', 'del_mount_massive', 'btn btn-danger', 'Del', ['id']],
                            ],
                            perm: [
                                ['no'],
                                ['no'],
                                ['mount_massive', 'edit'],
                                ['mount_massive', 'del'],
                            ]
                        }
                    }
                })
                if (this.data_for_tab[0] && Array.isArray(this.data_for_tab)) {
                    this.data_for_tab[0].filter_data = {
                        title: 'Filter by Mount Masive',
                        data: validData,
                        action_fun_id: 'filtr_outdoors',
                        array_key: 'locale_data.title',
                        id_key: 'id'
                    }
                }
            }).catch(error => console.log(error))
        },
        show_sector_model(sector_id){
            this.$refs.sector_modal.show_sector_modal(sector_id)
        },
        del_article(id){
            if(confirm('Are you sure you want to delete it?')){
                axios.post('/set_article/del_article/'+id, {_method: 'DELETE'}).then(() => this.get_articles()).catch(error => console.error('Delete article error:', error))
            }
        },
        del_mount_massive(id){
            if(confirm('Are you sure you want to delete it?')){
                axios.post('/set_mount/del_mount_massive/'+id, {_method: 'DELETE'}).then(() => this.get_articles()).catch(error => console.log(error))
            }
        },
        del_region(id){
            if(confirm('Are you sure you want to delete it?')){
                axios.post('/set_region/del_region/'+id, {id, _method: 'delete'}).then(() => this.get_articles()).catch(error => console.log(error))
            }
        },
        del_route(id){
            if(confirm('Are you sure you want to delete it?')){
                axios.post('/set_route/del_route/'+id, {id, _method: 'DELETE'}).then(() => this.get_articles()).catch(error => console.log(error))
            }
        },
        del_sector(id){
            if(confirm('Are you sure you want to delete it?')){
                axios.post('/set_sector/del_sector/'+id, {_method: 'DELETE'}).then(() => this.get_articles()).catch(error => console.log(error))
            }
        },
        filtr_outdoors(event){
            if(event != 0){
                if(this.current_article_category === 'mount_route'){
                    this.get_filtred_mount_routes(event)
                } else {
                    this.get_filtred_articles(event)
                }
            } else {
                this.get_unfilted_articles()
            }
        },
        show_spot_sectors_modal(article_id){
            this.$refs.show_spot_sectors_modal.show_spot_sectors_modal(article_id)
        },
        quick_wiev_action(article_id){
            let article = null
            this.data_for_tab.forEach(tab => {
                if (tab?.tab_data?.data) {
                    const found = tab.tab_data.data.find(item => item?.id === article_id)
                    if (found?.id !== undefined) {
                        article = found
                    }
                }
            })
            if (article) {
                this.$refs.quick_view_modal.show_quick_view_modal(article, this.current_article_category)
            } else {
                console.warn(`Article not found for ID: ${article_id}`)
            }
        }
    }
}
</script>

<style>
</style>
