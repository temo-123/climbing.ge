<template>
    <StackModal v-model="is_open" :title="rental_id ? $t('admin.gear_rental.edit_rental_title', { id: rental_id }) : $t('admin.gear_rental.new_rental')" size="xl" @close="close">
        <div v-if="loading" class="text-center py-4"><span class="spinner-border text-primary" aria-hidden="true"></span></div>

        <form v-else class="rf" @submit.prevent="save" novalidate>
            <div v-if="general_error" class="alert alert-danger">{{ general_error }}</div>

            <!-- 1. renter -->
            <section class="rf__section">
                <h3 class="rf__title"><span>1</span> {{ $t('admin.gear_rental.renter_title') }}</h3>

                <div class="rf__search">
                    <label class="form-label" for="rf_user_search">{{ $t('admin.gear_rental.find_user_label') }}</label>
                    <div class="position-relative">
                        <input id="rf_user_search" type="search" class="form-control" v-model.trim="user_query" autocomplete="off"
                               :placeholder="$t('admin.gear_rental.find_user_placeholder')" @input="search_users">
                        <ul v-if="user_results.length" class="rf__results list-group">
                            <li v-for="u in user_results" :key="u.id">
                                <button type="button" class="list-group-item list-group-item-action" @click="pick_user(u)">
                                    <strong>{{ u.name }} {{ u.surname }}</strong> <span class="text-muted">#{{ u.id }}</span>
                                    <small class="d-block text-muted">{{ u.email }}<span v-if="u.phone_number"> · {{ u.phone_number }}</span></small>
                                </button>
                            </li>
                        </ul>
                    </div>
                    <div v-if="form.user_id" class="rf__linked">
                        <i class="fa fa-link" aria-hidden="true"></i>
                        {{ $t('admin.gear_rental.linked_to_user', { id: form.user_id }) }}
                        <button type="button" class="btn btn-link btn-sm p-0 ms-2" @click="form.user_id = null">{{ $t('admin.gear_rental.unlink_user') }}</button>
                    </div>
                    <small v-else class="form-text text-muted">{{ $t('admin.gear_rental.auto_link_hint') }}</small>
                </div>

                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">{{ $t('common.name') }} *</label>
                        <input type="text" class="form-control" :class="{ 'is-invalid': err('name') }" v-model.trim="form.name" required maxlength="120">
                        <div class="invalid-feedback">{{ err('name') }}</div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">{{ $t('common.surname') }} *</label>
                        <input type="text" class="form-control" :class="{ 'is-invalid': err('surname') }" v-model.trim="form.surname" required maxlength="120">
                        <div class="invalid-feedback">{{ err('surname') }}</div>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">{{ $t('admin.gear_rental.phone_col') }} *</label>
                        <input type="tel" class="form-control" :class="{ 'is-invalid': err('phone') }" v-model.trim="form.phone" required maxlength="50">
                        <div class="invalid-feedback">{{ err('phone') }}</div>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">{{ $t('common.email') }}</label>
                        <input type="email" class="form-control" :class="{ 'is-invalid': err('email') }" v-model.trim="form.email" maxlength="255">
                        <div class="invalid-feedback">{{ err('email') }}</div>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">{{ $t('admin.gear_rental.id_number') }}</label>
                        <input type="text" class="form-control" :class="{ 'is-invalid': err('id_number') }" v-model.trim="form.id_number" maxlength="60">
                        <div class="invalid-feedback">{{ err('id_number') }}</div>
                    </div>
                    <div class="col-12">
                        <label class="form-label d-block">{{ $t('admin.gear_rental.id_card') }}</label>
                        <div v-if="has_id_card && !replace_id_card" class="rf__idcard">
                            <IdCardImage :rental_id="rental_id" />
                            <button type="button" class="btn btn-sm btn-outline-secondary mt-2" @click="replace_id_card = true">
                                <i class="fa fa-refresh" aria-hidden="true"></i> {{ $t('admin.gear_rental.id_card_replace') }}
                            </button>
                        </div>
                        <IdCardCapture v-else ref="capture" @update:file="id_card_file = $event" />
                        <small class="form-text text-muted d-block">{{ $t('admin.gear_rental.id_card_privacy') }}</small>
                        <div v-if="err('id_card')" class="text-danger small">{{ err('id_card') }}</div>
                    </div>
                </div>
            </section>

            <!-- 2. period -->
            <section class="rf__section">
                <h3 class="rf__title"><span>2</span> {{ $t('admin.gear_rental.period_title') }}</h3>
                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label">{{ $t('admin.gear_rental.warehouse') }} *</label>
                        <select class="form-select" :class="{ 'is-invalid': err('rental_warehouse_id') }" v-model="form.rental_warehouse_id" @change="quote">
                            <option v-for="w in warehouses" :key="w.id" :value="w.id">{{ w.name }}<template v-if="w.general"> ({{ $t('admin.gear_rental.general') }})</template></option>
                        </select>
                        <div class="invalid-feedback">{{ err('rental_warehouse_id') }}</div>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">{{ $t('admin.gear_rental.start_label') }} *</label>
                        <input type="datetime-local" class="form-control" :class="{ 'is-invalid': err('start_at') }" v-model="form.start_at">
                        <div class="invalid-feedback">{{ err('start_at') }}</div>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">{{ $t('admin.gear_rental.days_label') }} *</label>
                        <input type="number" min="1" max="365" class="form-control" :class="{ 'is-invalid': err('days') }" v-model.number="form.days" @change="quote">
                        <div class="invalid-feedback">{{ err('days') }}</div>
                    </div>
                    <div class="col-12">
                        <div class="rf__chips" role="group" :aria-label="$t('admin.gear_rental.days_label')">
                            <button v-for="d in presets" :key="d" type="button" class="btn btn-sm" :class="form.days === d ? 'btn-primary' : 'btn-outline-secondary'" @click="set_days(d)">
                                {{ period_label(d) }}
                            </button>
                        </div>
                        <div class="rf__due" v-if="due_label">
                            <i class="fa fa-calendar-check-o" aria-hidden="true"></i> {{ $t('admin.gear_rental.due_back', { date: due_label }) }}
                        </div>
                    </div>
                </div>
            </section>

            <!-- 3. gear -->
            <section class="rf__section">
                <h3 class="rf__title"><span>3</span> {{ $t('admin.gear_rental.gear_title') }}</h3>
                <div v-if="err('items')" class="alert alert-danger py-2">{{ err('items') }}</div>

                <div class="table-responsive">
                    <table class="table align-middle rf__lines">
                        <thead>
                            <tr>
                                <th>{{ $t('admin.gear_rental.gear_col') }}</th>
                                <th class="rf__qty">{{ $t('admin.gear_rental.qty_col') }}</th>
                                <th class="rf__price">{{ $t('admin.gear_rental.unit_price_col', { period: period_label(form.days || 1) }) }}</th>
                                <th class="text-end">{{ $t('admin.gear_rental.line_total_col') }}</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(line, i) in form.items" :key="line.key">
                                <td>
                                    <select class="form-select" v-model="line.gear_id" @change="on_gear_change(line)">
                                        <option :value="null" disabled>{{ $t('admin.gear_rental.choose_gear') }}</option>
                                        <option v-for="g in gear" :key="g.id" :value="g.id">
                                            {{ g.us_name }}{{ g.category ? ' · ' + g.category : '' }} — {{ $t('admin.gear_rental.available_n', { n: available_for(g.id, line) }) }}
                                        </option>
                                    </select>
                                    <small v-if="quote_line(i) && quote_line(i).suggested_price === null" class="text-warning d-block">{{ $t('admin.gear_rental.no_price_for_period') }}</small>
                                </td>
                                <td>
                                    <input type="number" min="1" class="form-control" :class="{ 'is-invalid': err('items.' + i + '.quantity') }" v-model.number="line.quantity" @change="quote">
                                    <div class="invalid-feedback">{{ err('items.' + i + '.quantity') }}</div>
                                </td>
                                <td>
                                    <div class="input-group">
                                        <input type="number" min="0" step="0.01" class="form-control" :class="{ 'is-invalid': err('items.' + i + '.unit_price') }" v-model="line.unit_price"
                                               :placeholder="quote_line(i) && quote_line(i).suggested_price !== null ? String(quote_line(i).suggested_price) : ''" @change="quote">
                                        <span class="input-group-text">₾</span>
                                        <div class="invalid-feedback">{{ err('items.' + i + '.unit_price') }}</div>
                                    </div>
                                    <small v-if="line.unit_price !== '' && line.unit_price !== null && quote_line(i) && quote_line(i).suggested_price !== null" class="text-muted">
                                        {{ $t('admin.gear_rental.list_price', { price: money(quote_line(i).suggested_price) }) }}
                                    </small>
                                </td>
                                <td class="text-end fw-semibold">{{ quote_line(i) ? money(quote_line(i).line_total) : '—' }}</td>
                                <td class="text-end">
                                    <button type="button" class="btn btn-sm btn-outline-danger" @click="remove_line(i)" :aria-label="$t('common.delete')"><i class="fa fa-times" aria-hidden="true"></i></button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <button type="button" class="btn btn-outline-primary btn-sm" @click="add_line()">
                    <i class="fa fa-plus" aria-hidden="true"></i> {{ $t('admin.gear_rental.add_gear_line') }}
                </button>
                <span v-if="!gear.length" class="text-muted small ms-2">{{ $t('admin.gear_rental.no_gear_yet') }}</span>
            </section>

            <!-- 4. money -->
            <section class="rf__section">
                <h3 class="rf__title"><span>4</span> {{ $t('admin.gear_rental.money_title') }}</h3>
                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label">{{ $t('admin.gear_rental.discount') }}</label>
                        <div class="input-group">
                            <input type="number" min="0" step="0.01" class="form-control" v-model="form.discount">
                            <span class="input-group-text">₾</span>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">{{ $t('admin.gear_rental.deposit') }}</label>
                        <div class="input-group">
                            <input type="number" min="0" step="0.01" class="form-control" v-model="form.deposit" :placeholder="suggested_deposit ? String(suggested_deposit) : ''" @input="deposit_touched = true">
                            <span class="input-group-text">₾</span>
                        </div>
                        <small v-if="suggested_deposit" class="text-muted">{{ $t('admin.gear_rental.suggested_deposit', { amount: money(suggested_deposit) }) }}</small>
                    </div>
                    <div class="col-md-4">
                        <div class="rf__totals">
                            <div><span>{{ $t('admin.gear_rental.subtotal') }}</span><strong>{{ money(subtotal) }}</strong></div>
                            <div v-if="discount_num"><span>{{ $t('admin.gear_rental.discount') }}</span><strong>−{{ money(discount_num) }}</strong></div>
                            <div class="rf__total"><span>{{ $t('admin.gear_rental.total_col') }}</span><strong>{{ money(total) }}</strong></div>
                        </div>
                    </div>
                    <!-- paid for the rental period now (can also be marked later, or on return) -->
                    <div class="col-12">
                        <div class="rf__paid" :class="{ 'is-on': form.paid }">
                            <div class="form-check form-switch mb-0">
                                <input id="rf_paid" class="form-check-input" type="checkbox" role="switch" v-model="form.paid">
                                <label for="rf_paid" class="form-check-label fw-semibold">
                                    {{ $t('admin.gear_rental.paid_for_period', { amount: money(total), period: period_label(form.days || 1) }) }}
                                </label>
                            </div>
                            <small class="text-muted">{{ form.paid ? $t('admin.gear_rental.paid_for_period_on') : $t('admin.gear_rental.paid_for_period_off') }}</small>
                        </div>
                    </div>
                    <div class="col-12">
                        <label class="form-label">{{ $t('admin.gear_rental.notes') }}</label>
                        <textarea class="form-control" rows="2" v-model="form.notes" maxlength="5000"></textarea>
                    </div>
                </div>
            </section>
        </form>

        <template #footer>
            <div class="modal-footer">
                <button type="button" class="btn btn-light me-auto" @click="close">{{ $t('common.cancel') }}</button>
                <button type="button" class="btn btn-primary" :disabled="saving || loading" @click="save">
                    <span v-if="saving" class="spinner-border spinner-border-sm" aria-hidden="true"></span>
                    <i v-else class="fa fa-check" aria-hidden="true"></i>
                    {{ rental_id ? $t('common.save') : $t('admin.gear_rental.create_rental') }}
                </button>
            </div>
        </template>
    </StackModal>
