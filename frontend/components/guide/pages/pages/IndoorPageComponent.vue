<template>
    <div class="container">
        <span v-if="article_loading">
            <articlePreloader />
        </span>
        <span v-else-if="!article_loading">
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
    import articlePreloader from "../../items/article/ArticlePreloaderComponent.vue";
    import articlePage from '../../items/pages_main_components/ArticleComponent'
    import metaData from '../../items/MetaDataComponent'

    export default {
        data: function () {
            return {
                indoors: [],
                article_loading: true
            }
        },
        components: {
            articlePreloader,
            articlePage,
            metaData,
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

                    this.$refs.article_page.update_similar_articles_component(this.indoors.id)
                })
                .catch(error =>{
                })
                .finally(() => this.article_loading = false);
            },
        }
    }
</script>