<template>
    <div class="container">
        <span v-if="article_loading">
            <articlePreloader />
        </span>
        <span v-else-if="!article_loading">
            <div class="row">
                <div class="col-sm-8 blog-header">
                    <h1 class="blog-title">
                        {{ partner.locale_data.title  }}
                    </h1>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-8 blog-header">
                    <breadcrumb />

                    <p class="blog-post-meta"> {{ partner.global_data.created_at  }}</p>

                    <h2>{{ $t('guide.donation.support_title') }}</h2>

                    <Donation position="left"/>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-8 blog-main">
                    <articleTextBlocks :article_prop="partner"/>

                    <h2>{{ $t('guide.donation.support_title') }}</h2>

                    <Donation position="left"/>

                    <galleryComponent 
                        :images_prop="partner.gallery_images" 
                    />
                </div>

                <articleRightMenu />
                
            </div>

            <commentForm 
                :article_id="partner.global_data.id" 
                ref="comments"
            />

            <!-- <SimilarArticles 
                :article_id="partner.global_data.id" 
                :article_category="partner.global_data.category" 
                :route="partner.global_data.category + '/'"
                :img_dir="partner.global_data.category"

                ref="similar_articles"
            /> -->

            <metaData 
                :title = "partner.locale_data.title"
                :description = "partner.locale_data.description"
                :image = "'/public/images/partner_img/'+partner.image"
            />
        </span>
    </div>
</template>

<script>
    import articlePreloader from "../../items/article/ArticlePreloaderComponent.vue";
    // import articlPage from '../../items/pages_main_components/ArticleComponent'
    import metaData from '../../items/MetaDataComponent'
    import Donation from '../../items/DonationComponent.vue'

    import commentForm from '../../items/comments/CommentFormComponent'
    import galleryComponent from '../../items/galleries/GalleryComponent'
    import articleRightMenu from '../../items/navbars/RightMenuComponent'
    // import SimilarArticles from '../../items/SimilarArticlesComponent'
    import breadcrumb from '../../items/BreadcrumbComponent.vue'
    import articleTextBlocks from '../../items/article/ArticleTextBlocksComponent'

    export default {
        components: {
            commentForm,
            galleryComponent,
            articleRightMenu,
            breadcrumb,
            articleTextBlocks,

            articlePreloader,
            metaData,
            Donation
        },
        data: function () {
            return {
                partner: [],
                article_loading: true
            }
        },
        mounted() {
            this.get_partner()
        },
        watch: {
            '$route' (to, from) {
                this.partner = [],
                this.get_partner(),
                window.scrollTo(0,0)
            }
        },
        methods: {
            get_partner(){
                axios
                .get('/get_article/get_locale_article_on_page/tech_tip/'+localStorage.getItem('lang')+'/'+this.$route.params.url_title)
                .then(response => {
                    this.partner = response.data

                    this.$refs.article_page.update_similar_articles_component(this.partner.id)
                })
                .catch(error =>{
                })
                .finally(() => this.article_loading = false);
            },
        }
    }
</script>
