<template>
    <div class="container">
        <span v-if="article_loading">
            <articlePreloader />
        </span>
        <span v-else-if="!article_loading">
            <articlPage 
                :article_prop="other" 
                ref="article_page"
            />

            <metaData 
                :title = "other.locale_data.title"
                :description = "other.locale_data.description"
                :image = "'/public/images/other_img/'+other.image"
            />
        </span>
    </div>
</template>

<script>
    import articlePreloader from "../items/article/ArticlePreloaderComponent.vue";
    import articlPage from '../items/pages_main_components/ArticleComponent'
    import metaData from '../items/MetaDataComponent'

    export default {
        data: function () {
            return {
                other: [],
                article_loading: true
            }
        },
        components: {
            articlePreloader,
            articlPage,
            metaData
        },
        mounted() {
            this.get_other()
        },
        watch: {
            '$route' (to, from) {
                this.other = [],
                this.get_other(),
                window.scrollTo(0,0)
            }
        },
        methods: {
            get_other(){
                axios
                .get('../api/article/other/'+localStorage.getItem('lang')+'/'+this.$route.params.url_title)
                .then(response => {
                    this.other = response.data

                    this.$refs.article_page.update_similar_articles_component(this.other.id)
                })
                .catch(error =>{
                })
                .finally(() => this.article_loading = false);
            },
        }
    }
</script>