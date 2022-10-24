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
        data(){
            return {
                image_dir: '',
                route: '',
                similar_article: []
            }
        },
        mounted() {
            this.get_same_articles()
        },
        watch: {
            '$route' (to, from) {
                this.get_same_articles()

                window.scrollTo(0,0)
            }
        },
        methods: {
            get_same_articles() {
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
            },

            test(){
                console.log('test')
            }
        }
    }
</script>