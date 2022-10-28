<template>
    <div class="container">
        <div class="row">
            <div class="col-md-6">
                <site-img
                    v-if="!film.global_film.image"
                    :src="'../../../public/images/site_img/film poster demo.jpg'"
                    :img_class="'img-fluid rounded mb-4 mb-lg-0 float-right'"
                    :alt="film.local_film.name"
                />
                <site-img
                    v-else
                    :src="'../../../public/images/film_img/' + film.global_film.image"
                    :img_class="'img-fluid rounded mb-4 mb-lg-0 float-right'"
                    :alt="film.local_film.name"
                />
            </div>
            <div class="col-md-6">
                <div class="row">
                    <h1>
                        {{ film.local_film.name }}
                            
                        <span @click="add_in_favorite_film(film.global_film.id)" style="float: right;"> <i class="fa fa-heart-o favorite_icon add_to_favorite" ></i> </span>
                    </h1>
                </div>
                <div class="row">
                    {{ film.local_film.text }}
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">

                <div class="mountain_masive_description" v-for="file in film.files" :key="file.key">

                    <p style="float: right;" v-show="masiv_desc" @click="masiv_desc = !masiv_desc">Close</p>
                    <p style="float: right;" v-show="!masiv_desc" @click="masiv_desc = !masiv_desc">Open</p>
                    
                    <h2 @click="masiv_desc = !masiv_desc"> {{ file.us_name }}</h2>

                    <span v-show="masiv_desc">
                        {{ file.us_description }}
                        <a :href="'/torrent/'+file.file" class="float-right" download>Download torrent file</a>
                    </span>
                </div> 

            </div>
        </div>
        
        <div class="row" v-if="same_films.length">

            <h2 class="text-center">you might also like</h2>

            <div v-if="films_loader">
                <content-loader
                    viewBox="0 0"
                    primaryColor="#f3f3f3"
                    secondaryColor="#279fbbb0"
                />
            </div>
            <div v-else>
                <filmCard v-for="s_film in same_films" :key='s_film.global_film.id' :film='s_film' :col="'3'"/>
            </div>
        </div>            

        <div class="row text-center">
            <li><router-link :to="{name: 'studia'}" exact> All films</router-link></li>
        </div>

        <metaData 
            :title = "film.local_film.name"
            :description = "film.local_film.text"
            :image = "'../../../public/images/film_img/' + film.global_film.image"
        />
    </div>
</template>

<script>
    import { ContentLoader } from "vue-content-loader";
    import  filmCard  from "../items/cards/filmsListCardComponent.vue";
    import metaData from '../items/MetaDataComponent'
    export default {
        components: {
            metaData,
            ContentLoader,
            filmCard
        },
        data: function () {
            return {
                masiv_desc: false,
                film: [],
                same_films: [],
                films_loader: true
            };
        },
        mounted() {
            this.get_film();
        },
        methods: {
            get_same_films(category_id, id) {
                axios
                    // .get('../api/film/get_films/'+localStorage.getItem('lang'))
                    .get("../api/film/get_same_films/"+category_id+"/"+id+"/us")
                    .then((response) => {
                        this.same_films = response.data;
                    })
                    .catch((error) => {})
                    .finally(() => (this.films_loader = false));
            },
            get_film() {
                axios
                    .get("../api/film/get_film/us/"+this.$route.params.url_title)
                    .then((response) => {
                        this.film = response.data;
                        // this.category = this.film.category
                        this.get_same_films(this.film.global_film.category_id, this.film.global_film.id)
                    })
                    .catch((error) => {})
            },
            add_in_favorite_film(film_id){
                axios
                    .post("../../api/film/add_to_faworite/",{
                        film_id: film_id
                    })
                    .then((response) => {
                        alert(response.data)
                    })
                    .catch((error) => {})
            }
        }
    }
</script>

<style>

</style>