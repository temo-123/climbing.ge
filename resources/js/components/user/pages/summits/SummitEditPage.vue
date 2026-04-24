<template>
    <div class="row">
        <left-menu />
        <div class="col-sm-12">
            <div class="row">
                <div class="col-md-12">
                    <breadcrumb />
                </div>
            </div>

            <div class="row">
                <div class="col-md-8 offset-md-2">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h4 class="mb-0">Edit Summit</h4>
                            <router-link to="/summits" class="btn btn-secondary btn-sm">Back</router-link>
                        </div>
                        <div class="card-body">

                            <div v-if="pageLoading" class="text-center py-4">
                                <div class="spinner-border" role="status"></div>
                            </div>

                            <template v-else>
                                <div v-if="errors && Object.keys(errors).length" class="alert alert-danger">
                                    <ul class="mb-0">
                                        <li v-for="(msgs, field) in errors" :key="field">
                                            {{ msgs[0] }}
                                        </li>
                                    </ul>
                                </div>

                                <form @submit.prevent="submitForm" enctype="multipart/form-data">

                                    <div class="form-group">
                                        <label>Title <span class="text-danger">*</span></label>
                                        <input
                                            type="text"
                                            v-model="form.title"
                                            class="form-control"
                                            required
                                            placeholder="Summit title"
                                        />
                                    </div>

                                    <div class="form-group">
                                        <label>Description</label>
                                        <textarea
                                            v-model="form.description"
                                            class="form-control"
                                            rows="4"
                                            placeholder="Description (optional)"
                                        ></textarea>
                                    </div>

                                    <div class="form-group">
                                        <label>Height (meters)</label>
                                        <input
                                            type="number"
                                            v-model="form.height"
                                            class="form-control"
                                            placeholder="e.g. 5047"
                                            min="0"
                                        />
                                    </div>

                                    <div class="form-row">
                                        <div class="form-group col-md-6">
                                            <label>Latitude</label>
                                            <input
                                                type="number"
                                                v-model="form.latitude"
                                                class="form-control"
                                                step="any"
                                                placeholder="e.g. 42.6629"
                                            />
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label>Longitude</label>
                                            <input
                                                type="number"
                                                v-model="form.longitude"
                                                class="form-control"
                                                step="any"
                                                placeholder="e.g. 44.0942"
                                            />
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label>Region</label>
                                        <select v-model="form.region_id" class="form-control">
                                            <option :value="null">-- Select region --</option>
                                            <option
                                                v-for="region in regions"
                                                :key="region.id"
                                                :value="region.id"
                                            >
                                                {{ region.us_name }}
                                            </option>
                                        </select>
                                    </div>

                                    <div class="form-group">
                                        <label>Image</label>
                                        <div v-if="currentImage" class="mb-2">
                                            <img :src="'/storage/' + currentImage" alt="Current image" style="max-height: 100px;" class="img-thumbnail" />
                                            <p class="text-muted small mt-1">Current image. Upload a new one to replace it.</p>
                                        </div>
                                        <input
                                            type="file"
                                            class="form-control-file"
                                            accept="image/*"
                                            @change="handleImage"
                                        />
                                    </div>

                                    <div class="form-group">
                                        <div class="custom-control custom-switch">
                                            <input
                                                type="checkbox"
                                                class="custom-control-input"
                                                id="publishedSwitch"
                                                v-model="form.published"
                                            />
                                            <label class="custom-control-label" for="publishedSwitch">Published</label>
                                        </div>
                                    </div>

                                    <button type="submit" class="btn btn-primary" :disabled="submitting">
                                        <span v-if="submitting">Saving...</span>
                                        <span v-else>Save Changes</span>
                                    </button>
                                </form>
                            </template>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import breadcrumb from '../../items/BreadcrumbComponent.vue'

export default {
    name: 'SummitEditPage',
    components: { breadcrumb },
    data() {
        return {
            form: {
                title: '',
                description: '',
                height: null,
                latitude: null,
                longitude: null,
                region_id: null,
                published: false,
                image: null,
            },
            currentImage: null,
            regions: [],
            errors: {},
            submitting: false,
            pageLoading: true,
        }
    },
    mounted() {
        this.loadRegions()
        this.loadSummit()
    },
    methods: {
        loadRegions() {
            axios.get('get_summit_admin/get_regions')
                .then(response => {
                    this.regions = response.data
                })
                .catch(error => {
                    console.error('Error loading regions:', error)
                })
        },
        loadSummit() {
            const id = this.$route.params.id
            axios.get('get_summit_admin/index')
                .then(response => {
                    const summit = response.data.find(s => s.id == id)
                    if (summit) {
                        this.form.title = summit.title || ''
                        this.form.description = summit.description || ''
                        this.form.height = summit.height || null
                        this.form.latitude = summit.latitude || null
                        this.form.longitude = summit.longitude || null
                        this.form.region_id = summit.region_id || null
                        this.form.published = !!summit.published
                        this.currentImage = summit.image || null
                    }
                })
                .catch(error => {
                    console.error('Error loading summit:', error)
                })
                .finally(() => {
                    this.pageLoading = false
                })
        },
        handleImage(event) {
            this.form.image = event.target.files[0] || null
        },
        submitForm() {
            this.submitting = true
            this.errors = {}

            const id = this.$route.params.id
            const formData = new FormData()
            formData.append('title', this.form.title)
            if (this.form.description) formData.append('description', this.form.description)
            if (this.form.height !== null && this.form.height !== '') formData.append('height', this.form.height)
            if (this.form.latitude !== null && this.form.latitude !== '') formData.append('latitude', this.form.latitude)
            if (this.form.longitude !== null && this.form.longitude !== '') formData.append('longitude', this.form.longitude)
            if (this.form.region_id) formData.append('region_id', this.form.region_id)
            formData.append('published', this.form.published ? 1 : 0)
            if (this.form.image) formData.append('image', this.form.image)

            axios.post(`set_summit/update/${id}`, formData, {
                headers: { 'Content-Type': 'multipart/form-data' }
            })
                .then(() => {
                    this.$router.push('/summits')
                })
                .catch(error => {
                    if (error.response && error.response.data && error.response.data.errors) {
                        this.errors = error.response.data.errors
                    } else {
                        alert('Failed to save summit. Please try again.')
                    }
                })
                .finally(() => {
                    this.submitting = false
                })
        }
    }
}
</script>
