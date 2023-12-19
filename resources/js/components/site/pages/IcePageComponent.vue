<template>
    <div class="container">
        <span v-if="article_loading">
            <articlePreloader />
        </span>
        <span v-else-if="!article_loading">
            <icePage 
                :article="ice" 
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
    import articlePreloader from "../items/article/ArticlePreloaderComponent.vue";
    import icePage from '../items/IceComponent'
    import metaData from '../items/MetaDataComponent'

    export default {
        data: function () {
            return {
                ice: [],
                article_loading: true
            }
        },
        components: {
            articlePreloader,
            icePage,
            metaData,
        },
        mounted() {
            this.get_ice()
        },
        watch: {
            '$route' (to, from) {
                this.ice = [],
                this.get_ice(),
                window.scrollTo(0,0)
            }
        },
        methods: {
            get_ice(){
                axios
                .get('/article/ice/'+localStorage.getItem('lang')+'/'+this.$route.params.url_title)
                .then(response => {
                    this.ice = response.data

                    this.$refs.article_page.update_similar_articles_component(this.ice.id)
                })
                .catch(error =>{
                })
                .finally(() => this.article_loading = false);
            },
        }
    }
</script>