<template>
    <!-- /confirm_reservation/:reservation_id/:token — opened from the "confirm your
         reservation" mail. Shows what is being confirmed and one button.
         docs/FUNCTIONS_DOCS/shop/tour_reservation_verification.md -->
    <div class="container cg-rv">
        <div class="cg-rv__card">
            <div class="cg-rv__icon" :class="'is-' + state" aria-hidden="true">
                <i class="fa" :class="icon"></i>
            </div>

            <div v-if="state === 'loading'" class="cg-rv__body">
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">{{ $t('user.notifications.please_wait') }}</span>
                </div>
            </div>

            <div v-else-if="state === 'invalid'" class="cg-rv__body">
                <h1>{{ $t('shop.tour.verification.invalid_title') }}</h1>
                <p>{{ $t('shop.tour.verification.invalid_text') }}</p>
            </div>

            <div v-else class="cg-rv__body">
                <h1 v-if="state === 'ready'">{{ $t('shop.tour.verification.confirm_reservation_title') }}</h1>
                <h1 v-else-if="state === 'done'">{{ $t('shop.tour.verification.done_title') }}</h1>
                <h1 v-else>{{ $t('shop.tour.verification.already_title') }}</h1>

                <p v-if="state === 'ready'">{{ $t('shop.tour.verification.ready_text') }}</p>
                <p v-else>{{ $t('shop.tour.verification.done_text') }}</p>

                <dl class="cg-rv__facts">
                    <template v-if="tour_title">
                        <dt>{{ $t('shop.tour.verification.tour') }}</dt>
                        <dd>
                            <router-link v-if="info.tour && info.tour.url_title" :to="{ name: 'tour', params: { url_title: info.tour.url_title } }">{{ tour_title }}</router-link>
                            <span v-else>{{ tour_title }}</span>
                        </dd>
                    </template>
                    <template v-if="info.check_in">
                        <dt>{{ $t('shop.tour.message.check_in') }}</dt>
                        <dd>{{ format_date(info.check_in, true) }}</dd>
                    </template>
                    <template v-if="info.days">
                        <dt>{{ $t('shop.tour.calc.duration') }}</dt>
                        <dd>{{ $t(Number(info.days) === 1 ? 'shop.tour.calc.one_day' : 'shop.tour.calc.n_days', { n: info.days }) }}</dd>
                    </template>
                    <template v-if="info.persons">
                        <dt>{{ $t('shop.tour.message.persons') }}</dt>
                        <dd>{{ info.persons }}</dd>
                    </template>
                    <template v-if="info.days">
                        <dt>{{ $t('shop.tour.calc.total') }}</dt>
                        <dd>
                            <template v-if="info.price_total !== null && info.price_total !== undefined">
                                {{ money(info.price_total, info.price_currency) }}
                                <small v-if="info.price_details && info.price_details.price_type === 'per_person' && info.persons > 1" class="cg-rv__muted">
                                    ({{ money(info.price_details.unit_price, info.price_currency) }} × {{ info.persons }})
                                </small>
                            </template>
                            <span v-else>{{ $t('shop.tour.calc.on_request') }}</span>
                        </dd>
                    </template>
                    <template v-if="info.name">
                        <dt>{{ $t('shop.tour.message.name') }}</dt>
                        <dd>{{ info.name }}</dd>
                    </template>
                    <template v-if="info.email">
                        <dt>{{ $t('shop.tour.message.email') }}</dt>
                        <dd>{{ info.email }}</dd>
                    </template>
                    <template v-if="info.verified_at">
                        <dt>{{ $t('shop.tour.verification.confirmed_at') }}</dt>
                        <dd>{{ format_date(info.verified_at) }}</dd>
                    </template>
                </dl>

                <div v-if="error" class="alert alert-danger">{{ error }}</div>

                <button
                    v-if="state === 'ready'"
                    type="button"
                    class="btn btn-primary btn-lg"
                    :disabled="sending"
                    @click="confirm_reservation"
                >
                    <span v-if="sending" class="spinner-border spinner-border-sm me-2" aria-hidden="true"></span>
                    <i v-else class="fa fa-check me-1" aria-hidden="true"></i>
                    {{ $t('shop.tour.verification.confirm_btn') }}
                </button>

                <a v-else :href="user_panel_url" class="btn btn-outline-primary">
                    {{ $t('shop.tour.verification.open_panel') }}
                </a>
            </div>
        </div>
    </div>
</template>

