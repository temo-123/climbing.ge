<template>
    <StackModal v-model="is_open" :title="gear_id ? $t('admin.gear_rental.edit_gear_title') : $t('admin.gear_rental.add_gear')" size="lg" @close="is_open = false">
        <form class="gf" @submit.prevent="save" novalidate>
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">{{ $t('admin.gear_rental.name_en') }} *</label>
                    <input type="text" class="form-control" :class="{ 'is-invalid': err('us_name') }" v-model.trim="form.us_name" maxlength="255" required>
                    <div class="invalid-feedback">{{ err('us_name') }}</div>
                </div>
                <div class="col-md-6">
                    <label class="form-label">{{ $t('admin.gear_rental.name_ka') }}</label>
                    <input type="text" class="form-control" v-model.trim="form.ka_name" maxlength="255">
                </div>
                <div class="col-md-6">
                    <label class="form-label">{{ $t('admin.gear_rental.description_en') }}</label>
                    <textarea class="form-control" :class="{ 'is-invalid': err('us_description') }" rows="2" v-model.trim="form.us_description" maxlength="500"></textarea>
                    <div class="invalid-feedback">{{ err('us_description') }}</div>
                </div>
                <div class="col-md-6">
                    <label class="form-label">{{ $t('admin.gear_rental.description_ka') }}</label>
                    <textarea class="form-control" :class="{ 'is-invalid': err('ka_description') }" rows="2" v-model.trim="form.ka_description" maxlength="500"></textarea>
                    <div class="invalid-feedback">{{ err('ka_description') }}</div>
                </div>
                <div class="col-12 mt-1"><small class="form-text text-muted">{{ $t('admin.gear_rental.description_hint') }}</small></div>
                <div class="col-md-4">
                    <label class="form-label">{{ $t('admin.gear_rental.category_col') }}</label>
                    <input type="text" class="form-control" v-model.trim="form.category" list="gf_categories" maxlength="120">
                    <datalist id="gf_categories"><option v-for="c in categories" :key="c" :value="c" /></datalist>
                </div>
                <div class="col-md-4">
                    <label class="form-label">{{ $t('admin.gear_rental.code') }}</label>
                    <input type="text" class="form-control" v-model.trim="form.code" maxlength="120">
                </div>
                <div class="col-md-4">
                    <label class="form-label">{{ $t('admin.gear_rental.deposit_per_piece') }}</label>
                    <div class="input-group">
                        <input type="number" min="0" step="0.01" class="form-control" v-model="form.deposit">
                        <span class="input-group-text">₾</span>
                    </div>
                </div>
                <div class="col-md-8">
                    <div class="form-check form-switch mt-md-4">
                        <input id="gf_shop" class="form-check-input" type="checkbox" role="switch" v-model="form.show_in_shop">
                        <label for="gf_shop" class="form-check-label">{{ $t('admin.gear_rental.show_in_shop') }}</label>
                    </div>
                    <small class="form-text text-muted">{{ $t('admin.gear_rental.show_in_shop_hint') }}</small>
                </div>
                <div class="col-md-4">
                    <label class="form-label">{{ $t('admin.gear_rental.sort_order') }}</label>
                    <input type="number" min="0" class="form-control" v-model.number="form.sort_order">
                </div>
            </div>

            <!-- prices per period -->
            <h3 class="gf__h">{{ $t('admin.gear_rental.prices_title') }}</h3>
            <p class="small text-muted">{{ $t('admin.gear_rental.prices_hint') }}</p>
            <div v-for="(p, i) in form.prices" :key="p.key" class="gf__price">
                <div class="input-group">
                    <input type="number" min="1" max="365" class="form-control" :class="{ 'is-invalid': err('prices.' + i + '.days') }" v-model.number="p.days">
                    <span class="input-group-text">{{ $t('admin.gear_rental.days_unit') }}</span>
                </div>
                <div class="input-group">
                    <input type="number" min="0" step="0.01" class="form-control" :class="{ 'is-invalid': err('prices.' + i + '.price') }" v-model="p.price">
                    <span class="input-group-text">₾</span>
                </div>
                <span class="gf__per-day text-muted small">{{ per_day(p) }}</span>
                <button type="button" class="btn btn-sm btn-outline-danger" @click="form.prices.splice(i, 1)" :aria-label="$t('common.delete')"><i class="fa fa-times" aria-hidden="true"></i></button>
            </div>
            <div v-if="err('prices')" class="text-danger small">{{ err('prices') }}</div>
            <div class="d-flex flex-wrap gap-2 mt-2">
                <button type="button" class="btn btn-sm btn-outline-primary" @click="add_price()"><i class="fa fa-plus" aria-hidden="true"></i> {{ $t('admin.gear_rental.add_period') }}</button>
                <button v-for="d in missing_presets" :key="d" type="button" class="btn btn-sm btn-outline-secondary" @click="add_price(d)">+ {{ period(d) }}</button>
            </div>

            <!-- stock per warehouse -->
            <h3 class="gf__h">{{ $t('admin.gear_rental.stock_title') }}</h3>
            <p v-if="!warehouses.length" class="small text-muted">{{ $t('admin.gear_rental.no_warehouses_yet') }}</p>
            <div v-for="(s, i) in form.stock" :key="s.warehouse_id" class="gf__stock">
                <label class="form-label mb-0" :for="'gf_stock_' + s.warehouse_id">
                    {{ s.name }} <span v-if="s.general" class="badge text-bg-secondary">{{ $t('admin.gear_rental.general') }}</span>
                </label>
                <input :id="'gf_stock_' + s.warehouse_id" type="number" min="0" class="form-control" :class="{ 'is-invalid': err('stock.' + i + '.quantity') }" v-model.number="s.quantity">
                <small class="text-muted">{{ $t('admin.gear_rental.out_now', { n: s.rented }) }}</small>
                <div class="invalid-feedback d-block" v-if="err('stock.' + i + '.quantity')">{{ err('stock.' + i + '.quantity') }}</div>
            </div>

            <!-- who has it -->
            <template v-if="renters.length">
                <h3 class="gf__h">{{ $t('admin.gear_rental.renters_col') }}</h3>
                <ul class="gf__renters">
                    <li v-for="r in renters" :key="r.rental_id">#{{ r.rental_id }} · {{ r.name }} × {{ r.quantity }} · {{ $t('admin.gear_rental.due_col') }} {{ date(r.due_at) }}</li>
                </ul>
            </template>

        </form>

        <template #footer>
            <div class="modal-footer">
                <button type="button" class="btn btn-light me-auto" @click="is_open = false">{{ $t('common.cancel') }}</button>
                <button type="button" class="btn btn-primary" :disabled="saving" @click="save">
                    <span v-if="saving" class="spinner-border spinner-border-sm" aria-hidden="true"></span>
                    <i v-else class="fa fa-check" aria-hidden="true"></i> {{ $t('common.save') }}
                </button>
            </div>
        </template>
    </StackModal>
