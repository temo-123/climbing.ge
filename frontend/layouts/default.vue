<template>
  <div id="app">
    <!-- Top Menu -->
    <header class="top_menu navbar navbar-expand-lg navbar-dark sticky-top">
      <div class="container">
        <NuxtLink class="navbar-brand" to="/">
          <img src="/favicon.ico" alt="Climbing.ge" height="30" />
        </NuxtLink>
        
        <button 
          class="navbar-toggler" 
          type="button" 
          data-bs-toggle="collapse" 
          data-bs-target="#navbarNav"
        >
          <span class="navbar-toggler-icon"></span>
        </button>
        
        <div class="collapse navbar-collapse" id="navbarNav">
          <ul class="navbar-nav me-auto">
            <li class="nav-item">
              <NuxtLink class="nav-link" to="/">{{ $t('home') }}</NuxtLink>
            </li>
            <li class="nav-item">
              <NuxtLink class="nav-link" to="/about">{{ $t('about') }}</NuxtLink>
            </li>
            <li class="nav-item dropdown">
              <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                {{ $t('climbing') }}
              </a>
              <ul class="dropdown-menu">
                <li><NuxtLink class="dropdown-item" to="/indoor">{{ $t('indoor') }}</NuxtLink></li>
                <li><NuxtLink class="dropdown-item" to="/outdoor">{{ $t('outdoor') }}</NuxtLink></li>
                <li><NuxtLink class="dropdown-item" to="/ice">{{ $t('ice') }}</NuxtLink></li>
                <li><NuxtLink class="dropdown-item" to="/mountaineering">{{ $t('mountaineering') }}</NuxtLink></li>
              </ul>
            </li>
            <li class="nav-item">
              <NuxtLink class="nav-link" to="/events">{{ $t('events') }}</NuxtLink>
            </li>
            <li class="nav-item">
              <NuxtLink class="nav-link" to="/news">{{ $t('news') }}</NuxtLink>
            </li>
          </ul>
          
          <!-- Right side - Language & Auth -->
          <ul class="navbar-nav">
            <li class="nav-item">
              <LocaleChangeComponent />
            </li>
            <li class="nav-item" v-if="!authStore.isAuthenticated">
              <NuxtLink class="nav-link" to="/login">{{ $t('login') }}</NuxtLink>
            </li>
            <li class="nav-item" v-if="!authStore.isAuthenticated">
              <NuxtLink class="nav-link" to="/register">{{ $t('register') }}</NuxtLink>
            </li>
            <li class="nav-item dropdown" v-if="authStore.isAuthenticated">
              <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                {{ authStore.user?.name }}
              </a>
              <ul class="dropdown-menu dropdown-menu-end">
                <li><NuxtLink class="dropdown-item" to="/user">{{ $t('dashboard') }}</NuxtLink></li>
                <li><a class="dropdown-item" href="#" @click.prevent="logout">{{ $t('logout') }}</a></li>
              </ul>
            </li>
          </ul>
        </div>
      </div>
    </header>

    <!-- Main Content -->
    <main class="site-content">
      <div class="container-fluid">
        <slot />
      </div>
    </main>

    <!-- Footer -->
    <footer class="footer">
      <div class="container">
        <div class="row">
          <div class="col-md-4">
            <h5>Climbing.ge</h5>
            <p>{{ $t('footer_description') }}</p>
          </div>
          <div class="col-md-4">
            <h5>{{ $t('quick_links') }}</h5>
            <ul class="list-unstyled">
              <li><NuxtLink to="/about">{{ $t('about') }}</NuxtLink></li>
              <li><NuxtLink to="/contact">{{ $t('contact') }}</NuxtLink></li>
              <li><NuxtLink to="/privacy">{{ $t('privacy') }}</NuxtLink></li>
            </ul>
          </div>
          <div class="col-md-4">
            <h5>{{ $t('follow_us') }}</h5>
            <SocialLinks />
          </div>
        </div>
        <hr />
        <div class="text-center">
          <p class="mb-0">&copy; {{ new Date().getFullYear() }} Climbing.ge. {{ $t('all_rights_reserved') }}</p>
        </div>
      </div>
    </footer>

    <!-- Go to top button -->
    <GoOnTopComponent />
  </div>
</template>

<script setup>
import { useAuthStore } from '~/stores/auth'

const authStore = useAuthStore()

const logout = async () => {
  await authStore.logout()
  navigateTo('/')
}
</script>

<style scoped>
.top_menu {
  position: sticky;
  top: 0;
  z-index: 1030;
}

.site-content {
  min-height: calc(100vh - 200px);
  padding: 2rem 0;
}

.footer {
  margin-top: 3rem;
}
</style>

