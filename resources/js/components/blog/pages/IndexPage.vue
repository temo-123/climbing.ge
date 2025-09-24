<template>
<div>
    <main role="main" class="container">

      <div class="row">
        <div class="col-md-8 blog-main">
            <section class="hero">
                <div class="container">
                    <div class="row">	

                      <div v-if="loading" class="text-center">
                        <p>Loading posts and news...</p>
                      </div>
                      <div v-else>
                        <h2>Latest Blog Posts and News</h2>
                        <div v-for="item in sortedItems" :key="item.id" class="mb-4 post-item">
                          <div @click="openModal(item)" class="post-content">
                            <h3>{{ item.title }}</h3>
                            <p>{{ item.short_description }}</p>
                            <small>{{ new Date(item.created_at).toLocaleDateString() }}</small>
                          </div>
                          <div class="post-actions">
                            <button @click.stop="viewFullPost(item.id)" class="btn btn-primary">Read More</button>
                          </div>
                        </div>
                      </div>
                      
                      <!-- <instaPost /> -->
                    </div>
                </div>
            </section>
        </div>

        <rightMenu />

      </div>
    </main>

    <PostModal :show="showModal" :post="selectedPost" @close="closeModal" @view-full-post="handleViewFullPost" />
</div>
</template>

<script>
  import axios from 'axios'
  import PostModal from '../items/PostModal.vue'
  // import addBlock from '../items/AddBlockComponent.vue'
  // import post from '../items/PostComponent.vue'
  // import instaPost from '../items/InstaPostsComponent.vue'
  // import rightMenu from '../items/RightMenuComponent.vue'
  export default {
    components: {
      // addBlock,
      // post,
      // rightMenu,
      // instaPost,
      PostModal
    },
    data() {
      return {
        posts: [],
        news: [],
        loading: true,
        showModal: false,
        selectedPost: {}
      }
    },
    computed: {
      sortedItems() {
        const allItems = [...this.posts, ...this.news]
        return allItems.sort((a, b) => new Date(b.created_at) - new Date(a.created_at))
      }
    },
    mounted() {
      this.fetchPostsAndNews()
    },
    methods: {
      async fetchPostsAndNews() {
        try {
          const [postsResponse, newsResponse] = await Promise.all([
            axios.get('/api/post/get_posts'),
            axios.get('/api/last_news/en') // Assuming 'en' for English, adjust as needed
          ])
          this.posts = postsResponse.data
          this.news = newsResponse.data
        } catch (error) {
          console.error('Error fetching posts and news:', error)
        } finally {
          this.loading = false
        }
      },
      openModal(post) {
        this.selectedPost = post
        this.showModal = true
      },
      closeModal() {
        this.showModal = false
        this.selectedPost = {}
      },
      viewFullPost(postId) {
        this.$router.push(`/blog/post/${postId}`)
      },
      handleViewFullPost(postId) {
        this.viewFullPost(postId)
      }
    }
  }
</script>

<style scoped>
.post-item {
  cursor: pointer;
  padding: 15px;
  border: 1px solid #ddd;
  border-radius: 5px;
  margin-bottom: 15px;
  transition: background-color 0.3s;
}

.post-item:hover {
  background-color: #f5f5f5;
}

.post-content {
  cursor: pointer;
  flex-grow: 1;
}

.post-actions {
  margin-top: 10px;
  text-align: right;
}

.btn {
  padding: 8px 16px;
  border: none;
  border-radius: 4px;
  cursor: pointer;
  font-size: 14px;
}

.btn-primary {
  background-color: #007bff;
  color: white;
}

.btn-primary:hover {
  background-color: #0056b3;
}
</style>

