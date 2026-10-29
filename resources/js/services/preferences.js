// Account defaults (users.lang / grade / mount_grade / animations, edited in
// user options → Preferences and in the welcome questions) applied to this
// device's settings. Runs after every auth_user fetch, but only changes
// anything when the saved defaults differ from what this device last
// applied — so later changes made with the navbar switches stick.
import { set_motion } from './motion.js';

const MARK = 'cg_prefs_applied';

function signature(u) {
    return [u.id, u.lang || '', u.grade || '', u.mount_grade || '', u.animations === false ? 0 : 1].join('|');
}

export function apply_user_preferences(user, { force = false } = {}) {
    if (!user || !user.id) return;
    let mark = null;
    try { mark = localStorage.getItem(MARK); } catch (e) {}
    const sig = signature(user);
    if (!force && mark === sig) return;
    try { localStorage.setItem(MARK, sig); } catch (e) {}

    let reload = false;

    if (user.grade) {
        const grade = user.grade === 'uiaa' ? 'UIAA' : 'yds';
        if ((localStorage.getItem('grade') || '').toLowerCase() !== user.grade) {
            localStorage.setItem('grade', grade);
            reload = true;
        }
    }
    if (user.mount_grade && localStorage.getItem('mount_grade') !== user.mount_grade) {
        localStorage.setItem('mount_grade', user.mount_grade);
        reload = true;
    }
    if (typeof user.animations === 'boolean') set_motion(user.animations);

    // language lives in the URL on every subdomain (/ka/...), so move there
    if (user.lang === 'us' || user.lang === 'ka') {
        localStorage.setItem('lang', user.lang);
        const path = window.location.pathname;
        const on_ka = path === '/ka' || path.startsWith('/ka/');
        if (user.lang === 'ka' && !on_ka) {
            window.location.replace('/ka' + (path === '/' ? '' : path) + window.location.search);
            return;
        }
        if (user.lang === 'us' && on_ka) {
            window.location.replace((path.slice(3) || '/') + window.location.search);
            return;
        }
    }

    if (reload) window.location.reload();
}
