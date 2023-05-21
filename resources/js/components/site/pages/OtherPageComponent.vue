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
                :article="other" 
                ref="article_page"
            />

            <metaData 
                :title = "other[0].title"
                :description = "other[0].description"
                :image = "'/public/images/other_img/'+other.image"
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
                other: [],
                article_loading: true
            }
        },
        components: {
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