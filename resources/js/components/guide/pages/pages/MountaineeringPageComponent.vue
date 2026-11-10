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
                    <!-- the mountain massif this route belongs to: name, other routes,
                         summits, and its about / season / access / gear / weather tabs -->
                    <mountMassifCard :massif="mounts_system" />

                    <!-- grade bar -->
                    <div v-if="mount_route.global_data && mount_route.global_data.mount_grade" class="mount-grade-section">
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
                    <div v-if="this.mount_route.locale_data.route" class="cg-section">
                        <h2 id="routes">{{ $t('common.routes')}}</h2>
                    
                        <MountaineeringRouteImages 
                            :article_id_prop="this.mount_route.global_data.id" 
                            ref="mountaineeringRouteImages"
                        />

                        <span v-html="this.mount_route.locale_data.route" class="article_text_block"></span>
                    </div>



                    <galleryComponent 
                        :images_prop="this.mount_route.gallery_images" 
                    />

                </div>

                <articleRightMenu />
            
            </div>

            <!-- "Before you go" (grade in plain words + emergency) is switched off
                 for now, until its workflow is redesigned. To bring it back, remove
                 this comment wrapper — the component, import and styles are kept.
            <section class="cg-before-go">
                <h2 id="safety">{{ $t('guide.mountain.section_title') }}</h2>
                <MountainSafety
                    :grade="mount_route.global_data && mount_route.global_data.mount_grade"
                    :article_id="mount_route.global_data.id"
                    :title="mount_route.locale_data.title"
                />
            </section>
            -->

            <commentForm 
                :article_id="this.mount_route.global_data.id" 
                ref="comments"
            />

            <!-- "Climb It With a Local Guide" — right under the comments (same as outdoor pages) -->
            <toursSliderComponent :article="true" />

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
    import toursSliderComponent from '../../items/shop_items_for_guide/ToursSliderComponent'
    import MountaineeringRouteImages from '../../items/MountaineeringRouteImages'
    import breadcrumb from '../../items/BreadcrumbComponent.vue'
    import articleTextBlocks from '../../items/article/ArticleTextBlocksComponent'
    import MountGradeBar from '../../items/MountGradeBarComponent.vue'
    import mountMassifCard from '../../items/MountMassifCardComponent.vue'

    export default {
        props: [
            // 'article',
        ],
        data: function () {
            return {
                mount_route: [],
                mounts_system: null,
                article_loading: true
            };
        },
        components: {
            commentForm,
            toursSliderComponent,
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
            mountMassifCard,
        },
        
        mounted() {
            this.get_mount_route()
        },
        watch: {
            '$route' (to, from) {
                this.article_loading = true
                this.mount_route = []
                this.mounts_system = null
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
                    this.mounts_system = response.data || null
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
    margin: 0 0 1.5rem;
}
</style>