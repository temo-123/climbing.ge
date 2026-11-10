<template>
    <!-- Tour edit → "Season & duration" tab: when the tour can be booked and for
         how many days. Saved on its own. docs/FUNCTIONS_DOCS/shop/tour_schedule.md -->
    <div class="cg-ts">
        <div v-if="loading" class="text-center py-4"><div class="spinner-border text-primary" role="status"></div></div>

        <template v-else>
            <!-- season -->
            <section class="cg-ts__block">
                <h3><i class="fa fa-sun-o" aria-hidden="true"></i> {{ $t('admin.tour_schedule.season_title') }}</h3>
                <p class="cg-ts__help">{{ $t('admin.tour_schedule.season_help') }}</p>

                <div class="cg-ts__switch" role="radiogroup">
                    <button type="button" :class="{ 'is-active': form.all_year }" @click="form.all_year = true">
                        <i class="fa fa-refresh" aria-hidden="true"></i> {{ $t('admin.tour_schedule.all_year') }}
                    </button>
                    <button type="button" :class="{ 'is-active': !form.all_year }" @click="set_periods">
                        <i class="fa fa-calendar" aria-hidden="true"></i> {{ $t('admin.tour_schedule.periods') }}
                    </button>
                </div>

                <div v-if="!form.all_year" class="cg-ts__periods">
                    <div v-for="(p, i) in form.seasons" :key="p.key" class="cg-ts__period">
                        <span class="cg-ts__label">{{ $t('admin.tour_schedule.from') }}</span>
                        <input v-model.number="p.from_day" type="number" min="1" max="31" class="form-control form-control-sm cg-ts__day">
                        <select v-model.number="p.from_month" class="form-select form-select-sm cg-ts__month">
                            <option v-for="m in 12" :key="m" :value="m">{{ month_name(m) }}</option>
                        </select>
                        <span class="cg-ts__label">{{ $t('admin.tour_schedule.to') }}</span>
                        <input v-model.number="p.to_day" type="number" min="1" max="31" class="form-control form-control-sm cg-ts__day">
                        <select v-model.number="p.to_month" class="form-select form-select-sm cg-ts__month">
                            <option v-for="m in 12" :key="m" :value="m">{{ month_name(m) }}</option>
                        </select>
                        <span v-if="wraps(p)" class="cg-ts__wrap" :title="$t('admin.tour_schedule.wraps')"><i class="fa fa-repeat" aria-hidden="true"></i> {{ $t('admin.tour_schedule.wraps_short') }}</span>
                        <button type="button" class="btn btn-sm btn-outline-danger ms-auto" :title="$t('common.delete')" @click="form.seasons.splice(i, 1)"><i class="fa fa-trash" aria-hidden="true"></i></button>
                    </div>
                    <button type="button" class="btn btn-sm btn-outline-primary" @click="add_period"><i class="fa fa-plus" aria-hidden="true"></i> {{ $t('admin.tour_schedule.add_period') }}</button>
                </div>

                <!-- 12-month bar: the "graphic" of when the tour runs -->
                <div class="cg-ts__year" :aria-label="$t('admin.tour_schedule.year_bar')">
                    <div v-for="(on, i) in months_on" :key="i" class="cg-ts__m" :class="{ 'is-on': on }">
                        <span>{{ month_short(i + 1) }}</span>
                    </div>
                </div>
            </section>

            <!-- duration -->
            <section class="cg-ts__block">
                <h3><i class="fa fa-clock-o" aria-hidden="true"></i> {{ $t('admin.tour_schedule.duration_title') }}</h3>
                <p class="cg-ts__help">{{ $t('admin.tour_schedule.duration_help') }}</p>

                <!-- buttons, not radio+label: the page's .tabs CSS restyles every radio label -->
                <div class="cg-ts__modes" role="radiogroup">
                    <button type="button" role="radio" class="cg-ts__mode" :class="{ 'is-active': form.duration_mode === null }" :aria-checked="form.duration_mode === null ? 'true' : 'false'" @click="form.duration_mode = null">
                        <strong>{{ $t('admin.tour_schedule.mode_prices') }}</strong>
                        <small>{{ price_durations.length ? days_list(price_durations) : $t('admin.tour_schedule.mode_prices_none') }}</small>
                    </button>
                    <button type="button" role="radio" class="cg-ts__mode" :class="{ 'is-active': form.duration_mode === 'fixed' }" :aria-checked="form.duration_mode === 'fixed' ? 'true' : 'false'" @click="form.duration_mode = 'fixed'">
                        <strong>{{ $t('admin.tour_schedule.mode_fixed') }}</strong>
                        <small>{{ $t('admin.tour_schedule.mode_fixed_help') }}</small>
                    </button>
                    <button type="button" role="radio" class="cg-ts__mode" :class="{ 'is-active': form.duration_mode === 'range' }" :aria-checked="form.duration_mode === 'range' ? 'true' : 'false'" @click="form.duration_mode = 'range'">
                        <strong>{{ $t('admin.tour_schedule.mode_range') }}</strong>
                        <small>{{ $t('admin.tour_schedule.mode_range_help') }}</small>
                    </button>
                </div>

                <div v-if="form.duration_mode === 'fixed'" class="cg-ts__chips">
                    <button v-for="d in 21" :key="d" type="button" :class="{ 'is-active': form.duration_days.includes(d) }" @click="toggle_day(d)">{{ d }}</button>
                    <span class="cg-ts__chips-note">{{ $t('admin.tour_schedule.days_unit') }}</span>
                </div>
                <div v-if="form.duration_mode === 'range'" class="cg-ts__range">
                    <span>{{ $t('admin.tour_schedule.from') }}</span>
                    <input v-model.number="form.duration_min" type="number" min="1" max="60" class="form-control form-control-sm cg-ts__day">
                    <span>{{ $t('admin.tour_schedule.to') }}</span>
                    <input v-model.number="form.duration_max" type="number" min="1" max="60" class="form-control form-control-sm cg-ts__day">
                    <span>{{ $t('admin.tour_schedule.days_unit') }}</span>
                </div>
                <p v-if="unpriced.length" class="cg-ts__warn">
                    <i class="fa fa-info-circle" aria-hidden="true"></i>
                    {{ $t('admin.tour_schedule.unpriced', { days: unpriced.join(', ') }) }}
                </p>
            </section>

            <!-- start days + lead time -->
            <section class="cg-ts__block">
                <h3><i class="fa fa-flag-o" aria-hidden="true"></i> {{ $t('admin.tour_schedule.group_title') }}</h3>
                <p class="cg-ts__help">{{ $t('admin.tour_schedule.start_help') }}</p>
                <div class="cg-ts__chips">
                    <button v-for="d in [1, 2, 3, 4, 5, 6, 0]" :key="d" type="button" class="is-wide" :class="{ 'is-active': form.start_weekdays.includes(d) }" @click="toggle_weekday(d)">{{ weekday_name(d) }}</button>
                </div>
                <div class="cg-ts__lead">
                    <span>{{ $t('admin.tour_schedule.max_before') }}</span>
                    <input v-model="form.max_persons" type="number" min="1" max="100" :placeholder="$t('admin.tour_schedule.max_none')" :aria-label="$t('admin.tour_schedule.max_before')" class="form-control form-control-sm cg-ts__day">
                    <span>{{ $t('admin.tour_schedule.max_after') }}</span>
                </div>
                <div class="cg-ts__lead">
                    <span>{{ $t('admin.tour_schedule.lead_before') }}</span>
                    <input v-model.number="form.lead_days" type="number" min="0" max="365" :aria-label="$t('admin.tour_schedule.lead_before')" class="form-control form-control-sm cg-ts__day">
                    <span>{{ $t('admin.tour_schedule.lead_after') }}</span>
                </div>
            </section>

            <div v-if="error" class="alert alert-danger py-2">{{ error }}</div>
            <div class="cg-ts__actions">
                <button type="button" class="btn btn-primary" :disabled="saving" @click="save">
                    <span v-if="saving" class="spinner-border spinner-border-sm me-1" aria-hidden="true"></span>
                    {{ $t('admin.tour_schedule.save') }}
                </button>
            </div>
        </template>
    </div>
