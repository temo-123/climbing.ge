<template>
    <span>
        <button type="bottom" class="btn btn-default btn-send main-btn pull-right" @click="show_modal">{{ $t('guide.sector.routes_list_button') }}</button>

        <stack-modal
            :show="is_show_modal"
            :title="$t('guide.sector.modal_title')"
            @close="is_show_modal=false"
            :saveButton="{ visible: false, title: 'Save', btnClass: { 'btn btn-primary': false } }"
            :cancelButton="{ visible: false, title: 'Close', btnClass: { 'btn btn-danger': true } }"
        >

            <pre class="language-vue">
                <!-- <div class="row">
                    <div class="col-md-6"> -->
                        <!-- <div class="form-group"> -->
                            <!-- <label class="font-weight-bold">{{ $t('guide.sector.routes_per_page') }}:</label> -->
                            <select class="" v-model="routesPerPage" @change="refreshData">
                                <option value="5">5 {{ $t('guide.sector.routes') }}</option>
                                <option value="10">10 {{ $t('guide.sector.routes') }}</option>
                                <option value="15">15 {{ $t('guide.sector.routes') }}</option>
                                <option value="25">25 {{ $t('guide.sector.routes') }}</option>
                                <option value="50">50 {{ $t('guide.sector.routes') }}</option>
                            </select>
                        <!-- </div> -->
                    <!-- </div>
                    <div class="col-md-6"> -->
                        <!-- <div class="form-group"> -->
                            <!-- <label class="font-weight-bold">{{ $t('guide.sector.min_reviews') }}:</label> -->
                            <select class="" v-model="minReviews" @change="refreshData">
                                <option value="1">{{ $t('guide.sector.all_reviews') }}</option>
                                <option value="2">2+ {{ $t('guide.sector.reviews') }}</option>
                                <option value="3">3+ {{ $t('guide.sector.reviews') }}</option>
                                <option value="5">5+ {{ $t('guide.sector.reviews') }}</option>
                                <option value="10">10+ {{ $t('guide.sector.reviews') }}</option>
                            </select>
                        <!-- </div> -->
                    <!-- </div>
                </div> -->
            <!-- </div> -->

                <!-- Sport Routes Table - Only show if there are routes or if currently loading -->
                <div v-if="hasSportRoutes || loading_sport">
                    <h1>
                        {{ $t('guide.sector.sport_climbing_routes_title') }}
                        <!-- <small class="badge badge-primary ml-2" v-if="sport_routes.data.length > 0">
                            {{ sport_routes.data.length }} {{ $t('guide.sector.routes') }}
                        </small> -->
                    </h1>
                    <table class="table table-hover" id="dev-table">
                        <thead>

                            <tr>
                                <th>#</th>
                                <th>{{ $t('guide.sector.route_name') }}</th>
                                <th>{{ $t("guide.route.grade fr") }}</th>
                                <th>{{ $t('guide.sector.reviev_count') }}</th>
                                <th>{{ $t('guide.sector.stars') }}</th>
                                <!-- <th>{{ $t('guide.sector.region') }}</th> -->
                                <th>{{ $t('guide.sector.spot_name') }}</th>
                                <th>{{ $t('guide.sector.sector') }}</th>
                                <th>{{ $t('guide.sector.countinue') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr  v-for="(sport_route, index) in sport_routes.data" :key="'sport-' + index">
                                <td>{{ index + 1 }}</td>
                                <td>{{ sport_route.name }}</td>

                                <td v-if="sport_route.or_grade && sport_route.or_grade !== ''">{{ sport_route.grade }} / {{ sport_route.or_grade }}</td> <td v-else>{{ sport_route.grade }}</td>
                                
                                <td>{{ sport_route.review_count }}</td>
                                <td>
                                    <starsReiting
                                        style='margin-top: -4em; margin-bottom: -5em;'
                                        :reviews_count_prop = sport_route.review_count
                                        :reviews_stars_prop = sport_route.stars
                                        :rewiew_count_text_prop = false
                                    />
                                </td>

                                <!-- <td>{{ sport_route.region }}</td> -->
                                <td>{{ sport_route.spot_name }}</td>
                                <td>{{ sport_route.sector }}</td>

                                <td @click="viewRoute(sport_route)">
                                    <a style="margin-top: -5%; font-size: 120%"
                                        ><i class="fa fa-info" aria-hidden="true"></i
                                    ></a>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Boulder Routes Table - Only show if there are routes or if currently loading -->
                <!-- <div v-if="hasBoulderRoutes || loading_boulder" class="mt-4">
                    <h1 class="text-success">
                        <i class="fas fa-rock"></i> {{ $t('guide.sector.boulder_routes_title') }}
                        <small class="badge badge-success ml-2" v-if="boulder_routes.data.length > 0">
                            {{ boulder_routes.data.length }} {{ $t('guide.sector.routes') }}
                        </small>
                    </h1>
                    <table class="table table-hover table-striped rounded-lg overflow-hidden" id="dev-table">
                        <thead class="thead-dark">
                            <tr>
                                <th class="text-center">#</th>
                                <th>{{ $t('guide.sector.route_name') }}</th>
                                <th class="text-center">{{ $t('guide.sector.reviev_count') }}</th>
                                <th class="text-center">{{ $t('guide.sector.stars') }}</th>
                                <th>{{ $t('guide.sector.region') }}</th>
                                <th>{{ $t('guide.sector.grade') }}</th>
                                <th>{{ $t('guide.sector.sector') }}</th>
                                <th class="text-center">{{ $t('guide.sector.countinue') }}</th>
                            </tr>
                        </thead>
                        <tbody>


                            <tr v-if="loading_boulder">
                                <td class="text-center py-4">
                                    <div class="spinner-border text-success" role="status">
                                        <span class="sr-only">{{ $t('guide.sector.loading_boulder_routes') }}</span>
                                    </div>
                                </td>
                            </tr><tr v-else-if="!hasBoulderRoutes">
                                <td class="text-center text-muted py-4">
                                    <i class="fas fa-info-circle fa-2x mb-2"></i>
                                    <div>{{ $t('guide.sector.no_reviews_available_boulder') }}</div>
                                    <small class="text-muted">{{ $t('guide.sector.be_first_to_review_boulder') }}</small>
                                </td>
                            </tr><tr v-else v-for="(boulder, index) in boulder_routes.data" :key="'boulder-' + index" class="table-row-hover">
                                <td class="text-center font-weight-bold">{{ index + 1 }}</td>
                                <td>
                                    <div class="route-name">{{ boulder.name }}</div>
                                    <small class="text-muted" v-if="boulder.grade">{{ boulder.grade }}</small>
                                </td>
                                <td class="text-center">
                                    <span class="badge badge-secondary">{{ boulder.review_count }}</span>
                                </td>
                                <td class="text-center">
                                    <div class="stars-display">
                                        <span class="font-weight-bold">{{ boulder.stars }}</span>
                                        <div class="star-rating">
                                            <i class="fas fa-star" v-for="n in 5" :key="n" 
                                               :class="n <= boulder.stars ? 'text-warning' : 'text-muted'"></i>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <i class="fas fa-map-marker-alt text-danger"></i> {{ boulder.region }}
                                </td>
                                <td>
                                    <i class="fas fa-map text-info"></i> {{ boulder.sector }}
                                </td>
                                <td class="text-center">
                                    <button class="btn btn-sm btn-success" @click="viewRoute(boulder)">
                                        <i class="fas fa-eye"></i> {{ $t('guide.sector.countinue') }}
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                    </table> -->
                <!-- </div> -->

                <!-- No data message when both categories are empty -->
                <div v-if="!hasSportRoutes && !hasBoulderRoutes && !loading_sport && !loading_boulder" class="text-center py-5">
                    <i class="fas fa-chart-line fa-3x text-muted mb-3"></i>
                    <h4 class="text-muted">{{ $t('guide.sector.no_popular_routes_any') }}</h4>
                    <p class="text-muted">{{ $t('guide.sector.be_first_to_review_any') }}</p>
                </div>


            </pre>
        </stack-modal>
    </span>
</template>

<script>
    import StackModal from '@innologica/vue-stackable-modal'  //https://innologica.github.io/vue-stackable-modal/#sample-css
    import starsReiting  from '../../../../../global_components/StarReitingShowComponent.vue'

    export default {
        components: {
            StackModal,

            starsReiting,
        },

        data(){
            return{
                sport_routes: { data: [], pagination: {} },
                boulder_routes: { data: [], pagination: {} },
                is_show_modal: false,
                loading_sport: false,
                loading_boulder: false,
                
                // Controls
                routesPerPage: 10,
                minReviews: 1,
            }
        },
        computed: {
            hasSportRoutes() {
                return this.sport_routes.data && this.sport_routes.data.length > 0;
            },
            hasBoulderRoutes() {
                return this.boulder_routes.data && this.boulder_routes.data.length > 0;
            }
        },
        mounted(){
            // 
        },



        methods: {
            async show_modal(){
                this.loading_sport = true
                this.loading_boulder = true
                
                // Load both sport and boulder routes in parallel
                await Promise.all([
                    this.loadSportRoutes(1),
                    this.loadBoulderRoutes(1)
                ])
                
                this.is_show_modal = true
            },
            
            async loadSportRoutes(page = 1) {
                try {
                    this.loading_sport = true
                    const response = await axios.get(`/get_route/get_most_popular_routes/sport/`, {
                        params: {
                            per_page: this.routesPerPage,
                            min_reviews: this.minReviews,
                            page: page
                        }
                    })
                    this.sport_routes = response.data
                } catch (error) {
                    // console.error('Error loading sport routes:', error)
                    this.sport_routes = { data: [], pagination: {} }
                } finally {
                    this.loading_sport = false
                }
            },
            
            async loadBoulderRoutes(page = 1) {
                try {
                    this.loading_boulder = true
                    const response = await axios.get(`/get_route/get_most_popular_routes/boulder/`, {
                        params: {
                            per_page: this.routesPerPage,
                            min_reviews: this.minReviews,
                            page: page
                        }
                    })
                    this.boulder_routes = response.data
                } catch (error) {
                    // console.error('Error loading boulder routes:', error)
                    this.boulder_routes = { data: [], pagination: {} }
                } finally {
                    this.loading_boulder = false
                }
            },
            
            async refreshData() {
                // Refresh both lists when controls change
                await Promise.all([
                    this.loadSportRoutes(1),
                    this.loadBoulderRoutes(1)
                ])
            },
            


            viewRoute(route) {
                // Close current modal
                this.is_show_modal = false
                
                // console.log('Navigating from modal with route data:', route);
                
                // Navigate to outdoor article page with sector and route info
                if (route.article_url_title) {
                    // Build URL with query parameters for sector and route
                    const queryParams = new URLSearchParams();
                    if (route.sector_id) {
                        queryParams.set('sector', route.sector_id);
                    }
                    if (route.route_id) {
                        queryParams.set('route', route.route_id);
                    }
                    
                    const queryString = queryParams.toString();
                    const url = `outdoor/${route.article_url_title}${queryString ? '?' + queryString : ''}`;
                    
                    // console.log('Using Vue Router to navigate to:', url);
                    // Use Vue router for navigation
                    this.$router.push(url);
                } else if (route.article_id && route.sector_id) {
                    // Fallback if no url_title but have article_id
                    const queryParams = new URLSearchParams();
                    queryParams.set('sector', route.sector_id);
                    if (route.route_id) {
                        queryParams.set('route', route.route_id);
                    }
                    
                    const queryString = queryParams.toString();
                    const url = `outdoor/article/${route.article_id}${queryString ? '?' + queryString : ''}`;
                    
                    // console.log('Fallback navigation to:', url);
                    this.$router.push(url);
                } else {
                    // Last resort: try to find by article_id if available
                    // console.warn('Missing required navigation data:', route);
                    if (route.article_id) {
                        alert('Unable to navigate to this route - missing URL title');
                    } else {
                        // Fallback to route details if sector/article info not available
                        this.$router.push(`route/${route.route_id}`);
                    }
                }
            }
        }
    }
</script>

<style>
.language-vue{
    margin: 10px 0 0 0;
    width: 95%;

    position: absolute;
    top: 0;
    bottom: 0;
    overflow-y: scroll;
    overflow-x: hidden;
}
.modal-body{
    height: 600px;
}
th:nth-child(2n+1) {
  background: #d7b396;
}
</style>
