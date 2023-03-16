<template>
    <div class="h-recent-work" id="other">
        <div class="container">

            <h2 class='index_h2'>{{ $t('guide.title.other')}}</h2>

            <div class="bar"><i class="fa fa-dribbble"></i></div>
            <!-- <h6> <span v-html="this.$siteData.other_activity_description"></span> </h6> -->
            <h2 style="text-align: center;">
                <span v-html="this.$siteData.other_activity_description"></span>
            </h2>
            <div v-if="other_article_loading">
                <content-loader
                    viewBox="0 0"
                    primaryColor="#f3f3f3"
                    secondaryColor="#279fbbb0"
                >

                </content-loader>
            </div>
            <div v-else>
                <div v-if="this.other_articles.length > 0">
                    <articleComponent 
                        v-for="other_article in other_articles"
                        :key='other_article.id'
                        :image_dir="'images/other_img/'"
                        :article="other_article"
                        :route="'other/'+other_article.url_title"
                    />
                </div>
                <div v-else>
                    <emptyPageComponent />
                </div>
            </div>
        </div> 

        <metaData 
            :title = "$t('guide.title.other')"
            :description = "this.$siteData.other_activity_description"
            :image = "'../../../../public/images/meta_img/other.jpg'"
        />
    </div>
</template>

<script>
    import articleComponent from '../items/cards/ArticleCardComponent'
    // import emptyPageComponent from '../items/EmptyPageComponent'
    import emptyPageComponent from '../../global_components/EmptyPageComponent'
    import metaData from '../items/MetaDataComponent'
    import { ContentLoader } from 'vue-content-loader'
    export default {
        data: function () {
            return {
                other_articles: [],
                other_article_loading: true
            };
        },
        components: {
            articleComponent,
            emptyPageComponent,
            ContentLoader,
            metaData
        },
        mounted() {
            this.get_other_articles()
        },
        watch: {
            '$route' (to, from) {
                this.get_other_articles(),
                window.scrollTo(0,0)
            }
        },
        methods: {
            get_other_articles(){
                axios
                .get('../api/articles/other/'+localStorage.getItem('lang'))
                .then(response => {
                    this.other_articles = response.data
                })
                .catch(error =>{
                })
                .finally(() => this.other_article_loading = false)
            }
        }
    }
</script>

<style>

</style>