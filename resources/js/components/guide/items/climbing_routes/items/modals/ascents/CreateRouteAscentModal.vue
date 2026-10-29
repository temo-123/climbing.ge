<template>
<StackModal
    v-model="is_show_modal"
    :title="$t('guide.route_ascent.create_title')"
    @close="close_modal"
    :saveButton="(success_message || captcha_error) ? { visible: false } : { visible: true, title: $t('guide.route_ascent.save_btn') }"
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
                <div v-if="data.ascent_style != 'toprope'" class="mt-3">
                    <button type="button" class="btn btn-hold btn-sm" :disabled="sharing" @click="share_card">
                        <i class="fa" :class="sharing ? 'fa-spinner fa-spin' : 'fa-instagram'" aria-hidden="true"></i> {{ $t('guide.route_ascent.share_card') }}
                    </button>
                </div>
            </div>

            <form v-else-if="!is_loading" @submit.prevent="submit_ascent">
                <p v-if="route_name" class="text-muted">
                    <strong>{{ route_name }}</strong><span v-if="route_grade"> — {{ route_grade }}</span>
                </p>

                <div class="form-group">
                    <label>{{ $t('guide.route_ascent.style_label') }} <span class="text-danger">*</span></label>
                    <div class="ascent_styles">
                        <button
                            v-for="style in styles"
                            :key="style"
                            type="button"
                            class="btn"
                            :class="data.ascent_style == style ? 'btn-primary' : 'btn-default'"
                            :title="$t('guide.route_ascent.style_hint.' + style)"
                            @click="data.ascent_style = style"
                        >
                            {{ $t('guide.route_ascent.style.' + style) }}
                        </button>
                    </div>
                    <small class="text-muted" v-if="data.ascent_style">{{ $t('guide.route_ascent.style_hint.' + data.ascent_style) }}</small>
                </div>

                <div class="row">
                    <div class="form-group col-md-6">
                        <label>{{ $t('guide.route_ascent.date_label') }} <span class="text-danger">*</span></label>
                        <input type="date" class="form-control" v-model="data.ascent_date" :max="today" required />
                    </div>
                    <div class="form-group col-md-6" v-if="!first_try_style">
                        <label>{{ $t('guide.route_ascent.attempts_label') }}</label>
                        <input type="number" min="1" max="999" class="form-control" v-model="data.attempts" />
                    </div>
                </div>

                <div class="form-group">
                    <label>{{ $t('guide.route_ascent.suggested_grade_label') }}</label>
                    <select class="form-control" v-model="data.suggested_grade">
                        <option :value="''">{{ $t('guide.route_ascent.grade_agree') }}</option>
                        <option v-for="grade in grade_options" :key="grade" :value="grade">{{ grade }}</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>{{ $t('guide.route_ascent.comment_label') }}</label>
                    <textarea class="form-control" rows="3" v-model="data.comment"></textarea>
                </div>

                <div class="form-group">
                    <label>{{ $t('guide.route_ascent.photo_label') }}</label>
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

const SPORT_GRADES = [
    '4', '5a', '5a+', '5b', '5b+', '5c', '5c+',
    '6a', '6a+', '6b', '6b+', '6c', '6c+',
    '7a', '7a+', '7b', '7b+', '7c', '7c+',
    '8a', '8a+', '8b', '8b+', '8c', '8c+',
    '9a', '9a+', '9b', '9b+', '9c',
];
const BOULDER_GRADES = Array.from({ length: 18 }, (_, i) => 'V' + i);

import { share_send_card } from '../../../../../../../services/fun/shareCard.js';

