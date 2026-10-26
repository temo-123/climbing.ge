<template>
    <section class="similar_articles cg-similar" v-if="this.similar_article.length > 0">
        <h2 id="other" class="cg-section-title cg-section-title--left">{{ $t('guide.article.title.similar articles')}}</h2>
        <div class="cg-similar__grid">
            <template v-if="article_category == 'outdoor'">
                <SimilarOutdoorAreaCardComponent
                    v-for="other_article in similar_article" :key='other_article.area.global_data.id'
                        :article="other_article"
                        :route="'../../../' + route + other_article.area.global_data.url_title"
                        :image_dir="'images/' + article_category + '_img'"
                        :category = "article_category"
                />
            </template>
            <template v-else>
                <SimilarArticleCardComponent
                    v-for="other_article in similar_article" :key='other_article.id'
                        :article="other_article"
                        :route="'../../../' + route + other_article.global_data.url_title"
                        :image_dir="'images/' + article_category + '_img'"
                        :category = "article_category"
                />
            </template>
        </div>
    </section>
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
                .post('/get_article/get_similar_locale_article/'+localStorage.getItem('lang'), {
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

<style>
    /* similar articles: an even grid of same-size cards (4 → 2 per row) */
    .cg-similar { margin: 2rem 0 1rem; }
    .cg-similar__grid { display: grid; grid-template-columns: repeat(4, minmax(0, 1fr)); gap: 1.25rem; }
    @media (max-width: 991.98px) { .cg-similar__grid { grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 1rem; } }
    .cg-sim-card { display: flex; flex-direction: column; gap: .5rem; height: 100%; color: var(--cg-ink); text-decoration: none; }
    .cg-sim-card__media { position: relative; aspect-ratio: 4 / 3; overflow: hidden; border-radius: var(--cg-r-lg); box-shadow: var(--cg-shadow-sm); }
    .cg-sim-card__media #image, .cg-sim-card__media img { position: absolute; inset: 0; width: 100% !important; height: 100% !important; max-width: none; margin: 0; object-fit: cover; border-radius: 0; transition: transform .35s var(--cg-ease, ease); }
    .cg-sim-card:hover .cg-sim-card__media img { transform: scale(1.04); }
    .cg-sim-card__title { display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; margin: 0 !important; font-size: 1rem !important; font-weight: 800; line-height: 1.3; color: var(--cg-ink) !important; text-transform: none; }
    .cg-sim-card:hover .cg-sim-card__title { color: var(--cg-accent-dark) !important; }
    .cg-sim-card__meta { display: flex; flex-wrap: wrap; gap: .3rem; }
    .cg-sim-card__meta span { padding: .1rem .5rem; border-radius: 1rem; background: var(--cg-chalk-2); color: var(--cg-stone); font-size: .75rem; font-weight: 700; }
    @media (prefers-reduced-motion: reduce) { .cg-sim-card__media img { transition: none; } }
</style>
