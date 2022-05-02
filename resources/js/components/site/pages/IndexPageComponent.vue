<template>
    <div>
        <div id="mycarousel" class="carousel slide" data-ride="carousel">
            <swiperComponent />
        </div>

        <div class="h-recent-work" v-if="this.lastNews.length > 0 && this.newses.length > 0">
            <div class="container">
                <h2 class='index_h2' id="news">{{ $t('title news') }}</h2>
                <div class="bar"><i class="fa fa-newspaper-o"></i></div>
                <h6> <span v-html="this.$siteData.news_description"></span> </h6>

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
                
                <whatWeDoComponent />
            </div>
        </div>



        <div class="container h-recent-work" v-if="this.$siteData.map">

            <h2 class='index_h2'>{{ $t('title topo') }}</h2>

            <div class="bar"><i class="fa fa-map-marker"></i></div>
            
            <h6> {{this.$siteData.title}} </h6>

            <div style='margin-bottom:7%;'>
                <span v-html="this.$siteData.map"></span>
            </div>
        </div>



        <!-- <div class="h-recent-work services" id="services">
            <div class="container">

                <h2 class='index_h2'>{{ $t('title tech tips') }}</h2>

                <div class="bar"><i class="fa fa-exclamation-triangle"></i></div>
                <h6> <span v-html="this.$siteData.description"></span> </h6>
                
                <techtipsComponent/>
            </div>
        </div> -->



        <div class="h-recent-work" id="other" v-if="this.other_articles.length > 0">
            <div class="container">

                <h2 class='index_h2'>{{ $t('title other') }}</h2>

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



        <div class="container h-recent-work events" id="event" v-if="this.events.length > 0">
            <div class="row">
                <div class="container">

                    <h2 class='index_h2'>{{ $t('title events') }}</h2>

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
        


        <!-- <div class="container">
            <h2 class='index_h2'>{{ $t('title gallery') }}</h2>
            <div class="bar" style="margin-bottom: 5%;"><i class="fa fa-picture-o" aria-hidden="true"></i></div> -->

            <!-- <h6>{{this.$siteData.}}</h6> -->

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
        // inject:['siteData'],
        // metaInfo() {
        //     return {
        //         title: 'test title',
        //         htmlAttrs: {
        //             lang: 'en-US'
        //         },
        //         link: [
        //             { rel: 'stylesheet', href: '/css/index.css' },
        //             { rel: 'canonical', href: 'climbing.loc' }
        //         ],
        //         meta: [
        //             {name: 'viewport', content: 'width=device-width, initial-scale=1'},
        //             { charset: 'utf-8' },
        //             { name: 'description', content: 'An example Vue application with vue-meta.' },
        //             { title: 'Default App Title',titleTemplate: '%s | vue-meta Example App'},
        //             { canonical: 'Default App Title',content: '%s | vue-meta Example App'},

        //             // https://www.digitalocean.com/community/tutorials/vuejs-vue-seo-tips
        //             // OpenGraph data (Most widely used)
        //             {property: 'og:title', content: 'My Page Title ← My Site'},
        //             {property: 'og:site_name', content: 'My Site'},
        //             // The list of types is available here: http://ogp.me/#types
        //             {property: 'og:type', content: 'website'},
        //             // Should the the same as your canonical link, see below.
        //             {property: 'og:url', content: 'https://www.my-site.com/my-special-page'},
        //             {property: 'og:image', content: '../../../../public/images./meta_images/outdoor.jpg'},
        //             // Often the same as your meta description, but not always.
        //             {property: 'og:description', content: 'I have things here on my site.'},

        //             // Twitter card
        //             {name: 'twitter:card', content: 'summary'},
        //             {name: 'twitter:site', content: 'https://www.my-site.com/my-special-page'},
        //             {name: 'twitter:title', content: 'My Page Title ← My Site'},
        //             {name: 'twitter:description', content: 'I have things here on my site.'},
        //             // Your twitter handle, if you have one.
        //             {name: 'twitter:creator', content: '@alligatorio'},
        //             {name: 'twitter:image:src', content: '../../../../public/images./meta_images/outdoor.jpg'},

        //             // Google / Schema.org markup:
        //             {itemprop: 'name', content: 'My Page Title ← My Site'},
        //             {itemprop: 'description', content: 'I have things here on my site.'},
        //             {itemprop: 'image', content: '../../../../public/images./meta_images/outdoor.jpg'}
        //         ]
        //     }
        // },
        data: function () {
            return {
                page_title: 'page title',
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