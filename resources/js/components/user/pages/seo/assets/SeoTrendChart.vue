<template>
    <figure class="seo-trend">
        <figcaption class="seo-trend__head">
            <strong>{{ title }}</strong>
            <button type="button" class="btn btn-link btn-sm p-0" @click="show_table = !show_table">
                {{ show_table ? $t('admin.seo.chart.show_chart') : $t('admin.seo.chart.show_table') }}
            </button>
        </figcaption>

        <div v-if="!show_table" class="seo-trend__plot" ref="plot"
            @pointermove="move" @pointerleave="hover = null"
            tabindex="0" role="img" :aria-label="aria_label" @keydown="key">
            <svg :viewBox="`0 0 ${W} ${H}`" preserveAspectRatio="none" aria-hidden="true">
                <!-- recessive hairline grid + round ticks -->
                <g v-for="tick in ticks" :key="tick">
                    <line :x1="PAD_L" :x2="W - PAD_R" :y1="y(tick)" :y2="y(tick)" class="seo-trend__grid" />
                </g>
                <path :d="area" class="seo-trend__area" />
                <path :d="line" class="seo-trend__line" />
                <g v-if="hover !== null">
                    <line :x1="x(hover)" :x2="x(hover)" :y1="PAD_T" :y2="H - PAD_B" class="seo-trend__cross" />
                    <circle :cx="x(hover)" :cy="y(points[hover][value_key])" r="4" class="seo-trend__dot" />
                </g>
            </svg>
            <!-- tick labels in HTML so they don't stretch with the SVG -->
            <span v-for="tick in ticks" :key="'t' + tick" class="seo-trend__tick" :style="{ top: (y(tick) / H * 100) + '%' }">{{ compact(tick) }}</span>
            <span class="seo-trend__xlabel seo-trend__xlabel--start">{{ short_date(points[0] && points[0].date) }}</span>
            <span class="seo-trend__xlabel seo-trend__xlabel--end">{{ short_date(points.length && points[points.length - 1].date) }}</span>

            <div v-if="hover !== null" class="seo-trend__tip shadow-sm" :style="tip_style">
                <div class="seo-trend__tip-date">{{ long_date(points[hover].date) }}</div>
                <div><strong>{{ number(points[hover][value_key]) }}</strong> {{ value_label }}</div>
                <div v-if="secondary_key" class="text-muted"><strong>{{ number(points[hover][secondary_key]) }}</strong> {{ secondary_label }}</div>
            </div>
        </div>

        <div v-else class="table-responsive seo-trend__table">
            <table class="table table-sm">
                <thead><tr><th>{{ $t('admin.seo.col.date') }}</th><th class="text-end">{{ value_label }}</th><th v-if="secondary_key" class="text-end">{{ secondary_label }}</th></tr></thead>
                <tbody>
                    <tr v-for="p in points" :key="p.date">
                        <td>{{ long_date(p.date) }}</td>
                        <td class="text-end">{{ number(p[value_key]) }}</td>
                        <td v-if="secondary_key" class="text-end">{{ number(p[secondary_key]) }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </figure>
</template>

<script>
    // One series per chart (one axis): clicks per day. Line 2px, area wash ~10%,
    // hairline grid, crosshair + tooltip snapping to the nearest day, table view.
    export default {
        props: {
            points: { type: Array, default: () => [] },     // [{ date, clicks, impressions }]
            value_key: { type: String, default: 'clicks' },
            value_label: { type: String, default: '' },
            secondary_key: { type: String, default: '' },
            secondary_label: { type: String, default: '' },
            title: { type: String, default: '' },
        },
        data() {
            return { W: 600, H: 180, PAD_L: 40, PAD_R: 8, PAD_T: 10, PAD_B: 22, hover: null, show_table: false }
        },
        computed: {
            max() {
                const m = Math.max(1, ...this.points.map((p) => p[this.value_key] || 0))
                const step = this.nice_step(m / 3)
                return Math.ceil(m / step) * step
            },
            ticks() {
                const step = this.nice_step(this.max / 3)
                const out = []
                for (let t = 0; t <= this.max + 1e-9; t += step) out.push(t)
                return out
            },
            line() {
                return this.points.map((p, i) => `${i ? 'L' : 'M'}${this.x(i)},${this.y(p[this.value_key])}`).join(' ')
            },
            area() {
                if (!this.points.length) return ''
                return `${this.line} L${this.x(this.points.length - 1)},${this.y(0)} L${this.x(0)},${this.y(0)} Z`
            },
            tip_style() {
                const left = this.x(this.hover) / this.W * 100
                return left > 60 ? { right: (100 - left) + '%', marginRight: '10px' } : { left: left + '%', marginLeft: '10px' }
            },
            aria_label() {
                const total = this.points.reduce((s, p) => s + (p[this.value_key] || 0), 0)
                return `${this.title}: ${this.number(total)} ${this.value_label}, ${this.points.length} ${this.$t('admin.seo.chart.days')}`
            },
        },
        methods: {
            x(i) { return this.PAD_L + (this.points.length > 1 ? i / (this.points.length - 1) : 0.5) * (this.W - this.PAD_L - this.PAD_R) },
            y(v) { return this.PAD_T + (1 - (v || 0) / this.max) * (this.H - this.PAD_T - this.PAD_B) },
            nice_step(raw) {
                const pow = Math.pow(10, Math.floor(Math.log10(Math.max(raw, 1))))
                const n = raw / pow
                return (n <= 1 ? 1 : n <= 2 ? 2 : n <= 5 ? 5 : 10) * pow
            },
            move(e) {
                if (!this.points.length) return
                const r = this.$refs.plot.getBoundingClientRect()
                const px = (e.clientX - r.left) / r.width * this.W
                const t = (px - this.PAD_L) / (this.W - this.PAD_L - this.PAD_R)
                this.hover = Math.max(0, Math.min(this.points.length - 1, Math.round(t * (this.points.length - 1))))
            },
            key(e) {
                if (!this.points.length) return
                if (e.key === 'ArrowRight') this.hover = Math.min(this.points.length - 1, (this.hover ?? -1) + 1)
                else if (e.key === 'ArrowLeft') this.hover = Math.max(0, (this.hover ?? this.points.length) - 1)
                else if (e.key === 'Escape') this.hover = null
            },
            locale() { return this.$i18n.locale === 'ka' ? 'ka-GE' : 'en-GB' },
            number(n) { return new Intl.NumberFormat(this.locale()).format(n || 0) },
            compact(n) { return n >= 10000 ? new Intl.NumberFormat(this.locale(), { notation: 'compact' }).format(n) : this.number(n) },
            short_date(d) { return d ? new Intl.DateTimeFormat(this.locale(), { day: 'numeric', month: 'short' }).format(new Date(d)) : '' },
            long_date(d) { return d ? new Intl.DateTimeFormat(this.locale(), { weekday: 'short', day: 'numeric', month: 'short', year: 'numeric' }).format(new Date(d)) : '' },
        },
    }
</script>

<style scoped>
    .seo-trend { margin: 0; }
    .seo-trend__head { display: flex; justify-content: space-between; align-items: baseline; margin-bottom: .5rem; color: var(--cg-ink); }
    .seo-trend__plot { position: relative; height: 180px; outline: none; }
    .seo-trend__plot:focus-visible { box-shadow: 0 0 0 2px rgba(var(--cg-accent-rgb), .4); border-radius: 4px; }
    .seo-trend__plot svg { width: 100%; height: 100%; display: block; overflow: visible; }
    .seo-trend__grid { stroke: var(--cg-line); stroke-width: 1; vector-effect: non-scaling-stroke; }
    .seo-trend__line { fill: none; stroke: var(--cg-accent); stroke-width: 2; stroke-linejoin: round; stroke-linecap: round; vector-effect: non-scaling-stroke; }
    .seo-trend__area { fill: var(--cg-accent); opacity: .1; }
    .seo-trend__cross { stroke: var(--cg-muted); stroke-width: 1; vector-effect: non-scaling-stroke; }
    .seo-trend__dot { fill: var(--cg-accent); stroke: var(--cg-surface, #fff); stroke-width: 2; vector-effect: non-scaling-stroke; }
    .seo-trend__tick { position: absolute; left: 0; width: 34px; transform: translateY(-50%); text-align: right; font-size: 11px; color: var(--cg-muted); font-variant-numeric: tabular-nums; pointer-events: none; }
    .seo-trend__xlabel { position: absolute; bottom: 0; font-size: 11px; color: var(--cg-muted); pointer-events: none; }
    .seo-trend__xlabel--start { left: 40px; }
    .seo-trend__xlabel--end { right: 8px; }
    .seo-trend__tip { position: absolute; top: 4px; z-index: 2; padding: 6px 10px; background: var(--cg-surface, #fff); border: 1px solid var(--cg-line); border-radius: 6px; font-size: 13px; color: var(--cg-ink-2); white-space: nowrap; pointer-events: none; }
    .seo-trend__tip-date { font-size: 12px; color: var(--cg-muted); margin-bottom: 2px; }
    .seo-trend__table { max-height: 260px; overflow-y: auto; }
    .seo-trend__table td, .seo-trend__table th { font-variant-numeric: tabular-nums; }
</style>
