// Run non-urgent work (third-party scripts, sockets, preloads) once the page
// has loaded and the browser is idle, so it never competes with the first
// paint or the first route's data. Falls back to a short timeout where
// requestIdleCallback is missing (Safari).
export function when_idle(fn, timeout = 3000) {
    const schedule = () => {
        if ('requestIdleCallback' in window) window.requestIdleCallback(() => fn(), { timeout });
        else setTimeout(fn, 200);
    };
    if (document.readyState === 'complete') schedule();
    else window.addEventListener('load', schedule, { once: true });
}
