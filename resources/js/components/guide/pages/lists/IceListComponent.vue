<template>
    <div class="h-recent-work" id="other">
        <div class="container">

            <h1 class='index_h2'>{{ $t('guide.title.ice climbing')}}</h1>

            <div class="bar"><i class="fa fa-dribbble"></i></div>
            
            <h2 class="article_list_short_description">
                {{this.$siteData.data.ice_description}}
            </h2>

            <div class="row otdoor_buttoms">
                <div class="col-md-12">

                    <mostPopularRoutesModal :routeCategories="routeCategories" :defaultRouteType="'ice'" />
                </div>
            </div>

            <div v-if="indoor_article_loading">
                <content-loader
                    viewBox="0 0"
                    primaryColor="#f3f3f3"
                    secondaryColor="#279fbbb0"
                />
            </div>
            <div v-else>
                <div class="article_card_container" v-if="this.ices.length > 0">
                    <articleComponent 
                        v-for="ice in ices"
                        :key='ice.id'
                        :image_dir="'images/ice_img/'"
                        :article="ice"
                        :route="'ice/'+ice.global_data.url_title"
                    />
                </div>
                <div v-else>
                    <emptyPageComponent />
                </div>
            </div>
        </div>
        
        <metaData 
            :title = "$t('guide.meta.ice climbing')"
            :description = "this.$siteData.data.ice_description"
            :image = "'/public/images/meta_img/ice.jpg'"
        />
    </div>
</template>

<script>
    import articleComponent from '../../items/cards/ArticleCardComponent'
    // import emptyPageComponent from '../items/EmptyPageComponent'
    import emptyPageComponent from '../../../global_components/EmptyPageComponent'
    import { ContentLoader } from 'vue-content-loader'
    import metaData from '../../items/MetaDataComponent'

    import axios_mixin from '../../../../mixins/axios_mixin'

    import mostPopularRoutesModal from '../../items/climbing_routes/items/modals/MostPopularRoutesModal.vue'

    export default {
        mixins: [
            axios_mixin
        ],
        data: function () {
            return {
                ices: [],
                indoor_article_loading: true,

                routeCategories: [
                    // { value: 'sport', label: 'guide.sector.sport_climbing' },
                    // { value: 'boulder', label: 'guide.sector.bouldering' },
                    { value: 'dry', label: 'guide.sector.dry' },
                    { value: 'ice', label: 'guide.sector.ice' }
                ]
            };
        },
        components: {
            articleComponent,
            emptyPageComponent,
            ContentLoader,
            metaData,
            mostPopularRoutesModal,
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
                this.get_articles('ice', localStorage.getItem('lang'),
                    (data) => {
                        this.ices = data;
                    },
                    (error) => {
                        console.error('Error fetching articles:', error);
                    },
                    () => {
                        this.indoor_article_loading = false;
                    }
                );
            }
        }
    }
</script>

<style>
    .otdoor_buttoms{
        margin: 1em 0;
    }
</style>