<script>
    import { money } from '../../../../../services/shop/tourPrice.js'

    export default {
        name: 'TourReservationVerificationePage',
        data() {
            return {
                state: 'loading',   // loading | invalid | ready | done | already
                info: {},
                sending: false,
                error: '',
            };
        },
        computed: {
            token() { return this.$route.params.token; },
            reservation_id() { return this.$route.params.reservation_id; },
            icon() {
                return {
                    loading: 'fa-envelope-o',
                    invalid: 'fa-chain-broken',
                    ready: 'fa-envelope-open-o',
                    done: 'fa-check',
                    already: 'fa-check',
                }[this.state];
            },
            tour_title() {
                const t = this.info.tour;
                if (!t) return '';
                return (this.$i18n.locale === 'ka' ? t.ka_title : t.us_title) || t.us_title || t.url_title;
            },
            user_panel_url() {
                return (process.env.MIX_APP_SSH || '') + (process.env.MIX_USER_PAGE_URL || '');
            },
        },
        mounted() {
            this.load();
        },
        watch: {
            '$route.params.token'() { this.load(); },
        },
        methods: {
            load() {
                this.state = 'loading';
                this.error = '';
                axios
                    .post('/set_user_reservation/reservation_status/' + this.reservation_id, { token: this.token })
                    .then(response => {
                        this.info = response.data || {};
                        this.state = this.info.verified ? 'already' : 'ready';
                    })
                    .catch(() => { this.state = 'invalid'; });
            },
            confirm_reservation() {
                if (this.sending) return;
                this.sending = true;
                this.error = '';
                axios
                    .post('/set_user_reservation/verify_reservation/' + this.reservation_id, { token: this.token })
                    .then(response => {
                        this.info = (response.data && response.data.reservation) || this.info;
                        this.state = response.data && response.data.status === 'already_verified' ? 'already' : 'done';
                    })
                    .catch(error => {
                        if (error.response && error.response.status === 404) this.state = 'invalid';
                        else this.error = this.$t('shop.tour.verification.failed');
                    })
                    .finally(() => { this.sending = false; });
            },
            money(v, currency) { return money(v, currency); },
            // check-in is a date ("2026-12-12", older ones "…T09:00"); confirmed_at is a timestamp
            format_date(value, is_check_in = false) {
                if (!value) return '';
                const s = String(value);
                const date_only = /^\d{4}-\d{2}-\d{2}$/.test(s);
                const m = /^(\d{4})-(\d{2})-(\d{2})/.exec(s);
                const d = date_only && m ? new Date(+m[1], +m[2] - 1, +m[3]) : new Date(s.replace(' ', 'T'));
                if (isNaN(d.getTime())) return value;
                const opts = { weekday: is_check_in ? 'short' : undefined, year: 'numeric', month: 'short', day: 'numeric' };
                if (!date_only && !is_check_in) { opts.hour = '2-digit'; opts.minute = '2-digit'; }
                return d.toLocaleString(this.$i18n.locale === 'ka' ? 'ka-GE' : 'en-GB', opts);
            },
        },
    };
</script>

<style scoped>
.cg-rv {
    display: flex;
    justify-content: center;
    padding: 3rem 1rem 4rem;
}
.cg-rv__card {
    width: 100%;
    max-width: 560px;
    padding: 2rem 1.75rem;
    border: 1px solid var(--cg-line);
    border-radius: var(--cg-r-lg);
    background: var(--cg-surface, #fff);
    box-shadow: var(--cg-shadow-sm);
    text-align: center;
}
.cg-rv__icon {
    display: grid;
    place-items: center;
    width: 64px;
    height: 64px;
    margin: 0 auto 1rem;
    border-radius: 50%;
    background: var(--cg-accent-soft);
    color: var(--cg-accent-dark);
    font-size: 1.6rem;
}
.cg-rv__icon.is-done, .cg-rv__icon.is-already { background: #e3f5ec; color: #2fa36b; }
.cg-rv__icon.is-invalid { background: var(--cg-chalk-2); color: var(--cg-muted); }
.cg-rv__body h1 {
    margin-bottom: .5rem;
    font-family: var(--cg-font-display);
    font-size: 1.7rem;
    color: var(--cg-ink);
}
.cg-rv__body p { color: var(--cg-stone); }
.cg-rv__facts {
    display: grid;
    grid-template-columns: auto 1fr;
    gap: .45rem 1rem;
    margin: 1.25rem 0 1.5rem;
    padding: 1rem 1.1rem;
    border-radius: var(--cg-r);
    background: var(--cg-chalk);
    text-align: left;
}
.cg-rv__facts dt { margin: 0; font-weight: 700; color: var(--cg-stone); }
.cg-rv__facts dd { margin: 0; font-weight: 700; color: var(--cg-ink); word-break: break-word; }
.cg-rv__muted { font-weight: 500; color: var(--cg-muted); }
@media (max-width: 575.98px) {
    .cg-rv { padding: 1.5rem 0 3rem; }
    .cg-rv__card { padding: 1.5rem 1rem; }
    .cg-rv__facts { grid-template-columns: 1fr; gap: .1rem; }
    .cg-rv__facts dd { margin-bottom: .45rem; }
}
</style>
