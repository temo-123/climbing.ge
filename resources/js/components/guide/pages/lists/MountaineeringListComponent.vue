<template>
    <div class="h-recent-work" id="other">
        <div class="container">

            <h1 class='index_h2'>{{ $t('guide.title.mountaineering')}}</h1>

            <div class="bar"><i class="fa fa-dribbble"></i></div>

            <h2 class="article_list_short_description">
                {{this.$siteData.data.mount_description}}
            </h2>

            <div class="row" v-if="this.mounts.length > 0">
                <div class="container articles_filter_bar">
                    <div class="col-md-6">
                        <!-- Select mount System and filtred routes -->
                        {{ $t('guide.article.mount_route_filtr') }}
                    </div>
                    <div class="col-md-6" v-if="this.mounts.length > 0">
                        <select class="form-control" v-model="filter_mount" @change="on_mount_filter_change()">
                            <option value="All">{{ $t('all') }}</option>
                            <option v-for="mount in mounts" :key='mount.global_data.id' :value="mount.global_data.id">{{ mount.locale_data.title }}</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="row" v-if="filter_mount != 'All' && summits.length > 0">
                <div class="container articles_filter_bar">
                    <div class="col-md-6">
                        {{ $t('guide.article.summit_filtr') }}
                    </div>
                    <div class="col-md-6">
                        <select class="form-control" v-model="filter_summit" @change="on_summit_filter_change()">
                            <option value="All">{{ $t('all') }}</option>
                            <option v-for="summit in summits" :key="summit.id" :value="summit.id">{{ summit.title }}</option>
                        </select>
                    </div>
                </div>
            </div>

            <region-list-header
                v-if="filter_mount != 'All' && selected_mount_data?.locale_data?.title"
                :title="selected_mount_data.locale_data.title"
                :description="selected_mount_data.locale_data.text"
            >
                <template #map-button>
                    <mount-summits-map-modal
                        v-if="selected_mount_data?.global_data?.map"
                        :mount-id="selected_mount_data.global_data.id"
                        :title="selected_mount_data.locale_data.title"
                        :map="selected_mount_data.global_data.map"
                    />
                </template>
            </region-list-header>
            
            <!-- View Controls Component -->
            <view-controls-component
                :view-mode="viewMode"
                @update:viewMode="viewMode = $event"
                :group-mode="groupMode"
                @update:groupMode="groupMode = $event"
                :filter-spot="filter_mount"
            />

            <div class="row">
                <div v-if="mount_route_loading">
                    <!--  -->
                </div>
                <div v-else>
                    <!-- Grouped View -->
                    <div v-if="groupMode === 'grouped'">
                        <div v-if="this.mount_routes_by_masiv.length > 0" class="article_card_container" :class="{'list-view': viewMode === 'list'}">
                            <!-- Grid View by Mountain -->
                            <div class="row width_100" v-if="viewMode === 'grid'" v-for="masiv in mount_routes_by_masiv">
                                <div class="col-md-12">
                                    <region-list-header
                                        :title="masiv.mount ? masiv.mount.locale_data.title : 'Other'"
                                        :description="masiv.mount ? masiv.mount.locale_data.short_description : ''"
                                    >
                                        <template #map-button>
                                            <mount-summits-map-modal
                                                v-if="masiv.mount?.global_data?.map"
                                                :mount-id="masiv.mount.global_data.id"
                                                :title="masiv.mount.locale_data.title"
                                                :map="masiv.mount.global_data.map"
                                            />
                                        </template>
                                    </region-list-header>
                                </div>
                                <div class="col-md-12 cards_block">
                                    <mountCard 
                                        v-for="mount_route in masiv.mount_route"
                                        :key='mount_route.global_data.id'
                                        :mount="mount_route"
                                        :route="'mountaineering/'+mount_route.global_data.url_title"
                                    />
                                </div>
                            </div>

                            <!-- List View by Mountain -->
                            <div v-if="viewMode === 'list'" v-for="masiv in mount_routes_by_masiv">
                                <region-list-header
                                    :title="masiv.mount ? masiv.mount.locale_data.title : 'Other'"
                                    :description="masiv.mount ? masiv.mount.locale_data.short_description : ''"
                                >
                                    <template #map-button>
                                        <mount-summits-map-modal
                                            v-if="masiv.mount?.global_data?.map"
                                            :mount-id="masiv.mount.global_data.id"
                                            :title="masiv.mount.locale_data.title"
                                            :map="masiv.mount.global_data.map"
                                        />
                                    </template>
                                </region-list-header>
                                <div class="list-view-container">
                                    <mountHorithontalCard
                                        v-for="mount_route in masiv.mount_route"
                                        :key="mount_route.global_data.id"
                                        :mount="mount_route"
                                        :route="'mountaineering/'+mount_route.global_data.url_title"
                                    />
                                </div>
                            </div>
                        </div>

                        <!-- Filtered Grouped View -->
                        <div v-else-if="this.displayed_mount_routes.length > 0" class="article_card_container" :class="{'list-view': viewMode === 'list'}">
                            <div v-if="viewMode === 'grid'">
                                <mountCard
                                    v-for="mount_route in displayed_mount_routes"
                                    :key='mount_route.id'
                                    :mount="mount_route"
                                    :route="'mountaineering/'+mount_route.global_data.url_title"
                                />
                            </div>
                            <div v-if="viewMode === 'list'" class="list-view-container">
                                <mountHorithontalCard
                                    v-for="mount_route in displayed_mount_routes"
                                    :key="mount_route.id"
                                    :mount="mount_route"
                                    :route="'mountaineering/'+mount_route.global_data.url_title"
                                />
                            </div>
                        </div>
                    </div>

                    <!-- Flat View (no grouping) -->
                    <div v-else>
                        <div v-if="this.mount_routes_by_masiv.length > 0" class="article_card_container" :class="{'list-view': viewMode === 'list'}">
                            <!-- Flat Grid View -->
                            <div v-if="viewMode === 'grid'">
                                <mountCard 
                                    v-for="mount_route in getAllMountRoutes(mount_routes_by_masiv)"
                                    :key='mount_route.global_data.id'
                                    :mount="mount_route"
                                    :route="'mountaineering/'+mount_route.global_data.url_title"
                                />
                            </div>

                            <!-- Flat List View -->
                            <div v-if="viewMode === 'list'" class="list-view-container">
                                <mountHorithontalCard
                                    v-for="mount_route in getAllMountRoutes(mount_routes_by_masiv)"
                                    :key="mount_route.global_data.id"
                                    :mount="mount_route"
                                    :route="'mountaineering/'+mount_route.global_data.url_title"
                                />
                            </div>
                        </div>

                        <!-- Filtered Flat View -->
                        <div v-else-if="this.displayed_mount_routes.length > 0" class="article_card_container" :class="{'list-view': viewMode === 'list'}">
                            <div v-if="viewMode === 'grid'">
                                <mountCard
                                    v-for="mount_route in displayed_mount_routes"
                                    :key='mount_route.id'
                                    :mount="mount_route"
                                    :route="'mountaineering/'+mount_route.global_data.url_title"
                                />
                            </div>
                            <div v-if="viewMode === 'list'" class="list-view-container">
                                <mountHorithontalCard
                                    v-for="mount_route in displayed_mount_routes"
                                    :key="mount_route.id"
                                    :mount="mount_route"
                                    :route="'mountaineering/'+mount_route.global_data.url_title"
                                />
                            </div>
                        </div>
                    </div>

                    <div v-if="this.mount_routes_by_masiv.length == 0 && this.displayed_mount_routes.length == 0">
                        <emptyPageComponent />
                    </div>
                </div>
            </div>

        </div>
        
        <metaData 
            :title = "$t('guide.meta.mountaineering')"
            :description = "this.$siteData.data.mount_description"
            :image = "'/public/images/meta_img/mount.jpg'"
        />
    </div>
