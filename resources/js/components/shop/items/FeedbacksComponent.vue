<template>
    <!-- Product reviews: rating summary + "Write a review", then the list
         (first PREVIEW shown, "Show all" for the rest). -->
    <div class="col-12">
        <section class="fbl">
            <div class="fbl-head">
                <div class="fbl-score">
                    <span class="fbl-score__num">{{ reviews_count_prop > 0 ? Number(reviews_stars_prop).toFixed(1) : '—' }}</span>
                    <div>
                        <h2 class="fbl-title">{{ $t('shop.product.feedback.title') }}</h2>
                        <starsReiting
                            v-if="reviews_count_prop > 0"
                            :reviews_count_prop="reviews_count_prop"
                            :reviews_stars_prop="reviews_stars_prop"
                            :rewiew_count_text_prop="false"
                        />
                        <span class="fbl-sub">
                            {{ reviews_count_prop > 0 ? $t('shop.product.feedback.count', { n: reviews_count_prop }) : $t('shop.product.feedback.no_feedbacks') }}
                        </span>
                    </div>
                </div>
                <div class="fbl-head__actions">
                    <button type="button" class="btn btn-sm btn-light rounded-circle fbl-refresh" :class="{ 'is-spinning': is_refresh }" @click="get_feedbacks" :title="$t('common.refresh')" :aria-label="$t('common.refresh')">
                        <i class="fa fa-refresh" aria-hidden="true"></i>
                    </button>
                    <button type="button" class="btn btn-primary" @click="show_feedback_modal()">
                        <i class="fa fa-pencil" aria-hidden="true"></i> {{ already_reviewed ? $t('shop.product.feedback.already_short') : $t('shop.product.feedback.create') }}
                    </button>
                </div>
            </div>

            <!-- rating breakdown -->
            <div v-if="feedbacks.length" class="fbl-bars">
                <div v-for="n in [5, 4, 3, 2, 1]" :key="n" class="fbl-bar">
                    <span class="fbl-bar__label">{{ n }} <i class="fa fa-star" aria-hidden="true"></i></span>
                    <span class="fbl-bar__track"><span class="fbl-bar__fill" :style="{ width: bar_percent(n) + '%' }"></span></span>
                    <span class="fbl-bar__n">{{ star_counts[n] }}</span>
                </div>
            </div>

            <p v-else-if="loaded" class="cg-empty fbl-empty">
                <i class="fa fa-star-o" aria-hidden="true"></i>
                {{ $t('shop.product.feedback.be_first') }}
            </p>

            <ul v-if="feedbacks.length" class="fbl-list">
                <li v-for="feedback in visible" :key="feedback.feedback.id" class="fbl-item">
                    <button type="button" class="fbl-avatar-btn" @click="showUserModal(feedback)" :aria-label="feedback.feedback.name">
                        <img v-if="avatar(feedback.user)" :src="avatar(feedback.user)" class="fbl-avatar" alt="" loading="lazy" decoding="async">
                        <span v-else class="fbl-avatar fbl-avatar--empty">{{ initials(feedback.feedback) }}</span>
                    </button>
                    <div class="fbl-body">
                        <div class="fbl-meta">
                            <strong class="fbl-name">{{ feedback.feedback.name }} {{ feedback.feedback.surname }}</strong>
                            <span v-if="Number(feedback.feedback.have_product)" class="badge text-bg-success"><i class="fa fa-check" aria-hidden="true"></i> {{ $t('shop.product.feedback.owner_badge') }}</span>
                            <time v-if="feedback.feedback.created_at" class="fbl-date" :datetime="feedback.feedback.created_at">{{ format_date(feedback.feedback.created_at) }}</time>
                        </div>
                        <div class="fbl-stars" :aria-label="feedback.feedback.stars + ' / 5'">
                            <i v-for="n in 5" :key="n" class="fa" :class="n <= Number(feedback.feedback.stars) ? 'fa-star is-on' : 'fa-star-o'" aria-hidden="true"></i>
                        </div>
                        <p class="fbl-text">{{ feedback.feedback.text }}</p>
                        <div v-if="is_logged" class="fbl-actions">
                            <button v-if="feedback.user && feedback.user.id == user.id" type="button" class="fbl-link fbl-link--danger" @click="confirm_delete_feedback(feedback.feedback.id)">
                                <i class="fa fa-trash" aria-hidden="true"></i> {{ $t('common.delete') }}
                            </button>
                            <button v-else type="button" class="fbl-link" @click="show_complaint_modal(feedback.feedback.id)">
                                <i class="fa fa-flag-o" aria-hidden="true"></i> {{ $t('guide.comments.report') }}
                            </button>
                        </div>
                    </div>
                </li>
            </ul>

            <div v-if="feedbacks.length > PREVIEW" class="text-center mt-2">
                <button type="button" class="btn btn-outline-secondary btn-sm" @click="show_feadbacks_action">
                    {{ show_feadbacks ? $t('shop.product.feedback.close_list') : $t('shop.product.feedback.show_all', { n: feedbacks.length }) }}
                </button>
            </div>
        </section>

        <feedbackComplaintModal ref="feedback_complaint_modal" @restart="get_feedbacks()"/>
        <feadbackModal ref="feadback_modal" @restart="get_feedbacks()"/>
        <UserModalComponent ref="user_modal" />
    </div>
