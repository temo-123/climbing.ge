// Can the current user open this user-panel route? Same rule as the route
// guard in app.js: a route's meta.permissions ([[action, subject], …]) needs
// any one of them; routes without it are open to every logged-in user. Lets
// widgets show a link only when following it won't bounce back home.
export function can_open_route(router, ability, to) {
    let route;
    try { route = router.resolve(to); } catch (e) { return false; }
    if (!route || !route.matched.length) return false;
    const needed = route.meta && route.meta.permissions;
    return !needed || !needed.length || needed.some(([action, subject]) => ability.can(action, subject));
}
