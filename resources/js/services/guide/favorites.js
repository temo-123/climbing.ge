// Favourite guide articles (every type except news / special articles), one
// reactive set shared by every heart on the page: loaded once per page load
// with get_faworite/get_favorite_article_ids, then kept in sync by toggle().
import { reactive } from 'vue';

export const FAVORITE_CATEGORIES = ['outdoor', 'ice', 'indoor', 'mount_route', 'other', 'tech_tip', 'partners', 'spot_projects'];

export const favorites = reactive({
    ids: new Set(),
    loaded: false,
    logged_in: false,
});

let loading = null;

export function load_favorites(force = false) {
    if (!localStorage.getItem('auth_token')) return Promise.resolve(favorites);
    if (loading && !force) return loading;
    loading = axios.get('/get_faworite/get_favorite_article_ids')
        .then(r => {
            favorites.ids = new Set((r.data || []).map(Number));
            favorites.logged_in = true;
        })
        .catch(() => {})
        .finally(() => { favorites.loaded = true; })
        .then(() => favorites);
    return loading;
}

export function is_favorite(article_id) {
    return favorites.ids.has(Number(article_id));
}

// resolves true/false (new state); rejects with the axios error
export function toggle_favorite(article_id) {
    const id = Number(article_id);
    if (is_favorite(id)) {
        return axios.delete('/set_faworite/del_favorite_outdoor_area/' + id).then(() => {
            const next = new Set(favorites.ids); next.delete(id); favorites.ids = next;
            return false;
        });
    }
    return axios.post('/set_faworite_by_user/add_to_favorite_outdoor_area/' + id).then(() => {
        favorites.ids = new Set(favorites.ids).add(id);
        return true;
    });
}
