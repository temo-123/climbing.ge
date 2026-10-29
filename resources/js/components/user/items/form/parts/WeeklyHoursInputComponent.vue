<template>
    <!-- Indoor gym "Working hours" tab: optional opening hours per weekday (article_working_hours).
         Off (null) = the same open / close time every day (Global info).
         On = 7 rows (Monday first): open, close, or "Closed"; pre-filled from
         the single times. The server checks the values and keeps
         open_time / closed_time as the week's most common hours. -->
    <div class="wh">
        <div class="form-check">
            <input :id="uid" class="form-check-input" type="checkbox" :checked="per_day" :disabled="disabled" @change="toggle($event.target.checked)">
            <label class="form-check-label" :for="uid">{{ $t('admin.articles.global_form.weekly_hours_toggle') }}</label>
        </div>
        <p class="wh__hint">{{ $t('admin.articles.global_form.weekly_hours_hint') }}</p>

        <div v-if="per_day && week" class="wh__table">
            <div v-for="(day, i) in days" :key="day" class="wh__row" :class="{ 'is-closed': week[day].closed }">
                <span class="wh__day">{{ names[i] }}</span>
                <input type="time" class="form-control form-control-sm" :value="week[day].open" :disabled="disabled || week[day].closed" :aria-label="names[i] + ' — ' + $t('admin.articles.global_form.start_time_placeholder')" @input="set(day, 'open', $event.target.value)">
                <span class="wh__dash">–</span>
                <input type="time" class="form-control form-control-sm" :value="week[day].close" :disabled="disabled || week[day].closed" :aria-label="names[i] + ' — ' + $t('admin.articles.global_form.end_time_placeholder')" @input="set(day, 'close', $event.target.value)">
                <label class="wh__closed">
                    <input type="checkbox" class="form-check-input" :checked="week[day].closed" :disabled="disabled" @change="set(day, 'closed', $event.target.checked)">
                    {{ $t('admin.articles.global_form.weekly_hours_closed') }}
                </label>
            </div>
            <button type="button" class="btn btn-outline-secondary btn-sm mt-2" :disabled="disabled" @click="copy_monday">
                <i class="fa fa-clone" aria-hidden="true"></i> {{ $t('admin.articles.global_form.weekly_hours_copy') }}
            </button>
        </div>
    </div>
</template>

<script>
    import { WEEK_DAYS, day_names, short_time } from '../../../../../services/guide/gymHours.js'

    let uid = 0;

    export default {
        name: 'WeeklyHoursInput',
        props: {
            modelValue: { default: null },          // { mon: { open, close } | { closed: true }, … } | JSON string | null
            default_open: { default: null },        // the single "every day" times, to pre-fill
            default_close: { default: null },
            disabled: { type: Boolean, default: false },
        },
        emits: ['update:modelValue'],
        data() {
            return { days: WEEK_DAYS, uid: 'wh-' + (++uid) };
        },
        computed: {
            parsed() {
                let v = this.modelValue;
                if (typeof v === 'string') { try { v = JSON.parse(v); } catch (e) { v = null; } }
                return v && typeof v === 'object' ? v : null;
            },
            per_day() { return !!this.parsed; },
            // every day present, as { open, close, closed }
            week() {
                if (!this.parsed) return null;
                const out = {};
                WEEK_DAYS.forEach(d => {
                    const x = this.parsed[d] || {};
                    out[d] = { open: short_time(x.open) || '', close: short_time(x.close) || '', closed: !!x.closed };
                });
                return out;
            },
            names() { return day_names(this.$i18n.locale === 'ka' ? 'ka-GE' : 'en-GB'); },
        },
        methods: {
            emit(week) {
                const out = {};
                WEEK_DAYS.forEach(d => {
                    out[d] = week[d].closed ? { closed: true } : { open: week[d].open || null, close: week[d].close || null };
                });
                this.$emit('update:modelValue', out);
            },
            toggle(on) {
                if (!on) { this.$emit('update:modelValue', null); return; }
                const open = short_time(this.default_open) || '', close = short_time(this.default_close) || '';
                const week = {};
                WEEK_DAYS.forEach(d => { week[d] = { open, close, closed: false }; });
                this.emit(week);
            },
            set(day, field, value) {
                const week = JSON.parse(JSON.stringify(this.week));
                week[day][field] = value;
                this.emit(week);
            },
            copy_monday() {
                const week = JSON.parse(JSON.stringify(this.week));
                WEEK_DAYS.forEach(d => { week[d] = { ...week.mon }; });
                this.emit(week);
            },
        },
    };
</script>

<style scoped>
    .wh { margin-top: .6rem; }
    .wh__hint { margin: .15rem 0 .4rem; color: var(--cg-muted); font-size: .82rem; }
    .wh__table { padding: .6rem .75rem; border: 1px solid var(--cg-line); border-radius: var(--cg-r); background: var(--cg-chalk); }
    .wh__row { display: grid; grid-template-columns: 8rem minmax(6rem, 9rem) auto minmax(6rem, 9rem) auto; justify-content: start; align-items: center; gap: .5rem; padding: .25rem 0; }
    .wh__row.is-closed .wh__day { color: var(--cg-muted); }
    .wh__day { font-weight: 700; text-transform: capitalize; }
    .wh__dash { color: var(--cg-muted); }
    .wh__closed { display: inline-flex; align-items: center; gap: .35rem; margin: 0; font-size: .88rem; white-space: nowrap; }
    @media (max-width: 575.98px) {
        .wh__row { grid-template-columns: 1fr 1fr; }
        .wh__day { grid-column: 1 / -1; }
        .wh__dash { display: none; }
    }
</style>
