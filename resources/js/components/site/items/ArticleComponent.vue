<template>
    <div>
        <div class="row">
            <div class="col-sm-8 blog-header">
                <h1 class="blog-title">
                    {{ this.article.locale_data.title  }}
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
                
                <galleryComponent 
                    :article_id="this.article.global_data.id" 
                    ref="gallery_component"
                />
            </div>

            <articleRightMenu />
            
        </div>

        <commentForm 
            :article_id="this.article.global_data.id" 
            ref="comments"
        />

        <SimilarArticles 
            :article_id="this.article.global_data.id" 
            :article_category="this.article.global_data.category" 
            :route="this.article.global_data.category + '/'"
            :img_dir="this.article.global_data.category"

            ref="similar_articles"
        />

    </div> 
</template>

<script>
    import commentForm from './CommentFormComponent'
    import galleryComponent from './galleries/GalleryComponent'
    import articleRightMenu from './navbars/RightMenuComponent'
    import SimilarArticles from './SimilarArticlesComponent'
    import breadcrumb from './BreadcrumbComponent.vue'
    import articleTextBlocks from './ArticleTextBlocksComponent'

    export default {
        props: [
            'article',
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
            // this.get_article_global_data()
                // console.log('mmmmmmmmmmmmmmmmmmmmmmm');
        },
        watch: {
            '$route' (to, from) {
                // console.log('lllllllllllll');
                // this.update_similar_articles_component()
                // console.log('lllllllllllll');
            }
        },
        methods: {
            update_similar_articles_component(id){
                // console.log(this.article);
                this.$refs.similar_articles.update(id)
                this.$refs.route_quan_diogram.update(id)
                this.$refs.routes_tab.update(id)
                this.$refs.gallery_component.update(id)
                this.$refs.comments.update(id)
            }
        }
    }
</script>