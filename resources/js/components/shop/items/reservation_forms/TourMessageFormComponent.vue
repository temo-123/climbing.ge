<template>
    <!-- Tour page reservation form. Every field is required (TourReservationRequest);
         after sending, the form is replaced by a "confirm your email" panel —
         the reservation is only complete after the emailed link is confirmed.
         docs/FUNCTIONS_DOCS/shop/tour_reservation_verification.md -->
    <div class="content" ref="root">
        <div class="container">
            <div class="row">
                <div class="col-12 mb30 text-center cg-res-title">
                    <h2>{{ $t('shop.tour.reserve.title') }}</h2>
                    <p>{{ $t('shop.tour.reserve.subtitle') }}</p>
                </div>
            </div>

            <div class="row">
                <div class="col-12 mb30">

                    <div v-if="is_loading" class="cg-res-loading">
                        <div class="spinner-border text-primary" role="status"></div>
                        <p>{{ $t('user.notifications.please_wait') }}</p>
                    </div>

                    <!-- sent: the reservation waits for the email confirmation -->
                    <div v-else-if="sent_email" class="cg-res-sent" role="status" aria-live="polite" ref="sent">
                        <div class="cg-res-sent__icon" aria-hidden="true"><i class="fa fa-envelope-o"></i></div>
                        <h3>{{ $t('shop.tour.message.sent_title') }}</h3>
                        <p class="cg-res-sent__lead">{{ $t('shop.tour.message.sent_lead') }}</p>
                        <p class="cg-res-sent__email">{{ sent_email }}</p>
                        <p v-if="sent_price" class="cg-res-sent__price">
                            {{ days_text(sent_price.days) }} · {{ persons_text(sent_price.persons) }} ·
                            <strong>{{ sent_price.total !== null ? money_in(sent_price.total, sent_price.currency) : $t('shop.tour.calc.on_request') }}</strong>
                        </p>
                        <ol class="cg-res-sent__steps">
                            <li>{{ $t('shop.tour.message.sent_step_open') }}</li>
                            <li>{{ $t('shop.tour.message.sent_step_button') }}</li>
                            <li>{{ $t('shop.tour.message.sent_step_guide') }}</li>
                        </ol>
                        <p class="cg-res-sent__note">
                            <i class="fa fa-info-circle" aria-hidden="true"></i>
                            {{ $t('shop.tour.message.sent_spam') }}
                        </p>
                        <button type="button" class="btn btn-outline-primary" @click="new_reservation">
                            {{ $t('shop.tour.message.new_reservation') }}
                        </button>
                    </div>

                    <!-- logged in with an incomplete profile: fill it first -->
                    <div v-else-if="is_user && !profile_complete" class="alert alert-warning cg-res-profile" role="alert">
                        <strong>{{ $t('shop.tour.message.warning') }}</strong>
                        <p class="mb-1">{{ $t('shop.tour.message.enter_full_information') }}</p>
                        <p class="mb-2">{{ $t('shop.tour.message.befor_enter_full_information') }}</p>
                        <button type="button" class="btn btn-warning btn-sm" @click="goTo('/options')">
                            {{ $t('shop.tour.message.open_profile') }}
                        </button>
                    </div>

                    <form v-else class="cg-res-form" novalidate @submit.prevent="create_reservation">
                        <p class="cg-res-form__hint">
                            <span class="cg-req" aria-hidden="true">*</span> {{ $t('shop.tour.message.all_required') }}
                        </p>

                        <!-- step 1: the trip + price calculator (rules: admin tour → Prices tab) -->
                        <div class="cg-res-step">
                            <span class="cg-res-step__num" :class="{ 'is-done': step1_done }">
                                <i v-if="step1_done" class="fa fa-check" aria-hidden="true"></i><template v-else>1</template>
                            </span>
                            <div>
                                <h3>{{ $t('shop.tour.reserve.step1') }}</h3>
                                <p>{{ $t('shop.tour.reserve.step1_text') }}</p>
                            </div>
                        </div>
                        <div class="cg-calc">
                            <div class="cg-calc__inputs">
                                <div class="cg-calc__field">
                                    <span class="form-label" id="res_days_label">{{ $t('shop.tour.calc.duration') }} <span class="cg-req">*</span></span>
                                    <div class="cg-calc__chips" role="radiogroup" aria-labelledby="res_days_label" :class="{ 'is-invalid': errors.days }">
                                        <button
                                            v-for="d in duration_options"
                                            :key="d"
                                            type="button"
                                            role="radio"
                                            class="cg-calc__chip"
                                            :class="{ 'is-active': Number(form_data.days) === d }"
                                            :aria-checked="Number(form_data.days) === d ? 'true' : 'false'"
                                            @click="form_data.days = d"
                                        >{{ days_text(d) }}</button>
                                    </div>
                                    <div v-if="errors.days" class="invalid-feedback d-block">{{ errors.days }}</div>
                                </div>

                                <div class="cg-calc__field">
                                    <label class="form-label" for="res_persons">{{ $t('shop.tour.message.persons') }} <span class="cg-req">*</span></label>
                                    <div class="cg-calc__stepper" :class="{ 'is-invalid': errors.persons }">
                                        <button type="button" :aria-label="$t('shop.tour.calc.less')" :disabled="persons_num <= 1" @click="step_persons(-1)"><i class="fa fa-minus" aria-hidden="true"></i></button>
                                        <input v-model="form_data.persons" id="res_persons" type="number" min="1" max="100" step="1" inputmode="numeric" :placeholder="$t('shop.tour.message.persons_ph')" class="form-control" required>
                                        <button type="button" :aria-label="$t('shop.tour.calc.more')" :disabled="persons_num >= 100" @click="step_persons(1)"><i class="fa fa-plus" aria-hidden="true"></i></button>
                                    </div>
                                    <div v-if="errors.persons" class="invalid-feedback d-block">{{ errors.persons }}</div>
                                </div>

                                <div class="cg-calc__field">
                                    <span class="form-label">{{ $t('shop.tour.message.check_in') }} <span class="cg-req">*</span></span>
                                    <!-- only days the tour runs (season, start days, lead time) -->
                                    <TourDatePicker
                                        v-model="form_data.check_in"
                                        :schedule="prices.schedule"
                                        :days="form_data.days || 1"
                                        :invalid="!!errors.check_in"
                                    />
                                    <div v-if="errors.check_in" class="invalid-feedback d-block">{{ errors.check_in }}</div>
                                </div>
                            </div>

                            <aside class="cg-calc__result" aria-live="polite">
                                <span class="cg-calc__result-label">{{ $t('shop.tour.calc.total') }}</span>
                                <template v-if="current_quote">
                                    <strong class="cg-calc__total">{{ money(current_quote.total) }}</strong>
                                    <span class="cg-calc__breakdown" v-if="current_quote.price_type === 'per_group'">
                                        {{ $t('shop.tour.calc.group_price', { persons: persons_text(current_quote.persons), days: days_text(current_quote.days) }) }}
                                    </span>
                                    <span class="cg-calc__breakdown" v-else>
                                        {{ money(current_quote.unit_price) }} × {{ persons_text(current_quote.persons) }} · {{ days_text(current_quote.days) }}
                                    </span>
                                    <span v-if="current_quote.persons > 1" class="cg-calc__per">{{ $t('shop.tour.calc.per_person', { price: money(current_quote.per_person) }) }}</span>
                                </template>
                                <template v-else-if="has_prices && form_data.days && persons_num">
                                    <strong class="cg-calc__total is-request">{{ $t('shop.tour.calc.on_request') }}</strong>
                                    <span class="cg-calc__breakdown">{{ $t('shop.tour.calc.on_request_text') }}</span>
                                </template>
                                <template v-else-if="has_prices">
                                    <strong class="cg-calc__total is-request">{{ from_price ? $t('shop.tour.calc.from', { price: money(from_price) }) : '—' }}</strong>
                                    <span class="cg-calc__breakdown">{{ $t('shop.tour.calc.choose') }}</span>
                                </template>
                                <template v-else>
                                    <strong class="cg-calc__total is-request">{{ $t('shop.tour.calc.on_request') }}</strong>
                                    <span class="cg-calc__breakdown">{{ $t('shop.tour.calc.no_prices') }}</span>
                                </template>
                                <span class="cg-calc__note">{{ $t('shop.tour.calc.note') }}</span>
                                <button v-if="has_prices" type="button" class="btn btn-link btn-sm cg-calc__toggle" :aria-expanded="show_matrix ? 'true' : 'false'" @click="show_matrix = !show_matrix">
                                    <i class="fa" :class="show_matrix ? 'fa-chevron-up' : 'fa-table'" aria-hidden="true"></i>
                                    {{ show_matrix ? $t('shop.tour.calc.hide_prices') : $t('shop.tour.calc.all_prices') }}
                                </button>
                            </aside>
                        </div>

                        <!-- all prices: group size × duration, click a cell to choose it -->
                        <div v-if="show_matrix && has_prices" class="cg-calc__matrix table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>{{ $t('shop.tour.calc.group') }}</th>
                                        <th v-for="d in prices.durations" :key="d">{{ days_text(d) }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="n in matrix_persons" :key="n">
                                        <th>{{ persons_text(n) }}</th>
                                        <td
                                            v-for="d in prices.durations"
                                            :key="d"
                                            :class="{ 'is-active': Number(form_data.days) === d && persons_num === n }"
                                            @click="form_data.days = d; form_data.persons = n"
                                        >
                                            <template v-if="quote(d, n)">
                                                <strong>{{ money(quote(d, n).total) }}</strong>
                                                <small v-if="n > 1">{{ $t('shop.tour.calc.per_person', { price: money(quote(d, n).per_person) }) }}</small>
                                            </template>
                                            <span v-else class="text-muted">{{ $t('shop.tour.calc.on_request') }}</span>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <!-- step 2: contact (guests type it, logged-in users see their profile) + message -->
                        <div class="cg-res-step cg-res-step--next">
                            <span class="cg-res-step__num">2</span>
                            <div>
                                <h3>{{ $t('shop.tour.reserve.step2') }}</h3>
                                <p>{{ is_user ? $t('shop.tour.reserve.step2_user') : $t('shop.tour.reserve.step2_text') }}</p>
                            </div>
                        </div>
                        <div v-if="is_user" class="cg-res-me">
                            <i class="fa fa-user-circle-o" aria-hidden="true"></i>
                            <div>
                                <strong>{{ form_data.name }}</strong>
                                <span>{{ form_data.email }} · {{ form_data.phone }} · {{ form_data.city }}, {{ form_data.country }}</span>
                                <a href="#" @click.prevent="goTo('/options')">{{ $t('shop.tour.message.edit_profile') }}</a>
                            </div>
                        </div>
                        <div v-else class="row g-3 mt-0">
                            <div class="col-md-6">
                                <label class="form-label" for="res_name">{{ $t('shop.tour.message.name') }} <span class="cg-req">*</span></label>
                                <input v-model="form_data.name" id="res_name" type="text" autocomplete="name" :placeholder="$t('shop.tour.message.name_ph')" class="form-control" :class="{ 'is-invalid': errors.name }" required>
                                <div v-if="errors.name" class="invalid-feedback">{{ errors.name }}</div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label" for="res_email">{{ $t('shop.tour.message.email') }} <span class="cg-req">*</span></label>
                                <input v-model="form_data.email" id="res_email" type="email" autocomplete="email" :placeholder="$t('shop.tour.message.email_ph')" class="form-control" :class="{ 'is-invalid': errors.email }" required>
                                <div v-if="errors.email" class="invalid-feedback">{{ errors.email }}</div>
                                <div v-else class="form-text">{{ $t('shop.tour.message.email_help') }}</div>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label" for="res_phone">{{ $t('shop.tour.message.phone') }} <span class="cg-req">*</span></label>
                                <input v-model="form_data.phone" id="res_phone" type="tel" autocomplete="tel" :placeholder="$t('shop.tour.message.phone_ph')" class="form-control" :class="{ 'is-invalid': errors.phone }" required>
                                <div v-if="errors.phone" class="invalid-feedback">{{ errors.phone }}</div>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label" for="res_country">{{ $t('common.country') }} <span class="cg-req">*</span></label>
                                <input v-model="form_data.country" id="res_country" type="text" autocomplete="country-name" class="form-control" :class="{ 'is-invalid': errors.country }" required>
                                <div v-if="errors.country" class="invalid-feedback">{{ errors.country }}</div>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label" for="res_city">{{ $t('shop.tour.message.city') }} <span class="cg-req">*</span></label>
                                <input v-model="form_data.city" id="res_city" type="text" autocomplete="address-level2" class="form-control" :class="{ 'is-invalid': errors.city }" required>
                                <div v-if="errors.city" class="invalid-feedback">{{ errors.city }}</div>
                            </div>
                        </div>

                        <div class="mt-3">
                            <label class="form-label" for="res_text">{{ $t('shop.tour.message.message') }} <span class="cg-req">*</span></label>
                            <textarea v-model="form_data.text" id="res_text" rows="4" maxlength="1000" :placeholder="$t('shop.tour.message.message_ph')" class="form-control" :class="{ 'is-invalid': errors.text }" required></textarea>
                            <div v-if="errors.text" class="invalid-feedback">{{ errors.text }}</div>
                            <div v-else class="form-text text-end">{{ (form_data.text || '').length }} / 1000</div>
                        </div>

                        <div v-if="captcha_error" class="alert alert-warning mt-3 mb-0">
                            <i class="fa fa-exclamation-triangle" aria-hidden="true"></i>
                            {{ $t('shop.tour.message.captcha_failed') }}
                        </div>
                        <div v-else-if="server_error" class="alert alert-danger mt-3 mb-0">{{ server_error }}</div>

                        <!-- reserve: what is being reserved + the button -->
                        <div class="cg-res-form__foot">
                            <div class="cg-res-form__summary">
                                <span v-if="form_data.days && persons_num" class="cg-res-form__trip">
                                    {{ days_text(form_data.days) }} · {{ persons_text(persons_num) }}
                                    <strong v-if="current_quote"> · {{ money(current_quote.total) }}</strong>
                                    <span v-else> · {{ $t('shop.tour.calc.on_request') }}</span>
                                </span>
                                <p class="cg-res-form__verify">
                                    <i class="fa fa-envelope-o" aria-hidden="true"></i>
                                    {{ $t('shop.tour.message.verify_notice') }}
                                </p>
                            </div>
                            <button type="submit" class="btn btn-success btn-lg cg-res-form__submit" :disabled="is_submitting || captcha_error">
                                <span v-if="is_submitting" class="spinner-border spinner-border-sm me-2" aria-hidden="true"></span>
                                <i v-else class="fa fa-calendar-check-o me-2" aria-hidden="true"></i>
                                {{ current_quote ? $t('shop.tour.reserve.btn_price', { price: money(current_quote.total) }) : $t('shop.tour.reserve.btn') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import { get_auth_user } from '../../../../services/authUser.js'
    import { quote_price, money, persons_range } from '../../../../services/shop/tourPrice.js'
    import { start_problem, parse_ymd } from '../../../../services/shop/tourSchedule.js'
    import TourDatePicker from './TourDatePickerComponent.vue'

    const EMPTY = () => ({ name: '', email: '', phone: '', country: '', city: '', check_in: '', persons: 1, days: '', text: '' });

    export default {
        props: ['tour_id_prop'],
        components: { TourDatePicker },
        data () {
            return {
                form_data: EMPTY(),
                user: null,
                is_loading: false,
                is_submitting: false,
                captcha_error: false,
                server_error: '',
                errors: {},
                // email the confirmation link went to (shows the "confirm your email" panel)
                sent_email: '',
                sent_price: null,
                // price calculator (get_tour/get_tour_prices)
                prices: { currency: 'EUR', durations: [], rules: [], schedule: null },
                show_matrix: false,
            }
        },
        computed: {
            is_user() { return !!(this.user && this.user.id); },
            profile_complete() {
                const u = this.user || {};
                return !!(u.name && u.surname && u.country && u.city && u.phone_number && u.email);
            },
            min_date() { return new Date().toISOString().split('T')[0] + 'T00:00'; },
            has_prices() { return this.prices.rules.length > 0; },
            // the tour's schedule decides (fixed list / range / price list / 1–7)
            duration_options() {
                const s = this.prices.schedule;
                if (s && Array.isArray(s.durations) && s.durations.length) return s.durations.map(Number);
                return this.prices.durations.length ? this.prices.durations.map(Number) : [1, 2, 3, 4, 5, 6, 7];
            },
            persons_num() { return parseInt(this.form_data.persons, 10) || 0; },
            step1_done() { return !!(parseInt(this.form_data.days, 10) && this.persons_num && this.form_data.check_in); },
            current_quote() { return this.quote(this.form_data.days, this.persons_num); },
            // cheapest per-person price (shown before a duration is chosen)
            from_price() {
                const values = [];
                this.prices.durations.forEach(d => {
                    for (let n = 1; n <= 10; n++) { const q = this.quote(d, n); if (q) values.push(q.per_person); }
                });
                return values.length ? Math.min(...values) : null;
            },
            matrix_persons() {
                const r = persons_range(this.prices.rules);
                const top = Math.min(10, r.max === 100 ? Math.max(r.min + 5, ...this.prices.rules.map(x => Number(x.persons_min) + 1)) : r.max);
                return Array.from({ length: Math.max(top, 1) }, (_, i) => i + 1);
            },
        },
        watch: {
            // one duration only → choose it; a duration that is no longer offered → clear
            duration_options(list) {
                if (this.form_data.days && !list.includes(Number(this.form_data.days))) this.form_data.days = '';
                if (list.length === 1 && !this.form_data.days) this.form_data.days = list[0];
            },
        },
        mounted() {
            this.get_user_info()
            this.get_prices()
            if (!window.grecaptcha) {
                const key = process.env.MIX_GOOGLE_CAPTCHA_V3_SITE_KEY
                if (key) {
                    const s = document.createElement('script')
                    s.src = `https://www.google.com/recaptcha/api.js?render=${key}`
                    document.head.appendChild(s)
                }
            }
        },
        methods: {
            get_prices() {
                if (!this.tour_id_prop) return;
                axios.get('/get_tour/get_tour_prices/' + this.tour_id_prop)
                    .then(r => {
                        const d = r.data || {};
                        this.prices = { currency: d.currency || 'EUR', durations: d.durations || [], rules: d.rules || [], schedule: d.schedule || null };
                    })
                    .catch(() => {});
            },
            quote(days, persons) { return quote_price(this.prices.rules, days, persons); },
            money_in(v, currency) { return money(v, currency || this.prices.currency); },
            money(v) { return money(v, this.prices.currency); },
            days_text(n) { return this.$t(Number(n) === 1 ? 'shop.tour.calc.one_day' : 'shop.tour.calc.n_days', { n }); },
            persons_text(n) { return this.$t(Number(n) === 1 ? 'shop.tour.calc.one_person' : 'shop.tour.calc.n_persons', { n }); },
            step_persons(d) {
                const n = Math.min(100, Math.max(1, this.persons_num + d));
                this.form_data.persons = n;
            },
            goTo(page = '/'){
                window.open(process.env.MIX_APP_SSH + 'user.' + process.env.MIX_SITE_URL + page) ;
            },
            async get_recaptcha_token(action = 'reservation') {
                const key = process.env.MIX_GOOGLE_CAPTCHA_V3_SITE_KEY
                if (!key) return 'no-captcha'   // not configured → the server skips the check too
                if (!window.grecaptcha) return null
                try {
                    await new Promise(resolve => window.grecaptcha.ready(resolve))
                    return await window.grecaptcha.execute(key, { action })
                } catch { return null }
            },

            // same rules as TourReservationRequest, so most mistakes never reach the server
            validate() {
                const f = this.form_data, e = {}, t = k => this.$t('shop.tour.message.err_' + k);
                if (!parseInt(f.days, 10)) e.days = t('days');
                if (!f.check_in) e.check_in = t('check_in');
                else {
                    const problem = start_problem(this.prices.schedule, parse_ymd(f.check_in), f.days || 1);
                    if (problem) e.check_in = this.$t('shop.tour.schedule.why_' + problem, { n: (this.prices.schedule && this.prices.schedule.lead_days) || 0 });
                }
                const persons = parseInt(f.persons, 10);
                if (!persons || persons < 1 || persons > 100 || String(persons) !== String(f.persons).trim()) e.persons = t('persons');
                if (!this.is_user) {
                    if (String(f.name).trim().length < 2) e.name = t('name');
                    if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(String(f.email).trim())) e.email = t('email');
                    if (String(f.phone).replace(/\D/g, '').length < 5) e.phone = t('phone');
                    if (String(f.country).trim().length < 2) e.country = t('country');
                    if (String(f.city).trim().length < 2) e.city = t('city');
                }
                if (String(f.text).trim().length < 5) e.text = t('text');
                this.errors = e;
                return Object.keys(e).length === 0;
            },
            focus_first_error() {
                this.$nextTick(() => {
                    const el = this.$refs.root && this.$refs.root.querySelector('.is-invalid');
                    if (el) { el.focus(); el.scrollIntoView({ behavior: 'smooth', block: 'center' }); }
                });
            },

            async create_reservation(){
                if (this.is_submitting) return;
                this.server_error = '';
                if (!this.validate()) { this.focus_first_error(); return; }

                this.is_submitting = true;
                const recaptcha_token = await this.get_recaptcha_token('reservation')
                if (!recaptcha_token) {
                    this.captcha_error = true;
                    this.is_submitting = false;
                    return;
                }

                axios
                .post('/set_user_reservation/create_reservation/' + this.tour_id_prop, {
                    form_data: this.form_data,
                    recaptcha_token: recaptcha_token === 'no-captcha' ? null : recaptcha_token,
                })
                .then(response => {
                    this.sent_email = (response.data && response.data.email) || this.form_data.email;
                    this.sent_price = (response.data && response.data.price) || null;
                    this.errors = {};
                    // bring the "confirm your email" panel into view
                    this.$nextTick(() => {
                        const el = this.$refs.sent;
                        if (el) el.scrollIntoView({ behavior: 'smooth', block: 'center' });
                    });
                })
                .catch(error => {
                    const data = (error.response && error.response.data) || {};
                    if (error.response && error.response.status === 422 && data.errors) {
                        // "form_data.email" → errors.email
                        const e = {};
                        Object.keys(data.errors).forEach(key => { e[key.replace('form_data.', '')] = data.errors[key][0]; });
                        this.errors = e;
                        this.focus_first_error();
                    } else if (error.response && error.response.status === 422 && String(data.message || '').toLowerCase().includes('recaptcha')) {
                        this.captcha_error = true;
                    } else {
                        this.server_error = data.message || this.$t('shop.tour.message.err_server');
                    }
                })
                .finally(() => { this.is_submitting = false; });
            },

            // after sending: an empty form again (a logged-in user keeps the profile fields)
            new_reservation() {
                const keep = this.is_user ? { name: this.form_data.name, email: this.form_data.email, phone: this.form_data.phone, country: this.form_data.country, city: this.form_data.city } : {};
                this.form_data = { ...EMPTY(), ...keep };
                this.sent_email = '';
                this.sent_price = null;
                this.errors = {};
            },

            get_user_info() {
                this.is_loading = true
                get_auth_user()
                .then(response => {
                    const u = response.data || {};
                    this.user = u;
                    this.form_data.name = [u.name, u.surname].filter(Boolean).join(' ');
                    this.form_data.country = u.country || '';
                    this.form_data.city = u.city || '';
                    this.form_data.phone = u.phone_number || '';
                    this.form_data.email = u.email || '';
                })
                .catch(() => { this.user = null; })
                .finally(() => { this.is_loading = false; })
            },
        }
    }
</script>

<style scoped>
.cg-res-loading { padding: 2rem 0; text-align: center; color: var(--cg-stone); }

.cg-res-form {
    padding: 1.5rem;
    border: 1px solid var(--cg-line);
    border-radius: var(--cg-r-lg);
    background: var(--cg-surface, #fff);
    box-shadow: var(--cg-shadow-sm);
}
.cg-res-form .form-label { font-weight: 700; color: var(--cg-ink-2); }
.cg-req { color: #d64545; font-weight: 800; }
.cg-res-form__hint { margin-bottom: 1rem; font-size: .85rem; color: var(--cg-muted); }

.cg-res-title p { max-width: 560px; margin: .35rem auto 0; color: var(--cg-stone); }
.cg-res-step {
    display: flex;
    align-items: flex-start;
    gap: .75rem;
    margin-bottom: 1rem;
}
.cg-res-step--next { margin-top: 1.75rem; padding-top: 1.5rem; border-top: 1px solid var(--cg-line); }
.cg-res-step__num {
    flex: 0 0 2.1rem;
    display: grid;
    place-items: center;
    width: 2.1rem;
    height: 2.1rem;
    border-radius: 50%;
    background: var(--cg-accent);
    color: #fff;
    font-weight: 800;
    transition: background .2s ease;
}
.cg-res-step__num.is-done { background: #2fa36b; }
.cg-res-step h3 { margin: 0; font-family: var(--cg-font-display); font-size: 1.35rem; color: var(--cg-ink); }
.cg-res-step p { margin: .1rem 0 0; font-size: .9rem; color: var(--cg-stone); }
.cg-res-form__summary { flex: 1 1 280px; display: flex; flex-direction: column; gap: .3rem; }
.cg-res-form__trip { font-weight: 700; color: var(--cg-ink-2); }
.cg-res-form__trip strong { color: var(--cg-ink); }
.cg-res-form__foot {
    display: flex;
    flex-wrap: wrap;
    align-items: center;
    justify-content: space-between;
    gap: .75rem 1.25rem;
    margin-top: 1.25rem;
    padding-top: 1.1rem;
    border-top: 1px solid var(--cg-line);
}
.cg-res-form__verify {
    flex: none;
    margin: 0;
    font-size: .88rem;
    color: var(--cg-stone);
}
.cg-res-form__verify .fa { color: var(--cg-accent-dark); margin-right: .25rem; }

.cg-res-me {
    display: flex;
    gap: .75rem;
    align-items: center;
    margin-top: 1rem;
    padding: .8rem 1rem;
    border-radius: var(--cg-r);
    background: var(--cg-chalk);
}
.cg-res-me > .fa { font-size: 2rem; color: var(--cg-accent-dark); }
.cg-res-me div { display: flex; flex-direction: column; gap: .1rem; min-width: 0; }
.cg-res-me strong { color: var(--cg-ink); }
.cg-res-me span { font-size: .88rem; color: var(--cg-stone); word-break: break-word; }
.cg-res-me a { font-size: .82rem; font-weight: 700; }

.cg-res-profile { border-radius: var(--cg-r); }

/* price calculator */
.cg-calc {
    display: grid;
    grid-template-columns: minmax(0, 1fr) minmax(240px, 300px);
    gap: 1.25rem;
    align-items: start;
}
.cg-calc__inputs { display: flex; flex-direction: column; gap: 1rem; }
.cg-calc__chips { display: flex; flex-wrap: wrap; gap: .45rem; }
.cg-calc__chips.is-invalid .cg-calc__chip { border-color: #d64545; }
.cg-calc__chip {
    padding: .5rem 1rem;
    border: 1px solid var(--cg-line);
    border-radius: 999px;
    background: var(--cg-surface, #fff);
    font-weight: 700;
    color: var(--cg-ink-2);
    cursor: pointer;
    transition: border-color .15s ease, background .15s ease;
}
.cg-calc__chip:hover { border-color: var(--cg-accent); }
.cg-calc__chip.is-active { border-color: var(--cg-accent); background: var(--cg-accent); color: #fff; }
.cg-calc__chip:focus-visible { outline: 2px solid var(--cg-accent); outline-offset: 2px; }
.cg-calc__stepper { display: flex; max-width: 220px; }
.cg-calc__stepper button {
    flex: 0 0 2.75rem;
    border: 1px solid var(--cg-line);
    background: var(--cg-chalk);
    color: var(--cg-ink-2);
    cursor: pointer;
}
.cg-calc__stepper button:first-child { border-radius: var(--cg-r-sm, .5rem) 0 0 var(--cg-r-sm, .5rem); }
.cg-calc__stepper button:last-child { border-radius: 0 var(--cg-r-sm, .5rem) var(--cg-r-sm, .5rem) 0; }
.cg-calc__stepper button:disabled { opacity: .45; cursor: default; }
.cg-calc__stepper input { border-radius: 0; text-align: center; font-weight: 800; -moz-appearance: textfield; }
.cg-calc__stepper input::-webkit-outer-spin-button, .cg-calc__stepper input::-webkit-inner-spin-button { -webkit-appearance: none; margin: 0; }
.cg-calc__stepper.is-invalid input, .cg-calc__stepper.is-invalid button { border-color: #d64545; }

.cg-calc__result {
    position: sticky;
    top: 90px;
    display: flex;
    flex-direction: column;
    gap: .25rem;
    padding: 1.1rem 1.2rem;
    border-radius: var(--cg-r-lg);
    background: var(--cg-accent-soft);
    border: 1px solid var(--cg-line);
}
.cg-calc__result-label { font-size: .75rem; font-weight: 800; letter-spacing: .05em; text-transform: uppercase; color: var(--cg-accent-dark); }
.cg-calc__total { font-family: var(--cg-font-display); font-size: 2.1rem; line-height: 1.1; color: var(--cg-ink); }
.cg-calc__total.is-request { font-size: 1.35rem; }
.cg-calc__breakdown { font-weight: 700; color: var(--cg-ink-2); }
.cg-calc__per { font-size: .88rem; color: var(--cg-stone); }
.cg-calc__note { margin-top: .4rem; font-size: .78rem; color: var(--cg-muted); }
.cg-calc__toggle { align-self: flex-start; padding: .25rem 0; font-weight: 700; text-decoration: none; }

.cg-calc__matrix { margin-top: 1rem; }
.cg-calc__matrix table { margin: 0; background: var(--cg-surface, #fff); border: 1px solid var(--cg-line); }
.cg-calc__matrix th { white-space: nowrap; font-size: .82rem; color: var(--cg-stone); background: var(--cg-chalk); }
.cg-calc__matrix td { white-space: nowrap; cursor: pointer; }
.cg-calc__matrix td small { display: block; font-size: .75rem; color: var(--cg-muted); }
.cg-calc__matrix td:hover { background: var(--cg-accent-soft); }
.cg-calc__matrix td.is-active { background: var(--cg-accent-soft); box-shadow: inset 0 0 0 2px var(--cg-accent); }

.cg-res-sent__price { margin-top: -.5rem; color: var(--cg-ink-2); }

.cg-res-sent {
    max-width: 620px;
    margin: 0 auto;
    padding: 2rem 1.5rem;
    border: 1px solid var(--cg-line);
    border-top: 4px solid var(--cg-accent);
    border-radius: var(--cg-r-lg);
    background: var(--cg-surface, #fff);
    box-shadow: var(--cg-shadow);
    text-align: center;
}
.cg-res-sent__icon {
    display: grid;
    place-items: center;
    width: 64px;
    height: 64px;
    margin: 0 auto 1rem;
    border-radius: 50%;
    background: var(--cg-accent-soft);
    color: var(--cg-accent-dark);
    font-size: 1.7rem;
}
.cg-res-sent h3 { font-family: var(--cg-font-display); color: var(--cg-ink); }
.cg-res-sent__lead { margin-bottom: .35rem; color: var(--cg-stone); }
.cg-res-sent__email {
    display: inline-block;
    margin-bottom: 1rem;
    padding: .3rem .9rem;
    border-radius: 999px;
    background: var(--cg-chalk);
    font-weight: 800;
    color: var(--cg-ink);
    word-break: break-all;
}
.cg-res-sent__steps {
    max-width: 420px;
    margin: 0 auto 1rem;
    padding-left: 1.2rem;
    text-align: left;
    color: var(--cg-ink-2);
}
.cg-res-sent__steps li { margin-bottom: .3rem; }
.cg-res-sent__note { font-size: .85rem; color: var(--cg-muted); }

@media (max-width: 767.98px) {
    .cg-calc { grid-template-columns: 1fr; }
    .cg-calc__result { position: static; }
}
@media (max-width: 575.98px) {
    .cg-res-form { padding: 1rem; }
    .cg-res-form__foot .btn { width: 100%; }
}
</style>
