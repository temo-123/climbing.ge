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

                <p v-if="this.bisnes_data.global_data.created_at" class="blog-post-meta">{{ $t('guide.article.created_label') }}: {{ $formatDate(this.bisnes_data.global_data.created_at) }}</p>
                <p v-if="this.bisnes_data.global_data.updated_at && this.bisnes_data.global_data.updated_at !== this.bisnes_data.global_data.created_at" class="blog-post-meta">{{ $t('guide.article.updated_label') }}: {{ $formatDate(this.bisnes_data.global_data.updated_at) }}</p>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-8 blog-main">
                <div v-if="this.bisnes_data.locale_data.text != NUll">
                    <span v-html="this.bisnes_data.locale_data.text"></span>
                </div>

                <bisnesGalleryComponent v-if="bisnes_data.images != []" :images="this.bisnes_data.images" />
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
            // 
        },
        methods: {
            // 
        }
    }
</script>