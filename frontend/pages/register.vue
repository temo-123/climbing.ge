<template>
  <div>
    <form @submit.prevent="handleRegister">
      <div class="mb-3">
        <label for="name" class="form-label">{{ $t('name') }}</label>
        <input 
          id="name"
          v-model="form.name" 
          type="text" 
          class="form-control" 
          :class="{ 'is-invalid': errors.name }"
          required 
        />
        <div v-if="errors.name" class="invalid-feedback">
          {{ errors.name[0] }}
        </div>
      </div>

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

      <div class="mb-3">
        <label for="password_confirmation" class="form-label">{{ $t('confirm_password') }}</label>
        <input 
          id="password_confirmation"
          v-model="form.password_confirmation" 
          type="password" 
          class="form-control" 
          required 
        />
      </div>

      <div class="mb-3 form-check">
        <input 
          id="terms"
          v-model="form.terms" 
          type="checkbox" 
          class="form-check-input" 
          :class="{ 'is-invalid': errors.terms }"
          required 
        />
        <label for="terms" class="form-check-label">
          {{ $t('agree_terms') }} <NuxtLink to="/terms">{{ $t('terms_conditions') }}</NuxtLink>
        </label>
        <div v-if="errors.terms" class="invalid-feedback">
          {{ errors.terms[0] }}
        </div>
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
        {{ $t('register') }}
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
  name: '',
  email: '',
  password: '',
  password_confirmation: '',
  terms: false
})

const errors = ref({})

const handleRegister = async () => {
  errors.value = {}
  
  try {
    await authStore.register({
      name: form.name,
      email: form.email,
      password: form.password,
      password_confirmation: form.password_confirmation
    })
    
    // Redirect to home after successful registration
    navigateTo('/')
  } catch (error) {
    if (error.response?.data?.errors) {
      errors.value = error.response.data.errors
    }
  }
}
</script>

