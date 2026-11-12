<template>
    <!-- /order_review/:order_id/:token — opened from the "your order is complete" mail.
         Part 1: order / delivery review (admins only see it). Part 2: one card per product
         (published on the product page). One "Send" button saves everything filled in.
         docs/FUNCTIONS_DOCS/shop/order_review.md -->
    <div class="container or-page">
        <section class="cg-page-head cg-page-head--list cg-art-carabiners">
            <span class="cg-page-head__icon"><i class="fa fa-star" aria-hidden="true"></i></span>
            <div class="cg-page-head__text">
                <h1>{{ $t('shop.order_review.title') }}</h1>
                <div class="cg-page-head__lead">
                    <template v-if="data">{{ $t('shop.order_review.lead', { id: data.order.id, date: format_date(data.order.created_at) }) }}</template>
                </div>
            </div>
        </section>

        <div v-if="state === 'loading'" class="text-center py-5">
            <span class="spinner-border text-primary" role="status"></span>
        </div>

        <div v-else-if="state === 'invalid'" class="cg-panel or-msg">
            <i class="fa fa-chain-broken" aria-hidden="true"></i>
            <h2>{{ $t('shop.order_review.invalid_title') }}</h2>
            <p class="mb-0">{{ $t('shop.order_review.invalid_text') }}</p>
        </div>

        <div v-else-if="state === 'not_complete'" class="cg-panel or-msg">
            <i class="fa fa-truck" aria-hidden="true"></i>
            <h2>{{ $t('shop.order_review.not_complete_title') }}</h2>
            <p class="mb-0">{{ $t('shop.order_review.not_complete_text') }}</p>
        </div>

        <template v-else>
            <!-- all done -->
            <div v-if="all_done" class="cg-panel or-msg or-msg--done">
                <i class="fa fa-check-circle" aria-hidden="true"></i>
                <h2>{{ $t('shop.order_review.done_title') }}</h2>
                <p>{{ $t('shop.order_review.done_text') }}</p>
                <router-link :to="{ name: 'catalog' }" class="btn btn-primary">{{ $t('shop.order_review.back_to_shop') }}</router-link>
            </div>

            <form v-else class="or-form" @submit.prevent="send" novalidate>
                <p class="or-hi">{{ $t('shop.order_review.hi', { name: data.buyer.name || '' }) }}</p>

                <!-- 1. order / delivery -->
                <section class="cg-panel or-card">
                    <div class="or-card__head">
                        <span class="or-step">1</span>
                        <div>
                            <h2 class="or-card__title">{{ $t('shop.order_review.order_title') }}</h2>
                            <p class="or-card__hint"><i class="fa fa-lock" aria-hidden="true"></i> {{ $t('shop.order_review.order_private') }}</p>
                        </div>
                    </div>

                    <div v-if="data.order_review" class="or-saved">
                        <i class="fa fa-check" aria-hidden="true"></i> {{ $t('shop.order_review.order_saved', { n: data.order_review.stars }) }}
                    </div>
                    <template v-else>
                        <div class="or-field">
                            <span class="or-label">{{ $t('shop.order_review.overall') }}</span>
                            <star-input v-model="order.stars" :label="$t('shop.order_review.overall')" :invalid="!!errors.order" />
                        </div>
                        <div class="row g-3">
                            <div class="col-sm-6 or-field">
                                <span class="or-label">{{ $t('shop.order_review.delivery_speed') }}</span>
                                <star-input v-model="order.delivery_speed" size="sm" :show_word="false" :label="$t('shop.order_review.delivery_speed')" />
                            </div>
                            <div class="col-sm-6 or-field">
                                <span class="or-label">{{ $t('shop.order_review.packaging') }}</span>
                                <star-input v-model="order.packaging" size="sm" :show_word="false" :label="$t('shop.order_review.packaging')" />
                            </div>
                        </div>
                        <label class="or-label mt-3" for="or_order_text">{{ $t('shop.order_review.order_text') }}</label>
                        <textarea id="or_order_text" v-model="order.text" rows="3" maxlength="1000" class="form-control" :placeholder="$t('shop.order_review.order_placeholder')"></textarea>
                    </template>
                </section>

                <!-- 2. products -->
                <section class="cg-panel or-card">
                    <div class="or-card__head">
                        <span class="or-step">2</span>
                        <div>
                            <h2 class="or-card__title">{{ $t('shop.order_review.products_title') }}</h2>
                            <p class="or-card__hint"><i class="fa fa-globe" aria-hidden="true"></i> {{ $t('shop.order_review.products_public') }}</p>
                        </div>
                    </div>

                    <p v-if="!data.products.length" class="text-muted mb-0">{{ $t('shop.order_review.no_products') }}</p>

                    <div v-for="p in data.products" :key="p.product_id" class="or-product" :class="{ 'is-done': p.reviewed }">
                        <router-link :to="{ name: 'product', params: { url_title: p.url_title } }" class="or-product__img cg-wm" v-if="p.url_title" target="_blank">
                            <img v-if="p.image" :src="p.image" :alt="p.title" loading="lazy" decoding="async">
                            <i v-else class="fa fa-shopping-bag" aria-hidden="true"></i>
                        </router-link>
                        <div class="or-product__body">
                            <div class="or-product__title">{{ p.title }}</div>
                            <div v-if="p.options.length" class="or-product__opts">{{ p.options.join(', ') }}</div>

                            <div v-if="p.reviewed" class="or-saved mt-2">
                                <i class="fa fa-check" aria-hidden="true"></i> {{ $t('shop.order_review.product_saved', { n: p.review ? p.review.stars : '' }) }}
                            </div>
                            <template v-else>
                                <star-input v-model="products[p.product_id].stars" size="sm" :label="p.title" class="mt-1" :invalid="!!errors['p' + p.product_id]" />
                                <textarea
                                    v-if="products[p.product_id].stars"
                                    v-model="products[p.product_id].text"
                                    rows="2"
                                    maxlength="500"
                                    class="form-control mt-2"
                                    :placeholder="$t('shop.product.feedback.feedback_placeholder_short')"
                                    :aria-label="p.title"
                                ></textarea>
                                <div v-if="errors['p' + p.product_id]" class="text-danger small mt-1">{{ errors['p' + p.product_id] }}</div>
                            </template>
                        </div>
                    </div>
                </section>

                <div v-if="notice" class="alert small py-2" :class="'alert-' + notice.type" role="status">{{ notice.text }}</div>

                <div class="or-actions">
                    <span class="small text-muted">{{ $t('shop.order_review.hint_partial') }}</span>
                    <button type="submit" class="btn btn-primary btn-lg ms-auto" :disabled="sending || !has_input">
                        <span v-if="sending" class="spinner-border spinner-border-sm me-1" role="status" aria-hidden="true"></span>
                        <i v-else class="fa fa-paper-plane me-1" aria-hidden="true"></i>
                        {{ $t('shop.order_review.send') }}
                    </button>
                </div>
            </form>
        </template>
    </div>
