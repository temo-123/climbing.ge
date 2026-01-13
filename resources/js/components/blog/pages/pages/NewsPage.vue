<template>
  <main role="main" class="container">
    <div class="row">
      <div class="col-md-8 blog-main">
        <section class="hero">
          <div class="container">
            <div class="row">
              <div v-if="loading" class="text-center">
                <p>Loading news...</p>
              </div>
              <div v-else-if="news">
                <article class="blog-post">
                  <header class="mb-4">
                    <h1 class="display-4">{{ news.title }}</h1>
                    <div class="d-flex align-items-center text-muted">
                      <small>Created • {{ formatDate(news.created_at) }}</small>
                    </div>
                  </header>
                  <div class="content" v-html="news.content"></div>
                </article>
              </div>
              <div v-else>
                <p>{{ $t('blog.loading.news_not_found') }}</p>
              </div>
            </div>
          </div>
        </section>
      </div>
      <rightMenu />
    </div>

    <metaData
        :title="news ? news.title : $t('blog.meta.news')"
        :description="news ? news.short_description : this.$siteData.data.guid_short_description"
        :image="news && news.image ? news.image : '/public/images/meta_img/outdoor.jpg'"
    />
  </main>
</template>

<script>
  import axios from 'axios'
  import metaData from '../../items/MetaDataComponent'

  export default {
    name: 'newsPage',
    components: {
      metaData
    },
    data() {
      return {
        news: null,
        loading: true
      }
    },
    mounted() {
      this.fetchnews()
    },
    methods: {
      fetchnews() {
        // const newsId = this.$route.params.id
        axios.get(`/get_post/get_news/${this.$route.params.url_title}`)
          .then(response => {
            this.news = response.data
          })
          .catch(error => {
            console.error('Error fetching news:', error)
          })
          .finally(() => {
            this.loading = false
          })
      },
      formatDate(dateString) {
        const date = new Date(dateString)
        return date.toLocaleDateString('en-US', {
          year: 'numeric',
          month: 'long',
          day: 'numeric'
        })
      }
    }
  }
</script>
