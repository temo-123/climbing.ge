<template>
    <!-- Shop dashboard → Gear rental (gear_rental › show). Rentals, gear list with
         stock / rented / available, rental warehouses. docs/FUNCTIONS_DOCS/shop/gear_rental.md -->
    <div class="row">
        <left-menu />
        <div class="col-sm-12">
            <breadcrumb />

            <div class="cg-admin-toolbar">
                <h1 class="h3">{{ $t('admin.gear_rental.title') }}</h1>
                <div class="d-flex flex-wrap gap-2">
                    <button v-if="$can('add', 'gear_rental')" type="button" class="btn btn-primary" :disabled="!warehouses.length" @click="new_rental()">
                        <i class="fa fa-plus" aria-hidden="true"></i> {{ $t('admin.gear_rental.new_rental') }}
                    </button>
                    <button type="button" class="btn btn-outline-secondary" :disabled="loading" @click="refresh()" :title="$t('common.refresh')">
                        <i class="fa fa-refresh" :class="{ 'fa-spin': loading }" aria-hidden="true"></i>
                    </button>
                </div>
            </div>

            <div v-if="loaded && !warehouses.length" class="alert alert-info">
                <i class="fa fa-info-circle" aria-hidden="true"></i> {{ $t('admin.gear_rental.start_hint') }}
            </div>

            <!-- numbers -->
            <div v-if="summary" class="gr-stats">
                <button type="button" class="gr-stat" @click="go('rentals', 0)">
                    <i class="fa fa-handshake-o" aria-hidden="true"></i>
                    <strong>{{ summary.active }}</strong><span>{{ $t('admin.gear_rental.stat_active') }}</span>
                </button>
                <button type="button" class="gr-stat" :class="{ 'is-hot': summary.overdue > 0 }" @click="go('rentals', 0)">
                    <i class="fa fa-clock-o" aria-hidden="true"></i>
                    <strong>{{ summary.overdue }}</strong><span>{{ $t('admin.gear_rental.stat_overdue') }}</span>
                </button>
                <button type="button" class="gr-stat" :class="{ 'is-warn': summary.due_today > 0 }" @click="go('rentals', 0)">
                    <i class="fa fa-calendar-check-o" aria-hidden="true"></i>
                    <strong>{{ summary.due_today }}</strong><span>{{ $t('admin.gear_rental.stat_due_today') }}</span>
                </button>
                <button type="button" class="gr-stat" @click="go('gear')">
                    <i class="fa fa-cubes" aria-hidden="true"></i>
                    <strong>{{ summary.pieces_out }} / {{ summary.pieces_total }}</strong><span>{{ $t('admin.gear_rental.stat_pieces_out') }}</span>
                </button>
                <div class="gr-stat is-static">
                    <i class="fa fa-money" aria-hidden="true"></i>
                    <strong>{{ money(summary.revenue_month) }}</strong><span>{{ $t('admin.gear_rental.stat_revenue_month') }}</span>
                </div>
                <div class="gr-stat is-static" :class="{ 'is-warn': summary.unpaid > 0 }">
                    <i class="fa fa-credit-card" aria-hidden="true"></i>
                    <strong>{{ summary.unpaid }}</strong><span>{{ $t('admin.gear_rental.stat_unpaid') }}</span>
                </div>
            </div>

            <!-- sections -->
            <ul class="nav nav-pills gr-sections" role="tablist">
                <li v-for="s in sections" :key="s.key" class="nav-item" role="presentation">
                    <button type="button" class="nav-link" :class="{ active: section === s.key }" role="tab" :aria-selected="section === s.key" @click="go(s.key)">
                        <i class="fa" :class="s.icon" aria-hidden="true"></i> {{ $t('admin.gear_rental.section_' + s.key) }}
                        <span v-if="s.count !== null" class="badge rounded-pill" :class="section === s.key ? 'text-bg-light' : 'text-bg-secondary'">{{ s.count }}</span>
                    </button>
                </li>
            </ul>

            <tabsComponent
                v-if="section === 'rentals'"
                :key="'rentals-' + rentals_key"
                :table_data="rentals_tabs"
                @update="load_rentals"
                @show_rental="show_rental"
                @edit_rental="edit_rental"
                @return_rental="show_rental"
                @del_rental="del_rental"
                @add_rental="new_rental"
                @delete_selected="bulk_delete_rentals"
            />
            <tabsComponent
                v-if="section === 'gear'"
                :table_data="gear_tabs"
                :selection_functions="false"
                @update="load_gear"
                @edit_gear="edit_gear"
                @add_gear="new_gear"
                @del_gear="del_gear"
            />
            <tabsComponent
                v-if="section === 'warehouses'"
                :table_data="warehouse_tabs"
                :selection_functions="false"
                @update="load_warehouses"
                @edit_warehouse="edit_warehouse"
                @add_warehouse="new_warehouse"
                @del_warehouse="del_warehouse"
            />
        </div>

        <rentalFormModal ref="rental_form" :warehouses="warehouses" :gear="gear" @saved="on_rental_saved" />
        <rentalDetailsModal ref="rental_details" @edit="edit_rental" @changed="refresh_rentals" />
        <gearFormModal ref="gear_form" :warehouses="warehouses" :categories="categories" @saved="on_gear_saved" />
        <warehouseModal ref="warehouse_form" @saved="on_warehouse_saved" />
    </div>
