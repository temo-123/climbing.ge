<template>
    <div>
        <div class="row">
            <div class="col-sm-8 blog-header">
                <h1 class="blog-title">
                    {{ this.article.locale_data.title  }}

                    <span @click="add_to_favorite_outdoor_area(article.global_data.id)"> 
                        <i class="fa fa-heart-o favorite_icon add_to_favorite" ></i> 
                    </span>
                </h1>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-8 blog-header">
                <breadcrumb />

                <p class="blog-post-meta"> {{ this.article.global_data.created_at  }}</p>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-8 blog-main">
                
                <articleTextBlocks :article="this.article"/>

                <!-- routes -->
                <div v-if="this.article.locale_data.route || this.article.global_info.routes_info != []">
                    <h2 id="routes">{{ $t('guide.article.title.route')}}</h2>

                    <routeQuanDiogram 
                        :outdoor_region_article_id="this.article.global_data.id"
                        ref="route_quan_diogram"
                    />

                    <generalInfo :global_info="article.global_info.routes_info" :locale_data="article.locale_data.route"/>

                </div>

                <routesTab 
                    :article_id="this.article.global_data.id" 
                    ref="routes_tab"
                />
                
                <galleryComponent 
                    :article_id="this.article.global_data.id" 
                    ref="gallery_component"
                />
            </div>

            <articleRightMenu />
            
        </div>

        <div class="row"> 
            <commentForm 
                :article_id="this.article.global_data.id" 
                ref="comments"
            />
        </div>

        <SimilarArticles 
            :article_id="this.article.global_data.id" 
            :article_category="this.article.global_data.category" 
            :route="'outdoor/'"
            :img_dir="'outdoor_img/'"

            ref="similar_articles"
        />

    </div> 
</template>

<script>
    import routesTab from './climbing_routes/SectorAndRoutesTabComponent'
    import commentForm from './CommentFormComponent'
    // import postsList from './PostsListComponent'
    import galleryComponent from './galleries/GalleryComponent'
    import articleRightMenu from './navbars/RightMenuComponent'
    import SimilarArticles from './SimilarArticlesComponent'
    import breadcrumb from './BreadcrumbComponent.vue'
    import articleTextBlocks from './ArticleTextBlocksComponent'

    import routeQuanDiogram from '../items/climbing_routes/RoutesQuantityComponent.vue'

    import generalInfo from './article/GeneralInfoComponent'

    export default {
        props: [
            'article',
        ],
        components: {
            commentForm,
            galleryComponent,
            articleRightMenu,
            SimilarArticles,
            routesTab,
            breadcrumb,
            routeQuanDiogram,
            articleTextBlocks,
            generalInfo
        },
        data: function () {
            return {
                posts: [],

                MIX_SITE_URL: process.env.MIX_SITE_URL,
                MIX_APP_SSH: process.env.MIX_APP_SSH,
            }
        },
        mounted() {
            // this.get_posts()
        },

        watch: {
            // $route(to, from) {
                // alert('prevent route change 2');
            // },
        },
        methods: {
            update_similar_articles_component(id){
                this.$refs.similar_articles.update(id)
                this.$refs.route_quan_diogram.update(id)
                this.$refs.routes_tab.update(id)
                this.$refs.gallery_component.update(id)
                this.$refs.comments.update(id)
            },

            add_to_favorite_outdoor_area(article_id){
                axios
                .post('/outdoor/add_to_favorite_outdoor_area/'+article_id)
                .then(response => {
                    alert(response.data)
                })
                .catch(error => {
                    if(error.response.status === 401) {
                        if(confirm('You are not login. Do you want log in?')){
                            window.open(this.MIX_APP_SSH + 'user.' + this.MIX_SITE_URL);
                        }
                    }
                    else{
                        alert("Error " . error.response.status)
                    }
                })
            }
            
        }
    }
</script>

<style scoped>
.tabs input[type="radio"]:checked + label {
    background: #fff;
    border: 1px solid #ccc !important;
}
.add_to_favorite{
    float: right; 
    cursor: pointer;
}
</style>