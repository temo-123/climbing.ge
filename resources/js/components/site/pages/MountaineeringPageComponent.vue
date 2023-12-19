<template>
    <div>
        <div class="row">
            <div class="col-sm-8 blog-header">
                <h1 class="blog-title">
                    {{ this.mount_route.locale_data.title  }}
                </h1>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-8 blog-header">
                <breadcrumb />

                <p class="blog-post-meta"> {{ this.mount_route.global_data.created_at  }}</p>
            </div>
        </div>

            <div class="row">
                <div class="col-sm-8 blog-main">
                    <div class="mountain_masive_description" v-if="mounts_system != []">

                        <p style="float: right;" v-show="masiv_desc" @click="masiv_desc = !masiv_desc">X</p>
                        <p style="float: right;" v-show="!masiv_desc" @click="masiv_desc = !masiv_desc">Read more</p>
                        
                        <h2>{{ $t('guide.article.title.mount massive')}} - <strong>{{this.mounts_system.locale_data.title}}</strong></h2>

                        <span v-show="masiv_desc">
                            <span v-html="this.mounts_system.locale_data.text"></span>

                            <div v-if="this.mounts_system.global_data.weather == NULL && this.mounts_system.locale_data.best_time != NULL">
                                <h2 id="best_time_to_climb">{{ $t('guide.article.title.best time')}}</h2>
                                <span v-html="this.mounts_system.locale_data.best_time"></span>
                            </div>  
                            <div v-else-if="this.mounts_system.global_data.weather != NULL && this.mounts_system.locale_data.best_time != NULL">
                                <h2 id="best_time_to_climb">{{ $t('guide.article.title.best time')}}</h2>

                                <div class="row">
                                    <div class="col-md-6" style="margin-top: 5%;">
                                        <span v-html="this.mounts_system.locale_data.best_time"></span>
                                    </div>
                                    <div class="col-md-6" style="text-align: center;">
                                        <span v-html="this.mounts_system.global_data.weather"></span>
                                    </div>
                                </div>
                            </div>

                            <!-- how get -->
                            <div v-if="this.mounts_system.locale_data.how_get != NUll">
                                <h2 id="how_to_get_there">{{ $t('guide.article.title.how get')}}</h2>
                                <span v-html="this.mounts_system.locale_data.how_get"></span>
                            </div>

                            <!-- map -->
                            <div v-if="this.mounts_system.locale_data.map != NULL">
                                <div class="article_map">
                                    <span v-html="this.mounts_system.locale_data.map"></span>
                                </div>
                            </div>
                        </span>
                    </div> 

                    <articleTextBlocks :article="this.mount_route"/>

                    <!-- route -->
                    <div v-if="this.mount_route.locale_data.route">
                        <h2 id="routes">{{ $t('guide.article.title.route')}}</h2>
                    
                        <MountaineeringRouteImages  :article_id_prop="this.mount_route.global_data.id" />

                        <span v-html="this.mount_route.locale_data.route"></span>
                    </div>
                    
                    <galleryComponent :article_id="this.mount_route.global_data.id" />

                </div>

            <articleRightMenu />
            
        </div>

        <commentForm :article_id="this.mount_route.global_data.id" />

        <SimilarArticles 
            :article_id="this.mount_route.global_data.id" 
            :article_category="this.mount_route.global_data.category" 
            :route="'outdoor/'"
            :img_dir="'outdoor'"
        />
        
        <metaData 
            :title = "mount_route.locale_data.title"
            :description = "mount_route.locale_data.description"
            :image = "'/public/images/mount_route_img/'+mount_route.image"
        />
    </div> 
</template>

<script>
    import commentForm from '../items/CommentFormComponent'
    import galleryComponent from '../items/galleries/GalleryComponent'
    import articleRightMenu from '../items/navbars/RightMenuComponent'
    import metaData from '../items/MetaDataComponent'
    import SimilarArticles from '../items/SimilarArticlesComponent'
    import MountaineeringRouteImages from '../items/MountaineeringRouteImages'
    import breadcrumb from '../items/BreadcrumbComponent.vue'
    import articleTextBlocks from '../items/ArticleTextBlocksComponent'

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
            breadcrumb,
            SimilarArticles,
            MountaineeringRouteImages,
            articleTextBlocks,

        },
        
        mounted() {
            this.get_mount_route()
        },
        watch: {
            '$route' (to, from) {
                this.get_mount_route(),
                window.scrollTo(0,0)
            }
        },
        methods: {
            get_mount_route(){
                axios
                .get('../api/article/mount_route/'+localStorage.getItem('lang')+'/'+this.$route.params.url_title)
                .then(response => {
                    this.mount_route = response.data
                    this.get_mount_masiv()
                })
                .catch(error =>{
                })
            },
            get_mount_masiv(){
                axios 
                .get('../api/mount/on_page/'+localStorage.getItem('lang')+'/'+this.mount_route.global_data.id)
                .then(response => {
                    this.mounts_system = response.data
                })
                .catch(error =>{
                })
            },
        }
    }
</script>