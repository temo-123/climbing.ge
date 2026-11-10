<template>
    <!-- Sector view "Table": every route of the area in one table with a Sector
         column. Sort by a column header, narrow to one sector, and the area
         route finder above (grade bands / search / classics) still filters.
         A row opens the route (or multi-pitch) modal. -->
    <div class="cg-rtable">
        <div class="cg-rtable__bar">
            <label class="cg-rtable__sector">
                <span class="visually-hidden">{{ $t('guide.sector_view.sector') }}</span>
                <select v-model="sector_id" class="form-select form-select-sm">
                    <option value="">{{ $t('guide.sector_view.all_sectors') }}</option>
                    <option v-for="s in sectors" :key="s.id" :value="s.id">{{ s.name }} ({{ s.count }})</option>
                </select>
            </label>
            <span class="cg-rtable__count">{{ $t('guide.sector_view.shown', { n: rows.length, total: all_rows.length }) }}</span>
        </div>

        <div class="table-responsive">
            <table class="table cg-route-table cg-rtable__table">
                <thead>
                    <tr>
                        <th v-for="col in columns" :key="col.key" :class="'cg-rt-' + col.key" :aria-sort="sort.key === col.key ? (sort.dir > 0 ? 'ascending' : 'descending') : null">
                            <button type="button" class="cg-rtable__sort" @click="sort_by(col.key)">
                                {{ col.label }}
                                <i class="fa" :class="sort.key === col.key ? (sort.dir > 0 ? 'fa-sort-asc' : 'fa-sort-desc') : 'fa-sort'" aria-hidden="true"></i>
                            </button>
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <tr
                        v-for="row in rows"
                        :key="row.key"
                        class="cg-rtable__row"
                        :class="{ 'route-ticked': row.ticked }"
                        tabindex="0"
                        :aria-label="row.name"
                        @click="open(row)"
                        @keydown.enter.self="open(row)"
                    >
                        <td class="cg-rt-num">{{ row.num }}</td>
                        <td class="cg-rt-name">
                            <span class="cg-rtable__name">{{ row.name }}</span>
                            <span v-if="row.type !== 'sport'" class="cg-rtable__type">{{ row.type_label }}</span>
                            <i v-if="row.ticked" class="fa fa-check cg-rtable__tick" :title="$t('guide.route_tools.climbed')" aria-hidden="true"></i>
                        </td>
                        <td class="cg-rt-sector">{{ row.sector_name }}</td>
                        <td class="cg-rt-grade"><strong>{{ row.grade || '?' }}</strong></td>
                        <td class="cg-rt-height">{{ row.height ? row.height + ' m' : '' }}</td>
                        <td class="cg-rt-stars">
                            <span v-if="row.stars" class="cg-rtable__stars" :title="row.stars + ' / 5'"><i v-for="n in Math.round(row.stars)" :key="n" class="fa fa-star" aria-hidden="true"></i></span>
                        </td>
                    </tr>
                    <tr v-if="!rows.length">
                        <td :colspan="columns.length" class="text-center text-muted py-3">
                            <i class="fa fa-filter" aria-hidden="true"></i> {{ $t('guide.route_tools.none_in_sector') }}
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <routeModals ref="modals" />
    </div>
</template>

<script>
import routeModals from "../items/RouteModalsComponent.vue";
import { sector_list, sector_name } from '../../../../../services/guide/sectorSummary.js'
import { grade_rank, route_matches, route_stars } from '../../../../../services/guide/routeFilters.js'

function num_of(v) {
    const n = parseFloat(String(v == null ? '' : v).replace(',', '.'));
    return isNaN(n) ? null : n;
}

