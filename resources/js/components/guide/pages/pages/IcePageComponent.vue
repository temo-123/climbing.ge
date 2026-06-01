<template>
    <div class="container">
        <span v-if="article_loading">
            <articlePreloader />
        </span>
        <span v-else-if="!article_loading">
            <articlPage 
                :article_prop="ice" 
                ref="article_page"
            />

            <metaData 
                :title = "ice.locale_data.title"
                :description = "ice.locale_data.description"
                :image = "'/public/images/event_img/'+ice.image"
            />
        </span>
    </div>
</template>

<script>
    import articlePreloader from "../../items/article/ArticlePreloaderComponent.vue";
    import articlPage from '../../items/pages_main_components/ArticleComponent'
    import metaData from '../../items/MetaDataComponent'

    export default {
        data: function () {
            return {
                ice: [],
                article_loading: true
            }
        },
        components: {
            articlePreloader,
            articlPage,
            metaData,
        },
        mounted() {
            this.get_ice()
        },
        watch: {
            '$route' (to, from) {
                this.article_loading = true
                this.ice = []
                this.get_ice()
                window.scrollTo(0, 0)
            }
        },
        methods: {
            get_ice(){
                axios
                .get('/get_article/get_locale_article_on_page/ice/'+localStorage.getItem('lang')+'/'+this.$route.params.url_title)
                .then(response => {
                    this.ice = response.data
                })
                .catch(error =>{
                })
                .finally(() => this.article_loading = false)
            },
        }
    }
</script>