<template>
  <div id="app-error" class="error-layout">
    <div class="error-container text-center">
      <div class="error-code">{{ error.statusCode }}</div>
      <h1 class="error-title">{{ errorTitle }}</h1>
      <p class="error-message">{{ error.message }}</p>
      
      <div class="error-actions mt-4">
        <NuxtLink to="/" class="btn btn-primary me-2">
          {{ $t('go_home') }}
        </NuxtLink>
        <button @click="handleError" class="btn btn-outline-secondary">
          {{ $t('try_again') }}
        </button>
      </div>
    </div>
  </div>
</template>

<script setup>
const props = defineProps({
  error: {
    type: Object,
    required: true
  }
})

const errorTitle = computed(() => {
  switch (props.error.statusCode) {
    case 404:
      return 'Page Not Found'
    case 403:
      return 'Access Forbidden'
    case 500:
      return 'Server Error'
    default:
      return 'Something Went Wrong'
  }
})

const handleError = () => {
  clearError({ redirect: '/' })
}
</script>

<style scoped>
.error-layout {
  min-height: 100vh;
  display: flex;
  align-items: center;
  justify-content: center;
  background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
  padding: 2rem;
}

.error-container {
  max-width: 600px;
}

.error-code {
  font-size: 8rem;
  font-weight: 700;
  color: var(--primary);
  line-height: 1;
  margin-bottom: 1rem;
}

.error-title {
  font-size: 2rem;
  color: var(--site-dark);
  margin-bottom: 1rem;
}

.error-message {
  font-size: 1.1rem;
  color: var(--secondary);
}
</style>

