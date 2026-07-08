<template>
    <div>
        <div class="container">
            <h1 class="serch_title">{{ $t('guide.title.search') }}</h1>
            <div class="bar"><i class="fa fa-search" aria-hidden="true"></i></div>
            <h3 class="article_list_short_description">
                <span v-html="this.$siteData.data.guide_search_description"></span>
            </h3>

            <div class="row">
                <div class="col-md-12">
                    <h2 class="text-center">{{ this.$route.query.query }}</h2>

                    <div v-if="is_loading" class="text-center" style="padding: 40px;">
                        <i class="fa fa-spinner fa-spin fa-3x"></i>
                    </div>

                    <div v-else>
                        <!-- Suggested results banner -->
                        <div v-if="suggested" class="alert alert-warning">
                            <i class="fa fa-lightbulb-o"></i>
                            {{ $t('common.search.did_you_mean') }}
                            <strong>"{{ best_match }}"</strong>?
                            {{ $t('common.search.you_searched') }}
                            <strong>"{{ original_query }}"</strong>.
                        </div>

                        <div v-if="articles.length === 0 && sectors.length === 0 && routes.length === 0">
                            <div class="alert alert-danger" role="alert">
                                {{ $t('guide.search_no_result_faild') }}
                            </div>
                        </div>

                        <!-- Articles -->
                        <div v-if="articles.length > 0">
                            <h3>{{ $t('guide.search.articles_title') }}</h3>
                            <hr>
                            <section class="inner">
                                <section class="portfolio" id="portfolio">
                                    <div class="layout">
                                        <section class="inner">
                                            <ul class="grid">
                                                <articleSerchingCard
                                                    v-for="article in articles"
                                                    :key="article.global_data.id"
                                                    :article="article"
                                                />
                                            </ul>
                                        </section>
                                    </div>
                                </section>
                            </section>
                        </div>

                        <!-- Sectors -->
                        <div v-if="sectors.length > 0" style="margin-top: 30px;">
                            <h3>{{ $t('common.sectors') }}</h3>
                            <hr>
                            <div class="row">
                                <div v-for="sector in sectors" :key="sector.id" class="col-md-4 search-result-card">
                                    <div class="search-result-card-inner">
                                        <router-link v-if="sector.article" :to="articlePath(sector.article) + '/' + sector.article.url_title">
                                            <h4>{{ sector.name }}</h4>
                                        </router-link>
                                        <h4 v-else>{{ sector.name }}</h4>
                                        <p class="search-result-desc" v-html="activeLocale === 'ka' ? sector.ka_description : sector.us_description"></p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Routes -->
                        <div v-if="routes.length > 0" style="margin-top: 30px;">
                            <h3>{{ $t('guide.search.routes_title') }}</h3>
                            <hr>
                            <div class="row">
                                <div v-for="route in routes" :key="route.id" class="col-md-3 search-result-card">
                                    <div class="search-result-card-inner">
                                        <h4>{{ route.name }}</h4>
                                        <span class="badge" style="background:#278da5;">{{ route.grade }}</span>
                                        <span class="badge" style="background:#555; margin-left:4px;">{{ route.category }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <metaData
            :title="$t('guide.meta.search')"
            :description="'Search climbing spots in Georgia. Query — ' + this.$route.query.query"
            :image="'/public/images/meta_img/outdoor.jpg'"
        />
    </div>
</template>

<script>
import articleSerchingCard from '../items/cards/ArticleSerchingCardComponent'
import metaData from '../items/MetaDataComponent'

export default {
    components: { articleSerchingCard, metaData },
    data() {
        return {
            articles: [],
            sectors: [],
            routes: [],
            suggested: false,
            best_match: null,
            original_query: null,
            is_loading: true,
        }
    },
    mounted() {
        this.search()
    },
    watch: {
        '$route'() {
            this.search()
            window.scrollTo(0, 0)
        }
    },
    computed: {
        activeLocale() {
            return this.$i18n.locale === 'ka' ? 'ka' : 'us'
        }
    },
    methods: {
        search() {
            const query = this.$route.query.query
            if (!query) return
            this.is_loading = true
            axios.post('/guide/search', {
                query,
                locale: this.activeLocale,
            })
            .then(res => {
                this.suggested      = res.data.suggested
                this.best_match     = res.data.best_match
                this.original_query = res.data.original_query
                this.articles       = res.data.articles
                this.sectors        = res.data.sectors
                this.routes         = res.data.routes
            })
            .catch(() => {})
            .finally(() => { this.is_loading = false })
        },
        articlePath(article) {
            const map = {
                outdoor:     'outdoor',
                indoor:      'indoor',
                mount_route: 'mountaineering',
                ice:         'ice',
                other:       'other',
                news:        'news',
            }
            return map[article.category] || 'outdoor'
        }
    }
}
</script>

<style scoped>
.search-result-card {
    margin-bottom: 20px;
}
.search-result-card-inner {
    border-radius: 8px;
    background: #fff;
    box-shadow: 0 2px 8px rgba(0,0,0,0.08);
    padding: 14px 16px;
}
.search-result-card-inner h4 {
    margin-top: 0;
}
.search-result-desc {
    font-size: 0.88em;
    color: #555;
    margin-top: 6px;
}
</style>
