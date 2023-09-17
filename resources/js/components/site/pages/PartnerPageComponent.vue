<template>
    <div class="container">
        <span v-if="article_loading">
            <articlePreloader />
        </span>
        <span v-else-if="!article_loading">
            <articlPage 
                :article="partner" 
                ref="article_page"
            />

            <metaData 
                :title = "partner[0].title"
                :description = "partner[0].description"
                :image = "'/public/images/partner_img/'+partner.image"
            />
        </span>
    </div>
</template>

<script>
    import articlePreloader from "../items/article/ArticlePreloaderComponent.vue";
    import articlPage from '../items/ArticleComponent'
    import metaData from '../items/MetaDataComponent'

    export default {
        data: function () {
            return {
                partner: [],
                article_loading: true
            }
        },
        components: {
            articlePreloader,
            articlPage,
            metaData
        },
        mounted() {
            this.get_partner()
        },
        watch: {
            '$route' (to, from) {
                this.partner = [],
                this.get_partner(),
                window.scrollTo(0,0)
            }
        },
        methods: {
            get_partner(){
                axios
                .get('../api/article/tech_tip/'+localStorage.getItem('lang')+'/'+this.$route.params.url_title)
                .then(response => {
                    this.partner = response.data

                    this.$refs.article_page.update_similar_articles_component(this.partner.id)
                })
                .catch(error =>{
                })
                .finally(() => this.article_loading = false);
            },
        }
    }
</script>