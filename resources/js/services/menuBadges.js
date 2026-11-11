// Alert badges for the user-panel menu (AdminMenuTreeComponent, LeftMenuComponent,
// the top bar's menu toggle). One shared store, refreshed at most every 2 minutes
// (and when the tab comes back / after login), so pages that each mount the
// sidebar don't refetch. docs/USER_PAGE.md#menu-alert-badges
//
// Sources — each only with the permissions for it:
//   account   your own account: e-mail not verified, name / location / photo missing
//             (also the top-bar bell count, NavBadgesComponent)
//   urgent    "needs attention" work (complaints, orders to ship, hazards, newly
//             confirmed tour reservations) — the same numbers as the dashboard tiles
//   fresh     new comments / reviews / feedback / ascents since you last opened the
//             page (set_admin_alerts/summary); opening the page marks them seen
//   mail      unread messages in the inbox
//   database  relation issues (set_admin_alerts/summary "danger" alert)
//   rental    gear rentals overdue (urgent) / due back today (new) — set_gear_rental/summary
import { reactive } from 'vue'
import { get_auth_user } from './authUser.js'
import { can_open_route } from './ability/canOpen.js'
import { attention_defs } from './adminAttention.js'

const REFRESH_MS = 2 * 60 * 1000
// no error toasts for these background checks (bootstrap.js) — a failing source just has no badge
const SILENT = { _silent: true }

export const menu_badges = reactive({
    account: [],     // keys of account alerts
    routes: {},      // menu path → { urgent, fresh, keys: [admin alert keys to mark seen] }
    loading: false,
})

let last_load = 0
let pending = null

// Account checks shared with the top-bar bell
export function account_alerts(user) {
    if (!user || !user.id) return []
    const out = []
    if (!user.email_verified_at) out.push('email')
    if (!user.name || !user.surname) out.push('name')
    if (!user.country || !user.city) out.push('location')
    if (!user.image) out.push('image')
    return out
}

// menu entries use plain paths ("/all_orders"); drop the /ka prefix of resolved routes
export function menu_path(path) {
    const p = String(path || '/').replace(/^\/(ka|us)(?=\/|$)/, '')
    return p === '' ? '/' : p
}

function path_of(router, to) {
    try { return menu_path(router.resolve(to).path) } catch (e) { return null }
}

function add(routes, path, kind, n, key) {
    if (!path || !(n > 0)) return
    const r = routes[path] || (routes[path] = { urgent: 0, fresh: 0, keys: [] })
    r[kind] += n
    if (key && !r.keys.includes(key)) r.keys.push(key)
}

const can = (ability, action, subject) => !!(ability && ability.can(action, subject))

export function load_menu_badges(router, ability, { force = false } = {}) {
    if (!localStorage.getItem('auth_token')) {
        menu_badges.account = []
        menu_badges.routes = {}
        return Promise.resolve()
    }
    if (pending) return pending
    if (!force && Date.now() - last_load < REFRESH_MS) return Promise.resolve()

    menu_badges.loading = true
    const routes = {}
    const jobs = []

    jobs.push(get_auth_user({ force })
        .then(r => { menu_badges.account = account_alerts(r.data) })
        .catch(() => { menu_badges.account = [] }))

    // "needs attention" urgent tiles — only staff with one of the dashboard blocks
    if (['comment', 'order', 'tour_reservation', 'article', 'user'].some(s => can(ability, 'show', s))) {
        jobs.push(axios.get('/get_dashboard/get_admin_overview', SILENT)
            .then(r => {
                const b = (r.data && r.data.blocks) || {}
                attention_defs(r.data).forEach(([block, key, , candidates, urgent]) => {
                    if (!urgent || !b[block]) return
                    const to = candidates.find(c => can_open_route(router, ability, c))
                    if (to) add(routes, path_of(router, to), 'urgent', Number(b[block][key]) || 0)
                })
            })
            .catch(() => {}))
    }

    // new content since last visit + database issues
    if (['comment', 'product_feedback', 'routes_rewiew', 'summit', 'database'].some(s => can(ability, 'show', s))) {
        jobs.push(axios.get('set_admin_alerts/summary', SILENT)
            .then(r => {
                (Array.isArray(r.data) ? r.data : []).forEach(a => {
                    const to = { name: a.route }
                    if (!can_open_route(router, ability, to)) return
                    if (a.severity === 'danger') add(routes, path_of(router, to), 'urgent', Number(a.count) || 0)
                    else add(routes, path_of(router, to), 'fresh', Number(a.count) || 0, a.dismissible ? a.key : null)
                })
            })
            .catch(() => {}))
    }

    // gear rental: overdue rentals (urgent), due back today (new)
    if (can(ability, 'show', 'gear_rental')) {
        jobs.push(axios.get('/set_gear_rental/summary', SILENT)
            .then(r => {
                add(routes, '/gear_rental', 'urgent', Number(r.data && r.data.overdue) || 0)
                add(routes, '/gear_rental', 'fresh', Number(r.data && r.data.due_today) || 0)
            })
            .catch(() => {}))
    }

    // unread mail (IMAP — the slowest source, so it's cached with the rest)
    if (can(ability, 'show', 'mail')) {
        jobs.push(axios.get('mail/unread_count', SILENT)
            .then(r => add(routes, '/mail', 'fresh', Number(r.data && r.data.unseen) || 0))
            .catch(() => {}))
    }

    pending = Promise.all(jobs).then(() => {
        menu_badges.routes = routes
        last_load = Date.now()
    }).finally(() => {
        pending = null
        menu_badges.loading = false
    })
    return pending
}

export function menu_badge(path) {
    return menu_badges.routes[menu_path(path)] || null
}

// totals for a group / the whole menu: urgent wins the colour
export function sum_badges(paths) {
    return paths.reduce((acc, p) => {
        const b = menu_badge(p)
        if (b) { acc.urgent += b.urgent; acc.fresh += b.fresh }
        return acc
    }, { urgent: 0, fresh: 0 })
}

export function any_menu_alert() {
    return menu_badges.account.length > 0 || Object.values(menu_badges.routes).some(b => b.urgent || b.fresh)
}

// Opening a page counts as "seen" for its new-content alerts
export function seen_menu_path(path) {
    const b = menu_badge(path)
    if (!b || !b.keys.length) return
    const keys = b.keys.splice(0)
    b.fresh = 0
    keys.forEach(key => axios.post('set_admin_alerts/mark_viewed', { key }, SILENT).catch(() => {}))
}

export function reset_menu_badges() {
    menu_badges.account = []
    menu_badges.routes = {}
    last_load = 0
}
