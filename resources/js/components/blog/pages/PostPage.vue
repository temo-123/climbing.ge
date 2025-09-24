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
  </div>
</template>

<script>
  import axios from 'axios'

  export default {
    name: 'PostPage',
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
      async fetchPost() {
        try {
          const postId = this.$route.params.id
          const response = await axios.get(`/api/post/get_activ_post/${postId}`)
          this.post = response.data
        } catch (error) {
          console.error('Error fetching post:', error)
        } finally {
          this.loading = false
        }
      }
    }
  }
</script>