</template>

<script>
    import tabsComponent from '../../items/data_table/TabsComponent.vue'
    import breadcrumb from '../../items/BreadcrumbComponent.vue'
    import rentalFormModal from './RentalFormModal.vue'
    import rentalDetailsModal from './RentalDetailsModal.vue'
    import gearFormModal from './GearFormModal.vue'
    import warehouseModal from './RentalWarehouseModal.vue'
    import { rental_money, period_label } from './rentalFormat.js'

    export default {
        components: { tabsComponent, breadcrumb, rentalFormModal, rentalDetailsModal, gearFormModal, warehouseModal },
        data() {
            return {
                section: 'rentals',
                summary: null,
                active_rentals: [],
                history_rentals: [],
                gear: [],
                warehouses: [],
                loading: false,
                loaded: false,
                rentals_key: 0,
            }
        },
        computed: {
            sections() {
                return [
                    { key: 'rentals', icon: 'fa-handshake-o', count: this.active_rentals.length },
                    { key: 'gear', icon: 'fa-cubes', count: this.gear.length },
                    { key: 'warehouses', icon: 'fa-building-o', count: this.warehouses.length },
                ]
            },
            categories() {
                return [...new Set(this.gear.map(g => g.category).filter(Boolean))].sort()
            },
            rentals_tabs() {
                const head = [
                    this.$t('common.id'), this.$t('admin.gear_rental.renter_col'), this.$t('admin.gear_rental.phone_col'),
                    this.$t('admin.gear_rental.gear_col'), this.$t('admin.gear_rental.period_col'), this.$t('admin.gear_rental.due_col'),
                    this.$t('admin.gear_rental.total_col'), this.$t('admin.gear_rental.paid_col'), this.$t('admin.gear_rental.registered_col'),
                    this.$t('admin.gear_rental.id_card_col'), this.$t('admin.gear_rental.status_col'),
                    this.$t('admin.gear_rental.details_col'), this.$t('common.edit'), this.$t('common.delete'),
                ]
                const body = [
                    ['data', ['id']],
                    ['data_action_id', ['renter'], 'show_rental'],
                    ['data', ['phone']],
                    ['data', ['gear_summary']],
                    ['data', ['period_label']],
                    ['data', ['due_label']],
                    ['data', ['total_label']],
                    ['data', ['is_paid'], 'bool'],
                    ['data', ['is_registered'], 'bool'],
                    ['data', ['has_id_card'], 'bool'],
                    ['data', ['status_label']],
                    ['action_fun_id', 'show_rental', 'btn btn-info', '<i aria-hidden="true" class="fa fa-eye"></i>'],
                    ['action_fun_id', 'edit_rental', 'btn btn-primary', '<i aria-hidden="true" class="fa fa-pencil"></i>'],
                    ['action_fun_id', 'del_rental', 'btn btn-danger', '<i aria-hidden="true" class="fa fa-trash"></i>'],
                ]
                const perm = [['no'], ['no'], ['no'], ['no'], ['no'], ['no'], ['no'], ['no'], ['no'], ['no'], ['no'], ['no'], ['gear_rental', 'edit'], ['gear_rental', 'del']]
                // red = overdue, yellow = due today, grey = returned / cancelled
                const row_marks = [
                    { field: ['is_overdue'], when: 'true', class: 'table-danger', label: this.$t('admin.gear_rental.row_overdue') },
                    { when: r => r.status === 'active' && !r.is_overdue && this.is_today(r.due_at), class: 'table-warning', label: this.$t('admin.gear_rental.row_due_today') },
                    { when: r => r.status !== 'active', class: 'cg-row-muted', label: this.$t('admin.gear_rental.row_closed') },
                ]
                const add = this.$can('add', 'gear_rental') && this.warehouses.length
                    ? { action: 'fun', link: 'add_rental', class: 'btn btn-primary', btn_title: this.$t('admin.gear_rental.new_rental') } : undefined
                return [
                    { id: 1, table_name: this.$t('admin.gear_rental.tab_active'), add_action: add,
                      tab_data: { data: this.active_rentals, tab: { row_marks, head, body, perm } } },
                    { id: 2, table_name: this.$t('admin.gear_rental.tab_history'),
                      tab_data: { data: this.history_rentals, tab: { row_marks, head, body, perm } } },
                ]
            },
            gear_tabs() {
                const rows = this.gear.map(g => ({
                    ...g,
                    description_label: (this.$i18n.locale === 'ka' && g.ka_description) ? g.ka_description : (g.us_description || ''),
                    prices_label: (g.prices || []).map(p => period_label(this.$t, p.days) + ' ' + this.money(p.price)).join(' · '),
                    stock_label: (g.warehouses || []).filter(w => w.quantity > 0).map(w => w.name + ': ' + w.available + '/' + w.quantity).join(' · '),
                    renters_label: (g.renters || []).map(r => r.name + ' ×' + r.quantity).join(', '),
                    none_left: g.total_quantity > 0 && g.available === 0,
                }))
                return [{
                    id: 1,
                    table_name: this.$t('admin.gear_rental.gear_table'),
                    add_action: this.$can('add', 'gear_rental') ? { action: 'fun', link: 'add_gear', class: 'btn btn-primary', btn_title: this.$t('admin.gear_rental.add_gear') } : undefined,
                    tab_data: {
                        data: rows,
                        tab: {
                            row_marks: [
                                { field: ['none_left'], when: 'true', class: 'table-warning', label: this.$t('admin.gear_rental.row_none_left') },
                                { field: ['is_rented'], when: 'true', class: 'cg-row-accent', label: this.$t('admin.gear_rental.row_rented') },
                            ],
                            head: [
                                this.$t('common.id'), this.$t('common.name'), this.$t('admin.gear_rental.description_col'), this.$t('admin.gear_rental.category_col'), this.$t('admin.gear_rental.prices_col'),
                                this.$t('admin.gear_rental.total_qty_col'), this.$t('admin.gear_rental.rented_col'), this.$t('admin.gear_rental.available_col'),
                                this.$t('admin.gear_rental.stock_col'), this.$t('admin.gear_rental.renters_col'),
                                this.$t('admin.gear_rental.is_rented_col'), this.$t('admin.gear_rental.in_shop_col'),
                                this.$t('common.edit'), this.$t('common.delete'),
                            ],
                            body: [
                                ['data', ['id']],
                                ['data_action_id', ['us_name'], 'edit_gear'],
                                ['data', ['description_label']],
                                ['data', ['category']],
                                ['data', ['prices_label']],
                                ['data', ['total_quantity']],
                                ['data', ['rented']],
                                ['data', ['available']],
                                ['data', ['stock_label']],
                                ['data', ['renters_label']],
                                ['data', ['is_rented'], 'bool'],
                                ['data', ['show_in_shop'], 'bool'],
                                ['action_fun_id', 'edit_gear', 'btn btn-primary', '<i aria-hidden="true" class="fa fa-pencil"></i>'],
                                ['action_fun_id', 'del_gear', 'btn btn-danger', '<i aria-hidden="true" class="fa fa-trash"></i>'],
                            ],
                            perm: [['no'], ['no'], ['no'], ['no'], ['no'], ['no'], ['no'], ['no'], ['no'], ['no'], ['no'], ['no'], ['gear_rental', 'edit'], ['gear_rental', 'del']],
                        },
                    },
                }]
            },
            warehouse_tabs() {
                return [{
                    id: 1,
                    table_name: this.$t('admin.gear_rental.warehouses_table'),
                    add_action: this.$can('add', 'gear_rental') ? { action: 'fun', link: 'add_warehouse', class: 'btn btn-primary', btn_title: this.$t('admin.gear_rental.add_warehouse') } : undefined,
                    tab_data: {
                        data: this.warehouses,
                        tab: {
                            head: [
                                this.$t('common.id'), this.$t('common.name'), this.$t('admin.gear_rental.general'), this.$t('admin.gear_rental.address_col'),
                                this.$t('admin.gear_rental.kinds_col'), this.$t('admin.gear_rental.pieces_col'), this.$t('admin.gear_rental.active_rentals_col'),
                                this.$t('common.edit'), this.$t('common.delete'),
                            ],
                            body: [
                                ['data', ['id']],
                                ['data_action_id', ['name'], 'edit_warehouse'],
                                ['data', ['general'], 'bool'],
                                ['data', ['address']],
                                ['data', ['kinds']],
                                ['data', ['pieces']],
                                ['data', ['active_rentals']],
                                ['action_fun_id', 'edit_warehouse', 'btn btn-primary', '<i aria-hidden="true" class="fa fa-pencil"></i>'],
                                ['action_fun_id', 'del_warehouse', 'btn btn-danger', '<i aria-hidden="true" class="fa fa-trash"></i>'],
                            ],
                            perm: [['no'], ['no'], ['no'], ['no'], ['no'], ['no'], ['no'], ['gear_rental', 'edit'], ['gear_rental', 'del']],
                        },
                    },
                }]
            },
        },
        mounted() {
            const s = this.$route.query.section
            if (['rentals', 'gear', 'warehouses'].includes(s)) this.section = s
            this.refresh()
        },
        methods: {
            money(v) { return rental_money(v) },
            refresh() {
                this.loading = true
                return Promise.all([this.load_summary(), this.load_rentals(), this.load_gear(), this.load_warehouses()])
                    .finally(() => { this.loading = false; this.loaded = true })
            },
            refresh_rentals() {
                return Promise.all([this.load_summary(), this.load_rentals(), this.load_gear()])
            },
            go(section) {
                this.section = section
                const query = { ...this.$route.query, section }
                this.$router.replace({ query }).catch(() => {})
            },
            load_summary() {
                return axios.get('/set_gear_rental/summary').then(r => { this.summary = r.data }).catch(() => {})
            },
            load_rentals() {
                return Promise.all([
                    axios.get('/set_gear_rental/rentals', { params: { scope: 'active' } }),
                    axios.get('/set_gear_rental/rentals', { params: { scope: 'history' } }),
                ]).then(([a, h]) => {
                    this.active_rentals = (a.data || []).map(this.prepare_rental)
                    this.history_rentals = (h.data || []).map(this.prepare_rental)
                    this.rentals_key++
                }).catch(() => {})
            },
            load_gear() {
                return axios.get('/set_gear_rental/gear').then(r => { this.gear = r.data || [] }).catch(() => {})
            },
            load_warehouses() {
                return axios.get('/set_gear_rental/warehouses').then(r => { this.warehouses = r.data || [] }).catch(() => {})
            },
            prepare_rental(r) {
                return {
                    ...r,
                    period_label: this.date(r.start_at) + ' · ' + period_label(this.$t, r.days),
                    due_label: this.date(r.due_at, true),
                    total_label: this.money(r.total) + (r.deposit ? ' (+' + this.money(r.deposit) + ' ' + this.$t('admin.gear_rental.deposit_short') + ')' : ''),
                    status_label: r.is_overdue ? this.$t('admin.gear_rental.status_overdue') : this.$t('admin.gear_rental.status_' + r.status),
                }
            },
            date(v, with_time = false) {
                if (!v) return ''
                const d = new Date(String(v).replace(' ', 'T'))
                if (isNaN(d.getTime())) return v
                const opt = { day: 'numeric', month: 'short', year: 'numeric' }
                if (with_time) Object.assign(opt, { hour: '2-digit', minute: '2-digit' })
                return d.toLocaleString(this.$i18n.locale === 'ka' ? 'ka-GE' : 'en-GB', opt)
            },
            is_today(v) {
                if (!v) return false
                const d = new Date(String(v).replace(' ', 'T'))
                return d.toDateString() === new Date().toDateString()
            },
            find_rental(id) {
                return [...this.active_rentals, ...this.history_rentals].find(r => String(r.id) === String(id))
            },

            // rentals
            new_rental() { this.$refs.rental_form.open() },
            edit_rental(id) { this.$refs.rental_form.open(id) },
            show_rental(id) { this.$refs.rental_details.open(id) },
            on_rental_saved(rental) {
                this.refresh_rentals()
                if (rental && rental.id) this.$refs.rental_details.open(rental.id)
            },
            del_rental(id) {
                if (!confirm(this.$t('admin.gear_rental.confirm_delete_rental'))) return
                axios.delete('/set_gear_rental/del_rental/' + id).then(() => this.refresh_rentals()).catch(() => {})
            },
            bulk_delete_rentals(ids) {
                if (!confirm(this.$t('admin.gear_rental.confirm_delete_rental'))) return
                axios.post('/set_gear_rental/bulk_delete', { ids }).then(() => this.refresh_rentals()).catch(() => {})
            },

            // gear
            new_gear() { this.$refs.gear_form.open() },
            edit_gear(id) { this.$refs.gear_form.open(this.gear.find(g => String(g.id) === String(id))) },
            on_gear_saved() { this.load_gear(); this.load_summary(); this.load_warehouses() },
            del_gear(id) {
                if (!confirm(this.$t('admin.common.confirm_delete'))) return
                axios.delete('/set_gear_rental/del_gear/' + id)
                    .then(() => { this.load_gear(); this.load_summary() })
                    .catch(e => this.server_message(e))
            },

            // warehouses
            new_warehouse() { this.$refs.warehouse_form.open() },
            edit_warehouse(id) { this.$refs.warehouse_form.open(this.warehouses.find(w => String(w.id) === String(id))) },
            on_warehouse_saved() { this.load_warehouses(); this.load_gear() },
            del_warehouse(id) {
                if (!confirm(this.$t('admin.gear_rental.confirm_delete_warehouse'))) return
                axios.delete('/set_gear_rental/del_warehouse/' + id)
                    .then(() => { this.load_warehouses(); this.load_gear(); this.load_summary() })
                    .catch(e => this.server_message(e))
            },
            server_message(e) {
                const msg = e.response && e.response.status === 422 && e.response.data && e.response.data.message
                if (msg) this.$bus.$emit('toast', { type: 'warning', message: msg })
            },
        },
    }
