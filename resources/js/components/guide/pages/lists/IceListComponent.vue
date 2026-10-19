<template>
    <div class="h-recent-work" id="other">
        <div class="container">

            <h1 class='index_h2'>{{ $t('guide.title.ice climbing')}}</h1>

            <div class="bar"><i class="fa fa-dribbble"></i></div>
            
            <h2 class="article_list_short_description">
                {{this.$siteData.data.ice_description}}
            </h2>

            <sectorQuantyt :route_categories_prop="route_categories"/>

            <div class="row otdoor_buttoms">
                <div class="col-md-6">
                    <routesAutersModal :route_categories_prop="route_categories"/>
                </div>

                <div class="col-md-6">
                    <mostPopularRoutesModal :route_categories_prop="route_categories" :default_route_type_prop="'ice'"/>
                </div>
            </div>

            <div class="row" v-if="ice_regions.length > 0">
                <div class="container articles_filter_bar">
                    <div class="col-md-6 col-sm-6">
                        {{ $t('guide.article.region_filtr') }}
                    </div>
                    <div class="col-md-6 col-sm-6">
                        <select class="form-control" v-model="filter_region" @change="update_url()">
                            <option value="All">{{ $t('all') }}</option>
                            <option v-for="region in ice_regions" :key="region.id" :value="region.id">{{ region.name }}</option>
                        </select>
                    </div>
                </div>
            </div>

            <region-list-header
                v-if="selected_region"
                :title="selected_region.name"
                :description="selected_region.text"
            />

            <!-- View Controls -->
            <div class="row view_controls_bar">
                <div class="col-md-6 text-left pull-left">
                    <div class="btn-group" role="group" aria-label="View Mode">
                        <button 
                            type="button" 
                            class="btn" 
                            :class="{'btn-primary active': viewMode === 'grid', 'btn-default': viewMode !== 'grid'}"
                            @click="set_mode('viewMode', 'grid')"
                        >
                            <i class="fa fa-th-large"></i> {{ $t('guide.view.grid') || 'Grid' }}
                        </button>
                        <button 
                            type="button" 
                            class="btn" 
                            :class="{'btn-primary active': viewMode === 'list', 'btn-default': viewMode !== 'list'}"
                            @click="set_mode('viewMode', 'list')"
                        >
                            <i class="fa fa-list-ul"></i> {{ $t('guide.view.list') || 'List' }}
                        </button>
                    </div>
                </div>
                <div class="col-md-6 text-right pull-right" v-if="filter_region === 'All' && ice_regions.length > 0">
                    <div class="btn-group pull-right" role="group" aria-label="Group Mode">
                        <button 
                            type="button" 
                            class="btn" 
                            :class="{'btn-primary active': group_mode === 'grouped', 'btn-default': group_mode !== 'grouped'}"
                            @click="set_mode('group_mode', 'grouped')"
                        >
                            <i class="fa fa-folder"></i> {{ $t('guide.group.by_region') }}
                        </button>
                        <button 
                            type="button" 
                            class="btn" 
                            :class="{'btn-primary active': group_mode === 'flat', 'btn-default': group_mode !== 'flat'}"
                            @click="set_mode('group_mode', 'flat')"
                        >
                            <i class="fa fa-list"></i> {{ $t('guide.group.flat') }}
                        </button>
                    </div>
                </div>
            </div>

            <div v-if="indoor_article_loading">
                <!--  -->
            </div>
            <div v-else>
                <!-- Grouped by region (only while all regions are shown), same as on the outdoor page -->
                <div v-if="is_grouped && filtered_ices.length > 0">
                    <div v-for="group in grouped_ices" :key="group.id">
                        <region-list-header
                            :title="group.name"
                            :description="group.text"
                        />
                        <div class="article_card_container" :class="{'list-view': viewMode === 'list'}">
                            <template v-if="viewMode === 'grid'">
                                <articleComponent 
                                    v-for="ice in group.ices"
                                    :key='ice.id'
                                    :image_dir="'images/ice_img/'"
                                    :article="ice"
                                    :route="'ice/'+ice.global_data.url_title"
                                />
                            </template>
                            <template v-else>
                                <articleHorithontalCardComponent
                                    v-for="ice in group.ices"
                                    :key="ice.id"
                                    :image_dir="'images/ice_img/'"
                                    :article="ice"
                                    :route="'ice/'+ice.global_data.url_title"
                                />
                            </template>
                        </div>
                    </div>
                </div>
                <div v-else-if="filtered_ices.length > 0" class="article_card_container" :class="{'list-view': viewMode === 'list'}">
                    <!-- Grid View -->
                    <template v-if="viewMode === 'grid'">
                        <articleComponent 
                            v-for="ice in filtered_ices"
                            :key='ice.id'
                            :image_dir="'images/ice_img/'"
                            :article="ice"
                            :route="'ice/'+ice.global_data.url_title"
                        />
                    </template>
                    <!-- List View -->
                    <template v-else>
                        <articleHorithontalCardComponent
                            v-for="ice in filtered_ices"
                            :key="ice.id"
                            :image_dir="'images/ice_img/'"
                            :article="ice"
                            :route="'ice/'+ice.global_data.url_title"
                        />
                    </template>
                </div>
                <div v-else>
                    <emptyPageComponent />
                </div>
            </div>
        </div>
        
        <metaData 
            :title = "$t('guide.meta.ice climbing')"
            :description = "this.$siteData.data.ice_description"
            :image = "'/public/images/meta_img/ice.jpg'"
        />
    </div>
