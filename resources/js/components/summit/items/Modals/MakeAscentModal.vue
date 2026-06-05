<template>
  <StackModal
    :modelValue="modelValue"
    :title="summit ? ($t('summit.summit_page.record_ascent') + ' — ' + summit.title) : $t('summit.summit_page.record_ascent')"
    size="lg"
    :saveButton="{ visible: false }"
    :cancelButton="{ visible: false }"
    @close="handleClose"
  >
    <!-- Success screen -->
    <div v-if="submitted" class="text-center py-4">
      <div class="mb-3">
        <i class="fa fa-check-circle fa-5x text-success"></i>
      </div>
      <h4 class="mb-3">{{ $t('summit.ascent_page.ascent_recorded') }}</h4>
      <p v-if="summit" class="text-muted mb-3">
        {{ $t('summit.ascent_page.success_text').replace('{summit}', summit.title) }}
      </p>
      <div class="d-inline-block text-left mb-4 px-4 py-3 border rounded" style="min-width: 220px;">
        <div class="mb-2">
          <span v-if="submitResult && submitResult.is_gps_validated" class="badge badge-success">
            <i class="fa fa-check-circle"></i> {{ $t('summit.ascent_page.gps_verified') }}
          </span>
          <span v-else class="badge badge-warning">
            <i class="fa fa-exclamation-triangle"></i> {{ $t('summit.ascent_page.gps_not_verified') }}
          </span>
        </div>
        <div v-if="submitResult && submitResult.matched_users_count > 0" class="text-success small mt-1">
          <i class="fa fa-user-check"></i>
          {{ $t('summit.ascent_page.matched_users').replace('{count}', submitResult.matched_users_count) }}
        </div>
        <div v-else class="text-muted small mt-1">
          <i class="fa fa-user"></i> {{ $t('summit.ascent_page.no_user_matched') }}
        </div>
      </div>
      <div>
        <button class="btn btn-outline-primary btn-send main-btn" @click="handleClose">
          {{ $t('summit.ascent_page.back_to_summit') }}
        </button>
      </div>
    </div>

    <!-- Form -->
    <div v-else>

      <!-- Login prompt for guests -->
      <div v-if="!authLoading && !isLoggedIn"
           class="alert alert-light border mb-4 d-flex align-items-center justify-content-between flex-wrap">
        <span class="text-muted">
          <i class="fa fa-user-circle mr-1"></i>
          Log in to record this ascent to your account.
        </span>
        <div class="mt-2 mt-sm-0">
          <button type="button" class="btn btn-primary btn-sm" @click="openLogin">
            <i class="fa fa-sign-in-alt"></i> Login
          </button>
        </div>
      </div>

      <!-- GPS status bar -->
      <div class="mb-4">

        <!-- Checking -->
        <div v-if="checkingLocation" class="d-flex align-items-center text-muted py-2">
          <span class="spinner-border spinner-border-sm mr-2"></span>
          {{ $t('summit.ascent_page.checking') }}
        </div>

        <!-- GPS valid -->
        <div v-else-if="gpsChecked && gpsValidated" class="alert alert-success d-flex align-items-center mb-0">
          <i class="fa fa-check-circle fa-lg mr-2"></i>
          <div>
            <strong>{{ $t('summit.ascent_page.location_verified') }}</strong>
            <div v-if="gpsDistance > 0" class="small">{{ formatDistance(gpsDistance) }} from summit</div>
          </div>
        </div>

        <!-- GPS checked but NOT within 20 m — prominent warning -->
        <div v-else-if="gpsChecked && !gpsValidated" class="alert alert-danger mb-0">
          <div class="d-flex align-items-center">
            <i class="fa fa-times-circle fa-lg mr-2"></i>
            <div>
              <strong>{{ $t('summit.ascent_page.gps_not_verified') }}</strong>
              <div class="small mt-1">
                You are <strong>{{ formatDistance(gpsDistance) }}</strong> from the summit — must be within 20 m for GPS validation.
                Your ascent will still be recorded but without GPS verification.
              </div>
            </div>
          </div>
          <button type="button" class="btn btn-sm btn-outline-danger mt-2" @click="retryGps">
            <i class="fa fa-redo"></i> {{ $t('summit.ascent_page.try_again') }}
          </button>
        </div>

        <!-- GPS unavailable (no HTTPS / denied / unsupported) -->
        <div v-else-if="gpsError && !checkingLocation" class="alert alert-info mb-0">
          <i class="fa fa-info-circle mr-1"></i> {{ gpsError }}
        </div>

        <!-- Summit has no coordinates — GPS irrelevant -->
        <div v-else-if="skipGps" class="alert alert-secondary mb-0">
          <i class="fa fa-map-marker-slash mr-1"></i> GPS coordinates not set for this summit.
        </div>

      </div>

      <!-- Ascent form (shown after GPS check or skip) -->
      <div v-if="showForm">
        <h5 class="border-bottom pb-2 mb-3">{{ $t('summit.ascent_page.step2_title') }}</h5>

        <div v-if="formErrors && Object.keys(formErrors).length" class="alert alert-danger">
          <ul class="mb-0">
            <li v-for="(msgs, field) in formErrors" :key="field">{{ msgs[0] }}</li>
          </ul>
        </div>

        <form @submit.prevent="submitAscent" enctype="multipart/form-data">

          <!-- Auth loading spinner in user section -->
          <div v-if="authLoading" class="text-center py-3 mb-3">
            <span class="spinner-border spinner-border-sm"></span>
          </div>

          <!-- Logged-in user info -->
          <div v-else-if="isLoggedIn && authUser" class="alert alert-info d-flex align-items-center mb-3">
            <i class="fa fa-user-circle fa-lg mr-3"></i>
            <div>
              <strong>{{ authUser.name }} {{ authUser.surname }}</strong>
              <div v-if="authUser.email" class="small text-muted">{{ authUser.email }}</div>
            </div>
          </div>

          <!-- Guest name/email fields -->
          <template v-else>
            <div class="form-row">
              <div class="form-group col-md-6">
                <label>First Name <span class="text-danger">*</span></label>
                <input type="text" v-model="form.name" class="form-control" required placeholder="Your first name" />
              </div>
              <div class="form-group col-md-6">
                <label>{{ $t('summit.ascent_page.last_name') }} <span class="text-danger">*</span></label>
                <input type="text" v-model="form.surname" class="form-control" required placeholder="Your last name" />
              </div>
            </div>
            <div class="form-group">
              <label>Email <span class="text-muted small">(optional — links to your account if found)</span></label>
              <input type="email" v-model="form.email" class="form-control" placeholder="your@email.com" />
            </div>
          </template>

          <div class="form-group">
            <label>{{ $t('summit.ascent_page.route_label') }}</label>
            <select v-model="form.article_id" class="form-control" @change="onRouteChange">
              <option :value="null">-- Select route (optional) --</option>
              <option v-for="route in routes" :key="route.id" :value="route.id">
                {{ route.name }}<span v-if="route.grade"> ({{ route.grade }})</span>
              </option>
              <option value="other">Other route...</option>
            </select>
          </div>

          <div v-if="showOtherRoute" class="form-group">
            <label>Route Name <span class="text-danger">*</span></label>
            <input type="text" v-model="form.other_route" class="form-control" placeholder="Enter route name" />
          </div>

          <div class="form-group">
            <label>{{ $t('summit.ascent_page.comment_label') }}</label>
            <textarea v-model="form.comment" class="form-control" rows="3"
                      placeholder="Notes about your ascent..."></textarea>
          </div>

          <div class="form-group">
            <label>Photo <span class="text-muted small">(optional)</span></label>
            <input type="file" class="form-control-file" accept="image/*" capture="environment"
                   @change="handlePhoto" />
            <small class="form-text text-muted">You can use your camera to take a photo directly.</small>
            <div v-if="photoPreview" class="mt-2">
              <img :src="photoPreview" alt="Photo preview" style="max-height: 150px;" class="img-thumbnail" />
            </div>
          </div>

          <button type="submit" class="btn btn-success btn-block btn-send main-btn" :disabled="submitting">
            <span v-if="submitting">
              <span class="spinner-border spinner-border-sm mr-1"></span>
              {{ $t('summit.ascent_page.submitting') }}
            </span>
            <span v-else>
              <i class="fa fa-flag-checkered"></i> {{ $t('summit.summit_page.record_ascent') }}
            </span>
          </button>

        </form>
      </div>

    </div>
  </StackModal>
