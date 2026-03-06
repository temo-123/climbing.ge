<template>
  <div>
    <form @submit.prevent="handleLogin">
      <div class="mb-3">
        <label for="email" class="form-label">{{ $t('email') }}</label>
        <input 
          id="email"
          v-model="form.email" 
          type="email" 
          class="form-control" 
          :class="{ 'is-invalid': errors.email }"
          required 
        />
        <div v-if="errors.email" class="invalid-feedback">
          {{ errors.email[0] }}
        </div>
      </div>

      <div class="mb-3">
        <label for="password" class="form-label">{{ $t('password') }}</label>
        <input 
          id="password"
          v-model="form.password" 
          type="password" 
          class="form-control" 
          :class="{ 'is-invalid': errors.password }"
          required 
        />
        <div v-if="errors.password" class="invalid-feedback">
          {{ errors.password[0] }}
        </div>
      </div>

      <div class="mb-3 form-check">
        <input 
          id="remember"
          v-model="form.remember" 
          type="checkbox" 
          class="form-check-input" 
        />
        <label for="remember" class="form-check-label">
          {{ $t('remember_me') }}
        </label>
      </div>

      <div class="mb-3">
        <NuxtLink to="/reset-password" class="text-decoration-none">
          {{ $t('forgot_password') }}
        </NuxtLink>
      </div>

      <div v-if="authStore.error" class="alert alert-danger">
        {{ authStore.error }}
      </div>

      <button 
        type="submit" 
        class="btn btn-primary w-100" 
        :disabled="authStore.loading"
      >
        <span v-if="authStore.loading" class="spinner-border spinner-border-sm me-2"></span>
        {{ $t('login') }}
      </button>
    </form>
  </div>
</template>

<script setup>
import { useAuthStore } from '~/stores/auth'

definePageMeta({
  layout: 'auth'
})

const authStore = useAuthStore()

const form = reactive({
  email: '',
  password: '',
  remember: false
})

const errors = ref({})

const handleLogin = async () => {
  errors.value = {}
  
  try {
    await authStore.login({
      email: form.email,
      password: form.password
    })
    
    // Redirect to intended page or home
    navigateTo('/')
  } catch (error) {
    if (error.response?.data?.errors) {
      errors.value = error.response.data.errors
    }
  }
}
</script>

