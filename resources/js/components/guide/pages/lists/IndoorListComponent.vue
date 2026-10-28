<template>
    <div class="h-recent-work">
        <div class="container">

            <section class="cg-page-head cg-page-head--list cg-art-holds">
                <span class="cg-page-head__icon"><i class="fa fa-building-o" aria-hidden="true"></i></span>
                <div class="cg-page-head__text">
                    <h1>{{ $t('guide.title.indoor climbing')}}</h1>
                    <div class="cg-page-head__lead">{{this.$siteData.data.indoor_description}}</div>
                </div>
            </section>

            <favoriteArticlesStrip category="indoor" />
            <recentlyViewed category="indoor" />

            <div class="row" v-if="indoors.length > 0">
                <div class="container articles_filter_bar indoor_filter_bar">
                    <div class="col-md-4 col-sm-4" v-if="indoor_regions.length > 0">
                        <label>{{ $t('guide.article.indoor_filter.region') }}</label>
                        <select class="form-control" v-model="filter_region" @change="update_url()">
                            <option value="All">{{ $t('all') }}</option>
                            <option v-for="region in indoor_regions" :key="region.id" :value="region.id">{{ region.name }}</option>
                        </select>
                    </div>
                    <div class="col-md-4 col-sm-4">
                        <label>{{ $t('guide.article.indoor_filter.status') }}</label>
                        <select class="form-control" v-model="filter_status" @change="update_url()">
                            <option value="all">{{ $t('guide.article.indoor_filter.status_all') }}</option>
                            <option value="open_now">{{ $t('guide.article.indoor_filter.status_open_now') }}</option>
                            <option value="working">{{ $t('guide.article.indoor_filter.status_working') }}</option>
                        </select>
                    </div>
                    <div class="col-md-4 col-sm-4">
                        <label>{{ $t('guide.article.indoor_filter.sort') }}</label>
                        <select class="form-control" v-model="sort_by" @change="update_url()">
                            <option value="newest">{{ $t('guide.article.indoor_filter.sort_newest') }}</option>
                            <option value="name">{{ $t('guide.article.indoor_filter.sort_name') }}</option>
                            <option value="open_now">{{ $t('guide.article.indoor_filter.sort_open_now') }}</option>
                            <option value="price">{{ $t('guide.article.indoor_filter.sort_price') }}</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Grouping toggle (only while all regions are shown), same as on the outdoor page -->
            <div class="row indoor_group_bar" v-if="filter_region === 'All' && indoor_regions.length > 0">
                <div class="btn-group float-end" role="group" aria-label="Group Mode">
                    <button type="button" class="btn btn-sm" :class="group_mode === 'grouped' ? 'btn-success' : 'btn-secondary'" @click="set_group_mode('grouped')">
                        <i class="fa fa-folder"></i> {{ $t('guide.group.by_region') }}
                    </button>
                    <button type="button" class="btn btn-sm" :class="group_mode === 'flat' ? 'btn-success' : 'btn-secondary'" @click="set_group_mode('flat')">
                        <i class="fa fa-list"></i> {{ $t('guide.group.flat') }}
                    </button>
                </div>
            </div>

            <region-list-header
                v-if="selected_region"
                :title="selected_region.name"
                :description="selected_region.text"
            />

            <listSearchBar v-model="search" :count="search.trim() ? filtered_indoors.length : null" :placeholder="$t('guide.list_search.indoor')" />

            <div v-if="indoor_article_loading">
                <!--  -->
            </div>
            <div class="row" v-else-if="filtered_indoors.length > 0">
                <div v-if="filter_region === 'All' && group_mode === 'grouped' && indoor_regions.length > 0">
                    <div v-for="group in grouped_indoors" :key="group.id">
                        <region-list-header
                            :title="group.name"
                            :description="group.text"
                        />
                        <indoorCard
                            v-for="indoor in group.indoors"
                            :key='indoor.global_data.id'
                            :indoor="indoor"
                        />
                    </div>
                </div>
                <div v-else>
                    <indoorCard
                        v-for="indoor in filtered_indoors"
                        :key='indoor.global_data.id'
                        :indoor="indoor"
                    />
                </div>
            </div>
            <div v-else>
                <emptyPageComponent />
            </div>
        </div>

        <metaData
            :title = "$t('guide.meta.indoor climbing')"
            :description = "this.$siteData.data.indoor_description"
            :image = "'/public/images/meta_images/indoor.jpg'"
        />
    </div>
</template>

