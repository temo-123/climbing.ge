<template>
    <!-- Logbook stats from the climber's route ascents (no extra request):
         grade pyramid of clean sends, ticks per month for the last year, and
         milestone badges. Used on /my_route_ascents. -->
    <div v-if="ascents.length" class="cg-stats">
        <section class="cg-stats__card">
            <h3><i class="fa fa-signal" aria-hidden="true"></i> {{ $t('user.climbing_stats.pyramid') }}</h3>
            <p v-if="!pyramid.length" class="text-muted small mb-0">{{ $t('user.climbing_stats.pyramid_empty') }}</p>
            <ul v-else class="cg-pyramid">
                <li v-for="row in pyramid" :key="row.grade">
                    <span class="cg-pyramid__grade">{{ row.grade }}</span>
                    <span class="cg-pyramid__bar">
                        <span :style="{ width: (row.count * 100 / pyramid_max) + '%', background: row.color }"></span>
                    </span>
                    <span class="cg-pyramid__n">{{ row.count }}</span>
                </li>
            </ul>
        </section>

        <section class="cg-stats__card">
            <h3><i class="fa fa-calendar" aria-hidden="true"></i> {{ $t('user.climbing_stats.per_month') }}</h3>
            <div class="cg-months" role="img" :aria-label="$t('user.climbing_stats.per_month')">
                <div v-for="m in months" :key="m.key" class="cg-months__col" :title="m.label + ': ' + m.count">
                    <span class="cg-months__bar" :style="{ height: (m.count ? Math.max(8, m.count * 100 / months_max) : 0) + '%' }"></span>
                    <small>{{ m.short }}</small>
                </div>
            </div>
            <p class="small text-muted mb-0 mt-2">{{ $t('user.climbing_stats.this_year', { n: year_count }) }}</p>
        </section>

        <section class="cg-stats__card cg-stats__badges">
            <h3><i class="fa fa-trophy" aria-hidden="true"></i> {{ $t('user.climbing_stats.badges') }} <small>{{ earned_count }} / {{ badges.length }}</small></h3>
            <ul>
                <li v-for="b in badges" :key="b.key" :class="{ 'is-earned': b.earned }" :title="$t('user.climbing_stats.badge.' + b.key + '_desc')">
                    <span class="cg-badge-icon"><i class="fa" :class="b.icon" aria-hidden="true"></i></span>
                    <span>
                        <strong>{{ $t('user.climbing_stats.badge.' + b.key) }}</strong>
                        <small>{{ $t('user.climbing_stats.badge.' + b.key + '_desc') }}</small>
                    </span>
                </li>
            </ul>
        </section>
    </div>
</template>

<script>
    // French sport grades low → high (same list as MyRouteAscentsListPage)
    const SPORT_GRADES = [
        '4', '5a', '5a+', '5b', '5b+', '5c', '5c+',
        '6a', '6a+', '6b', '6b+', '6c', '6c+',
        '7a', '7a+', '7b', '7b+', '7c', '7c+',
        '8a', '8a+', '8b', '8b+', '8c', '8c+',
        '9a', '9a+', '9b', '9b+', '9c',
    ];
    const SENDS = ['onsight', 'flash', 'redpoint'];
    const color_for = i => (i < 7 ? '#2fa36b' : i < 13 ? '#1f93b0' : i < 19 ? '#e08a1e' : '#c43c3c');

    export default {
        name: 'ClimbingStats',
        props: {
            ascents: { type: Array, default: () => [] },
        },
        computed: {
            sends() {
                return this.ascents.filter(a => SENDS.includes(a.ascent_style) && a.route && SPORT_GRADES.includes(String(a.route.grade).toLowerCase()));
            },
            // one row per grade from hardest down, distinct routes only
            pyramid() {
                const by = {};
                const seen = new Set();
                this.sends.forEach(a => {
                    if (seen.has(a.route_id)) return;
                    seen.add(a.route_id);
                    const g = String(a.route.grade).toLowerCase();
                    by[g] = (by[g] || 0) + 1;
                });
                return SPORT_GRADES.map((g, i) => ({ grade: g, count: by[g] || 0, color: color_for(i) }))
                    .filter(r => r.count > 0)
                    .reverse();
            },
            pyramid_max() { return Math.max(1, ...this.pyramid.map(r => r.count)); },
            months() {
                const now = new Date();
                const locale = this.$i18n && this.$i18n.locale === 'ka' ? 'ka-GE' : 'en-GB';
                const out = [];
                for (let i = 11; i >= 0; i--) {
                    const d = new Date(now.getFullYear(), now.getMonth() - i, 1);
                    const key = d.getFullYear() + '-' + String(d.getMonth() + 1).padStart(2, '0');
                    out.push({
                        key,
                        count: this.ascents.filter(a => String(a.ascent_date).slice(0, 7) === key).length,
                        short: new Intl.DateTimeFormat(locale, { month: 'narrow' }).format(d),
                        label: new Intl.DateTimeFormat(locale, { month: 'long', year: 'numeric' }).format(d),
                    });
                }
                return out;
            },
            months_max() { return Math.max(1, ...this.months.map(m => m.count)); },
            year_count() { return this.months.reduce((s, m) => s + m.count, 0); },
            hardest_index() {
                return this.sends.reduce((max, a) => Math.max(max, SPORT_GRADES.indexOf(String(a.route.grade).toLowerCase())), -1);
            },
            badges() {
                const count = style => this.ascents.filter(a => a.ascent_style === style).length;
                const routes = new Set(this.ascents.map(a => a.route_id)).size;
                const sectors = new Set(this.ascents.map(a => a.sector_name).filter(Boolean)).size;
                const active_months = this.months.filter(m => m.count > 0).length;
                const grade = g => this.hardest_index >= SPORT_GRADES.indexOf(g);
                return [
                    { key: 'first_tick', icon: 'fa-check', earned: this.ascents.length >= 1 },
                    { key: 'ten_routes', icon: 'fa-list-ol', earned: routes >= 10 },
                    { key: 'fifty_routes', icon: 'fa-fire', earned: routes >= 50 },
                    { key: 'grade_6a', icon: 'fa-level-up', earned: grade('6a') },
                    { key: 'grade_7a', icon: 'fa-bolt', earned: grade('7a') },
                    { key: 'grade_8a', icon: 'fa-diamond', earned: grade('8a') },
                    { key: 'onsight_5', icon: 'fa-eye', earned: count('onsight') >= 5 },
                    { key: 'explorer', icon: 'fa-compass', earned: sectors >= 5 },
                    { key: 'all_year', icon: 'fa-calendar-check-o', earned: active_months >= 6 },
                ];
            },
            earned_count() { return this.badges.filter(b => b.earned).length; },
        },
    };
