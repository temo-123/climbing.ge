<template>
    <div>
        <div class="row">
            <div class="col-sm-8 blog-header">
                <h1 class="blog-title">
                    {{ this.article[0].title  }}
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

                <ul class="social-network social-circle" style="text-align: center;"> 
                    <li v-if="this.article['fb_link'] != NULL">
                        <a target="_blank" href="$article['fb_link']}}" class="icoFacebook ico_color" title="Facebook">
                            <i class="fa fa-facebook"></i>
                        </a>
                    </li>

                    <li v-if="this.article['twit_link'] != NULL">
                        <a target="_blank" href="$article['twit_link']}}" class="icoTwitter ico_color" title="Twitter">
                            <i class="fa fa-twitter"></i>
                        </a>
                    </li>

                    <li v-if="this.article['google_link'] != NULL">
                        <a target="_blank" href="$article['google_link']}}" class="icoGoogle ico_color" title="Google +">
                            <i class="fa fa-google-plus"></i>
                        </a>
                    </li>

                    <li v-if="this.article['inst_link'] != NULL">
                        <a target="_blank" href="$article['inst_link']}}" class="icoLinkedin ico_color" title="Instagram">
                            <i class="fa fa-instagram"></i>
                        </a>
                    </li>

                    <li v-if="this.article['web_link'] != NULL">
                        <a target="_blank" href="$article['web_link']}}" class="icoLinkedin ico_color" title="website">
                            <i class="fa fa-globe "></i>
                        </a>
                    </li>
                </ul>
                
                <galleryComponent :article_id="this.article.id" />
            </div>

            <articleRightMenu />
            
        </div>

        <commentForm :article_id="this.article.id" />


        <SimilarArticles 
            :article_id="this.article.id" 
            :article_category="this.article.category" 
            :route="'outdoor/'"
            :img_dir="'outdoor'"
        />

    </div> 
</template>

<script>
    import commentForm from './CommentFormComponent'
    import galleryComponent from './GalleryComponent'
    import articleRightMenu from './RightMenuComponent'
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