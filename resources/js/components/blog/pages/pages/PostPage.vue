<template>
  <div>
    <main role="main" class="container">
      <div class="row">
        <div class="col-md-8 blog-main">
          <section class="hero">
            <div class="container">
              <div class="row">
                <div v-if="loading" class="text-center">
                  <p>Loading post...</p>
                </div>
                <div v-else-if="post">
                  <h1>{{ post.title }}</h1>
                  <p>{{ post.content }}</p>
                  <small>Created at: {{ post.created_at }}</small>
                </div>
                <div v-else>
                  <p>Post not found.</p>
                </div>
              </div>
            </div>
          </section>
        </div>
        <rightMenu />
      </div>
    </main>

    <metaData 
        :title = " $t('blog.meta.post') "
        :description = "this.$siteData.data.guid_short_description"
        :image = "'/public/images/meta_img/outdoor.jpg'"
    />
  </div>
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
        const postId = this.$route.params.id
        axios.get(`/post/get_post/${postId}`)
          .then(response => {
            this.post = response.data
          })
          .catch(error => {
            console.error('Error fetching post:', error)
          })
          .finally(() => {
            this.loading = false
          })
      }
    }
  }
</script>
