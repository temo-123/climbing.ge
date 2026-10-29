// Text search for the guide list pages (outdoor, ice, indoor, mountaineering,
// tech tips, projects): every word typed must appear somewhere in the item's
// titles / names / short descriptions / url_title, in either language and in
// any of the shapes the list APIs return (an article, { area: article }, a
// region with spots…). Case- and accent-insensitive.
const FIELDS = ['title', 'short_description', 'name', 'us_name', 'ka_name', 'url_title', 'us_title', 'ka_title'];

const fold = s => String(s).toLowerCase().normalize('NFD').replace(/[̀-ͯ]/g, '').replace(/[_\-()]+/g, ' ');

function collect(obj, out, depth) {
    if (!obj || typeof obj !== 'object' || depth > 3) return;
    for (const key of Object.keys(obj)) {
        const v = obj[key];
        if (typeof v === 'string' && FIELDS.includes(key)) out.push(v);
        else if (v && typeof v === 'object' && !Array.isArray(v)) collect(v, out, depth + 1);
    }
}

const cache = new WeakMap();
function text_of(item) {
    if (!item || typeof item !== 'object') return fold(item || '');
    if (!cache.has(item)) { const out = []; collect(item, out, 0); cache.set(item, fold(out.join(' '))); }
    return cache.get(item);
}

export function search_items(items, query) {
    const words = fold(query || '').split(/\s+/).filter(Boolean);
    if (!words.length) return items || [];
    return (items || []).filter(item => { const t = text_of(item); return words.every(w => t.includes(w)); });
}

// true when a navigation only changed the search term (?q): list pages skip
// their reload / scroll-to-top for it
export function only_search_changed(to, from) {
    if (!to || !from || to.path !== from.path) return false;
    const rest = q => JSON.stringify(Object.keys(q).filter(k => k !== 'q').sort().map(k => [k, q[k]]));
    return (to.query.q || '') !== (from.query.q || '') && rest(to.query) === rest(from.query);
}
