// Recommended-services statistics beacon (BusinessStatsService, Redis).
// Fire-and-forget; impressions are sent once per business per page view.
const sent = new Set();

export function track_business(bisnes_id, metric, article_id = null) {
    // admins previewing in the user panel don't count
    if (!bisnes_id || window.location.hostname === process.env.MIX_USER_PAGE_URL) return;
    if (metric === 'impression') {
        const key = bisnes_id + ':' + (article_id || '') + ':' + window.location.pathname;
        if (sent.has(key)) return;
        sent.add(key);
    }
    axios.post('/get_bisnes/track/' + bisnes_id, { metric, article_id }).catch(() => {});
}
