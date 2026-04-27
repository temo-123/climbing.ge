<template>
    <div>
        <div class="container">
            <h1 class="serch_title">{{ $t('blog.title.search') }}</h1>
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
                            {{ $t('blog.search.did_you_mean') }}
                            <strong>"{{ best_match }}"</strong>?
                            {{ $t('blog.search.you_searched') }}
                            <strong>"{{ original_query }}"</strong>.
                        </div>

                        <div v-if="posts.length === 0 && news.length === 0">
                            <div class="alert alert-danger">{{ $t('blog.search.no_results') }}</div>
                        </div>

                        <div v-if="posts.length > 0">
                            <h3>{{ $t('blog.search.posts_title') }}</h3>
                            <hr>
                            <div class="row">
                                <div v-for="post in posts" :key="post.global_data.id" class="col-md-4 search-card">
                                    <div class="search-card-inner">
                                        <router-link :to="'/post/' + post.global_data.url_title">
                                            <img v-if="post.global_data.image"
                                                :src="'/public/images/blog_img/' + post.global_data.image"
                                                class="img-responsive search-card-img"
                                                :alt="post.locale_data.title" />
                                            <img v-else src="/public/images/site_img/image.png"
                                                class="img-responsive search-card-img"
                                                :alt="post.locale_data.title" />
                                            <h4>{{ post.locale_data.title }}</h4>
                                        </router-link>
                                        <p class="search-card-desc" v-html="post.locale_data.short_description"></p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div v-if="news.length > 0" style="margin-top: 30px;">
                            <h3>{{ $t('blog.search.news_title') }}</h3>
                            <hr>
                            <div class="row">
                                <div v-for="item in news" :key="item.global_data.id" class="col-md-4 search-card">
                                    <div class="search-card-inner">
                                        <router-link :to="'/news/' + item.global_data.url_title">
                                            <img v-if="item.global_data.image"
                                                :src="'/public/images/news_img/' + item.global_data.image"
                                                class="img-responsive search-card-img"
                                                :alt="item.locale_data.title" />
                                            <img v-else src="/public/images/site_img/image.png"
                                                class="img-responsive search-card-img"
                                                :alt="item.locale_data.title" />
                                            <h4>{{ item.locale_data.title }}</h4>
                                        </router-link>
                                        <p class="search-card-desc" v-html="item.locale_data.short_description"></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <metaData
            :title="$t('blog.meta.search')"
            :description="'Blog search — ' + $route.query.query"
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
            posts: [],
            news: [],
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
            axios.post('/blog/search', {
                query,
                locale: this.$i18n.locale === 'ka' ? 'ka' : 'us',
            })
            .then(res => {
                this.suggested      = res.data.suggested
                this.best_match     = res.data.best_match
                this.original_query = res.data.original_query
                this.posts          = res.data.posts
                this.news           = res.data.news
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
</style>
