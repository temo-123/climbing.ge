<template>
    <div>
        <h1 style="display: none;"><span v-html="this.$siteData.guid_title"></span></h1>

        <swiperComponent 
            :category_prop="'guide'"
            :image_path_prop="'/images/head_slider_img/guide/'"
        />

        <whatWeDoComponent />

        <div class="container h-recent-work" v-if="this.$globalSiteData.map && this.$globalSiteData.map != 'function map() { [native code] }'">

            <h2 class='index_h2'>{{ $t('guide.title.topo') }}</h2>

            <div class="bar"><i class="fa fa-map-marker"></i></div>
            
            <h3 class="article_list_short_description"> {{this.$siteData.topo_description}} </h3>

            <div :style='"margin-bottom: 7%"'>
                <span v-html="this.$globalSiteData.map"></span>
            </div>
        </div>

        <div class="h-recent-work" v-if="lastNews != [] && lastNews">
            <div class="container">
                <h2 class='index_h2' id="news">{{ $t('guide.title.news') }}</h2>
                <div class="bar"><i class="fa fa-newspaper-o"></i></div>
                <h3 class="article_list_short_description"> {{ this.$siteData.news_description }} </h3>

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

        
        <div class="h-recent-work services" id="services" v-if="team_members != []">
            <div class="container">

                <h2 class='index_h2'>Team Members</h2>

                <div class="bar"><i class="fa fa-exclamation-triangle"></i></div>
                <h3 class='article_list_short_description'> Climbing.ge team members </h3>
                        
                <usersIconsComponent 
                    :users_prop="team_members"
                />
            </div>
        </div>

        <div class="row" v-if="products.length > 0">

            <h2 class="index_h2">{{ $t('shop.title.products') }}</h2>

            <div class="bar"><i class="fa fa-exclamation-triangle"></i></div>

            <h3 class="article_list_short_description">
                <span v-html="this.$siteData.shop_short_description"></span>
            </h3>

            <div class="col-sm-12">
                <section class="portfolio inner" id="portfolio" >
                    <div class="layout">
                        <ul class="grid">
                            <catalogItem
                                v-for="product in products"
                                :key='product.id'
                                :product_data="product"

                                @quick_view="quick_view_model"
                            />
                        </ul>
                    </div>
                </section>
            </div>
        </div>
        
        <metaData 
            :title = " $t('guide.meta.index') "
            :description = "this.$siteData.guid_short_description"
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

    import usersIconsComponent from '../../global_components/UsersIconsComponent.vue'

    // import instaPost from '../../global_components/InstaPostsComponent.vue'

    import catalogItem from '../items/shop_items_for_guide/CatalogItemComponent'

    import metaData from '../items/MetaDataComponent'

    export default {
        data: function () {
            return {
                newses: [],
                lastNews: [],
                products: [],

                team_members: [],
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
            usersIconsComponent,
            // instaPost,
            catalogItem
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
                this.get_products()
                this.get_team_members()
            },
            
            get_team_members(){
                axios
                .get('/user/team/get_team_members')
                .then(response => {
                    this.team_members = response.data
                })
                .catch(error =>{
                })
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

            get_products(){
                axios
                .get('/products/'+localStorage.getItem('lang'))
                .then(response => {
                    this.products = response.data.slice(0, 3);
                    
                })
                .catch(error =>{
                })
                // .finally(() => this.products_loading = false);
            },
        }
    }
</script>
