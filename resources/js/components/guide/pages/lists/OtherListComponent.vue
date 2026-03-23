<template>
    <div class="h-recent-work" id="other">
        <div class="container">

            <h2 class='index_h2'>{{ $t('guide.title.other')}}</h2>

            <div class="bar"><i class="fa fa-dribbble"></i></div>

            <h2 class="article_list_short_description">
                {{this.$siteData.data.other_activity_description}}
            </h2>

            <!-- View Controls -->
            <div class="row view_controls_bar">
                <div class="col-md-12 text-left pull-left">
                    <div class="btn-group" role="group" aria-label="View Mode">
                        <button 
                            type="button" 
                            class="btn" 
                            :class="{'btn-primary active': viewMode === 'grid', 'btn-default': viewMode !== 'grid'}"
                            @click="viewMode = 'grid'"
                        >
                            <i class="fa fa-th-large"></i> {{ $t('guide.view.grid') || 'Grid' }}
                        </button>
                        <button 
                            type="button" 
                            class="btn" 
                            :class="{'btn-primary active': viewMode === 'list', 'btn-default': viewMode !== 'list'}"
                            @click="viewMode = 'list'"
                        >
                            <i class="fa fa-list-ul"></i> {{ $t('guide.view.list') || 'List' }}
                        </button>
                    </div>
                </div>
            </div>

            <div v-if="other_article_loading">
                
            </div>
            <div v-else>
                <div v-if="this.other_articles.length > 0" class="article_card_container" :class="{'list-view': viewMode === 'list'}">
                    <!-- Grid View -->
                    <template v-if="viewMode === 'grid'">
                        <articleComponent 
                            v-for="other_article in other_articles"
                            :key='other_article.id'
                            :image_dir="'images/other_img/'"
                            :article="other_article"
                            :route="'other/'+other_article.global_data.url_title"
                        />
                    </template>
                    <!-- List View -->
                    <template v-else>
                        <articleHorithontalCardComponent
                            v-for="other_article in other_articles"
                            :key="other_article.id"
                            :image_dir="'images/other_img/'"
                            :article="other_article"
                            :route="'other/'+other_article.global_data.url_title"
                        />
                    </template>
                </div>
                <div v-else>
                    <emptyPageComponent />
                </div>
            </div>
        </div> 

        <metaData 
            :title = "$t('guide.meta.other')"
            :description = "this.$siteData.data.other_activity_description"
            :image = "'/public/images/meta_img/other.jpg'"
        />
    </div>
</template>

<script>
    import articleComponent from '../../items/cards/ArticleCardComponent'
    import articleHorithontalCardComponent from '../../items/cards/ArticleHorithontalCardComponent'
    // import emptyPageComponent from '../items/EmptyPageComponent'
    import emptyPageComponent from '../../../global_components/EmptyPageComponent'
    import metaData from '../../items/MetaDataComponent'


    import axios_mixin from '../../../../mixins/axios_mixin'

    export default {
        mixins: [
            axios_mixin
        ],
        data: function () {
            return {
                other_articles: [],
                other_article_loading: true,

                viewMode: 'grid' // 'grid' or 'list'
            };
        },
        components: {
            articleComponent,
            articleHorithontalCardComponent,
            emptyPageComponent,

            metaData
        },
        mounted() {
            this.get_other_articles()
        },
        watch: {
            '$route' (to, from) {
                this.get_other_articles(),
                window.scrollTo(0,0)
            }
        },
        methods: {
            get_other_articles(){
                this.get_articles('other', localStorage.getItem('lang'),
                    (data) => {
                        this.other_articles = data;
                    },
                    (error) => {
                        console.error('Error fetching articles:', error);
                    },
                    () => {
                        this.other_article_loading = false;
                    }
                );
            }
        }
    }
</script>

<style>

</style>