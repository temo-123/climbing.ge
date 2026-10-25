<template>
    <!-- Product filters: off-canvas panel from the left (FiltrViewControls
         opens it). Filtering is client-side on the page's product list, so
         every option shows how many products it would leave, and the button
         says how many the whole selection shows. Nothing is applied until
         "Show N products"; Esc / backdrop / × close without applying. -->
    <Teleport to="body">
        <transition name="pf-fade">
            <div v-if="menu" class="pf-backdrop" @click="open_menu()"></div>
        </transition>
        <transition name="pf-slide">
            <aside v-if="menu" class="pf" role="dialog" aria-modal="true" :aria-label="$t('shop.filters.filters_title')">
                <header class="pf__head">
                    <h2><i class="fa fa-sliders" aria-hidden="true"></i> {{ $t('shop.filters.filters_title') }}
                        <span v-if="draft_count" class="pf__count">{{ draft_count }}</span>
                    </h2>
                    <button ref="close" type="button" class="pf__close" :aria-label="$t('shop.filters.close')" @click="open_menu()"><i class="fa fa-times" aria-hidden="true"></i></button>
                </header>

                <div class="pf__body">
                    <!-- category → subcategory -->
                    <section class="pf-sec">
                        <button type="button" class="pf-sec__head" :aria-expanded="open.category" @click="open.category = !open.category">
                            <span><i class="fa fa-th-large" aria-hidden="true"></i> {{ $t('shop.filters.categories_label') }}</span>
                            <small v-if="filter_category" class="pf-sec__val">{{ name_of(category_by_id(filter_category)) }}</small>
                            <i class="fa fa-chevron-down pf-sec__chev" aria-hidden="true"></i>
                        </button>
                        <div v-show="open.category" class="pf-sec__body">
                            <button type="button" class="pf-row" :class="{ 'is-on': !filter_category }" @click="pick_category(0)">
                                <span class="pf-row__dot"></span><span class="pf-row__name">{{ $t('shop.filters.all_categories') }}</span>
                                <span class="pf-row__n">{{ count_with({ category_id: null, subcategory_id: null }) }}</span>
                            </button>
                            <template v-for="category in categories" :key="category.id">
                                <button type="button" class="pf-row" :class="{ 'is-on': filter_category == category.id, 'is-empty': !category_n(category.id) }"
                                        :disabled="!category_n(category.id) && filter_category != category.id" @click="pick_category(category.id)">
                                    <span class="pf-row__dot"></span><span class="pf-row__name">{{ name_of(category) }}</span>
                                    <span class="pf-row__n">{{ category_n(category.id) }}</span>
                                </button>
                                <div v-if="filter_category == category.id && subs_of(category.id).length" class="pf-chips pf-chips--sub">
                                    <button type="button" class="pf-chip" :class="{ 'is-on': !selected_subcategory }" @click="selected_subcategory = 0">{{ $t('shop.filters.all_subcategories_option') }}</button>
                                    <button v-for="sub in subs_of(category.id)" :key="sub.id" type="button" class="pf-chip"
                                            :class="{ 'is-on': selected_subcategory == sub.id }" :disabled="!subcategory_n(sub.id) && selected_subcategory != sub.id"
                                            @click="selected_subcategory = selected_subcategory == sub.id ? 0 : sub.id">
                                        {{ name_of(sub) }} <small>{{ subcategory_n(sub.id) }}</small>
                                    </button>
                                </div>
                            </template>
                        </div>
                    </section>

                    <!-- brand -->
                    <section class="pf-sec" v-if="brands.length">
                        <button type="button" class="pf-sec__head" :aria-expanded="open.brand" @click="open.brand = !open.brand">
                            <span><i class="fa fa-tag" aria-hidden="true"></i> {{ $t('shop.filters.brand_label') }}</span>
                            <small v-if="filter_brand" class="pf-sec__val">{{ brand_title(filter_brand) }}</small>
                            <i class="fa fa-chevron-down pf-sec__chev" aria-hidden="true"></i>
                        </button>
                        <div v-show="open.brand" class="pf-sec__body">
                            <div v-if="brands.length > 8" class="pf-search">
                                <i class="fa fa-search" aria-hidden="true"></i>
                                <input v-model.trim="brand_query" type="search" class="form-control form-control-sm" :placeholder="$t('shop.filters.search_brands')">
                            </div>
                            <div class="pf-chips">
                                <button v-for="brand in shown_brands" :key="brand.global_brand.id" type="button" class="pf-chip"
                                        :class="{ 'is-on': filter_brand == brand.global_brand.id }"
                                        :disabled="!brand_n(brand.global_brand.id) && filter_brand != brand.global_brand.id"
                                        @click="filter_brand = filter_brand == brand.global_brand.id ? 0 : brand.global_brand.id">
                                    {{ brand.us_brand ? brand.us_brand.title : brand.global_brand.id }} <small>{{ brand_n(brand.global_brand.id) }}</small>
                                </button>
                            </div>
                        </div>
                    </section>

                    <!-- sale type -->
                    <section class="pf-sec">
                        <button type="button" class="pf-sec__head" :aria-expanded="open.sale_type" @click="open.sale_type = !open.sale_type">
                            <span><i class="fa fa-shopping-cart" aria-hidden="true"></i> {{ $t('shop.filters.sale_type_label') }}</span>
                            <i class="fa fa-chevron-down pf-sec__chev" aria-hidden="true"></i>
                        </button>
                        <div v-show="open.sale_type" class="pf-sec__body pf-types">
                            <button v-for="t in sale_types" :key="t.key" type="button" class="pf-type"
                                    :class="{ 'is-on': sale_type === t.key }" :disabled="!count_with({ sale_type: t.key }) && sale_type !== t.key"
                                    @click="sale_type = sale_type === t.key ? 0 : t.key">
                                <i class="fa" :class="t.icon" aria-hidden="true"></i>
                                <span>{{ $t(t.label) }}</span>
                                <small>{{ count_with({ sale_type: t.key }) }}</small>
                            </button>
                        </div>
                    </section>

                    <!-- price: histogram of the products + two-handle slider -->
                    <section class="pf-sec">
                        <button type="button" class="pf-sec__head" :aria-expanded="open.price" @click="open.price = !open.price">
                            <span><i class="fa fa-money" aria-hidden="true"></i> {{ $t('shop.filters.price_label') }}</span>
                            <small class="pf-sec__val">₾{{ min_price }} – ₾{{ max_price }}{{ max_price >= maxPriceLimit ? '+' : '' }}</small>
                            <i class="fa fa-chevron-down pf-sec__chev" aria-hidden="true"></i>
                        </button>
                        <div v-show="open.price" class="pf-sec__body">
                            <div v-if="histogram.length" class="pf-hist" aria-hidden="true">
                                <span v-for="(bar, i) in histogram" :key="i" :class="{ 'is-in': bar.in }" :style="{ height: bar.h + '%' }"></span>
                            </div>
                            <div class="pf-range" :style="{ '--a': pos(min_price) / 10 + '%', '--b': pos(max_price) / 10 + '%' }">
                                <input type="range" min="0" max="1000" :value="pos(min_price)" :aria-label="$t('shop.filters.min_price_label')" :aria-valuetext="'₾' + min_price"
                                       @input="min_price = Math.min(price_at(+$event.target.value), max_price)">
                                <input type="range" min="0" max="1000" :value="pos(max_price)" :aria-label="$t('shop.filters.max_price_label')" :aria-valuetext="'₾' + max_price"
                                       @input="max_price = Math.max(price_at(+$event.target.value), min_price)">
                            </div>
                            <div class="pf-prices">
                                <label>
                                    <span>{{ $t('shop.filters.min_label') }}</span>
                                    <div class="input-group input-group-sm"><span class="input-group-text">₾</span>
                                        <input type="number" class="form-control" min="0" :max="max_price" :value="min_price" @change="set_min($event.target.value)">
                                    </div>
                                </label>
                                <span class="pf-prices__dash">–</span>
                                <label>
                                    <span>{{ $t('shop.filters.max_label') }}</span>
                                    <div class="input-group input-group-sm"><span class="input-group-text">₾</span>
                                        <input type="number" class="form-control" :min="min_price" :max="maxPriceLimit" :value="max_price" @change="set_max($event.target.value)">
                                    </div>
                                </label>
                            </div>
                        </div>
                    </section>
                </div>

                <footer class="pf__foot">
                    <button type="button" class="btn btn-link pf__reset" :disabled="!draft_count" @click="resetFilters()">
                        <i class="fa fa-undo" aria-hidden="true"></i> {{ $t('shop.filters.reset_btn') }}
                    </button>
                    <button type="button" class="btn btn-primary pf__apply" :disabled="products.length > 0 && !result_count" @click="applyFilters()">
                        <template v-if="products.length">{{ result_count ? $t('shop.filters.show_n', { n: result_count }) : $t('shop.filters.no_matches') }}</template>
                        <template v-else>{{ $t('shop.filters.apply_filters_btn') }}</template>
                    </button>
                </footer>
            </aside>
        </transition>
    </Teleport>
