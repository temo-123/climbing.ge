<template>
    <!-- Write a product review: star rating (required), text, "I own it" switch.
         Guests type name / surname / email; signed-in users see who they post as. -->
    <StackModal
        v-model="is_show_modal"
        :title="$t('shop.product.feedback.modal_title')"
        size="lg"
        :modal-class="{ [ModalClass]: true }"
        :saveButton="{ visible: false }"
        :cancelButton="{ visible: false }"
        @close="close_modal()"
    >
        <form @submit.prevent="add_feedback" id="feedback_form" class="fb-form" novalidate>
            <!-- rating -->
            <div class="fb-rate" :class="{ 'is-invalid': errors.stars }">
                <span class="fb-label">{{ $t('shop.product.feedback.rate_product') }} *</span>
                <div class="fb-stars" role="radiogroup" :aria-label="$t('shop.product.feedback.rate_product')" @mouseleave="hover = 0">
                    <button
                        v-for="n in 5"
                        :key="n"
                        type="button"
                        role="radio"
                        class="fb-star"
                        :class="{ 'is-on': n <= (hover || data.stars) }"
                        :aria-checked="data.stars === n"
                        :aria-label="n + ' / 5 — ' + star_label(n)"
                        @mouseenter="hover = n"
                        @focus="hover = n"
                        @blur="hover = 0"
                        @click="set_stars(n)"
                    ><i class="fa fa-star" aria-hidden="true"></i></button>
                </div>
                <span class="fb-rate__word">{{ (hover || data.stars) ? star_label(hover || data.stars) : $t('shop.product.feedback.rate_hint') }}</span>
                <div v-if="errors.stars" class="text-danger small w-100">{{ errors.stars }}</div>
            </div>

            <!-- who -->
            <div v-if="is_logged" class="fb-who">
                <i class="fa fa-user-circle" aria-hidden="true"></i>
                <span class="text-muted">{{ $t('shop.product.feedback.posting_as') }}</span>
                <strong>{{ user.name }} {{ user.surname }}</strong>
            </div>
            <div v-else class="row g-2 mb-3">
                <div class="col-sm-6">
                    <label class="form-label small" for="fb_name">{{ $t('common.name') }} *</label>
                    <input id="fb_name" type="text" v-model.trim="data.name" maxlength="25" autocomplete="given-name" class="form-control" :class="{ 'is-invalid': errors.name }">
                    <div class="invalid-feedback">{{ errors.name }}</div>
                </div>
                <div class="col-sm-6">
                    <label class="form-label small" for="fb_surname">{{ $t('shop.product.feedback.surname') }} *</label>
                    <input id="fb_surname" type="text" v-model.trim="data.surname" maxlength="55" autocomplete="family-name" class="form-control" :class="{ 'is-invalid': errors.surname }">
                    <div class="invalid-feedback">{{ errors.surname }}</div>
                </div>
                <div class="col-12">
                    <label class="form-label small" for="fb_email">{{ $t('common.email') }} *</label>
                    <input id="fb_email" type="email" v-model.trim="data.email" autocomplete="email" class="form-control" :class="{ 'is-invalid': errors.email }">
                    <div class="invalid-feedback">{{ errors.email }}</div>
                </div>
            </div>
            <div v-if="is_logged && (errors.name || errors.surname || errors.email)" class="alert alert-warning small py-2">
                {{ $t('shop.product.feedback.autofill_warning') }}
            </div>

            <!-- text -->
            <label class="form-label fb-label" for="fb_text">{{ $t('shop.product.feedback.your_feedback') }} *</label>
            <textarea
                id="fb_text"
                rows="5"
                v-model="data.text"
                :maxlength="MAX"
                :placeholder="$t('shop.product.feedback.feedback_placeholder_short')"
                class="form-control"
                :class="{ 'is-invalid': errors.text }"
            ></textarea>
            <div class="invalid-feedback">{{ errors.text }}</div>
            <div class="fb-counter" :class="{ 'is-near': data.text.length > MAX - 50 }">{{ data.text.length }} / {{ MAX }}</div>

            <!-- own it -->
            <div class="form-check form-switch fb-own">
                <input class="form-check-input" type="checkbox" role="switch" id="fb_have" v-model="data.have_product">
                <label class="form-check-label" for="fb_have">{{ $t('shop.product.feedback.have_product_switch') }}</label>
            </div>

            <div v-if="notice" class="alert small py-2 mt-3 mb-0" :class="'alert-' + notice.type" role="status">{{ notice.text }}</div>

            <div class="fb-actions">
                <button type="button" class="btn btn-light" @click="close_modal()">{{ $t('common.close') }}</button>
                <button type="submit" class="btn btn-primary" :disabled="is_loading">
                    <span v-if="is_loading" class="spinner-border spinner-border-sm me-1" role="status" aria-hidden="true"></span>
                    <i v-else class="fa fa-paper-plane me-1" aria-hidden="true"></i>
                    {{ $t('shop.product.feedback.submit') }}
                </button>
            </div>
        </form>
    </StackModal>
