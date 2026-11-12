<template>
    <!-- Article comments (guide: outdoor / article / mountaineering / business pages).
         Form on top (signed-in: "commenting as …"; guest: name / surname / email + a login nudge),
         then the thread. Replies are one level deep. Parents call update(id) when the article changes. -->
    <section class="cmt" id="comments">
        <div class="cmt-head">
            <h2 class="section-title mb-0">
                {{ $t('guide.article.title.comments') }}
                <span v-if="total" class="cmt-head__count">{{ total }}</span>
            </h2>
            <button type="button" class="btn btn-sm btn-light rounded-circle cmt-head__refresh" :class="{ 'is-spinning': is_refresh }" @click="get_comments" :title="$t('common.refresh')" :aria-label="$t('common.refresh')">
                <i class="fa fa-refresh" aria-hidden="true"></i>
            </button>
        </div>

        <form class="cmt-form" ref="form" @submit.prevent="submit" novalidate>
            <!-- who is writing -->
            <div v-if="is_logged" class="cmt-who">
                <img v-if="avatar(user)" :src="avatar(user)" class="cmt-avatar cmt-avatar--sm" alt="" loading="lazy" decoding="async">
                <span v-else class="cmt-avatar cmt-avatar--sm cmt-avatar--empty">{{ initials(user.name, user.surname) }}</span>
                <span class="small text-muted">{{ $t('guide.comments.commenting_as') }}</span>
                <strong class="small">{{ user.name }} {{ user.surname }}</strong>
            </div>
            <template v-else>
                <div class="cmt-guest">
                    <span><i class="fa fa-user-circle-o" aria-hidden="true"></i> {{ $t('guide.comments.guest_hint') }}</span>
                    <button type="button" class="btn btn-sm btn-outline-primary" @click="$bus.$emit('open-login-modal', get_user_info)">
                        <i class="fa fa-sign-in" aria-hidden="true"></i> {{ $t('common.login') }}
                    </button>
                </div>
                <div class="row g-2 mb-2">
                    <div class="col-sm-6">
                        <label class="form-label small" for="cmt_name">{{ $t('common.name') }} *</label>
                        <input id="cmt_name" type="text" v-model.trim="data.name" maxlength="25" autocomplete="given-name" class="form-control" :class="{ 'is-invalid': err('name') }">
                        <div class="invalid-feedback">{{ err('name') }}</div>
                    </div>
                    <div class="col-sm-6">
                        <label class="form-label small" for="cmt_surname">{{ $t('common.surname') }} *</label>
                        <input id="cmt_surname" type="text" v-model.trim="data.surname" maxlength="55" autocomplete="family-name" class="form-control" :class="{ 'is-invalid': err('surname') }">
                        <div class="invalid-feedback">{{ err('surname') }}</div>
                    </div>
                    <div class="col-12">
                        <label class="form-label small" for="cmt_email">{{ $t('common.email') }} *</label>
                        <input id="cmt_email" type="email" v-model.trim="data.email" autocomplete="email" class="form-control" :class="{ 'is-invalid': err('email') }">
                        <div class="invalid-feedback">{{ err('email') }}</div>
                        <div class="form-text">{{ $t('guide.comments.email_hint') }}</div>
                    </div>
                </div>
            </template>

            <!-- signed in, but the stored profile fails validation (e.g. empty surname) -->
            <div v-if="is_logged && (err('name') || err('surname') || err('email'))" class="alert alert-warning small py-2">
                {{ $t('guide.comments.profile_incomplete') }}
                <a :href="options_url" class="alert-link">{{ $t('guide.comments.open_profile') }}</a>
            </div>

            <!-- replying to -->
            <div v-if="reply_to" class="cmt-reply-chip">
                <i class="fa fa-reply" aria-hidden="true"></i>
                <span>{{ $t('guide.comments.replying_to', { name: reply_to.name }) }}</span>
                <button type="button" class="btn-close btn-sm ms-auto" @click="disable_answer()" :aria-label="$t('guide.comments.cancel_reply')" :title="$t('guide.comments.cancel_reply')"></button>
            </div>

            <div class="cmt-text">
                <label class="visually-hidden" for="cmt_text">{{ $t('guide.comments.text_label') }}</label>
                <textarea
                    id="cmt_text"
                    ref="text"
                    rows="4"
                    v-model="data.text"
                    :maxlength="MAX"
                    :placeholder="reply_to ? $t('guide.comments.reply_placeholder') : $t('guide.comments.text_placeholder')"
                    class="form-control"
                    :class="{ 'is-invalid': err('text') }"
                    @keydown.ctrl.enter.prevent="submit"
                    @keydown.meta.enter.prevent="submit"
                ></textarea>
                <div class="invalid-feedback">{{ err('text') }}</div>
            </div>

            <div class="cmt-actions">
                <span class="cmt-counter" :class="{ 'is-near': data.text.length > MAX - 50 }">{{ data.text.length }} / {{ MAX }}</span>
                <span class="small text-muted d-none d-md-inline">{{ $t('guide.comments.rules') }}</span>
                <button type="submit" class="btn btn-primary ms-auto" :disabled="sending || !data.text.trim()">
                    <span v-if="sending" class="spinner-border spinner-border-sm me-1" role="status" aria-hidden="true"></span>
                    <i v-else class="fa fa-paper-plane me-1" aria-hidden="true"></i>
                    {{ reply_to ? $t('guide.comments.send_reply') : $t('guide.comments.send') }}
                </button>
            </div>

            <div v-if="notice" class="alert small py-2 mt-2 mb-0" :class="'alert-' + notice.type" role="status">{{ notice.text }}</div>
        </form>

        <!-- thread -->
        <p v-if="loaded && !comments.length" class="cg-empty cmt-empty">
            <i class="fa fa-comments-o" aria-hidden="true"></i>
            {{ $t('guide.comments.empty') }}
        </p>

        <ul class="cmt-list">
            <li v-for="comment in comments" :key="comment.comment.id" class="cmt-item">
                <div class="cmt-row">
                    <button type="button" class="cmt-avatar-btn" @click="handleUserImageClick(comment.user)" :aria-label="comment.comment.name">
                        <img v-if="avatar(comment.user)" :src="avatar(comment.user)" class="cmt-avatar" alt="" loading="lazy" decoding="async">
                        <span v-else class="cmt-avatar cmt-avatar--empty">{{ initials(comment.comment.name, comment.comment.surname) }}</span>
                    </button>
                    <div class="cmt-body">
                        <div class="cmt-meta">
                            <router-link v-if="comment.user && comment.user.id" :to="{ name: 'climberProfile', params: { id: comment.user.id } }" class="cmt-name">{{ comment.comment.name }} {{ comment.comment.surname }}</router-link>
                            <span v-else class="cmt-name">{{ comment.comment.name }} {{ comment.comment.surname }}</span>
                            <span v-if="!comment.user" class="badge text-bg-light">{{ $t('guide.comments.guest_badge') }}</span>
                            <time v-if="comment.comment.created_at" class="cmt-date" :datetime="comment.comment.created_at">{{ format_date(comment.comment.created_at) }}</time>
                        </div>
                        <p class="cmt-text-body">{{ comment.comment.text }}</p>
                        <div class="cmt-row-actions">
                            <template v-if="is_logged">
                                <button type="button" class="cmt-link" @click="crete_comment_answer(comment.comment)">
                                    <i class="fa fa-reply" aria-hidden="true"></i> {{ $t('guide.comments.reply') }}
                                </button>
                                <button v-if="is_mine(comment.user)" type="button" class="cmt-link cmt-link--danger" @click="confirm_delete(comment.comment.id)">
                                    <i class="fa fa-trash" aria-hidden="true"></i> {{ $t('common.delete') }}
                                </button>
                                <button v-else type="button" class="cmt-link" @click="show_complaint_modal(comment.comment.id)">
                                    <i class="fa fa-flag-o" aria-hidden="true"></i> {{ $t('guide.comments.report') }}
                                </button>
                            </template>
                        </div>
                    </div>
                </div>

                <ul v-if="comment.answers.length" class="cmt-answers">
                    <li v-for="answer in comment.answers" :key="answer.answer.id" class="cmt-item cmt-item--answer">
                        <div class="cmt-row">
                            <button type="button" class="cmt-avatar-btn" @click="handleUserImageClick(answer.user)" :aria-label="answer.answer.name">
                                <img v-if="avatar(answer.user)" :src="avatar(answer.user)" class="cmt-avatar cmt-avatar--sm" alt="" loading="lazy" decoding="async">
                                <span v-else class="cmt-avatar cmt-avatar--sm cmt-avatar--empty">{{ initials(answer.answer.name, answer.answer.surname) }}</span>
                            </button>
                            <div class="cmt-body">
                                <div class="cmt-meta">
                                    <router-link v-if="answer.user && answer.user.id" :to="{ name: 'climberProfile', params: { id: answer.user.id } }" class="cmt-name">{{ answer.answer.name }} {{ answer.answer.surname }}</router-link>
                                    <span v-else class="cmt-name">{{ answer.answer.name }} {{ answer.answer.surname }}</span>
                                    <span class="cmt-to"><i class="fa fa-long-arrow-right" aria-hidden="true"></i> {{ comment.comment.name }}</span>
                                    <time v-if="answer.answer.created_at" class="cmt-date" :datetime="answer.answer.created_at">{{ format_date(answer.answer.created_at) }}</time>
                                </div>
                                <p class="cmt-text-body">{{ answer.answer.text }}</p>
                                <div class="cmt-row-actions" v-if="is_logged">
                                    <button v-if="is_mine(answer.user)" type="button" class="cmt-link cmt-link--danger" @click="confirm_delete(answer.answer.id)">
                                        <i class="fa fa-trash" aria-hidden="true"></i> {{ $t('common.delete') }}
                                    </button>
                                    <button v-else type="button" class="cmt-link" @click="show_complaint_modal(answer.answer.id)">
                                        <i class="fa fa-flag-o" aria-hidden="true"></i> {{ $t('guide.comments.report') }}
                                    </button>
                                </div>
                            </div>
                        </div>
                    </li>
                </ul>
            </li>
        </ul>

        <StackModal
            v-if="is_user_comment_complaint_model"
            v-model:show="is_user_comment_complaint_model"
            :title="$t('guide.comments.report_title')"
            @close="is_user_comment_complaint_model=false"
            @save="make_complaint"
            :saveButton="{ visible: true, title: $t('guide.comments.report_send'), btnClass: { 'btn btn-primary': true } }"
            :cancelButton="{ visible: true, title: $t('common.close'), btnClass: { 'btn btn-light': true } }"
        >
            <div v-if="complaint_loader" class="text-center py-4">
                <span class="spinner-border text-primary" role="status"></span>
            </div>
            <form v-else @submit.prevent="make_complaint">
                <p class="text-muted">{{ $t('guide.comments.report_text') }}</p>
                <div class="cmt-reasons">
                    <label v-for="r in complaint_reasons" :key="r.value" class="cmt-reason" :class="{ 'is-on': selected_comment_complaint === r.value }">
                        <input type="radio" class="form-check-input" name="cmt_reason" :value="r.value" v-model="selected_comment_complaint">
                        <span>{{ r.label }}</span>
                    </label>
                </div>
                <div v-if="complaint_error" class="text-danger small mt-2">{{ complaint_error }}</div>
            </form>
        </StackModal>

        <user-modal ref="userModal" :modalClass="'user-modal'"></user-modal>
    </section>
