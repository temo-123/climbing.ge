<template>
    <div class="h-recent-work" id="other">
        <div class="container">

            <h2 class='index_h2'>{{__ ('title other')}}</h2>

            <div class="bar"><i class="fa fa-dribbble"></i></div>
            <h6> <span v-html="this.$siteData.other_activity_description"></span> </h6>

            <div v-if="this.other_articles.length > 0">
                <articleComponent 
                    v-for="other_article in other_articles"
                    :key='other_article.id'
                    :image_dir="'images/other_img/'"
                    :article="other_article"
                    :route="'other/'+other_article.url_title"
                />
            </div>
            <div v-else>
                <emptyPageComponent />
            </div>
        </div>  
    </div>
</template>

<script>
    import articleComponent from '../items/cards/ArticleCardComponent'
    import emptyPageComponent from '../items/EmptyPageComponent'
    export default {
        data: function () {
            return {
                other_articles: []
            };
        },
        components: {
            articleComponent,
            emptyPageComponent,
        },
        mounted() {
            this.get_other_articles()
        },
        methods: {
            get_other_articles(){
                axios
                .get('../api/other')
                .then(response => {
                    this.other_articles = response.data
                })
                .catch(error =>{
                })
            }
        }
    }
</script>

<style>

</style>