</template>

<script>
    const SALE_TYPES = [
        { key: 'online_order', icon: 'fa-truck', label: 'shop.filters.online_sale_option' },
        { key: 'produced_by_order', icon: 'fa-wrench', label: 'shop.filters.produced_by_order_option' },
        { key: 'custom_production', icon: 'fa-pencil', label: 'shop.filters.custom_production_option' },
    ]

    export default {
        name: 'leftMenu',
        props: {
            activeFilters: {
                type: Object,
                default: () => ({})
            },
            // the page's full product list (filtering is client-side): used
            // for the counts, the price range and the histogram
            products: {
                type: Array,
                default: () => []
            }
        },
        emits: ['apply-filters', 'categories-loaded', 'brands-loaded', 'subcategories-loaded'],
        data() {
            return {
                menu: false,
                min_price: 0,
                max_price: 5000,
                open: { category: true, brand: true, sale_type: true, price: true },
                filter_category: 0,
                filter_brand: 0,
                sale_type: 0,
                selected_subcategory: 0,
                categories: [],
                brands: [],
                subcategories: [],
                brand_query: '',
                sale_types: SALE_TYPES,
            }
        },
        computed: {
            ka() { return this.$i18n.locale === 'ka' },
            // top of the slider: the dearest product, rounded up (5000 until products arrive)
            maxPriceLimit() {
                const top = Math.max(0, ...this.products.map(p => Number(p.max_price) || 0))
                if (!top) return 5000
                const step = top > 1000 ? 100 : 10
                return Math.ceil(top / step) * step
            },
            // slider curve: linear for a narrow range; with a few very dear
            // products (a whole climbing wall) the cheap end gets most of the track
            price_curve() { return this.maxPriceLimit > 2000 ? 2.5 : 1 },
            sub_category() {
                const map = {}
                this.subcategories.forEach(s => { map[s.id] = s.category_id })
                return map
            },
            // the filters as they'd be applied now
            draft() {
                return {
                    category_id: this.filter_category || null,
                    subcategory_id: this.selected_subcategory || null,
                    brand_id: this.filter_brand || null,
                    sale_type: this.sale_type || null,
                    price_min: this.min_price > 0 ? this.min_price : null,
                    price_max: this.max_price < this.maxPriceLimit ? this.max_price : null,
                }
            },
            draft_count() { return Object.values(this.draft).filter(v => v !== null).length },
            result_count() { return this.count_with({}) },
            shown_brands() {
                const q = this.brand_query.toLowerCase()
                return q ? this.brands.filter(b => ((b.us_brand && b.us_brand.title) || '').toLowerCase().includes(q)) : this.brands
            },
            // product count per price slice; slices inside the chosen range are highlighted
            histogram() {
                if (this.products.length < 4) return []
                const n = 28, max = this.maxPriceLimit
                const bins = new Array(n).fill(0)
                // bins are even slices of the slider track (same curve)
                this.products.forEach(p => {
                    const v = Math.min(max, Number(p.min_price) || 0)
                    bins[Math.min(n - 1, Math.floor(this.pos(v) / 1000 * n))]++
                })
                const top = Math.max(...bins) || 1
                const a = this.pos(this.min_price), b = this.pos(this.max_price)
                return bins.map((c, i) => ({
                    h: c ? 12 + 88 * c / top : 4,
                    in: (i + 1) * 1000 / n > a && i * 1000 / n < b,
                }))
            },
        },
        mounted() {
            this.get_product_categories()
            this.onKey = e => { if (e.key === 'Escape' && this.menu) this.open_menu() }
            document.addEventListener('keydown', this.onKey)
        },
        beforeUnmount() {
            document.removeEventListener('keydown', this.onKey)
            document.documentElement.classList.remove('pf-lock')
        },
        watch: {
            activeFilters: {
                handler(filters) {
                    if (!filters) return
                    this.sync_from(filters)
                },
                immediate: true,
                deep: true
            },
            maxPriceLimit() { this.sync_from(this.activeFilters || {}) },
        },
        methods: {
            // opens / closes the panel (FiltrViewControls calls this). Closing
            // without "Show" drops the unapplied changes.
            open_menu() {
                this.menu = !this.menu
                document.documentElement.classList.toggle('pf-lock', this.menu)
                if (this.menu) {
                    this.sync_from(this.activeFilters || {})
                    this.$nextTick(() => this.$refs.close && this.$refs.close.focus())
                }
            },
            sync_from(filters) {
                this.sale_type = filters.sale_type || 0
                this.filter_brand = filters.brand_id || 0
                this.filter_category = filters.category_id || 0
                this.selected_subcategory = filters.subcategory_id || 0
                this.min_price = filters.price_min || 0
                this.max_price = filters.price_max ? Math.min(filters.price_max, this.maxPriceLimit) : this.maxPriceLimit
                // a subcategory link (?subcategory_id=) without its category: select the parent too
                if (!this.filter_category && this.selected_subcategory && this.sub_category[this.selected_subcategory]) {
                    this.filter_category = this.sub_category[this.selected_subcategory]
                }
            },
            resetFilters() {
                this.filter_brand = 0
                this.sale_type = 0
                this.filter_category = 0
                this.selected_subcategory = 0
                this.min_price = 0
                this.max_price = this.maxPriceLimit
            },
            applyFilters() {
                this.$emit('apply-filters', { ...this.draft })
                this.open_menu()
            },
            pick_category(id) {
                if (this.filter_category != id) this.selected_subcategory = 0
                this.filter_category = id
            },
            set_min(v) { this.min_price = Math.max(0, Math.min(Number(v) || 0, this.max_price)) },
            set_max(v) { this.max_price = Math.min(this.maxPriceLimit, Math.max(Number(v) || 0, this.min_price)) },
            // price ↔ slider position (0…1000)
            pos(price) { return this.maxPriceLimit ? Math.round(1000 * Math.pow(price / this.maxPriceLimit, 1 / this.price_curve)) : 0 },
            price_at(pos) {
                if (pos >= 1000) return this.maxPriceLimit
                const v = this.maxPriceLimit * Math.pow(pos / 1000, this.price_curve)
                const step = v < 100 ? 1 : v < 1000 ? 10 : 100
                return Math.round(v / step) * step
            },

            // same rules as the catalog pages' filtred_products
            matches(p, f) {
                const g = p.global_product || {}
                if (f.sale_type && g.sale_type != f.sale_type) return false
                if (f.brand_id && g.brand_id != f.brand_id) return false
                if (f.subcategory_id) { if (g.subcategory_id != f.subcategory_id) return false }
                else if (f.category_id && this.sub_category[g.subcategory_id] != f.category_id) return false
                if (f.price_min && (p.min_price !== undefined ? p.min_price : 0) < f.price_min) return false
                if (f.price_max && (p.max_price !== undefined ? p.max_price : 0) > f.price_max) return false
                return true
            },
            // products left if `change` were applied on top of the current selection
            count_with(change) {
                const f = { ...this.draft, ...change }
                return this.products.filter(p => this.matches(p, f)).length
            },
            category_n(id) { return this.count_with({ category_id: id, subcategory_id: null }) },
            subcategory_n(id) { return this.count_with({ subcategory_id: id }) },
            brand_n(id) { return this.count_with({ brand_id: id }) },
            subs_of(category_id) { return this.subcategories.filter(s => s.category_id == category_id) },
            category_by_id(id) { return this.categories.find(c => c.id == id) },
            name_of(item) { return item ? ((this.ka && item.ka_name) || item.us_name) : '' },
            brand_title(id) {
                const b = this.brands.find(x => x.global_brand.id == id)
                return b && b.us_brand ? b.us_brand.title : id
            },

            get_product_categories() {
                axios.get('/get_product/get_product_category/get_all_product_category')
                    .then(response => {
                        this.categories = response.data
                        this.$emit('categories-loaded', this.categories)
                        this.get_product_brabds()
                    })
                    .catch(error => console.log(error))
                // all subcategories at once: grouped under their categories here
                axios.get('/get_product/get_product_category/get_subcategory/get_all_subcategories')
                    .then(response => {
                        this.subcategories = response.data
                        this.$emit('subcategories-loaded', this.subcategories)
                        this.sync_from(this.activeFilters || {})
                    })
                    .catch(() => {})
            },
            get_product_brabds() {
                axios.get('/get_product/get_brand/get_all_brands')
                    .then(response => {
                        this.brands = response.data
                        this.$emit('brands-loaded', this.brands)
                    })
                    .catch(error => console.log(error))
            },
        }
    }
