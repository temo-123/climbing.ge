<template>
    <div class="container">
        <articlPage :article="news" />

        <metaData 
            :title = "news[0].title"
            :description = "news[0].description"
            :image = "'../../../../public/images/news_img/'+news.image"
        />
    </div>
</template>

<script>
    import articlPage from '../items/ArticleComponent'
    import metaData from '../items/MetaDataComponent'

    export default {
        data: function () {
            return {
                news: [],
            }
        },
        components: {
            articlPage,
            metaData
        },
        mounted() {
            this.get_news()
        },
        methods: {
            get_news(){
                axios
                // .get('../api/get_article/news/'+this.$route.params.url_title)
                .get('../api/article/news/'+localStorage.getItem('lang')+'/'+this.$route.params.url_title)
                .then(response => {
                    this.news = response.data
                })
                .catch(error =>{
                })
            },
        }
    }
</script>