<script>
    import favoriteArticlesStrip from '../../items/FavoriteArticlesStripComponent.vue'
    import recentlyViewed from '../../items/RecentlyViewedComponent.vue'
    import indoorCard from '../../items/cards/IndoorCardComponent'
    // import emptyPageComponent from '../items/EmptyPageComponent'
    import emptyPageComponent from '../../../global_components/EmptyPageComponent'
    import listSearchBar from '../../items/ListSearchBarComponent.vue'
    import { search_items, only_search_changed } from '../../../../services/guide/listSearch.js'
    import RegionListHeader from '../../items/RegionListHeaderComponent.vue'


    import metaData from '../../items/MetaDataComponent'

    import axios_mixin from '../../../../mixins/axios_mixin'

    // Statuses that mean the gym can't be visited right now
    const CLOSED_STATUSES = ['temporarily_closed', 'sport_session_closed', 'coming_soon']

    // same rule as the card and the gym page (per-day hours, after-midnight closing)
    import { is_open_now } from '../../../../services/guide/gymHours.js'

    export default {
        mixins: [
            axios_mixin
        ],
        data: function () {
            return {
                indoors: [],
                indoor_article_loading: true,
                search: '',
                regions: [],
                filter_region: 'All',
                filter_status: 'all',
                sort_by: 'newest',
                group_mode: 'grouped',
                now: new Date(),
                timer: null,
            };
        },
        computed: {
            // Only regions that have at least one indoor gym
            indoor_regions() {
                const ids = new Set(this.indoors.flatMap(indoor => (indoor.global_data.outdoor_region || []).map(region => region.id)))
                return this.regions.filter(region => ids.has(region.id))
            },
            selected_region() {
                if (this.filter_region === 'All') return null
                return this.regions.find(region => region.id === this.filter_region) || null
            },
            filtered_indoors() {
                let list = this.indoors

                if (this.filter_region !== 'All') {
                    list = list.filter(indoor => (indoor.global_data.outdoor_region || []).some(region => region.id === this.filter_region))
                }
                if (this.filter_status === 'open_now') {
                    list = list.filter(indoor => is_open_now(indoor.global_data, this.now))
                }
                else if (this.filter_status === 'working') {
                    list = list.filter(indoor => !CLOSED_STATUSES.includes(indoor.global_data.work_status))
                }

                return this.sort_indoors(search_items(list, this.search))
            },
            // Regions in the region list order, gyms without a region last
            grouped_indoors() {
                const groups = this.indoor_regions.map(region => ({
                    id: region.id,
                    name: region.name,
                    text: region.text,
                    indoors: this.filtered_indoors.filter(indoor => (indoor.global_data.outdoor_region || []).some(r => r.id === region.id)),
                }))
                const without_region = this.filtered_indoors.filter(indoor => !(indoor.global_data.outdoor_region || []).length)
                if (without_region.length) {
                    groups.push({ id: 'other', name: this.$t('guide.article.indoor_filter.other_region'), text: '', indoors: without_region })
                }
                return groups.filter(group => group.indoors.length > 0)
            },
        },
        components: {
            favoriteArticlesStrip,
            recentlyViewed,
            indoorCard,
            emptyPageComponent,
            RegionListHeader,
            listSearchBar,

            metaData
        },
        watch: {
            '$route' (to, from) {
                if (only_search_changed(to, from)) return   // typing in the search box
                this.load_filters_from_url()
                // Filters only change the query — no refetch needed
                if (to.path === from.path) return
                this.get_indoors(),
                window.scrollTo(0,0)
            }
        },
        mounted() {
            this.load_filters_from_url()
            this.get_indoors()
            this.get_regions()
            // keep "open now" filter/sort current while the page stays open
            this.timer = setInterval(() => { this.now = new Date() }, 60000)
        },
        beforeUnmount() {
            clearInterval(this.timer)
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
                this.filter_status = ['open_now', 'working'].includes(query.status) ? query.status : 'all'
                this.sort_by = ['name', 'open_now', 'price'].includes(query.sort) ? query.sort : 'newest'
                this.group_mode = query.group === 'flat' ? 'flat' : 'grouped'
            },
            update_url() {
                const query = {}
                if (this.filter_region !== 'All') query.region = this.filter_region
                if (this.filter_status !== 'all') query.status = this.filter_status
                if (this.sort_by !== 'newest') query.sort = this.sort_by
                if (this.group_mode !== 'grouped') query.group = this.group_mode
                if (this.search && this.search.trim()) query.q = this.search.trim()   // keep the search box's term
                this.$router.replace({ query }).catch(() => {})
            },
            set_group_mode(mode) {
                this.group_mode = mode
                this.update_url()
            },
            sort_indoors(list) {
                const sorted = [...list]
                // Closed / coming soon gyms always go after the working ones (except "newest")
                const closed_rank = indoor => CLOSED_STATUSES.includes(indoor.global_data.work_status) ? 1 : 0

                if (this.sort_by === 'name') {
                    sorted.sort((a, b) => closed_rank(a) - closed_rank(b) || String(a.locale_data.title).localeCompare(String(b.locale_data.title)))
                }
                else if (this.sort_by === 'open_now') {
                    const open_rank = indoor => is_open_now(indoor.global_data, this.now) ? 0 : 1 + closed_rank(indoor)
                    sorted.sort((a, b) => open_rank(a) - open_rank(b))
                }
                else if (this.sort_by === 'price') {
                    // Gyms without a price go last
                    const price = indoor => parseFloat(indoor.global_data.price_from) || Infinity
                    sorted.sort((a, b) => closed_rank(a) - closed_rank(b) || price(a) - price(b))
                }
                // "newest" keeps the API order (latest id first)
                return sorted
            },
            get_indoors(){
                // gyms + their hours per weekday (article_working_hours) in parallel
                const hours = axios.get('/get_gym_hours/all').then(r => r.data || {}).catch(() => ({}))
                this.get_articles('indoor', localStorage.getItem('lang'),
                    (data) => {
                        this.waiting_hours = true
                        hours.then(weeks => {
                            (data || []).forEach(gym => {
                                if (gym && gym.global_data && weeks[gym.global_data.id]) gym.global_data.weekly_hours = weeks[gym.global_data.id]
                            })
                            this.indoors = data;
                            this.waiting_hours = false
                            this.indoor_article_loading = false;
                        })
                    },
                    (error) => {
                        console.error('Error fetching articles:', error);
                    },
                    () => {
                        // success: cleared once the hours are merged in (above)
                        if (!this.waiting_hours) this.indoor_article_loading = false;
                    }
                );
            }
        }
    }
</script>

<style scoped>
    .indoor_filter_bar label {
        display: block;
        font-weight: bold;
        margin-bottom: 4px;
    }

    .indoor_filter_bar > div {
        margin-bottom: 10px;
    }

    .indoor_group_bar {
        justify-content: flex-end;
        margin: 0 0 1em;
    }
</style>
