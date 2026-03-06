<template>
  <div class="home-page">
    <!-- Hero Section -->
    <section class="hero-section py-5">
      <div class="container">
        <div class="row align-items-center">
          <div class="col-lg-6">
            <h1 class="display-4 mb-4">{{ $t('welcome_to_climbing') }}</h1>
            <p class="lead mb-4">{{ $t('hero_description') }}</p>
            <div class="d-flex gap-3">
              <NuxtLink to="/indoor" class="btn btn-primary btn-lg">
                {{ $t('start_climbing') }}
              </NuxtLink>
              <NuxtLink to="/about" class="btn btn-outline-secondary btn-lg">
                {{ $t('learn_more') }}
              </NuxtLink>
            </div>
          </div>
          <div class="col-lg-6">
            <div class="hero-image">
              <img src="/images/hero-climbing.jpg" alt="Climbing" class="img-fluid rounded" @error="handleImageError" />
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- Featured Sections -->
    <section class="features-section py-5 bg-light">
      <div class="container">
        <h2 class="text-center mb-5">{{ $t('explore_climbing') }}</h2>
        <div class="row g-4">
          <div class="col-md-6 col-lg-3">
            <div class="card h-100 feature-card">
              <div class="card-body text-center">
                <div class="feature-icon mb-3">
                  <i class="bi bi-building"></i>
                </div>
                <h5 class="card-title">{{ $t('indoor') }}</h5>
                <p class="card-text">{{ $t('indoor_description') }}</p>
                <NuxtLink to="/indoor" class="btn btn-sm btn-outline-primary">
                  {{ $t('view_more') }}
                </NuxtLink>
              </div>
            </div>
          </div>
          <div class="col-md-6 col-lg-3">
            <div class="card h-100 feature-card">
              <div class="card-body text-center">
                <div class="feature-icon mb-3">
                  <i class="bi bi-sun"></i>
                </div>
                <h5 class="card-title">{{ $t('outdoor') }}</h5>
                <p class="card-text">{{ $t('outdoor_description') }}</p>
                <NuxtLink to="/outdoor" class="btn btn-sm btn-outline-primary">
                  {{ $t('view_more') }}
                </NuxtLink>
              </div>
            </div>
          </div>
          <div class="col-md-6 col-lg-3">
            <div class="card h-100 feature-card">
              <div class="card-body text-center">
                <div class="feature-icon mb-3">
                  <i class="bi bi-snow"></i>
                </div>
                <h5 class="card-title">{{ $t('ice') }}</h5>
                <p class="card-text">{{ $t('ice_description') }}</p>
                <NuxtLink to="/ice" class="btn btn-sm btn-outline-primary">
                  {{ $t('view_more') }}
                </NuxtLink>
              </div>
            </div>
          </div>
          <div class="col-md-6 col-lg-3">
            <div class="card h-100 feature-card">
              <div class="card-body text-center">
                <div class="feature-icon mb-3">
                  <i class="bi bi-mountains"></i>
                </div>
                <h5 class="card-title">{{ $t('mountaineering') }}</h5>
                <p class="card-text">{{ $t('mountaineering_description') }}</p>
                <NuxtLink to="/mountaineering" class="btn btn-sm btn-outline-primary">
                  {{ $t('view_more') }}
                </NuxtLink>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- Latest News Section -->
    <section class="news-section py-5">
      <div class="container">
        <h2 class="text-center mb-5">{{ $t('latest_news') }}</h2>
        <div class="row" v-if="news.length">
          <div class="col-md-4 mb-4" v-for="item in news" :key="item.id">
            <NewsCard :news="item" />
          </div>
        </div>
        <div v-else class="text-center py-5">
          <p>{{ $t('no_news') }}</p>
        </div>
      </div>
    </section>
  </div>
</template>

<script setup>
const handleImageError = (e) => {
  e.target.style.display = 'none'
}

// SEO
useHead({
  title: 'Climbing.ge - Mountaineering, Outdoor, Indoor climbing routes',
  meta: [
    { name: 'description', content: 'Discover climbing routes, events, and outdoor adventures in Georgia. Indoor climbing, outdoor climbing, ice climbing, and mountaineering.' }
  ]
})

// Fetch news (placeholder - would connect to API)
const news = ref([])
</script>

<style scoped>
.hero-section {
  padding: 4rem 0;
}

.feature-card {
  transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.feature-card:hover {
  transform: translateY(-5px);
  box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
}

.feature-icon {
  font-size: 3rem;
  color: var(--primary);
}

.hero-image img {
  max-width: 100%;
  height: auto;
}
</style>

