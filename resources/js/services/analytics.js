// GA4 events for the core engagement actions (docs/FRONTEND/FRONTEND.md#analytics-events).
// vue-gtag defines window.gtag once the tag loads; with no GA id (local dev) or an ad-blocker
// it doesn't exist and track() does nothing — a tracking problem must never break the action.
export function track(name, params = {}) {
    try {
        if (typeof window !== 'undefined' && typeof window.gtag === 'function') {
            window.gtag('event', name, params);
        }
    } catch (e) { /* ignore */ }
}
