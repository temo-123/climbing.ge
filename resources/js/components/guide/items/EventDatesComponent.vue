<template>
    <!-- When an event happens, at a glance: a status pill (starts in N days /
         today / happening now / finished) and date tiles — Start → Finish with
         the duration between them, or one tile with a time range for a
         single-day event. Month and weekday names follow the site language. -->
    <div v-if="startDate" class="cg-event-dates">
        <span class="cg-event-dates__status" :class="'is-' + status.key">
            <i class="fa" :class="status.icon" aria-hidden="true"></i> {{ status.text }}
        </span>

        <div class="cg-event-dates__range">
            <div class="cg-date-tile">
                <span class="cg-date-tile__label">{{ sameDay ? $t('guide.event_dates.date') : $t('guide.event_dates.starts') }}</span>
                <span class="cg-date-tile__weekday">{{ parts(startDate).weekday }}</span>
                <span class="cg-date-tile__day">{{ parts(startDate).day }}</span>
                <span class="cg-date-tile__month">{{ parts(startDate).month }} {{ parts(startDate).year }}</span>
                <span class="cg-date-tile__time" v-if="hasTime">
                    <i class="fa fa-clock-o" aria-hidden="true"></i>
                    {{ parts(startDate).time }}<template v-if="sameDay && endDate"> – {{ parts(endDate).time }}</template>
                </span>
            </div>

            <template v-if="endDate && !sameDay">
                <div class="cg-event-dates__arrow" aria-hidden="true">
                    <i class="fa fa-long-arrow-right"></i>
                    <small>{{ duration }}</small>
                </div>
                <div class="cg-date-tile">
                    <span class="cg-date-tile__label">{{ $t('guide.event_dates.ends') }}</span>
                    <span class="cg-date-tile__weekday">{{ parts(endDate).weekday }}</span>
                    <span class="cg-date-tile__day">{{ parts(endDate).day }}</span>
                    <span class="cg-date-tile__month">{{ parts(endDate).month }} {{ parts(endDate).year }}</span>
                    <span class="cg-date-tile__time" v-if="hasTime"><i class="fa fa-clock-o" aria-hidden="true"></i> {{ parts(endDate).time }}</span>
                </div>
            </template>
        </div>
    </div>
</template>

<script>
    const DAY = 24 * 60 * 60 * 1000;
    // "2023-04-28 10:00:00" (server format) or ISO → Date
    const toDate = v => {
        if (!v) return null;
        const d = new Date(String(v).replace(' ', 'T'));
        return isNaN(d) ? null : d;
    };
    const startOfDay = d => new Date(d.getFullYear(), d.getMonth(), d.getDate());

    export default {
        name: 'EventDates',
        props: {
            start: { type: [String, Date], default: null },
            end: { type: [String, Date], default: null },
        },
        computed: {
            startDate() { return toDate(this.start); },
            endDate() { return toDate(this.end); },
            intlLocale() { return this.$i18n && this.$i18n.locale === 'ka' ? 'ka-GE' : 'en-GB'; },
            sameDay() {
                return !this.endDate || startOfDay(this.startDate).getTime() === startOfDay(this.endDate).getTime();
            },
            // events saved without a time sit at midnight: don't show "00:00"
            hasTime() {
                return this.startDate.getHours() + this.startDate.getMinutes() > 0
                    || (this.endDate && this.endDate.getHours() + this.endDate.getMinutes() > 0);
            },
            duration() {
                const days = Math.round((startOfDay(this.endDate) - startOfDay(this.startDate)) / DAY) + 1;
                return this.$t('guide.event_dates.days', { n: days });
            },
            status() {
                const now = new Date();
                const end = this.endDate || new Date(startOfDay(this.startDate).getTime() + DAY - 1);
                if (now > end) return { key: 'finished', icon: 'fa-flag-checkered', text: this.$t('guide.event_dates.finished') };
                if (now >= this.startDate) return { key: 'live', icon: 'fa-circle', text: this.$t('guide.event_dates.happening_now') };
                const days = Math.round((startOfDay(this.startDate) - startOfDay(now)) / DAY);
                if (days === 0) return { key: 'soon', icon: 'fa-bell', text: this.$t('guide.event_dates.starts_today') };
                return { key: 'upcoming', icon: 'fa-calendar', text: this.$t('guide.event_dates.starts_in', { n: days }) };
            },
        },
        methods: {
            parts(d) {
                const f = o => new Intl.DateTimeFormat(this.intlLocale, o).format(d);
                return {
                    weekday: f({ weekday: 'long' }),
                    day: d.getDate(),
                    month: f({ month: 'long' }),
                    year: d.getFullYear(),
                    time: f({ hour: '2-digit', minute: '2-digit', hour12: false }),
                };
            },
        },
    };