</template>

<script>
    import { active_months } from '../../../../../services/shop/tourSchedule.js'

    let uid = 0;
    const period = p => ({ key: ++uid, from_month: p.from_month || 12, from_day: p.from_day || 1, to_month: p.to_month || 3, to_day: p.to_day || 15 });

    export default {
        name: 'TourScheduleEditorComponent',
        props: { tour_id: { type: [Number, String], required: true } },
        data() {
            return {
                loading: true,
                saving: false,
                error: '',
                price_durations: [],
                form: {
                    all_year: true,
                    seasons: [],
                    duration_mode: null,
                    duration_days: [],
                    duration_min: 1,
                    duration_max: 3,
                    start_weekdays: [0, 1, 2, 3, 4, 5, 6],
                    lead_days: 0,
                    max_persons: '',
                },
            };
        },
        computed: {
            locale() { return this.$i18n.locale === 'ka' ? 'ka-GE' : 'en-GB'; },
            months_on() { return active_months({ all_year: this.form.all_year, seasons: this.form.seasons }); },
            offered() {
                if (this.form.duration_mode === 'fixed') return [...this.form.duration_days].sort((a, b) => a - b);
                if (this.form.duration_mode === 'range') {
                    const min = parseInt(this.form.duration_min, 10) || 1;
                    const max = Math.max(min, parseInt(this.form.duration_max, 10) || min);
                    return Array.from({ length: Math.min(max - min + 1, 60) }, (_, i) => min + i);
                }
                return [];
            },
            // offered durations without a price row → "price on request" in the form
            unpriced() {
                if (!this.price_durations.length) return [];
                return this.offered.filter(d => !this.price_durations.includes(d));
            },
        },
        mounted() { this.load(); },
        methods: {
            month_name(m) { return new Date(2024, m - 1, 1).toLocaleDateString(this.locale, { month: 'long' }); },
            month_short(m) { return new Date(2024, m - 1, 1).toLocaleDateString(this.locale, { month: 'short' }); },
            weekday_name(d) { return new Date(2024, 0, 7 + d).toLocaleDateString(this.locale, { weekday: 'short' }); },
            days_list(list) { return list.map(d => d + ' ' + this.$t('admin.tour_schedule.days_unit')).join(', '); },
            wraps(p) { return p.from_month * 100 + p.from_day > p.to_month * 100 + p.to_day; },
            set_periods() {
                this.form.all_year = false;
                if (!this.form.seasons.length) this.add_period();
            },
            add_period() { this.form.seasons.push(period({})); },
            toggle_day(d) {
                const i = this.form.duration_days.indexOf(d);
                if (i === -1) this.form.duration_days.push(d); else this.form.duration_days.splice(i, 1);
            },
            toggle_weekday(d) {
                const i = this.form.start_weekdays.indexOf(d);
                if (i === -1) this.form.start_weekdays.push(d);
                else if (this.form.start_weekdays.length > 1) this.form.start_weekdays.splice(i, 1);
            },
            load() {
                this.loading = true;
                axios.get('/set_tour/get_tour_schedule/' + this.tour_id)
                    .then(r => this.fill(r.data))
                    .catch(() => { this.error = this.$t('admin.tour_schedule.load_failed'); })
                    .finally(() => { this.loading = false; });
            },
            fill(d) {
                this.price_durations = (d.price_durations || []).map(Number);
                this.form = {
                    all_year: !!d.all_year,
                    seasons: (d.seasons || []).map(period),
                    duration_mode: d.duration_mode || null,
                    duration_days: (d.duration_days || []).map(Number),
                    duration_min: d.duration_min || 1,
                    duration_max: d.duration_max || d.duration_min || 3,
                    start_weekdays: Array.isArray(d.start_weekdays) && d.start_weekdays.length ? d.start_weekdays.map(Number) : [0, 1, 2, 3, 4, 5, 6],
                    lead_days: d.lead_days || 0,
                    max_persons: d.max_persons || '',
                };
            },
            save() {
                if (this.saving) return;
                this.saving = true;
                this.error = '';
                const f = this.form;
                axios.post('/set_tour/save_tour_schedule/' + this.tour_id, {
                    all_year: f.all_year,
                    seasons: f.all_year ? [] : f.seasons.map(({ from_month, from_day, to_month, to_day }) => ({ from_month, from_day, to_month, to_day })),
                    duration_mode: f.duration_mode,
                    duration_days: f.duration_days,
                    duration_min: f.duration_min,
                    duration_max: f.duration_max,
                    start_weekdays: f.start_weekdays,
                    lead_days: f.lead_days || 0,
                    max_persons: f.max_persons === '' || f.max_persons === null ? null : parseInt(f.max_persons, 10),
                })
                    .then(r => {
                        this.fill(r.data);
                        this.$notify && this.$notify({ type: 'success', text: this.$t('admin.tour_schedule.saved') });
                    })
                    .catch(e => { this.error = (e.response && e.response.data && e.response.data.message) || this.$t('admin.tour_schedule.save_failed'); })
                    .finally(() => { this.saving = false; });
            },
        },
    };
