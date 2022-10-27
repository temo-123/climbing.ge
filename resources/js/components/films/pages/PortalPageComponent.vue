<template>
    <!-- Page Content-->
    <div class="container px-4 px-lg-5">

        <!-- Heading Row-->
        <div class="row gx-4 gx-lg-5 align-items-center my-5">

            <!-- <div class="image_moving">
                <div class="previes_film_bottom">
                    <i class="fa fa-chevron-left" aria-hidden="true" @click="previes_film()" v-if="best_film > 1"></i>
                </div>

                <div class="next_film_bottom">
                    <i class="fa fa-chevron-right" aria-hidden="true" @click="next_film()" v-if="best_film < 5"></i>
                </div>
            </div>

            <div class="row"  v-if="best_film == 1">
                <h2 class="text-center">Best movie of the day</h2>
                <div class="col-lg-5">
                    <site-img
                        :src="'../../../public/images/site_img/film poster demo.jpg'"
                        :img_class="'img-fluid rounded mb-4 mb-lg-0 float-right'"
                        :alt="'news[0][0][0][0].title'"
                    />
                </div>
                <div class="col-lg-5">
                    <h1 class="font-weight-light">day</h1>
                    <p>
                        This is a template that is great for small businesses. It
                        doesn't have too much fancy flare to it, but it makes a
                        great use of the standard Bootstrap core components. Feel
                        free to use this template for any project you want!
                    </p>
                    <a class="btn btn-primary" href="#!">Call to Action!</a>
                </div>
            </div>

            <div class="row" v-else-if="best_film == 2">
                <h2 class="text-center">Best movie of the week</h2>
                <div class="col-lg-5" >
                    <site-img
                        :src="'../../../public/images/site_img/film poster demo.jpg'"
                        :img_class="'img-fluid rounded mb-4 mb-lg-0 float-right'"
                        :alt="'news[0][0][0][0].title'"
                    />
                </div>
                <div class="col-lg-5">
                    <h1 class="font-weight-light">week</h1>
                    <p>
                        This is a template that is great for small businesses. It
                        doesn't have too much fancy flare to it, but it makes a
                        great use of the standard Bootstrap core components. Feel
                        free to use this template for any project you want!
                    </p>
                    <a class="btn btn-primary" href="#!">Call to Action!</a>
                </div>
            </div>

            <div class="row"  v-else-if="best_film == 3">
                <h2 class="text-center">Best movie of the month</h2>
                <div class="col-lg-5">
                    <site-img
                        :src="'../../../public/images/site_img/film poster demo.jpg'"
                        :img_class="'img-fluid rounded mb-4 mb-lg-0 float-right'"
                        :alt="'news[0][0][0][0].title'"
                    />
                </div>
                <div class="col-lg-5">
                    <h1 class="font-weight-light">monse</h1>
                    <p>
                        This is a template that is great for small businesses. It
                        doesn't have too much fancy flare to it, but it makes a
                        great use of the standard Bootstrap core components. Feel
                        free to use this template for any project you want!
                    </p>
                    <a class="btn btn-primary" href="#!">Call to Action!</a>
                </div>
            </div>

            <div class="row"  v-else-if="best_film == 4">
                <h2 class="text-center">Best movie of the all time</h2>
                <div class="col-lg-5">
                    <site-img
                        :src="'../../../public/images/site_img/film poster demo.jpg'"
                        :img_class="'img-fluid rounded mb-4 mb-lg-0 float-right'"
                        :alt="'news[0][0][0][0].title'"
                    />
                </div>
                <div class="col-lg-5">
                    <h1 class="font-weight-light">all time</h1>
                    <p>
                        This is a template that is great for small businesses. It
                        doesn't have too much fancy flare to it, but it makes a
                        great use of the standard Bootstrap core components. Feel
                        free to use this template for any project you want!
                    </p>
                    <a class="btn btn-primary" href="#!">Call to Action!</a>
                </div>
            </div> -->
            
            <!-- {{ most_liked_film }} -->
            <div class="container">
                <div class="row" v-if="most_liked_film_loader">
                    <content-loader
                        viewBox="0 0"
                        primaryColor="#f3f3f3"
                        secondaryColor="#279fbbb0"
                    />
                </div>
                <div class="row" v-else>
                    <h2 class="text-center">Most liked film</h2>
                    <div class="row">
                        <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                            <site-img
                                v-if="!most_liked_film.global_film.image"
                                :src="'../../../public/images/site_img/film poster demo.jpg'"
                                :img_class="'img-fluid rounded mb-4 mb-lg-0 float-right'"
                                :alt="most_liked_film.local_film.name"
                            />
                            <site-img
                                v-else
                                :src="'../../../public/images/film_img/' + most_liked_film.global_film.image"
                                :img_class="'img-fluid rounded mb-4 mb-lg-0 float-right'"
                                :alt="most_liked_film.local_film.name"
                            />
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                            <div class="row">
                                <router-link :to="'film/'+most_liked_film.global_film.url_title">
                                    <h1 class="font-weight-light">{{ most_liked_film.local_film.name }}</h1>
                                </router-link>
                            </div>
                            <div class="row" v-if=" most_liked_film.local_film.coutry || most_liked_film.local_film.issue_year">
                                <p v-if=" most_liked_film.local_film.coutry">Coutry - {{ most_liked_film.local_film.coutry }}</p>
                                <p v-if="most_liked_film.local_film.issue_year">Year of issue - {{ most_liked_film.local_film.issue_year }}</p>
                            </div>
                            <div class="row">
                                {{ most_liked_film.local_film.short_description }}
                            </div>
                            <div class="row">
                                <router-link :to="'film/'+most_liked_film.global_film.url_title">
                                    <button class="btn btn-primary">Open</button>
                                </router-link>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <!-- Call to Action-->
        <div class="card text-white bg-secondary my-5 py-4 text-center">
            <div class="card-body">
                <h2 class="text-white m-0">All films</h2>
            </div>
        </div>

        <div class="row">
            <div class="container articles_filter_bar">
                <div class="col-md-6 col-sm-6">
                    Select category and filtred films by category
                </div>
                <div class="col-md-6 col-sm-6">
                    <select class="form-control" v-model="filter_category" @click="sort_film_by_category()">
                        <option>All</option>
                        <option v-for="category in films_categories" :key="category.id" :value="category.id">{{ category.us_name }}</option>
                    </select>
                </div>
            </div>
        </div>
        
        <!-- Content Row-->
        <div class="row gx-4 gx-lg-5">
            <div v-if="films_loader">
                <content-loader
                    viewBox="0 0"
                    primaryColor="#f3f3f3"
                    secondaryColor="#279fbbb0"
                />
            </div>
            <div v-else>
                <div class="row">
                    <filmCard v-for="film in films" :key='film.global_film.id' :film='film' />
                </div>
            </div>
        </div>

        <metaData 
            :title = "'Climbing films'"
            :description = "'Rock climbing, mountaineering and other outdoor actyvity films'"
            :image = "'../../../../public/images/meta_img/films.jpg'"
        />
    </div>
