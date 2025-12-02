<template>
    <div class="container">
        <span v-if="article_loading">
            <articlePreloader />
        </span>
        <span v-else-if="!article_loading">
            <articlPage :article_prop="news" />
        </span>

        <metaData 
            :title = "news.locale_data.title"
            :description = "news.locale_data.description"
            :image = "'/public/images/news_img/'+news.image"
        />
    </div>
</template>

<script>
    import articlPage from '../../items/pages_main_components/ArticleComponent'
    import metaData from '../../items/MetaDataComponent'
    import articlePreloader from "../../items/article/ArticlePreloaderComponent.vue";

    export default {
        data: function () {
            return {
                news: [],
                article_loading: false
            }
        },
        components: {
            articlePreloader,
            articlPage,
            metaData
        },
        mounted() {
            this.get_news()
        },
        watch: {
            '$route' (to, from) {
                this.get_news(),
                window.scrollTo(0,0)
            }
        },
        methods: {
            get_news(){
                this.article_loading = true
                axios
                .get('/get_article/news/'+localStorage.getItem('lang')+'/'+this.$route.params.url_title)
                .then(response => {
                    this.news = response.data
                })
                .catch(error =>{
                })
                .finally(() => this.article_loading = false);
            },
        }
    }
</script>