export default {
    name: 'SectorRoutesTableComponent',
    components: { routeModals },
    props: ['climbing_area'],
    inject: { route_tools: { default: null } },
    data() {
        return {
            sort: { key: 'sector', dir: 1 },
            sector_id: '',
        };
    },
    computed: {
        columns() {
            return [
                { key: 'num', label: 'N' },
                { key: 'name', label: this.$t('common.name') },
                { key: 'sector', label: this.$t('guide.sector_view.sector') },
                { key: 'grade', label: this.$t('guide.sector_view.grade') },
                { key: 'height', label: this.$t('common.height') },
                { key: 'stars', label: '★' },
            ];
        },
        list() { return sector_list(this.climbing_area); },
        sectors() {
            return this.list.map(x => ({
                id: String(x.entry.sector.id),
                name: sector_name(x.entry, x.n, this.$t),
                count: (x.entry.sport_routes || []).length + (x.entry.boulder_route || []).length + (x.entry.mtps || []).length,
            }));
        },
        // one flat row per route / boulder / multi-pitch
        all_rows() {
            const rows = [];
            const ticked = (this.route_tools && this.route_tools.ticked) || {};
            this.list.forEach(x => {
                const sname = sector_name(x.entry, x.n, this.$t);
                const base = { sector_id: String(x.entry.sector.id), sector_name: sname, sector_n: x.n };
                (x.entry.sport_routes || []).forEach(r => rows.push({ ...base, key: 'r' + r.id, id: r.id, type: 'sport', route: r, num: r.num, name: r.name, grade: r.grade, height: r.height, stars: route_stars(r), ticked: !!ticked[r.id] }));
                (x.entry.boulder_route || []).forEach(r => rows.push({ ...base, key: 'b' + r.id, id: r.id, type: 'boulder', type_label: this.$t('guide.route_type_bouldering'), route: r, num: r.num, name: r.name, grade: r.grade, height: r.height, stars: route_stars(r), ticked: !!ticked[r.id] }));
                (x.entry.mtps || []).forEach(m => {
                    const pitches = m.mtp_pitchs || [];
                    // hardest pitch = the multi-pitch grade; total height
                    const hardest = pitches.reduce((h, p) => (grade_rank(p.grade) || 0) > (grade_rank(h) || 0) ? p.grade : h, pitches[0] ? pitches[0].grade : '');
                    const height = pitches.reduce((sum, p) => sum + (num_of(p.height) || 0), 0);
                    const pseudo = { id: 'mtp-' + m.mtp_id, name: m.mtp_name, grade: hardest, reviews_stars: m.reviews_stars };
                    rows.push({ ...base, key: 'm' + m.mtp_id, id: m.mtp_id, type: 'mtp', type_label: this.$t('guide.route_type_multi_pitch'), route: pseudo, num: '', name: m.mtp_name, grade: hardest, height: height || '', stars: route_stars(pseudo), ticked: false });
                });
            });
            return rows;
        },
        rows() {
            const { key, dir } = this.sort;
            const value = (r) => {
                switch (key) {
                    case 'num': return num_of(r.num);
                    case 'name': return String(r.name || '').toLowerCase();
                    case 'sector': return r.sector_n * 10000 + (num_of(r.num) || 0);
                    case 'grade': return grade_rank(r.grade);
                    case 'height': return num_of(r.height);
                    case 'stars': return r.stars || 0;
                }
                return null;
            };
            return this.all_rows
                .filter(r => !this.sector_id || r.sector_id === this.sector_id)
                .filter(r => route_matches(r.route, this.route_tools))
                .slice()
                .sort((a, b) => {
                    const va = value(a), vb = value(b);
                    // empty values always last
                    if (va === null || va === '') return (vb === null || vb === '') ? 0 : 1;
                    if (vb === null || vb === '') return -1;
                    return (va < vb ? -1 : va > vb ? 1 : 0) * dir;
                });
        },
    },
    mounted() {
        // menu / "On this page": show only that sector's routes
        this.onOpenRequest = (e) => {
            const id = String(e.detail && e.detail.id);
            if (this.sectors.some(s => s.id === id)) this.sector_id = id;
        };
        window.addEventListener('cg-sector-open', this.onOpenRequest);
    },
    beforeUnmount() {
        window.removeEventListener('cg-sector-open', this.onOpenRequest);
    },
    methods: {
        sort_by(key) {
            if (this.sort.key === key) this.sort.dir = -this.sort.dir;
            else this.sort = { key, dir: key === 'stars' ? -1 : 1 };
        },
        open(row) {
            if (row.type === 'mtp') this.$refs.modals.open_mtp(row.id);
            else this.$refs.modals.open_route(row.id);
        },
    },
};
</script>

<style scoped>
.cg-rtable { margin-bottom: 1.5rem; }
.cg-rtable__bar {
    display: flex;
    flex-wrap: wrap;
    align-items: center;
    justify-content: space-between;
    gap: .5rem 1rem;
    margin-bottom: .6rem;
}
.cg-rtable__sector { min-width: 200px; max-width: 320px; flex: 1 1 200px; }
.cg-rtable__count { font-size: .85rem; font-weight: 700; color: var(--cg-stone); }

.cg-rtable__table {
    margin: 0;
    background: var(--cg-surface, #fff);
    border: 1px solid var(--cg-line);
    border-radius: var(--cg-r);
    overflow: hidden;
}
.cg-rtable__table thead th {
    position: sticky;
    top: 0;
    background: var(--cg-chalk);
    white-space: nowrap;
}
.cg-rtable__sort {
    display: inline-flex;
    align-items: center;
    gap: .3rem;
    padding: 0;
    border: 0;
    background: none;
    font: inherit;
    font-weight: 800;
    color: var(--cg-ink-2);
    cursor: pointer;
}
.cg-rtable__sort .fa { font-size: .75rem; color: var(--cg-muted); }
th[aria-sort] .cg-rtable__sort, th[aria-sort] .cg-rtable__sort .fa { color: var(--cg-accent-dark); }

.cg-rtable__row { cursor: pointer; }
.cg-rtable__row:hover { background: var(--cg-accent-soft); }
.cg-rtable__row:hover .cg-rtable__name { color: var(--cg-accent); }
.cg-rtable__row:focus-visible { outline: 2px solid var(--cg-accent); outline-offset: -2px; }
.cg-rtable__name { font-weight: 700; color: var(--cg-ink); }
.cg-rtable__type {
    margin-left: .4rem;
    padding: .05rem .45rem;
    border-radius: 999px;
    background: var(--cg-chalk);
    font-size: .72rem;
    font-weight: 800;
    color: var(--cg-stone);
    white-space: nowrap;
}
.cg-rtable__tick { margin-left: .35rem; color: #2fa36b; }
.cg-rt-sector { color: var(--cg-stone); white-space: nowrap; }
.cg-rt-num, .cg-rt-height { color: var(--cg-muted); white-space: nowrap; }
.cg-rt-grade { white-space: nowrap; }
.cg-rtable__stars { color: #f5a623; font-size: .78rem; white-space: nowrap; }

@media (max-width: 575.98px) {
    .cg-rt-num, .cg-rt-stars { display: none; }
}
</style>
