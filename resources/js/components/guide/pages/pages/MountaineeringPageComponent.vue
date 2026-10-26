<template>
    <div class="container">
        <span v-if="article_loading">
            <articlePreloader />
        </span>
        <span v-else-if="!article_loading">
            <div class="row">
                <div class="col-sm-8 blog-header">
                    <h1 class="blog-title">
                        {{ this.mount_route.locale_data.title  }}
                        <favoriteHeart variant="inline" :article_id="mount_route.global_data.id" />
                    </h1>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-8 blog-header">
                    <breadcrumb />

                    <p v-if="this.mount_route.global_data.created_at" class="blog-post-meta">{{ $t('guide.article.created_label') }}: {{ $formatDate(this.mount_route.global_data.created_at) }}</p>
                    <p v-if="this.mount_route.global_data.updated_at && this.mount_route.global_data.updated_at !== this.mount_route.global_data.created_at" class="blog-post-meta">{{ $t('guide.article.updated_label') }}: {{ $formatDate(this.mount_route.global_data.updated_at) }}</p>
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

                            <div v-if="this.mounts_system.global_data.weather == null && this.mounts_system.locale_data.best_time != null">
                                <h2 id="best_time_to_climb">{{ $t('guide.article.title.best time')}}</h2>
                                <span v-html="this.mounts_system.locale_data.best_time"></span>
                            </div>  
                            <div v-else-if="this.mounts_system.global_data.weather != null && this.mounts_system.locale_data.best_time != null">
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
                            <div v-if="this.mounts_system.global_data.map != null">
                                <div class="article_map">
                                    <span v-html="this.mounts_system.global_data.map"></span>
                                </div>
                            </div>
                        </span>
                    </div> 

                    <!-- grade bar -->
                    <div v-if="mount_route.global_data && mount_route.global_data.mount_grade" class="mount-grade-section">
                        <h3 class="mount-grade-title">{{ $t('guide.article.mount_route.grade') }}</h3>
                        <MountGradeBar :grade="mount_route.global_data.mount_grade" />
                    </div>

                    <articleTextBlocks :article_prop="this.mount_route" :default_gear="grade_gear"/>

                    <mobileScene
                        variant="summit"
                        :title="mount_route.locale_data.title"
                        target="safety"
                        :button="$t('guide.mobile_scene.before_you_go')"
                    />

                    <!-- route -->
                    <div v-if="this.mount_route.locale_data.route">
                        <h2 id="routes">{{ $t('common.routes')}}</h2>
                    
                        <MountaineeringRouteImages 
                            :article_id_prop="this.mount_route.global_data.id" 
                            ref="mountaineeringRouteImages"
                        />

                        <span v-html="this.mount_route.locale_data.route"></span>
                    </div>



                    <galleryComponent 
                        :images_prop="this.mount_route.gallery_images" 
                    />

                </div>

                <articleRightMenu />
            
            </div>

            <!-- before you go: grade in plain words + emergency, full page width -->
            <section class="cg-before-go">
                <h2 id="safety">{{ $t('guide.mountain.section_title') }}</h2>
                <MountainSafety
                    :grade="mount_route.global_data && mount_route.global_data.mount_grade"
                    :article_id="mount_route.global_data.id"
                    :title="mount_route.locale_data.title"
                />
            </section>

            <commentForm 
                :article_id="this.mount_route.global_data.id" 
                ref="comments"
            />

            <SimilarArticles 
                :article_id="this.mount_route.global_data.id" 
                :article_category="this.mount_route.global_data.category" 
                :route="'mountaineering/'"
                :img_dir="'mount_route_description_img'"

                ref="similar_articles"
            />
            
            <metaData 
                :title = "mount_route.locale_data.title"
                :description = "mount_route.locale_data.description"
                :image = "'/public/images/mount_route_img/'+mount_route.image"
            />
        </span>
    </div> 
</template>

<script>
    import favoriteHeart from '../../items/FavoriteHeartComponent.vue'
    import mobileScene from '../../items/MobileSceneComponent.vue'
    import MountainSafety from '../../items/MountainSafetyComponent.vue'
    import { mountain_level, default_gear } from '../../../../services/guide/mountainGear.js'
    import { remember_viewed } from '../../../../services/guide/recentlyViewed.js'
    import commentForm from '../../items/comments/CommentFormComponent'
    import galleryComponent from '../../items/galleries/GalleryComponent'
    import articleRightMenu from '../../items/navbars/RightMenuComponent'
    import metaData from '../../items/MetaDataComponent'
    import SimilarArticles from '../../items/SimilarArticlesComponent'
    import MountaineeringRouteImages from '../../items/MountaineeringRouteImages'
    import breadcrumb from '../../items/BreadcrumbComponent.vue'
    import articleTextBlocks from '../../items/article/ArticleTextBlocksComponent'
    import MountGradeBar from '../../items/MountGradeBarComponent.vue'

    export default {
        props: [
            // 'article',
        ],
        data: function () {
            return {
                mount_route: [],
                mounts_system: [],
                masiv_desc: true,
                article_loading: true
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
            MountGradeBar,
            MountainSafety,
            favoriteHeart,
            mobileScene,

        },
        
        mounted() {
            this.get_mount_route()
        },
        watch: {
            '$route' (to, from) {
                this.article_loading = true
                this.mount_route = []
                this.mounts_system = []
                this.get_mount_route()
                window.scrollTo(0, 0)
            },
        },
        computed: {
            // gear checklist for "What You Need" when the route text has no list of its own
            grade_gear() {
                const g = this.mount_route && this.mount_route.global_data;
                return default_gear(mountain_level(g && g.mount_grade)).map(k => this.$t('guide.mountain.gear.' + k));
            },
        },
        methods: {
            get_mount_route(){
                this.article_loading = true
                axios
                .get('/get_article/get_locale_article_on_page/mount_route/'+localStorage.getItem('lang')+'/'+this.$route.params.url_title)
                .then(response => {
                    this.mount_route = response.data
                    this.remember(response.data)
                    this.get_mount_masiv()
                })
                .catch(error =>{
                })
            },
            remember(a){
                if (a && a.global_data && a.locale_data) remember_viewed({ category: 'mount_route', url_title: a.global_data.url_title, title: a.locale_data.title, image: a.image })
            },
            get_mount_masiv(){
                axios 
                .get('/get_mount/on_page/'+localStorage.getItem('lang')+'/'+this.mount_route.global_data.id)
                .then(response => {
                    this.mounts_system = response.data
                })
                .catch(error =>{
                })
                .finally(() => this.article_loading = false);
            },
        }
    }
</script>

<style scoped>
.cg-before-go {
    margin: 1.5rem 0 1rem;
}
.mount-grade-section {
    margin: 24px 0;
    padding: 16px 20px;
    background: var(--cg-chalk);
    border-radius: 8px;
    border-left: 4px solid var(--cg-stone);
}
.mount-grade-title {
    font-size: 1rem;
    font-weight: 600;
    color: var(--cg-stone);
    margin-bottom: 12px;
    text-transform: uppercase;
    letter-spacing: 0.05em;
}
</style>