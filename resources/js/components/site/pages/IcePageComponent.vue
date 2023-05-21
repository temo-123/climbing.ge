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
                :article="ice" 
                ref="article_page"
            />

            <metaData 
                :title = "ice[0].title"
                :description = "ice[0].description"
                :image = "'/public/images/event_img/'+ice.image"
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
                ice: [],
                article_loading: true
            }
        },
        components: {
            articlPage,
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
                .get('../api/article/ice/'+localStorage.getItem('lang')+'/'+this.$route.params.url_title)
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