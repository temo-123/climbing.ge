<template>
    <div class="container">
        <span v-if="article_loading">
            <articlePreloader />
        </span>
        <span v-else-if="!article_loading">
            <outdoor 
                :article_prop="outdoor" 
                ref="article_page"
            />

            <metaData 
                :title = "outdoor.locale_data.title"
                :description = "outdoor.locale_data.description"
                :image = "'/public/images/outdoor_img/'+outdoor.image"
            />
        </span>
    </div>
</template>

<script>
    import articlePreloader from "../../items/article/ArticlePreloaderComponent.vue";
    import outdoor from '../../items/pages_main_components/OutdoorComponent'
    import metaData from '../../items/MetaDataComponent'

    export default {
        data: function () {
            return {
                outdoor: [],
                article_loading: true
            }
        },
        components: {
            articlePreloader,
            outdoor,
            metaData
        },
        mounted() {
            this.get_outdoor()
        },

        watch: {
            '$route' (to, from) {
                this.get_outdoor(),
                window.scrollTo(0,0)
            }
        },
        
        updated() {
            // Check for navigation parameters when component updates
            this.$nextTick(() => {
                if (this.$refs.article_page && (this.$route.query.sector || this.$route.query.route)) {
                    this.$refs.article_page.handleRouteNavigation();
                }
            });
        },
        methods: {

            get_outdoor(){
                this.outdoor = []
                axios
                .get('/get_article/outdoor/'+localStorage.getItem('lang')+'/'+this.$route.params.url_title)
                .then(response => {
                    this.outdoor = response.data

                    // Wait for the article page to be fully rendered before handling navigation
                    this.$nextTick(() => {
                        if (this.$refs.article_page) {
                            this.$refs.article_page.update_similar_articles_component(this.outdoor.global_data.id)
                        }
                    })
                })
                .catch(error =>{
                    console.error('Error loading outdoor article:', error);
                })
                .finally(() => this.article_loading = false);
            },
        }
    }
</script>