</template>

<script>
    import UserModal from '../../../global_components/modals/UserModalComponent.vue'
    import { get_auth_user } from '../../../../services/authUser.js'

    // values are stored as-is by the backend (English) — only the labels are translated
    const REASONS = [
        ['hostile', 'Hostile remarks'],
        ['off_topic', 'Does not match the theme of the site'],
        ['spam', 'Spam'],
        ['sexual_content', 'Sexual content'],
        ['anger', 'Expression of anger'],
        ['conflict', 'Conflict with other members of the site'],
        ['language', 'The language of the comments does not match the requirements of the site'],
    ]

    export default {
        components: { UserModal },
        props: ['article_id'],
        data() {
            return {
                MAX: 500,
                data: { name: '', surname: '', email: '', text: '' },

                comments: [],
                loaded: false,
                is_refresh: false,
                sending: false,
                notice: null,
                errors: {},

                answer_array: { answer: false, comment_id: 0 },
                reply_to: null,

                user: null,

                complaint_comment_id: 0,
                selected_comment_complaint: '',
                complaint_error: '',
                is_user_comment_complaint_model: false,
                complainter_email: '',
                complaint_loader: false,

                id: this.article_id,
            }
        },
        computed: {
            is_logged() {
                return !!(this.user && this.user.id)
            },
            total() {
                return this.comments.reduce((n, c) => n + 1 + (c.answers ? c.answers.length : 0), 0)
            },
            complaint_reasons() {
                return REASONS.map(([key, value]) => ({ value, label: this.$t('shop.product.feedback.reasons.' + key) }))
            },
            options_url() {
                const host = process.env.MIX_USER_PAGE_URL
                return host ? '//' + host + '/options' : '/options'
            },
        },
        watch: {
            article_id() {
                this.id = this.article_id
                this.get_comments()
            },
        },
        mounted() {
            this.get_comments()
            this.get_user_info()
            this.$bus.$on('logged-in', this.get_user_info)
        },
        beforeUnmount() {
            this.$bus.$off('logged-in', this.get_user_info)
        },
        methods: {
            update(id) {
                if (id) this.id = id
                this.get_comments()
            },

            // ---- helpers ----
            err(field) {
                const v = this.errors['data.' + field] || this.errors[field]
                return Array.isArray(v) ? v[0] : (v || '')
            },
            avatar(u) {
                if (!u || !u.image) return ''
                return /^(https?:)?\/\//.test(u.image) ? u.image : '/public/images/user_profil_img/' + u.image
            },
            initials(name, surname) {
                return (((name || '').charAt(0)) + ((surname || '').charAt(0))).toUpperCase() || '?'
            },
            is_mine(u) {
                return !!(u && this.user && u.id == this.user.id)
            },
            format_date(value) {
                const d = new Date(String(value).replace(' ', 'T'))
                if (isNaN(d)) return ''
                return d.toLocaleDateString(this.$i18n.locale === 'ka' ? 'ka-GE' : 'en-GB', { day: 'numeric', month: 'short', year: 'numeric' })
            },

            // ---- reply ----
            crete_comment_answer(comment) {
                this.answer_array = { answer: true, comment_id: comment.id }
                this.reply_to = { id: comment.id, name: [comment.name, comment.surname].filter(Boolean).join(' ') }
                this.$nextTick(() => {
                    if (this.$refs.form) this.$refs.form.scrollIntoView({ behavior: 'smooth', block: 'center' })
                    if (this.$refs.text) this.$refs.text.focus({ preventScroll: true })
                })
            },
            disable_answer() {
                this.answer_array = { answer: false, comment_id: 0 }
                this.reply_to = null
            },

            get_user_info() {
                if (!localStorage.getItem('auth_token')) { this.user = null; return }
                get_auth_user()
                    .then(response => {
                        const u = response.data
                        if (!u || !u.id) { this.user = null; return }
                        this.user = u
                        this.data.name = u.name || ''
                        this.data.surname = u.surname || ''
                        this.data.email = u.email || ''
                        this.complainter_email = u.email
                    })
                    .catch(() => { this.user = null })
            },

            // ---- send ----
            validate_local() {
                const e = {}
                const req = this.$t('guide.comments.required')
                if (!this.is_logged) {
                    if (!this.data.name) e.name = req
                    if (!this.data.surname) e.surname = req
                    if (!/^\S+@\S+\.\S+$/.test(this.data.email)) e.email = this.$t('guide.comments.email_invalid')
                }
                if (!this.data.text.trim()) e.text = req
                this.errors = e
                return !Object.keys(e).length
            },
            async get_recaptcha_token(action = 'comment') {
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
                    // ready() never calls back when an ad-blocker stubs grecaptcha
                    const ready = new Promise(resolve => window.grecaptcha.ready(resolve))
                    const timeout = new Promise((_, reject) => setTimeout(() => reject(new Error('timeout')), 8000))
                    await Promise.race([ready, timeout])
                    return await window.grecaptcha.execute(key, { action })
                } catch (e) { return null }
            },
            async submit() {
                if (this.sending) return
                this.notice = null
                if (!this.validate_local()) return

                this.sending = true
                const token = await this.get_recaptcha_token('comment')
                if (!token) {
                    this.sending = false
                    this.notice = { type: 'warning', text: this.$t('shop.product.feedback.recaptcha_error') }
                    return
                }

                axios
                .post('/set_guide_comment_by_gest/create_comment/' + this.id, {
                    data: { ...this.data, text: this.data.text.trim() },
                    answer_array: this.answer_array,
                    recaptcha_token: token === 'none' ? null : token,
                })
                .then(response => {
                    const msg = typeof response.data === 'string' ? response.data : (response.data && response.data.message)
                    this.errors = {}
                    this.data.text = ''
                    this.disable_answer()
                    this.notice = { type: 'success', text: this.is_logged ? this.$t('guide.comments.sent') : (msg || this.$t('guide.comments.sent_guest')) }
                    this.get_comments()
                })
                .catch(error => {
                    const res = error.response
                    if (res && res.status === 422) {
                        if (res.data && String(res.data.message || '').toLowerCase().includes('recaptcha')) {
                            this.notice = { type: 'warning', text: this.$t('shop.product.feedback.recaptcha_error') }
                        } else {
                            this.errors = (res.data && res.data.errors) || {}
                        }
                    } else {
                        this.notice = { type: 'danger', text: this.$t('guide.comments.send_error') }
                    }
                })
                .finally(() => { this.sending = false })
            },

            // ---- delete / report ----
            confirm_delete(id) {
                if (window.confirm(this.$t('guide.comments.confirm_delete'))) this.del_comment(id)
            },
            del_comment(id) {
                axios
                .delete('/set_article/set_guide_comment/del_comment/' + id)
                .then(() => this.get_comments())
                .catch(() => {})
            },
            show_complaint_modal(comment_id) {
                this.complaint_comment_id = comment_id
                this.selected_comment_complaint = ''
                this.complaint_error = ''
                this.is_user_comment_complaint_model = true
            },
            make_complaint() {
                if (!this.selected_comment_complaint) {
                    this.complaint_error = this.$t('guide.comments.report_pick')
                    return
                }
                this.complaint_loader = true
                axios
                .post('/set_article/set_guide_comment/add_comment_complaint/', {
                    comment_id: this.complaint_comment_id,
                    comment_complaint: this.selected_comment_complaint,
                    email: this.complainter_email,
                })
                .then(response => {
                    this.is_user_comment_complaint_model = false
                    this.selected_comment_complaint = ''
                    this.$bus.$emit('toast', { type: 'success', message: typeof response.data === 'string' ? response.data : this.$t('guide.comments.report_done') })
                })
                .catch(() => {})
                .finally(() => { this.complaint_loader = false })
            },

            get_comments() {
                if (!this.id) return
                this.is_refresh = true
                axios
                .get('/get_article/get_guide_comment/get_article_comments/' + this.id)
                .then(response => { this.comments = Array.isArray(response.data) ? response.data : [] })
                .catch(() => {})
                .finally(() => { this.is_refresh = false; this.loaded = true })
            },

            handleUserImageClick(user) {
                if (user && user.id) this.$refs.userModal.show_modal(user.id)
                else this.$bus.$emit('toast', { type: 'info', message: this.$t('shop.product.feedback.user_not_registered') })
            },
        }
    }
