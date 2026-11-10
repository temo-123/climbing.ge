<template>
    <!-- Reservation calendar: only start days the tour allows can be picked
         (season, start weekdays, booking lead time, and the whole trip must fit
         in the season). The chosen trip (start → end) is highlighted.
         v-model = "YYYY-MM-DD" (date only). docs/FUNCTIONS_DOCS/shop/tour_schedule.md -->
    <div class="cg-dp" :class="{ 'is-invalid': invalid }">
        <div class="cg-dp__season">
            <i class="fa fa-sun-o" aria-hidden="true"></i>
            <span v-if="!ranges.length">{{ $t('shop.tour.schedule.all_year') }}</span>
            <span v-else>{{ $t('shop.tour.schedule.season') }}: <strong>{{ ranges.join(', ') }}</strong></span>
            <span v-if="weekday_names" class="cg-dp__extra">· {{ $t('shop.tour.schedule.starts_on', { days: weekday_names }) }}</span>
            <span v-if="schedule && schedule.lead_days" class="cg-dp__extra">· {{ $t('shop.tour.schedule.lead', { n: schedule.lead_days }) }}</span>
        </div>

        <div class="cg-dp__months">
            <div v-for="(month, mi) in months" :key="month.key" class="cg-dp__month">
                <div class="cg-dp__head">
                    <button v-if="mi === 0" type="button" class="cg-dp__nav" :disabled="!can_prev" :aria-label="$t('shop.tour.schedule.prev')" @click="shift(-1)"><i class="fa fa-chevron-left" aria-hidden="true"></i></button>
                    <span v-else class="cg-dp__nav-space"></span>
                    <strong>{{ month.title }}</strong>
                    <button v-if="mi === months.length - 1" type="button" class="cg-dp__nav" :disabled="!can_next" :aria-label="$t('shop.tour.schedule.next')" @click="shift(1)"><i class="fa fa-chevron-right" aria-hidden="true"></i></button>
                    <span v-else class="cg-dp__nav-space"></span>
                </div>
                <div class="cg-dp__grid" role="grid">
                    <span v-for="w in weekday_heads" :key="w" class="cg-dp__wd">{{ w }}</span>
                    <span v-for="n in month.offset" :key="'e' + n"></span>
                    <button
                        v-for="cell in month.cells"
                        :key="cell.key"
                        type="button"
                        class="cg-dp__day"
                        :class="cell.cls"
                        :aria-disabled="cell.problem ? 'true' : 'false'"
                        :aria-pressed="cell.start ? 'true' : 'false'"
                        :title="cell.problem ? reason(cell.problem) : ''"
                        @click="pick(cell)"
                    >{{ cell.day }}</button>
                </div>
            </div>
        </div>

        <div class="cg-dp__foot">
            <div class="cg-dp__picked" aria-live="polite">
                <template v-if="picked">
                    <i class="fa fa-calendar-check-o" aria-hidden="true"></i>
                    <strong>{{ fmt(picked) }}</strong>
                    <span v-if="trip_days > 1"> → {{ fmt(trip_end) }}</span>
                </template>
                <span v-else-if="!note" class="cg-dp__muted">{{ $t('shop.tour.schedule.pick') }}</span>
                <span v-if="note" class="cg-dp__note">{{ note }}</span>
            </div>
        </div>

        <div class="cg-dp__legend" aria-hidden="true">
            <span><i class="is-free"></i> {{ $t('shop.tour.schedule.legend_free') }}</span>
            <span><i class="is-trip"></i> {{ $t('shop.tour.schedule.legend_trip') }}</span>
            <span><i class="is-off"></i> {{ $t('shop.tour.schedule.legend_off') }}</span>
        </div>
    </div>
</template>

