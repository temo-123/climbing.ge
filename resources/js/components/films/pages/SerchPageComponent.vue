<template>
    <div>
        <div class="container">

            <h1  class='serch_title'>{{ $t('films.search_films_title') }}</h1>

            <div class="row">
                <div class="col-md-12">
                    <h2 class="text-center">{{ $t('films.search_query_notice', { query: this.$route.query.query }) }}</h2>
                    <div v-if="film_search_loading">
                        <skeleton-loader
                            viewBox="0 0"
                            primaryColor="#f3f3f3"
                            secondaryColor="#777777"
                        />
                    </div>
                    <div class="col-sm-12" v-else>
                        <hr>
                        <span v-if="films.length == 0">
                            <div class="alert alert-danger" role="alert">
                                {{ $t('guide.search_no_result_faild') }}
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
            :title = "$t('films.meta_title') + ' - ' + this.$route.query.query"
            :description = "$t('films.meta_description')"
            :image = "'../../../../public/images/meta_img/films.jpg'"
        />
    </div>
</template>

<script>
    import filmSerchingCard from '../items/cards/FilmSerchingCardComponent'
    import metaData from '../items/MetaDataComponent'

    export default {
        components: {
            filmSerchingCard,
            metaData,

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
