// Tour season + duration schedule — same rules as App\Services\TourScheduleService
// (docs/FUNCTIONS_DOCS/shop/tour_schedule.md). The server checks again on save.
//
// schedule = { all_year, seasons: [{from_month, from_day, to_month, to_day}],
//              durations: [1,2,3], start_weekdays: null | [0..6], lead_days }

export const pad = n => String(n).padStart(2, '0');
export const ymd = d => `${d.getFullYear()}-${pad(d.getMonth() + 1)}-${pad(d.getDate())}`;
export const parse_ymd = s => {
    const m = /^(\d{4})-(\d{2})-(\d{2})/.exec(String(s || ''));
    return m ? new Date(+m[1], +m[2] - 1, +m[3]) : null;
};
const add_days = (d, n) => new Date(d.getFullYear(), d.getMonth(), d.getDate() + n);
const today = () => { const t = new Date(); return new Date(t.getFullYear(), t.getMonth(), t.getDate()); };

export function in_season(schedule, day) {
    if (!schedule || schedule.all_year || !schedule.seasons || !schedule.seasons.length) return true;
    const md = (day.getMonth() + 1) * 100 + day.getDate();
    return schedule.seasons.some(s => {
        const from = s.from_month * 100 + s.from_day, to = s.to_month * 100 + s.to_day;
        return from <= to ? (md >= from && md <= to) : (md >= from || md <= to);
    });
}

// why a start day can't be chosen: null (ok) | 'past' | 'lead' | 'weekday' | 'season' | 'end'
export function start_problem(schedule, day, days) {
    const first = add_days(today(), (schedule && schedule.lead_days) || 0);
    if (day < today()) return 'past';
    if (day < first) return 'lead';
    const wd = schedule && schedule.start_weekdays;
    if (Array.isArray(wd) && wd.length && !wd.includes(day.getDay())) return 'weekday';
    if (!in_season(schedule, day)) return 'season';
    const n = Math.max(parseInt(days, 10) || 1, 1);
    for (let i = 1; i < n; i++) if (!in_season(schedule, add_days(day, i))) return 'end';
    return null;
}

// "1 Dec – 15 Mar" pieces for the season line
export function season_ranges(schedule, locale = 'en-GB') {
    if (!schedule || schedule.all_year || !schedule.seasons || !schedule.seasons.length) return [];
    const f = (m, d) => new Date(2024, m - 1, d).toLocaleDateString(locale, { day: 'numeric', month: 'short' });
    return schedule.seasons.map(s => f(s.from_month, s.from_day) + ' – ' + f(s.to_month, s.to_day));
}

// months (1–12) when the tour runs at least one day — for the 12-month bar
export function active_months(schedule) {
    const out = [];
    for (let m = 1; m <= 12; m++) {
        const days = new Date(2024, m, 0).getDate();
        let on = false;
        for (let d = 1; d <= days && !on; d++) on = in_season(schedule, new Date(2024, m - 1, d));
        out.push(on);
    }
    return out;
}

export { add_days, today };
