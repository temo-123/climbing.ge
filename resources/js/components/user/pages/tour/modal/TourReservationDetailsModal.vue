<template>
    <!-- Admin: one tour reservation. Opens with the table row, then loads the full
         details (tour, guides, linked accounts, confirmation timeline) from
         set_tour/get_reservation/get_reservation/{id}.
         docs/FUNCTIONS_DOCS/shop/tour_reservation_verification.md -->
    <div>
        <stack-modal
            :show="show_modal"
            :title="$t('admin.tour.reservation_details_title')"
            size="xl"
            @close="close_modal"
            :saveButton="{ visible: false }"
            :cancelButton="{ visible: true, title: $t('common.close'), btnClass: { 'btn btn-secondary': true } }"
        >
            <div class="cg-rd" v-if="reservation">
                <!-- head: tour + status -->
                <header class="cg-rd__head">
                    <div class="cg-rd__head-main">
                        <span class="cg-rd__status" :class="'is-' + status">
                            <i class="fa" :class="status_icon" aria-hidden="true"></i>
                            {{ status_label }}
                        </span>
                        <h3 class="cg-rd__title">{{ tour_title }}</h3>
                        <p class="cg-rd__sub">
                            {{ $t('admin.tour.reservation_number_prefix') }}{{ reservation.id }}
                            <span v-if="reservation.created_at"> · {{ $t('admin.tour.rd_made') }} {{ fmt_datetime(reservation.created_at) }}</span>
                        </p>
                    </div>
                    <div v-if="details_loading" class="spinner-border spinner-border-sm text-secondary" role="status" :title="$t('user.notifications.please_wait')"></div>
                </header>

                <!-- key facts -->
                <div class="cg-rd__facts">
                    <div class="cg-rd__fact">
                        <span class="cg-rd__fact-label"><i class="fa fa-calendar" aria-hidden="true"></i> {{ $t('admin.tour.check_in_col') }}</span>
                        <strong>{{ fmt_date(reservation.check_in) }}</strong>
                        <span class="cg-rd__fact-note">
                            <span v-if="check_in_time">{{ check_in_time }}</span>
                            <span v-if="days_label" :class="{ 'is-past': status === 'past' }">{{ check_in_time ? ' · ' : '' }}{{ days_label }}</span>
                        </span>
                    </div>
                    <div class="cg-rd__fact">
                        <span class="cg-rd__fact-label"><i class="fa fa-users" aria-hidden="true"></i> {{ $t('admin.tour.persons_col') }}</span>
                        <strong>{{ reservation.persons || '—' }}</strong>
                        <span class="cg-rd__fact-note">{{ $t('admin.tour.rd_people') }}</span>
                    </div>
                    <div class="cg-rd__fact">
                        <span class="cg-rd__fact-label"><i class="fa fa-clock-o" aria-hidden="true"></i> {{ $t('admin.tour.rd_duration') }}</span>
                        <strong>{{ reservation.days ? days_text(reservation.days) : '—' }}</strong>
                        <span class="cg-rd__fact-note" v-if="tour_duration">{{ $t('admin.tour_prices.tour_length', { text: tour_duration }) }}</span>
                    </div>
                    <div class="cg-rd__fact" v-if="reservation.tour_language">
                        <span class="cg-rd__fact-label"><i class="fa fa-language" aria-hidden="true"></i> {{ $t('shop.tour.lang.label') }}</span>
                        <strong>{{ $t('shop.tour.lang.' + reservation.tour_language) }}</strong>
                    </div>
                    <div class="cg-rd__fact is-price">
                        <span class="cg-rd__fact-label"><i class="fa fa-tag" aria-hidden="true"></i> {{ $t('admin.tour.rd_price') }}</span>
                        <template v-if="reservation.price_total !== null && reservation.price_total !== undefined">
                            <strong>{{ money_of(reservation.price_total) }}</strong>
                            <span class="cg-rd__fact-note">{{ price_breakdown }}</span>
                        </template>
                        <template v-else>
                            <strong>{{ reservation.days ? $t('admin.tour_prices.on_request') : '—' }}</strong>
                            <span class="cg-rd__fact-note" v-if="tour && tour.min_price">{{ $t('admin.tour_prices.tour_from', { price: price_label }) }}</span>
                        </template>
                    </div>
                </div>

                <div class="cg-rd__grid">
                    <!-- confirmation timeline -->
                    <section class="cg-rd__card">
                        <h4><i class="fa fa-envelope-o" aria-hidden="true"></i> {{ $t('admin.tour.rd_confirmation') }}</h4>
                        <ol class="cg-rd__timeline">
                            <li class="is-done">
                                <strong>{{ $t('admin.tour.rd_step_made') }}</strong>
                                <span>{{ reservation.created_at ? fmt_datetime(reservation.created_at) : $t('admin.tour.rd_mail_unknown') }}</span>
                            </li>
                            <li :class="reservation.verify_sent_at || reservation.verify_method === 'email' ? 'is-done' : (reservation.verify_method === 'admin' ? 'is-skip' : 'is-wait')">
                                <strong>{{ $t('admin.tour.rd_step_mail') }}</strong>
                                <span v-if="reservation.verify_sent_at">
                                    {{ fmt_datetime(reservation.verify_sent_at) }}
                                    <em v-if="reservation.verify_sent_count > 1">· {{ $t('admin.tour.rd_sent_times', { n: reservation.verify_sent_count }) }}</em>
                                </span>
                                <span v-else-if="reservation.verify_method === 'admin'">{{ $t('admin.tour.rd_mail_skipped') }}</span>
                                <span v-else>{{ $t('admin.tour.rd_mail_unknown') }}</span>
                            </li>
                            <li :class="is_verified ? 'is-done' : 'is-wait'">
                                <strong>{{ is_verified ? $t('admin.tour.rd_step_confirmed') : $t('admin.tour.rd_step_waiting') }}</strong>
                                <span v-if="is_verified">
                                    {{ reservation.verified_at ? fmt_datetime(reservation.verified_at) : $t('admin.tour.verified_yes') }}
                                    <em v-if="reservation.verify_method">· {{ $t('admin.tour.verify_method_' + reservation.verify_method) }}</em>
                                </span>
                                <span v-else>{{ $t('admin.tour.waiting_email_confirm') }}</span>
                            </li>
                        </ol>
                        <div v-if="!is_verified" class="cg-rd__card-actions">
                            <button type="button" class="btn btn-success btn-sm" :disabled="isLoading" @click="verifyReservation">
                                <i class="fa fa-check" aria-hidden="true"></i> {{ $t('admin.tour.verify_btn') }}
                            </button>
                            <button v-if="reservation.email" type="button" class="btn btn-outline-secondary btn-sm" :disabled="isLoading" @click="resendVerification">
                                <i class="fa fa-refresh" aria-hidden="true"></i> {{ $t('admin.tour.resend_confirm_btn') }}
                            </button>
                        </div>
                    </section>

                    <!-- reserver -->
                    <section class="cg-rd__card">
                        <h4><i class="fa fa-user" aria-hidden="true"></i> {{ $t('admin.tour.rd_reserver') }}</h4>
                        <dl class="cg-rd__list">
                            <dt>{{ $t('common.name') }}</dt>
                            <dd>{{ reservation.name || '—' }}</dd>
                            <dt>{{ $t('common.email') }}</dt>
                            <dd><a v-if="reservation.email" :href="'mailto:' + reservation.email">{{ reservation.email }}</a><span v-else>—</span></dd>
                            <dt>{{ $t('admin.tour.rd_phone') }}</dt>
                            <dd><a v-if="reservation.phone" :href="'tel:' + reservation.phone">{{ reservation.phone }}</a><span v-else>—</span></dd>
                            <dt>{{ $t('admin.tour.rd_from') }}</dt>
                            <dd>{{ [reservation.city, reservation.country].filter(Boolean).join(', ') || '—' }}</dd>
                            <dt>{{ $t('admin.tour.rd_account') }}</dt>
                            <dd>
                                <template v-if="accounts.length">
                                    <span v-for="u in accounts" :key="u.id" class="cg-rd__chip">
                                        <i class="fa fa-user-circle-o" aria-hidden="true"></i> {{ [u.name, u.surname].filter(Boolean).join(' ') }} <small>#{{ u.id }}</small>
                                    </span>
                                </template>
                                <span v-else class="cg-rd__muted">{{ $t('admin.tour.rd_guest') }}</span>
                            </dd>
                        </dl>
                        <p v-if="details && details.same_email_count" class="cg-rd__note">
                            <i class="fa fa-history" aria-hidden="true"></i>
                            {{ $t('admin.tour.rd_same_email', { n: details.same_email_count }) }}
                        </p>
                    </section>

                    <!-- tour + guides -->
                    <section class="cg-rd__card" v-if="tour">
                        <h4><i class="fa fa-map-signs" aria-hidden="true"></i> {{ $t('admin.tour.rd_tour') }}</h4>
                        <dl class="cg-rd__list">
                            <dt>{{ $t('admin.tour.rd_tour') }}</dt>
                            <dd>
                                <a v-if="tour.public_url" :href="tour.public_url" target="_blank" rel="noopener">{{ tour_title }} <i class="fa fa-external-link" aria-hidden="true"></i></a>
                                <span v-else>{{ tour_title }}</span>
                                <span v-if="!tour.published" class="cg-rd__chip is-warn">{{ $t('admin.tour.rd_unpublished') }}</span>
                            </dd>
                            <template v-if="tour_location">
                                <dt>{{ $t('admin.tour.rd_location') }}</dt>
                                <dd>{{ tour_location }}</dd>
                            </template>
                            <dt>{{ $t('admin.tour.rd_guide') }}</dt>
                            <dd>
                                <div v-for="g in tour_guides" :key="g.id" class="cg-rd__guide">
                                    <strong>{{ g.name }} <span v-if="g.is_main" class="cg-rd__star" :title="$t('admin.tour_guides.general')">★</span></strong>
                                    <a v-if="g.email" :href="'mailto:' + g.email">{{ g.email }}</a>
                                </div>
                                <span v-if="!tour_guides.length" class="cg-rd__muted">{{ $t('admin.tour.rd_no_guide') }}</span>
                            </dd>
                        </dl>
                    </section>

                    <!-- guide in charge: switch (only to the tour's guides) + history -->
                    <section class="cg-rd__card cg-rd__guidecard">
                        <h4><i class="fa fa-compass" aria-hidden="true"></i> {{ $t('admin.tour_guides.guide_in_charge') }}</h4>
                        <div v-if="details && details.guide" class="cg-rd__incharge">
                            <strong>{{ details.guide.name }}</strong>
                            <span v-if="is_general(details.guide.id)" class="cg-rd__chip">★ {{ $t('admin.tour_guides.general') }}</span>
                            <small>
                                <a v-if="details.guide.email" :href="'mailto:' + details.guide.email">{{ details.guide.email }}</a>
                                <template v-if="details.guide.phone"> · <a :href="'tel:' + details.guide.phone">{{ details.guide.phone }}</a></template>
                            </small>
                        </div>
                        <p v-else-if="details" class="cg-rd__muted">{{ $t('admin.tour_guides.nobody') }}</p>

                        <div v-if="details && tour_guides.length > 1 && $can('edit', 'tour_reservation')" class="cg-rd__switch">
                            <label class="form-label">{{ $t('admin.tour_guides.switch_to') }}</label>
                            <div class="cg-rd__switch-row">
                                <select v-model="switch_form.user_id" class="form-select form-select-sm">
                                    <option value="">{{ $t('admin.tour_guides.choose_guide') }}</option>
                                    <option v-for="g in tour_guides.filter(x => !details.guide || x.id !== details.guide.id)" :key="g.id" :value="g.id">{{ g.name }}{{ g.is_main ? ' ★' : '' }}</option>
                                </select>
                                <button type="button" class="btn btn-sm btn-warning" :disabled="!switch_form.user_id || isLoading" @click="switchGuide">
                                    <i class="fa fa-exchange" aria-hidden="true"></i> {{ $t('admin.tour_guides.switch_btn') }}
                                </button>
                            </div>
                            <input v-model="switch_form.note" type="text" maxlength="500" class="form-control form-control-sm mt-2" :placeholder="$t('admin.tour_guides.note_ph')">
                            <div v-if="is_verified" class="form-check mt-1">
                                <input id="rd_notify_client" v-model="switch_form.notify" class="form-check-input" type="checkbox">
                                <label for="rd_notify_client" class="form-check-label">{{ $t('admin.tour_guides.notify_client_one') }}</label>
                            </div>
                            <small v-else class="cg-rd__muted d-block mt-1">{{ $t('admin.tour_guides.notify_client_unconfirmed') }}</small>
                        </div>

                        <ol v-if="details && details.guide_history && details.guide_history.length" class="cg-rd__ghist">
                            <li v-for="h in details.guide_history" :key="h.id">
                                <strong>{{ h.from || '—' }} → {{ h.to || '—' }}</strong>
                                <span>{{ fmt_datetime(h.at) }} · {{ $t('admin.tour_guides.reason_' + (h.reason || 'manual')) }}<template v-if="h.by"> · {{ h.by }}</template></span>
                                <em v-if="h.note">“{{ h.note }}”</em>
                            </li>
                        </ol>
                    </section>

                    <!-- completion + climbing.ge fee (fee only when a helper guide has it) -->
                    <section class="cg-rd__card">
                        <h4><i class="fa fa-flag-checkered" aria-hidden="true"></i> {{ $t('admin.tour_fee.title') }}</h4>
                        <div class="cg-rd__state">
                            <div>
                                <strong>{{ $t('admin.tour_fee.tour') }}</strong>
                                <span v-if="reservation.completed_at" class="cg-rd__chip is-ok">✓ {{ $t('admin.tour_fee.completed') }} · {{ fmt_date(reservation.completed_at) }}</span>
                                <span v-else class="cg-rd__muted">{{ $t('admin.tour_fee.not_completed') }}</span>
                            </div>
                            <button v-if="$can('edit', 'tour_reservation')" type="button" class="btn btn-sm" :class="reservation.completed_at ? 'btn-outline-secondary' : 'btn-success'" :disabled="isLoading" @click="setStatus({ completed: !reservation.completed_at })">
                                {{ reservation.completed_at ? $t('admin.tour_fee.undo') : $t('admin.tour_fee.mark_completed') }}
                            </button>
                        </div>
                        <div class="cg-rd__state">
                            <div>
                                <strong>{{ $t('admin.tour_fee.fee') }}</strong>
                                <template v-if="reservation.commission_percent">
                                    <span class="cg-rd__fee">{{ reservation.commission_amount !== null && reservation.commission_amount !== undefined ? money_of(reservation.commission_amount) : '' }} ({{ Number(reservation.commission_percent) }}%)</span>
                                    <span v-if="reservation.commission_paid_at" class="cg-rd__chip is-ok">✓ {{ $t('admin.tour_fee.paid') }} · {{ fmt_date(reservation.commission_paid_at) }}</span>
                                    <span v-else class="cg-rd__chip is-warn">{{ $t('admin.tour_fee.unpaid') }}</span>
                                </template>
                                <span v-else class="cg-rd__muted">{{ $t('admin.tour_fee.no_fee') }}</span>
                            </div>
                            <button v-if="reservation.commission_percent && $can('edit', 'tour_reservation')" type="button" class="btn btn-sm" :class="reservation.commission_paid_at ? 'btn-outline-secondary' : 'btn-warning'" :disabled="isLoading" @click="setStatus({ commission_paid: !reservation.commission_paid_at })">
                                {{ reservation.commission_paid_at ? $t('admin.tour_fee.undo') : $t('admin.tour_fee.mark_paid') }}
                            </button>
                        </div>
                        <p class="cg-rd__note">{{ $t('admin.tour_fee.rule') }}</p>
                    </section>

                    <!-- message -->
                    <section class="cg-rd__card">
                        <h4><i class="fa fa-comment-o" aria-hidden="true"></i> {{ $t('admin.tour.rd_message') }}</h4>
                        <p v-if="reservation.text" class="cg-rd__message">{{ reservation.text }}</p>
                        <p v-else class="cg-rd__muted">{{ $t('admin.tour.rd_no_message') }}</p>
                    </section>
                </div>

                <!-- actions -->
                <div class="cg-rd__actions">
                    <a v-if="reservation.email" class="btn btn-primary" :href="mail_link">
                        <i class="fa fa-envelope" aria-hidden="true"></i> {{ $t('admin.tour.send_email_btn') }}
                    </a>
                    <a v-if="reservation.phone" class="btn btn-outline-primary" :href="'tel:' + reservation.phone">
                        <i class="fa fa-phone" aria-hidden="true"></i> {{ $t('admin.tour.rd_call') }}
                    </a>
                    <button type="button" class="btn btn-outline-primary" @click="addToCalendar">
                        <i class="fa fa-calendar-plus-o" aria-hidden="true"></i> {{ $t('admin.tour.add_to_calendar_btn') }}
                    </button>
                    <button v-if="$can('del', 'tour_reservation')" type="button" class="btn btn-outline-danger ms-auto" @click="showDeleteConfirm = true">
                        <i class="fa fa-trash" aria-hidden="true"></i> {{ $t('common.delete') }}
                    </button>
                </div>

                <!-- delete confirmation -->
                <div v-if="showDeleteConfirm" class="cg-rd__confirm" role="alertdialog">
                    <p><strong>{{ $t('admin.tour.confirm_delete_title') }}</strong> {{ $t('admin.tour.confirm_delete_reservation_msg') }} <span class="cg-rd__muted">{{ $t('admin.tour.action_cannot_be_undone') }}</span></p>
                    <div>
                        <button type="button" class="btn btn-secondary btn-sm" @click="showDeleteConfirm = false">{{ $t('admin.comments.cancel_btn') }}</button>
                        <button type="button" class="btn btn-danger btn-sm" :disabled="isLoading" @click="deleteReservation">
                            <i class="fa fa-trash" aria-hidden="true"></i> {{ $t('common.delete') }}
                        </button>
                    </div>
                </div>
            </div>

            <div v-else class="cg-rd__empty">
                <i class="fa fa-exclamation-circle" aria-hidden="true"></i>
                <h4>{{ $t('admin.tour.no_reservation_data_title') }}</h4>
                <p>{{ $t('admin.tour.unable_to_load_reservation') }}</p>
            </div>
        </stack-modal>
    </div>
</template>

<script>
    import { money } from '../../../../../services/shop/tourPrice.js'

    // check_in is saved as "2026-12-01T10:00" (datetime-local) or an older "2026-12-01"
    function to_date(v) {
        if (!v) return null;
        const only = /^(\d{4})-(\d{2})-(\d{2})$/.exec(String(v));
        if (only) return new Date(+only[1], +only[2] - 1, +only[3]);
        const d = new Date(String(v).replace(' ', 'T'));
        return isNaN(d.getTime()) ? null : d;
    }
    const has_time = v => /\d{2}:\d{2}/.test(String(v || ''));

    export default {
        emits: ['deleted', 'verified'],
        data(){
            return{
                show_modal: false,
                showDeleteConfirm: false,
                isLoading: false,
                details_loading: false,
                reservation: null,
                details: null,
                switch_form: { user_id: '', note: '', notify: true },
            }
        },
        computed: {
            locale() { return this.$i18n.locale === 'ka' ? 'ka' : 'us'; },
            date_locale() { return this.$i18n.locale === 'ka' ? 'ka-GE' : 'en-GB'; },
            tour() { return this.details && this.details.tour; },
            guides() { return (this.details && this.details.guides) || []; },
            accounts() { return (this.details && this.details.accounts) || []; },
            // the tour's guides (general first) — the only people a reservation can switch to
            tour_guides() { return (this.details && this.details.tour_guides) || []; },
            tour_title() {
                const t = this.tour;
                if (!t) return this.$t('admin.tour.tour_hash_prefix') + (this.reservation ? this.reservation.tour_id : '');
                return t[this.locale + '_title'] || t.us_title || t.url_title;
            },
            tour_location() { return this.tour ? (this.tour[this.locale + '_location'] || this.tour.us_location || '') : ''; },
            tour_duration() { return this.tour ? (this.tour[this.locale + '_duration'] || this.tour.us_duration || '') : ''; },
            is_verified() {
                const v = this.reservation && this.reservation.verificate;
                return v === true || v === 1 || v === '1';
            },
            // past (check-in day gone) > confirmed > waiting
            status() {
                const d = to_date(this.reservation && this.reservation.check_in);
                const today = new Date(); today.setHours(0, 0, 0, 0);
                if (d && d < today) return 'past';
                return this.is_verified ? 'confirmed' : 'waiting';
            },
            status_label() {
                return {
                    past: this.$t('admin.tour.rd_status_past'),
                    confirmed: this.$t('admin.tour.rd_status_confirmed'),
                    waiting: this.$t('admin.tour.rd_status_waiting'),
                }[this.status];
            },
            status_icon() {
                return { past: 'fa-history', confirmed: 'fa-check-circle', waiting: 'fa-hourglass-half' }[this.status];
            },
            check_in_time() {
                const v = this.reservation && this.reservation.check_in;
                const d = to_date(v);
                return d && has_time(v) ? d.toLocaleTimeString(this.date_locale, { hour: '2-digit', minute: '2-digit' }) : '';
            },
            // "in 12 days" / "today" / "5 days ago"
            days_label() {
                const d = to_date(this.reservation && this.reservation.check_in);
                if (!d) return '';
                const a = new Date(d); a.setHours(0, 0, 0, 0);
                const b = new Date(); b.setHours(0, 0, 0, 0);
                const days = Math.round((a - b) / 86400000);
                if (days === 0) return this.$t('admin.tour.rd_today');
                return days > 0 ? this.$t('admin.tour.rd_in_days', { n: days }) : this.$t('admin.tour.rd_days_ago', { n: -days });
            },
            // min_price is free text ("100", "100$", "from 80 GEL") — add ₾ only to a bare number
            price_label() {
                const p = String((this.tour && this.tour.min_price) || '').trim();
                return /^\d+([.,]\d+)?$/.test(p) ? p + ' ₾' : p;
            },
            // "180 ₾ × 3 persons" / "group price" — from the saved snapshot
            price_breakdown() {
                const q = this.reservation && this.reservation.price_details;
                if (!q) return '';
                if (q.price_type === 'per_group') return this.$t('admin.tour_prices.per_group');
                return this.money_of(q.unit_price) + ' × ' + q.persons;
            },
            mail_link() {
                const subject = encodeURIComponent(this.$t('admin.tour.rd_mail_subject', { tour: this.tour_title }));
                return 'mailto:' + this.reservation.email + '?subject=' + subject;
            },
        },
        methods: {
            show_modal_func(reservation){
                this.reservation = { ...reservation };
                this.details = null;
                this.switch_form = { user_id: '', note: '', notify: true };
                this.showDeleteConfirm = false;
                this.show_modal = true;
                this.load_details();
            },
            load_details(){
                if (!this.reservation) return;
                const id = this.reservation.id;
                this.details_loading = true;
                axios.get('/set_tour/get_reservation/get_reservation/' + id)
                .then(response => {
                    if (!this.reservation || this.reservation.id !== id) return;
                    this.details = response.data;
                    // the fresh row (verify_sent_at etc.) wins over the table copy
                    this.reservation = { ...this.reservation, ...response.data };
                })
                .catch(error => console.log(error))
                .finally(() => { this.details_loading = false; });
            },
            close_modal(){
                this.show_modal = false;
                this.reservation = null;
                this.details = null;
                this.showDeleteConfirm = false;
            },

            money_of(v) { return money(v, this.reservation && this.reservation.price_currency); },
            days_text(n) { return this.$t(Number(n) === 1 ? 'admin.tour_prices.one_day' : 'admin.tour_prices.n_days', { n }); },
            fmt_date(v){
                const d = to_date(v);
                if (!d) return v || '—';
                return d.toLocaleDateString(this.date_locale, { weekday: 'short', year: 'numeric', month: 'short', day: 'numeric' });
            },
            fmt_datetime(v){
                const d = to_date(v);
                if (!d) return v || '—';
                return d.toLocaleString(this.date_locale, { year: 'numeric', month: 'short', day: 'numeric', hour: '2-digit', minute: '2-digit' });
            },

            // tour completed / fee paid (or undo)
            setStatus(payload){
                if (!this.reservation || this.isLoading) return;
                this.isLoading = true;
                axios.post('/set_tour/set_reservation/set_status/' + this.reservation.id, payload)
                .then(r => {
                    this.details = r.data;
                    this.reservation = { ...this.reservation, ...r.data };
                    this.$emit('verified', this.reservation.id);
                    this.$bus.$emit('toast', { type: 'success', message: this.$t('admin.tour_fee.saved') });
                })
                .catch(e => this.$bus.$emit('toast', { type: 'danger', message: (e.response && e.response.data && e.response.data.message) || this.$t('admin.tour_guides.failed') }))
                .finally(() => { this.isLoading = false; });
            },

            is_general(id) { const g = this.tour_guides.find(x => x.id === id); return !!(g && g.is_main); },

            // switch the guide in charge; mails: new guide, previous guide, guest (optional)
            switchGuide(){
                if (!this.reservation || !this.switch_form.user_id || this.isLoading) return;
                this.isLoading = true;
                axios.post('/set_tour/set_reservation/assign_guide/' + this.reservation.id, {
                    user_id: this.switch_form.user_id,
                    note: this.switch_form.note || null,
                    notify_client: this.is_verified && this.switch_form.notify,
                })
                .then(r => {
                    this.details = r.data;
                    this.reservation = { ...this.reservation, ...r.data };
                    this.switch_form = { user_id: '', note: '', notify: true };
                    this.$emit('verified', this.reservation.id);
                    this.$bus.$emit('toast', { type: 'success', message: this.$t('admin.tour_guides.switched', { id: this.reservation.id }) });
                })
                .catch(e => {
                    this.$bus.$emit('toast', { type: 'danger', message: (e.response && e.response.data && e.response.data.message) || this.$t('admin.tour_guides.failed') });
                })
                .finally(() => { this.isLoading = false; });
            },

            verifyReservation(){
                if (!this.reservation || this.isLoading) return;
                this.isLoading = true;
                axios.post('/set_tour/set_reservation/verifiation_reservation/' + this.reservation.id)
                .then(response => {
                    const fresh = response.data && response.data.reservation;
                    this.reservation = { ...this.reservation, verificate: 1, ...(fresh || {}) };
                    this.$emit('verified', this.reservation.id);
                    this.$bus.$emit('toast', { type: 'success', message: this.$t('admin.tour.reservation_verified_success') });
                })
                .catch(error => {
                    console.log(error);
                    this.$bus.$emit('toast', { type: 'danger', message: this.$t('admin.tour.failed_verify_reservation') });
                })
                .finally(() => { this.isLoading = false; });
            },

            // send the "confirm your reservation" mail again
            resendVerification(){
                if (!this.reservation || this.isLoading) return;
                this.isLoading = true;
                axios.post('/set_tour/set_reservation/resend_verification/' + this.reservation.id)
                .then(() => {
                    this.$bus.$emit('toast', { type: 'success', message: this.$t('admin.tour.resend_confirm_success') });
                    this.load_details();
                })
                .catch(() => {
                    this.$bus.$emit('toast', { type: 'danger', message: this.$t('admin.tour.resend_confirm_failed') });
                })
                .finally(() => { this.isLoading = false; });
            },

            // Google Calendar event: check-in time (or 09:00) for 8 hours
            addToCalendar(){
                if (!this.reservation) return;
                const start = to_date(this.reservation.check_in);
                if (!start) return;
                if (!has_time(this.reservation.check_in)) start.setHours(9, 0, 0, 0);
                const end = new Date(start.getTime() + 8 * 3600000);
                const pad = n => String(n).padStart(2, '0');
                const stamp = d => `${d.getFullYear()}${pad(d.getMonth() + 1)}${pad(d.getDate())}T${pad(d.getHours())}${pad(d.getMinutes())}00`;
                const r = this.reservation;
                const text = encodeURIComponent(this.tour_title + ' — ' + (r.name || ''));
                const details = encodeURIComponent([
                    this.$t('common.name') + ': ' + (r.name || ''),
                    this.$t('common.email') + ': ' + (r.email || ''),
                    this.$t('admin.tour.rd_phone') + ': ' + (r.phone || ''),
                    this.$t('admin.tour.persons_col') + ': ' + (r.persons || ''),
                    r.text ? '\n' + r.text : '',
                ].join('\n'));
                const location = encodeURIComponent(this.tour_location || '');
                window.open(`https://calendar.google.com/calendar/render?action=TEMPLATE&text=${text}&details=${details}&location=${location}&dates=${stamp(start)}/${stamp(end)}`, '_blank');
            },

            deleteReservation(){
                if (!this.reservation || this.isLoading) return;
                const id = this.reservation.id;
                this.isLoading = true;
                axios.post('/set_tour/set_reservation/del_reservation/' + id, { _method: 'DELETE' })
                .then(() => {
                    this.$bus.$emit('toast', { type: 'success', message: this.$t('admin.tour.reservation_deleted_success') });
                    this.close_modal();
                    this.$emit('deleted', id);
                })
                .catch(error => {
                    console.log(error);
                    this.$bus.$emit('toast', { type: 'danger', message: this.$t('admin.tour.failed_delete_reservation') });
                })
                .finally(() => { this.isLoading = false; });
            },
        },
    }
</script>

<style scoped>
.cg-rd { font-size: .95rem; color: var(--cg-ink-2); }

.cg-rd__head {
    display: flex;
    align-items: flex-start;
    justify-content: space-between;
    gap: 1rem;
    margin-bottom: 1rem;
}
.cg-rd__status {
    display: inline-flex;
    align-items: center;
    gap: .35rem;
    padding: .2rem .7rem;
    border-radius: 999px;
    font-size: .78rem;
    font-weight: 800;
    letter-spacing: .02em;
    text-transform: uppercase;
}
.cg-rd__status.is-waiting { background: #fff3cd; color: #8a6100; }
.cg-rd__status.is-confirmed { background: #e3f5ec; color: #1f7a4d; }
.cg-rd__status.is-past { background: var(--cg-chalk-2); color: var(--cg-stone); }
.cg-rd__title {
    margin: .45rem 0 .15rem;
    font-family: var(--cg-font-display);
    font-size: 1.55rem;
    line-height: 1.15;
    color: var(--cg-ink);
}
.cg-rd__sub { margin: 0; font-size: .85rem; color: var(--cg-muted); }

.cg-rd__facts {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
    gap: .75rem;
    margin-bottom: 1rem;
}
.cg-rd__fact {
    display: flex;
    flex-direction: column;
    gap: .15rem;
    padding: .75rem .9rem;
    border-radius: var(--cg-r);
    background: var(--cg-chalk);
}
.cg-rd__fact-label { font-size: .75rem; font-weight: 800; text-transform: uppercase; letter-spacing: .03em; color: var(--cg-muted); }
.cg-rd__fact-label .fa { margin-right: .2rem; }
.cg-rd__fact strong { font-size: 1.15rem; color: var(--cg-ink); }
.cg-rd__fact-note { font-size: .82rem; color: var(--cg-stone); }
.cg-rd__fact-note .is-past { color: #b54708; }
.cg-rd__fact.is-price { background: var(--cg-accent-soft); }

.cg-rd__grid {
    display: grid;
    grid-template-columns: repeat(2, minmax(0, 1fr));
    gap: .9rem;
}
.cg-rd__card {
    padding: 1rem 1.1rem;
    border: 1px solid var(--cg-line);
    border-radius: var(--cg-r);
    background: var(--cg-surface, #fff);
}
.cg-rd__card h4 {
    display: flex;
    align-items: center;
    gap: .45rem;
    margin: 0 0 .75rem;
    font-size: .8rem;
    font-weight: 800;
    letter-spacing: .04em;
    text-transform: uppercase;
    color: var(--cg-stone);
}
.cg-rd__card h4 .fa { color: var(--cg-accent-dark); font-size: .95rem; }
.cg-rd__card-actions { display: flex; flex-wrap: wrap; gap: .5rem; margin-top: .75rem; }

.cg-rd__list {
    display: grid;
    grid-template-columns: max-content 1fr;
    gap: .45rem 1rem;
    margin: 0;
}
.cg-rd__list dt { font-weight: 600; color: var(--cg-muted); }
.cg-rd__list dd { margin: 0; font-weight: 600; color: var(--cg-ink); word-break: break-word; }
.cg-rd__list a { color: var(--cg-accent-dark); }
.cg-rd__muted { color: var(--cg-muted); font-weight: 500; }
.cg-rd__note { margin: .75rem 0 0; font-size: .85rem; color: var(--cg-stone); }

.cg-rd__chip {
    display: inline-flex;
    align-items: center;
    gap: .3rem;
    margin: 0 .3rem .2rem 0;
    padding: .1rem .55rem;
    border-radius: 999px;
    background: var(--cg-accent-soft);
    font-size: .82rem;
    color: var(--cg-accent-dark);
}
.cg-rd__chip small { color: var(--cg-muted); }
.cg-rd__chip.is-warn { background: #fff3cd; color: #8a6100; }
.cg-rd__guide { display: flex; flex-direction: column; margin-bottom: .35rem; }
.cg-rd__guide a { font-weight: 500; font-size: .88rem; }
.cg-rd__star { color: #e0a100; }
.cg-rd__state { display: flex; align-items: center; justify-content: space-between; gap: .75rem; padding: .5rem 0; border-bottom: 1px solid var(--cg-line); }
.cg-rd__state > div { display: flex; flex-wrap: wrap; align-items: center; gap: .3rem .5rem; }
.cg-rd__state strong { min-width: 3.5rem; color: var(--cg-ink); }
.cg-rd__fee { font-weight: 700; color: var(--cg-ink); }
.cg-rd__chip.is-ok { background: #e3f5ec; color: #1f7a4d; }
.cg-rd__guidecard { grid-column: 1 / -1; }
.cg-rd__incharge { display: flex; flex-wrap: wrap; align-items: center; gap: .3rem .6rem; margin-bottom: .6rem; }
.cg-rd__incharge strong { font-size: 1.05rem; color: var(--cg-ink); }
.cg-rd__incharge small { flex-basis: 100%; }
.cg-rd__switch { padding-top: .6rem; border-top: 1px solid var(--cg-line); }
.cg-rd__switch-row { display: flex; gap: .5rem; }
.cg-rd__ghist { list-style: none; margin: .75rem 0 0; padding: .6rem 0 0; border-top: 1px dashed var(--cg-line); font-size: .85rem; }
.cg-rd__ghist li { display: flex; flex-direction: column; margin-bottom: .4rem; }
.cg-rd__ghist strong { color: var(--cg-ink-2); }
.cg-rd__ghist span { color: var(--cg-muted); font-size: .8rem; }
.cg-rd__ghist em { color: var(--cg-stone); }

.cg-rd__message {
    margin: 0;
    white-space: pre-wrap;
    line-height: 1.55;
    color: var(--cg-ink);
}

.cg-rd__timeline { list-style: none; margin: 0; padding: 0; }
.cg-rd__timeline li {
    position: relative;
    display: flex;
    flex-direction: column;
    padding: 0 0 .85rem 1.6rem;
}
.cg-rd__timeline li:last-child { padding-bottom: 0; }
.cg-rd__timeline li::before {
    content: "";
    position: absolute;
    left: 0;
    top: .2rem;
    width: .9rem;
    height: .9rem;
    border-radius: 50%;
    border: 2px solid var(--cg-line);
    background: var(--cg-surface, #fff);
}
.cg-rd__timeline li:not(:last-child)::after {
    content: "";
    position: absolute;
    left: calc(.45rem - 1px);
    top: 1.15rem;
    bottom: .05rem;
    width: 2px;
    background: var(--cg-line);
}
.cg-rd__timeline li.is-done::before { border-color: #2fa36b; background: #2fa36b; }
.cg-rd__timeline li.is-wait::before { border-color: #e0a100; }
.cg-rd__timeline li.is-skip::before { border-style: dashed; }
.cg-rd__timeline strong { color: var(--cg-ink); }
.cg-rd__timeline span { font-size: .85rem; color: var(--cg-stone); }
.cg-rd__timeline em { font-style: normal; color: var(--cg-muted); }

.cg-rd__actions {
    display: flex;
    flex-wrap: wrap;
    gap: .5rem;
    margin-top: 1rem;
    padding-top: 1rem;
    border-top: 1px solid var(--cg-line);
}
.cg-rd__confirm {
    display: flex;
    flex-wrap: wrap;
    align-items: center;
    justify-content: space-between;
    gap: .5rem 1rem;
    margin-top: .75rem;
    padding: .75rem 1rem;
    border: 1px solid #f1b0b0;
    border-radius: var(--cg-r);
    background: #fdf0f0;
}
.cg-rd__confirm p { margin: 0; }
.cg-rd__confirm div { display: flex; gap: .5rem; }

.cg-rd__empty { padding: 2rem 1rem; text-align: center; color: var(--cg-muted); }
.cg-rd__empty .fa { font-size: 2rem; }

@media (max-width: 767.98px) {
    .cg-rd__grid { grid-template-columns: 1fr; }
    .cg-rd__title { font-size: 1.3rem; }
}
@media (max-width: 420px) {
    .cg-rd__list { grid-template-columns: 1fr; gap: .05rem; }
    .cg-rd__list dd { margin-bottom: .45rem; }
    .cg-rd__actions .btn { flex: 1 1 100%; }
    .cg-rd__actions .ms-auto { margin-left: 0 !important; }
}
</style>
