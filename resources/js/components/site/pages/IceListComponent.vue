<template>
    <div class="h-recent-work" id="other">
        <div class="container">

            <h2 class='index_h2'>{{ $t('guide.title.ice climbing')}}</h2>

            <div class="bar"><i class="fa fa-dribbble"></i></div>
            <!-- <h6><span v-html="this.$siteData.other_activity_description"></span></h6> -->
            <h2 style="text-align: center;">
                <span v-html="this.$siteData.ice_description"></span>
            </h2>

            <div v-if="indoor_article_loading">
                <content-loader
                    viewBox="0 0"
                    primaryColor="#f3f3f3"
                    secondaryColor="#279fbbb0"
                />
            </div>
            <div v-else>
                <div v-if="this.ices.length > 0">
                    <articleComponent 
                        v-for="ice in ices"
                        :key='ice.id'
                        :image_dir="'images/ice_img/'"
                        :article="ice"
                        :route="'ice/'+ice.url_title"
                    />
                </div>
                <div v-else>
                    <emptyPageComponent />
                </div>
            </div>
        </div>
        
        <metaData 
            :title = "$t('site.meta.ice climbing')"
            :description = "this.$siteData.ice_description"
            :image = "'../../../../public/images/meta_img/ice.jpg'"
        />
    </div>
</template>

<script>
    import articleComponent from '../items/cards/ArticleCardComponent'
    // import emptyPageComponent from '../items/EmptyPageComponent'
    import emptyPageComponent from '../../global_components/EmptyPageComponent'
    import { ContentLoader } from 'vue-content-loader'
    import metaData from '../items/MetaDataComponent'

    export default {
        data: function () {
            return {
                ices: [],
                indoor_article_loading: true
            };
        },
        components: {
            articleComponent,
            emptyPageComponent,
            ContentLoader,
            metaData
        },
        mounted() {
            this.get_ices()
        },
        watch: {
            '$route' (to, from) {
                this.get_ices(),
                window.scrollTo(0,0)
            }
        },
        methods: {
            get_ices(){
                axios
                .get('../api/articles/ice/'+localStorage.getItem('lang'))
                .then(response => {
                    this.ices = response.data
                })
                .catch(error =>{
                })
                .finally(() => this.indoor_article_loading = false)
            }
        }
    }
</script>

<style>

</style>