</script>

<style scoped>
    .cg-event-dates {
        margin: .25rem 0 1.5rem;
        padding: 1rem 1.15rem;
        background: #fff;
        border: 1px solid var(--cg-line);
        border-radius: var(--cg-r-lg);
        box-shadow: var(--cg-shadow-sm);
    }
    .cg-event-dates__status {
        display: inline-flex;
        align-items: center;
        gap: .4rem;
        margin-bottom: .85rem;
        padding: .3rem .8rem;
        border-radius: 2rem;
        font-size: .8rem;
        font-weight: 800;
        letter-spacing: .02em;
        background: var(--cg-accent-soft);
        color: var(--cg-accent-dark);
    }
    .cg-event-dates__status.is-live { background: #e3f6ec; color: #13865d; }
    .cg-event-dates__status.is-live .fa { font-size: .6rem; animation: cg-live 1.4s ease-in-out infinite; }
    .cg-event-dates__status.is-soon { background: #fff4d6; color: #8a5a00; }
    .cg-event-dates__status.is-finished { background: var(--cg-chalk-2); color: var(--cg-stone); }
    @keyframes cg-live { 50% { opacity: .3; } }
    @media (prefers-reduced-motion: reduce) { .cg-event-dates__status .fa { animation: none; } }

    .cg-event-dates__range {
        display: flex;
        flex-wrap: wrap;
        align-items: stretch;
        gap: .75rem;
    }
    .cg-date-tile {
        display: flex;
        flex-direction: column;
        align-items: flex-start;
        min-width: 9.5rem;
        padding: .7rem .95rem .8rem;
        border-radius: var(--cg-r);
        background: var(--cg-chalk);
        border-top: 4px solid var(--cg-accent);
        line-height: 1.15;
    }
    .cg-date-tile__label {
        margin-bottom: .35rem;
        font-size: .68rem;
        font-weight: 800;
        letter-spacing: .1em;
        text-transform: uppercase;
        color: var(--cg-accent-dark);
    }
    .cg-date-tile__weekday { font-size: .82rem; font-weight: 700; color: var(--cg-stone); text-transform: capitalize; }
    .cg-date-tile__day {
        font-family: var(--cg-font-display);
        font-size: 2.6rem;
        font-weight: 700;
        line-height: 1;
        color: var(--cg-ink);
    }
    .cg-date-tile__month { font-size: .95rem; font-weight: 700; color: var(--cg-ink-2); text-transform: capitalize; }
    .cg-date-tile__time {
        margin-top: .45rem;
        padding: .15rem .5rem;
        border-radius: 2rem;
        background: #fff;
        font-size: .82rem;
        font-weight: 700;
        color: var(--cg-ink-2);
    }
    .cg-event-dates__arrow {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        gap: .2rem;
        color: var(--cg-muted);
        font-size: 1.4rem;
    }
    .cg-event-dates__arrow small {
        font-size: .75rem;
        font-weight: 800;
        color: var(--cg-stone);
        white-space: nowrap;
    }
    @media (max-width: 575.98px) {
        .cg-date-tile { flex: 1 1 8rem; min-width: 0; }
        .cg-event-dates__arrow { flex: 0 0 auto; }
    }
</style>
