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

        <!-- add to calendar: .ics file (Apple / Outlook / any app) + Google link -->
        <div v-if="title && status.key !== 'finished'" class="cg-event-dates__cal d-print-none">
            <span class="cg-event-dates__cal-label"><i class="fa fa-calendar-plus-o" aria-hidden="true"></i> {{ $t('guide.event_dates.add_to_calendar') }}</span>
            <a class="btn btn-sm btn-outline-secondary" :href="googleUrl" target="_blank" rel="noopener">Google</a>
            <button type="button" class="btn btn-sm btn-outline-secondary" @click="downloadIcs">{{ $t('guide.event_dates.ics') }}</button>
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
    const pad = n => String(n).padStart(2, '0');
    // floating local time (no Z): calendars place it in the viewer's zone, like the page does
    const icsDate = d => d.getFullYear() + pad(d.getMonth() + 1) + pad(d.getDate());
    const icsDateTime = d => icsDate(d) + 'T' + pad(d.getHours()) + pad(d.getMinutes()) + '00';
    const icsEscape = v => String(v || '').replace(/\\/g, '\\\\').replace(/[,;]/g, m => '\\' + m).replace(/\r?\n/g, '\\n');

    export default {
        name: 'EventDates',
        props: {
            start: { type: [String, Date], default: null },
            end: { type: [String, Date], default: null },
            title: { type: String, default: '' },
            details: { type: String, default: '' },
        },
        computed: {
            googleUrl() {
                const [from, to] = this.calRange();
                const q = new URLSearchParams({ action: 'TEMPLATE', text: this.title, dates: from + '/' + to, details: this.calDetails(), ctz: 'Asia/Tbilisi' });
                return 'https://calendar.google.com/calendar/render?' + q.toString();
            },
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
            // [start, end) for calendars: all-day events end the day after the last day
            calRange() {
                if (this.hasTime) {
                    const end = this.endDate && this.endDate > this.startDate ? this.endDate : new Date(this.startDate.getTime() + 2 * 3600 * 1000);
                    return [icsDateTime(this.startDate), icsDateTime(end)];
                }
                const last = this.endDate || this.startDate;
                return [icsDate(this.startDate), icsDate(new Date(last.getFullYear(), last.getMonth(), last.getDate() + 1))];
            },
            calDetails() {
                const text = String(this.details || '').replace(/<[^>]+>/g, ' ').replace(/&nbsp;/g, ' ').replace(/\s+/g, ' ').trim();
                return [text, window.location.href].filter(Boolean).join('\n\n');
            },
            downloadIcs() {
                const [from, to] = this.calRange();
                const type = this.hasTime ? '' : ';VALUE=DATE';
                const ics = [
                    'BEGIN:VCALENDAR', 'VERSION:2.0', 'PRODID:-//climbing.ge//events//EN', 'CALSCALE:GREGORIAN',
                    'BEGIN:VEVENT',
                    'UID:' + from + '-' + encodeURIComponent(this.title).slice(0, 40) + '@climbing.ge',
                    'DTSTAMP:' + new Date().toISOString().replace(/[-:]/g, '').replace(/\.\d+/, ''),
                    'DTSTART' + type + ':' + from,
                    'DTEND' + type + ':' + to,
                    'SUMMARY:' + icsEscape(this.title),
                    'DESCRIPTION:' + icsEscape(this.calDetails()),
                    'URL:' + window.location.href,
                    'END:VEVENT', 'END:VCALENDAR',
                ].join('\r\n');
                const a = document.createElement('a');
                a.href = URL.createObjectURL(new Blob([ics], { type: 'text/calendar;charset=utf-8' }));
                a.download = (this.title || 'event').replace(/[^\w\u10D0-\u10FF -]+/g, '').trim().slice(0, 60) + '.ics';
                document.body.appendChild(a);
                a.click();
                setTimeout(() => { URL.revokeObjectURL(a.href); a.remove(); }, 500);
            },
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
    .cg-event-dates__cal { display: flex; flex-wrap: wrap; align-items: center; gap: .5rem; margin-top: .9rem; padding-top: .8rem; border-top: 1px dashed var(--cg-line); }
    .cg-event-dates__cal-label { margin-right: .25rem; font-size: .85rem; font-weight: 800; color: var(--cg-ink-2); }
    .cg-event-dates__cal-label .fa { color: var(--cg-accent); }
    @media (max-width: 575.98px) {
        .cg-date-tile { flex: 1 1 8rem; min-width: 0; }
        .cg-event-dates__arrow { flex: 0 0 auto; }
    }
</style>
