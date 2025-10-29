<template>
    <div class="row similar_articles" v-if="this.similar_article.length > 0">
        <div class="container">
            <h2 id="other">{{ $t('guide.article.title.similar articles')}}</h2>
            <hr class="other_line">
        </div>
        <span v-if="article_category == 'outdoor'">
            <SimilarOutdoorAreaCardComponent
                v-for="other_article in similar_article" :key='other_article.area.global_data.id'
                    :article="other_article"
                    :route="'../../../' + route + other_article.area.global_data.url_title"
                    :image_dir="'images/' + article_category + '_img'"
                    :category = "article_category"
            />
        </span>
        <span v-else>
            <SimilarArticleCardComponent
                v-for="other_article in similar_article" :key='other_article.id'
                    :article="other_article"
                    :route="'../../../' + route + other_article.global_data.url_title"
                    :image_dir="'images/' + article_category + '_img'"
                    :category = "article_category"
            />
        </span>
    </div>
</template>

<script>
    import SimilarArticleCardComponent from './cards/SimilarArticleCardComponent.vue'
    import SimilarOutdoorAreaCardComponent from './cards/SimilarOutdoorAreaCardComponent.vue'

    export default {
        props: [
            'article_id',
            'article_category',
            'img_dir',
            'route'
        ],
        components: {
            SimilarArticleCardComponent,
            SimilarOutdoorAreaCardComponent
        },
        data(){
            return {
                similar_article: [],

                id: this.article_id
            }
        },
        mounted() {
            this.get_same_articles()
        },
        watch: {
            '$route' (to, from) {
                window.scrollTo(0,0)
            }
        },
        methods: {
            get_same_articles() {
                // console.log(this.id);
                axios
                .post('/article/similar_article/'+localStorage.getItem('lang'), {
                    article_id: this.id,
                    article_category: this.article_category,
                })
                .then(response => {
                    this.similar_article = []
                    this.similar_article = response.data
                })
                .catch(error =>{

                })
            },

            update(id){
                this.id = id
                this.get_same_articles()
            }
        }
    }
</script>