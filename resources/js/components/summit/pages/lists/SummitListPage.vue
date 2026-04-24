<template>
  <div>
    <main role="main" class="container">
      <div class="container">
        <h1 class="index_h2">Summits</h1>

        <div class="bar"><i class="fa fa-mountain"></i></div>

        <div v-if="loading" class="text-center py-5">
          <div class="spinner-border" role="status"></div>
        </div>

        <div v-else-if="summits.length === 0" class="text-center py-5">
          <p class="text-muted">No summits available yet.</p>
        </div>

        <div v-else class="row">
          <div v-for="summit in summits" :key="summit.id" class="col-md-4 mb-4">
            <div class="card h-100 shadow-sm">
              <img
                v-if="summit.image"
                :src="'/storage/' + summit.image"
                class="card-img-top"
                alt="Summit image"
                style="height: 200px; object-fit: cover;"
              />
              <div v-else class="card-img-top bg-light d-flex align-items-center justify-content-center" style="height: 200px;">
                <i class="fa fa-mountain fa-3x text-muted"></i>
              </div>
              <div class="card-body d-flex flex-column">
                <h5 class="card-title">{{ summit.title }}</h5>
                <p v-if="summit.height" class="card-text text-muted">
                  <i class="fa fa-arrows-alt-v"></i> {{ summit.height }}m
                </p>
                <p v-if="summit.region" class="card-text text-muted small">
                  <i class="fa fa-map-marker-alt"></i> {{ summit.region.us_name }}
                </p>
                <div class="mt-auto pt-3 d-flex gap-2">
                  <router-link :to="'/summit/' + summit.url_title" class="btn btn-outline-primary btn-sm">
                    View Details
                  </router-link>
                  <router-link :to="'/make_ascent/' + summit.id" class="btn btn-primary btn-sm">
                    Make Ascent
                  </router-link>
                </div>
              </div>
            </div>
          </div>
        </div>

        <metaData
          title="Summit List"
          description="List of climbing summits in Georgia"
          :image="'/public/images/meta_img/mountain.jpg'"
        />
      </div>
    </main>
  </div>
</template>

<script>
import metaData from '../../items/MetaDataComponent.vue'

export default {
  name: 'SummitListPage',
  components: {
    metaData,
  },
  data() {
    return {
      summits: [],
      loading: false,
    }
  },
  mounted() {
    this.loadSummits()
  },
  methods: {
    loadSummits() {
      this.loading = true
      axios.get('summit/list')
        .then(response => {
          this.summits = response.data
        })
        .catch(error => {
          console.error('Error loading summits:', error)
        })
        .finally(() => {
          this.loading = false
        })
    },
  }
}
</script>

<style scoped>
.card {
  transition: transform 0.2s;
}
.card:hover {
  transform: translateY(-4px);
}
.gap-2 {
  gap: 0.5rem;
}
</style>
