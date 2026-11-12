// "Needs attention" tiles (get_dashboard/get_admin_overview), shared by the
// dashboard cards (AdminAttentionComponent) and the left-menu badges
// (services/menuBadges.js), so both always show the same numbers.
//
// [block, key, icon, candidate routes (best first), urgent]
// The tile links to the first candidate this user may open (roles differ: an
// Order Manager has `order` but not `shop_task`, an Article Manager has
// `article` but not `guide_task`); without one it's just the number.
export function attention_defs(data) {
    const b = (data && data.blocks) || {};
    const drafts = { name: 'articlelist', params: { article_category: (b.guide_meta && b.guide_meta.draft_category) || 'outdoor' } };
    const guide = { name: 'guideDashboardPage' };
    const shop = { name: 'shopDashboardPage' };
    const orders = { name: 'allOrders' };
    return [
        ['moderation', 'comment_complaints', 'fa-flag', [{ name: 'commentsAndReviewsList' }], true],
        ['moderation', 'user_complaints', 'fa-user-times', [{ name: 'usersList', query: { view: 'list' } }], true],
        ['shop', 'open_orders', 'fa-truck', [orders, shop], true],
        ['guide', 'hazard_reports', 'fa-exclamation-triangle', [guide], true],
        ['moderation', 'new_comments', 'fa-comments-o', [{ name: 'commentsAndReviewsList' }], false],
        ['moderation', 'new_reviews', 'fa-star-half-o', [{ name: 'allRoutesReviews' }], false],
        ['guide', 'draft_articles', 'fa-pencil-square-o', [guide, drafts], false],
        ['guide', 'condition_reports', 'fa-thermometer-half', [guide], false],
        ['guide', 'partner_posts', 'fa-handshake-o', [guide], false],
        ['shop', 'orders_week', 'fa-shopping-cart', [shop, orders], false],
        ['shop', 'revenue_week', 'fa-money', [shop, orders], false],
        ['shop', 'awaiting_confirmation', 'fa-envelope-o', [orders, shop], false],
        ['shop', 'back_in_stock_waiting', 'fa-bell-o', [shop, { name: 'productsList' }], false],
        ['tours', 'tour_new_confirmed', 'fa-calendar-check-o', [{ name: 'allToursReservations' }], true],
        ['tours', 'tour_waiting', 'fa-hourglass-half', [{ name: 'allToursReservations' }], false],
        ['tours', 'tour_upcoming', 'fa-compass', [{ name: 'allToursReservations' }], false],
        ['tours', 'tour_new_week', 'fa-bolt', [{ name: 'allToursReservations' }], false],
        ['users', 'new_week', 'fa-user-plus', [{ name: 'usersList' }], false],
        ['users', 'active_week', 'fa-users', [{ name: 'usersList' }], false],
    ];
}
