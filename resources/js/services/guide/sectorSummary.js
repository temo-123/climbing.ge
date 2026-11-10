// Small, shared facts about one sector payload (get_sector_and_routes) for the
// tiles / table / tabs sector views.
import { grade_rank, band_of, route_matches, is_filtering, GRADE_BANDS } from './routeFilters.js';

export const BAND_ORDER = GRADE_BANDS.map(b => b.key).concat('project');

// Admins sometimes save placeholder names ("-", "Sector -"): drop trailing
// dashes / colons; a name with no letter or digit counts as missing.
export function clean_name(name) {
    const text = String(name || '').trim().replace(/[\s\-–—:|.]+$/u, '').trim();
    return /[\p{L}\p{N}]/u.test(text) ? text : '';
}

// display name; unnamed sectors become "Sector N" (N = place on the page, 1-based)
export function sector_name(entry, n, t) {
    return clean_name(entry && entry.sector && entry.sector.name)
        || t('guide.article_right_nabar.unnamed_sector', { n: n || '' }).trim();
}

export function sector_routes(entry) {
    return (entry.sport_routes || []).concat(entry.boulder_route || []);
}

// routes, grade range, band mix (sport routes, French scale), filter matches
export function sector_summary(entry, tools) {
    const sport = entry.sport_routes || [];
    let low = null, high = null;
    const counts = {};
    sport.forEach(r => {
        const rank = grade_rank(r.grade);
        if (rank !== null) {
            if (!low || rank < low.rank) low = { rank, grade: r.grade };
            if (!high || rank > high.rank) high = { rank, grade: r.grade };
        }
        const band = band_of(r);
        counts[band] = (counts[band] || 0) + 1;
    });
    const all = sector_routes(entry);
    return {
        routes: all.length,
        mtps: (entry.mtps || []).length,
        range: low ? (low.rank === high.rank ? low.grade : low.grade + ' – ' + high.grade) : '',
        bands: BAND_ORDER.filter(k => counts[k]).map(k => ({ key: k, count: counts[k] })),
        matches: is_filtering(tools) ? all.filter(r => route_matches(r, tools)).length : null,
    };
}

// first photo of the sector, else the photo of its local-approach group
export function sector_thumb(entry, group) {
    const img = (entry.sector_imgs || [])[0];
    if (img && img.image) return '/public/images/sector_img/' + img.image + (img.updated_at ? '?v=' + encodeURIComponent(img.updated_at) : '');
    const local = group && group.local_images && group.local_images[0];
    if (local && local.image) return '/public/images/sector_local_img/' + local.image;
    return null;
}

// climbing_area → [{ entry, group, n }] in page order (group = its local-image group, if any)
export function sector_list(climbing_area) {
    const out = [];
    (climbing_area || []).forEach(area => {
        if (!area) return;
        if (area.sectors) area.sectors.forEach(s => s && s.sector && out.push({ entry: s, group: area }));
        else if (area.sector) out.push({ entry: area, group: null });
    });
    out.forEach((x, i) => { x.n = i + 1; });
    return out;
}
