<template>
    <span>
        <!-- {{ $t('guide.sector.authers_list') }} <a @click="show_modal"> {{ $t('guide.sector.authers_list_button') }}</a> -->
        <div>
            <button type="bottom" class="btn btn-default btn-send main-btn" @click="show_modal">{{ $t('guide.sector.authers_list_button') }}</button>
        </div>

<StackModal v-model="is_show_modal" :title="$t('guide.sector.routes_authers_list_title')" @close="is_show_modal = false">
          <!-- <template #default> -->
            <pre class="language-vue"> 
                <div v-if="Object.keys(authers).length === 0" class="text-center p-4">
                    <h2>{{ $t('guide.sector.no_authors_found') }}</h2>
                </div> <div v-else>

                    <input type="text" v-model="filterAuthor" :placeholder="$t('guide.sector.filter_by_author_name')" class="form-control ">
                    <select v-model="sortBy" class="form-control ">
                        <option value="total">{{ $t('guide.sector.sort_by_total_routes') }}</option>
                        <option value="author">{{ $t('guide.sector.sort_by_author_name') }}</option>
                        <option v-for="category in route_categories" :key="category.value" :value="category.value">
                            {{ $t('guide.sector.sort_by') }} {{ $t(category.label) }}
                        </option>
                    </select>
                    <select v-model="sortOrder" class="form-control ">
                        <option value="desc">{{ $t('guide.sector.sort_descending') }}</option>
                        <option value="asc">{{ $t('guide.sector.sort_ascending') }}</option>
                    </select>

                    <div class="table-responsive">
                    <table class="table table-hover" id="dev-table">
                        <thead>
                            <tr>
                                <th>{{ $t('guide.sector.auther_name') }}</th>
                                <!-- <th>{{ $t('guide.sector.auther_routes_quantity') }}</th> -->
                                <th>{{ $t('guide.sector.total_routes_header') }}</th>
                                <th v-for="category in route_categories" :key="category.value"> {{ $t(category.label) }} </th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(categories, author) in filteredAndSortedAuthors" :key="author">
                                <td>{{author}}</td>
                                <td>{{ getTotalRoutes(categories) }}</td>
                                <td v-for="category in route_categories" :key="category.value"> {{ categories[category.value] || 0 }} </td>
                            </tr>
                        </tbody>
                    </table>
                    </div>
                </div>
            </pre>
          <!-- </template> -->
        </StackModal> 
    </span>
</template>

<script setup>
import { ref, computed, watch, onMounted } from 'vue'
import { useI18n } from 'vue-i18n'
import axios from 'axios'
// import StackModal from '@innologica/vue-stackable-modal'  // Global now

const { t } = useI18n()

const props = defineProps({
  route_categories_prop: {
    type: Array,
    default: () => [
      { value: 'sport', label: 'guide.sector.sport_climbing' },
      { value: 'boulder', label: 'guide.sector.bouldering' },
      { value: 'dry', label: 'guide.sector.dry' },
      { value: 'ice', label: 'guide.sector.ice' }
    ]
  }
})

const authers = ref({})
const is_show_modal = ref(false)
const route_categories = ref([])
const filterAuthor = ref('')
const sortBy = ref('total')
const sortOrder = ref('desc')

watch(() => props.route_categories_prop, (newVal) => {
  route_categories.value = newVal
}, { immediate: true })

const filteredAndSortedAuthors = computed(() => {
  let result = { ...authers.value }
  
  // Filter by author name
  if (filterAuthor.value) {
    const filter = filterAuthor.value.toLowerCase()
    result = Object.fromEntries(
      Object.entries(result).filter(([author]) => 
        author.toLowerCase().includes(filter)
      )
    )
  }
  
  // Filter out authors who don't have routes in the selected category
  if (sortBy.value !== 'author' && sortBy.value !== 'total') {
    result = Object.fromEntries(
      Object.entries(result).filter(([author, categories]) => {
        const count = categories[sortBy.value] || 0
        return count > 0
      })
    )
  }
  
  // Sort authors
  const sortedEntries = Object.entries(result).sort((a, b) => {
    let valueA, valueB
    
    if (sortBy.value === 'author') {
      valueA = a[0].toLowerCase()
      valueB = b[0].toLowerCase()
    } else if (sortBy.value === 'total') {
      valueA = getTotalRoutes(a[1])
      valueB = getTotalRoutes(b[1])
    } else {
      valueA = a[1][sortBy.value] || 0
      valueB = b[1][sortBy.value] || 0
    }
    
    if (sortOrder.value === 'asc') {
      return valueA > valueB ? 1 : -1
    } else {
      return valueA < valueB ? 1 : -1
    }
  })
  
  return Object.fromEntries(sortedEntries)
})

const getTotalRoutes = (categories) => {
  return route_categories.value.reduce((sum, category) => {
    return sum + (categories[category.value] || 0)
  }, 0)
}

const show_modal = async () => {
  // Reset filter and sort values
  filterAuthor.value = ''
  sortBy.value = 'total'
  sortOrder.value = 'desc'
  
  try {
    const response = await axios.post('/get_route/routes_authers_by_categories/', {
      route_categories: route_categories.value.map(cat => cat.value)
    })
    authers.value = response.data
    is_show_modal.value = true
  } catch (error) {
    console.error(error)
  }
}
</script>

<style>
/* .modal-body{
    height: auto;
    min-height: 600px;
}
th:nth-child(2n+1) {
  background: #d7b396;
}

.table-responsive {
    overflow-x: auto;
    width: 100%;
}

.table-responsive table {
    min-width: 800px;
}

@media (max-width: 767px) {
    div > .main-btn {
        display: block;
        margin: 1em auto;
        float: none !important;
        width: fit-content;
    }
} */
</style>
