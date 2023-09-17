<template>
    <div class="container">
        <span v-if="article_loading">
            <articlePreloader />
        </span>
        <span v-else-if="!article_loading">
            <outdoor 
                :article="outdoor" 
                ref="article_page"
            />

            <metaData 
                :title = "outdoor[0].title"
                :description = "outdoor[0].description"
                :image = "'/public/images/outdoor_img/'+outdoor.image"
            />
        </span>
    </div>
</template>

<script>
    import articlePreloader from "../items/article/ArticlePreloaderComponent.vue";
    import outdoor from '../items/OutdoorComponent'
    import metaData from '../items/MetaDataComponent'

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
        methods: {
            get_outdoor(){
                this.outdoor = []
                axios
                .get('../../api/article/outdoor/'+localStorage.getItem('lang')+'/'+this.$route.params.url_title)
                .then(response => {
                    this.outdoor = response.data

                    this.$refs.article_page.update_similar_articles_component(this.outdoor.id)
                })
                .catch(error =>{
                })
                .finally(() => this.article_loading = false);
            },
        }
    }
</script>