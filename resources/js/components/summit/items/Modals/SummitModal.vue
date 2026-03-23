<template>
  <StackModal
    v-model="is_show_modal"
    :title="modalTitle"
    size="lg"
    :saveButton="{ visible: false }"
    :cancelButton="{ visible: true, title: $t('common.close'), btnClass: { 'btn btn-secondary': true } }"
    @close="close_modal"
  >
    <div v-if="loading" class="text-center py-8">
      <div class="spinner mx-auto mb-4"></div>
      <p>{{ $t('common.loading') }}</p>
    </div>
    <div v-else class="modal-body">
      <img v-if="post.image" :src="`/images/blog_img/${post.image}`" alt="Post image" class="post-image w-full h-64 object-cover rounded-lg mb-4">
      <h3 class="text-2xl font-bold mb-4">{{ post.title }}</h3>
      <p class="post-content mb-4">{{ post.content }}</p>
      <p class="post-description italic mb-4">{{ post.short_description }}</p>
      <small class="post-date block text-gray-500 mb-6">{{ $t('blog.modal.created') }} {{ new Date(post.created_at).toLocaleDateString() }}</small>
      <div class="text-center">
        <button @click="viewFullPost" class="btn btn-primary px-6 py-3 rounded-lg">{{ $t('blog.modal.view_full_post') }}</button>
      </div>
    </div>
  </StackModal>
</template>

<script>
export default {
  name: 'PostModal',
  data() {
    return {
      is_show_modal: false,
      post: {},
      loading: false
    }
  },
  computed: {
    modalTitle() {
      return this.post.title || this.$t('blog.modal.loading_post');
    }
  },
  methods: {
    show_modal(post_id) {
      this.loading = true;
      this.post = {};
      this.is_show_modal = false;

      // Fetch post data - adjust endpoint as needed
      axios.get(`/api/blog/posts/${post_id}`)
        .then(response => {
          this.post = response.data;
          this.is_show_modal = true;
          this.loading = false;
        })
        .catch(error => {
          console.error('Error loading post:', error);
          this.loading = false;
        });
    },
    close_modal() {
      this.is_show_modal = false;
      this.post = {};
      this.loading = false;
    },
    viewFullPost() {
      this.$emit('view-full-post', this.post.id)
      this.close_modal()
    }
  }
}
</script>

<style scoped>
.modal-overlay {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background: rgba(0, 0, 0, 0.5);
  display: flex;
  justify-content: center;
  align-items: center;
  z-index: 1000;
}

.modal-content {
  background: white;
  padding: 20px;
  border-radius: 8px;
  max-width: 600px;
  width: 90%;
  max-height: 80vh;
  overflow-y: auto;
}

.modal-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 15px;
}

.close-btn {
  background: none;
  border: none;
  font-size: 24px;
  cursor: pointer;
}

.post-image {
  max-width: 100%;
  height: auto;
  margin-bottom: 15px;
}

.post-content {
  margin-bottom: 10px;
}

.post-description {
  font-style: italic;
  margin-bottom: 10px;
}

.post-date {
  color: #666;
}

.modal-actions {
  margin-top: 20px;
  text-align: center;
}

.btn {
  padding: 10px 20px;
  border: none;
  border-radius: 5px;
  cursor: pointer;
  font-size: 16px;
}

.btn-primary {
  background-color: #007bff;
  color: white;
}

.btn-primary:hover {
  background-color: #0056b3;
}
</style>
