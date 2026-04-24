<template>
  <main role="main" class="container py-4">

    <!-- Loading summit -->
    <div v-if="loadingSummit" class="text-center py-5">
      <div class="spinner-border" role="status"></div>
      <p class="mt-2">Loading summit...</p>
    </div>

    <!-- Summit not found -->
    <div v-else-if="!summit" class="text-center py-5">
      <h3>Summit not found</h3>
      <router-link to="/summits/list" class="btn btn-primary mt-3">Back to list</router-link>
    </div>

    <!-- Success screen -->
    <div v-else-if="submitted" class="text-center py-5">
      <div class="mb-4">
        <i class="fa fa-check-circle fa-5x text-success"></i>
      </div>
      <h2 class="mb-3">Ascent Recorded!</h2>
      <p class="lead mb-2">Your ascent of <strong>{{ summit.title }}</strong> has been successfully recorded.</p>

      <div class="card d-inline-block text-left mt-3 mb-4 px-4 py-3 shadow-sm" style="min-width: 260px;">
        <div class="mb-2">
          <span v-if="submitResult.is_gps_validated" class="badge badge-success badge-pill px-3 py-2">
            <i class="fa fa-check-circle"></i> GPS Location Verified
          </span>
          <span v-else class="badge badge-warning badge-pill px-3 py-2">
            <i class="fa fa-exclamation-triangle"></i> GPS Not Verified
          </span>
        </div>
        <div v-if="submitResult.matched_users_count > 0" class="text-success mt-2">
          <i class="fa fa-user-check"></i>
          Matched to {{ submitResult.matched_users_count }} registered user(s)
        </div>
        <div v-else class="text-muted mt-2">
          <i class="fa fa-user"></i> No registered user matched
        </div>
      </div>

      <div>
        <router-link :to="'/summit/' + summit.url_title" class="btn btn-outline-primary mr-2">
          Back to Summit
        </router-link>
        <router-link to="/summits/list" class="btn btn-outline-secondary">
          All Summits
        </router-link>
      </div>
    </div>

    <!-- Main form -->
    <div v-else>
      <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
          <li class="breadcrumb-item">
            <router-link to="/summits/list">Summits</router-link>
          </li>
          <li class="breadcrumb-item active">Record Ascent: {{ summit.title }}</li>
        </ol>
      </nav>

      <div class="row">
        <div class="col-md-8 offset-md-2">

          <div class="card shadow mb-4">
            <div class="card-header bg-primary text-white">
              <h4 class="mb-0">
                <i class="fa fa-flag"></i> Record Ascent — {{ summit.title }}
                <span v-if="summit.height" class="small ml-2">({{ summit.height }}m)</span>
              </h4>
            </div>
            <div class="card-body">

              <!-- Step 1: GPS Check -->
              <div class="mb-4">
                <h5 class="border-bottom pb-2 mb-3">
                  Step 1: Location Verification
                  <span v-if="gpsValidated" class="badge badge-success ml-2">Verified</span>
                  <span v-else-if="gpsChecked && !gpsValidated" class="badge badge-warning ml-2">Not at summit</span>
                </h5>

                <div v-if="!gpsChecked && !skipGps" class="d-flex gap-2 flex-wrap">
                  <button
                    type="button"
                    class="btn btn-primary"
                    @click="checkLocation"
                    :disabled="checkingLocation"
                  >
                    <span v-if="checkingLocation">
                      <span class="spinner-border spinner-border-sm mr-1"></span>
                      Checking location...
                    </span>
                    <span v-else>
                      <i class="fa fa-crosshairs"></i> Check my location
                    </span>
                  </button>
                  <button
                    type="button"
                    class="btn btn-outline-secondary"
                    @click="skipGps = true"
                  >
                    Continue without GPS
                  </button>
                </div>

                <div v-if="gpsChecked" class="mt-2">
                  <div v-if="gpsValidated" class="alert alert-success d-flex align-items-center">
                    <i class="fa fa-check-circle fa-lg mr-2"></i>
                    <div>
                      <strong>Location verified!</strong>
                      You are within {{ Math.round(gpsDistance) }}m of the summit.
                    </div>
                  </div>
                  <div v-else class="alert alert-warning d-flex align-items-center">
                    <i class="fa fa-exclamation-triangle fa-lg mr-2"></i>
                    <div>
                      <strong>You are {{ Math.round(gpsDistance) }}m away</strong> from the summit.
                      You can still record your ascent, but it will not be GPS-verified.
                    </div>
                  </div>
                  <button type="button" class="btn btn-sm btn-outline-secondary mt-1" @click="retryGps">
                    <i class="fa fa-redo"></i> Try again
                  </button>
                </div>

                <div v-if="skipGps && !gpsChecked" class="alert alert-info mt-2">
                  <i class="fa fa-info-circle"></i>
                  Continuing without GPS verification. Your ascent will be recorded without location validation.
                </div>

                <div v-if="gpsError" class="alert alert-danger mt-2">
                  <i class="fa fa-exclamation-circle"></i>
                  {{ gpsError }}
                  <button type="button" class="btn btn-sm btn-outline-danger ml-2" @click="skipGps = true">
                    Continue anyway
                  </button>
                </div>
              </div>

              <!-- Step 2: Ascent Form (shown after GPS check or skip) -->
              <div v-if="showForm">
                <h5 class="border-bottom pb-2 mb-3">Step 2: Ascent Details</h5>

                <div v-if="formErrors && Object.keys(formErrors).length" class="alert alert-danger">
                  <ul class="mb-0">
                    <li v-for="(msgs, field) in formErrors" :key="field">{{ msgs[0] }}</li>
                  </ul>
                </div>

                <form @submit.prevent="submitAscent" enctype="multipart/form-data">

                  <div class="form-row">
                    <div class="form-group col-md-6">
                      <label>First Name <span class="text-danger">*</span></label>
                      <input
                        type="text"
                        v-model="form.name"
                        class="form-control"
                        required
                        placeholder="Your first name"
                      />
                    </div>
                    <div class="form-group col-md-6">
                      <label>Last Name <span class="text-danger">*</span></label>
                      <input
                        type="text"
                        v-model="form.surname"
                        class="form-control"
                        required
                        placeholder="Your last name"
                      />
                    </div>
                  </div>

                  <div class="form-group">
                    <label>Email <span class="text-muted small">(optional — used to link to your account)</span></label>
                    <input
                      type="email"
                      v-model="form.email"
                      class="form-control"
                      placeholder="your@email.com"
                    />
                  </div>

                  <div class="form-group">
                    <label>Ascent Date</label>
                    <input
                      type="date"
                      v-model="form.ascent_date"
                      class="form-control"
                    />
                  </div>

                  <div class="form-group">
                    <label>Route</label>
                    <select v-model="form.route_id" class="form-control" @change="onRouteChange">
                      <option :value="null">-- Select route (optional) --</option>
                      <option v-for="route in routes" :key="route.id" :value="route.id">
                        {{ route.name }} <span v-if="route.grade">({{ route.grade }})</span>
                      </option>
                      <option value="other">Other route...</option>
                    </select>
                  </div>

                  <div v-if="showOtherRoute" class="form-group">
                    <label>Route Name <span class="text-danger">*</span></label>
                    <input
                      type="text"
                      v-model="form.other_route"
                      class="form-control"
                      placeholder="Enter route name"
                    />
                  </div>

                  <div class="form-group">
                    <label>Comment <span class="text-muted small">(optional)</span></label>
                    <textarea
                      v-model="form.comment"
                      class="form-control"
                      rows="3"
                      placeholder="Notes about your ascent..."
                    ></textarea>
                  </div>

                  <div class="form-group">
                    <label>Photo <span class="text-muted small">(optional)</span></label>
                    <input
                      type="file"
                      class="form-control-file"
                      accept="image/*"
                      capture="environment"
                      @change="handlePhoto"
                    />
                    <small class="form-text text-muted">
                      You can use your camera to take a photo directly.
                    </small>
                    <div v-if="photoPreview" class="mt-2">
                      <img :src="photoPreview" alt="Photo preview" style="max-height: 150px;" class="img-thumbnail" />
                    </div>
                  </div>

                  <button
                    type="submit"
                    class="btn btn-success btn-block btn-lg"
                    :disabled="submitting"
                  >
                    <span v-if="submitting">
                      <span class="spinner-border spinner-border-sm mr-1"></span>
                      Recording ascent...
                    </span>
                    <span v-else">
                      <i class="fa fa-flag-checkered"></i> Record My Ascent
                    </span>
                  </button>

                </form>
              </div>

            </div>
          </div>

        </div>
      </div>
    </div>

    <metaData
      :title="summit ? 'Record Ascent: ' + summit.title : 'Make Ascent'"
      description="Record your summit ascent"
      :image="'/public/images/meta_img/mountain.jpg'"
    />
  </main>
