<template>
    <div>
        <div class="row">
            <div class="col-sm-8 blog-header">
                <h1 class="blog-title">
                    {{ this.article_prop.locale_data.title  }}

                    <span @click="add_to_favorite_outdoor_area(article.global_data.id)"> 
                        <i class="fa fa-heart-o favorite_icon add_to_favorite" ></i> 
                    </span>
                </h1>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-8 blog-header">
                <breadcrumb />

                <p class="blog-post-meta"> {{ this.article_prop.global_data.created_at  }}</p>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-8 blog-main">
                
                <articleTextBlocks :article_prop="this.article_prop"/>

                <!-- routes -->
                <div v-if="this.article_prop.locale_data.route || this.article_prop.general_info.routes_info != []">
                    <h2 id="routes">{{ $t('guide.article.title.route')}}</h2>

                    <routeQuanDiogram 
                        :outdoor_region_article_id="this.article_prop.global_data.id"
                        ref="route_quan_diogram"
                    />

                    <generalInfo :global_info_prop="article_prop.general_info.routes_info" :locale_data_prop="article_prop.locale_data.route"/>

                </div>

                <routesTab 
                    :article_id_prop="this.article_prop.global_data.id" 
                    ref="routes_tab"
                />
                
                <galleryComponent 
                    :images_prop="this.article_prop.gallery_images" 
                />
            </div>

            <articleRightMenu />
            
        </div>

        <div class="row"> 
            <commentForm 
                :article_id="this.article_prop.global_data.id" 
                ref="comments"
            />
        </div>

        <SimilarArticles 
            :article_id="this.article_prop.global_data.id" 
            :article_category="this.article_prop.global_data.category" 
            :route="'outdoor/'"
            :img_dir="'outdoor_img/'"

            ref="similar_articles"
        />

    </div> 
</template>

<script>
    import routesTab from '../climbing_routes/SectorAndRoutesTabComponent'
    import commentForm from '../comments/CommentFormComponent'
    import galleryComponent from '../galleries/GalleryComponent'
    import articleRightMenu from '../navbars/RightMenuComponent'
    import SimilarArticles from '../SimilarArticlesComponent'
    import breadcrumb from '../BreadcrumbComponent.vue'
    import articleTextBlocks from '../article/ArticleTextBlocksComponent'

    import routeQuanDiogram from '../climbing_routes/RoutesQuantityComponent.vue'

    import generalInfo from '../article/GeneralInfoComponent'

    export default {
        props: [
            'article_prop',
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
            this.update_similar_articles_component(this.article_prop.global_data.id)
        },

        watch: {
            // $route(to, from) {
            //     // alert('prevent route change 2');
            //     update_similar_articles_component(this.article_prop.global_data.id)
            // },
            // article_prop: function(){
            //     update_similar_articles_component(this.article_prop.global_data.id)
            // },
        },
        methods: {
            update_similar_articles_component(id){
                this.$refs.similar_articles.update(id)
                this.$refs.route_quan_diogram.update(id)
                this.$refs.routes_tab.update(id)
                // this.$refs.gallery_component.update(id)
                this.$refs.comments.update(id)
            },

            add_to_favorite_outdoor_area(article_id){
                axios
                .post('/outdoor/add_to_favorite_outdoor_area/'+article_id)
                .then(response => {
                    // alert(response.data)
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