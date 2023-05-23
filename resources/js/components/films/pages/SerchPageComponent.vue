<template>
    <div>
        <div class="container">

            <h1  class='serch_title'>Search films</h1>

            <div class="row">
                <div class="col-md-12">
                    <h2 class="text-center"> The your search query is:"<b> {{ this.$route.query.query }} </b>". Please use only english text for your query!!!</h2>
                    <div v-if="film_search_loading">
                        <content-loader
                            viewBox="0 0"
                            primaryColor="#f3f3f3"
                            secondaryColor="#777777"
                        />
                    </div>
                    <div class="col-sm-12" v-else>
                        <hr>
                        <span v-if="films.length == 0">
                            <div class="alert alert-danger" role="alert">
                                Nothing found! Try again! :)
                            </div>
                        </span>

                        <span v-else>
                            <section class="inner">
                                <section class="portfolio" id="portfolio">
                                    <div class="layout">
                                        <section class="inner">
                                            <ul class="grid">
                                                <filmSerchingCard
                                                    v-for="film in films"
                                                    :key='film.id'
                                                    :film="film" 
                                                />
                                            </ul>
                                        </section>
                                    </div>
                                </section>
                            </section>
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <metaData 
            :title = "'Climbing films - ' + this.$route.query.query"
            :description = "'Rock climbing, mountaineering and other outdoor actyvity films'"
            :image = "'../../../../public/images/meta_img/films.jpg'"
        />
    </div>
</template>

<script>
    import filmSerchingCard from '../items/cards/FilmSerchingCardComponent'
    import metaData from '../items/MetaDataComponent'
    import { ContentLoader } from 'vue-content-loader'
    export default {
        components: {
            filmSerchingCard,
            metaData,
            ContentLoader,
        },
        data: function () {
            return {
                films: [],
                film_search_loading: true
            };
        },
        mounted() {
            this.get_serching_data();
        },
        watch: {
            '$route' (to, from) {
                this.get_serching_data();
                window.scrollTo(0,0);
            }
        },
        methods: {
            get_serching_data() {
                axios
                .post('../api/filmSearch/' + this.$route.query.query)
                .then(response => {
                    this.films = response.data
                })
                .catch(error =>{
                })
                .finally(() => (this.film_search_loading = false));
            }
        }
    }
</script>
