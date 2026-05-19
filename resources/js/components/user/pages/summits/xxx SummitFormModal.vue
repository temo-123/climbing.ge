<template>
    <stack-modal
        :show="visible"
        :title="summit_id ? 'Edit Summit' : 'Add Summit'"
        @close="close"
        :saveButton="{ visible: false }"
        :cancelButton="{ visible: false }"
    >
        <div>
            <div v-if="page_loading" class="text-center py-4">
                <div class="spinner-border" role="status"></div>
            </div>

            <template v-else>
                <div v-if="error" class="alert alert-danger">{{ error }}</div>

                <!-- EN / KA tabs -->
                <ul class="nav nav-tabs mb-3">
                    <li class="nav-item">
                        <a class="nav-link" :class="{ active: lang === 'en' }" href="#" @click.prevent="lang = 'en'">English</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" :class="{ active: lang === 'ka' }" href="#" @click.prevent="lang = 'ka'">ქართული</a>
                    </li>
                </ul>

                <div v-show="lang === 'en'">
                    <div class="form-group">
                        <label>Title (EN) <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" v-model="form.title" placeholder="Summit title (English)" />
                    </div>
                    <div class="form-group">
                        <label>Description (EN)</label>
                        <textarea class="form-control" rows="3" v-model="form.description" placeholder="Description (optional)"></textarea>
                    </div>
                </div>

                <div v-show="lang === 'ka'">
                    <div class="form-group">
                        <label>სახელი (KA)</label>
                        <input type="text" class="form-control" v-model="form.ka_title" placeholder="მწვერვალის სახელი (ქართულად)" />
                    </div>
                    <div class="form-group">
                        <label>აღწერა (KA)</label>
                        <textarea class="form-control" rows="3" v-model="form.ka_description" placeholder="აღწერა (არასავალდებულო)"></textarea>
                    </div>
                </div>

                <div class="form-group">
                    <label>Height (meters)</label>
                    <input type="number" class="form-control" v-model="form.height" min="0" placeholder="e.g. 5047" />
                </div>

                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label>Latitude</label>
                        <input type="number" class="form-control" v-model="form.latitude" step="any" placeholder="e.g. 42.6629" />
                    </div>
                    <div class="form-group col-md-6">
                        <label>Longitude</label>
                        <input type="number" class="form-control" v-model="form.longitude" step="any" placeholder="e.g. 44.0942" />
                    </div>
                </div>

                <div class="form-group">
                    <div class="custom-control custom-switch">
                        <input type="checkbox" class="custom-control-input" id="publishedSwitch" v-model="form.published" />
                        <label class="custom-control-label" for="publishedSwitch">Published</label>
                    </div>
                </div>
            </template>
        </div>

        <template #footer>
            <button type="button" class="btn btn-secondary" @click="close">Cancel</button>
            <button type="button" class="btn btn-primary" :disabled="saving" @click="save">
                <span v-if="saving"><i class="fa fa-spinner fa-spin"></i></span>
                <span v-else>{{ summit_id ? 'Save Changes' : 'Add Summit' }}</span>
            </button>
        </template>
    </stack-modal>
</template>

<script>
export default {
    name: 'SummitFormModal',
    data() {
        return {
            visible: false,
            saving: false,
            page_loading: false,
            error: null,
            lang: 'en',
            summit_id: null,
            form: {
                title: '',
                ka_title: '',
                description: '',
                ka_description: '',
                height: null,
                latitude: null,
                longitude: null,
                published: false,
            },
        }
    },
    methods: {
        show_modal(summit = null) {
            this.visible = true
            this.error = null
            this.lang = 'en'
            this.summit_id = summit ? summit.id : null
            this.form = {
                title:          summit?.title          || '',
                ka_title:       summit?.ka_title       || '',
                description:    summit?.description    || '',
                ka_description: summit?.ka_description || '',
                height:         summit?.height         || null,
                latitude:       summit?.latitude       || null,
                longitude:      summit?.longitude      || null,
                published:      summit?.published      ?? false,
            }
        },
        close() {
            this.visible = false
            this.summit_id = null
        },
        save() {
            if (!this.form.title.trim()) {
                this.error = 'English title is required.'
                return
            }
            this.error = null
            this.saving = true

            const payload = { ...this.form }
            const url = this.summit_id
                ? `set_summit/update/${this.summit_id}`
                : 'set_summit/store'

            axios.post(url, payload)
                .then(r => {
                    this.close()
                    this.$emit('saved', r.data)
                })
                .catch(err => {
                    const msgs = err.response?.data?.errors
                    this.error = msgs
                        ? Object.values(msgs).map(m => m[0]).join(' ')
                        : 'Failed to save summit.'
                })
                .finally(() => { this.saving = false })
        },
    }
}
</script>