<script>
    import { start_problem, in_season, season_ranges, parse_ymd, ymd, add_days, today } from '../../../../services/shop/tourSchedule.js'

    export default {
        name: 'TourDatePickerComponent',
        props: {
            modelValue: { type: String, default: '' },
            schedule: { type: Object, default: null },
            days: { type: [Number, String], default: 1 },
            invalid: { type: Boolean, default: false },
        },
        emits: ['update:modelValue'],
        data() {
            const t = today();
            return {
                view: new Date(t.getFullYear(), t.getMonth(), 1),
                note: '',
                wide: typeof window !== 'undefined' ? window.innerWidth >= 768 : true,
            };
        },
        computed: {
            locale() { return this.$i18n.locale === 'ka' ? 'ka-GE' : 'en-GB'; },
            trip_days() { return Math.max(parseInt(this.days, 10) || 1, 1); },
            picked() { return parse_ymd(this.modelValue); },
            trip_end() { return this.picked ? add_days(this.picked, this.trip_days - 1) : null; },
            ranges() { return season_ranges(this.schedule, this.locale); },
            // Monday first
            weekday_heads() {
                return [1, 2, 3, 4, 5, 6, 0].map(d => new Date(2024, 0, 7 + d).toLocaleDateString(this.locale, { weekday: 'short' }));
            },
            weekday_names() {
                const wd = this.schedule && this.schedule.start_weekdays;
                if (!Array.isArray(wd) || !wd.length || wd.length >= 7) return '';
                return wd.map(d => new Date(2024, 0, 7 + d).toLocaleDateString(this.locale, { weekday: 'long' })).join(', ');
            },
            can_prev() {
                const t = today();
                return this.view > new Date(t.getFullYear(), t.getMonth(), 1);
            },
            can_next() {
                const t = today();
                return this.view < new Date(t.getFullYear() + 2, t.getMonth(), 1);
            },
            // two months on desktop, one on phones
            months() {
                const list = [];
                for (let i = 0; i < (this.wide ? 2 : 1); i++) {
                    const first = new Date(this.view.getFullYear(), this.view.getMonth() + i, 1);
                    const count = new Date(first.getFullYear(), first.getMonth() + 1, 0).getDate();
                    const cells = [];
                    for (let d = 1; d <= count; d++) {
                        const date = new Date(first.getFullYear(), first.getMonth(), d);
                        const problem = start_problem(this.schedule, date, this.trip_days);
                        const start = this.picked && ymd(date) === ymd(this.picked);
                        const in_trip = this.picked && date > this.picked && date <= this.trip_end;
                        cells.push({
                            key: ymd(date), day: d, date, problem, start,
                            cls: {
                                'is-off': !!problem && !start && !in_trip,
                                'is-out': !in_season(this.schedule, date),
                                'is-start': start,
                                'is-trip': in_trip,
                                'is-end': in_trip && ymd(date) === ymd(this.trip_end),
                                'is-today': ymd(date) === ymd(today()),
                            },
                        });
                    }
                    list.push({
                        key: first.getFullYear() + '-' + first.getMonth(),
                        title: first.toLocaleDateString(this.locale, { month: 'long', year: 'numeric' }),
                        offset: (first.getDay() + 6) % 7,
                        cells,
                    });
                }
                return list;
            },
        },
        watch: {
            // a new duration or schedule can make the chosen day impossible → clear it
            days() { this.revalidate(); },
            schedule() { this.revalidate(); this.jump_to_first(); },
        },
        mounted() {
            this.onResize = () => { this.wide = window.innerWidth >= 768; };
            window.addEventListener('resize', this.onResize);
            this.jump_to_first();
        },
        beforeUnmount() {
            window.removeEventListener('resize', this.onResize);
        },
        methods: {
            shift(n) {
                if ((n < 0 && !this.can_prev) || (n > 0 && !this.can_next)) return;
                this.view = new Date(this.view.getFullYear(), this.view.getMonth() + n, 1);
            },
            // open the calendar on the first month that has a free day (e.g. winter tours in summer)
            jump_to_first() {
                if (this.picked) { this.view = new Date(this.picked.getFullYear(), this.picked.getMonth(), 1); return; }
                let d = today();
                for (let i = 0; i < 400; i++, d = add_days(d, 1)) {
                    if (!start_problem(this.schedule, d, this.trip_days)) {
                        this.view = new Date(d.getFullYear(), d.getMonth(), 1);
                        return;
                    }
                }
            },
            pick(cell) {
                if (cell.problem) { this.note = this.reason(cell.problem); return; }
                this.note = '';
                this.$emit('update:modelValue', ymd(cell.date));
            },
            revalidate() {
                if (this.picked && start_problem(this.schedule, this.picked, this.trip_days)) {
                    this.note = this.$t('shop.tour.schedule.cleared');
                    this.$emit('update:modelValue', '');
                }
            },
            reason(problem) {
                return this.$t('shop.tour.schedule.why_' + problem, { n: (this.schedule && this.schedule.lead_days) || 0 });
            },
            fmt(d) {
                return d.toLocaleDateString(this.locale, { weekday: 'short', day: 'numeric', month: 'short', year: 'numeric' });
            },
        },
    };
