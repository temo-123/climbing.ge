<template>
    <main role="main" class="container">
        <div class="container">

            <h1 class='index_h2'>{{ $t('guide.title.ice climbing')}}</h1>

            <div class="bar"><i class="fa fa-dribbble"></i></div>

            <h2 class="article_list_short_description">
                {{this.$siteData.data.ice_description}}
            </h2>

            <bigPost :items="sortedItems" />

            <!-- Infinite Scroll Trigger -->
            <div v-if="loadingMore" class="loading-more">
                <div class="spinner-border text-primary" role="status">
                    <span class="sr-only">Loading...</span>
                </div>
                Loading more posts... ({{ countdown }}s)
            </div>
            <div ref="scrollTrigger" class="scroll-trigger" :class="{ 'hidden': !hasMorePages }"></div>
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
        selectedPost: {},
        currentPage: 1,
        totalPages: 1,
        perPage: 5,
        loadingMore: false,
        hasMorePages: true,
        observer: null,
        countdown: 2,
        countdownTimer: null
      }
    },
    computed: {
      sortedItems() {
        return this.items.sort((a, b) => new Date(b.data.created_at) - new Date(a.data.created_at))
      }
    },
    mounted() {
      this.get_all_posts_and_news_for_blog()
      this.setupInfiniteScroll()
    },
    beforeDestroy() {
      if (this.observer) {
        this.observer.disconnect()
      }
      if (this.countdownTimer) {
        clearInterval(this.countdownTimer)
      }
    },
    methods: {
      get_all_posts_and_news_for_blog(page = 1, append = false) {
        const lang = localStorage.getItem('lang') || 'en'; // Get lang from route params
        if (append) {
          this.loadingMore = true
        }
        console.log(`Fetching page ${page}, append: ${append}`)
        axios.get(`/post/get_all_posts_and_news_for_blog/${lang}`, {
          params: {
            page: page,
            per_page: this.perPage
          }
        })
          .then(response => {
            console.log('Response received:', response.data)
            if (append) {
              this.items = [...this.items, ...response.data.data]
              console.log(`Appended ${response.data.data.length} new items, total: ${this.items.length}`)
            } else {
              this.items = response.data.data
              console.log(`Loaded ${response.data.data.length} initial items`)
            }
            this.currentPage = response.data.current_page
            this.totalPages = response.data.last_page
            this.hasMorePages = this.currentPage < this.totalPages
            console.log(`Current page: ${this.currentPage}, Total pages: ${this.totalPages}, Has more: ${this.hasMorePages}`)
          })
          .catch(error => {
            console.error('Error fetching posts and news:', error)
          })
          .finally(() => {
            this.loading = false
            this.loadingMore = false
          })
      },
      loadNextPage() {
        if (this.hasMorePages && !this.loadingMore) {
          this.loadingMore = true
          this.countdown = 2
          console.log('Starting 2-second delay before loading next page...')

          this.countdownTimer = setInterval(() => {
            this.countdown--
            if (this.countdown <= 0) {
              clearInterval(this.countdownTimer)
              this.get_all_posts_and_news_for_blog(this.currentPage + 1, true)
            }
          }, 1000)
        }
      },
      setupInfiniteScroll() {
        this.observer = new IntersectionObserver((entries) => {
          entries.forEach(entry => {
            if (entry.isIntersecting && this.hasMorePages && !this.loadingMore) {
              console.log('Scroll trigger intersected, loading next page')
              this.loadNextPage()
            }
          })
        }, {
          rootMargin: '200px'
        })

        this.$nextTick(() => {
          if (this.$refs.scrollTrigger) {
            console.log('Setting up observer for scroll trigger')
            this.observer.observe(this.$refs.scrollTrigger)
          }
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
        this.$router.push(`/get_blog/post/${postId}`)
      },
      handleViewFullPost(postId) {
        this.viewFullPost(postId)
      }
    }
  }
</script>

<style scoped>
.loading-more {
  text-align: center;
  padding: 20px;
  font-size: 16px;
  color: #666;
}

.scroll-trigger {
  height: 20px;
  background-color: transparent;
}

.scroll-trigger.hidden {
  display: none;
}

.spinner-border {
  width: 1rem;
  height: 1rem;
  border-width: 0.2em;
  margin-right: 10px;
}
</style>

