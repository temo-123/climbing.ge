<template>
    <div>
        <div id="mycarousel" class="carousel slide" data-ride="carousel">
            <swiperComponent />
        </div>

        <div class="h-recent-work">
            <div class="container">
                <h2 class='index_h2' id="news">{{__ ('title news') }}</h2>
                <div class="bar"><i class="fa fa-newspaper-o"></i></div>
                <h6> <span v-html="this.$siteData.news_description"></span> </h6>

                <bigNewsCard 
                    :news="lastNews"
                />

                <section class="m-collection-strip mm-collection-strip--is-stretched m-component-stack mm-component-stack--is-stacked mm-component-stack--has-header">
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



        <div class="container h-recent-work">

            <h2 class='index_h2'>{{__ ('title topo') }}</h2>

            <div class="bar"><i class="fa fa-map-marker"></i></div>
            
            <!-- <h6> {{this.$siteData.title}} </h6> -->

            <div style='margin-bottom:7%;'>
                <span v-html="this.$siteData.map"></span>
            </div>
        </div>



        <div class="services">
            <div class="container">

                <h2 class='index_h2'>{{__ ('title what we do') }}</h2>

                <div class="bar"><i class="fa fa-book"></i></div>
                
                <whatWeDoComponent />
            </div>
        </div>



        <div class="h-recent-work services" id="services">
            <div class="container">

                <h2 class='index_h2'>{{__ ('title tech tips') }}</h2>

                <div class="bar"><i class="fa fa-exclamation-triangle"></i></div>
                <h6> <span v-html="this.$siteData.description"></span> </h6>
                
                <techtipsComponent/>
            </div>
        </div>



        <div class="h-recent-work" id="other" v-if="this.other_articles.length > 0">
            <div class="container">

                <h2 class='index_h2'>{{__ ('title other') }}</h2>

                <div class="bar"><i class="fa fa-dribbble"></i></div>
                <h6> <span v-html="this.$siteData.other_activity_description"></span> </h6>

                <articleCardComponent 
                    v-for="other_article in other_articles"
                    :key='other_article.id'
                    :image_dir="'images/other_img/'"
                    :article="other_article"
                    :route="'other/'+other_article.url_title"
                />
            </div>  
        </div>



        <div class="container h-recent-work events" id="event">
            <div class="row">
                <div class="container">

                    <h2 class='index_h2'>{{__ ('title events') }}</h2>

                    <div class="bar"><i class="fa fa-calendar"></i></div>
                    <h6> <span v-html="this.$siteData.event_description"></span></h6>

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
        


        <div class="container">
            <h2 class='index_h2'>{{__ ('title gallery') }}</h2>
            <div class="bar" style="margin-bottom: 5%;"><i class="fa fa-picture-o" aria-hidden="true"></i></div>

            <!-- <h6>{{this.$siteData.}}</h6> -->

            <indexGalleryComponent />
        </div>
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

    export default {
        inject:['siteData'],
        metaInfo() {
            return {
                title: 'test',
                // meta: [
                //     { charset: 'utf-8' },
                //     { title: 'test', vmid: 'description', name: 'description', content: 'foo' }
                // ]
            }
        },
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
            get_other_articles(){
                axios
                .get('../api/other')
                .then(response => {
                    this.other_articles = response.data
                })
                .catch(error =>{
                })
            },
            get_event(){
                axios
                .get('../api/event')
                .then(response => {
                    this.events = response.data
                })
                .catch(error =>{
                })
            },
            get_news(){
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