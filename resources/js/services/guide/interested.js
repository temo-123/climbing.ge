// "Interested" events (the events favourite list, table interested_events):
// one reactive set shared by every heart on the page, loaded once with
// get_faworite/get_interested_event_ids. Counts shown on cards are adjusted
// locally after a toggle (`delta`), so the page doesn't refetch.
import { reactive } from 'vue';

export const interested = reactive({ ids: new Set(), delta: {}, loaded: false });

let loading = null;

export function load_interested(force = false) {
    if (!localStorage.getItem('auth_token')) return Promise.resolve(interested);
    if (loading && !force) return loading;
    loading = axios.get('/get_faworite/get_interested_event_ids')
        .then(r => { interested.ids = new Set((r.data || []).map(Number)); })
        .catch(() => {})
        .finally(() => { interested.loaded = true; })
        .then(() => interested);
    return loading;
}

export function is_interested(id) {
    return interested.ids.has(Number(id));
}

export function interested_count(event) {
    return Math.max(0, (Number(event.interested_count) || 0) + (interested.delta[event.id] || 0));
}

// resolves true/false (new state)
export function toggle_interested(id) {
    id = Number(id);
    const bump = d => { interested.delta = Object.assign({}, interested.delta, { [id]: (interested.delta[id] || 0) + d }); };
    if (is_interested(id)) {
        return axios.delete('/set_faworite/del_interested_event/' + id).then(() => {
            const next = new Set(interested.ids); next.delete(id); interested.ids = next; bump(-1);
            return false;
        });
    }
    return axios.post('/set_faworite_by_user/add_to_interested_events', { event_id: id }).then(() => {
        interested.ids = new Set(interested.ids).add(id); bump(1);
        return true;
    });
}
