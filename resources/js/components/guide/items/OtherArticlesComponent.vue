<template>
    <div class="h-recent-work" id="other" v-if="other_articles.length > 0">
        <div class="container">

            <h2 class='index_h2 functional_block_title'>
                <router-link :to="{name: 'others', params: {lang: this.$i18n.locale}}">
                    {{ $t('guide.title.other') }}
                </router-link>
            </h2>

            <div class="bar"><i class="fa fa-dribbble"></i></div>
            <h3 class="article_list_short_description"> <span v-html="$siteData.data.other_activity_description"></span> </h3>
            
            <div class="article_card_container">
                <articleCardComponent 
                    v-for="other_article in other_articles"
                        :key='other_article.id'
                        :image_dir="'images/other_img/'"
                        :article="other_article"
                        :route="'other/'+other_article.global_data.url_title"
                />
            </div>
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
/* .article_card_container {
    display: flex;
    flex-flow: row wrap;
} */
</style>