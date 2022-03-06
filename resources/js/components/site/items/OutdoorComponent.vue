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

                <!-- what need -->
                <div v-if="this.article[0].what_need != NUll">
                    <h2 id="what_you_need">{{__ ('site.what need')}}</h2>
                    <span v-html="this.article[0].what_need"></span>
                </div>

                <!-- info -->
                <div v-if="this.article[0].info != NUll">
                    <h2 id="how_to_get_there">{{__ ('site.info')}}</h2>
                    <span v-html="this.article[0].info"></span>
                </div>

                <!-- routes -->
                <div v-if="this.article[0].route">
                    <h2 id="routes">{{__ ('site.route')}}</h2>
                    <span v-html="this.article[0].route"></span>
                </div>

                <routesTab :article_id="this.article.id" />     
                
                <galleryComponent :article_id="this.article.id" />
            </div>

            <articleRightMenu />
            
        </div>

        <commentForm :article_id="this.article.id" />

        <otherArticleBlock :article_id="this.article.id" :article_category="this.article.category"/>

    </div> 
</template>

<script>
    import routesTab from './RoutesTabComponent'
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
            };
        },
        components: {
            commentForm,
            galleryComponent,
            articleRightMenu,
            otherArticleBlock,
            routesTab
        },
        mounted() {
        },
        methods: {
        }
    }
</script>