</script>

<style scoped>
    .cmt { clear: both; }

    .cmt-head {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: .75rem;
        margin-bottom: 1rem;
    }
    .cmt-head__count {
        display: inline-block;
        min-width: 1.8rem;
        margin-left: .35rem;
        padding: 0 .5rem;
        border-radius: 1rem;
        background: var(--cg-accent-soft);
        color: var(--cg-accent-dark);
        font-size: .9rem;
        line-height: 1.6rem;
        text-align: center;
        vertical-align: middle;
    }
    .cmt-head__refresh { width: 2.2rem; height: 2.2rem; padding: 0; color: var(--cg-stone); }
    .cmt-head__refresh.is-spinning .fa { animation: cmt-spin .8s linear infinite; }
    @keyframes cmt-spin { to { transform: rotate(360deg); } }
    @media (prefers-reduced-motion: reduce) { .cmt-head__refresh.is-spinning .fa { animation: none; } }

    /* ---- form ---- */
    .cmt-form {
        padding: 1rem;
        margin-bottom: 1.5rem;
        background: #fff;
        border: 1px solid var(--cg-line);
        border-radius: var(--cg-r-lg);
        box-shadow: var(--cg-shadow-sm);
    }
    .cmt-who {
        display: flex;
        align-items: center;
        gap: .5rem;
        margin-bottom: .6rem;
    }
    .cmt-guest {
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: .5rem;
        margin-bottom: .75rem;
        padding: .55rem .75rem;
        border-radius: var(--cg-r);
        background: var(--cg-chalk);
        color: var(--cg-stone);
        font-size: .9rem;
    }
    .cmt-reply-chip {
        display: flex;
        align-items: center;
        gap: .5rem;
        margin-bottom: .5rem;
        padding: .4rem .6rem;
        border-left: 3px solid var(--cg-accent);
        border-radius: var(--cg-r);
        background: var(--cg-accent-soft);
        color: var(--cg-accent-dark);
        font-size: .9rem;
        font-weight: 600;
    }
    .cmt-text textarea { resize: vertical; min-height: 6rem; }
    .cmt-actions {
        display: flex;
        align-items: center;
        flex-wrap: wrap;
        gap: .75rem;
        margin-top: .6rem;
    }
    .cmt-counter { font-size: .8rem; color: var(--cg-muted); font-variant-numeric: tabular-nums; }
    .cmt-counter.is-near { color: var(--cg-rope); font-weight: 700; }

    /* ---- thread ---- */
    .cmt-empty { padding: 1.5rem; text-align: center; color: var(--cg-stone); }
    .cmt-empty .fa { display: block; font-size: 1.8rem; margin-bottom: .4rem; color: var(--cg-muted); }

    .cmt-list, .cmt-answers { list-style: none; margin: 0; padding: 0; }
    .cmt-item {
        padding: .9rem 1rem;
        margin-bottom: .75rem;
        background: #fff;
        border: 1px solid var(--cg-line);
        border-radius: var(--cg-r-lg);
    }
    .cmt-answers {
        margin: .75rem 0 0 1.6rem;
        padding-left: .9rem;
        border-left: 2px solid var(--cg-line);
    }
    .cmt-item--answer {
        margin-bottom: .5rem;
        padding: .7rem .8rem;
        background: var(--cg-chalk);
    }
    .cmt-row { display: flex; align-items: flex-start; gap: .75rem; }
    .cmt-avatar-btn { flex: 0 0 auto; padding: 0; border: 0; background: none; border-radius: 50%; }
    .cmt-avatar {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 44px;
        height: 44px;
        border-radius: 50%;
        object-fit: cover;
        cursor: pointer;
    }
    .cmt-avatar--sm { width: 32px; height: 32px; font-size: .75rem; }
    .cmt-avatar--empty {
        background: var(--cg-accent-soft);
        color: var(--cg-accent-dark);
        font-weight: 800;
    }
    .cmt-body { flex: 1 1 auto; min-width: 0; }
    .cmt-meta {
        display: flex;
        align-items: baseline;
        flex-wrap: wrap;
        gap: .2rem .5rem;
    }
    .cmt-name { font-weight: 700; color: var(--cg-ink); text-decoration: none; overflow-wrap: anywhere; }
    a.cmt-name:hover { text-decoration: underline; }
    .cmt-to { font-size: .85rem; color: var(--cg-stone); }
    .cmt-date { font-size: .8rem; color: var(--cg-muted); }
    .cmt-text-body { margin: .3rem 0 .4rem; white-space: pre-line; overflow-wrap: anywhere; }
    .cmt-row-actions { display: flex; flex-wrap: wrap; gap: .25rem 1rem; }
    .cmt-link {
        padding: 0;
        border: 0;
        background: none;
        color: var(--cg-stone);
        font-size: .85rem;
        font-weight: 600;
    }
    .cmt-link:hover { color: var(--cg-accent-dark); }
    .cmt-link--danger:hover { color: #b02a37; }

    /* ---- report modal ---- */
    .cmt-reasons { display: grid; gap: .4rem; }
    .cmt-reason {
        display: flex;
        align-items: center;
        gap: .6rem;
        padding: .55rem .75rem;
        border: 1px solid var(--cg-line);
        border-radius: var(--cg-r);
        cursor: pointer;
    }
    .cmt-reason .form-check-input { margin: 0; flex: 0 0 auto; }
    .cmt-reason.is-on { border-color: var(--cg-accent); background: var(--cg-accent-soft); }

    @media (max-width: 575.98px) {
        .cmt-form { padding: .8rem; }
        .cmt-item { padding: .75rem; }
        .cmt-answers { margin-left: .4rem; padding-left: .6rem; }
        .cmt-avatar { width: 36px; height: 36px; }
        .cmt-avatar--sm { width: 28px; height: 28px; }
    }
</style>