</template>

<script>
    //http://www.blog.tonyswierz.com/javascript/add-and-use-google-recaptcha-in-a-vuejs-laravel-project/
    // import VueRecaptchaV2 from 'vue3-recaptcha-v2'; //https://www.npmjs.com/package/vue3-recaptcha-v2

    import feedbackComplaintModal from './modals/feedbacks/FeedbackComplaintModal';
    import feadbackModal from './modals/feedbacks/FeedbackModal';
    import starsReiting from '../../global_components/StarReitingShowComponent.vue'
    import { get_auth_user } from '../../../services/authUser.js'
    import UserModalComponent from '../../global_components/modals/UserModalComponent.vue'
    
    export default {
        components: {
            feedbackComplaintModal,
            // 'vue-recaptcha': VueRecaptchaV2,
            feadbackModal,
            starsReiting,
            UserModalComponent
        },
        props: {
            product_id_prop: {
                type: Number,
            },
            reviews_stars_prop: {
                type: Number,
                default: 0
            },
            reviews_count_prop: {
                type: Number,
                default: 0
            }
        },
        data() {
            return {
                name: "",
                surname: "",
                email: "",
                text: "",

                show_feadbacks: false,
                PREVIEW: 3,
                loaded: false,

                feedbacks: [],
                is_refresh: false,
                refresh_id: 0,
                id: 0,

                user: [],

                complainter_email: '',

                id: this.product_id_prop,
            }
        },
        computed: {
            is_logged() {
                return !!(this.user && this.user.id)
            },
            visible() {
                return this.show_feadbacks ? this.feedbacks : this.feedbacks.slice(0, this.PREVIEW)
            },
            star_counts() {
                const c = { 1: 0, 2: 0, 3: 0, 4: 0, 5: 0 }
                this.feedbacks.forEach(f => { const n = Math.round(Number(f.feedback.stars) || 0); if (c[n] !== undefined) c[n]++ })
                return c
            },
            already_reviewed() {
                return this.is_logged && this.feedbacks.some(f => f.user && f.user.id == this.user.id)
            },
        },
        watch: {
            // product_id_prop: function(){
            //     this.id = this.product_id_prop
            // },
        },
        mounted() {
            this.get_feedbacks()
            this.get_user_info()
            this.$bus.$on('logged-in', this.get_user_info)

            // this.$refs.stars_reiting_modal.colculate_stars(4.6, 6)
        },
        beforeUnmount() {
            this.$bus.$off('logged-in', this.get_user_info)
        },
        methods: {
            show_feadbacks_action(){
                this.show_feadbacks = !this.show_feadbacks
            },

            avatar(u) {
                if (!u || !u.image) return ''
                return /^(https?:)?\/\//.test(u.image) ? u.image : '/public/images/user_profil_img/' + u.image
            },
            initials(f) {
                return (((f.name || '').charAt(0)) + ((f.surname || '').charAt(0))).toUpperCase() || '?'
            },
            format_date(value) {
                const d = new Date(String(value).replace(' ', 'T'))
                if (isNaN(d)) return ''
                return d.toLocaleDateString(this.$i18n.locale === 'ka' ? 'ka-GE' : 'en-GB', { day: 'numeric', month: 'short', year: 'numeric' })
            },
            bar_percent(n) {
                return this.feedbacks.length ? Math.round(100 * this.star_counts[n] / this.feedbacks.length) : 0
            },
            show_feedback_modal(){
                if (this.already_reviewed) {
                    this.$bus.$emit('toast', { type: 'info', message: this.$t('shop.product.feedback.already') })
                    return
                }
                if (this.user && this.user.id) {
                    this.$refs.feadback_modal.show_modal(this.id)
                } else {
                    this.$bus.$emit('open-login-modal', () => this.$refs.feadback_modal.show_modal(this.id))
                }
            },

            show_complaint_modal(feedback_id){
                this.$refs.feedback_complaint_modal.show_modal(feedback_id)
            },

            showUserModal(feedback){
                if (feedback.user) {
                    this.$refs.user_modal.show_modal(feedback.user.id);
                } else {
                    this.$bus.$emit('toast', { type: 'info', message: this.$t('shop.product.feedback.user_not_registered') });
                }
            },

            confirm_delete_feedback(id){
                if (window.confirm(this.$t('shop.product.feedback.confirm_delete'))) {
                    this.del_feedback(id)
                }
            },

            colculate_feedack_stars(stars){
                this.$refs.feedack_stars.colculate_stars(stars, 1, false)
            },

            get_user_info() {
                this.user = []
                if (!localStorage.getItem('auth_token')) return
                get_auth_user()
                    .then(response => {
                        if (!response.data || !response.data.id) return
                        this.user = response.data
                        this.name = this.user.name
                        this.surname = this.user.surname
                        this.email = this.user.email
                        this.complainter_email = this.user.email
                    })
                    .catch(() => {})
            },

            del_feedback(id) {
                axios
                .delete('/set_product/set_product_feedback/del_feedback/'+ id, {
                    id: id,
                })
                .then(Response => {
                    this.get_feedbacks()
                })
                .catch()
            },

            get_feedbacks: function(){
                // if(!this.id && this.id != 0 && this.id != null && this.id != undefined && this.id != 'undefined'){
                    this.is_refresh = true

                    axios
                    .get('/get_product/get_product_feedback/get_product_feedbacks/' + this.id)
                    .then(response => {
                        this.feedbacks = Array.isArray(response.data) ? response.data : []
                        this.refresh_id++
                    })
                    .catch(() => {})
                    .finally(() => { this.is_refresh = false; this.loaded = true });
                // }
            },
        }
    }
