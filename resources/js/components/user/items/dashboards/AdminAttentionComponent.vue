<template>
    <!-- User panel home, admins only: what needs attention now + 30-day
         activity (get_dashboard/get_admin_overview). Each block appears only
         with the permission for its numbers; tiles with work waiting are
         highlighted. A tile links to its page only when this user may open
         it (otherwise it's just the number). -->
    <section v-if="items.length" class="aa">
        <div class="aa__head">
            <h2 class="cg-section-title cg-section-title--left cg-dashboard__title mb-0">{{ title || $t('admin.overview.title') }}</h2>
            <button type="button" class="btn btn-sm btn-outline-secondary" :disabled="loading" @click="load" :title="$t('common.refresh')">
                <i class="fa fa-refresh" :class="{ 'fa-spin': loading }" aria-hidden="true"></i>
            </button>
        </div>

        <div class="aa__tiles">
            <component :is="t.link ? 'router-link' : 'div'" v-for="t in items" :key="t.key" :to="t.link ? t.to : undefined" class="aa-tile" :class="{ 'is-hot': t.hot, 'is-static': !t.link }">
                <span class="aa-tile__icon"><i class="fa" :class="t.icon" aria-hidden="true"></i></span>
                <span class="aa-tile__text">
                    <strong>{{ t.value }}</strong>
                    <span>{{ $t('admin.overview.items.' + t.key) }}</span>
                </span>
                <i v-if="t.link" class="fa fa-angle-right aa-tile__go" aria-hidden="true"></i>
            </component>
        </div>

        <div v-if="sparks.length" class="aa__sparks">
            <div v-for="s in sparks" :key="s.key" class="aa-spark">
                <div class="aa-spark__head">
                    <span>{{ $t('admin.overview.series.' + s.key) }}</span>
                    <strong>{{ s.total }}</strong>
                </div>
                <svg viewBox="0 0 120 32" preserveAspectRatio="none" role="img" :aria-label="$t('admin.overview.series.' + s.key) + ': ' + s.total">
                    <polyline :points="s.area" class="aa-spark__area" />
                    <polyline :points="s.line" class="aa-spark__line" />
                </svg>
                <small>{{ $t('admin.overview.last_30') }}</small>
            </div>
        </div>
    </section>
</template>

<script>
    import { can_open_route } from '../../../../services/ability/canOpen.js'

    export default {
        name: 'AdminAttention',
        props: {
            // limit to some blocks (e.g. ['shop'] on the shop dashboard); empty = all allowed
            blocks: { type: Array, default: () => [] },
            title: { type: String, default: '' },
        },
        data() {
            return { data: null, loading: false };
        },
        computed: {
            // [block, key, icon, route, "hot" when > 0]
            items() {
                if (!this.data) return [];
                const b = this.data.blocks || {};
                const defs = [
                    ['moderation', 'comment_complaints', 'fa-flag', { name: 'commentsAndReviewsList' }, true],
                    ['moderation', 'user_complaints', 'fa-user-times', { name: 'usersList' }, true],
                    ['shop', 'open_orders', 'fa-truck', { name: 'allOrders' }, true],
                    ['guide', 'hazard_reports', 'fa-exclamation-triangle', { name: 'guideDashboardPage' }, true],
                    ['moderation', 'new_comments', 'fa-comments-o', { name: 'commentsAndReviewsList' }, false],
                    ['moderation', 'new_reviews', 'fa-star-half-o', { name: 'allRoutesReviews' }, false],
                    ['guide', 'draft_articles', 'fa-pencil-square-o', { name: 'guideDashboardPage' }, false],
                    ['guide', 'condition_reports', 'fa-thermometer-half', { name: 'guideDashboardPage' }, false],
                    ['guide', 'partner_posts', 'fa-handshake-o', { name: 'guideDashboardPage' }, false],
                    ['shop', 'orders_week', 'fa-shopping-cart', { name: 'shopDashboardPage' }, false],
                    ['shop', 'revenue_week', 'fa-money', { name: 'shopDashboardPage' }, false],
                    ['shop', 'back_in_stock_waiting', 'fa-bell-o', { name: 'shopDashboardPage' }, false],
                    ['users', 'new_week', 'fa-user-plus', { name: 'usersList' }, false],
                    ['users', 'active_week', 'fa-users', { name: 'usersList' }, false],
                ];
                return defs
                    .filter(([block]) => !this.blocks.length || this.blocks.includes(block))
                    .filter(([block, key]) => b[block] && b[block][key] !== undefined)
                    .map(([block, key, icon, to, urgent]) => ({
                        key, icon, to,
                        link: can_open_route(this.$router, this.$ability, to),
                        value: key === 'revenue_week' ? '₾ ' + b[block][key] : b[block][key],
                        hot: urgent && b[block][key] > 0,
                    }))
                    // work waiting first
                    .sort((x, y) => (y.hot ? 1 : 0) - (x.hot ? 1 : 0));
            },
            sparks() {
                if (!this.data) return [];
                // series key → block it belongs to
                const owner = { users: 'users', ascents: 'guide', comments: 'moderation', orders: 'shop' };
                return Object.entries(this.data.series || {}).filter(([key]) => !this.blocks.length || this.blocks.includes(owner[key])).map(([key, values]) => {
                    const max = Math.max(1, ...values);
                    const pts = values.map((v, i) => [(i * 120 / (values.length - 1)).toFixed(1), (30 - v * 28 / max).toFixed(1)]);
                    const line = pts.map(p => p.join(',')).join(' ');
                    return { key, total: values.reduce((s, v) => s + v, 0), line, area: '0,32 ' + line + ' 120,32' };
                });
            },
        },
        mounted() { this.load(); },
        methods: {
            load() {
                this.loading = true;
                axios.get('/get_dashboard/get_admin_overview')
                    .then(r => { this.data = r.data; })
                    .catch(() => {})
                    .finally(() => { this.loading = false; });
            },
        },
    };
</script>

<style scoped>
    .aa { margin-bottom: 1.5rem; }
    .aa__head { display: flex; align-items: center; justify-content: space-between; gap: .75rem; margin-bottom: .75rem; }
    .aa__tiles { display: grid; grid-template-columns: repeat(auto-fill, minmax(13rem, 1fr)); gap: .6rem; }
    .aa-tile { display: flex; align-items: center; gap: .7rem; padding: .7rem .8rem; background: #fff; border: 1px solid var(--cg-line); border-radius: var(--cg-r); color: var(--cg-ink-2); text-decoration: none; transition: border-color .15s; }
    .aa-tile:hover { border-color: var(--cg-accent); color: var(--cg-ink-2); }
    .aa-tile.is-static:hover { border-color: var(--cg-line); }
    .aa-tile__icon { flex: 0 0 2.4rem; height: 2.4rem; display: flex; align-items: center; justify-content: center; border-radius: 50%; background: var(--cg-accent-soft); color: var(--cg-accent-dark); }
    .aa-tile__text { display: flex; flex-direction: column; min-width: 0; line-height: 1.2; }
    .aa-tile__text strong { font-family: var(--cg-font-display); font-size: 1.5rem; color: var(--cg-ink); }
    .aa-tile__text span { font-size: .82rem; font-weight: 700; }
    .aa-tile__go { margin-left: auto; color: var(--cg-muted); }
    .aa-tile.is-hot { border-color: #e0632a; background: #fff6f0; }
    .aa-tile.is-hot .aa-tile__icon { background: #e0632a; color: #fff; }
    .aa__sparks { display: grid; grid-template-columns: repeat(auto-fill, minmax(12rem, 1fr)); gap: .6rem; margin-top: .75rem; }
    .aa-spark { padding: .7rem .85rem; background: #fff; border: 1px solid var(--cg-line); border-radius: var(--cg-r); }
    .aa-spark__head { display: flex; justify-content: space-between; align-items: baseline; font-weight: 700; font-size: .85rem; }
    .aa-spark__head strong { font-family: var(--cg-font-display); font-size: 1.3rem; color: var(--cg-ink); }
    .aa-spark svg { display: block; width: 100%; height: 2.2rem; margin: .25rem 0; }
    .aa-spark__line { fill: none; stroke: var(--cg-accent); stroke-width: 1.6; stroke-linejoin: round; vector-effect: non-scaling-stroke; }
    .aa-spark__area { fill: rgba(var(--cg-accent-rgb), .12); stroke: none; }
    .aa-spark small { color: var(--cg-muted); font-weight: 600; }
</style>
