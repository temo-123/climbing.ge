<template>
    <div class="container">
        <span v-if="article_loading">
            <div class="row">
                <div class="col-md-4">
                    <img :src="'../../../../../../public/images/site_img/loading.gif'" alt="loading">
                </div>
            </div>
        </span>
        <span v-else-if="!article_loading">
            <articlPage 
                :article="tech_tips" 
                ref="article_page"
            />

            <metaData 
                :title = "tech_tips[0].title"
                :description = "tech_tips[0].description"
                :image = "'../../../../public/images/security_img/'+tech_tips.image"
            />
        </span>
    </div>
</template>

<script>
    import articlPage from '../items/ArticleComponent'
    import metaData from '../items/MetaDataComponent'

    export default {
        data: function () {
            return {
                tech_tips: [],
                article_loading: true
            }
        },
        components: {
            articlPage,
            metaData
        },
        mounted() {
            this.get_tech_tips()
        },
        watch: {
            '$route' (to, from) {
                this.tech_tips = [],
                this.get_tech_tips(),
                window.scrollTo(0,0)
            }
        },
        methods: {
            get_tech_tips(){
                axios
                .get('../api/article/tech_tip/'+localStorage.getItem('lang')+'/'+this.$route.params.url_title)
                .then(response => {
                    this.tech_tips = response.data

                    this.$refs.article_page.update_similar_articles_component(this.tech_tips.id)
                })
                .catch(error =>{
                })
                .finally(() => this.article_loading = false);
            },
        }
    }
</script>