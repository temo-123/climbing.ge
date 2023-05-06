<template>
    <div class="h-recent-work" id="other" v-if="other_articles.length > 0">
        <div class="container">

            <h2 class='index_h2'>{{ $t('guide.title.other') }}</h2>

            <div class="bar"><i class="fa fa-dribbble"></i></div>
            <h3> <span v-html="$siteData.other_activity_description"></span> </h3>

            <articleCardComponent 
                v-for="other_article in other_articles"
                    :key='other_article.id'
                    :image_dir="'images/other_img/'"
                    :article="other_article"
                    :route="'other/'+other_article.url_title"
            />
        </div>  
    </div>
</template>

<script>
    import articleCardComponent from './cards/ArticleCardComponent'
    export default {
        components: {
            articleCardComponent,
        },
        mounted() {
            this.get_other_articles()
        },
        watch: {
            '$route' (to, from) {
                this.get_other_articles()
            }
        },
        data: function () {
            return {
                other_articles: [],
            };
        },
        methods: {
            async get_other_articles(){
                axios
                .get('../api/articles/other/'+localStorage.getItem('lang'))
                .then(response => {
                    this.other_articles = response.data
                })
                .catch(error =>{
                })
            },
        }
    }
</script>

<style>

</style>