</template>

<script>
import StackModal from '../../../global_components/modals/StackModal.vue'
import { rental_money, period_label, PERIOD_PRESETS } from './rentalFormat.js'

let seq = 0

export default {
    name: 'GearFormModal',
    components: { StackModal },
    props: {
        warehouses: { type: Array, default: () => [] },
        categories: { type: Array, default: () => [] },
    },
    emits: ['saved'],
    data() {
        return { is_open: false, saving: false, gear_id: null, form: {}, renters: [], errors: {} }
    },
    computed: {
        missing_presets() {
            const have = new Set((this.form.prices || []).map(p => Number(p.days)))
            return PERIOD_PRESETS.filter(d => !have.has(d))
        },
    },
    methods: {
        period(d) { return period_label(this.$t, d) },
        date(v) {
            const d = new Date(String(v).replace(' ', 'T'))
            return isNaN(d.getTime()) ? v : d.toLocaleDateString(this.$i18n.locale === 'ka' ? 'ka-GE' : 'en-GB', { day: 'numeric', month: 'short' })
        },
        per_day(p) {
            const d = Number(p.days), v = Number(p.price)
            if (!(d > 1) || !(v > 0)) return ''
            return this.$t('admin.gear_rental.per_day', { price: rental_money(Math.round(v / d * 100) / 100) })
        },
        open(gear = null) {
            this.errors = {}
            this.gear_id = gear ? gear.id : null
            this.renters = gear ? (gear.renters || []) : []
            const stock_of = id => ((gear && gear.warehouses) || []).find(w => w.id === id) || {}
            this.form = {
                us_name: gear ? gear.us_name : '',
                ka_name: gear ? (gear.ka_name || '') : '',
                category: gear ? (gear.category || '') : '',
                code: gear ? (gear.code || '') : '',
                deposit: gear && gear.deposit !== null ? gear.deposit : '',
                show_in_shop: gear ? !!gear.show_in_shop : true,
                sort_order: gear ? (gear.sort_order || 0) : 0,
                us_description: gear ? (gear.us_description || '') : '',
                ka_description: gear ? (gear.ka_description || '') : '',
                prices: gear
                    ? (gear.prices || []).map(p => ({ key: ++seq, days: p.days, price: p.price }))
                    : [1, 2, 3].map(d => ({ key: ++seq, days: d, price: '' })),
                stock: this.warehouses.map(w => ({
                    warehouse_id: w.id, name: w.name, general: w.general,
                    quantity: stock_of(w.id).quantity || 0, rented: stock_of(w.id).rented || 0,
                })),
            }
            this.is_open = true
        },
        add_price(days = '') {
            this.form.prices.push({ key: ++seq, days, price: '' })
            this.form.prices.sort((a, b) => (Number(a.days) || 999) - (Number(b.days) || 999))
        },
        err(key) {
            const e = this.errors[key]
            return e ? (Array.isArray(e) ? e[0] : e) : ''
        },
        save() {
            this.errors = {}
            const payload = {
                ...this.form,
                deposit: this.form.deposit === '' ? null : Number(this.form.deposit),
                prices: this.form.prices.filter(p => p.days !== '' && p.price !== '').map(p => ({ days: Number(p.days), price: Number(p.price) })),
                stock: this.form.stock.map(s => ({ warehouse_id: s.warehouse_id, quantity: Math.max(0, Number(s.quantity) || 0) })),
            }
            this.saving = true
            const url = this.gear_id ? '/set_gear_rental/edit_gear/' + this.gear_id : '/set_gear_rental/add_gear'
            axios.post(url, payload)
                .then(r => {
                    this.$bus.$emit('toast', { type: 'success', message: this.$t('admin.gear_rental.gear_saved') })
                    this.is_open = false
                    this.$emit('saved', r.data)
                })
                .catch(e => {
                    if (e.response && e.response.status === 422) this.errors = e.response.data.errors || {}
                })
                .finally(() => { this.saving = false })
        },
    },
}
</script>

<style scoped>
.gf__h { font-size: .8rem; font-weight: 800; letter-spacing: .05em; text-transform: uppercase; color: var(--cg-muted); margin: 1.5rem 0 .5rem; }
.gf__price { display: grid; grid-template-columns: minmax(0, 9rem) minmax(0, 10rem) 1fr auto; gap: .5rem; align-items: center; margin-bottom: .5rem; }
@media (max-width: 575.98px) { .gf__price { grid-template-columns: 1fr 1fr auto; } .gf__per-day { display: none; } }
.gf__stock { display: grid; grid-template-columns: minmax(0, 1fr) 8rem auto; gap: .25rem .75rem; align-items: center; padding: .4rem 0; border-top: 1px dashed var(--cg-line); }
.gf__renters { padding-left: 1.1rem; font-size: .9rem; }
</style>
