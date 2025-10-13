<template>
    <main role="main" class="container">
        <div class="container">

            <h1 class='index_h2'>{{ $t('guide.title.ice climbing')}}</h1>

            <div class="bar"><i class="fa fa-dribbble"></i></div>
            
            <h2 class="article_list_short_description">
                {{this.$siteData.data.ice_description}}
            </h2>

            <bigPost :items="sortedItems" />
        </div>

        <PostModal :show="showModal" :post="selectedPost" @close="closeModal" @view-full-post="handleViewFullPost" />

        <metaData 
            :title = " $t('blog.meta.index') "
            :description = "this.$siteData.data.guid_short_description"
            :image = "'/public/images/meta_img/outdoor.jpg'"
        />
    </main>
</template>

<script>
  import axios from 'axios'
  import bigPost from '../items/cards/BigPostCardComponent.vue'
  import PostModal from '../items/Modals/PostModal.vue'
  import metaData from '../items/MetaDataComponent'

  export default {
    components: {
      metaData,
      PostModal,
      bigPost
    },
    data() {
      return {
        items: [],
        loading: true,
        showModal: false,
        selectedPost: {}
      }
    },
    computed: {
      sortedItems() {
        return this.items.sort((a, b) => new Date(b.data.created_at) - new Date(a.data.created_at))
      }
    },
    mounted() {
      this.get_all_posts_and_news_for_blog()
    },
    methods: {
      get_all_posts_and_news_for_blog() {
        const lang = localStorage.getItem('lang') || 'en'; // Get lang from route params
        axios.get(`/post/get_all_posts_and_news_for_blog/` + lang )
          .then(response => {
            this.items = response.data
          })
          .catch(error => {
            console.error('Error fetching posts and news:', error)
          })
          .finally(() => {
            this.loading = false
          })
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