</template>

<script>
    import articleComponent from '../../items/cards/ArticleCardComponent'
    import articleHorithontalCardComponent from '../../items/cards/ArticleHorithontalCardComponent'
    // import emptyPageComponent from '../items/EmptyPageComponent'
    import emptyPageComponent from '../../../global_components/EmptyPageComponent'
    import RegionListHeader from '../../items/RegionListHeaderComponent.vue'

    import metaData from '../../items/MetaDataComponent'

    import axios_mixin from '../../../../mixins/axios_mixin'

    import sectorQuantyt from '../../items/climbing_routes/SectorsQuantytyComponent'
    import routesAutersModal from '../../items/climbing_routes/items/modals/statistic_modals/RoutesAutersListModal.vue'
    import mostPopularRoutesModal from '../../items/climbing_routes/items/modals/statistic_modals/MostPopularRoutesModal.vue'

    export default {
        mixins: [
            axios_mixin
        ],
        data: function () {
            return {
                ices: [],
                indoor_article_loading: true,

                viewMode: 'grid', // 'grid' or 'list'

                regions: [],
                filter_region: 'All',
                group_mode: 'grouped', // 'grouped' or 'flat'

                route_categories: [
                    { value: 'dry', label: 'guide.sector.dry' },
                    { value: 'ice', label: 'guide.sector.ice' }
                ]
            };
        },
        computed: {
            // Only regions that have at least one ice spot
            ice_regions() {
                const ids = new Set(this.ices.flatMap(ice => (ice.global_data.outdoor_region || []).map(region => region.id)))
                return this.regions.filter(region => ids.has(region.id))
            },
            selected_region() {
                if (this.filter_region === 'All') return null
                return this.regions.find(region => region.id === this.filter_region) || null
            },
            filtered_ices() {
                if (this.filter_region === 'All') return this.ices
                return this.ices.filter(ice => (ice.global_data.outdoor_region || []).some(region => region.id === this.filter_region))
            },
            is_grouped() {
                return this.filter_region === 'All' && this.group_mode === 'grouped' && this.ice_regions.length > 0
            },
            // Regions in the region list order, spots without a region last
            grouped_ices() {
                const groups = this.ice_regions.map(region => ({
                    id: region.id,
                    name: region.name,
                    text: region.text,
                    ices: this.ices.filter(ice => (ice.global_data.outdoor_region || []).some(r => r.id === region.id)),
                }))
                const without_region = this.ices.filter(ice => !(ice.global_data.outdoor_region || []).length)
                if (without_region.length) {
                    groups.push({ id: 'other', name: this.$t('guide.article.indoor_filter.other_region'), text: '', ices: without_region })
                }
                return groups
            },
        },
        components: {
            RegionListHeader,
            articleComponent,
            articleHorithontalCardComponent,
            emptyPageComponent,

            metaData,
            
            sectorQuantyt,
            routesAutersModal,
            mostPopularRoutesModal
        },
        mounted() {
            this.load_filters_from_url()
            this.get_ices()
            this.get_regions()
        },
        watch: {
            '$route' (to, from) {
                this.load_filters_from_url()
                // Filters only change the query — no refetch needed
                if (to.path === from.path) return
                this.get_ices(),
                window.scrollTo(0,0)
            }
        },
        methods: {
            get_regions() {
                axios
                .get('/get_region/get_local_regions/' + localStorage.getItem('lang'))
                .then(response => {
                    this.regions = response.data
                })
                .catch(error => console.log(error))
            },
            load_filters_from_url() {
                const query = this.$route.query
                this.filter_region = query.region ? Number(query.region) : 'All'
                this.viewMode = query.view === 'list' ? 'list' : 'grid'
                this.group_mode = query.group === 'flat' ? 'flat' : 'grouped'
            },
            update_url() {
                const query = {}
                if (this.filter_region !== 'All') query.region = this.filter_region
                if (this.viewMode !== 'grid') query.view = this.viewMode
                if (this.group_mode !== 'grouped') query.group = this.group_mode
                this.$router.replace({ query }).catch(() => {})
            },
            set_mode(key, value) {
                this[key] = value
                this.update_url()
            },
            get_ices(){
                this.get_articles('ice', localStorage.getItem('lang'),
                    (data) => {
                        this.ices = data;
                    },
                    (error) => {
                        console.error('Error fetching articles:', error);
                    },
                    () => {
                        this.indoor_article_loading = false;
                    }
                );
            }
        }
    }
</script>

<style>
    .otdoor_buttoms{
        margin: 1em 0;
    }
</style>