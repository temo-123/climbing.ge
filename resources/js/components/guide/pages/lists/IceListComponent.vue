<template>
    <div class="h-recent-work" id="other">
        <div class="container">

            <section class="cg-page-head cg-page-head--list cg-art-iceaxe">
                <span class="cg-page-head__icon"><i class="fa fa-snowflake-o" aria-hidden="true"></i></span>
                <div class="cg-page-head__text">
                    <h1>{{ $t('guide.title.ice climbing')}}</h1>
                    <div class="cg-page-head__lead">{{this.$siteData.data.ice_description}}</div>
                </div>
                <!-- the ice the tool (page-head sketch) swings into; chips burst on each hit -->
                <svg class="cg-page-head__fx cg-ice-fx" viewBox="0 0 300 180" aria-hidden="true">
                    <!-- frozen icefall: translucent ice, its face, cracks and drips -->
                    <path d="M-40 0H96c-10 26 6 38-4 64s8 46-4 72-2 30-10 44H-40z" fill="#dff3ff" fill-opacity=".2" />
                    <path d="M-40 0H70c-8 30 6 50-4 90s6 50-4 90H-40z" fill="#dff3ff" fill-opacity=".12" />
                    <g fill="none" stroke="#fff" stroke-linecap="round" stroke-linejoin="round">
                        <path class="cg-ice-fx__wall" d="M96 0c-10 26 6 38-4 64s8 46-4 72-2 30-10 44" stroke-width="2.6" stroke-opacity=".9" />
                        <path d="M96 0c-10 26 6 38-4 64s8 46-4 72-2 30-10 44" stroke-width="1.2" stroke-opacity=".35" transform="translate(-4 2)" />
                        <path d="M80 0c-6 20 4 30-2 52M64 6c-4 14 2 22-2 36M76 98c-4 14 2 26-2 40" stroke-width="1.4" stroke-opacity=".45" />
                        <path d="M84 40l-10 8 6 6-8 10M78 116l-8 6 5 8" stroke-width="1" stroke-opacity=".5" />
                        <path d="M98 22l-6 12 1-14M93 96l-6 14 2-16M88 150l-5 12 1-14M84 0v14l-3-14" stroke-width="1.6" stroke-opacity=".7" />
                    </g>
                    <!-- chips that burst off the pick placement -->
                    <g class="cg-ice-fx__chips" fill="#eefaff" stroke="#fff" stroke-width=".5">
                        <path d="M-3 -2l5 -2 1 5z" /><path d="M-2 0l4 2-4 4z" /><path d="M0 -3l-2 -4 5 1z" /><path d="M-2 -2l5 0-1 5z" />
                        <circle r="2.2" /><circle r="1.5" /><circle r="2.6" /><circle r="1.2" /><circle r="1.8" /><circle r="1.3" />
                    </g>
                </svg>
            </section>

            <favoriteArticlesStrip category="ice" />
            <recentlyViewed category="ice" />

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
                <div class="col-md-6 text-start float-start">
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
                <div class="col-md-6 text-end float-end" v-if="filter_region === 'All' && ice_regions.length > 0">
                    <div class="btn-group float-end" role="group" aria-label="Group Mode">
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

            <listSearchBar v-model="search" :count="search.trim() ? filtered_ices.length : null" :placeholder="$t('guide.list_search.ice')" />

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
    import favoriteArticlesStrip from '../../items/FavoriteArticlesStripComponent.vue'
    import recentlyViewed from '../../items/RecentlyViewedComponent.vue'
    import articleComponent from '../../items/cards/ArticleCardComponent'
    import articleHorithontalCardComponent from '../../items/cards/ArticleHorithontalCardComponent'
    // import emptyPageComponent from '../items/EmptyPageComponent'
    import emptyPageComponent from '../../../global_components/EmptyPageComponent'
    import listSearchBar from '../../items/ListSearchBarComponent.vue'
    import { search_items, only_search_changed } from '../../../../services/guide/listSearch.js'
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
                search: '',

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
                const list = this.filter_region === 'All' ? this.ices
                    : this.ices.filter(ice => (ice.global_data.outdoor_region || []).some(region => region.id === this.filter_region))
                return search_items(list, this.search)
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
            favoriteArticlesStrip,
            recentlyViewed,
            RegionListHeader,
            listSearchBar,
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
                if (only_search_changed(to, from)) return   // typing in the search box
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
                if (this.search && this.search.trim()) query.q = this.search.trim()   // keep the search box's term
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