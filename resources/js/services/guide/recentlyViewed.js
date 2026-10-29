// "Recently viewed" guidebook pages, per browser (localStorage only — it's a
// convenience, nothing breaks when storage is blocked). Article pages call
// remember_viewed(); RecentlyViewedComponent reads the list.
const KEY = 'cg_recently_viewed';
const MAX = 8;

// category → [router path prefix, public image folder]
export const CATEGORY_PATHS = {
    outdoor:  ['/outdoor/', '/public/images/outdoor_img/'],
    ice:      ['/ice/', '/public/images/ice_img/'],
    indoor:   ['/indoor/', '/public/images/indoor_img/'],
    mount_route: ['/mountaineering/', '/public/images/mount_route_img/'],
};

export function read_viewed() {
    try {
        const list = JSON.parse(localStorage.getItem(KEY) || '[]');
        return Array.isArray(list) ? list : [];
    } catch (e) {
        return [];
    }
}

export function remember_viewed({ category, url_title, title, image }) {
    if (!CATEGORY_PATHS[category] || !url_title || !title) return;
    const list = read_viewed().filter(i => !(i.category === category && i.url_title === url_title));
    list.unshift({ category, url_title, title, image: image || null, at: Date.now() });
    try { localStorage.setItem(KEY, JSON.stringify(list.slice(0, MAX))); } catch (e) {}
}

export function forget_viewed() {
    try { localStorage.removeItem(KEY); } catch (e) {}
}
