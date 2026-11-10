<template>
    <!-- "Manage tour & guides" (tours list → guides button). Guides of the tour with the
         GENERAL guide (new reservations go to them), add / remove / make general, hand the
         tour over to another guide, and switch the guide of each open reservation.
         docs/FUNCTIONS_DOCS/shop/tour_guides.md -->
    <stack-modal
        :show="is_modal_show"
        :title="$t('admin.tour_guides.title')"
        size="xl"
        @close="close_modal"
        :saveButton="{ visible: false }"
        :cancelButton="{ visible: true, title: $t('common.close'), btnClass: { 'btn btn-secondary': true } }"
    >
        <div class="tg">
            <header class="tg__head">
                <div>
                    <h3>{{ tour_title || ('#' + tour_id) }}</h3>
                    <p>{{ $t('admin.tour_guides.subtitle') }}</p>
                </div>
                <div v-if="loading" class="spinner-border spinner-border-sm text-secondary" role="status"></div>
            </header>

            <div v-if="error_msg" class="alert alert-danger py-2">{{ error_msg }}</div>
            <div v-if="done_msg" class="alert alert-success py-2">{{ done_msg }}</div>

            <div class="tg__grid">
                <!-- guides -->
                <section class="tg__card">
                    <h4><i class="fa fa-users" aria-hidden="true"></i> {{ $t('admin.tour_guides.guides') }} <span class="tg__count">{{ guides.length }} / 4</span></h4>

                    <p v-if="!loading && !guides.length" class="tg__muted">{{ $t('admin.tour_guides.no_guides') }}</p>

                    <ul class="tg__guides">
                        <li v-for="g in guides" :key="g.user.id" class="tg__guide" :class="{ 'is-main': g.is_main }">
                            <span class="tg__avatar" :style="avatar(g.user)"></span>
                            <div class="tg__who">
                                <strong>{{ g.user.name }} {{ g.user.surname }}</strong>
                                <span v-if="g.is_main" class="tg__badge"><i class="fa fa-star" aria-hidden="true"></i> {{ $t('admin.tour_guides.general') }}</span>
                                <small>
                                    <a v-if="g.user.email" :href="'mailto:' + g.user.email">{{ g.user.email }}</a>
                                    <template v-if="g.user.phone_number"> · <a :href="'tel:' + g.user.phone_number">{{ g.user.phone_number }}</a></template>
                                </small>
                                <small class="tg__muted">{{ $t('admin.tour_guides.open_n', { n: g.open_reservations, all: g.all_reservations }) }}</small>
                            </div>
                            <div class="tg__actions" v-if="$can('edit', 'tour')">
                                <button v-if="!g.is_main" type="button" class="btn btn-sm btn-outline-primary" :disabled="busy" @click="make_main(g)" :title="$t('admin.tour_guides.make_general_hint')">
                                    <i class="fa fa-star-o" aria-hidden="true"></i> {{ $t('admin.tour_guides.make_general') }}
                                </button>
                                <button type="button" class="btn btn-sm btn-outline-danger" :disabled="busy" @click="remove_guide(g)" :title="$t('common.delete')">
                                    <i class="fa fa-times" aria-hidden="true"></i>
                                </button>
                            </div>
                        </li>
                    </ul>

                    <!-- add a guide -->
                    <div v-if="$can('edit', 'tour')" class="tg__add">
                        <div v-if="guides.length >= 4" class="alert alert-warning py-2 mb-0">{{ $t('admin.tour.max_guides_reached_msg') }}</div>
                        <template v-else>
                            <label class="form-label">{{ $t('admin.tour_guides.add') }}</label>
                            <input v-model="search" type="search" class="form-control form-control-sm mb-2" :placeholder="$t('admin.tour_guides.search_ph')">
                            <div class="tg__add-row">
                                <select v-model="selected_user_id" class="form-select form-select-sm">
                                    <option value="">{{ $t('admin.tour.select_user_em_dash_placeholder') }}</option>
                                    <option v-for="u in available_users" :key="u.id" :value="u.id">{{ u.name }} {{ u.surname }} (#{{ u.id }})</option>
                                </select>
                                <button type="button" class="btn btn-sm btn-primary" :disabled="!selected_user_id || busy" @click="add_guide">
                                    <i class="fa fa-plus" aria-hidden="true"></i> {{ $t('admin.orders.add_btn') }}
                                </button>
                            </div>
                            <small class="tg__muted">{{ guides.length ? $t('admin.tour_guides.add_hint') : $t('admin.tour_guides.add_first_hint') }} {{ $t('admin.tour_guides.only_guides_hint') }}</small>
                        </template>
                    </div>
                </section>

                <!-- hand over -->
                <section v-if="$can('edit', 'tour') && guides.length > 1" class="tg__card">
                    <h4><i class="fa fa-exchange" aria-hidden="true"></i> {{ $t('admin.tour_guides.handover') }}</h4>
                    <p class="tg__muted">{{ $t('admin.tour_guides.handover_help', { name: main_name }) }}</p>
                    <select v-model="handover.user_id" class="form-select form-select-sm mb-2">
                        <option value="">{{ $t('admin.tour_guides.choose_guide') }}</option>
                        <option v-for="g in guides.filter(x => !x.is_main)" :key="g.user.id" :value="g.user.id">{{ g.user.name }} {{ g.user.surname }}</option>
                    </select>
                    <div class="form-check">
                        <input id="tg_move" v-model="handover.move" class="form-check-input" type="checkbox">
                        <label for="tg_move" class="form-check-label">{{ $t('admin.tour_guides.move_reservations', { name: main_name, n: main_open }) }}</label>
                    </div>
                    <button type="button" class="btn btn-sm btn-warning" :disabled="!handover.user_id || busy" @click="do_handover">
                        <i class="fa fa-exchange" aria-hidden="true"></i> {{ $t('admin.tour_guides.handover_btn') }}
                    </button>
                    <p class="tg__mails"><i class="fa fa-envelope-o" aria-hidden="true"></i> {{ $t('admin.tour_guides.mails_hint') }}</p>
                </section>
            </div>

            <!-- open reservations: switch the guide in charge -->
            <section v-if="$can('show', 'tour_reservation')" class="tg__card tg__res">
                <h4><i class="fa fa-calendar" aria-hidden="true"></i> {{ $t('admin.tour_guides.open_reservations') }}</h4>
                <p v-if="!reservations.length" class="tg__muted mb-0">{{ $t('admin.tour_guides.no_open') }}</p>
                <div v-else class="table-responsive">
                    <table class="table table-sm align-middle mb-0">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>{{ $t('admin.tour.check_in_col') }}</th>
                                <th>{{ $t('common.name') }}</th>
                                <th>{{ $t('admin.tour.persons_col') }}</th>
                                <th>{{ $t('shop.tour.lang.label') }}</th>
                                <th>{{ $t('admin.tour.verified_col') }}</th>
                                <th>{{ $t('admin.tour_guides.guide_in_charge') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="r in reservations" :key="r.id">
                                <td>{{ r.id }}</td>
                                <td class="text-nowrap">{{ r.check_in ? String(r.check_in).slice(0, 10) : '—' }}</td>
                                <td>{{ r.name }}</td>
                                <td>{{ r.persons }}</td>
                                <td>{{ r.tour_language ? $t('shop.tour.lang.' + r.tour_language) : '—' }}</td>
                                <td><i class="fa" :class="Number(r.verificate) === 1 ? 'fa-check text-success' : 'fa-hourglass-half text-warning'" aria-hidden="true"></i></td>
                                <td style="min-width: 190px">
                                    <select class="form-select form-select-sm" :value="r.guide_id || ''" :disabled="busy || !$can('edit', 'tour_reservation')" @change="switch_guide(r, $event.target.value)">
                                        <option value="" disabled>—</option>
                                        <option v-for="g in guides" :key="g.user.id" :value="g.user.id">{{ g.user.name }} {{ g.user.surname }}{{ g.is_main ? ' ★' : '' }}</option>
                                    </select>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <p class="tg__mails"><i class="fa fa-info-circle" aria-hidden="true"></i> {{ $t('admin.tour_fee.rule') }}</p>
            </section>

            <!-- to settle: past trips not marked completed, unpaid climbing.ge fees -->
            <section v-if="$can('show', 'tour_reservation')" class="tg__card tg__res mt-3">
                <h4><i class="fa fa-flag-checkered" aria-hidden="true"></i> {{ $t('admin.tour_fee.to_settle') }} <span v-if="settle.length" class="tg__count">{{ settle.length }}</span></h4>
                <p v-if="!settle.length" class="tg__muted mb-0"><i class="fa fa-check text-success" aria-hidden="true"></i> {{ $t('admin.tour_fee.nothing_to_settle') }}</p>
                <div v-else class="table-responsive">
                    <table class="table table-sm align-middle mb-0">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>{{ $t('admin.tour.check_in_col') }}</th>
                                <th>{{ $t('common.name') }}</th>
                                <th>{{ $t('admin.tour_guides.guide_in_charge') }}</th>
                                <th>{{ $t('admin.tour_fee.fee') }}</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="r in settle" :key="r.id">
                                <td>{{ r.id }}</td>
                                <td class="text-nowrap">{{ r.check_in ? String(r.check_in).slice(0, 10) : '—' }}</td>
                                <td>{{ r.name }}</td>
                                <td>{{ r.guide_name || '—' }}</td>
                                <td class="text-nowrap">
                                    <template v-if="r.commission_percent">
                                        {{ r.commission_amount !== null ? money(r.commission_amount, r.price_currency) : r.commission_percent + '%' }}
                                        <span :class="r.commission_paid_at ? 'text-success' : 'text-danger'">· {{ r.commission_paid_at ? $t('admin.tour_fee.paid') : $t('admin.tour_fee.unpaid') }}</span>
                                    </template>
                                    <span v-else class="tg__muted">{{ $t('admin.tour_fee.no_fee_short') }}</span>
                                </td>
                                <td class="text-nowrap text-end">
                                    <button v-if="!r.completed_at && $can('edit', 'tour_reservation')" type="button" class="btn btn-sm btn-success" :disabled="busy" @click="set_status(r, { completed: true })">{{ $t('admin.tour_fee.mark_completed') }}</button>
                                    <button v-if="r.commission_percent && !r.commission_paid_at && $can('edit', 'tour_reservation')" type="button" class="btn btn-sm btn-warning ms-1" :disabled="busy" @click="set_status(r, { commission_paid: true })">{{ $t('admin.tour_fee.mark_paid') }}</button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </section>
        </div>
    </stack-modal>
</template>

<script>
import { money } from '../../../../../services/shop/tourPrice.js'

export default {
    name: 'TourGuideRelationModalComponent',
    emits: ['updated'],
    data() {
        return {
            is_modal_show: false,
            tour_id: null,
            tour_title: '',
            guides: [],
            reservations: [],
            settle: [],
            all_users: [],
            search: '',
            selected_user_id: '',
            loading: false,
            busy: false,
            error_msg: '',
            done_msg: '',
            notify_on_switch: true,
            handover: { user_id: '', move: true, notify: true },
        }
    },
    computed: {
        available_users() {
            const taken = new Set(this.guides.map(g => g.user.id));
            const q = this.search.trim().toLowerCase();
            return this.all_users
                .filter(u => !taken.has(u.id))
                .filter(u => !q || (`${u.name || ''} ${u.surname || ''} ${u.id}`).toLowerCase().includes(q))
                .slice(0, 200);
        },
        main() { return this.guides.find(g => g.is_main) || null; },
        main_name() { return this.main ? `${this.main.user.name} ${this.main.user.surname}` : '—'; },
        main_open() { return this.main ? this.main.open_reservations : 0; },
    },
    methods: {
        show_modal(tour_id, tour_title = '') {
            Object.assign(this, { tour_id, tour_title, error_msg: '', done_msg: '', selected_user_id: '', search: '', handover: { user_id: '', move: true, notify: true } });
            this.is_modal_show = true;
            this.load_data();
        },
        close_modal() {
            this.is_modal_show = false;
            this.tour_id = null;
            this.guides = [];
            this.reservations = [];
        },
        avatar(u) {
            return u.image
                ? 'background-image:url(/public/images/user_profil_img/' + u.image + '), var(--cg-topo-dark)'
                : 'background-image:url(/public/images/site_img/demo_imgs/user_demo_img.gif)';
        },
        load_data() {
            this.loading = true;
            const calls = [axios.get('/set_tour/get_tour_users/' + this.tour_id)];
            if (this.$can('show', 'tour_reservation')) calls.push(axios.get('/set_tour/get_tour_open_reservations/' + this.tour_id));
            Promise.all(calls)
                .then(([g, r]) => {
                    this.guides = g.data || [];
                    const d = (r && r.data) || {};
                    this.reservations = Array.isArray(d) ? d : (d.open || []);
                    this.settle = Array.isArray(d) ? [] : (d.settle || []);
                })
                .catch(() => { this.error_msg = this.$t('admin.tour.failed_load_data'); })
                .finally(() => { this.loading = false; });
            if (this.$can('edit', 'tour') && !this.all_users.length) {
                // only users with the Guide role (tour_guide permission), banned excluded
                axios.get('/set_tour/get_guide_candidates').then(u => { this.all_users = u.data || []; }).catch(() => {});
            }
        },
        // every write: busy flag, message, reload, tell the list
        run(promise, ok) {
            this.busy = true;
            this.error_msg = '';
            this.done_msg = '';
            return promise
                .then(res => {
                    this.done_msg = typeof ok === 'function' ? ok(res.data || {}) : ok;
                    this.load_data();
                    this.$emit('updated');
                })
                .catch(err => { this.error_msg = (err.response && err.response.data && (err.response.data.error || err.response.data.message)) || this.$t('admin.tour_guides.failed'); })
                .finally(() => { this.busy = false; });
        },
        add_guide() {
            if (!this.selected_user_id) return;
            this.run(axios.post('/set_tour/add_tour_user/' + this.tour_id, { user_id: this.selected_user_id }), this.$t('admin.tour_guides.added'))
                .then(() => { this.selected_user_id = ''; });
        },
        make_main(g) {
            this.run(axios.post('/set_tour/set_main_guide/' + this.tour_id, { user_id: g.user.id }), this.$t('admin.tour_guides.now_general', { name: g.user.name }));
        },
        remove_guide(g) {
            const msg = g.is_main ? this.$t('admin.tour_guides.confirm_remove_main', { name: g.user.name }) : this.$t('admin.tour_guides.confirm_remove', { name: g.user.name });
            if (!confirm(msg)) return;
            this.run(axios.delete('/set_tour/remove_tour_user/' + this.tour_id + '/' + g.user.id), d => this.$t('admin.tour_guides.removed', { n: d.moved || 0 }));
        },
        do_handover() {
            const g = this.guides.find(x => String(x.user.id) === String(this.handover.user_id));
            if (!g || !confirm(this.$t('admin.tour_guides.confirm_handover', { name: g.user.name }))) return;
            this.run(axios.post('/set_tour/handover_tour/' + this.tour_id, {
                user_id: g.user.id, move_reservations: this.handover.move, notify_clients: false,
            }), d => this.$t('admin.tour_guides.handed_over', { name: g.user.name, n: d.moved || 0 }));
        },
        money(v, cur) { return money(v, cur); },
        set_status(r, payload) {
            this.run(axios.post('/set_tour/set_reservation/set_status/' + r.id, payload), this.$t('admin.tour_fee.saved'));
        },
        switch_guide(r, user_id) {
            if (!user_id || String(user_id) === String(r.guide_id)) return;
            this.run(axios.post('/set_tour/set_reservation/assign_guide/' + r.id, { user_id, notify_client: false }), this.$t('admin.tour_guides.switched', { id: r.id }));
        },
    }
}
</script>

<style scoped>
.tg__head { display: flex; justify-content: space-between; align-items: flex-start; gap: 1rem; margin-bottom: 1rem; }
.tg__head h3 { margin: 0 0 .2rem; font-family: var(--cg-font-display); font-size: 1.5rem; color: var(--cg-ink); }
.tg__head p { margin: 0; color: var(--cg-stone); font-size: .9rem; }
.tg__grid { display: grid; grid-template-columns: minmax(0, 3fr) minmax(0, 2fr); gap: .9rem; align-items: start; margin-bottom: .9rem; }
.tg__card { padding: 1rem 1.1rem; border: 1px solid var(--cg-line); border-radius: var(--cg-r); background: var(--cg-surface, #fff); }
.tg__card h4 { display: flex; align-items: center; gap: .45rem; margin: 0 0 .75rem; font-size: .8rem; font-weight: 800; letter-spacing: .04em; text-transform: uppercase; color: var(--cg-stone); }
.tg__card h4 .fa { color: var(--cg-accent-dark); }
.tg__count { margin-left: auto; padding: 0 .5rem; border-radius: 999px; background: var(--cg-chalk-2); font-size: .72rem; color: var(--cg-ink-2); }
.tg__muted { color: var(--cg-muted); font-size: .85rem; }
.tg__guides { list-style: none; margin: 0 0 .75rem; padding: 0; }
.tg__guide { display: flex; align-items: center; gap: .75rem; padding: .6rem .7rem; margin-bottom: .5rem; border: 1px solid var(--cg-line); border-radius: var(--cg-r-sm, .5rem); }
.tg__guide.is-main { border-color: #f0c45a; background: #fffaeb; }
.tg__avatar { flex: 0 0 42px; width: 42px; height: 42px; border-radius: 50%; background-size: cover; background-position: center; }
.tg__who { display: flex; flex-direction: column; min-width: 0; flex: 1; }
.tg__who strong { color: var(--cg-ink); }
.tg__who small { overflow: hidden; text-overflow: ellipsis; }
.tg__badge { align-self: flex-start; margin: .1rem 0; padding: 0 .5rem; border-radius: 999px; background: #ffc233; color: #5a4300; font-size: .72rem; font-weight: 800; }
.tg__actions { display: flex; gap: .35rem; flex-wrap: wrap; justify-content: flex-end; }
.tg__add { padding-top: .75rem; border-top: 1px solid var(--cg-line); }
.tg__add-row { display: flex; gap: .5rem; margin-bottom: .3rem; }
.tg__mails { margin: .6rem 0 0; font-size: .78rem; color: var(--cg-muted); }
@media (max-width: 767.98px) { .tg__grid { grid-template-columns: 1fr; } .tg__guide { flex-wrap: wrap; } }
</style>
