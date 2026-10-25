<template>
    <div class="container">
        <span v-if="article_loading">
            <articlePreloader />
        </span>
        <!-- Coming soon gyms have no page yet — friendly placeholder instead of the article -->
        <div v-else-if="is_coming_soon" class="indoor_coming_soon_page">
            <i class="fa fa-clock-o indoor_coming_soon_icon" aria-hidden="true"></i>
            <h1>{{ $t('guide.article.coming_soon_page_title') }}</h1>
            <h2 v-if="indoors.locale_data">{{ indoors.locale_data.title }}</h2>
            <p>{{ $t('guide.article.coming_soon_page_text') }}</p>
            <router-link class="btn btn-primary" :to="($i18n.locale === 'ka' ? '/ka' : '') + '/indoor'">
                {{ $t('guide.article.back_to_indoor_list') }}
            </router-link>

            <metaData
                v-if="indoors.locale_data"
                :title = "indoors.locale_data.title"
                :description = "indoors.locale_data.description"
                :image = "'/public/images/indoor_img/'+indoors.image"
            />
        </div>
        <span v-else-if="!article_loading">
            <IndoorWorkStatusAlert v-if="indoors.global_data" :status="indoors.global_data.work_status" style="margin-top: 20px;" />
            <articlePage
                :article_prop="indoors"
                ref="article_page"
            />

            <metaData
                :title = "indoors.locale_data.title"
                :description = "indoors.locale_data.description"
                :image = "'/public/images/indoor_img/'+indoors.image"
            />
        </span>
    </div>
</template>

<script>
    import { remember_viewed } from '../../../../services/guide/recentlyViewed.js'
    import articlePreloader from "../../items/article/ArticlePreloaderComponent.vue";
    import articlePage from '../../items/pages_main_components/ArticleComponent'
    import metaData from '../../items/MetaDataComponent'
    import IndoorWorkStatusAlert from '../../items/IndoorWorkStatusAlert.vue'

    export default {
        data: function () {
            return {
                indoors: [],
                article_loading: true
            }
        },
        computed: {
            is_coming_soon() {
                return !!(this.indoors.global_data && this.indoors.global_data.work_status === 'coming_soon')
            },
        },
        components: {
            IndoorWorkStatusAlert,
            articlePreloader,
            articlePage,
            metaData
        },
        mounted() {
            this.get_indoors()
        },
        watch: {
            '$route' (to, from) {
                this.indoors = []
                this.get_indoors(),
                window.scrollTo(0,0)
            }
        },
        methods: {
            get_indoors(){
                axios
                .get('/get_article/get_locale_article_on_page/indoor/'+localStorage.getItem('lang')+'/'+this.$route.params.url_title)
                .then(response => {
                    this.indoors = response.data
                    if (response.data && response.data.global_data && response.data.locale_data) {
                        remember_viewed({ category: 'indoor', url_title: response.data.global_data.url_title, title: response.data.locale_data.title, image: response.data.image })
                    }

                    // Not rendered for coming soon gyms
                    if (this.$refs.article_page) {
                        this.$refs.article_page.update_similar_articles_component(this.indoors.id)
                    }
                })
                .catch(error =>{
                })
                .finally(() => this.article_loading = false);
            },
        }
    }
</script>

<style scoped>
    .indoor_coming_soon_page {
        text-align: center;
        padding: 60px 15px 80px;
    }
    .indoor_coming_soon_icon {
        font-size: 4em;
        color: var(--cg-accent);
        margin-bottom: 15px;
    }
    .indoor_coming_soon_page h1 {
        font-weight: bold;
    }
    .indoor_coming_soon_page h2 {
        color: var(--cg-accent);
        font-size: 1.5em;
    }
    .indoor_coming_soon_page p {
        color: var(--cg-stone);
        font-size: 1.1em;
        max-width: 600px;
        margin: 15px auto 25px;
    }
</style>