</script>

<style scoped>
.cg-dp {
    padding: .9rem 1rem 1rem;
    border: 1px solid var(--cg-line);
    border-radius: var(--cg-r);
    background: var(--cg-surface, #fff);
}
.cg-dp.is-invalid { border-color: #d64545; }
.cg-dp__season { margin-bottom: .75rem; font-size: .88rem; color: var(--cg-stone); }
.cg-dp__season .fa { color: #e0a100; margin-right: .25rem; }
.cg-dp__season strong { color: var(--cg-ink); }
.cg-dp__extra { margin-left: .25rem; }

.cg-dp__months { display: grid; grid-template-columns: repeat(auto-fit, minmax(230px, 1fr)); gap: 1.25rem; }
.cg-dp__head { display: flex; align-items: center; justify-content: space-between; margin-bottom: .4rem; }
.cg-dp__head strong { text-transform: capitalize; color: var(--cg-ink); }
.cg-dp__nav, .cg-dp__nav-space { width: 2rem; height: 2rem; }
.cg-dp__nav {
    display: grid;
    place-items: center;
    border: 1px solid var(--cg-line);
    border-radius: 50%;
    background: var(--cg-surface, #fff);
    color: var(--cg-ink-2);
    cursor: pointer;
}
.cg-dp__nav:disabled { opacity: .35; cursor: default; }

.cg-dp__grid { display: grid; grid-template-columns: repeat(7, 1fr); gap: 2px; }
.cg-dp__wd { padding: .2rem 0; font-size: .72rem; font-weight: 800; text-align: center; text-transform: uppercase; color: var(--cg-muted); }
.cg-dp__day {
    aspect-ratio: 1 / 1;
    min-height: 2rem;
    border: 0;
    border-radius: .45rem;
    background: var(--cg-accent-soft);
    font-weight: 700;
    font-size: .88rem;
    color: var(--cg-ink);
    cursor: pointer;
    transition: background .12s ease, color .12s ease;
}
.cg-dp__day:hover { background: var(--cg-accent); color: #fff; }
.cg-dp__day.is-today { box-shadow: inset 0 0 0 1.5px var(--cg-accent-dark); }
.cg-dp__day.is-off { background: transparent; color: #b9c0c6; font-weight: 500; cursor: not-allowed; }
.cg-dp__day.is-off.is-out { text-decoration: line-through; }
.cg-dp__day.is-off:hover { background: var(--cg-chalk); color: #b9c0c6; }
.cg-dp__day.is-trip { background: color-mix(in srgb, var(--cg-accent) 35%, #fff); color: var(--cg-ink); border-radius: 0; }
.cg-dp__day.is-start { background: var(--cg-accent); color: #fff; }
.cg-dp__day.is-end { border-radius: 0 .45rem .45rem 0; }

.cg-dp__foot {
    display: flex;
    flex-wrap: wrap;
    align-items: center;
    justify-content: space-between;
    gap: .5rem 1rem;
    margin-top: .85rem;
    padding-top: .75rem;
    border-top: 1px solid var(--cg-line);
}
.cg-dp__picked { color: var(--cg-ink-2); }
.cg-dp__picked .fa { color: var(--cg-accent-dark); margin-right: .25rem; }
.cg-dp__note { display: block; color: #b54708; font-weight: 600; font-size: .88rem; }
.cg-dp__muted { color: var(--cg-muted); }

.cg-dp__legend { display: flex; flex-wrap: wrap; gap: .4rem 1rem; margin-top: .6rem; font-size: .75rem; color: var(--cg-muted); }
.cg-dp__legend i { display: inline-block; width: .8rem; height: .8rem; margin-right: .25rem; border-radius: .2rem; vertical-align: -1px; }
.cg-dp__legend .is-free { background: var(--cg-accent-soft); }
.cg-dp__legend .is-trip { background: var(--cg-accent); }
.cg-dp__legend .is-off { background: var(--cg-chalk-2); }
</style>
