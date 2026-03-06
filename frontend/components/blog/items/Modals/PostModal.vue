<template>
  <div v-if="show" class="modal-overlay" @click="closeModal">
    <div class="modal-content" @click.stop>
      <div class="modal-header">
        <h3>{{ post.title }}</h3>
        <button @click="closeModal" class="close-btn">&times;</button>
      </div>
      <div class="modal-body">
        <img v-if="post.image" :src="`/images/blog_img/${post.image}`" alt="Post image" class="post-image">
        <p class="post-content">{{ post.content }}</p>
        <p class="post-description">{{ post.short_description }}</p>
        <small class="post-date">{{ $t('blog.modal.created') }} {{ new Date(post.created_at).toLocaleDateString() }}</small>
        <div class="modal-actions">
          <button @click="viewFullPost" class="btn btn-primary">{{ $t('blog.modal.view_full_post') }}</button>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'PostModal',
  props: {
    show: {
      type: Boolean,
      default: false
    },
    post: {
      type: Object,
      default: () => ({})
    }
  },
  methods: {
    closeModal() {
      this.$emit('close')
    },
    viewFullPost() {
      this.$emit('view-full-post', this.post.id)
      this.closeModal()
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
