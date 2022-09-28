<template>
    <div>
        <h1 style="display: none;"><span v-html="this.$siteData.guid_title"></span></h1>

        <swiperComponent />

        <whatWeDoComponent />

        <div class="container h-recent-work" v-if="this.$siteData.map">

            <h2 class='index_h2'>{{ $t('title topo') }}</h2>

            <div class="bar"><i class="fa fa-map-marker"></i></div>
            
            <h3> {{this.$siteData.topo_description}} </h3>

            <div style='margin-bottom:7%;'>
                <span v-html="this.$siteData.map"></span>
            </div>
        </div>

        <div class="h-recent-work" v-if="this.lastNews">
            <div class="container">
                <h2 class='index_h2' id="news">{{ $t('title news') }}</h2>
                <div class="bar"><i class="fa fa-newspaper-o"></i></div>
                <h3> <span v-html="this.$siteData.news_description"></span> </h3>

                <span v-if="this.lastNews.length > 0" >
                    <bigNewsCard 
                        :news="lastNews"
                    />
                </span>
                
                <section v-if="this.newses.length > 6" class="m-collection-strip mm-collection-strip--is-stretched m-component-stack mm-component-stack--is-stacked mm-component-stack--has-header">
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

        <eventComponent/>

        <otherArticlesComponent/>

        <techtipsComponent/>

        <indexGalleryComponent />
        
        <metaData 
            :title = "'teeest'"
            :description = "'Rock climbing, mountaineering and other outdoor actyvity in georgia in Georgia'"
            :image = "'../../../../public/images./meta_images/outdoor.jpg'"
        />
    </div>  
</template>

<script>
    import indexGalleryComponent from '../items/IndexGalleryComponent'
    import techtipsComponent from '../items/TechtipsComponent'
    import otherArticlesComponent from '../items/OtherArticlesComponent'
    import eventComponent from '../items/EventComponent'
    import swiperComponent from '../items/SwiperComponent'
    import whatWeDoComponent from '../items/WhatWeDoComponent'

    import newsCard from '../items/cards/NewsCardComponent'
    import bigNewsCard from '../items/cards/BigNewsCardComponent'

    import metaData from '../items/MetaDataComponent'

    export default {
        data: function () {
            return {
                newses: [],
                lastNews: [],
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
        },
        mounted() {
            this.get_news()
            this.get_last_news()
        },
        watch: {
            '$route' (to, from) {
                this.get_news()
                this.get_last_news()
            }
        },
        methods: {
            async get_news(){
                axios
                .get('../api/articles/news/'+localStorage.getItem('lang'))
                .then(response => {
                    this.newses = response.data
                    this.lastNews = response.data[0]
                })
                .catch(error =>{
                })
            },
            async get_last_news(){
                axios
                .get('../api/last_news/'+localStorage.getItem('lang'))
                .then(response => {
                    this.lastNews = response.data
                })
                .catch(error =>{
                })
            }
        }
    }
</script>