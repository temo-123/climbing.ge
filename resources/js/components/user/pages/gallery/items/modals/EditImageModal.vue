<template>
    <StackModal
        :show="is_edit_image_modal"
        :title="$t('head_slider.edit_image')"
        @close="close_modal()"
        @save="$refs.edit_form.requestSubmit()"
        :saveButton="{ visible: true, title: $t('common.save') }"
        :cancelButton="{ visible: false }"
    >
        <div>
            <div v-if="is_loading" class="text-center py-3">
                <i class="fa fa-spinner fa-spin fa-2x"></i>
            </div>

            <form v-else ref="edit_form" @submit.prevent="edit_image">
                <div class="container">

                    <!-- Error messages -->
                    <div v-if="errors.length > 0" class="alert alert-danger">
                        <div v-for="(msg, i) in errors" :key="i">{{ msg }}</div>
                    </div>

                    <!-- Current image preview -->
                    <div class="form-group row" v-if="editing_data.image">
                        <div class="col-md-12">
                            <img
                                :src="image_path_prop + editing_data.image"
                                :alt="editing_data.title"
                                style="max-height:200px; max-width:100%; border-radius:4px;"
                            >
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-md-3 col-form-label">{{ $t('common.title') }} *</label>
                        <div class="col-md-9">
                            <input
                                type="text"
                                class="form-control"
                                :placeholder="$t('common.title')"
                                v-model="editing_data.title"
                                required
                            >
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-md-3 col-form-label">{{ $t('head_slider.text') }}</label>
                        <div class="col-md-9">
                            <textarea
                                rows="4"
                                class="form-control"
                                v-model="editing_data.text"
                            ></textarea>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-md-3 col-form-label">{{ $t('head_slider.text_position') }}</label>
                        <div class="col-md-9">
                            <select class="form-control" v-model="editing_data.text_position">
                                <option value="center">{{ $t('head_slider.center') }}</option>
                                <option value="left-top">{{ $t('head_slider.top_left') }}</option>
                                <option value="right-top">{{ $t('head_slider.top_right') }}</option>
                                <option value="left-bottom">{{ $t('head_slider.bottom_left') }}</option>
                                <option value="right-bottom">{{ $t('head_slider.bottom_right') }}</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-md-3 col-form-label">{{ $t('head_slider.article_link') }}</label>
                        <div class="col-md-9">
                            <input
                                type="text"
                                class="form-control"
                                :placeholder="$t('head_slider.article_link')"
                                v-model="editing_data.link"
                            >
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-md-3 col-form-label">{{ $t('head_slider.public') }} *</label>
                        <div class="col-md-9">
                            <select class="form-control" v-model="editing_data.published" required>
                                <option value="0">{{ $t('head_slider.not_public') }}</option>
                                <option value="1">{{ $t('head_slider.public') }}</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-md-3 col-form-label">{{ $t('admin.gallery.replace_image_label') }}</label>
                        <div class="col-md-9">
                            <input
                                type="file"
                                accept="image/*,.webp"
                                @change="on_image_change"
                            >
                            <small class="text-muted">{{ $t('admin.gallery.replace_image_hint') }}</small>
                        </div>
                    </div>

                </div>
            </form>
        </div>
    </StackModal>
</template>

<script>
export default {
    props: ['image_path_prop'],
    data() {
        return {
            is_edit_image_modal: false,
            is_loading: false,
            errors: [],
            image_file: null,
            editing_data: {},
        }
    },
    methods: {
        show_modal(image_id) {
            this.reset()
            this.is_edit_image_modal = true
            this.load_slide(image_id)
        },
        close_modal() {
            this.is_edit_image_modal = false
            this.reset()
        },
        reset() {
            this.errors = []
            this.image_file = null
            this.editing_data = {}
        },
        load_slide(id) {
            this.is_loading = true
            axios.get('set_head_slider/get_editing_slide/' + id)
                .then(response => { this.editing_data = response.data })
                .catch(() => { this.errors = [this.$t('admin.gallery.failed_load_slide')] })
                .finally(() => { this.is_loading = false })
        },
        on_image_change(e) {
            this.image_file = e.target.files[0] || null
        },
        edit_image() {
            this.errors = []
            this.is_loading = true

            const formData = new FormData()
            if (this.image_file instanceof File) {
                formData.append('image', this.image_file)
            }
            formData.append('data', JSON.stringify(this.editing_data))

            axios.post('set_head_slider/edit_slide/' + this.editing_data.id, formData)
                .then(() => {
                    this.close_modal()
                    this.$emit('update')
                })
                .catch(error => {
                    if (error.response && error.response.status === 422) {
                        this.errors = this.extract_errors(error.response.data)
                    } else {
                        this.errors = [this.$t('guide.donation.error')]
                    }
                })
                .finally(() => { this.is_loading = false })
        },
        extract_errors(data) {
            const msgs = []
            if (Array.isArray(data)) {
                data.forEach(item => {
                    if (typeof item === 'object' && item !== null) {
                        Object.values(item).forEach(arr => {
                            if (Array.isArray(arr)) arr.forEach(m => msgs.push(m))
                            else msgs.push(String(arr))
                        })
                    }
                })
            } else if (typeof data === 'object') {
                Object.values(data).forEach(arr => {
                    if (Array.isArray(arr)) arr.forEach(m => msgs.push(m))
                    else msgs.push(String(arr))
                })
            }
            return msgs
        },
    }
}
</script>
