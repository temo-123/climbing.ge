// Favourite products: one reactive set shared by every heart in the shop
// (cards, quick view, product page), loaded once with get_favorite_product_ids.
import { reactive } from 'vue';

export const favorite_products = reactive({ ids: new Set(), loaded: false });

let loading = null;

export function load_favorite_products(force = false) {
    if (!localStorage.getItem('auth_token')) return Promise.resolve(favorite_products);
    if (loading && !force) return loading;
    loading = axios.get('/get_favorite_product_ids')
        .then(r => { favorite_products.ids = new Set((r.data || []).map(Number)); })
        .catch(() => {})
        .finally(() => { favorite_products.loaded = true; })
        .then(() => favorite_products);
    return loading;
}

export function is_favorite_product(id) {
    return favorite_products.ids.has(Number(id));
}

export function toggle_favorite_product(id) {
    id = Number(id);
    if (is_favorite_product(id)) {
        return axios.post('/del_from_favorite/' + id).then(() => {
            const next = new Set(favorite_products.ids); next.delete(id); favorite_products.ids = next;
            return false;
        });
    }
    return axios.post('/add_to_favorite/' + id).then(() => {
        favorite_products.ids = new Set(favorite_products.ids).add(id);
        return true;
    });
}
