<template>
    <div>
        <div class="container">
            <h1 class="serch_title">{{ $t('summit.title.search') }}</h1>
            <div class="bar"><i class="fa fa-search" aria-hidden="true"></i></div>

            <div class="row">
                <div class="col-md-12">
                    <h2 class="text-center">{{ $route.query.query }}</h2>

                    <div v-if="is_loading" class="text-center" style="padding: 40px;">
                        <i class="fa fa-spinner fa-spin fa-3x"></i>
                    </div>

                    <div v-else>
                        <div v-if="suggested" class="alert alert-warning">
                            <i class="fa fa-lightbulb-o"></i>
                            {{ $t('summit.search.did_you_mean') }}
                            <strong>"{{ best_match }}"</strong>?
                            {{ $t('summit.search.you_searched') }}
                            <strong>"{{ original_query }}"</strong>.
                        </div>

                        <div v-if="summits.length === 0 && mount_routes.length === 0">
                            <div class="alert alert-danger">{{ $t('summit.search.no_results') }}</div>
                        </div>

                        <div v-if="summits.length > 0">
                            <h3>{{ $t('summit.search.summits_title') }}</h3>
                            <hr>
                            <div class="row">
                                <div v-for="summit in summits" :key="summit.id" class="col-md-4 search-card">
                                    <div class="search-card-inner">
                                        <router-link :to="'/summit/' + summit.url_title">
                                            <img v-if="summit.image"
                                                :src="'/public/images/summit_img/' + summit.image"
                                                class="img-responsive search-card-img"
                                                :alt="activeTitle(summit)" />
                                            <img v-else src="/public/images/site_img/image.png"
                                                class="img-responsive search-card-img"
                                                :alt="activeTitle(summit)" />
                                            <h4>{{ activeTitle(summit) }}</h4>
                                        </router-link>
                                        <p class="search-card-height" v-if="summit.height">
                                            {{ summit.height }} m
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div v-if="mount_routes.length > 0" style="margin-top: 30px;">
                            <h3>{{ $t('summit.search.mount_routes_title') }}</h3>
                            <hr>
                            <div class="row">
                                <div v-for="route in mount_routes" :key="route.global_data.id" class="col-md-4 search-card">
                                    <div class="search-card-inner">
                                        <router-link :to="'/mountaineering/' + route.global_data.url_title">
                                            <img v-if="route.global_data.image"
                                                :src="'/public/images/mount_route_img/' + route.global_data.image"
                                                class="img-responsive search-card-img"
                                                :alt="route.locale_data.title" />
                                            <img v-else src="/public/images/site_img/image.png"
                                                class="img-responsive search-card-img"
                                                :alt="route.locale_data.title" />
                                            <h4>{{ route.locale_data.title }}</h4>
                                        </router-link>
                                        <p class="search-card-desc" v-html="route.locale_data.short_description"></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <metaData
            :title="$t('summit.meta.search')"
            :description="'Summit search — ' + $route.query.query"
            :image="'/public/images/meta_img/outdoor.jpg'"
        />
    </div>
</template>

<script>
import metaData from '../items/MetaDataComponent'

export default {
    components: { metaData },
    data() {
        return {
            summits: [],
            mount_routes: [],
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
        locale() {
            return this.$i18n.locale === 'ka' ? 'ka' : 'us'
        }
    },
    methods: {
        activeTitle(summit) {
            return this.locale === 'ka' ? (summit.ka_title || summit.title) : summit.title
        },
        search() {
            const query = this.$route.query.query
            if (!query) return
            this.is_loading = true
            axios.post('/summit/search', {
                query,
                locale: this.locale,
            })
            .then(res => {
                this.suggested      = res.data.suggested
                this.best_match     = res.data.best_match
                this.original_query = res.data.original_query
                this.summits        = res.data.summits
                this.mount_routes   = res.data.mount_routes
            })
            .catch(() => {})
            .finally(() => { this.is_loading = false })
        }
    }
}
</script>

<style scoped>
.search-card {
    margin-bottom: 20px;
}
.search-card-inner {
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
.search-card-inner h4 {
    padding: 10px 12px 4px;
    margin: 0;
}
.search-card-desc {
    padding: 0 12px;
    font-size: 0.9em;
    color: #555;
}
.search-card-height {
    padding: 0 12px;
    font-weight: bold;
    color: #278da5;
}
</style>
