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

            <div class="tabs">
                <div class="row">
                    <div class="col-md-6">
                        <input type="radio" id="1" :value="1" v-model="tab_num" />

                        <label for="1">Faworite outdoor areas</label>
                    </div>
                    <div class="col-md-6">
                        <input type="radio" id="2" :value="2" v-model="tab_num" />

                        <label for="2">Sport climbing routes reviews</label>
                    </div>
                </div>

                <div class="row" v-if="tab_num == 1">
                    <div class="col-md-12">
                        <div class="row font-italic">
                            <div class="col-md-6">
                                <h3 class="mt-3 pb-3 mb-4 ">
                                    Faworite climbing spots
                                </h3>
                            </div>
                            <div class="col-md-6">
                                <button class="btn btn-primary float-right" @click="go_to_spots_list('/outdoor')">
                                    Go to outdoor spot list
                                </button>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <button class="btn btn-success float-right" @click="get_faworite_outdoor_region()">
                                    Refresh
                                </button>
                            </div>
                        </div>
                        <div class="container" v-if="spots.length">
                            <div class="row">
                                <div class="col-md-4" v-for="spot in spots" :key="spot.id">
                                    <div class="card">

                                        <span @click="go_to_spots_list('/outdoor/'+spot.global_data.url_title)" >
                                            <site-img v-if="spot.global_data.image != NULL" :src="'/public/images/outdoor_img/'+spot.global_data.image" :img_class="'card-img-top cursor_pointer'" :alt='spot.locale_data.title'/>
                                            <site-img v-else :src="'/public/images/site_img/image.png'" :img_class="'card-img-top cursor_pointer'" :alt='spot.locale_data.title'/>
                                        </span>

                                        <div class="card-body">
                                            <a @click="go_to_spots_list('/outdoor/'+spot.global_data.url_title)"> 
                                                <h5 class="card-title border-bottom pb-3 cursor_pointer">
                                                    {{ spot.locale_data.title }}
                                                </h5> 
                                            </a>

                                            <a @click="del_from_faworites(spot.global_data.id)" class="btn btn-danger"><i class="fa fa-trash" aria-hidden="true"></i></a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row" v-else>
                            <h2>You dont have faworite climbing spots</h2>
                        </div>
                    </div>
                </div>

                <div class="row" v-if="tab_num == 2">
                    <div class="col-md-12">
                        <div class="row">
                            <div class="col-md-12">
                                <h3>
                                    Your sport climbing routes reviews list
                                </h3>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <button class="btn btn-success float-right" @click="get_user_sport_routes_review()">
                                    Refresh
                                </button>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <table class="table table-hover" v-if="reviews.length"> 
                                    <thead>
                                        <tr>
                                            <th style="text-align: center">
                                                <input type="checkbox" class="all" />
                                            </th>
                                            <th>|</th>

                                            <th>ID</th>
                                            <th>|</th>

                                            <th>Route name</th>
                                            <th>|</th>

                                            <th>Reiting</th>
                                            <th>|</th>

                                            <th>Delite</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="review in reviews" :key="review.route.id">
                                            <td style='text-align: center;'>
                                                <input type="checkbox">
                                            </td>
                                            <td>|</td>
                                            <td>{{review.review.id}}</td>

                                            <td>|</td>
                                            <td>{{review.route.name}}</td>
                                            
                                            <td>|</td>

                                            <td>{{review.review.stars}}</td>
                                            
                                            <td>|</td>
                                            <td>
                                                <button type="submit" class="btn btn-danger" @click="del_route_review(review.review.id)" ><i class="fa fa-trash" aria-hidden="true"></i></button>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>

                                <div v-else>
                                    <h2>You dont have reviews</h2>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>  
</template>

<script>
    import breadcrumb from '../../items/BreadcrumbComponent.vue'
    export default {
        components: {
            breadcrumb,
        },
        data: function () {
            return {
                spots: [],
                reviews: [],
                MIX_SITE_URL: process.env.MIX_SITE_URL,
                MIX_APP_SSH: process.env.MIX_APP_SSH,
                tab_num: 1
            };
        },
        mounted() {
            this.get_faworite_outdoor_region()
        },
        methods: {
            get_faworite_outdoor_region(){
                axios
                .get('outdoor/get_faworite_outdoor_region')
                .then(response => {
                    this.spots = response.data
                    this.get_user_sport_routes_review()
                })
                .catch(error =>{
                })
            },
            get_user_sport_routes_review(){
                axios
                .get('route_review/get_user_sport_routes_review')
                .then(response => {
                    this.reviews = response.data
                })
                .catch(error =>{
                })
            },

            go_to_spots_list(page){
                window.open(this.MIX_APP_SSH + '' + this.MIX_SITE_URL + page)
            },

            del_from_faworites(id){
                if(confirm('Are you sure, you want delite this area from your faworites?')){
                    axios
                    .post('outdoor/del_faworite_outdoor_region/'+id, {
                        article_id: id,
                        _method: 'DELETE'
                    })
                    .then(Response => {
                        this.get_faworite_outdoor_region()
                        // alert("Area delited from your favorite list!");
                    })
                    .catch(error => {
                        alert("Error");
                    })
                }
            },

            del_route_review(id){
                if(confirm('Are you sure, you want delite your reiting?')){
                    axios
                    .post('route_review/del_route_review/'+id, {
                        review_id: id,
                        _method: 'DELETE'
                    })
                    .then(Response => {
                        this.get_user_sport_routes_review()
                    })
                    .catch(error => {
                        alert("Error");
                    })
                }
            }
        }
    }
</script>

<style scoped>
</style>