</template>

<script>
import StarInput from '../../items/StarInputComponent.vue'

export default {
    components: { 'star-input': StarInput },
    data() {
        return {
            state: 'loading',   // loading | invalid | not_complete | ready
            data: null,
            order: { stars: 0, delivery_speed: 0, packaging: 0, text: '' },
            products: {},       // product_id → { stars, text }
            errors: {},
            notice: null,
            sending: false,
        }
    },
    computed: {
        locale() {
            return this.$i18n.locale === 'ka' ? 'ka' : 'en'
        },
        has_input() {
            if (!this.data) return false
            if (!this.data.order_review && this.order.stars) return true
            return Object.values(this.products).some(p => p.stars)
        },
        all_done() {
            return !!(this.data && this.data.order_review && this.data.products.every(p => p.reviewed))
        },
    },
    mounted() {
        this.load()
    },
    methods: {
        format_date(value) {
            const d = new Date(String(value || '').replace(' ', 'T'))
            if (isNaN(d)) return ''
            return d.toLocaleDateString(this.locale === 'ka' ? 'ka-GE' : 'en-GB', { day: 'numeric', month: 'long', year: 'numeric' })
        },
        apply(data) {
            this.data = data
            const products = {}
            data.products.forEach(p => {
                products[p.product_id] = this.products[p.product_id] && !p.reviewed ? this.products[p.product_id] : { stars: 0, text: '' }
            })
            this.products = products
            this.state = data.order.complete ? 'ready' : 'not_complete'
        },
        load() {
            this.state = 'loading'
            axios.get('order_review/get/' + this.$route.params.order_id, {
                params: { token: this.$route.params.token, locale: this.locale },
                _silent: true,
            })
                .then(r => this.apply(r.data))
                .catch(() => { this.state = 'invalid' })
        },
        send() {
            if (this.sending || !this.has_input) return
            this.errors = {}
            this.notice = null

            const payload = {
                token: this.$route.params.token,
                locale: this.locale,
                products: [],
            }
            if (!this.data.order_review && this.order.stars) {
                payload.order = {
                    stars: this.order.stars,
                    delivery_speed: this.order.delivery_speed || null,
                    packaging: this.order.packaging || null,
                    text: this.order.text.trim() || null,
                }
            }
            this.data.products.forEach(p => {
                const v = this.products[p.product_id]
                if (!p.reviewed && v && v.stars) payload.products.push({ product_id: p.product_id, stars: v.stars, text: (v.text || '').trim() })
            })

            this.sending = true
            axios.post('order_review/save/' + this.$route.params.order_id, payload, { _silent: true })
                .then(r => {
                    this.apply(r.data)
                    if (!this.all_done) {
                        this.notice = { type: 'success', text: this.$t('shop.order_review.saved_partial') }
                    }
                    window.scrollTo({ top: 0, behavior: 'smooth' })
                })
                .catch(error => {
                    const res = error.response
                    if (res && res.status === 404) this.state = 'invalid'
                    else if (res && res.status === 422 && res.data && res.data.errors) this.notice = { type: 'warning', text: this.$t('shop.order_review.check_fields') }
                    else if (res && res.status === 422) this.state = 'not_complete'
                    else this.notice = { type: 'danger', text: this.$t('guide.comments.send_error') }
                })
                .finally(() => { this.sending = false })
        },
    },
}
</script>

