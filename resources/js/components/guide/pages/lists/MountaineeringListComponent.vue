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
                        <select class="form-control" v-model="filter_mount" @click="get_mountain_route_articles()">
                            <option value="All">{{ $t('all') }}</option>
                            <option v-for="mount in mounts" :key='mount.global_data.id' :value="mount.global_data.id">{{ mount.locale_data.title }}</option> 
                        </select>
                    </div>
                </div>
            </div>

            <div class="row articles_filter_bar" v-if="filter_mount != 'All'">
                <div class="col-md-12" style="text-align: center;">
                    <h4>{{selected_mount_data?.locale_data?.title}}</h4>
                    <span v-html="selected_mount_data?.locale_data?.text || ''"></span>
                </div>
            </div>
            
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
                                    <div v-if="masiv.mount" class="mountain-list-header">
                                        <h2 class="article_list_short_description">{{masiv.mount.locale_data.title}}</h2>
                                    </div>
                                    <div v-else class="mountain-list-header">
                                        <h2 class="article_list_short_description">Other</h2>
                                    </div>
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
                                <div v-if="masiv.mount" class="mountain-list-header">
                                    <h2 class="article_list_short_description">{{masiv.mount.locale_data.title}}</h2>
                                </div>
                                <div v-else class="mountain-list-header">
                                    <h2 class="article_list_short_description">Other</h2>
                                </div>
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
                        <div v-else-if="this.mount_routes.length > 0" class="article_card_container" :class="{'list-view': viewMode === 'list'}">
                            <div v-if="viewMode === 'grid'">
                                <mountCard 
                                    v-for="mount_route in mount_routes"
                                    :key='mount_route.id'
                                    :mount="mount_route"
                                    :route="'mountaineering/'+mount_route.global_data.url_title"
                                />
                            </div>
                            <div v-if="viewMode === 'list'" class="list-view-container">
                                <mountHorithontalCard
                                    v-for="mount_route in mount_routes"
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
                        <div v-else-if="this.mount_routes.length > 0" class="article_card_container" :class="{'list-view': viewMode === 'list'}">
                            <div v-if="viewMode === 'grid'">
                                <mountCard 
                                    v-for="mount_route in mount_routes"
                                    :key='mount_route.id'
                                    :mount="mount_route"
                                    :route="'mountaineering/'+mount_route.global_data.url_title"
                                />
                            </div>
                            <div v-if="viewMode === 'list'" class="list-view-container">
                                <mountHorithontalCard
                                    v-for="mount_route in mount_routes"
                                    :key="mount_route.id"
                                    :mount="mount_route"
                                    :route="'mountaineering/'+mount_route.global_data.url_title"
                                />
                            </div>
                        </div>
                    </div>

                    <div v-if="this.mount_routes_by_masiv.length == 0 && this.mount_routes.length == 0">
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
    export default {
        data: function () {
            return {
                mounts: [],
                mount_routes: [],
                mount_routes_by_masiv: [],
                filter_mount: 'All',

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
        },
        watch: {
            filter_mount(newVal) {
                this.get_mountain_route_articles();
            }
        },
        mounted() {
            this.get_mounts(),
            this.get_mountain_route_articles()
        },

        watch: {
            '$route' (to, from) {
                this.get_mounts(),
                this.get_mountain_route_articles()
                window.scrollTo(0,0)
            }
        },
        
        methods: {

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
                }
                else{
                    this.get_filtered_articles(Number(this.filter_mount)) 
                    this.get_selected_mount_data(Number(this.filter_mount))
                }
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

.mountain-list-header {
  background: #f8f9fa;
  padding: 1rem;
  margin-top: 1.5rem;
  margin-bottom: 1rem;
  border-left: 4px solid #007bff;
}

.list-view-container {
  display: block;
}
</style>