</template>

<script>
import { get_auth_user } from '../../../../../services/authUser.js'

const EMPTY = () => ({ stars: 0, text: '', have_product: false, name: '', surname: '', email: '' })

export default {
    emits: ['restart'],
    data() {
        return {
            MAX: 500,
            is_show_modal: false,
            product_id: 0,
            ModalClass: '',
            data: EMPTY(),
            hover: 0,
            is_loading: false,
            notice: null,
            user: null,
            errors: {},
        }
    },
    computed: {
        is_logged() {
            return !!(this.user && this.user.id)
        },
    },
    methods: {
        show_modal(id) {
            this.clear_data()
            this.product_id = id
            this.is_show_modal = true
            this.get_user_info()
        },
        close_modal() {
            this.is_show_modal = false
            this.is_loading = false
            this.clear_data()
        },
        clear_data() {
            this.data = EMPTY()
            this.hover = 0
            this.errors = {}
            this.notice = null
        },
        set_stars(n) {
            this.data.stars = n
            if (this.errors.stars) this.errors = { ...this.errors, stars: '' }
        },
        star_label(n) {
            return this.$t('shop.product.feedback.stars_' + n)
        },
        get_user_info() {
            this.user = null
            if (!localStorage.getItem('auth_token')) return
            get_auth_user()
                .then(response => {
                    const u = response.data
                    if (!u || !u.id) return
                    this.user = u
                    this.data.name = u.name || ''
                    this.data.surname = u.surname || ''
                    this.data.email = u.email || ''
                })
                .catch(() => {})
        },
        validate_local() {
            const e = {}
            const req = this.$t('guide.comments.required')
            if (!this.data.stars) e.stars = this.$t('shop.product.feedback.stars_required')
            if (!this.is_logged) {
                if (!this.data.name) e.name = this.$t('shop.product.feedback.name_required')
                if (!this.data.surname) e.surname = this.$t('shop.product.feedback.surname_required')
                if (!/^\S+@\S+\.\S+$/.test(this.data.email)) e.email = this.$t('guide.comments.email_invalid')
            }
            if (!this.data.text.trim()) e.text = this.$t('shop.product.feedback.feedback_required') || req
            this.errors = e
            return !Object.keys(e).length
        },
        async get_recaptcha_token(action = 'feedback') {
            const key = process.env.MIX_GOOGLE_CAPTCHA_V3_SITE_KEY
            if (!key) return 'none'
            if (!window.grecaptcha) {
                await new Promise(resolve => {
                    const s = document.createElement('script')
                    s.src = `https://www.google.com/recaptcha/api.js?render=${key}`
                    s.onload = resolve
                    s.onerror = resolve
                    document.head.appendChild(s)
                })
            }
            if (!window.grecaptcha) return null
            try {
                const ready = new Promise(resolve => window.grecaptcha.ready(resolve))
                const timeout = new Promise((_, reject) => setTimeout(() => reject(new Error('timeout')), 8000))
                await Promise.race([ready, timeout])
                return await window.grecaptcha.execute(key, { action })
            } catch { return null }
        },
        async add_feedback() {
            if (this.is_loading) return
            this.notice = null
            if (!this.validate_local()) return

            this.is_loading = true
            const recaptcha_token = await this.get_recaptcha_token('feedback')
            if (!recaptcha_token) {
                this.is_loading = false
                this.notice = { type: 'warning', text: this.$t('shop.product.feedback.recaptcha_error') }
                return
            }

            axios
            .post('/set_product_feedback_by_gest/create_feedback/' + this.product_id, {
                data: { ...this.data, text: this.data.text.trim(), have_product: this.data.have_product ? 1 : 0, product_id: this.product_id },
                recaptcha_token: recaptcha_token === 'none' ? null : recaptcha_token,
            })
            .then(response => {
                const msg = typeof response.data === 'string' ? response.data : (response.data && response.data.message)
                // "You already have feedback for this product!" comes back as a plain 200 string
                if (typeof response.data === 'string' && /already have feedback/i.test(response.data)) {
                    this.notice = { type: 'warning', text: this.$t('shop.product.feedback.already') }
                    return
                }
                this.$emit('restart')
                this.$bus.$emit('toast', { type: 'success', message: this.is_logged ? this.$t('shop.product.feedback.thanks') : (msg || this.$t('shop.product.feedback.thanks')) })
                this.close_modal()
            })
            .catch(error => {
                const res = error.response
                if (res && res.status === 422) {
                    if (res.data && String(res.data.message || '').toLowerCase().includes('recaptcha')) {
                        this.notice = { type: 'warning', text: this.$t('shop.product.feedback.recaptcha_error') }
                    } else {
                        // backend keys are "data.name" etc.
                        const e = {}
                        Object.entries((res.data && res.data.errors) || {}).forEach(([k, v]) => { e[k.replace(/^data\./, '')] = Array.isArray(v) ? v[0] : v })
                        this.errors = e
                    }
                } else {
                    this.notice = { type: 'danger', text: this.$t('guide.comments.send_error') }
                }
            })
            .finally(() => { this.is_loading = false })
        },
    }
}
</script>

