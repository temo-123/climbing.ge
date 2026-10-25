// Event dates for cards, lists and the event page. Server format
// "2026-12-24 20:53:00" (local time). Status is decided on real dates —
// the old cards compared day and month numbers separately and got it
// wrong across months and years.
const DAY = 86400000;

export function to_date(v) {
    if (!v) return null;
    const d = new Date(String(v).replace(' ', 'T'));
    return isNaN(d) ? null : d;
}

const start_of_day = d => new Date(d.getFullYear(), d.getMonth(), d.getDate());

// { key: upcoming | today | live | finished, days } for a global_event
export function event_status(ev, now = new Date()) {
    const start = to_date(ev && ev.start_data);
    if (!start) return { key: 'upcoming', days: null };
    const end = to_date(ev.end_data) || new Date(start_of_day(start).getTime() + DAY - 1);
    if (now > end) return { key: 'finished', days: null };
    if (now >= start) return { key: 'live', days: 0 };
    const days = Math.round((start_of_day(start) - start_of_day(now)) / DAY);
    return { key: days === 0 ? 'today' : 'upcoming', days };
}

// events saved without a time sit at midnight
export function has_time(ev) {
    const s = to_date(ev && ev.start_data), e = to_date(ev && ev.end_data);
    return !!((s && s.getHours() + s.getMinutes()) || (e && e.getHours() + e.getMinutes()));
}

export function fmt(d, locale, opts) {
    return d ? new Intl.DateTimeFormat(locale, opts).format(d) : '';
}

// "24 – 30 Dec 2026", "24 Dec – 2 Jan 2027", "24 Dec 2026"
export function range_label(ev, locale) {
    const s = to_date(ev.start_data), e = to_date(ev.end_data);
    if (!s) return '';
    if (!e || start_of_day(s).getTime() === start_of_day(e).getTime()) return fmt(s, locale, { day: 'numeric', month: 'short', year: 'numeric' });
    if (s.getFullYear() !== e.getFullYear()) return fmt(s, locale, { day: 'numeric', month: 'short', year: 'numeric' }) + ' – ' + fmt(e, locale, { day: 'numeric', month: 'short', year: 'numeric' });
    if (s.getMonth() !== e.getMonth()) return fmt(s, locale, { day: 'numeric', month: 'short' }) + ' – ' + fmt(e, locale, { day: 'numeric', month: 'short', year: 'numeric' });
    return s.getDate() + ' – ' + fmt(e, locale, { day: 'numeric', month: 'short', year: 'numeric' });
}

export function intl_locale(i18n) {
    return i18n && i18n.locale === 'ka' ? 'ka-GE' : 'en-GB';
}
