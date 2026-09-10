<template>
    <div class="d-flex gap-2 partial-date-input">
        <select class="form-control" :value="year" @change="onYearChange($event.target.value)">
            <option value="">{{ $t('admin.routes_sectors.date_year_placeholder') }}</option>
            <option v-for="y in years" :key="y" :value="y">{{ y }}</option>
        </select>

        <select class="form-control" :value="month" :disabled="!year" @change="onMonthChange($event.target.value)">
            <option value="">{{ $t('admin.routes_sectors.date_month_placeholder') }}</option>
            <option v-for="m in months" :key="m" :value="m">{{ m }}</option>
        </select>

        <select class="form-control" :value="day" :disabled="!month" @change="onDayChange($event.target.value)">
            <option value="">{{ $t('admin.routes_sectors.date_day_placeholder') }}</option>
            <option v-for="d in days" :key="d" :value="d">{{ d }}</option>
        </select>
    </div>
</template>

<script>
// Reusable "approximate date" picker for fields where the exact day (or even
// month) of an event often isn't known — bolting date and first-ascent date,
// across Route/Mtp/Mtp-pitch. A plain <input type="date"> forces picking a
// full YYYY-MM-DD, which isn't always known/true; this instead cascades
// Year -> Month -> Day, each disabled until the one before it is set, and
// emits whichever precision the user actually filled in: "" (nothing set),
// "YYYY" (year only), "YYYY-MM" (year+month), or "YYYY-MM-DD" (full date).
// Clearing a more-significant field clears everything after it, since e.g.
// a day without a month is meaningless.
//
// Backing column must be a plain string (routes.creation_data/mtp_pitchs.
// creation_data always were; routes.first_ascent_date and the new mtp(s)/
// mtp_pitchs first_ascent_date columns were added/converted to string
// specifically to support this — a real SQL `date` column can't hold a bare
// year or year-month).
export default {
    props: {
        modelValue: { type: String, default: '' },
    },
    emits: ['update:modelValue'],
    data() {
        return {
            year: '',
            month: '',
            day: '',
        };
    },
    computed: {
        years() {
            const current = new Date().getFullYear();
            const list = [];
            for (let y = current; y >= 1900; y--) list.push(String(y));
            return list;
        },
        months() {
            const list = [];
            for (let m = 1; m <= 12; m++) list.push(String(m).padStart(2, '0'));
            return list;
        },
        days() {
            const daysInMonth = (this.year && this.month)
                ? new Date(Number(this.year), Number(this.month), 0).getDate()
                : 31;
            const list = [];
            for (let d = 1; d <= daysInMonth; d++) list.push(String(d).padStart(2, '0'));
            return list;
        },
    },
    watch: {
        modelValue: {
            immediate: true,
            handler(value) {
                const parts = (value || '').split('-');
                this.year = parts[0] || '';
                this.month = parts[1] || '';
                this.day = parts[2] || '';
            },
        },
    },
    methods: {
        emitValue() {
            let value = '';
            if (this.year) {
                value = this.year;
                if (this.month) {
                    value += '-' + this.month;
                    if (this.day) value += '-' + this.day;
                }
            }
            this.$emit('update:modelValue', value);
        },
        onYearChange(value) {
            this.year = value;
            if (!value) { this.month = ''; this.day = ''; }
            this.emitValue();
        },
        onMonthChange(value) {
            this.month = value;
            if (!value) this.day = '';
            this.emitValue();
        },
        onDayChange(value) {
            this.day = value;
            this.emitValue();
        },
    },
};
</script>
