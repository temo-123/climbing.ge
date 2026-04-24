<template>
  <main role="main" class="container">
    <div v-if="loading" class="text-center py-5">
      <div class="spinner-border" role="status"></div>
    </div>

    <div v-else-if="!summit" class="text-center py-5">
      <h3>Summit not found</h3>
      <router-link to="/summits/list" class="btn btn-primary mt-3">Back to list</router-link>
    </div>

    <div v-else class="row">
      <div class="col-md-8">
        <nav aria-label="breadcrumb" class="mb-3">
          <ol class="breadcrumb">
            <li class="breadcrumb-item">
              <router-link to="/summits/list">Summits</router-link>
            </li>
            <li class="breadcrumb-item active">{{ summit.title }}</li>
          </ol>
        </nav>

        <h1 class="display-5 mb-2">{{ summit.title }}</h1>

        <div class="d-flex flex-wrap gap-3 mb-4 text-muted">
          <span v-if="summit.height">
            <i class="fa fa-arrows-alt-v"></i> {{ summit.height }}m
          </span>
          <span v-if="summit.region">
            <i class="fa fa-map-marker-alt"></i> {{ summit.region.us_name }}
          </span>
          <span v-if="summit.latitude && summit.longitude">
            <i class="fa fa-crosshairs"></i>
            {{ parseFloat(summit.latitude).toFixed(6) }}, {{ parseFloat(summit.longitude).toFixed(6) }}
          </span>
        </div>

        <img
          v-if="summit.image"
          :src="'/storage/' + summit.image"
          :alt="summit.title"
          class="img-fluid rounded mb-4"
          style="max-height: 400px; width: 100%; object-fit: cover;"
        />

        <div v-if="summit.description" class="mb-4">
          <p class="lead">{{ summit.description }}</p>
        </div>

        <router-link :to="'/make_ascent/' + summit.id" class="btn btn-primary btn-lg">
          <i class="fa fa-flag"></i> Make Ascent
        </router-link>
      </div>

      <div class="col-md-4">
        <div class="card shadow-sm">
          <div class="card-body text-center">
            <h5 class="card-title mb-3">Scan to Record Ascent</h5>
            <div class="d-flex justify-content-center mb-3">
              <qrcode-vue
                :value="ascentUrl"
                :size="180"
                level="H"
                render-as="svg"
              />
            </div>
            <p class="text-muted small">Scan this QR code at the summit to record your ascent</p>
          </div>
        </div>
      </div>
    </div>

    <metaData
      :title="summit ? summit.title : 'Summit'"
      :description="summit ? summit.description : ''"
      :image="summit && summit.image ? '/storage/' + summit.image : '/public/images/meta_img/mountain.jpg'"
    />
  </main>
</template>

<script>
import metaData from '../../items/MetaDataComponent.vue'
import QrcodeVue from 'qrcode.vue'

export default {
  name: 'SummitPage',
  components: {
    metaData,
    QrcodeVue,
  },
  data() {
    return {
      summit: null,
      loading: true,
    }
  },
  computed: {
    ascentUrl() {
      if (!this.summit) return ''
      const base = process.env.MIX_SUMMIT_URL
        ? (process.env.MIX_APP_SSH || '').replace(/\/$/, '') + '/' + (process.env.MIX_SUMMIT_URL || '').replace(/^\/|\/$/g, '')
        : window.location.origin
      return `${base}/make_ascent/${this.summit.id}`
    }
  },
  mounted() {
    this.fetchSummit()
  },
  methods: {
    fetchSummit() {
      this.loading = true
      const urlTitle = this.$route.params.url_title
      axios.get(`summit/show/${urlTitle}`)
        .then(response => {
          this.summit = response.data
        })
        .catch(error => {
          console.error('Error fetching summit:', error)
          this.summit = null
        })
        .finally(() => {
          this.loading = false
        })
    },
  }
}
</script>

<style scoped>
.gap-3 {
  gap: 1rem;
}
</style>
