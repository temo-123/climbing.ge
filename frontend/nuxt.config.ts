// https://nuxt.com/docs/api/configuration/nuxt-config
export default defineNuxtConfig({
  compatibilityDate: '2024-11-01',
  devtools: { enabled: true },

  ssr: true,

  app: {
    head: {
      title: 'Climbing.ge',
      htmlAttrs: {
        lang: 'en'
      },
      meta: [
        { charset: 'utf-8' },
        { name: 'viewport', content: 'width=device-width, initial-scale=1' },
        { name: 'description', content: 'Climbing.ge - Mountaineering, Outdoor, Indoor climbing routes and more' },
        { name: 'format-detection', content: 'telephone=no' },
        { name: 'csrf-token', content: '' }
      ],
      link: [
        { rel: 'icon', type: 'image/x-icon', href: '/favicon.ico' },
        { rel: 'stylesheet', href: 'https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap' }
      ],
      script: [
        { src: 'https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js' }
      ]
    },
    pageTransition: { name: 'page', mode: 'out-in' }
  },

  css: [
    '~/assets/scss/main.scss'
  ],

  modules: [
    '@pinia/nuxt',
    '@vueuse/nuxt'
  ],

  pinia: {
    storesDirs: ['./stores/**']
  },

  vite: {
    css: {
      preprocessorOptions: {
        scss: {
          additionalData: '@use "~/assets/scss/_variables.scss" as *;'
        }
      }
    },
    build: {
      rollupOptions: {
        output: {
          manualChunks: {
            'vendor': ['vue', 'vue-router', 'vue-i18n', 'pinia'],
            'ckeditor': ['@ckeditor/ckeditor5-build-classic', '@ckeditor/ckeditor5-vue'],
            'bootstrap': ['bootstrap']
          }
        }
      }
    },
    optimizeDeps: {
      include: ['vue', 'vue-router', 'vue-i18n', 'pinia', 'axios']
    }
  },

  runtimeConfig: {
    public: {
      siteUrl: process.env.MIX_SITE_URL || 'climbing.ge',
      shopUrl: process.env.MIX_SHOP_URL || 'shop.climbing.ge',
      userPageUrl: process.env.MIX_USER_PAGE_URL || 'user.climbing.ge',
      filmsUrl: process.env.MIX_FILMS_URL || 'films.climbing.ge',
      blogUrl: process.env.MIX_BLOG_URL || 'blog.climbing.ge',
      apiBaseUrl: process.env.MIX_APP_SSH || 'http://localhost:8000/api',
      supportedLocales: (process.env.MIX_VUE_APP_I18N_SUPORTED_LOCALE || 'en|ka').split('|'),
      analyticsIds: {
        site: process.env.MIX_CLIMBING_GUIDBOOK_ANALITICS_ID || '',
        shop: process.env.MIX_SHOP_ANALITICS_ID || '',
        user: process.env.MIX_USER_ANALITICS_ID || '',
        films: process.env.MIX_FILMS_ANALITICS_ID || '',
        blog: process.env.MIX_blog_ANALITICS_ID || ''
      }
    }
  },

  routeRules: {
    '/': { prerender: true },
    '/about': { prerender: true },
    '/api/**': { cors: true }
  },

  nitro: {
    preset: 'node-server'
  },

  vue: {
    compilerOptions: {
      isCustomElement: (tag) => {
        return tag.startsWith('ckeditor') || tag.startsWith('b-') || tag.startsWith('b-')
      }
    }
  }
})