</script>

<style scoped>
.cg-ts { width: 100%; }
.cg-ts__block {
    margin-bottom: 1.25rem;
    padding: 1.1rem 1.2rem;
    border: 1px solid var(--cg-line);
    border-radius: var(--cg-r);
    background: var(--cg-surface, #fff);
}
.cg-ts__block h3 { display: flex; align-items: center; gap: .5rem; margin: 0 0 .25rem; font-family: var(--cg-font-display); font-size: 1.3rem; color: var(--cg-ink); }
.cg-ts__block h3 .fa { color: var(--cg-accent-dark); font-size: 1.05rem; }
.cg-ts__help { margin: 0 0 .85rem; max-width: 720px; font-size: .9rem; color: var(--cg-stone); }

.cg-ts__switch { display: inline-flex; gap: 4px; padding: 4px; margin-bottom: .85rem; border-radius: 2rem; background: var(--cg-chalk-2); }
.cg-ts__switch button { padding: .45rem 1rem; border: 0; border-radius: 2rem; background: transparent; font-weight: 700; color: var(--cg-stone); cursor: pointer; }
.cg-ts__switch button.is-active { background: #fff; color: var(--cg-accent-dark); box-shadow: var(--cg-shadow-sm); }

.cg-ts__periods { display: flex; flex-direction: column; gap: .5rem; align-items: flex-start; margin-bottom: 1rem; }
.cg-ts__period { display: flex; flex-wrap: wrap; align-items: center; gap: .4rem; width: 100%; max-width: 720px; padding: .5rem .6rem; border-radius: var(--cg-r-sm, .5rem); background: var(--cg-chalk); }
.cg-ts__label { font-size: .82rem; font-weight: 700; color: var(--cg-stone); }
.cg-ts__day { width: 4.5rem; }
.cg-ts__month { width: 9rem; }
.cg-ts__wrap { font-size: .78rem; font-weight: 700; color: var(--cg-accent-dark); }

.cg-ts__year { display: grid; grid-template-columns: repeat(12, 1fr); gap: 3px; max-width: 720px; }
.cg-ts__m { padding: .5rem 0 .35rem; border-radius: .35rem; background: var(--cg-chalk-2); text-align: center; }
.cg-ts__m span { font-size: .72rem; font-weight: 800; text-transform: uppercase; color: var(--cg-muted); }
.cg-ts__m.is-on { background: var(--cg-accent); }
.cg-ts__m.is-on span { color: #fff; }

.cg-ts__modes { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: .6rem; margin-bottom: .85rem; }
.cg-ts__mode { display: flex; flex-direction: column; align-items: flex-start; gap: .15rem; padding: .7rem .85rem; border: 1px solid var(--cg-line); border-radius: var(--cg-r-sm, .5rem); background: var(--cg-surface, #fff); text-align: left; cursor: pointer; }
.cg-ts__mode:hover { border-color: var(--cg-accent); }
.cg-ts__mode strong { color: var(--cg-ink); }
.cg-ts__mode small { color: var(--cg-muted); }
.cg-ts__mode.is-active { border-color: var(--cg-accent); background: var(--cg-accent-soft); box-shadow: inset 0 0 0 1px var(--cg-accent); }

.cg-ts__chips { display: flex; flex-wrap: wrap; align-items: center; gap: .35rem; margin-bottom: .75rem; }
.cg-ts__chips button { min-width: 2.4rem; padding: .35rem .5rem; border: 1px solid var(--cg-line); border-radius: .5rem; background: var(--cg-surface, #fff); font-weight: 700; color: var(--cg-ink-2); cursor: pointer; }
.cg-ts__chips button.is-wide { min-width: 3.6rem; text-transform: capitalize; }
.cg-ts__chips button.is-active { border-color: var(--cg-accent); background: var(--cg-accent); color: #fff; }
.cg-ts__chips-note { margin-left: .25rem; font-size: .85rem; color: var(--cg-muted); }
.cg-ts__range { display: flex; align-items: center; gap: .5rem; margin-bottom: .75rem; font-weight: 700; color: var(--cg-stone); }
.cg-ts__warn { margin: 0; font-size: .85rem; color: #8a6100; }
.cg-ts__lead { display: flex; flex-wrap: wrap; align-items: center; gap: .5rem; margin: .25rem 0 0; font-weight: 600; color: var(--cg-ink-2); }

.cg-ts__actions { display: flex; justify-content: flex-end; }
@media (max-width: 575.98px) {
    .cg-ts__year { grid-template-columns: repeat(6, 1fr); }
}
</style>
