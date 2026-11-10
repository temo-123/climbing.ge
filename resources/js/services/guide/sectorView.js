// How the sectors of an outdoor / ice area are shown (SectorAndRoutesTabComponent):
//   'full'  — today's layout: every sector with icons, text, photos, tables (default)
//   'tiles' — a grid of sector tiles; a tile opens that sector in a big panel
//   'table' — every route of the area in one sortable table (with a Sector column)
//   'tabs'  — one sector at a time, picked from a row of tabs, with ‹ › arrows
// Stored per device in localStorage (cg_sector_view).
import { reactive } from 'vue';

const KEY = 'cg_sector_view';
export const SECTOR_VIEWS = ['full', 'tiles', 'table', 'tabs'];

function read() {
    try {
        const v = localStorage.getItem(KEY);
        if (SECTOR_VIEWS.includes(v)) return v;
    } catch (e) {}
    return 'full';
}

export const sector_view = reactive({ mode: read() });

export function set_sector_view(mode) {
    if (!SECTOR_VIEWS.includes(mode)) return;
    sector_view.mode = mode;
    try { localStorage.setItem(KEY, mode); } catch (e) {}
}

// "Show me this sector" (right menu, phone "On this page"): the tabs view
// selects it, the tiles view opens its panel, the table view filters to it.
export function open_sector(id) {
    window.dispatchEvent(new CustomEvent('cg-sector-open', { detail: { id: String(id) } }));
}
