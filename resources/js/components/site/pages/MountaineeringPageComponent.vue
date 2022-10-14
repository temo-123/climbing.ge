<template>
    <div>
        <div class="row">
            <div class="col-sm-8 blog-header">
                <h1 class="blog-title">
                    {{ this.mount_route[0].title  }}
                </h1>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-8 blog-header">
                <!-- @component('components.breadcrumb')
                @slot('parent') Home @endslot
                @slot('link') route($all_article_but) @endslot
                @slot('active') $article_map}} @endslot
                @if($article [0]['title'])
                @slot('article') $article [0]['title']}} @endslot
                @else
                @slot('article') $article [0]['name']}} @endslot
                @endif
                @endcomponent -->

                <p class="blog-post-meta"> {{ this.mount_route.created_at  }}</p>
            </div>
        </div>

            <div class="row">
                <div class="col-sm-8 blog-main">

                    <div class="mountain_masive_description">

                        <p style="float: right;" v-show="masiv_desc" @click="masiv_desc = !masiv_desc">Close</p>
                        <p style="float: right;" v-show="!masiv_desc" @click="masiv_desc = !masiv_desc">Open</p>
                        <h2>{{ $t('mount massive')}} - <strong>{{this.mounts_system['name']}}</strong></h2>

                        <span v-show="masiv_desc">
                            <span v-html="this.mounts_system.text"></span>

                            <div v-if="this.mounts_system.weather == NULL && this.mounts_system.best_time != NULL">
                                <h2 id="best_time_to_climb">{{ $t('best time')}}</h2>
                                <span v-html="this.mounts_system.best_time"></span>
                            </div>  
                            <div v-else-if="this.mounts_system.weather != NULL && this.mounts_system.best_time != NULL">
                                <h2 id="best_time_to_climb">{{ $t('best time')}}</h2>

                                <div class="row">
                                    <div class="col-md-6" style="margin-top: 5%;">
                                        <span v-html="this.mounts_system.best_time"></span>
                                    </div>
                                    <div class="col-md-6" style="text-align: center;">
                                        <span v-html="this.mounts_system.weather"></span>
                                    </div>
                                </div>
                            </div>

                            <!-- how get -->
                            <div v-if="this.mounts_system.how_get != NUll">
                                <h2 id="how_to_get_there">{{ $t('how get')}}</h2>
                                <span v-html="this.mounts_system.how_get"></span>
                            </div>

                            <!-- map -->
                            <div v-if="this.mounts_system.map != NULL">
                                <div class="article_map">
                                    <span v-html="this.mounts_system.map"></span>
                                </div>
                            </div>
                        </span>
                    </div> 
        

                <span v-html="this.mount_route[0].text"></span>

                <!-- Best time for climbing block -->
                <div v-if="this.mount_route[0].weather == NULL && this.mount_route[0].best_time != NULL">
                    <h2 id="best_time_to_climb">{{ $t('best time')}}</h2>
                    <span v-html="this.mount_route[0].best_time"></span>
                </div>  
                <div v-else-if="this.mount_route[0].weather != NULL && this.mount_route[0].best_time != NULL">
                    <h2 id="best_time_to_climb">{{ $t('best time')}}</h2>

                    <div class="row">
                        <div class="col-md-6" style="margin-top: 5%;">
                            <span v-html="this.mount_route[0].best_time"></span>
                        </div>
                        <div class="col-md-6" style="text-align: center;">
                            <span v-html="this.mount_route.weather"></span>
                        </div>
                    </div>
                </div>

                <!-- addres -->
                <div v-if="this.mount_route[0].address != NUll">
                    <h2 id="how_to_get_there">{{ $t('address')}}</h2>
                    <span v-html="this.mount_route[0].address"></span>
                </div>

                <!-- how get -->
                <div v-if="this.mount_route[0].how_get != NUll">
                    <h2 id="how_to_get_there">{{ $t('how get')}}</h2>
                    <span v-html="this.mount_route[0].how_get"></span>
                </div>

                <!-- map -->
                <div v-if="this.mount_route.map != NULL">
                    <div class="article_map">
                        <span v-html="this.mount_route.map"></span>
                    </div>
                </div>

                <!-- price -->
                <div v-if="this.mount_route[0].prices_text != NUll">
                    <h2 id="how_to_get_there">{{ $t('price')}}</h2>
                    <span v-html="this.mount_route[0].prices_text"></span>
                </div>

                <!-- info -->
                <div v-if="this.mount_route[0].info != NUll">
                    <h2 id="how_to_get_there">{{ $t('info')}}</h2>
                    <span v-html="this.mount_route[0].info"></span>
                </div>

                <!-- what need -->
                <div v-if="this.mount_route[0].what_need != NUll">
                    <h2 id="what_you_need">{{ $t('what need')}}</h2>
                    <span v-html="this.mount_route[0].what_need"></span>
                </div>

                <!-- news -->
                <div v-if="this.mount_route[0].route">
                    <h2 id="routes">{{ $t('route')}}</h2>
                    <span v-html="this.mount_route [0].route"></span>
                </div>
                
                <galleryComponent :article_id="this.mount_route.id" />
            </div>

            <articleRightMenu />
            
        </div>

        <commentForm :article_id="this.mount_route.id" />


        <div class="row similar_articles">
            <div class="container">
                <h2 id="other">{{ $t('similar articles')}}</h2>
                <hr class="other_line">
            </div>

            @include('components.other_article')}}
            
        </div>

        
        <metaData 
            :title = "indoors[0].title"
            :description = "indoors[0].description"
            :image = "'../../../../public/images/indoor_img/'+indoors.image"
        />
    </div> 
</template>

<script>
    import commentForm from '../items/CommentFormComponent'
    import galleryComponent from '../items/GalleryComponent'
    import articleRightMenu from '../items/RightMenuComponent'
    import metaData from '../items/MetaDataComponent'

    export default {
        props: [
            // 'article',
        ],
        data: function () {
            return {
                mount_route: [],
                mounts_system: [],
                masiv_desc: true,
            };
        },
        components: {
            commentForm,
            galleryComponent,
            articleRightMenu,
            metaData,
        },
        
        mounted() {
            this.get_mount_route()
        },
        methods: {
            get_mount_route(){
                axios
                .get('../api/article/mount_route/'+localStorage.getItem('lang')+'/'+this.$route.params.url_title)
                .then(response => {
                    this.mount_route = response.data
                    // this.get_mount_masiv()
                })
                .catch(error =>{
                })
            },
            get_mount_masiv(region){
                axios 
                .get('../api/mount/'+localStorage.getItem('lang')+'/'+region)
                .then(response => {
                    this.selected_mount_data = response.data[0]
                })
                .catch(error =>{
                })
            },
            show_masiv_description(){

            }
        }
    }
</script>