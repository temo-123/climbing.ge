<template>
    <StackModal v-model="is_open" :title="r ? $t('admin.gear_rental.rental_title', { id: r.id }) : ''" size="xl" @close="is_open = false">
        <div v-if="loading && !r" class="text-center py-4"><span class="spinner-border text-primary" aria-hidden="true"></span></div>

        <div v-else-if="r" class="rd">
            <!-- status -->
            <div class="rd__status" :class="'is-' + state">
                <i class="fa" :class="state_icon" aria-hidden="true"></i>
                <div>
                    <strong>{{ state_title }}</strong>
                    <span>{{ state_text }}</span>
                </div>
                <span class="rd__badges">
                    <span class="badge" :class="r.is_paid ? 'text-bg-success' : 'text-bg-warning'">{{ r.is_paid ? $t('admin.gear_rental.paid') : $t('admin.gear_rental.unpaid') }}</span>
                    <span v-if="r.deposit" class="badge" :class="r.deposit_returned ? 'text-bg-secondary' : 'text-bg-info'">
                        {{ $t('admin.gear_rental.deposit') }} {{ money(r.deposit) }} · {{ r.deposit_returned ? $t('admin.gear_rental.deposit_returned') : $t('admin.gear_rental.deposit_held') }}
                    </span>
                </span>
            </div>

            <div class="row g-4">
                <!-- renter -->
                <div class="col-lg-5">
                    <h3 class="rd__h">{{ $t('admin.gear_rental.renter_title') }}</h3>
                    <dl class="rd__dl">
                        <dt>{{ $t('common.name') }}</dt><dd>{{ r.renter }}</dd>
                        <dt>{{ $t('admin.gear_rental.phone_col') }}</dt><dd><a :href="'tel:' + r.phone">{{ r.phone }}</a></dd>
                        <template v-if="r.email"><dt>{{ $t('common.email') }}</dt><dd class="text-break"><a :href="'mailto:' + r.email">{{ r.email }}</a></dd></template>
                        <template v-if="r.id_number"><dt>{{ $t('admin.gear_rental.id_number') }}</dt><dd>{{ r.id_number }}</dd></template>
                        <dt>{{ $t('admin.gear_rental.account') }}</dt>
                        <dd>
                            <span v-if="r.user" class="text-success fw-semibold"><i class="fa fa-link" aria-hidden="true"></i> {{ r.user.name }} {{ r.user.surname }} #{{ r.user.id }}</span>
                            <span v-else class="text-muted">{{ $t('admin.gear_rental.not_registered') }}</span>
                        </dd>
                    </dl>

                    <h3 class="rd__h">{{ $t('admin.gear_rental.id_card') }}</h3>
                    <IdCardImage v-if="r.has_id_card" :rental_id="r.id" :version="card_version" />
                    <p v-else class="text-muted small">{{ $t('admin.gear_rental.no_id_card') }}</p>
                    <div v-if="$can('edit', 'gear_rental')" class="mt-2">
                        <IdCardCapture v-if="new_card_open" ref="capture" @update:file="upload_card" />
                        <div v-else class="d-flex gap-2 flex-wrap">
                            <button type="button" class="btn btn-sm btn-outline-secondary" @click="new_card_open = true">
                                <i class="fa fa-camera" aria-hidden="true"></i> {{ r.has_id_card ? $t('admin.gear_rental.id_card_replace') : $t('admin.gear_rental.id_card_add') }}
                            </button>
                            <button v-if="r.has_id_card" type="button" class="btn btn-sm btn-outline-danger" @click="del_card">
                                <i class="fa fa-trash" aria-hidden="true"></i> {{ $t('admin.gear_rental.id_card_remove') }}
                            </button>
                        </div>
                    </div>
                </div>

                <!-- rental -->
                <div class="col-lg-7">
                    <h3 class="rd__h">{{ $t('admin.gear_rental.period_title') }}</h3>
                    <dl class="rd__dl">
                        <dt>{{ $t('admin.gear_rental.warehouse') }}</dt><dd>{{ r.warehouse_name || '—' }}</dd>
                        <dt>{{ $t('admin.gear_rental.start_label') }}</dt><dd>{{ date(r.start_at) }}</dd>
                        <dt>{{ $t('admin.gear_rental.days_label') }}</dt><dd>{{ period(r.days) }}</dd>
                        <dt>{{ $t('admin.gear_rental.due_col') }}</dt><dd :class="{ 'text-danger fw-bold': r.is_overdue }">{{ date(r.due_at) }}</dd>
                        <template v-if="r.returned_at"><dt>{{ $t('admin.gear_rental.returned_at') }}</dt><dd>{{ date(r.returned_at) }}</dd></template>
                        <template v-if="r.creator"><dt>{{ $t('admin.gear_rental.created_by') }}</dt><dd>{{ r.creator.name }} {{ r.creator.surname }} · {{ date(r.created_at) }}</dd></template>
                    </dl>

                    <h3 class="rd__h">{{ $t('admin.gear_rental.gear_title') }}</h3>
                    <div class="table-responsive">
                        <table class="table table-sm align-middle">
                            <thead>
                                <tr>
                                    <th>{{ $t('admin.gear_rental.gear_col') }}</th>
                                    <th class="text-end">{{ $t('admin.gear_rental.qty_col') }}</th>
                                    <th class="text-end">{{ $t('admin.gear_rental.unit_price_col', { period: period(r.days) }) }}</th>
                                    <th class="text-end">{{ $t('admin.gear_rental.line_total_col') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="i in r.items" :key="i.id">
                                    <td>{{ i.gear_name }}</td>
                                    <td class="text-end">{{ i.quantity }}</td>
                                    <td class="text-end">{{ money(i.unit_price) }}</td>
                                    <td class="text-end">{{ money(i.line_total) }}</td>
                                </tr>
                            </tbody>
                            <tfoot>
                                <tr v-if="r.discount"><td colspan="3" class="text-end">{{ $t('admin.gear_rental.discount') }}</td><td class="text-end">−{{ money(r.discount) }}</td></tr>
                                <tr><th colspan="3" class="text-end">{{ $t('admin.gear_rental.total_col') }}</th><th class="text-end">{{ money(r.total) }}</th></tr>
                            </tfoot>
                        </table>
                    </div>
                    <p v-if="r.notes" class="rd__notes"><i class="fa fa-sticky-note-o" aria-hidden="true"></i> {{ r.notes }}</p>

                    <!-- return -->
                    <div v-if="r.status === 'active' && $can('edit', 'gear_rental')" class="rd__return">
                        <h3 class="rd__h mb-2">{{ $t('admin.gear_rental.return_title') }}</h3>
                        <div class="form-check">
                            <input id="rd_paid" class="form-check-input" type="checkbox" v-model="return_paid" :disabled="r.is_paid">
                            <label for="rd_paid" class="form-check-label">{{ $t('admin.gear_rental.return_paid', { amount: money(r.total) }) }}</label>
                        </div>
                        <div v-if="r.deposit" class="form-check">
                            <input id="rd_dep" class="form-check-input" type="checkbox" v-model="return_deposit">
                            <label for="rd_dep" class="form-check-label">{{ $t('admin.gear_rental.return_deposit', { amount: money(r.deposit) }) }}</label>
                        </div>
                        <button type="button" class="btn btn-success mt-2" :disabled="busy" @click="do_return">
                            <i class="fa fa-check" aria-hidden="true"></i> {{ $t('admin.gear_rental.return_btn') }}
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <template #footer>
            <div class="modal-footer" v-if="r">
                <button v-if="$can('del', 'gear_rental')" type="button" class="btn btn-outline-danger me-auto" :disabled="busy" @click="del">
                    <i class="fa fa-trash" aria-hidden="true"></i> {{ $t('common.delete') }}
                </button>
                <template v-if="$can('edit', 'gear_rental')">
                    <button v-if="r.status === 'active'" type="button" class="btn btn-outline-secondary" :disabled="busy" @click="cancel">
                        {{ $t('admin.gear_rental.cancel_rental') }}
                    </button>
                    <button type="button" class="btn btn-outline-success" :disabled="busy" @click="toggle_paid">
                        {{ r.is_paid ? $t('admin.gear_rental.mark_unpaid') : $t('admin.gear_rental.mark_paid') }}
                    </button>
                    <button type="button" class="btn btn-primary" :disabled="busy" @click="edit">
                        <i class="fa fa-pencil" aria-hidden="true"></i> {{ $t('common.edit') }}
                    </button>
                </template>
            </div>
        </template>
    </StackModal>
</template>

<script>
import StackModal from '../../../global_components/modals/StackModal.vue'
import IdCardImage from './IdCardImage.vue'
import IdCardCapture from './IdCardCapture.vue'
import { rental_money, period_label } from './rentalFormat.js'

export default {
    name: 'RentalDetailsModal',
    components: { StackModal, IdCardImage, IdCardCapture },
    emits: ['edit', 'changed'],
    data() {
        return {
            is_open: false, loading: false, busy: false, r: null,
            return_paid: true, return_deposit: true, new_card_open: false, card_version: 0,
        }
    },
    computed: {
        state() {
            if (!this.r) return ''
            if (this.r.status === 'active') return this.r.is_overdue ? 'overdue' : 'active'
            return this.r.status
        },
        state_icon() {
            return { active: 'fa-handshake-o', overdue: 'fa-clock-o', returned: 'fa-check-circle', cancelled: 'fa-ban' }[this.state] || 'fa-circle'
        },
        state_title() {
            return this.$t('admin.gear_rental.status_' + this.state)
        },
        state_text() {
            if (!this.r) return ''
            if (this.state === 'overdue') return this.$t('admin.gear_rental.overdue_since', { date: this.date(this.r.due_at) })
            if (this.state === 'active') return this.$t('admin.gear_rental.due_back', { date: this.date(this.r.due_at) })
            if (this.state === 'returned') return this.$t('admin.gear_rental.returned_on', { date: this.date(this.r.returned_at) })
            return ''
        },
    },
    methods: {
        money(v) { return rental_money(v) },
        period(d) { return period_label(this.$t, d) },
        date(v) {
            if (!v) return '—'
            const d = new Date(String(v).replace(' ', 'T'))
            return isNaN(d.getTime()) ? v : d.toLocaleString(this.$i18n.locale === 'ka' ? 'ka-GE' : 'en-GB', { day: 'numeric', month: 'short', year: 'numeric', hour: '2-digit', minute: '2-digit' })
        },
        open(id) {
            this.is_open = true
            this.new_card_open = false
            if (!this.r || this.r.id !== Number(id)) this.r = null
            this.load(id)
        },
        load(id) {
            this.loading = true
            return axios.get('/set_gear_rental/rental/' + id)
                .then(res => {
                    this.r = res.data
                    this.return_paid = true
                    this.return_deposit = true
                })
                .catch(() => { this.is_open = false })
                .finally(() => { this.loading = false })
        },
        after(msg) {
            if (msg) this.$bus.$emit('toast', { type: 'success', message: this.$t(msg) })
            this.$emit('changed')
            return this.load(this.r.id)
        },
        do_return() {
            this.busy = true
            axios.post('/set_gear_rental/return_rental/' + this.r.id, { paid: this.return_paid, deposit_returned: this.return_deposit })
                .then(() => this.after('admin.gear_rental.returned_toast'))
                .catch(() => {})
                .finally(() => { this.busy = false })
        },
        cancel() {
            if (!confirm(this.$t('admin.gear_rental.confirm_cancel'))) return
            this.busy = true
            axios.post('/set_gear_rental/cancel_rental/' + this.r.id)
                .then(() => this.after('admin.gear_rental.cancelled_toast'))
                .catch(() => {})
                .finally(() => { this.busy = false })
        },
        toggle_paid() {
            this.busy = true
            axios.post('/set_gear_rental/set_paid/' + this.r.id, { paid: !this.r.is_paid })
                .then(() => this.after(null))
                .catch(() => {})
                .finally(() => { this.busy = false })
        },
        edit() {
            this.is_open = false
            this.$emit('edit', this.r.id)
        },
        del() {
            if (!confirm(this.$t('admin.gear_rental.confirm_delete_rental'))) return
            this.busy = true
            axios.delete('/set_gear_rental/del_rental/' + this.r.id)
                .then(() => { this.is_open = false; this.$emit('changed') })
                .catch(() => {})
                .finally(() => { this.busy = false })
        },
        upload_card(file) {
            if (!file) return
            const fd = new FormData()
            fd.append('id_card', file)
            axios.post('/set_gear_rental/upload_id_card/' + this.r.id, fd, { headers: { 'Content-Type': 'multipart/form-data' } })
                .then(() => {
                    this.new_card_open = false
                    this.card_version++
                    this.after('admin.gear_rental.id_card_saved')
                })
                .catch(() => {})
        },
        del_card() {
            if (!confirm(this.$t('admin.gear_rental.confirm_del_id_card'))) return
            axios.delete('/set_gear_rental/del_id_card/' + this.r.id)
                .then(() => this.after('admin.gear_rental.id_card_removed'))
                .catch(() => {})
        },
    },
}
</script>

<style scoped>
.rd__status {
    display: flex; flex-wrap: wrap; align-items: center; gap: .75rem;
    padding: .75rem 1rem; margin-bottom: 1.25rem;
    border-radius: var(--cg-r-lg); border: 1px solid var(--cg-line); border-left-width: 5px; background: var(--cg-chalk);
}
.rd__status > .fa { font-size: 1.5rem; }
.rd__status strong { display: block; }
.rd__status span { font-size: .9rem; }
.rd__status.is-active { border-left-color: var(--cg-accent); }
.rd__status.is-active > .fa { color: var(--cg-accent); }
.rd__status.is-overdue { border-left-color: #dc3545; background: #fdf2f3; }
.rd__status.is-overdue > .fa { color: #dc3545; }
.rd__status.is-returned { border-left-color: #2fa36b; }
.rd__status.is-returned > .fa { color: #2fa36b; }
.rd__status.is-cancelled > .fa { color: var(--cg-muted); }
.rd__badges { margin-left: auto; display: flex; gap: .35rem; flex-wrap: wrap; }
.rd__h { font-size: .8rem; font-weight: 800; letter-spacing: .05em; text-transform: uppercase; color: var(--cg-muted); margin: 0 0 .5rem; }
.rd__dl { display: grid; grid-template-columns: max-content 1fr; gap: .3rem 1rem; margin-bottom: 1.25rem; }
.rd__dl dt { font-weight: 600; color: var(--cg-stone); }
.rd__dl dd { margin: 0; min-width: 0; }
.rd__notes { padding: .6rem .8rem; border-radius: var(--cg-r); background: #fffaf0; }
.rd__return { margin-top: 1rem; padding: 1rem; border: 1px solid #b9e2cb; border-radius: var(--cg-r-lg); background: #f3fbf6; }
</style>