</script>

<style scoped>
    .pf-backdrop { position: fixed; inset: 0; z-index: 1090; background: rgba(15, 20, 25, .45); }
    .pf {
        position: fixed; top: 0; left: 0; bottom: 0; z-index: 1091;
        display: flex; flex-direction: column;
        width: min(380px, 100vw);
        background: var(--cg-surface, #fff); color: var(--cg-ink);
        box-shadow: var(--cg-shadow-lg);
    }
    .pf__head { display: flex; align-items: center; justify-content: space-between; gap: 1rem; padding: 1rem 1.1rem; border-bottom: 1px solid var(--cg-line); border-top: 4px solid var(--cg-accent); }
    .pf__head h2 { display: flex; align-items: center; gap: .55rem; margin: 0 !important; font-size: 1.25rem !important; }
    .pf__head h2 .fa { color: var(--cg-accent); }
    .pf__count { display: inline-grid; place-items: center; min-width: 1.5rem; height: 1.5rem; padding: 0 .4rem; border-radius: 1rem; background: var(--cg-accent); color: var(--cg-accent-contrast, #fff); font-size: .8rem; font-weight: 800; }
    .pf__close { display: grid; place-items: center; width: 2.4rem; height: 2.4rem; border: 0; border-radius: 50%; background: var(--cg-chalk); color: var(--cg-ink); font-size: 1.1rem; }
    .pf__close:hover { background: var(--cg-line); }
    .pf__body { flex: 1; overflow-y: auto; overscroll-behavior: contain; padding: .25rem 0 1rem; }

    .pf-sec { border-bottom: 1px solid var(--cg-line); }
    .pf-sec__head { display: flex; align-items: center; gap: .5rem; width: 100%; padding: .9rem 1.1rem; border: 0; background: none; color: var(--cg-ink); font-weight: 800; text-align: left; }
    .pf-sec__head > span { display: inline-flex; align-items: center; gap: .55rem; }
    .pf-sec__head > span .fa { width: 1.1rem; color: var(--cg-accent); }
    .pf-sec__val { margin-left: auto; max-width: 45%; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; color: var(--cg-accent-dark, var(--cg-accent)); font-weight: 700; }
    .pf-sec__chev { margin-left: auto; color: var(--cg-muted); font-size: .8rem; transition: transform .2s var(--cg-ease); }
    .pf-sec__val + .pf-sec__chev { margin-left: .5rem; }
    .pf-sec__head[aria-expanded="false"] .pf-sec__chev { transform: rotate(-90deg); }
    .pf-sec__body { padding: 0 1.1rem 1rem; }

    /* category rows */
    .pf-row { display: flex; align-items: center; gap: .65rem; width: 100%; padding: .5rem .6rem; border: 0; border-radius: var(--cg-r-sm, 6px); background: none; color: var(--cg-ink); text-align: left; }
    .pf-row:hover:not(:disabled) { background: var(--cg-chalk); }
    .pf-row__dot { flex: none; width: 1rem; height: 1rem; border: 2px solid var(--cg-line); border-radius: 50%; transition: border-color .15s, border-width .15s; }
    .pf-row.is-on .pf-row__dot { border: 5px solid var(--cg-accent); }
    .pf-row.is-on { font-weight: 800; }
    .pf-row__name { flex: 1; }
    .pf-row__n, .pf-chip small, .pf-type small { color: var(--cg-muted); font-size: .8rem; font-weight: 700; }
    .pf-row:disabled { opacity: .45; cursor: default; }

    /* chips (subcategories, brands) */
    .pf-chips { display: flex; flex-wrap: wrap; gap: .4rem; }
    .pf-chips--sub { margin: .15rem 0 .5rem 2.25rem; }
    .pf-chip { display: inline-flex; align-items: center; gap: .35rem; padding: .35rem .75rem; border: 1px solid var(--cg-line); border-radius: 2rem; background: var(--cg-surface, #fff); color: var(--cg-ink); font-size: .88rem; font-weight: 600; transition: background-color .15s, border-color .15s, color .15s; }
    .pf-chip:hover:not(:disabled) { border-color: var(--cg-accent); }
    .pf-chip.is-on { background: var(--cg-accent); border-color: var(--cg-accent); color: var(--cg-accent-contrast, #fff); }
    .pf-chip.is-on small { color: inherit; opacity: .8; }
    .pf-chip:disabled { opacity: .4; cursor: default; }
    .pf-search { position: relative; margin-bottom: .6rem; }
    .pf-search .fa { position: absolute; left: .7rem; top: 50%; transform: translateY(-50%); color: var(--cg-muted); }
    .pf-search input { padding-left: 2rem; border-radius: 2rem; }

    /* sale type cards */
    .pf-types { display: grid; grid-template-columns: repeat(3, 1fr); gap: .5rem; }
    .pf-type { display: flex; flex-direction: column; align-items: center; gap: .3rem; padding: .7rem .4rem; border: 1px solid var(--cg-line); border-radius: var(--cg-r, 10px); background: var(--cg-surface, #fff); color: var(--cg-ink); font-size: .8rem; font-weight: 700; line-height: 1.2; text-align: center; transition: background-color .15s, border-color .15s; }
    .pf-type .fa { font-size: 1.25rem; color: var(--cg-accent); }
    .pf-type:hover:not(:disabled) { border-color: var(--cg-accent); }
    .pf-type.is-on { border-color: var(--cg-accent); background: var(--cg-accent-soft, rgba(var(--cg-accent-rgb), .1)); box-shadow: inset 0 0 0 1px var(--cg-accent); }
    .pf-type:disabled { opacity: .4; cursor: default; }

    /* price */
    .pf-hist { display: flex; align-items: flex-end; gap: 2px; height: 46px; margin: .25rem .55rem 0; }
    .pf-hist span { flex: 1; border-radius: 2px 2px 0 0; background: var(--cg-line); transition: background-color .15s; }
    .pf-hist span.is-in { background: rgba(var(--cg-accent-rgb), .55); }
    .pf-range { position: relative; height: 1.6rem; margin: 0 .55rem .75rem; }
    .pf-range::before, .pf-range::after { content: ""; position: absolute; top: 50%; height: 4px; margin-top: -2px; border-radius: 2px; }
    .pf-range::before { left: 0; right: 0; background: var(--cg-line); }
    .pf-range::after { left: var(--a); right: calc(100% - var(--b)); background: var(--cg-accent); }
    .pf-range input { position: absolute; inset: 0; width: 100%; margin: 0; background: none; pointer-events: none; -webkit-appearance: none; appearance: none; z-index: 1; }
    .pf-range input::-webkit-slider-runnable-track { background: none; border: 0; }
    .pf-range input::-moz-range-track { background: none; border: 0; }
    .pf-range input::-webkit-slider-thumb { -webkit-appearance: none; width: 1.3rem; height: 1.3rem; border: 3px solid var(--cg-accent); border-radius: 50%; background: #fff; box-shadow: var(--cg-shadow-sm); pointer-events: auto; cursor: grab; }
    .pf-range input::-moz-range-thumb { width: 1.3rem; height: 1.3rem; border: 3px solid var(--cg-accent); border-radius: 50%; background: #fff; box-shadow: var(--cg-shadow-sm); pointer-events: auto; cursor: grab; }
    .pf-range input:focus-visible::-webkit-slider-thumb { outline: 3px solid rgba(var(--cg-accent-rgb), .4); }
    .pf-prices { display: flex; align-items: flex-end; gap: .5rem; }
    .pf-prices label { flex: 1; margin: 0; }
    .pf-prices label > span { display: block; margin-bottom: .2rem; color: var(--cg-muted); font-size: .75rem; font-weight: 800; letter-spacing: .06em; text-transform: uppercase; }
    .pf-prices__dash { padding-bottom: .35rem; color: var(--cg-muted); }

    .pf__foot { display: flex; align-items: center; gap: .75rem; padding: .85rem 1.1rem; border-top: 1px solid var(--cg-line); background: var(--cg-surface, #fff); box-shadow: 0 -6px 16px rgba(15, 20, 25, .06); }
    .pf__reset { padding-left: 0; padding-right: 0; color: var(--cg-stone); font-weight: 700; text-decoration: none; }
    .pf__apply { flex: 1; padding: .7rem 1rem; font-weight: 800; }

    .pf-fade-enter-active, .pf-fade-leave-active { transition: opacity .25s ease; }
    .pf-fade-enter-from, .pf-fade-leave-to { opacity: 0; }
    .pf-slide-enter-active, .pf-slide-leave-active { transition: transform .3s var(--cg-ease, ease); }
    .pf-slide-enter-from, .pf-slide-leave-to { transform: translateX(-100%); }
    @media (prefers-reduced-motion: reduce) {
        .pf-fade-enter-active, .pf-fade-leave-active, .pf-slide-enter-active, .pf-slide-leave-active { transition: none; }
    }
</style>

<style>
    /* page doesn't scroll behind the open filter panel */
    html.pf-lock, html.pf-lock body { overflow: hidden; }
</style>
