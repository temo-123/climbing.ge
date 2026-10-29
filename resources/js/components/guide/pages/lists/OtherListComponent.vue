<template>
    <div class="h-recent-work" id="other">
        <div class="container">

            <section class="cg-page-head cg-page-head--list cg-art-tent">
                <span class="cg-page-head__icon"><i class="fa fa-bicycle" aria-hidden="true"></i></span>
                <div class="cg-page-head__text">
                    <h1>{{ $t('guide.title.other')}}</h1>
                    <div class="cg-page-head__lead">{{this.$siteData.data.other_activity_description}}</div>
                </div>
            </section>

            <favoriteArticlesStrip category="other" />

            <!-- View Controls -->
            <div class="row view_controls_bar">
                <div class="col-md-12 text-start float-start">
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

            <listSearchBar v-model="search" :count="search.trim() ? shown_articles.length : null" />

            <div v-if="other_article_loading">
                
            </div>
            <div v-else>
                <div v-if="shown_articles.length > 0" class="article_card_container" :class="{'list-view': viewMode === 'list'}">
                    <!-- Grid View -->
                    <template v-if="viewMode === 'grid'">
                        <articleComponent 
                            v-for="other_article in shown_articles"
                            :key='other_article.id'
                            :image_dir="'images/other_img/'"
                            :article="other_article"
                            :route="'other/'+other_article.global_data.url_title"
                        />
                    </template>
                    <!-- List View -->
                    <template v-else>
                        <articleHorithontalCardComponent
                            v-for="other_article in shown_articles"
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
    import favoriteArticlesStrip from '../../items/FavoriteArticlesStripComponent.vue'
    import articleComponent from '../../items/cards/ArticleCardComponent'
    import articleHorithontalCardComponent from '../../items/cards/ArticleHorithontalCardComponent'
    // import emptyPageComponent from '../items/EmptyPageComponent'
    import emptyPageComponent from '../../../global_components/EmptyPageComponent'
    import metaData from '../../items/MetaDataComponent'
    import listSearchBar from '../../items/ListSearchBarComponent.vue'
    import { search_items, only_search_changed } from '../../../../services/guide/listSearch.js'


    import axios_mixin from '../../../../mixins/axios_mixin'

    export default {
        mixins: [
            axios_mixin
        ],
        data: function () {
            return {
                other_articles: [],
                search: '',
                other_article_loading: true,

                viewMode: 'grid' // 'grid' or 'list'
            };
        },
        computed: {
            shown_articles() { return search_items(this.other_articles, this.search) },
        },
        components: {
            listSearchBar,
            favoriteArticlesStrip,
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
                if (only_search_changed(to, from)) return   // typing in the search box
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