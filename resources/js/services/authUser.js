// One /auth_user request shared by everything that needs the current user at
// the same time (route guard, navbar, nav badges, home page widgets…): calls
// made while a request is in flight get that request, and its answer is
// reused for a few seconds. The cache belongs to the token, so logging in or
// out always fetches again; { force: true } skips the cache.
//
// Resolves / rejects like axios.get('auth_user') — callers read response.data
// (each gets its own shallow copy, so one component editing it can't change
// another's).
const REUSE_MS = 3000;

let pending = null;
let cached = null;   // { token, at, response }

const token_now = () => localStorage.getItem('auth_token') || '';
const copy = r => ({ ...r, data: r.data && typeof r.data === 'object' ? { ...r.data } : r.data });

export function get_auth_user({ force = false } = {}) {
    const token = token_now();
    if (!force && cached && cached.token === token && Date.now() - cached.at < REUSE_MS) {
        return Promise.resolve(copy(cached.response));
    }
    if (!force && pending && pending.token === token) return pending.promise.then(copy);

    const promise = axios.get('auth_user')
        .then(response => { cached = { token, at: Date.now(), response }; return response; })
        .finally(() => { if (pending && pending.promise === promise) pending = null; });
    pending = { token, promise };
    return promise.then(copy);
}

// after the user's own data changed (profile, photo…)
export function forget_auth_user() {
    cached = null;
}
