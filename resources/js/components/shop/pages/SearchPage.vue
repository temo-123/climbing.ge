<template>
    <div>
        <div class="container">
            <h1 class="serch_title">{{ $t('shop.title.search') }}</h1>
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

                        <div v-if="products.length === 0 && tours.length === 0 && services.length === 0">
                            <div class="alert alert-danger" role="alert">
                                {{ $t('common.search.no_results') }}
                            </div>
                        </div>

                        <!-- Products -->
                        <div v-if="products.length > 0">
                            <h3>{{ $t('shop.search.products_title') }}</h3>
                            <hr>
                            <catalogItem
                                v-for="product in products"
                                :key="product.global_product ? product.global_product.id : product.id"
                                :product_data="product"
                            />
                        </div>

                        <!-- Tours -->
                        <div v-if="tours.length > 0" style="margin-top: 30px;">
                            <h3>{{ $t('shop.search.tours_title') }}</h3>
                            <hr>
                            <div class="row">
                                <div v-for="tour in tours" :key="tour.global_data.id" class="col-md-4 search-result-card">
                                    <div class="search-result-card-inner">
                                        <router-link :to="'/tours/' + tour.global_data.url_title">
                                            <img v-if="tour.image"
                                                :src="'/public/images/tour_img/' + tour.image"
                                                class="img-responsive search-card-img"
                                                :alt="tour.locale_data.title" />
                                            <img v-else src="/public/images/site_img/image.png"
                                                class="img-responsive search-card-img"
                                                :alt="tour.locale_data.title" />
                                            <h4>{{ tour.locale_data.title }}</h4>
                                        </router-link>
                                        <p class="search-result-desc" v-html="tour.locale_data.short_description"></p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Services -->
                        <div v-if="services.length > 0" style="margin-top: 30px;">
                            <h3>{{ $t('shop.search.services_title') }}</h3>
                            <hr>
                            <div class="row">
                                <div v-for="service in services" :key="service.global_data.id" class="col-md-4 search-result-card">
                                    <div class="search-result-card-inner">
                                        <router-link :to="'/services/' + service.global_data.url_title">
                                            <img v-if="service.image"
                                                :src="'/public/images/service_img/' + service.image"
                                                class="img-responsive search-card-img"
                                                :alt="service.locale_data.title" />
                                            <img v-else src="/public/images/site_img/image.png"
                                                class="img-responsive search-card-img"
                                                :alt="service.locale_data.title" />
                                            <h4>{{ service.locale_data.title }}</h4>
                                        </router-link>
                                        <p class="search-result-desc" v-html="service.locale_data.short_description"></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <metaData
            :title="$t('shop.meta.search')"
            :description="'Search products, tours and services. All made in Georgia.'"
            :image="'/public/images/meta_img/shop.jpg'"
        />
    </div>
</template>

<script>
import metaData from '../items/MetaDataComponent'
import catalogItem from '../items/cards/CatalogItemComponent'

export default {
    components: { metaData, catalogItem },
    data() {
        return {
            products: [],
            tours: [],
            services: [],
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
    methods: {
        search() {
            const query = this.$route.query.query
            if (!query) return
            this.is_loading = true
            axios.post('/shop/search', {
                query,
                locale: this.$i18n.locale === 'ka' ? 'ka' : 'us',
            })
            .then(res => {
                this.suggested      = res.data.suggested
                this.best_match     = res.data.best_match
                this.original_query = res.data.original_query
                this.products       = res.data.products
                this.tours          = res.data.tours
                this.services       = res.data.services
            })
            .catch(() => {})
            .finally(() => { this.is_loading = false })
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
    overflow: hidden;
    background: #fff;
    box-shadow: 0 2px 8px rgba(0,0,0,0.08);
    padding-bottom: 10px;
}
.search-card-img {
    width: 100%;
    height: 180px;
    object-fit: cover;
}
.search-result-card-inner h4 {
    padding: 10px 12px 4px;
    margin: 0;
}
.search-result-desc {
    padding: 0 12px;
    font-size: 0.88em;
    color: #555;
}
</style>
