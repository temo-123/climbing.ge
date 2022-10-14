<template>
    <div class="row similar_articles" v-if="this.similar_article.length > 0">
        <div class="container">
            <h2 id="other">{{ $t('similar articles')}}</h2>
            <hr class="other_line">
        </div>
        <SimilarArticleCardComponent
            v-for="other_article in similar_article" :key='other_article.id'
                :article="other_article"
                :route="route + other_article.area.url_title"
                :image_dir="'images/' + img_dir"
        />
    </div>
</template>

<script>
    import SimilarArticleCardComponent from './cards/SimilarArticleCardComponent.vue'

    export default {
        props: [
            'article_id',
            'article_category',
            'img_dir',
            'route'
        ],
        components: {
            SimilarArticleCardComponent
        },
        // mounted() {
        //     this.get_similar_articles()
        // },
        // methods: {
        //     get_similar_articles(){
        //         axios
        //         .put('../api/article/' + this.article_id, {})
        //         .then(response => {
        //             this.other_articles = response.data

        //             if (this.article_category == 'news') {
        //                 this.image_dir = 'news_img/',
        //                 this.route_url = 'news/'
        //             }
        //         })
        //         .catch(error =>{
        //         })
        //     }
        // }
        data(){
            return {
                image_dir: '',
                route: '',
                similar_article: []
            }
        },
        mounted() {
            axios
                .post('../api/similar_article/'+localStorage.getItem('lang'), {
                    article_id: this.article_id,
                    article_category: this.article_category,
                })
                .then(response => {
                    this.similar_article = response.data
                })
                .catch(error =>{

                })
        }
    }
</script>