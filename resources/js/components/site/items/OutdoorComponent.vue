<template>
    <div>
        <div class="row">
            <div class="col-sm-8 blog-header">
                <h1 class="blog-title">
                    {{ this.article[0].title  }}

                    <span @click="add_to_favorite_outdoor_area(article.id)"> <i class="fa fa-heart-o favorite_icon add_to_favorite" ></i> </span>
                </h1>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-8 blog-header">
                <breadcrumb />

                <p class="blog-post-meta"> {{ this.article[0].created_at  }}</p>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-8 blog-main">
                
                <articleTextBlocks :article="this.article"/>

                <!-- routes -->
                <div v-if="this.article[0].route || this.article.global_info.routes_info != []">
                    <h2 id="routes">{{ $t('route')}}</h2>

                    <routeQuanDiogram :outdoor_region_article_id="this.article.id"/>

                    <span v-if="this.article.global_info.routes_info.length == 0">
                        <span v-html="this.article[0].route"></span>
                    </span>
                    <span v-else>
                        <span v-if="this.article.global_info.routes_info.block_action == 'befor'">
                            <span v-html="this.article.global_info.routes_info.text"></span>
                            <span v-html="this.article[0].route"></span>
                        </span>
                        <span v-if="this.article.global_info.routes_info.block_action == 'after'">
                            <span v-html="this.article[0].route"></span>
                            <span v-html="this.article.global_info.routes_info.text"></span>
                        </span>
                        <span v-if="this.article.global_info.routes_info.block_action == 'instead'">
                            <span v-html="this.article.global_info.routes_info.text"></span>
                        </span>
                    </span>
                </div>

                <routesTab :article_id="this.article.id" />
                
                <galleryComponent :article_id="this.article.id" />
            </div>

            <articleRightMenu />
            
        </div>

        <div class="row"> 
            <commentForm :article_id="this.article.id" />
        </div>

        <SimilarArticles 
            :article_id="this.article.id" 
            :article_category="this.article.category" 
            :route="'outdoor/'"
            :img_dir="'outdoor_img/'"

            ref="SArticles"
        />

    </div> 
</template>

<script>
    import routesTab from './RoutesTabComponent'
    import commentForm from './CommentFormComponent'
    // import postsList from './PostsListComponent'
    import galleryComponent from './GalleryComponent'
    import articleRightMenu from './RightMenuComponent'
    import SimilarArticles from './SimilarArticlesComponent'
    import breadcrumb from './BreadcrumbComponent.vue'
    import articleTextBlocks from './ArticleTextBlocksComponent'

    import routeQuanDiogram from '../items/RoutesQuantityComponent.vue'

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
            // postsList
        },
        data: function () {
            return {
                // tab_num: 1,
                posts: [],
            }
        },
        mounted() {
            this.get_posts()
        },
        watch: {
            '$route' (to, from) {
                this.$refs.SArticles.test();
                // this.$refs.SimilarArticles.get_same_articles();
            }
        },
        methods: {
            get_posts(){
                axios
                .get("../api/posts/get_posts_for_outdoor_region/"+this.article.id)
                .then(response => {
                    this.posts = response.data
                })
                .catch(
                    error => console.log(error)
                );
            },

            add_to_favorite_outdoor_area(article_id){
                // alert('add to interested event. ID = ' + article_id)

                axios
                .post('../../api/articles/add_to_favorite_outdoor_area/', {
                    article_id: article_id,
                })
                .then(response => {
                    alert(response.data)
                })
                .catch(error =>{
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