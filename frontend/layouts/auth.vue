<template>
  <div id="app-auth" class="auth-layout">
    <div class="auth-container">
      <div class="auth-header text-center mb-4">
        <NuxtLink to="/">
          <img src="/favicon.ico" alt="Climbing.ge" height="50" />
        </NuxtLink>
        <h2 class="mt-3">{{ pageTitle }}</h2>
      </div>
      
      <div class="auth-card card">
        <div class="card-body p-4">
          <slot />
        </div>
      </div>
      
      <div class="auth-footer text-center mt-4">
        <p class="mb-0">
          {{ $t('already_have_account') }} 
          <NuxtLink v-if="$route.path.includes('login')" to="/register">{{ $t('register') }}</NuxtLink>
          <NuxtLink v-else to="/login">{{ $t('login') }}</NuxtLink>
        </p>
      </div>
    </div>
  </div>
</template>

<script setup>
const route = useRoute()

const pageTitle = computed(() => {
  if (route.path.includes('login')) return 'Login'
  if (route.path.includes('register')) return 'Register'
  if (route.path.includes('reset-password')) return 'Reset Password'
  return 'Auth'
})
</script>

<style scoped>
.auth-layout {
  min-height: 100vh;
  display: flex;
  align-items: center;
  justify-content: center;
  background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
  padding: 2rem;
}

.auth-container {
  width: 100%;
  max-width: 450px;
}

.auth-card {
  border-radius: 1rem;
  box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
}

.auth-header h2 {
  color: var(--site-dark);
  font-family: var(--font-family-heading);
}

.auth-footer a {
  color: var(--primary);
  font-weight: 500;
}
</style>

