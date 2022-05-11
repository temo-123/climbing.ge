<template>
    <div>
        <h1 style="display: none;"><span v-html="this.$siteData.guid_title"></span></h1>

        <div id="mycarousel" class="carousel slide" data-ride="carousel">
            <swiperComponent />
        </div>

        <div class="h-recent-work" v-if="this.lastNews.length > 0 && this.newses.length > 0">
            <div class="container">
                <h2 class='index_h2' id="news">{{ $t('title news') }}</h2>
                <div class="bar"><i class="fa fa-newspaper-o"></i></div>
                <h3> <span v-html="this.$siteData.news_description"></span> </h3>

                <span v-if="this.lastNews.length > 0" >
                    <bigNewsCard 
                        :news="lastNews"
                    />
                </span>
                
                <section v-if="this.newses.length > 0" class="m-collection-strip mm-collection-strip--is-stretched m-component-stack mm-component-stack--is-stacked mm-component-stack--has-header">
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



        <div class="services" v-if="this.$siteData.map">
            <div class="container">

                <h2 class='index_h2'>{{ $t('title what we do') }}</h2>

                <div class="bar"><i class="fa fa-book"></i></div>
            
                <h3> {{this.$siteData.what_we_do_description}} </h3>
                
                <whatWeDoComponent />
            </div>
        </div>



        <div class="container h-recent-work" v-if="this.$siteData.map">

            <h2 class='index_h2'>{{ $t('title topo') }}</h2>

            <div class="bar"><i class="fa fa-map-marker"></i></div>
            
            <h3> {{this.$siteData.topo_description}} </h3>

            <div style='margin-bottom:7%;'>
                <span v-html="this.$siteData.map"></span>
            </div>
        </div>



        <!-- <div class="h-recent-work services" id="services">
            <div class="container">

                <h2 class='index_h2'>{{ $t('title tech tips') }}</h2>

                <div class="bar"><i class="fa fa-exclamation-triangle"></i></div>
                <h3> <span v-html="this.$siteData.description"></span> </h3>
                
                <techtipsComponent/>
            </div>
        </div> -->



        <div class="h-recent-work" id="other" v-if="this.other_articles.length > 0">
            <div class="container">

                <h2 class='index_h2'>{{ $t('title other') }}</h2>

                <div class="bar"><i class="fa fa-dribbble"></i></div>
                <h3> <span v-html="this.$siteData.other_activity_description"></span> </h3>

                <articleCardComponent 
                    v-for="other_article in other_articles"
                    :key='other_article.id'
                    :image_dir="'images/other_img/'"
                    :article="other_article"
                    :route="'other/'+other_article.url_title"
                />
            </div>  
        </div>



        <div class="container h-recent-work events" id="event" v-if="this.events.length > 0">
            <div class="row">
                <div class="container">

                    <h2 class='index_h2'>{{ $t('title events') }}</h2>

                    <div class="bar"><i class="fa fa-calendar"></i></div>
                    <h3> <span v-html="this.$siteData.event_description"></span></h3>

                    <div class="container">
                        <div class="row event-list">
                            <eventCard 
                                v-for="event in events"
                                :key='event.id'
                                :event="event"
                            />
                        </div>
                    </div>
                </div>
            </div>
        </div>
        


        <!-- <div class="container">
            <h2 class='index_h2'>{{ $t('title gallery') }}</h2>
            <div class="bar" style="margin-bottom: 5%;"><i class="fa fa-picture-o" aria-hidden="true"></i></div> -->

            <!-- <h3>{{this.$siteData.}}</h3> -->

            <indexGalleryComponent />
        <!-- </div> -->

        
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
    import articleCardComponent from '../items/cards/ArticleCardComponent'
    import swiperComponent from '../items/SwiperComponent'
    import whatWeDoComponent from '../items/WhatWeDoComponent'

    import newsCard from '../items/cards/NewsCardComponent'
    import bigNewsCard from '../items/cards/BigNewsCardComponent'
    import eventCard from '../items/cards/EventCardComponent'
    import metaData from '../items/MetaDataComponent'

    export default {
        data: function () {
            return {
                other_articles: [],
                events: [],
                newses: [],
                lastNews: [],
                techtips: [],
            };
        },
        components: {
            metaData,
            indexGalleryComponent,
            techtipsComponent,
            articleCardComponent,
            swiperComponent,
            newsCard,
            bigNewsCard,
            eventCard,
            whatWeDoComponent,
        },
        mounted() {
            this.get_other_articles()
            this.get_event()
            this.get_news()
            // this.get_techtips()
        },
        methods: {
            async get_other_articles(){
                axios
                .get('../api/other')
                .then(response => {
                    this.other_articles = response.data
                })
                .catch(error =>{
                })
            },
            async get_event(){
                axios
                .get('../api/event')
                .then(response => {
                    this.events = response.data
                })
                .catch(error =>{
                })
            },
            async get_news(){
                axios
                .get('../api/news')
                .then(response => {
                    this.newses = response.data
                    this.lastNews = this.newses[0]
                })
                .catch(error =>{
                })
            }
        }
    }
</script>