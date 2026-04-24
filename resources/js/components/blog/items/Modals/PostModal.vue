<template>
  <StackModal
    :show="show"
    :title="post && post.title ? post.title : $t('blog.modal.loading_post')"
    size="lg"
    :saveButton="{ visible: false }"
    :cancelButton="{ visible: true, title: $t('common.close'), btnClass: { 'btn btn-secondary': true } }"
    @close="$emit('close')"
  >
    <div v-if="post && post.id" class="modal-body">
      <img v-if="post.image" :src="`/images/blog_img/${post.image}`" alt="Post image" class="post-image img-fluid rounded mb-3">
      <p class="post-description text-muted mb-3">{{ post.short_description }}</p>
      <div class="post-content mb-4" v-html="post.content"></div>
      <small class="text-muted">{{ $t('blog.modal.created') }} {{ new Date(post.created_at).toLocaleDateString() }}</small>
      <div class="text-center mt-4">
        <button @click="viewFullPost" class="btn btn-primary">{{ $t('blog.modal.view_full_post') }}</button>
      </div>
    </div>
  </StackModal>
</template>

<script>
export default {
  name: 'PostModal',
  props: {
    show: { type: Boolean, default: false },
    post: { type: Object, default: () => ({}) },
  },
  emits: ['close', 'view-full-post'],
  methods: {
    viewFullPost() {
      this.$emit('view-full-post', this.post.id)
      this.$emit('close')
    }
  }
}
</script>

<style scoped>
.post-image {
  max-width: 100%;
  height: auto;
  margin-bottom: 15px;
}
</style>
