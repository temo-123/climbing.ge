<template>
    <div class="container">
        <div class="row">
            <div class="col-sm-3">
                <left-menu />
            </div>
            
            <div class="col-sm-9">
                <div class="row font-italic">
                    <div class="col-md-6">
                        <h3 class="mt-3 pb-3 mb-4 ">
                            Faworite climbing film
                        </h3>
                    </div>
                    <div class="col-md-6">
                        <button class="btn btn-primary float-right" @click="go_to_film_list('/film')">
                            Go to film list
                        </button>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <button class="btn btn-success float-right" @click="get_faworite_films()">
                            Refresh
                        </button>
                    </div>
                </div>
                
                <div class="row"  v-if="films.length">

                    <div class="col-md-4" v-for="film in films" :key="film.id">
                        <div class="card">

                            <span @click="go_to_film_list('/film/'+film.global_film.url_title)" >
                                <site-img v-if="film.global_film.image != NULL" :src="'../../../public/images/film_img/'+film.global_film.image" :img_class="'card-img-top'" :alt='film.local_film.name'/>
                                <site-img v-else :src="'../../../public/images/site_img/image.png'" :img_class="'card-img-top'" :alt='film.local_film.name'/>
                            </span>

                            <div class="card-body">
                                <a @click="go_to_film_list('/film/'+film.url_title)"> 
                                    <h5 class="card-title border-bottom pb-3">
                                        {{ film.local_film.name }}
                                    </h5> 
                                </a>

                                <a @click="del_from_faworites(film.global_film.id)" class="btn btn-danger">X <i class="fas fa-angle-double-right"></i></a>
                            </div>
                        </div>
                    </div>

                </div>
                <div class="row" v-else>
                    <h2>You dont have faworite filmds</h2>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        components: {
            // catalogItem,
        },
        data: function () {
            return {
                MIX_SITE_URL: process.env.MIX_SITE_URL,
                MIX_APP_SSH: process.env.MIX_APP_SSH,
                films: [],
            };
        },
        mounted() {
            this.get_faworite_films()
        },
        methods: {
            get_faworite_films(){
                axios
                .get('../api/film/get_faworite_film_list')
                .then(response => {
                    this.films = response.data
                })
                .catch(error =>{
                })
            },

            go_to_film_list(page){
                window.open(this.MIX_APP_SSH + 'films.' + this.MIX_SITE_URL + page)
            },

            del_from_faworites(id){
                if(confirm('Are you sure, you want delite this film from your faworites?')){
                    axios
                    .post('../../api/film/del_from_faworite/'+id, {
                        film_id: id,
                        _method: 'DELETE'
                    })
                    .then(Response => {
                        this.get_faworite_films()
                        // alert("Film delited from your favorite list!");
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