</script>

<style scoped>
    .cg-stats { display: grid; gap: 1rem; margin: 1rem 0; }
    @media (min-width: 992px) { .cg-stats { grid-template-columns: 1fr 1fr; } .cg-stats__badges { grid-column: 1 / -1; } }
    .cg-stats__card { padding: 1rem 1.1rem; background: #fff; border: 1px solid var(--cg-line); border-radius: var(--cg-r-lg); box-shadow: var(--cg-shadow-sm); }
    .cg-stats__card h3 { margin: 0 0 .75rem; font-size: 1.05rem; font-weight: 800; color: var(--cg-ink); }
    .cg-stats__card h3 .fa { color: var(--cg-accent); }
    .cg-stats__card h3 small { margin-left: .4rem; color: var(--cg-muted); font-size: .8rem; }

    .cg-pyramid { list-style: none; margin: 0; padding: 0; }
    .cg-pyramid li { display: grid; grid-template-columns: 2.6rem 1fr 2rem; align-items: center; gap: .5rem; margin-bottom: .3rem; }
    .cg-pyramid__grade { font-family: var(--cg-font-display); font-weight: 700; font-size: 1.05rem; text-align: right; }
    .cg-pyramid__bar { display: flex; justify-content: center; height: 1.1rem; }
    .cg-pyramid__bar span { display: block; height: 100%; min-width: 6px; border-radius: .3rem; }
    .cg-pyramid__n { font-weight: 800; color: var(--cg-stone); font-size: .85rem; }

    .cg-months { display: grid; grid-template-columns: repeat(12, 1fr); gap: .3rem; height: 8rem; align-items: end; }
    .cg-months__col { display: flex; flex-direction: column; align-items: center; justify-content: flex-end; height: 100%; }
    .cg-months__bar { display: block; width: 100%; max-width: 1.4rem; border-radius: .3rem .3rem 0 0; background: var(--cg-accent); }
    .cg-months__col small { margin-top: .25rem; color: var(--cg-muted); font-weight: 700; font-size: .7rem; }

    .cg-stats__badges ul { display: grid; grid-template-columns: repeat(auto-fill, minmax(14rem, 1fr)); gap: .6rem; list-style: none; margin: 0; padding: 0; }
    .cg-stats__badges li { display: flex; align-items: center; gap: .65rem; padding: .55rem .65rem; border-radius: var(--cg-r); background: var(--cg-chalk); opacity: .55; filter: grayscale(1); }
    .cg-stats__badges li.is-earned { opacity: 1; filter: none; background: var(--cg-accent-soft); }
    .cg-stats__badges li > span:last-child { min-width: 0; overflow-wrap: anywhere; }
    .cg-stats__badges li strong { display: block; font-size: .9rem; }
    .cg-stats__badges li small { display: block; color: var(--cg-stone); font-size: .75rem; line-height: 1.25; }
    .cg-badge-icon { flex: 0 0 2.4rem; height: 2.4rem; display: flex; align-items: center; justify-content: center; border-radius: 50%; background: #fff; color: var(--cg-accent); font-size: 1.1rem; box-shadow: inset 0 0 0 2px currentColor; }
    .is-earned .cg-badge-icon { background: var(--cg-hold); color: var(--cg-ink); box-shadow: none; }
</style>
