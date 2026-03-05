<template>
  <main role="main" class="container">
    <div class="row">
      <div class="col-md-8 blog-main">
        <section class="hero">
          <div class="container">
            <div class="row">
              <div v-if="loading" class="text-center">
                <p>{{ $t('blog.loading.post') }}</p>
              </div>
              <div v-else-if="post">
                <article class="blog-post">
                  <header class="mb-4">
                    <h1 class="display-4">{{ post.title }}</h1>
                    <div class="d-flex align-items-center text-muted">
                      <small>{{ $t('blog.loading.created', { date: formatDate(post.created_at) }) }}</small>
                    </div>
                  </header>
                  <div class="content" v-html="post.content"></div>
                </article>
              </div>
              <div v-else>
                <p>post not found.</p>
              </div>
            </div>
          </div>
        </section>
      </div>
      <rightMenu />
    </div>

    <metaData
        :title="post ? post.title : $t('blog.meta.post')"
        :description="post ? post.short_description : this.$siteData.data.guid_short_description"
        :image="post && post.image ? post.image : '/public/images/meta_img/outdoor.jpg'"
    />
  </main>
</template>

<script>
  import axios from 'axios'
  import metaData from '../../items/MetaDataComponent'

  export default {
    name: 'PostPage',
    components: {
      metaData
    },
    data() {
      return {
        post: null,
        loading: true
      }
    },
    mounted() {
      this.fetchPost()
    },
    methods: {
      fetchPost() {
        // const postId = this.$route.params.id
        axios.get(`/get_post/get_post/${this.$route.params.url_title}`)
          .then(response => {
            this.post = response.data
          })
          .catch(error => {
            console.error('Error fetching post:', error)
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
