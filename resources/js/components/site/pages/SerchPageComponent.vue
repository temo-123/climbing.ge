<template>
    <div>
        <div class="container">

            <h1  class='serch_title'>Search page</h1>

            <div class="row">
                <div class="col-md-12">
                    <p> The Search results for your query is:<b> {{ this.$route.query.query }} </b></p>

                    <div class="col-sm-12">
                        <section class="inner">
                            <section class="portfolio" id="portfolio">

                                    <div class="layout">
                                        <section class="inner">
                                            <ul class="grid">
                                                <articleSerchingCard
                                                    v-for="article in articles"
                                                    :key='article.id'
                                                    :article="article" 
                                                />
                                            </ul>
                                        </section>
                                    </div>
                            </section>
                        </section>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import articleSerchingCard from '../items/cards/ArticleSerchingCardComponent'
    export default {
        components: {
            articleSerchingCard,
        },
        data: function () {
            return {
                articles: [],
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
                .get('../api/articleSearch/' + this.$route.query.query)
                .then(response => {
                    this.articles = response.data
                })
                .catch(error =>{
                })
            }
        }
    }
</script>
