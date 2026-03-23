<template>
  <div>
    <main role="main" class="container">
      <div class="container">
        <h1 class='index_h2'>{{ $t('summit.title.summit_list') || 'Summit List' }}</h1>

        <div class="bar"><i class="fa fa-mountain"></i></div>

        <div class="row">
          <div v-for="summit in summits" :key="summit.id" class="col-md-4 mb-4">
            <div class="card h-100">
              <img v-if="summit.image" :src="summit.image" class="card-img-top" alt="Summit image">
              <div class="card-body">
                <h5 class="card-title">{{ summit.name }}</h5>
                <p class="card-text">Height: {{ summit.height }}m</p>
                <p class="card-text">Location: {{ summit.location }}</p>
                <router-link :to="'/summit/' + summit.id" class="btn btn-primary">View Details</router-link>
              </div>
            </div>
          </div>
        </div>

        <metaData
          :title="$t('summit.title.summit_list') || 'Summit List'"
          :description="$t('summit.meta.list') || 'List of climbing summits'"
          :image="'/public/images/meta_img/mountain.jpg'"
        />
      </div>
    </main>
  </div>
</template>

<script>
import metaData from '../../items/MetaDataComponent.vue'
import axios from 'axios'

export default {
  components: {
    metaData
  },
  data() {
    return {
      summits: []
    }
  },
  mounted() {
    this.loadSummits()
  },
  methods: {
    loadSummits() {
      // Placeholder API call - replace with real endpoint
      axios.get('/api/summits')
        .then(response => {
          this.summits = response.data.data || this.mockSummits()
        })
        .catch(error => {
          console.error('Error loading summits:', error)
          this.summits = this.mockSummits()
        })
    },
    mockSummits() {
      return [
        {
          id: 1,
          name: 'Everest',
          height: 8848,
          location: 'Himalayas',
          image: '/public/images/summits/everest.jpg'
        },
        {
          id: 2,
          name: 'K2',
          height: 8611,
          location: 'Karakoram',
          image: '/public/images/summits/k2.jpg'
        },
        {
          id: 3,
          name: 'Kangchenjunga',
          height: 8586,
          location: 'Himalayas',
          image: '/public/images/summits/kangchenjunga.jpg'
        }
      ]
    }
  }
}
</script>

<style scoped>
.card {
  transition: transform 0.2s;
}

.card:hover {
  transform: translateY(-5px);
}

.card-img-top {
  height: 200px;
  object-fit: cover;
}
</style>
