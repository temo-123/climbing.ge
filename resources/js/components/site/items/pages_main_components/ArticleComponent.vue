<template>
    <div>
        <div class="row">
            <div class="col-sm-8 blog-header">
                <h1 class="blog-title">
                    {{ this.article_prop.locale_data.title  }}
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
                
                <galleryComponent 
                    :article_id="this.article_prop.global_data.id" 
                    ref="gallery_component"
                />
            </div>

            <articleRightMenu />
            
        </div>

        <commentForm 
            :article_id="this.article_prop.global_data.id" 
            ref="comments"
        />

        <SimilarArticles 
            :article_id="this.article_prop.global_data.id" 
            :article_category="this.article_prop.global_data.category" 
            :route="this.article_prop.global_data.category + '/'"
            :img_dir="this.article_prop.global_data.category"

            ref="similar_articles"
        />

    </div> 
</template>

<script>
    import commentForm from '../comments/CommentFormComponent'
    import galleryComponent from '../galleries/GalleryComponent'
    import articleRightMenu from '../navbars/RightMenuComponent'
    import SimilarArticles from '../SimilarArticlesComponent'
    import breadcrumb from '../BreadcrumbComponent.vue'
    import articleTextBlocks from '../article/ArticleTextBlocksComponent'

    export default {
        props: [
            'article_prop',
        ],
        components: {
            commentForm,
            galleryComponent,
            articleRightMenu,
            SimilarArticles,
            breadcrumb,
            articleTextBlocks,
        },
        data: function () {
            return {
                // article_global_data: [],
            }
        },
        mounted() {
            // 
        },
        watch: {
            '$route' (to, from) {
                // 
            }
        },
        methods: {
            update_similar_articles_component(id){
                this.$refs.similar_articles.update(id)
                this.$refs.route_quan_diogram.update(id)
                this.$refs.routes_tab.update(id)
                this.$refs.gallery_component.update(id)
                this.$refs.comments.update(id)
            }
        }
    }
</script>