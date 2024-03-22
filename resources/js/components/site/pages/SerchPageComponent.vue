<template>
    <div>
        <div class="container">

            <h1  class='serch_title'>Search climbing spot</h1>

            <div class="row">
                <div class="col-md-12">
                    <h2 class="text-center"> The your search query is:"<b> {{ this.$route.query.query }} </b>". Please use only english text for your query!!!</h2>
                    
                    <div v-if="article_search_loading">
                        <content-loader
                            viewBox="0 0"
                            primaryColor="#f3f3f3"
                            secondaryColor="#279fbbb0"
                        />
                    </div>
                    <div class="col-sm-12" v-else>
                        <hr>
                        <span v-if="articles.length == 0">
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
                        </span>
                    </div>
                </div>
            </div>
        </div>
        <metaData 
            :title = " $t('guide.meta.search') "
            :description = "'Search climbing spots in Georgia. Query - ' + this.$route.query.query"
            :image = "'/public/images/meta_img/outdoor.jpg'"
        />
    </div>
</template>

<script>
    import articleSerchingCard from '../items/cards/ArticleSerchingCardComponent'
    import { ContentLoader } from 'vue-content-loader'
    import metaData from '../items/MetaDataComponent'
    export default {
        components: {
            articleSerchingCard,
            ContentLoader,
            metaData
        },
        data: function () {
            return {
                articles: [],
                article_search_loading: true
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
                .post('/articleSearch/' + this.$route.query.query)
                .then(response => {
                    this.articles = response.data
                })
                .catch(error =>{
                })
                .finally(() => this.article_search_loading = false)
            }
        }
    }
</script>