</template>

<script>
import { ContentLoader } from "vue-content-loader";
import  filmCard  from "../items/card/filmsListCardComponent.vue";

import metaData from '../items/MetaDataComponent'
    export default {
        components: {
            metaData,
            ContentLoader,
            filmCard
        },
        data: function () {
            return {
                films: [],
                not_filtred_films: [],
                films_loader: true,
                most_liked_film_loader: true,

                day_films: [],
                week_films: [],
                monse_films: [],
                all_time_films: [],
                most_liked_film: [],

                films_categories: [],

                best_film: 1,

                filter_category: 'All',
            };
        },
        mounted() {
            this.get_films();
            this.get_films_categories();
            this.get_most_liked_film()

            this.get_user()
        },
        methods: {
            get_films() {
                axios
                    // .get('../api/film/get_films/'+localStorage.getItem('lang'))
                    .get("../api/film/get_films/us")
                    .then((response) => {
                        this.not_filtred_films = response.data;
                        this.sort_film_by_category()
                    })
                    .catch((error) => {})
                    .finally(() => (this.films_loader = false));
            },
            get_films_categories() {
                axios
                    .get("../api/film/get_films_categories/us")
                    .then((response) => {
                        this.films_categories = response.data;
                    })
                    .catch((error) => {})
            },

            sort_film_by_category(){
                let vm = this;

                if (vm.filter_category == 'All') {
                    this.films = this.not_filtred_films
                }
                else{
                    this.films = this.not_filtred_films.filter(function (item){
                        return item.global_film.category_id == vm.filter_category
                    })
                }
            },

            previes_film(){
                if(this.best_film > 1){
                    this.best_film --
                }
            },
            next_film(){
                if(this.best_film < 5){
                    this.best_film ++
                }
            },

            get_user(){
                axios
                .get('/api/auth_user')
                .then((response)=>{
                    this.user = response.data['name']
                })
                .catch(
                    this.user = 'Boss'
                );
            },
            // get_best_films_for_day(){
            //     axios
            //         // .get('../api/film/get_films/'+localStorage.getItem('lang'))
            //         .get("../api/film/get_films/us")
            //         .then((response) => {
            //             this.day_films = response.data;
            //         })
            //         .catch((error) => {})
            //         .finally(() => (this.films_loading = false));
            // },
            // get_best_films_for_week(){
            //     axios
            //         // .get('../api/film/get_films/'+localStorage.getItem('lang'))
            //         .get("../api/film/get_films/us")
            //         .then((response) => {
            //             this.week_films = response.data;
            //         })
            //         .catch((error) => {})
            //         .finally(() => (this.films_loading = false));
            // },
            // get_best_films_for_manse(){
            //     axios
            //         // .get('../api/film/get_films/'+localStorage.getItem('lang'))
            //         .get("../api/film/get_films/us")
            //         .then((response) => {
            //             this.monse_films = response.data;
            //         })
            //         .catch((error) => {})
            //         .finally(() => (this.films_loading = false));
            // },
            // get_best_films_for_all_time(){
            //     axios
            //         // .get('../api/film/get_films/'+localStorage.getItem('lang'))
            //         .get("../api/film/get_films/us")
            //         .then((response) => {
            //             this.all_time_films = response.data;
            //         })
            //         .catch((error) => {})
            //         .finally(() => (this.films_loading = false));
            // },
            get_most_liked_film(){
                axios
                    .get("../api/film/top_films/get_most_liked_films/us")
                    .then((response) => {
                        this.most_liked_film = response.data[0];
                    })
                    .catch((error) => {})
                    .finally(() => (this.most_liked_film_loader = false));
            }
        },
    };
</script>

<style>
.previes_film_bottom{
  float: left;
  margin-left: 0.10em;
}
.next_film_bottom{
  float: right;
  margin-right: -0.10em;
}
.previes_film_bottom, .next_film_bottom{
  cursor: pointer; 
  color: #b3b2b2d9;
  font-size: 3.5em;
  margin-top: 4.5em;
}
</style>
