<template>
    <!-- Tour edit → "Prices" tab: the price rules of the calculator on the tour page.
         One row = duration (days) + group size (from–to, empty "to" = and more) + price
         per person or per group. Saved on its own (Save prices), replaces all rules.
         docs/FUNCTIONS_DOCS/shop/tour_price_calculator.md -->
    <div class="cg-tp">
        <div class="cg-tp__head">
            <div>
                <h3>{{ $t('admin.tour_prices.title') }}</h3>
                <p>{{ $t('admin.tour_prices.subtitle') }}</p>
            </div>
            <!-- prices are calculated in euro -->
            <span class="cg-tp__currency"><i class="fa fa-eur" aria-hidden="true"></i> {{ $t('admin.tour_prices.in_euro') }}</span>
        </div>

        <div v-if="loading" class="text-center py-4"><div class="spinner-border text-primary" role="status"></div></div>

        <template v-else>
            <div class="table-responsive">
                <table class="table cg-tp__table">
                    <thead>
                        <tr>
                            <th>{{ $t('admin.tour_prices.days') }}</th>
                            <th>{{ $t('admin.tour_prices.persons_from') }}</th>
                            <th>{{ $t('admin.tour_prices.persons_to') }}</th>
                            <th>{{ $t('admin.tour_prices.price') }} ({{ symbols[currency] }})</th>
                            <th>{{ $t('admin.tour_prices.type') }}</th>
                            <th>{{ $t('admin.tour_prices.example') }}</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(row, i) in rows" :key="row.key" :class="{ 'is-bad': row_problem(i) }">
                            <td><input v-model.number="row.days" type="number" min="1" max="60" class="form-control form-control-sm cg-tp__num"></td>
                            <td><input v-model.number="row.persons_min" type="number" min="1" max="500" class="form-control form-control-sm cg-tp__num"></td>
                            <td><input v-model="row.persons_max" type="number" min="1" max="500" :placeholder="$t('admin.tour_prices.and_more')" class="form-control form-control-sm cg-tp__num"></td>
                            <td><input v-model.number="row.price" type="number" min="0" step="0.01" class="form-control form-control-sm cg-tp__price"></td>
                            <td>
                                <select v-model="row.price_type" class="form-select form-select-sm">
                                    <option value="per_person">{{ $t('admin.tour_prices.per_person') }}</option>
                                    <option value="per_group">{{ $t('admin.tour_prices.per_group') }}</option>
                                </select>
                            </td>
                            <td class="cg-tp__example">{{ example(row) }}</td>
                            <td class="text-nowrap">
                                <button type="button" class="btn btn-sm btn-outline-secondary" :title="$t('admin.tour_prices.duplicate')" @click="duplicate(i)"><i class="fa fa-clone" aria-hidden="true"></i></button>
                                <button type="button" class="btn btn-sm btn-outline-danger ms-1" :title="$t('common.delete')" @click="rows.splice(i, 1)"><i class="fa fa-trash" aria-hidden="true"></i></button>
                            </td>
                        </tr>
                        <tr v-if="!rows.length">
                            <td colspan="7" class="text-center text-muted py-3">{{ $t('admin.tour_prices.empty') }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div v-if="problem" class="alert alert-warning py-2">{{ problem }}</div>
            <div v-if="error" class="alert alert-danger py-2">{{ error }}</div>

            <div class="cg-tp__actions">
                <button type="button" class="btn btn-outline-primary btn-sm" @click="add_row"><i class="fa fa-plus" aria-hidden="true"></i> {{ $t('admin.tour_prices.add_row') }}</button>
                <button type="button" class="btn btn-outline-secondary btn-sm" @click="add_day"><i class="fa fa-calendar-plus-o" aria-hidden="true"></i> {{ $t('admin.tour_prices.add_day') }}</button>
                <button type="button" class="btn btn-primary ms-auto" :disabled="saving || !!problem" @click="save">
                    <span v-if="saving" class="spinner-border spinner-border-sm me-1" aria-hidden="true"></span>
                    {{ $t('admin.tour_prices.save') }}
                </button>
            </div>

            <!-- what visitors will see: price per group size × duration -->
            <div v-if="preview.days.length" class="cg-tp__preview">
                <h4>{{ $t('admin.tour_prices.preview') }}</h4>
                <div class="table-responsive">
                    <table class="table table-sm">
                        <thead>
                            <tr>
                                <th>{{ $t('admin.tour_prices.group') }}</th>
                                <th v-for="d in preview.days" :key="d">{{ n_days(d) }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="p in preview.persons" :key="p">
                                <th>{{ n_persons(p) }}</th>
                                <td v-for="d in preview.days" :key="d">
                                    <template v-if="quote(d, p)">
                                        <strong>{{ money(quote(d, p).total) }}</strong>
                                        <small v-if="p > 1"> · {{ money(quote(d, p).per_person) }} / {{ $t('admin.tour_prices.person_short') }}</small>
                                    </template>
                                    <span v-else class="text-muted">{{ $t('admin.tour_prices.on_request') }}</span>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </template>
    </div>
</template>

<script>
    import { quote_price, money } from '../../../../../services/shop/tourPrice.js'

    let uid = 0;
    const row_of = r => ({
        key: ++uid,
        days: r.days || 1,
        persons_min: r.persons_min || 1,
        persons_max: r.persons_max === null || r.persons_max === undefined ? '' : r.persons_max,
        price: r.price === undefined ? '' : r.price,
        price_type: r.price_type || 'per_person',
    });

    export default {
        name: 'TourPricesEditorComponent',
        props: { tour_id: { type: [Number, String], required: true } },
        data() {
            return {
                loading: true,
                saving: false,
                error: '',
                currency: 'EUR',
                symbols: { GEL: '₾', USD: '$', EUR: '€' },
                rows: [],
            };
        },
        computed: {
            clean_rules() {
                return this.rows.map(r => ({
                    days: parseInt(r.days, 10) || 0,
                    persons_min: parseInt(r.persons_min, 10) || 0,
                    persons_max: r.persons_max === '' || r.persons_max === null ? null : parseInt(r.persons_max, 10),
                    price: parseFloat(r.price),
                    price_type: r.price_type,
                }));
            },
            // first problem found (shown above Save, also blocks it)
            problem() {
                for (let i = 0; i < this.rows.length; i++) {
                    const p = this.row_problem(i);
                    if (p) return this.$t('admin.tour_prices.row_n', { n: i + 1 }) + ': ' + p;
                }
                return '';
            },
            preview() {
                const rules = this.clean_rules.filter(r => r.days > 0 && r.persons_min > 0 && !isNaN(r.price));
                const days = [...new Set(rules.map(r => r.days))].sort((a, b) => a - b);
                const top = Math.min(10, Math.max(1, ...rules.map(r => (r.persons_max || r.persons_min + 1))));
                return { days, persons: Array.from({ length: top }, (_, i) => i + 1) };
            },
        },
        mounted() { this.load(); },
        methods: {
            money(v) { return money(v, this.currency); },
            n_days(n) { return this.$t(n === 1 ? 'admin.tour_prices.one_day' : 'admin.tour_prices.n_days', { n }); },
            n_persons(n) { return this.$t(n === 1 ? 'admin.tour_prices.one_person' : 'admin.tour_prices.n_persons', { n }); },
            quote(days, persons) {
                return quote_price(this.clean_rules.filter(r => !isNaN(r.price)), days, persons);
            },
            example(row) {
                const price = parseFloat(row.price);
                if (isNaN(price)) return '';
                // an example group inside the row's range (2 when the range allows it)
                const min = parseInt(row.persons_min, 10) || 1;
                const max = row.persons_max === '' || row.persons_max === null ? Infinity : parseInt(row.persons_max, 10);
                const n = Math.min(Math.max(min, 2), max);
                if (row.price_type === 'per_group') return this.$t('admin.tour_prices.example_group', { price: this.money(price) });
                return this.$t('admin.tour_prices.example_person', { n, total: this.money(price * n) });
            },
            row_problem(i) {
                const r = this.clean_rules[i];
                if (!r.days || r.days < 1 || r.days > 60) return this.$t('admin.tour_prices.err_days');
                if (!r.persons_min || r.persons_min < 1) return this.$t('admin.tour_prices.err_from');
                if (r.persons_max !== null && (isNaN(r.persons_max) || r.persons_max < r.persons_min)) return this.$t('admin.tour_prices.err_to');
                if (isNaN(r.price) || r.price < 0) return this.$t('admin.tour_prices.err_price');
                for (let j = 0; j < this.clean_rules.length; j++) {
                    if (j === i) continue;
                    const o = this.clean_rules[j];
                    if (o.days !== r.days) continue;
                    const a_max = r.persons_max === null ? Infinity : r.persons_max;
                    const b_max = o.persons_max === null ? Infinity : o.persons_max;
                    if (r.persons_min <= b_max && o.persons_min <= a_max) return this.$t('admin.tour_prices.err_overlap', { n: j + 1 });
                }
                return '';
            },
            // next group size after the last row of the same duration
            add_row() {
                const last = this.rows[this.rows.length - 1];
                if (!last) { this.rows.push(row_of({ days: 1, persons_min: 1, persons_max: 1 })); return; }
                const same = this.clean_rules.filter(r => r.days === (parseInt(last.days, 10) || 1));
                const top = Math.max(...same.map(r => r.persons_max || r.persons_min));
                this.rows.push(row_of({ days: last.days, persons_min: top + 1, persons_max: '', price_type: last.price_type }));
            },
            // copy the 1st duration's group sizes for one more day (prices empty)
            add_day() {
                const days = Math.max(0, ...this.clean_rules.map(r => r.days)) + 1;
                const base = this.clean_rules.filter(r => r.days === (this.clean_rules[0] ? this.clean_rules[0].days : 1));
                if (!base.length) { this.rows.push(row_of({ days, persons_min: 1, persons_max: '' })); return; }
                base.forEach(r => this.rows.push(row_of({ ...r, days, price: '' })));
            },
            duplicate(i) {
                this.rows.splice(i + 1, 0, row_of({ ...this.rows[i], persons_min: '', persons_max: '' }));
            },
            load() {
                this.loading = true;
                axios.get('/set_tour/get_tour_prices/' + this.tour_id)
                    .then(r => {
                        this.currency = 'EUR';
                        this.rows = (r.data.rules || []).map(row_of);
                    })
                    .catch(() => { this.error = this.$t('admin.tour_prices.load_failed'); })
                    .finally(() => { this.loading = false; });
            },
            save() {
                if (this.problem || this.saving) return;
                this.saving = true;
                this.error = '';
                axios.post('/set_tour/save_tour_prices/' + this.tour_id, { currency: this.currency, rules: this.clean_rules })
                    .then(r => {
                        this.rows = (r.data.rules || []).map(row_of);
                        this.$notify && this.$notify({ type: 'success', text: this.$t('admin.tour_prices.saved') });
                    })
                    .catch(e => { this.error = (e.response && e.response.data && e.response.data.message) || this.$t('admin.tour_prices.save_failed'); })
                    .finally(() => { this.saving = false; });
            },
        },
    };
</script>

<style scoped>
.cg-tp { width: 100%; }
.cg-tp__head {
    display: flex;
    flex-wrap: wrap;
    align-items: flex-end;
    justify-content: space-between;
    gap: 1rem;
    margin-bottom: 1rem;
}
.cg-tp__head h3 { margin: 0 0 .25rem; font-family: var(--cg-font-display); color: var(--cg-ink); }
.cg-tp__head p { margin: 0; max-width: 640px; color: var(--cg-stone); }
.cg-tp__currency { display: inline-flex; align-items: center; gap: .4rem; padding: .35rem .85rem; border-radius: 999px; background: var(--cg-accent-soft); font-weight: 700; color: var(--cg-accent-dark); }

.cg-tp__table { background: var(--cg-surface, #fff); border: 1px solid var(--cg-line); border-radius: var(--cg-r); }
.cg-tp__table th { white-space: nowrap; font-size: .8rem; color: var(--cg-stone); background: var(--cg-chalk); }
.cg-tp__table td { vertical-align: middle; }
.cg-tp__table tr.is-bad td { background: #fff7e6; }
.cg-tp__num { width: 5.5rem; }
.cg-tp__price { width: 7.5rem; }
.cg-tp__example { min-width: 9rem; font-size: .82rem; color: var(--cg-muted); }

.cg-tp__actions { display: flex; flex-wrap: wrap; gap: .5rem; align-items: center; margin: .75rem 0 1.5rem; }

.cg-tp__preview h4 { font-size: .85rem; font-weight: 800; text-transform: uppercase; letter-spacing: .04em; color: var(--cg-stone); }
.cg-tp__preview table { background: var(--cg-surface, #fff); }
.cg-tp__preview th, .cg-tp__preview td { white-space: nowrap; }
.cg-tp__preview small { color: var(--cg-muted); }
</style>