</template>

<script>
import metaData from '../items/MetaDataComponent.vue'

function haversine(lat1, lon1, lat2, lon2) {
  const R = 6371000
  const φ1 = lat1 * Math.PI / 180
  const φ2 = lat2 * Math.PI / 180
  const Δφ = (lat2 - lat1) * Math.PI / 180
  const Δλ = (lon2 - lon1) * Math.PI / 180
  const a = Math.sin(Δφ / 2) ** 2 + Math.cos(φ1) * Math.cos(φ2) * Math.sin(Δλ / 2) ** 2
  return R * 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a))
}

export default {
  name: 'MakeSummitAscentPage',
  components: { metaData },
  data() {
    return {
      // Summit
      summit: null,
      loadingSummit: true,

      // GPS
      gpsChecked: false,
      gpsValidated: false,
      gpsDistance: 0,
      gpsError: null,
      checkingLocation: false,
      skipGps: false,
      userLatitude: null,
      userLongitude: null,

      // Routes
      routes: [],

      // Form
      form: {
        name: '',
        surname: '',
        email: '',
        route_id: null,
        other_route: '',
        comment: '',
        ascent_date: new Date().toISOString().slice(0, 10),
        photo: null,
      },
      showOtherRoute: false,
      photoPreview: null,

      // Submission
      submitting: false,
      submitted: false,
      submitResult: null,
      formErrors: {},
    }
  },
  computed: {
    showForm() {
      return this.gpsChecked || this.skipGps
    }
  },
  mounted() {
    this.loadSummit()
  },
  methods: {
    loadSummit() {
      const id = this.$route.params.id
      // Load from summit list (published), find by id
      axios.get('summit/list')
        .then(response => {
          const found = response.data.find(s => s.id == id)
          if (!found) {
            this.summit = null
            this.loadingSummit = false
            return
          }
          // Fetch full details by url_title
          axios.get(`summit/show/${found.url_title}`)
            .then(r => {
              this.summit = r.data
              this.loadRoutes()
            })
            .catch(error => {
              console.error('Error loading summit details:', error)
              this.summit = found
              this.loadRoutes()
            })
            .finally(() => {
              this.loadingSummit = false
            })
        })
        .catch(error => {
          console.error('Error loading summit list:', error)
          this.summit = null
          this.loadingSummit = false
        })
    },
    loadRoutes() {
      if (!this.summit) return
      axios.get(`summit/routes/${this.summit.id}`)
        .then(response => {
          this.routes = response.data
        })
        .catch(error => {
          console.error('Error loading routes:', error)
        })
    },
    checkLocation() {
      if (!navigator.geolocation) {
        this.gpsError = 'Geolocation is not supported by your browser.'
        return
      }

      this.checkingLocation = true
      this.gpsError = null

      navigator.geolocation.getCurrentPosition(
        (position) => {
          this.userLatitude = position.coords.latitude
          this.userLongitude = position.coords.longitude

          if (this.summit.latitude && this.summit.longitude) {
            this.gpsDistance = haversine(
              this.userLatitude,
              this.userLongitude,
              parseFloat(this.summit.latitude),
              parseFloat(this.summit.longitude)
            )
            this.gpsValidated = this.gpsDistance <= 20
          } else {
            this.gpsDistance = 0
            this.gpsValidated = false
          }

          this.gpsChecked = true
          this.checkingLocation = false
        },
        (error) => {
          this.checkingLocation = false
          switch (error.code) {
            case error.PERMISSION_DENIED:
              this.gpsError = 'Location access was denied. You can continue without GPS verification.'
              break
            case error.POSITION_UNAVAILABLE:
              this.gpsError = 'Location information is unavailable.'
              break
            case error.TIMEOUT:
              this.gpsError = 'Location request timed out.'
              break
            default:
              this.gpsError = 'An unknown error occurred while getting your location.'
          }
        },
        {
          enableHighAccuracy: true,
          timeout: 15000,
          maximumAge: 0,
        }
      )
    },
    retryGps() {
      this.gpsChecked = false
      this.gpsValidated = false
      this.gpsDistance = 0
      this.gpsError = null
      this.skipGps = false
    },
    onRouteChange() {
      this.showOtherRoute = this.form.route_id === 'other'
      if (this.showOtherRoute) {
        this.form.route_id = null
      }
    },
    handlePhoto(event) {
      const file = event.target.files[0]
      if (!file) {
        this.form.photo = null
        this.photoPreview = null
        return
      }
      this.form.photo = file
      const reader = new FileReader()
      reader.onload = (e) => {
        this.photoPreview = e.target.result
      }
      reader.readAsDataURL(file)
    },
    submitAscent() {
      this.submitting = true
      this.formErrors = {}

      const formData = new FormData()
      formData.append('name', this.form.name)
      formData.append('surname', this.form.surname)
      if (this.form.email) formData.append('email', this.form.email)
      if (this.form.route_id) formData.append('route_id', this.form.route_id)
      if (this.showOtherRoute && this.form.other_route) formData.append('other_route', this.form.other_route)
      if (this.form.comment) formData.append('comment', this.form.comment)
      if (this.form.ascent_date) formData.append('ascent_date', this.form.ascent_date)
      if (this.form.photo) formData.append('photo', this.form.photo)

      // GPS data
      if (this.userLatitude !== null) formData.append('user_latitude', this.userLatitude)
      if (this.userLongitude !== null) formData.append('user_longitude', this.userLongitude)
      formData.append('is_gps_validated', this.gpsValidated ? 1 : 0)

      axios.post(`summit/ascent/${this.summit.id}`, formData, {
        headers: { 'Content-Type': 'multipart/form-data' }
      })
        .then(response => {
          this.submitResult = response.data
          this.submitted = true
        })
        .catch(error => {
          if (error.response && error.response.data && error.response.data.errors) {
            this.formErrors = error.response.data.errors
          } else {
            alert('Failed to record ascent. Please try again.')
          }
        })
        .finally(() => {
          this.submitting = false
        })
    }
  }
}
</script>

<style scoped>
.gap-2 {
  gap: 0.5rem;
}
</style>