export default {
    emits: ['back_route_modal'],
    data() {
        return {
            is_show_modal: false,
            route_id: 0,
            route_name: '',
            route_grade: '',
            route_category: '',
            styles: ['onsight', 'flash', 'redpoint', 'toprope'],
            data: this.empty_data(),
            is_loading: false,
            compressing_photo: false,
            success_message: '',
            error_message: '',
            captcha_error: false,
            sharing: false,
        };
    },
    computed: {
        today() {
            return new Date().toISOString().slice(0, 10);
        },
        first_try_style() {
            return this.data.ascent_style == 'onsight' || this.data.ascent_style == 'flash';
        },
        is_boulder() {
            return this.route_category == 'bouldering' || /^V/i.test(this.route_grade || '');
        },
        grade_options() {
            return this.is_boulder ? BOULDER_GRADES : SPORT_GRADES;
        },
    },
    watch: {
        // Boulders have no onsight in practice — flash / send (redpoint) only
        is_boulder(value) {
            this.styles = value ? ['flash', 'redpoint', 'toprope'] : ['onsight', 'flash', 'redpoint', 'toprope'];
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
        share_card() {
            this.sharing = true;
            const area = document.querySelector('h1');
            const date = new Date(this.data.ascent_date + 'T12:00:00');
            share_send_card({
                route: this.route_name,
                grade: this.route_grade,
                style: this.$t('guide.route_ascent.style.' + this.data.ascent_style),
                date: isNaN(date) ? '' : this.$formatDate(date),
                area: area ? area.textContent.replace(/\s+/g, ' ').trim() : '',
                labels: { sent: this.$t('guide.route_ascent.share_sent') },
            }).finally(() => { this.sharing = false; });
        },
        empty_data() {
            return { ascent_date: new Date().toISOString().slice(0, 10), ascent_style: 'redpoint', attempts: '', suggested_grade: '', comment: '', photo: null };
        },

        // Logged-in only, same as route reviews
        async show_route_ascent_modal(route_id, route = {}) {
            try {
                const authRes = await axios.get('/auth_user/');
                if (!authRes.data || !authRes.data.id) {
                    this.$bus.$emit('open-login-modal', () => this.show_route_ascent_modal(route_id, route));
                    return;
                }
            } catch {
                this.$bus.$emit('open-login-modal', () => this.show_route_ascent_modal(route_id, route));
                return;
            }

            this.route_id = route_id;
            this.route_name = route.name || '';
            this.route_grade = route.grade || '';
            this.route_category = route.category || '';
            this.success_message = '';
            this.error_message = '';
            this.captcha_error = false;
            this.data = this.empty_data();
            this.is_show_modal = true;
        },

        close_modal() {
            this.is_show_modal = false;
            this.$emit('back_route_modal', this.route_id);
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
                return await window.grecaptcha.execute(key, { action: 'route_ascent' });
            } catch {
                return null;
            }
        },

        async submit_ascent() {
            if (this.success_message || this.compressing_photo) return;
            if (!this.data.ascent_style) {
                this.error_message = this.$t('guide.route_ascent.style_required');
                return;
            }
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
            formData.append('ascent_style', this.data.ascent_style);
            if (this.data.attempts && !this.first_try_style) formData.append('attempts', this.data.attempts);
            if (this.data.suggested_grade) formData.append('suggested_grade', this.data.suggested_grade);
            if (this.data.comment) formData.append('comment', this.data.comment);
            if (this.data.photo) formData.append('photo', this.data.photo);
            formData.append('recaptcha_token', recaptcha_token);

            axios.post('/set_route_ascent/create_route_ascent/' + this.route_id, formData, {
                headers: { 'Content-Type': 'multipart/form-data' }
            })
                .then(() => {
                    this.success_message = this.$t('guide.route_ascent.success');
                    // a small "tick" buzz on phones that support it
                    if (navigator.vibrate && !window.cgReducedMotion()) navigator.vibrate([20, 40, 30]);
                })
                .catch((error) => {
                    const errors = error.response?.data?.errors;
                    this.error_message = (errors && Object.values(errors)[0]?.[0])
                        || error.response?.data?.message
                        || this.$t('guide.route_ascent.error');
                })
                .finally(() => {
                    this.is_loading = false;
                });
        },
    },
};
</script>

<style scoped>
.ascent_styles {
    display: flex;
    flex-wrap: wrap;
    gap: 6px;
    margin-bottom: 4px;
}
</style>
