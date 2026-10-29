// Indoor gym opening hours — one place for the card, the list ("open now"
// filter / sort) and the gym page. A gym has either the same hours every day
// (global_data.open_time / closed_time) or hours per weekday
// (global_data.weekly_hours: { mon: { open, close } | { closed: true }, … sun }).
// Closing after midnight (e.g. 18:00 – 01:00) belongs to the day it opened.

export const WEEK_DAYS = ['mon', 'tue', 'wed', 'thu', 'fri', 'sat', 'sun'];

// Minutes since midnight for "HH:mm" or "HH:mm:ss"
export function to_minutes(time) {
    const [h, m] = String(time).split(':').map(Number);
    return h * 60 + (m || 0);
}

export function short_time(time) {
    return time ? String(time).slice(0, 5) : '';
}

// JS getDay() (0 = Sunday) → 'mon' … 'sun'
export function day_key(date) {
    return WEEK_DAYS[(date.getDay() + 6) % 7];
}

export function weekly(gym) {
    let w = gym && gym.weekly_hours;
    if (typeof w === 'string') { try { w = JSON.parse(w); } catch (e) { w = null; } }
    return w && typeof w === 'object' ? w : null;
}

// { open, close } for a weekday, or null when closed / unknown
export function hours_for_day(gym, day) {
    const w = weekly(gym);
    if (w) {
        const d = w[day];
        return d && !d.closed && d.open && d.close ? { open: short_time(d.open), close: short_time(d.close) } : null;
    }
    return gym && gym.open_time && gym.closed_time ? { open: short_time(gym.open_time), close: short_time(gym.closed_time) } : null;
}

export function has_hours(gym) {
    return WEEK_DAYS.some(d => hours_for_day(gym, d));
}

// is it open at `now`? also counts last night's hours that run past midnight
export function open_window(gym, now) {
    const minutes = now.getHours() * 60 + now.getMinutes();
    const today = hours_for_day(gym, day_key(now));
    if (today) {
        const o = to_minutes(today.open), c = to_minutes(today.close);
        if (c > o ? (minutes >= o && minutes < c) : minutes >= o) return { open: true, closes_in: (c - minutes + 1440) % 1440 };
    }
    const yesterday = new Date(now.getTime() - 86400000);
    const prev = hours_for_day(gym, day_key(yesterday));
    if (prev) {
        const o = to_minutes(prev.open), c = to_minutes(prev.close);
        if (c <= o && minutes < c) return { open: true, closes_in: c - minutes };
    }
    return { open: false, opens_in: today && to_minutes(today.open) > minutes ? to_minutes(today.open) - minutes : null };
}

export function is_open_now(gym, now) {
    if (!gym || gym.work_status) return false;
    return open_window(gym, now).open;
}

// badge for cards / the gym page: open / soon / closed + its label key
export function open_state(gym, now) {
    const w = open_window(gym, now);
    if (w.open) {
        return w.closes_in <= 60
            ? { state: 'soon', key: 'guide.article.open_status.closes_in', min: w.closes_in }
            : { state: 'open', key: 'guide.article.open_status.open_now' };
    }
    return w.opens_in !== null && w.opens_in <= 60
        ? { state: 'soon', key: 'guide.article.open_status.opens_in', min: w.opens_in }
        : { state: 'closed', key: 'guide.article.open_status.closed_now' };
}

// weekday names in the site language, Monday first
export function day_names(locale, style = 'long') {
    const fmt = new Intl.DateTimeFormat(locale, { weekday: style });
    // 2024-01-01 was a Monday
    return WEEK_DAYS.map((_, i) => fmt.format(new Date(2024, 0, 1 + i)));
}
