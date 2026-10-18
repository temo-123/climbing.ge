<template>
<StackModal
    v-model="is_show_modal"
    :title="$t('guide.mtp_ascent.create_title')"
    @close="close_modal"
    :saveButton="(success_message || captcha_error) ? { visible: false } : { visible: true, title: $t('guide.mtp_ascent.save_btn') }"
    @save="submit_ascent"
    :cancelButton="{
        title: $t('guide.route.back_to_modal'),
        btnClass: { 'btn btn-warning': true },
    }"
>
    <div class="model-body">
        <div class="container">

            <div class="row justify-content-center" v-show="is_loading">
                <div class="col-md-4 text-center py-3">
                    <div class="spinner-border text-primary" role="status"></div>
                    <p class="mt-2 text-muted">{{ $t('global.loading') }}</p>
                </div>
            </div>

            <div v-if="!is_loading && success_message" class="alert alert-success text-center mt-3">
                <i class="fa fa-check-circle fa-2x mb-2 d-block"></i>
                <strong>{{ success_message }}</strong>
            </div>

            <form v-else-if="!is_loading" @submit.prevent="submit_ascent">
                <div class="row">
                    <div class="form-group col-md-6">
                        <label>{{ $t('guide.mtp_ascent.date_label') }} <span class="text-danger">*</span></label>
                        <input type="date" class="form-control" v-model="data.ascent_date" :max="today" required />
                    </div>
                    <div class="form-group col-md-6">
                        <label>{{ $t('guide.mtp_ascent.time_label') }}</label>
                        <input type="time" class="form-control" v-model="data.ascent_time" />
                    </div>
                </div>

                <div class="form-group">
                    <label>{{ $t('guide.mtp_ascent.style_label') }}</label>
                    <select class="form-control" v-model="data.ascent_style">
                        <option :value="''">—</option>
                        <option v-for="style in styles" :key="style" :value="style">{{ $t('guide.mtp_ascent.style.' + style) }}</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>{{ $t('guide.mtp_ascent.comment_label') }}</label>
                    <textarea class="form-control" rows="3" v-model="data.comment"></textarea>
                </div>

                <div class="form-group">
                    <label>{{ $t('guide.mtp_ascent.photo_label') }}</label>
                    <input type="file" class="form-control" accept="image/*" @change="handle_photo" />
                    <div v-if="compressing_photo" class="text-muted small mt-2">{{ $t('common.processing_image') }}</div>
                </div>

                <div class="alert alert-danger mt-2" v-if="error_message">
                    <i class="fa fa-exclamation-circle"></i> {{ error_message }}
                </div>
                <div v-if="captcha_error" class="alert alert-warning mt-2">
                    <i class="fa fa-exclamation-triangle"></i>
                    reCAPTCHA failed to load. Please reload the page and try again.
                </div>
            </form>

        </div>
    </div>
</StackModal>
</template>

<script>
import { compressImage } from '../../../../../../../services/image/compressImage.js'

export default {
    emits: ['back_mtp_modal'],
    data() {
        return {
            is_show_modal: false,
            mtp_id: 0,
            styles: ['lead', 'second', 'alternate'],
            data: this.empty_data(),
            is_loading: false,
            compressing_photo: false,
            success_message: '',
            error_message: '',
            captcha_error: false,
        };
    },
    computed: {
        today() {
            return new Date().toISOString().slice(0, 10);
        },
    },
    mounted() {
        if (!window.grecaptcha) {
            const key = process.env.MIX_GOOGLE_CAPTCHA_V3_SITE_KEY;
            if (key) {
                const script = document.createElement('script');
                script.src = `https://www.google.com/recaptcha/api.js?render=${key}`;
                document.head.appendChild(script);
            }
        }
    },
    methods: {
        empty_data() {
            return { ascent_date: new Date().toISOString().slice(0, 10), ascent_time: '', ascent_style: '', comment: '', photo: null };
        },

        // Logged-in only, same as MTP reviews
        async show_mtp_ascent_modal(mtp_id) {
            try {
                const authRes = await axios.get('/auth_user/');
                if (!authRes.data || !authRes.data.id) {
                    this.$bus.$emit('open-login-modal', () => this.show_mtp_ascent_modal(mtp_id));
                    return;
                }
            } catch {
                this.$bus.$emit('open-login-modal', () => this.show_mtp_ascent_modal(mtp_id));
                return;
            }

            this.mtp_id = mtp_id;
            this.success_message = '';
            this.error_message = '';
            this.captcha_error = false;
            this.data = this.empty_data();
            this.is_show_modal = true;
        },

        close_modal() {
            this.is_show_modal = false;
            this.$emit('back_mtp_modal', this.mtp_id);
        },

        async handle_photo(event) {
            const file = event.target.files[0];
            if (!file) {
                this.data.photo = null;
                return;
            }
            this.compressing_photo = true;
            try {
                this.data.photo = await compressImage(file, { maxSizeMB: 3, maxWidthOrHeight: 1920 });
            } finally {
                this.compressing_photo = false;
            }
        },

        async get_recaptcha_token() {
            const key = process.env.MIX_GOOGLE_CAPTCHA_V3_SITE_KEY;
            if (!key || !window.grecaptcha) return null;
            try {
                await new Promise(resolve => window.grecaptcha.ready(resolve));
                return await window.grecaptcha.execute(key, { action: 'mtp_ascent' });
            } catch {
                return null;
            }
        },

        async submit_ascent() {
            if (this.success_message || this.compressing_photo) return;
            this.is_loading = true;
            this.error_message = '';

            const recaptcha_token = await this.get_recaptcha_token();
            if (!recaptcha_token) {
                this.captcha_error = true;
                this.is_loading = false;
                return;
            }

            const formData = new FormData();
            formData.append('ascent_date', this.data.ascent_date);
            if (this.data.ascent_time) formData.append('ascent_time', this.data.ascent_time);
            if (this.data.ascent_style) formData.append('ascent_style', this.data.ascent_style);
            if (this.data.comment) formData.append('comment', this.data.comment);
            if (this.data.photo) formData.append('photo', this.data.photo);
            formData.append('recaptcha_token', recaptcha_token);

            axios.post('/set_mtp_ascent/create_mtp_ascent/' + this.mtp_id, formData, {
                headers: { 'Content-Type': 'multipart/form-data' }
            })
                .then(() => {
                    this.success_message = this.$t('guide.mtp_ascent.success');
                })
                .catch((error) => {
                    const errors = error.response?.data?.errors;
                    this.error_message = (errors && Object.values(errors)[0]?.[0])
                        || error.response?.data?.message
                        || this.$t('guide.mtp_ascent.error');
                })
                .finally(() => {
                    this.is_loading = false;
                });
        },
    },
};
</script>
