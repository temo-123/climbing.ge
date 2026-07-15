<template>
    <StackModal
        :show="is_add_image_modal"
        :title="$t('head_slider.add_image')"
        @close="close_modal()"
        @save="$refs.add_form.requestSubmit()"
        :saveButton="{ visible: true, title: $t('common.save') }"
        :cancelButton="{ visible: false }"
    >
        <div>
            <div v-if="is_loading" class="text-center py-3">
                <i class="fa fa-spinner fa-spin fa-2x"></i>
            </div>

            <form v-else ref="add_form" @submit.prevent="add_image">
                <div class="container">

                    <!-- Error messages -->
                    <div v-if="errors.length > 0" class="alert alert-danger">
                        <div v-for="(msg, i) in errors" :key="i">{{ msg }}</div>
                    </div>

                    <div class="form-group row">
                        <label class="col-md-3 col-form-label">{{ $t('common.title') }} *</label>
                        <div class="col-md-9">
                            <input
                                type="text"
                                class="form-control"
                                :placeholder="$t('common.title')"
                                v-model="form_data.title"
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
                                v-model="form_data.text"
                            ></textarea>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-md-3 col-form-label">{{ $t('head_slider.text_position') }}</label>
                        <div class="col-md-9">
                            <select class="form-control" v-model="form_data.text_position">
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
                                v-model="form_data.link"
                            >
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-md-3 col-form-label">{{ $t('head_slider.public') }} *</label>
                        <div class="col-md-9">
                            <select class="form-control" v-model="form_data.published" required>
                                <option value="0">{{ $t('head_slider.not_public') }}</option>
                                <option value="1">{{ $t('head_slider.public') }}</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-md-3 col-form-label">{{ $t('admin.gallery.image_required_label') }}</label>
                        <div class="col-md-9">
                            <single_image_add
                                :key="image_reset_id"
                                :title_prop="''"
                                :crop_ratio_prop="{ width: 16, height: 9 }"
                                @update_single_image="image_file = $event"
                                @processing="image_processing = $event"
                            />
                        </div>
                    </div>

                </div>
            </form>
        </div>
    </StackModal>
</template>

<script>
import single_image_add from '../../../../items/single_image/singleImageAddComponent.vue'

export default {
    components: { single_image_add },
    props: ['category_prop'],
    data() {
        return {
            is_add_image_modal: false,
            is_loading: false,
            errors: [],
            image_file: null,
            image_processing: false,
            image_reset_id: 0,
            form_data: {
                published: 0,
                category: '',
                title: '',
                text: '',
                link: '',
                text_position: 'center',
            }
        }
    },
    methods: {
        show_modal() {
            this.reset()
            this.is_add_image_modal = true
        },
        close_modal() {
            this.is_add_image_modal = false
            this.reset()
        },
        reset() {
            this.errors = []
            this.image_file = null
            this.image_processing = false
            this.image_reset_id++
            this.form_data = {
                published: 0,
                category: this.category_prop,
                title: '',
                text: '',
                link: '',
                text_position: 'center',
            }
        },
        add_image() {
            this.errors = []

            if (this.image_processing) {
                this.errors = [this.$t('admin.gallery.image_still_processing')]
                return
            }

            if (!this.image_file) {
                this.errors = [this.$t('admin.gallery.please_select_image')]
                return
            }

            this.is_loading = true

            const formData = new FormData()
            formData.append('image', this.image_file, this.image_file.name || 'image.jpg')
            formData.append('data', JSON.stringify(this.form_data))

            axios.post('set_head_slider/add_slide/', formData)
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