</script>

<style scoped>
    .fbl {
        padding: 1.25rem;
        background: #fff;
        border: 1px solid var(--cg-line);
        border-radius: var(--cg-r-lg);
        box-shadow: var(--cg-shadow-sm);
    }
    .fbl-head {
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 1rem;
    }
    .fbl-score { display: flex; align-items: center; gap: 1rem; }
    .fbl-score__num {
        font-family: var(--cg-font-display);
        font-size: 3rem;
        font-weight: 700;
        line-height: 1;
        color: var(--cg-ink);
    }
    .fbl-title { margin: 0; font-size: 1.25rem; font-weight: 700; }
    .fbl-score :deep(.stars_block) { margin: .15rem 0; }
    .fbl-sub { font-size: .85rem; color: var(--cg-stone); }
    .fbl-head__actions { display: flex; align-items: center; gap: .5rem; }
    .fbl-refresh { width: 2.2rem; height: 2.2rem; padding: 0; color: var(--cg-stone); }
    .fbl-refresh.is-spinning .fa { animation: fbl-spin .8s linear infinite; }
    @keyframes fbl-spin { to { transform: rotate(360deg); } }
    @media (prefers-reduced-motion: reduce) { .fbl-refresh.is-spinning .fa { animation: none; } }

    .fbl-bars { display: grid; gap: .3rem; max-width: 26rem; margin: 1.1rem 0 .5rem; }
    .fbl-bar { display: flex; align-items: center; gap: .6rem; font-size: .8rem; color: var(--cg-stone); }
    .fbl-bar__label { width: 2.4rem; white-space: nowrap; }
    .fbl-bar__label .fa { color: var(--cg-hold); }
    .fbl-bar__track { flex: 1; height: .45rem; border-radius: 1rem; background: var(--cg-chalk-2); overflow: hidden; }
    .fbl-bar__fill { display: block; height: 100%; background: var(--cg-hold); border-radius: 1rem; }
    .fbl-bar__n { width: 1.5rem; text-align: right; font-variant-numeric: tabular-nums; }

    .fbl-empty { margin: 1rem 0 0; padding: 1.25rem; text-align: center; color: var(--cg-stone); }
    .fbl-empty .fa { display: block; font-size: 1.8rem; margin-bottom: .4rem; color: var(--cg-hold); }

    .fbl-list { list-style: none; margin: 1rem 0 0; padding: 0; }
    .fbl-item {
        display: flex;
        gap: .8rem;
        padding: 1rem 0;
        border-top: 1px solid var(--cg-line);
    }
    .fbl-avatar-btn { flex: 0 0 auto; padding: 0; border: 0; background: none; border-radius: 50%; }
    .fbl-avatar {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 44px;
        height: 44px;
        border-radius: 50%;
        object-fit: cover;
        cursor: pointer;
    }
    .fbl-avatar--empty { background: var(--cg-accent-soft); color: var(--cg-accent-dark); font-weight: 800; }
    .fbl-body { flex: 1; min-width: 0; }
    .fbl-meta { display: flex; align-items: baseline; flex-wrap: wrap; gap: .2rem .5rem; }
    .fbl-name { color: var(--cg-ink); overflow-wrap: anywhere; }
    .fbl-date { font-size: .8rem; color: var(--cg-muted); }
    .fbl-stars { margin: .15rem 0; color: var(--cg-line); font-size: .9rem; }
    .fbl-stars .is-on { color: var(--cg-hold); }
    .fbl-text { margin: .25rem 0 .35rem; white-space: pre-line; overflow-wrap: anywhere; }
    .fbl-actions { display: flex; gap: 1rem; }
    .fbl-link { padding: 0; border: 0; background: none; color: var(--cg-stone); font-size: .85rem; font-weight: 600; }
    .fbl-link:hover { color: var(--cg-accent-dark); }
    .fbl-link--danger:hover { color: #b02a37; }

    @media (max-width: 575.98px) {
        .fbl { padding: 1rem; }
        .fbl-head__actions { width: 100%; }
        .fbl-head__actions .btn-primary { flex: 1; }
        .fbl-score__num { font-size: 2.4rem; }
    }
</style>
