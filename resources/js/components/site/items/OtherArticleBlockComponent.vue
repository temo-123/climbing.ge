<template>
  <div class="row similar_articles">
        <div class="container">
            <h2 id="other">{{__ ('similar articles')}}</h2>
            <hr class="other_line">
        </div>
        <otherArticleCard
            v-for="other_article in other_articles"
            :key='other_article.id'
            :image_dir="'images/'+image_dir"
            :article="other_article"
            :route="route_url"
        />
    </div>
</template>

<script>
    import otherArticleCard from './cards/OtherArticleCardComponent.vue'

    export default {
        props: [
            'article_id',
            'article_category'
        ],
        data: function () {
            return {
                other_articles: [],
                image_dir: '',
                route_url: '',
            };
        },
        components: {
            otherArticleCard
        },
        mounted() {
            this.get_similar_articles()
        },
        methods: {
            get_similar_articles(){
                axios
                .put('../api/article/' + this.article_id, {})
                .then(response => {
                    this.other_articles = response.data

                    if (this.article_category == 'news') {
                        this.image_dir = 'news_img/',
                        this.route_url = 'news/'
                    }
                })
                .catch(error =>{
                })
            }
        }
    }
</script>