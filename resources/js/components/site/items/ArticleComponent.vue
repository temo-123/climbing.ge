<template>
    <div>
        <div class="row">
            <div class="col-sm-8 blog-header">
                <h1 class="blog-title">
                    {{ this.article[0].title  }}
                </h1>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-8 blog-header">
                <!-- @component('site.components.breadcrumb')
                @slot('parent') Home @endslot
                @slot('link') route($all_article_but)}} @endslot
                @slot('active') $article_map}} @endslot
                @if($article [0]['title'])
                @slot('article') $article [0]['title']}} @endslot
                @else
                @slot('article') $article [0]['name']}} @endslot
                @endif
                @endcomponent -->

                <p class="blog-post-meta"> {{ this.article[0].created_at  }}</p>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-8 blog-main">

                <span v-html="this.article[0].text"></span>

                <!-- Best time for climbing block -->
                <div v-if="this.article[0].weather == NULL && this.article[0].best_time != NULL">
                    <h2 id="best_time_to_climb">{{__ ('site.best time')}}</h2>
                    <span v-html="this.article[0].best_time"></span>
                </div>  
                <div v-else-if="this.article[0].weather != NULL && this.article[0].best_time != NULL">
                    <h2 id="best_time_to_climb">{{__ ('site.best time')}}</h2>

                    <div class="row">
                        <div class="col-md-6" style="margin-top: 5%;">
                            <span v-html="this.article[0].best_time"></span>
                        </div>
                        <div class="col-md-6" style="text-align: center;">
                            <span v-html="this.article.weather"></span>
                        </div>
                    </div>
                </div>

                <!-- addres -->
                <div v-if="this.article[0].address != NUll">
                    <h2 id="how_to_get_there">{{__ ('site.address')}}</h2>
                    <span v-html="this.article[0].address"></span>
                </div>

                <!-- how get -->
                <div v-if="this.article[0].how_get != NUll">
                    <h2 id="how_to_get_there">{{__ ('site.how get')}}</h2>
                    <span v-html="this.article[0].how_get"></span>
                </div>

                <!-- map -->
                <div v-if="this.article.map != NULL">
                    <div class="article_map">
                        <span v-html="this.article.map"></span>
                    </div>
                </div>

                <!-- price -->
                <div v-if="this.article[0].prices_text != NUll">
                    <h2 id="how_to_get_there">{{__ ('site.price')}}</h2>
                    <span v-html="this.article[0].prices_text"></span>
                </div>

                <!-- info -->
                <div v-if="this.article[0].info != NUll">
                    <h2 id="how_to_get_there">{{__ ('site.info')}}</h2>
                    <span v-html="this.article[0].info"></span>
                </div>

                <!-- what need -->
                <div v-if="this.article[0].what_need != NUll">
                    <h2 id="what_you_need">{{__ ('site.what need')}}</h2>
                    <span v-html="this.article[0].what_need"></span>
                </div>

                <!-- news -->
                <div v-if="this.article[0].route">
                    <h2 id="routes">{{__ ('site.route')}}</h2>
                    <span v-html="this.article[0].route"></span>
                </div>             

                <ul class="social-network social-circle" style="text-align: center;"> 
                    <li v-if="this.article['fb_link'] != NULL">
                        <a target="_blank" href="$article['fb_link']}}" class="icoFacebook ico_color" title="Facebook">
                            <i class="fa fa-facebook"></i>
                        </a>
                    </li>

                    <li v-if="this.article['twit_link'] != NULL">
                        <a target="_blank" href="$article['twit_link']}}" class="icoTwitter ico_color" title="Twitter">
                            <i class="fa fa-twitter"></i>
                        </a>
                    </li>

                    <li v-if="this.article['google_link'] != NULL">
                        <a target="_blank" href="$article['google_link']}}" class="icoGoogle ico_color" title="Google +">
                            <i class="fa fa-google-plus"></i>
                        </a>
                    </li>

                    <li v-if="this.article['inst_link'] != NULL">
                        <a target="_blank" href="$article['inst_link']}}" class="icoLinkedin ico_color" title="Instagram">
                            <i class="fa fa-instagram"></i>
                        </a>
                    </li>

                    <li v-if="this.article['web_link'] != NULL">
                        <a target="_blank" href="$article['web_link']}}" class="icoLinkedin ico_color" title="website">
                            <i class="fa fa-globe "></i>
                        </a>
                    </li>
                </ul>
                
                <galleryComponent :article_id="this.article.id" />
            </div>

            <articleRightMenu />
            
        </div>

        <commentForm :article_id="this.article.id" />

        <otherArticleBlock :article_id="this.article.id" :article_category="this.article.category"/>

    </div> 
</template>

<script>
    import commentForm from './CommentFormComponent'
    import galleryComponent from './GalleryComponent'
    import articleRightMenu from './RightMenuComponent'
    import otherArticleBlock from './OtherArticleBlockComponent'

    export default {
        props: [
            'article',
        ],
        data: function () {
            return {
                meta_title: 'meta_title'
            };
        },
        // computed: {
        //     normalizedSize: function () {
        //         this.test = this.article
        //         // return this.test
        //     }
        // },
        components: {
            commentForm,
            galleryComponent,
            articleRightMenu,
            otherArticleBlock,
        },
        mounted() {
            // this.meta_title = this.article[0].title
            console.log(this.article)
            // console.log(this.meta_title)
            // console.log(this.meta_title)
            // console.log(this.meta_title)
            // console.log(this.meta_title)
        },
        methods: {
            // 
        },

        metaInfo() {
            return {
                title: this.meta_title,
            }
        },
    }
</script>