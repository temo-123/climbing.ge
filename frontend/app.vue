<template>
  <NuxtLayout>
    <NuxtPage />
  </NuxtLayout>
</template>

<script setup>
import { useAuthStore } from '~/stores/auth'

const authStore = useAuthStore()

// Initialize auth on app mount (client-side only)
if (import.meta.client) {
  authStore.initAuth()
}
</script>