</template>

<script>
import { useAuthStore } from '../../../../store/auth.js'

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
  name: 'MakeAscentModal',
  props: {
    modelValue: {
      type: Boolean,
      default: false,
    },
    summit: {
      type: Object,
      default: null,
    },
  },
  emits: ['update:modelValue', 'submitted'],
  data() {
    return {
      authLoading: true,
      pendingReopen: false,
      gpsChecked: false,
      gpsValidated: false,
      gpsDistance: 0,
      gpsError: null,
      checkingLocation: false,
      skipGps: false,
      userLatitude: null,
      userLongitude: null,
      routes: [],
      form: {
        name: '',
        surname: '',
        email: '',
        article_id: null,
        other_route: '',
        comment: '',
        photo: null,
      },
      showOtherRoute: false,
      photoPreview: null,
      submitting: false,
      submitted: false,
      submitResult: null,
      formErrors: {},
    }
  },
  computed: {
    showForm() {
      return this.gpsChecked || this.skipGps || (this.gpsError !== null && !this.checkingLocation)
    },
    isLoggedIn() {
      return useAuthStore().isLoggedIn
    },
    authUser() {
      return useAuthStore().user
    },
  },
  watch: {
    modelValue(val) {
      if (val) this.onOpen()
    },
  },
  created() {
    this.$bus.$on('login-modal-closed', this.onLoginModalClosed)
  },
  beforeUnmount() {
    this.$bus.$off('login-modal-closed', this.onLoginModalClosed)
  },
  methods: {
    onOpen() {
      this.resetForm()
      useAuthStore().fetchUser().finally(() => {
        this.authLoading = false
      })
      if (this.summit) {
        this.loadRoutes()
        if (this.summit.latitude && this.summit.longitude) {
          this.checkLocation()
        } else {
          this.skipGps = true
        }
      }
    },
    resetForm() {
      this.authLoading = true
      this.gpsChecked = false
      this.gpsValidated = false
      this.gpsDistance = 0
      this.gpsError = null
      this.checkingLocation = false
      this.skipGps = false
      this.userLatitude = null
      this.userLongitude = null
      this.routes = []
      this.form = { name: '', surname: '', email: '', article_id: null, other_route: '', comment: '', photo: null }
      this.showOtherRoute = false
      this.photoPreview = null
      this.submitting = false
      this.submitted = false
      this.submitResult = null
      this.formErrors = {}
    },
    handleClose() {
      if (!this.modelValue) return
      this.$emit('update:modelValue', false)
      setTimeout(() => this.resetForm(), 300)
    },
    openLogin() {
      this.pendingReopen = true
      this.$emit('update:modelValue', false)
      this.$bus.$emit('open-login-modal')
    },
    onLoginModalClosed() {
      if (!this.pendingReopen) return
      this.pendingReopen = false
      this.$nextTick(() => {
        this.$emit('update:modelValue', true)
      })
    },
    loadRoutes() {
      if (!this.summit) return
      axios.get(`summit/routes/${this.summit.id}`)
        .then(r => { this.routes = r.data })
        .catch(() => {})
    },
    formatDistance(meters) {
      if (!meters) return ''
      return meters >= 1000
        ? `${(meters / 1000).toFixed(1)} km`
        : `${Math.round(meters)} m`
    },
    checkLocation() {
      if (!window.isSecureContext) {
        this.gpsError = 'GPS verification requires a secure (HTTPS) connection.'
        this.checkingLocation = false
        return
      }
      if (!navigator.geolocation) {
        this.gpsError = 'Geolocation is not supported by your browser.'
        this.checkingLocation = false
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
        { enableHighAccuracy: true, timeout: 15000, maximumAge: 0 }
      )
    },
    retryGps() {
      this.gpsChecked = false
      this.gpsValidated = false
      this.gpsDistance = 0
      this.gpsError = null
      this.userLatitude = null
      this.userLongitude = null
      this.checkLocation()
    },
    onRouteChange() {
      this.showOtherRoute = this.form.article_id === 'other'
      if (this.showOtherRoute) this.form.article_id = null
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
      reader.onload = (e) => { this.photoPreview = e.target.result }
      reader.readAsDataURL(file)
    },
    submitAscent() {
      this.submitting = true
      this.formErrors = {}
      const formData = new FormData()
      if (!this.isLoggedIn) {
        formData.append('name', this.form.name)
        formData.append('surname', this.form.surname)
        if (this.form.email) formData.append('email', this.form.email)
      }
      if (this.form.article_id) formData.append('article_id', this.form.article_id)
      if (this.showOtherRoute && this.form.other_route) formData.append('other_route', this.form.other_route)
      if (this.form.comment) formData.append('comment', this.form.comment)
      if (this.form.photo) formData.append('photo', this.form.photo)
      if (this.userLatitude !== null) formData.append('user_latitude', this.userLatitude)
      if (this.userLongitude !== null) formData.append('user_longitude', this.userLongitude)
      formData.append('is_gps_validated', this.gpsValidated ? 1 : 0)
      axios.post(`summit/ascent/${this.summit.id}`, formData, {
        headers: { 'Content-Type': 'multipart/form-data' }
      })
        .then(response => {
          this.submitResult = response.data
          this.submitted = true
          this.$emit('submitted', response.data)
        })
        .catch(error => {
          if (error.response?.data?.errors) {
            this.formErrors = error.response.data.errors
          } else {
            alert('Failed to record ascent. Please try again.')
          }
        })
        .finally(() => { this.submitting = false })
    },
  },
}
</script>