</template>

<script>
    import mountCard from '../../items/cards/MountCardComponent'
    import mountHorithontalCard from '../../items/cards/MountHorizontalCardComponent'
    import emptyPageComponent from '../../../global_components/EmptyPageComponent'
    

    import metaData from '../../items/MetaDataComponent'
    import viewControlsComponent from '../../items/ViewControlsComponent.vue'
    import regionListHeader from '../../items/RegionListHeaderComponent.vue'
    export default {
        data: function () {
            return {
                mounts: [],
                mount_routes: [],
                mount_routes_by_masiv: [],
                filter_mount: 'All',

                summits: [],
                filter_summit: 'All',
                summit_route_ids: null,

                selected_mount_data: [],
                mount_route_loading: false,

                viewMode: 'grid', // 'grid' or 'list'
                groupMode: 'grouped' // 'grouped' or 'flat'
            };
        },
        components: {
            mountCard,
            mountHorithontalCard,
            emptyPageComponent,

            metaData,
            viewControlsComponent,
            regionListHeader,
        },
        computed: {
            // Step 2 of the filter: narrow the already mount-filtered routes down to the selected summit
            displayed_mount_routes() {
                if (this.filter_summit === 'All' || !this.summit_route_ids) {
                    return this.mount_routes
                }
                return this.mount_routes.filter(mount_route => this.summit_route_ids.has(mount_route.global_data.id))
            }
        },
        watch: {
            '$route' (to, from) {
                window.scrollTo(0,0)
                const pathChanged = to.path !== from.path
                const mountChanged = (to.query.mount || 'All') !== this.filter_mount
                const summitChanged = (to.query.summit || 'All') !== this.filter_summit

                this.loadFiltersFromUrl()

                if (pathChanged) this.get_mounts()
                if (pathChanged || mountChanged) {
                    this.get_mountain_route_articles()
                } else if (summitChanged) {
                    this.apply_summit_filter()
                }
            },
            viewMode() { this.updateUrl() },
            groupMode() { this.updateUrl() }
        },
        mounted() {
            this.get_mounts()
            this.loadFiltersFromUrl()
            this.get_mountain_route_articles()
        },

        methods: {

            loadFiltersFromUrl(){
                const query = this.$route.query
                this.filter_mount = query.mount || 'All'
                this.filter_summit = query.summit || 'All'
                this.viewMode = query.view === 'list' ? 'list' : 'grid'
                this.groupMode = query.group === 'flat' ? 'flat' : 'grouped'
            },

            updateUrl(){
                let query = {}
                if (this.filter_mount !== 'All') query.mount = this.filter_mount
                if (this.filter_summit !== 'All') query.summit = this.filter_summit
                if (this.viewMode !== 'grid') query.view = this.viewMode
                if (this.groupMode !== 'grouped') query.group = this.groupMode
                this.$router.replace({ query }).catch(() => {})
            },

            on_mount_filter_change(){
                this.filter_summit = 'All'
                this.summit_route_ids = null
                this.updateUrl()
                this.get_mountain_route_articles()
            },

            on_summit_filter_change(){
                this.updateUrl()
                this.apply_summit_filter()
            },

            get_filtered_articles(id){
                this.mount_route_loading = true
                this.mount_routes_by_masiv = []
                axios
                .get("/get_mount_route/get_filtred_mount_routes/" + localStorage.getItem('lang') + '/' + id + '/' + 1)
                .then(response => {
                    this.mount_routes = response.data
                })
                .catch(
                )
                .finally(() => this.mount_route_loading = false)
            },

            get_unfiltered_articles(){
                this.mount_route_loading = true
                this.mount_routes = []
                axios
                .get('/get_mount_route/get_mount_routes_by_maunt/'+localStorage.getItem('lang'))
                .then(response => {
                    this.mount_routes_by_masiv = response.data
                })
                .catch(error =>{
                })
                .finally(() => this.mount_route_loading = false)
            },

            get_mountain_route_articles(){
                if (this.filter_mount === 'All') {
                    this.get_unfiltered_articles()
                    this.summits = []
                    this.summit_route_ids = null
                }
                else{
                    this.get_filtered_articles(Number(this.filter_mount))
                    this.get_selected_mount_data(Number(this.filter_mount))
                    this.get_summits_for_mount(Number(this.filter_mount))
                }

                if (this.filter_summit !== 'All') {
                    this.apply_summit_filter()
                }
            },

            get_summits_for_mount(mount_id){
                axios
                .get('/summit/list_filtered/' + mount_id)
                .then(response => {
                    this.summits = response.data
                })
                .catch(error => {
                    this.summits = []
                })
            },

            // Filters the mount-filtered routes down to only those linked to the selected summit
            apply_summit_filter(){
                if (this.filter_summit === 'All') {
                    this.summit_route_ids = null
                    return
                }
                axios
                .get('/summit/routes/' + this.filter_summit)
                .then(response => {
                    this.summit_route_ids = new Set(response.data.map(route => route.id))
                })
                .catch(error => {
                    this.summit_route_ids = null
                })
            },

            get_mounts(){
                axios
                .get('/get_mount/get_locale_mounts/'+localStorage.getItem('lang'))
                .then(response => {
                    this.mounts = response.data
                })
                .catch(error =>{
                })
            },

            get_selected_mount_data(mount_masiv_id){
                if (this.filter_mount !== 'All') {
                    this.selected_mount_data = []
                    axios
                    .get('/get_mount/'+localStorage.getItem('lang')+'/'+mount_masiv_id)
                    .then(response => {
                        this.selected_mount_data = response.data[0]
                    })
                    .catch(error =>{
                    })
                }
                else{
                    this.selected_mount_data = []
                }
            },

            // Helper method to get all mount routes from mount_routes_by_masiv for list view
            getAllMountRoutes(mountRoutesByMasiv) {
                let allRoutes = []
                if (mountRoutesByMasiv && Array.isArray(mountRoutesByMasiv)) {
                    mountRoutesByMasiv.forEach(masiv => {
                        if (masiv.mount_route && Array.isArray(masiv.mount_route)) {
                            allRoutes = allRoutes.concat(masiv.mount_route)
                        }
                    })
                }
                return allRoutes
            }
        }
    }
</script>

<style scoped>
.list-view .cards_block {
  display: block !important;
}

.list-view .cards_block > * {
  width: 100% !important;
  margin-bottom: 1rem;
}

.list-view-container {
  display: block;
}
</style>
