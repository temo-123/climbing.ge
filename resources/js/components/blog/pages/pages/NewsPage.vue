<template>
  <div>
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
                  <h1>{{ news.title }}</h1>
                  <p>{{ news.content }}</p>
                  <small>Created at: {{ news.created_at }}</small>
                </div>
                <div v-else>
                  <p>news not found.</p>
                </div>
              </div>
            </div>
          </section>
        </div>
        <rightMenu />
      </div>
    </main>

    <metaData 
        :title = " $t('blog.meta.news') "
        :description = "this.$siteData.data.guid_short_description"
        :image = "'/public/images/meta_img/outdoor.jpg'"
    />
  </div>
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
        const newsId = this.$route.params.id
        axios.get(`/post/get_news/${newsId}`)
          .then(response => {
            this.news = response.data
          })
          .catch(error => {
            console.error('Error fetching news:', error)
          })
          .finally(() => {
            this.loading = false
          })
      }
    }
  }
</script>
