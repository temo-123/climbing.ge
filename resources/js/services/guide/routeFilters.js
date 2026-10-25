// Shared by the outdoor area route toolbar and every SectorComponent: the
// toolbar edits one reactive `route_tools` object (provided by
// SectorAndRoutesTabComponent), the sector tables read it to hide rows.

// French grade → sortable number: "6a" = 60, "6a+" = 61, "6b" = 62 … "4" = 40.
// Projects and unknown values return null.
export function grade_rank(grade) {
    if (!grade) return null;
    const m = String(grade).trim().toLowerCase().match(/^([3-9])([abc])?(\+)?$/);
    if (!m) return null;
    const letter = m[2] ? { a: 0, b: 2, c: 4 }[m[2]] : 0;
    return Number(m[1]) * 10 + letter + (m[3] ? 1 : 0);
}

// Grade bands shown as filter chips. `max` is inclusive.
export const GRADE_BANDS = [
    { key: 'easy',   label: '≤ 5c+',     min: 0,  max: 55 },
    { key: 'mid',    label: '6a – 6b+',  min: 60, max: 63 },
    { key: 'hard',   label: '6c – 7a+',  min: 64, max: 71 },
    { key: 'expert', label: '7b +',      min: 72, max: 999 },
];

export function band_of(route) {
    const rank = grade_rank(route && route.grade);
    if (rank === null) return 'project';
    const band = GRADE_BANDS.find(b => rank >= b.min && rank <= b.max);
    return band ? band.key : 'project';
}

// Community rating first (reviews), the admin "stars" column as fallback.
export function route_stars(route) {
    if (!route) return 0;
    if (route.reviews_stars) return Number(route.reviews_stars);
    return Number(route.stars) || 0;
}

export const CLASSIC_STARS = 4;

export function new_route_tools() {
    return {
        band: 'all',          // 'all' or a GRADE_BANDS key / 'project'
        classics: false,      // only routes rated ≥ CLASSIC_STARS
        hide_ticked: false,   // hide routes the logged-in climber has logged
        query: '',            // name search
        ticked: {},           // route_id → best ascent style, filled when logged in
        logged_in: false,
    };
}

export function is_filtering(tools) {
    return !!tools && (tools.band !== 'all' || tools.classics || tools.hide_ticked || !!tools.query.trim());
}

export function route_matches(route, tools) {
    if (!tools) return true;
    if (tools.band !== 'all' && band_of(route) !== tools.band) return false;
    if (tools.classics && route_stars(route) < CLASSIC_STARS) return false;
    if (tools.hide_ticked && tools.ticked[route.id]) return false;
    const q = tools.query.trim().toLowerCase();
    if (q && !String(route.name || '').toLowerCase().includes(q)) return false;
    return true;
}

// climbing_area from get_sector_and_routes → flat list of sector payloads
// (an entry is either one sector, or a local image group with `sectors`).
export function flat_sectors(climbing_area) {
    const out = [];
    (climbing_area || []).forEach(area => {
        if (!area) return;
        if (area.sectors) area.sectors.forEach(s => s && out.push(s));
        else if (area.sector) out.push(area);
    });
    return out;
}

export function flat_routes(climbing_area) {
    const out = [];
    flat_sectors(climbing_area).forEach(s => {
        (s.sport_routes || []).forEach(r => out.push(r));
        (s.boulder_route || []).forEach(r => out.push(r));
    });
    return out;
}
