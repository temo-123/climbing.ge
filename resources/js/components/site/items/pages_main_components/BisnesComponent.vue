<template>
    <div>
        <div class="row">
            <div class="col-sm-8 blog-header">
                <h1 class="blog-title">
                    {{ this.bisnes_data.locale_data.title  }}
                </h1>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-8 blog-header">
                <breadcrumb />

                <p class="blog-post-meta"> {{ this.bisnes_data.global_data.created_at  }}</p>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-8 blog-main">
                <div v-if="this.bisnes_data.locale_data.text != NUll">
                    <span v-html="this.bisnes_data.locale_data.text"></span>
                </div>

                <bisnesGalleryComponent :images="this.bisnes_data.images" />
            </div>

            <bisnesRightMenu />
            
        </div>

    </div> 
</template>

<script>
    import commentForm from '../comments/CommentFormComponent'
    import bisnesGalleryComponent from '../galleries/BisnesGalleryComponent'
    import bisnesRightMenu from '../navbars/BisnesRightMenuComponent'
    import SimilarArticles from '../SimilarArticlesComponent'
    import breadcrumb from '../BreadcrumbComponent.vue'
    import articleTextBlocks from '../article/ArticleTextBlocksComponent'

    export default {
        props: [
            'bisnes',
        ],
        components: {
            commentForm,
            bisnesGalleryComponent,
            bisnesRightMenu,
            SimilarArticles,
            breadcrumb,
            articleTextBlocks,
        },
        data: function () {
            return {
                bisnes_data: this.bisnes,
            }
        },
        mounted() {
            // this.get_article_global_data()
        },
        methods: {
            // get_article_global_data(){
            //     axios
            //     .get('../../api/get_article_global_data/'+localStorage.getItem('lang')+'/'+this.article.id)
            //     .then(response => {
            //         this.article_global_data = response.data
            //     })
            //     .catch(error =>{
            //     })
            // },
        }
    }
</script>