<style scoped>
    .or-page { padding-bottom: 3rem; }
    .or-form { max-width: 820px; margin: 0 auto; }
    .or-hi { font-size: 1.05rem; color: var(--cg-ink-2); margin: 0 0 1rem; }

    .or-msg { max-width: 560px; margin: 1rem auto; text-align: center; }
    .or-msg > .fa { font-size: 2.6rem; color: var(--cg-muted); margin-bottom: .5rem; }
    .or-msg--done > .fa { color: var(--cg-accent); }
    .or-msg h2 { font-size: 1.4rem; font-weight: 700; }

    .or-card { margin-bottom: 1.25rem; }
    .or-card__head { display: flex; align-items: flex-start; gap: .8rem; margin-bottom: 1rem; }
    .or-step {
        flex: 0 0 2rem;
        height: 2rem;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        background: var(--cg-accent);
        color: #fff;
        font-weight: 800;
    }
    .or-card__title { margin: 0; font-size: 1.2rem; font-weight: 700; color: var(--cg-ink); }
    .or-card__hint { margin: .1rem 0 0; font-size: .85rem; color: var(--cg-stone); }

    .or-field { margin-bottom: .75rem; }
    .or-label { display: block; font-size: .8rem; font-weight: 700; text-transform: uppercase; letter-spacing: .03em; color: var(--cg-muted); margin-bottom: .15rem; }

    .or-saved {
        display: inline-flex;
        align-items: center;
        gap: .4rem;
        padding: .4rem .75rem;
        border-radius: 2rem;
        background: var(--cg-accent-soft);
        color: var(--cg-accent-dark);
        font-weight: 600;
        font-size: .9rem;
    }

    .or-product {
        display: flex;
        gap: .9rem;
        padding: .9rem 0;
        border-top: 1px solid var(--cg-line);
    }
    .or-product:first-of-type { border-top: 0; padding-top: 0; }
    .or-product__img {
        flex: 0 0 76px;
        width: 76px;
        height: 76px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: var(--cg-r);
        background: var(--cg-chalk);
        overflow: hidden;
        color: var(--cg-muted);
        font-size: 1.6rem;
    }
    .or-product__img img { width: 100%; height: 100%; object-fit: cover; }
    .or-product__body { flex: 1; min-width: 0; }
    .or-product__title { font-weight: 700; color: var(--cg-ink); overflow-wrap: anywhere; }
    .or-product__opts { font-size: .85rem; color: var(--cg-stone); }
    .or-product.is-done .or-product__title { color: var(--cg-stone); }

    .or-actions {
        display: flex;
        align-items: center;
        flex-wrap: wrap;
        gap: .75rem;
        position: sticky;
        bottom: 0;
        padding: .8rem 0;
        background: linear-gradient(to top, var(--cg-chalk) 70%, transparent);
    }

    @media (max-width: 575.98px) {
        .or-product__img { flex-basis: 60px; width: 60px; height: 60px; }
        .or-actions .btn { width: 100%; }
    }
</style>