</template>

<script>
import StackModal from '../../../global_components/modals/StackModal.vue'
import IdCardCapture from './IdCardCapture.vue'
import IdCardImage from './IdCardImage.vue'
import { rental_money, period_label, to_local_input, PERIOD_PRESETS } from './rentalFormat.js'

let line_seq = 0
const empty_line = () => ({ key: ++line_seq, gear_id: null, quantity: 1, unit_price: '' })

export default {
    name: 'RentalFormModal',
    components: { StackModal, IdCardCapture, IdCardImage },
    props: {
        warehouses: { type: Array, default: () => [] },
        gear: { type: Array, default: () => [] },
    },
    emits: ['saved'],
    data() {
        return {
            is_open: false,
            loading: false,
            saving: false,
            rental_id: null,
            has_id_card: false,
            replace_id_card: false,
            id_card_file: null,
            form: this.blank(),
            quote_data: null,
            errors: {},
            general_error: '',
            user_query: '',
            user_results: [],
            deposit_touched: false,
            presets: PERIOD_PRESETS,
        }
    },
    computed: {
        subtotal() { return this.quote_data ? this.quote_data.subtotal : 0 },
        discount_num() { return Math.max(0, Number(this.form.discount) || 0) },
        total() { return Math.max(0, this.subtotal - this.discount_num) },
        suggested_deposit() {
            if (!this.quote_data) return 0
            return this.quote_data.lines.reduce((s, l) => s + (Number(l.deposit) || 0) * l.quantity, 0)
        },
        due_label() {
            if (!this.form.start_at || !(this.form.days > 0)) return ''
            const d = new Date(this.form.start_at)
            if (isNaN(d.getTime())) return ''
            d.setDate(d.getDate() + Number(this.form.days))
            return d.toLocaleString(this.$i18n.locale === 'ka' ? 'ka-GE' : 'en-GB', { weekday: 'short', day: 'numeric', month: 'short', hour: '2-digit', minute: '2-digit' })
        },
    },
    methods: {
        money(v) { return rental_money(v) },
        period_label(d) { return period_label(this.$t, d) },
        blank() {
            const general = (this.warehouses || []).find(w => w.general) || (this.warehouses || [])[0]
            return {
                user_id: null, name: '', surname: '', email: '', phone: '', id_number: '',
                rental_warehouse_id: general ? general.id : null,
                start_at: to_local_input(new Date()),
                days: 1, discount: '', deposit: '', notes: '', paid: false,
                items: [empty_line()],
            }
        },
        open(id = null) {
            this.rental_id = id
            this.errors = {}
            this.general_error = ''
            this.user_query = ''
            this.user_results = []
            this.id_card_file = null
            this.replace_id_card = false
            this.has_id_card = false
            this.deposit_touched = false
            this.quote_data = null
            this.form = this.blank()
            this.is_open = true
            if (!id) { this.quote(); return }

            this.loading = true
            axios.get('/set_gear_rental/rental/' + id)
                .then(r => {
                    const x = r.data
                    this.has_id_card = !!x.has_id_card
                    this.deposit_touched = true
                    this.form = {
                        user_id: x.user_id, name: x.name, surname: x.surname, email: x.email || '', phone: x.phone, id_number: x.id_number || '',
                        rental_warehouse_id: x.rental_warehouse_id, start_at: to_local_input(x.start_at), days: x.days,
                        discount: x.discount || '', deposit: x.deposit || '', notes: x.notes || '', paid: !!x.paid_at,
                        items: (x.items || []).filter(i => i.rental_gear_id).map(i => ({ key: ++line_seq, gear_id: i.rental_gear_id, quantity: i.quantity, unit_price: i.unit_price })),
                    }
                    if (!this.form.items.length) this.form.items.push(empty_line())
                    this.quote()
                })
                .catch(() => { this.is_open = false })
                .finally(() => { this.loading = false })
        },
        close() {
            if (this.$refs.capture) this.$refs.capture.reset()
            this.is_open = false
        },
        err(key) {
            const e = this.errors[key]
            return e ? (Array.isArray(e) ? e[0] : e) : ''
        },
        set_days(d) { this.form.days = d; this.quote() },
        add_line() { this.form.items.push(empty_line()) },
        remove_line(i) {
            this.form.items.splice(i, 1)
            if (!this.form.items.length) this.form.items.push(empty_line())
            this.quote()
        },
        on_gear_change(line) {
            line.unit_price = ''   // the new gear's list price
            this.quote()
        },
        quote_line(i) {
            const line = this.form.items[i]
            if (!this.quote_data || !line || !line.gear_id) return null
            return this.quote_data.lines.find(l => l.gear_id === line.gear_id) || null
        },
        // free pieces in the chosen warehouse (this rental's own pieces count as free when editing)
        available_for(gear_id) {
            const g = this.gear.find(x => x.id === gear_id)
            if (!g) return 0
            const w = (g.warehouses || []).find(x => x.id === this.form.rental_warehouse_id)
            let n = w ? w.available : 0
            const q = this.quote_data && this.quote_data.lines.find(l => l.gear_id === gear_id)
            if (q) n = q.available
            return n
        },
        lines_payload() {
            return this.form.items.filter(l => l.gear_id).map(l => ({
                gear_id: l.gear_id,
                quantity: Math.max(1, Number(l.quantity) || 1),
                unit_price: l.unit_price === '' || l.unit_price === null ? null : Number(l.unit_price),
            }))
        },
        quote() {
            clearTimeout(this._quote_t)
            this._quote_t = setTimeout(() => {
                if (!(this.form.days > 0)) return
                axios.post('/set_gear_rental/quote', {
                    days: this.form.days,
                    rental_warehouse_id: this.form.rental_warehouse_id,
                    rental_id: this.rental_id,
                    items: this.lines_payload(),
                }, { _silent: true })
                    .then(r => {
                        this.quote_data = r.data
                        if (!this.deposit_touched) this.form.deposit = this.suggested_deposit ? String(this.suggested_deposit) : ''
                    })
                    .catch(() => {})
            }, 200)
        },
        search_users() {
            clearTimeout(this._search_t)
            if (this.user_query.length < 2) { this.user_results = []; return }
            this._search_t = setTimeout(() => {
                axios.get('/set_gear_rental/search_users', { params: { q: this.user_query }, _silent: true })
                    .then(r => { this.user_results = r.data || [] })
                    .catch(() => { this.user_results = [] })
            }, 250)
        },
        pick_user(u) {
            this.form.user_id = u.id
            this.form.name = u.name || this.form.name
            this.form.surname = u.surname || this.form.surname
            this.form.email = u.email || this.form.email
            this.form.phone = u.phone_number || this.form.phone
            this.user_results = []
            this.user_query = ''
        },
        save() {
            this.errors = {}
            this.general_error = ''
            const items = this.lines_payload()
            if (!items.length) { this.errors = { items: [this.$t('admin.gear_rental.need_gear')] }; return }

            const data = {
                ...this.form,
                start_at: this.form.start_at ? this.form.start_at.replace('T', ' ') + ':00' : null,
                discount: this.form.discount === '' ? 0 : Number(this.form.discount),
                deposit: this.form.deposit === '' ? 0 : Number(this.form.deposit),
                items,
            }
            delete data.key
            const fd = new FormData()
            fd.append('data', JSON.stringify(data))
            if (this.id_card_file) fd.append('id_card', this.id_card_file)

            this.saving = true
            const url = this.rental_id ? '/set_gear_rental/edit_rental/' + this.rental_id : '/set_gear_rental/add_rental'
            axios.post(url, fd, { headers: { 'Content-Type': 'multipart/form-data' } })
                .then(r => {
                    this.$bus.$emit('toast', { type: 'success', message: this.$t(this.rental_id ? 'admin.gear_rental.rental_saved' : 'admin.gear_rental.rental_created') })
                    if (this.$refs.capture) this.$refs.capture.reset()
                    this.is_open = false
                    this.$emit('saved', r.data)
                })
                .catch(e => {
                    if (e.response && e.response.status === 422) {
                        this.errors = e.response.data.errors || {}
                        this.general_error = Object.keys(this.errors).length ? this.$t('admin.gear_rental.fix_errors') : (e.response.data.message || '')
                    }
                })
                .finally(() => { this.saving = false })
        },
    },
}
</script>

