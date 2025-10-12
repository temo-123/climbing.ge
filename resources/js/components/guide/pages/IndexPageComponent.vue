<template>
    <div>
        <h1 style="display: none;"><span v-html="this.$siteData.data.guid_title"></span></h1>

        <swiperComponent 
            :category_prop="'guide'"
            :image_path_prop="'/images/head_slider_img/guide/'"
        />

        <whatWeDoComponent />

        <div class="container h-recent-work" v-if="this.$globalSiteData.data.map && this.$globalSiteData.data.map != 'function map() { [native code] }'">

            <h2 class='index_h2'>{{ $t('guide.title.topo') }}</h2>

            <div class="bar"><i class="fa fa-map-marker"></i></div>
            
            <h3 class="article_list_short_description"> {{this.$siteData.data.topo_description}} </h3>

            <div :style='"margin-bottom: 7%"'>
                <span v-html="this.$globalSiteData.data.map"></span>
            </div>
        </div>

        <div class="h-recent-work" v-if="lastNews != [] && lastNews">
            <div class="container">
                <h2 class='index_h2' id="news">{{ $t('guide.title.news') }}</h2>
                <div class="bar"><i class="fa fa-newspaper-o"></i></div>
                <h3 class="article_list_short_description"> {{ this.$siteData.data.news_description }} </h3>

                <span v-if="lastNews != []">
                    <bigNewsCard 
                        :news="lastNews"
                    />
                </span>
                
                <section v-if="newses.length >= 6" class="m-collection-strip mm-collection-strip--is-stretched m-component-stack mm-component-stack--is-stacked mm-component-stack--has-header">
                    <div class="m-collection-strip--carousel">
                        <div class="m-collection-strip--carousel-wrapper">
                            <div class="m-collection-strip--carousel-container">
                                <div class="m-collection-strip--carousel-content">
                                    <newsCard
                                        v-for="news in newses"
                                        :key='news.id'
                                        :news="news"
                                    />
                                </div>
                            </div>
                        </div>
                    </div>
                </section>

            </div>
        </div>

        <specialArticleComponent/>

        <eventComponent/>

        <otherArticlesComponent/>

        <techtipsComponent/>

        <indexGalleryComponent />

        <!-- <instaPost /> -->

        <teamMembersSliderComponent />

        <productsSliderComponent />
        
        <metaData 
            :title = " $t('guide.meta.index') "
            :description = "this.$siteData.data.guid_short_description"
            :image = "'/public/images/meta_img/outdoor.jpg'"
        />
    </div>
</template>

<script>
    import indexGalleryComponent from '../items/galleries/IndexGalleryComponent'
    import techtipsComponent from '../items/TechtipsComponent'
    import otherArticlesComponent from '../items/OtherArticlesComponent'
    import eventComponent from '../items/EventComponent'
    import swiperComponent from '../../global_components/SwiperComponent.vue'
    import whatWeDoComponent from '../items/WhatWeDoComponent'

    import newsCard from '../items/cards/NewsCardComponent'
    import bigNewsCard from '../items/cards/BigNewsCardComponent'
    import specialArticleComponent from '../items/SpecialArticleComponent'

    import productsSliderComponent from '../items/shop_items_for_guide/ProductsSliderComponent'
    import teamMembersSliderComponent from '../items/TeamMembersSliderComponent'

    // import instaPost from '../../global_components/InstaPostsComponent.vue'

    import metaData from '../items/MetaDataComponent'

    export default {
        data: function () {
            return {
                newses: [],
                lastNews: [],
                products: [],

                team_members: [],
                partners: [],
            };
        },
        components: {
            metaData,
            indexGalleryComponent,
            techtipsComponent,
            otherArticlesComponent,
            eventComponent,
            swiperComponent,
            newsCard,
            bigNewsCard,
            whatWeDoComponent,
            specialArticleComponent,
            productsSliderComponent,
            teamMembersSliderComponent,
            // instaPost,
        },
        mounted() {
            this.get_data()
        },
        watch: {
            '$route' (to, from) {
                this.get_data()

                window.scrollTo(0,0)
            }
        },
        methods: {
            get_data(){
                this.get_news()
            },

            get_news(){
                axios
                .get('articles/news/'+localStorage.getItem('lang'))
                .then(response => {
                    this.newses = response.data.splice(1, 7);
                    this.lastNews = response.data[0]
                })
                .catch(error =>{
                })
            },
        }
    }
</script>