<style scoped>
    .fb-label { font-weight: 700; color: var(--cg-ink); }
    .fb-rate {
        display: flex;
        align-items: center;
        flex-wrap: wrap;
        gap: .4rem .9rem;
        margin-bottom: 1rem;
        padding: .8rem 1rem;
        border: 1px solid var(--cg-line);
        border-radius: var(--cg-r-lg);
        background: var(--cg-chalk);
    }
    .fb-rate.is-invalid { border-color: #dc3545; }
    .fb-stars { display: inline-flex; gap: .15rem; }
    .fb-star {
        padding: .1rem .15rem;
        border: 0;
        background: none;
        color: var(--cg-line);
        font-size: 1.9rem;
        line-height: 1;
        cursor: pointer;
        transition: transform .15s ease, color .15s ease;
    }
    .fb-star.is-on { color: var(--cg-hold); }
    .fb-star:hover { transform: scale(1.12); }
    .fb-star:focus-visible { outline: 2px solid var(--cg-accent); border-radius: .3rem; }
    @media (prefers-reduced-motion: reduce) { .fb-star { transition: none; } .fb-star:hover { transform: none; } }
    .fb-rate__word { font-weight: 700; color: var(--cg-stone); }

    .fb-who {
        display: flex;
        align-items: center;
        gap: .4rem;
        margin-bottom: .9rem;
        font-size: .9rem;
    }
    .fb-who .fa { color: var(--cg-accent); font-size: 1.2rem; }
    .fb-form textarea { resize: vertical; }
    .fb-counter { text-align: right; font-size: .8rem; color: var(--cg-muted); margin-top: .25rem; font-variant-numeric: tabular-nums; }
    .fb-counter.is-near { color: var(--cg-rope); font-weight: 700; }
    .fb-own { margin-top: .5rem; }
    .fb-actions {
        display: flex;
        justify-content: flex-end;
        gap: .5rem;
        margin-top: 1.25rem;
        padding-top: 1rem;
        border-top: 1px solid var(--cg-line);
    }
    @media (max-width: 575.98px) {
        .fb-star { font-size: 2.2rem; }
        .fb-actions .btn { flex: 1; }
    }
</style>