<style scoped>
.rf__section { padding: 1rem 0; border-top: 1px solid var(--cg-line); }
.rf__section:first-of-type { border-top: 0; padding-top: 0; }
.rf__title { display: flex; align-items: center; gap: .5rem; font-size: 1rem; font-weight: 800; margin: 0 0 .85rem; }
.rf__title span {
    display: grid; place-items: center; width: 1.6rem; height: 1.6rem; border-radius: 50%;
    background: var(--cg-accent); color: #fff; font-size: .8rem;
}
.rf__search { margin-bottom: 1rem; }
.rf__results { position: absolute; z-index: 5; left: 0; right: 0; top: 100%; max-height: 260px; overflow-y: auto; box-shadow: var(--cg-shadow); padding: 0; list-style: none; }
.rf__results button { text-align: left; }
.rf__linked { margin-top: .4rem; font-size: .88rem; font-weight: 700; color: #2fa36b; }
.rf__chips { display: flex; flex-wrap: wrap; gap: .4rem; }
.rf__due { margin-top: .6rem; font-weight: 700; color: var(--cg-ink-2); }
.rf__lines { min-width: 640px; }
.rf__qty { width: 6.5rem; }
.rf__price { width: 12rem; }
.rf__totals { padding: .75rem 1rem; border-radius: var(--cg-r); background: var(--cg-chalk); }
.rf__totals div { display: flex; justify-content: space-between; gap: 1rem; }
.rf__paid { padding: .7rem 1rem; border: 1px solid var(--cg-line); border-radius: var(--cg-r-lg); background: #fff; }
.rf__paid.is-on { border-color: #b9e2cb; background: #f3fbf6; }
.rf__total { margin-top: .35rem; padding-top: .35rem; border-top: 1px solid var(--cg-line); font-size: 1.1rem; }
</style>