</script>

<style scoped>
.gr-stats {
    display: grid;
    grid-template-columns: repeat(2, minmax(0, 1fr));
    gap: .75rem;
    margin-bottom: 1.25rem;
}
@media (min-width: 768px) { .gr-stats { grid-template-columns: repeat(3, minmax(0, 1fr)); } }
@media (min-width: 1200px) { .gr-stats { grid-template-columns: repeat(6, minmax(0, 1fr)); } }
.gr-stat {
    display: flex;
    flex-direction: column;
    align-items: flex-start;
    gap: .1rem;
    padding: .85rem 1rem;
    text-align: left;
    background: #fff;
    border: 1px solid var(--cg-line);
    border-radius: var(--cg-r-lg);
    box-shadow: var(--cg-shadow-sm);
    color: var(--cg-ink-2);
    transition: border-color .15s;
}
.gr-stat:hover { border-color: var(--cg-accent); }
.gr-stat.is-static { cursor: default; }
.gr-stat.is-static:hover { border-color: var(--cg-line); }
.gr-stat .fa { color: var(--cg-accent); font-size: 1.05rem; }
.gr-stat strong { font-family: var(--cg-font-display); font-size: 1.6rem; line-height: 1.1; color: var(--cg-ink); }
.gr-stat span { font-size: .85rem; font-weight: 700; }
.gr-stat.is-hot { border-color: #e0632a; background: #fff6f0; }
.gr-stat.is-hot .fa, .gr-stat.is-hot strong { color: #e0632a; }
.gr-stat.is-warn { border-color: #f2d48a; background: #fffaf0; }
.gr-sections { gap: .4rem; margin-bottom: 1rem; flex-wrap: wrap; }
.gr-sections .nav-link { display: inline-flex; align-items: center; gap: .4rem; font-weight: 700; }
</style>
