<template>
    <!-- Users list page → Dashboard tab (user › show).
         Quick actions, headline numbers, sign-ups per month, activity, profile completeness,
         roles, ages, languages & sign-in methods, newest sign-ups (click → user info modal).
         Data: GET set_user/get_user_stats (UserStatsService). docs/FUNCTIONS_DOCS/user/users_dashboard.md -->
    <section class="uso">
        <header class="uso-head">
            <div>
                <h2 class="uso-head__title"><i class="fa fa-users" aria-hidden="true"></i> {{ $t('admin.user_stats.title') }}</h2>
                <p class="uso-hint mb-0">{{ $t('admin.user_stats.lead') }}</p>
            </div>
            <div class="uso-actions">
                <button v-for="a in actions" :key="a.key" type="button" class="btn btn-sm" :class="a.primary ? 'btn-primary' : 'btn-light'" @click="$emit('open-view', a.key)">
                    <i class="fa" :class="a.icon" aria-hidden="true"></i> {{ a.label }}
                </button>
                <button type="button" class="btn btn-sm btn-light" @click="load" :disabled="loading" :title="$t('common.refresh')" :aria-label="$t('common.refresh')">
                    <i class="fa fa-refresh" :class="{ 'fa-spin': loading }" aria-hidden="true"></i>
                </button>
            </div>
        </header>

        <div v-if="!s && loading" class="row g-3">
            <div v-for="n in 4" :key="n" class="col-6 col-lg-3"><div class="uso-kpi uso-kpi--skeleton"></div></div>
        </div>
        <div v-else-if="!s" class="alert alert-warning">{{ $t('admin.dashboards.failed_load_dashboard_data') }}</div>

        <template v-else>
            <!-- headline numbers -->
            <div class="row g-3 mb-4">
                <div class="col-6 col-lg-3">
                    <div class="uso-kpi" style="--tone: var(--cg-accent)">
                        <span class="uso-kpi__label">{{ $t('admin.user_stats.total') }}</span>
                        <span class="uso-kpi__num">{{ fmt(s.totals.total) }}</span>
                        <span class="uso-kpi__sub">{{ $t('admin.user_stats.verified_pct', { n: pct(s.totals.verified) }) }}</span>
                    </div>
                </div>
                <div class="col-6 col-lg-3">
                    <div class="uso-kpi" style="--tone: #18a06f">
                        <span class="uso-kpi__label">{{ $t('admin.user_stats.new_week') }}</span>
                        <span class="uso-kpi__num">+{{ fmt(s.growth.new_week) }}</span>
                        <span class="uso-kpi__sub">{{ $t('admin.user_stats.today_month', { today: fmt(s.growth.new_today), month: fmt(s.growth.new_month) }) }}</span>
                    </div>
                </div>
                <div class="col-6 col-lg-3">
                    <div class="uso-kpi" style="--tone: #1f93b0">
                        <span class="uso-kpi__label">{{ $t('admin.user_stats.active_week') }}</span>
                        <span class="uso-kpi__num">{{ fmt(s.activity.week) }}</span>
                        <span class="uso-kpi__sub">{{ $t('admin.user_stats.today_month', { today: fmt(s.activity.today), month: fmt(s.activity.month) }) }}</span>
                    </div>
                </div>
                <div class="col-6 col-lg-3">
                    <div class="uso-kpi" :style="{ '--tone': s.totals.unverified ? 'var(--cg-rope)' : '#56646e' }">
                        <span class="uso-kpi__label">{{ $t('admin.user_stats.unverified') }}</span>
                        <span class="uso-kpi__num">{{ fmt(s.totals.unverified) }}</span>
                        <span class="uso-kpi__sub">{{ $t('admin.user_stats.banned_team', { banned: fmt(s.totals.banned), team: fmt(s.totals.team_members) }) }}</span>
                    </div>
                </div>
            </div>

            <div class="row g-4">
                <!-- sign-ups per month -->
                <div class="col-lg-7">
                    <div class="cg-panel uso-panel">
                        <h3 class="uso-panel__title"><i class="fa fa-line-chart" aria-hidden="true"></i> {{ $t('admin.user_stats.signups') }}</h3>
                        <p class="uso-hint">{{ $t('admin.user_stats.signups_hint', { n: fmt(year_total) }) }}</p>
                        <div class="uso-cols" role="img" :aria-label="$t('admin.user_stats.signups')">
                            <div v-for="m in s.signups_by_month" :key="m.month" class="uso-col" :title="month_label(m.month, true) + ': ' + m.count">
                                <span class="uso-col__n">{{ m.count || '' }}</span>
                                <span class="uso-col__bar" :style="{ height: col_height(m.count) }"></span>
                                <span class="uso-col__m">{{ month_label(m.month) }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- activity -->
                <div class="col-lg-5">
                    <div class="cg-panel uso-panel">
                        <h3 class="uso-panel__title"><i class="fa fa-heartbeat" aria-hidden="true"></i> {{ $t('admin.user_stats.activity') }}</h3>
                        <p class="uso-hint">{{ $t('admin.user_stats.activity_hint') }}</p>
                        <div class="uso-bars">
                            <div v-for="r in activity_rows" :key="r.key" class="uso-bar">
                                <span class="uso-bar__label">{{ r.label }}</span>
                                <span class="uso-bar__track"><span class="uso-bar__fill" :style="{ width: pct(r.value) + '%', background: r.color }"></span></span>
                                <span class="uso-bar__num">{{ fmt(r.value) }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- profile completeness -->
                <div class="col-lg-6">
                    <div class="cg-panel uso-panel">
                        <h3 class="uso-panel__title"><i class="fa fa-id-card-o" aria-hidden="true"></i> {{ $t('admin.user_stats.profiles') }}</h3>
                        <p class="uso-hint">{{ $t('admin.user_stats.profiles_hint') }}</p>
                        <div class="uso-bars">
                            <div v-for="r in profile_rows" :key="r.key" class="uso-bar">
                                <span class="uso-bar__label"><i class="fa" :class="r.icon" aria-hidden="true"></i> {{ r.label }}</span>
                                <span class="uso-bar__track"><span class="uso-bar__fill" :style="{ width: pct(r.value) + '%' }" :class="'is-' + level(pct(r.value))"></span></span>
                                <span class="uso-bar__num">{{ pct(r.value) }}%</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- roles -->
                <div class="col-lg-6">
                    <div class="cg-panel uso-panel">
                        <h3 class="uso-panel__title"><i class="fa fa-key" aria-hidden="true"></i> {{ $t('admin.user_stats.roles') }}</h3>
                        <p class="uso-hint">{{ $t('admin.user_stats.roles_hint') }}</p>
                        <ul class="uso-roles">
                            <li v-for="r in roles_shown" :key="r.slug">
                                <span class="uso-roles__name" :class="{ 'is-ban': r.slug === 'ban', 'is-admin': r.slug === 'admin' }">{{ r.name || r.slug }}</span>
                                <span class="uso-roles__track"><span :style="{ width: Math.max(4, pct(r.count)) + '%' }" :class="{ 'is-ban': r.slug === 'ban' }"></span></span>
                                <strong>{{ fmt(r.count) }}</strong>
                            </li>
                        </ul>
                        <button v-if="s.roles.length > 6" type="button" class="btn btn-link btn-sm p-0" @click="all_roles = !all_roles">
                            {{ all_roles ? $t('admin.user_stats.less') : $t('admin.user_stats.all_roles', { n: s.roles.length }) }}
                        </button>
                    </div>
                </div>

                <!-- ages -->
                <div class="col-lg-6">
                    <div class="cg-panel uso-panel">
                        <h3 class="uso-panel__title"><i class="fa fa-birthday-cake" aria-hidden="true"></i> {{ $t('admin.user_stats.ages') }}</h3>
                        <p class="uso-hint">{{ $t('admin.user_stats.ages_hint', { n: fmt(s.profile.birthday), missing: fmt(s.totals.total - s.profile.birthday) }) }}</p>
                        <div v-if="s.profile.birthday" class="uso-ages">
                            <div v-for="a in age_rows" :key="a.key" class="uso-age">
                                <span class="uso-age__bar"><span :style="{ height: a.h }"></span></span>
                                <strong>{{ fmt(a.value) }}</strong>
                                <small>{{ a.label }}</small>
                            </div>
                        </div>
                        <p v-else class="cg-empty uso-empty"><i class="fa fa-calendar-o" aria-hidden="true"></i> {{ $t('admin.user_stats.ages_empty') }}</p>
                    </div>
                </div>

                <!-- languages & sign-in -->
                <div class="col-lg-6">
                    <div class="cg-panel uso-panel">
                        <h3 class="uso-panel__title"><i class="fa fa-sign-in" aria-hidden="true"></i> {{ $t('admin.user_stats.lang_signin') }}</h3>
                        <span class="uso-label">{{ $t('admin.user_stats.languages') }}</span>
                        <div class="uso-split" role="img" :aria-label="'EN ' + fmt(s.languages.us) + ', KA ' + fmt(s.languages.ka)">
                            <span class="uso-split__en" :style="{ flexGrow: s.languages.us || 0.0001 }">EN {{ pct(s.languages.us) }}%</span>
                            <span class="uso-split__ka" :style="{ flexGrow: s.languages.ka || 0.0001 }">KA {{ pct(s.languages.ka) }}%</span>
                        </div>
                        <span class="uso-label mt-3">{{ $t('admin.user_stats.sign_in') }}</span>
                        <div class="uso-signin">
                            <div><i class="fa fa-envelope-o" aria-hidden="true"></i><strong>{{ fmt(email_only) }}</strong><small>{{ $t('admin.user_stats.email_password') }}</small></div>
                            <div><i class="fa fa-google" aria-hidden="true"></i><strong>{{ fmt(s.sign_in.google) }}</strong><small>Google</small></div>
                            <div><i class="fa fa-facebook" aria-hidden="true"></i><strong>{{ fmt(s.sign_in.facebook) }}</strong><small>Facebook</small></div>
                            <div><i class="fa fa-user-plus" aria-hidden="true"></i><strong>{{ fmt(s.follows) }}</strong><small>{{ $t('admin.user_stats.follows') }}</small></div>
                        </div>
                    </div>
                </div>

                <!-- newest sign-ups -->
                <div class="col-12">
                    <div class="cg-panel uso-panel">
                        <h3 class="uso-panel__title"><i class="fa fa-user-plus" aria-hidden="true"></i> {{ $t('admin.user_stats.newest') }}</h3>
                        <p class="uso-hint">{{ $t('admin.user_stats.newest_hint') }}</p>
                        <ul class="uso-new">
                            <li v-for="u in s.recent" :key="u.id">
                                <button type="button" class="uso-new__btn" @click="open_user(u.id)">
                                    <img v-if="avatar(u.image)" :src="avatar(u.image)" alt="" class="uso-new__avatar" loading="lazy" decoding="async">
                                    <span v-else class="uso-new__avatar uso-new__avatar--empty">{{ initials(u.name) }}</span>
                                    <span class="uso-new__text">
                                        <strong>{{ u.name || ('#' + u.id) }}</strong>
                                        <small>{{ $t('admin.user_stats.joined', { date: ago(u.created_at) }) }}</small>
                                    </span>
                                    <span class="uso-new__badges">
                                        <span v-if="u.verified" class="badge text-bg-success" :title="$t('admin.users.col_verified')"><i class="fa fa-check" aria-hidden="true"></i></span>
                                        <span v-else class="badge text-bg-warning" :title="$t('admin.user_stats.unverified')"><i class="fa fa-envelope-o" aria-hidden="true"></i></span>
                                    </span>
                                </button>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </template>

        <!-- same modals as the Users list: info (read-only) → Edit stacks manage on top -->
        <UserInfoModal ref="user_info_modal" @manage="open_manage" @verified="load" />
        <UserManageModal ref="user_manage_modal" @update="on_user_changed" />
    </section>
</template>

<script>
    import UserInfoModal from '../modal/tab_modals/user_info/UserInfoModalComponent.vue'
    import UserManageModal from '../modal/tab_modals/user_manage/UserManageModalComponent.vue'

    export default {
        name: 'UsersOverview',
        components: { UserInfoModal, UserManageModal },
        emits: ['open-view'],
        data() {
            return { s: null, loading: false, all_roles: false }
        },
        computed: {
            locale() { return this.$i18n.locale === 'ka' ? 'ka-GE' : 'en-GB' },
            // tabs of the Users page (it switches on `open-view`)
            actions() {
                return [
                    { key: 'list', icon: 'fa-list', label: this.$t('admin.user_stats.open_users'), primary: true, can: this.$can('show', 'user') },
                    { key: 'commenters', icon: 'fa-user-secret', label: this.$t('admin.user_stats.open_commenters'), can: this.$can('show', 'commenter') },
                    { key: 'notifications', icon: 'fa-bell-o', label: this.$t('admin.user_stats.open_notifications'), can: this.$can('show', 'user_notification') },
                ].filter(a => a.can)
            },
            year_total() {
                return this.s ? this.s.signups_by_month.reduce((n, m) => n + m.count, 0) : 0
            },
            activity_rows() {
                const a = this.s.activity
                return [
                    { key: 'today', label: this.$t('admin.user_stats.act_today'), value: a.today, color: '#18a06f' },
                    { key: 'week', label: this.$t('admin.user_stats.act_week'), value: a.week, color: '#1f93b0' },
                    { key: 'month', label: this.$t('admin.user_stats.act_month'), value: a.month, color: 'var(--cg-accent)' },
                    { key: 'never', label: this.$t('admin.user_stats.act_never'), value: a.never, color: '#adb5bd' },
                ]
            },
            profile_rows() {
                const p = this.s.profile
                const t = (k) => this.$t('admin.user_stats.p_' + k)
                return [
                    { key: 'birthday', icon: 'fa-birthday-cake', label: t('birthday'), value: p.birthday },
                    { key: 'photo', icon: 'fa-camera', label: t('photo'), value: p.photo },
                    { key: 'phone', icon: 'fa-phone', label: t('phone'), value: p.phone },
                    { key: 'location', icon: 'fa-map-marker', label: t('location'), value: p.location },
                    { key: 'onboarded', icon: 'fa-flag-checkered', label: t('onboarded'), value: p.onboarded },
                ]
            },
            roles_shown() {
                return this.all_roles ? this.s.roles : this.s.roles.slice(0, 6)
            },
            age_rows() {
                const g = this.s.ages
                const max = Math.max(1, ...Object.values(g))
                const labels = { under_18: '<18', '18_24': '18–24', '25_34': '25–34', '35_44': '35–44', '45_plus': '45+' }
                return Object.keys(labels).map(k => ({ key: k, label: labels[k], value: g[k] || 0, h: Math.max(4, Math.round(100 * (g[k] || 0) / max)) + '%' }))
            },
            email_only() {
                return Math.max(0, this.s.totals.total - this.s.sign_in.google - this.s.sign_in.facebook)
            },
        },
        mounted() {
            this.load()
        },
        methods: {
            load() {
                this.loading = true
                axios.get('set_user/get_user_stats', { _silent: true })
                    .then(r => { this.s = r.data })
                    .catch(() => {})
                    .finally(() => { this.loading = false })
            },
            fmt(v) {
                return new Intl.NumberFormat(this.locale).format(Number(v) || 0)
            },
            pct(v) {
                const total = this.s ? Number(this.s.totals.total) : 0
                return total ? Math.round(100 * (Number(v) || 0) / total) : 0
            },
            level(p) {
                return p >= 75 ? 'good' : (p >= 40 ? 'mid' : 'low')
            },
            col_height(n) {
                const max = Math.max(1, ...this.s.signups_by_month.map(m => m.count))
                return Math.max(n ? 6 : 2, Math.round(100 * n / max)) + '%'
            },
            month_label(ym, long = false) {
                const [y, m] = ym.split('-').map(Number)
                return new Date(y, m - 1, 1).toLocaleDateString(this.locale, long ? { month: 'long', year: 'numeric' } : { month: 'short' })
            },
            ago(value) {
                return this.$timeAgo ? this.$timeAgo(value) : String(value || '').slice(0, 10)
            },
            avatar(image) {
                if (!image) return ''
                return /^(https?:)?\/\//.test(image) ? image : '/public/images/user_profil_img/' + image
            },
            initials(name) {
                return String(name || '?').split(' ').map(p => p.charAt(0)).join('').slice(0, 2).toUpperCase()
            },
            open_user(id) {
                this.$refs.user_info_modal.show_modal(id)
            },
            open_manage(id) {
                this.$refs.user_manage_modal.show_modal(id, {})
            },
            on_user_changed() {
                this.load()
                this.$refs.user_info_modal?.reload()
            },
        },
    }
</script>

<style scoped>
    .uso { margin-top: .25rem; }
    .uso-head { display: flex; align-items: flex-end; justify-content: space-between; flex-wrap: wrap; gap: .75rem; margin-bottom: 1rem; }
    .uso-head__title { margin: 0; font-size: 1.25rem; font-weight: 700; color: var(--cg-ink); }
    .uso-head__title .fa { color: var(--cg-accent); margin-right: .3rem; }
    .uso-actions { display: flex; flex-wrap: wrap; gap: .4rem; }
    .uso-hint { font-size: .85rem; color: var(--cg-stone); margin: 0 0 .85rem; }

    .uso-kpi {
        position: relative; display: flex; flex-direction: column; height: 100%; min-height: 7.5rem;
        padding: .95rem 1.1rem; border: 1px solid var(--cg-line); border-radius: var(--cg-r-lg);
        background: #fff; box-shadow: var(--cg-shadow-sm); overflow: hidden;
    }
    .uso-kpi::before { content: ''; position: absolute; inset: 0 auto 0 0; width: 4px; background: var(--tone); }
    .uso-kpi__label { font-size: .78rem; font-weight: 700; text-transform: uppercase; letter-spacing: .03em; color: var(--cg-muted); }
    .uso-kpi__num { font-family: var(--cg-font-display); font-size: 2.2rem; font-weight: 700; line-height: 1.1; color: var(--cg-ink); }
    .uso-kpi__sub { font-size: .8rem; color: var(--cg-stone); }
    .uso-kpi--skeleton { background: var(--cg-chalk-2); box-shadow: none; }

    .uso-panel { height: 100%; }
    .uso-panel__title { margin: 0 0 .2rem; font-size: 1.05rem; font-weight: 700; color: var(--cg-ink); }
    .uso-panel__title .fa { color: var(--cg-accent); margin-right: .3rem; }
    .uso-label { display: block; font-size: .75rem; font-weight: 700; text-transform: uppercase; letter-spacing: .03em; color: var(--cg-muted); margin-bottom: .3rem; }

    /* sign-ups per month */
    .uso-cols { display: grid; grid-template-columns: repeat(12, 1fr); gap: .35rem; height: 10rem; align-items: end; }
    .uso-col { display: flex; flex-direction: column; align-items: center; justify-content: flex-end; height: 100%; min-width: 0; }
    .uso-col__n { font-size: .7rem; font-weight: 700; color: var(--cg-stone); min-height: 1rem; }
    .uso-col__bar { width: 100%; max-width: 2rem; border-radius: .35rem .35rem 0 0; background: var(--cg-accent); opacity: .85; }
    .uso-col:hover .uso-col__bar { opacity: 1; }
    .uso-col__m { margin-top: .25rem; font-size: .68rem; color: var(--cg-muted); white-space: nowrap; overflow: hidden; text-overflow: clip; max-width: 100%; }

    /* horizontal bars */
    .uso-bars { display: grid; gap: .55rem; }
    .uso-bar { display: grid; grid-template-columns: 9.5rem 1fr 3rem; align-items: center; gap: .6rem; font-size: .9rem; }
    .uso-bar__label { white-space: nowrap; overflow: hidden; text-overflow: ellipsis; color: var(--cg-ink-2); }
    .uso-bar__label .fa { width: 1rem; color: var(--cg-muted); }
    .uso-bar__track { height: .6rem; border-radius: 1rem; background: var(--cg-chalk-2); overflow: hidden; }
    .uso-bar__fill { display: block; height: 100%; border-radius: 1rem; background: var(--cg-accent); }
    .uso-bar__fill.is-good { background: #18a06f; }
    .uso-bar__fill.is-mid { background: var(--cg-hold); }
    .uso-bar__fill.is-low { background: var(--cg-rope); }
    .uso-bar__num { text-align: right; font-weight: 700; font-variant-numeric: tabular-nums; }

    /* roles */
    .uso-roles { list-style: none; margin: 0 0 .4rem; padding: 0; display: grid; gap: .45rem; }
    .uso-roles li { display: grid; grid-template-columns: 10rem 1fr 2.5rem; align-items: center; gap: .6rem; font-size: .9rem; }
    .uso-roles__name { white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
    .uso-roles__name.is-admin { font-weight: 700; }
    .uso-roles__name.is-ban { color: #b02a37; font-weight: 700; }
    .uso-roles__track { height: .5rem; border-radius: 1rem; background: var(--cg-chalk-2); overflow: hidden; }
    .uso-roles__track span { display: block; height: 100%; border-radius: 1rem; background: #6c45c4; }
    .uso-roles__track span.is-ban { background: #dc3545; }
    .uso-roles strong { text-align: right; font-variant-numeric: tabular-nums; }

    /* ages */
    .uso-ages { display: grid; grid-template-columns: repeat(5, 1fr); gap: .6rem; align-items: end; }
    .uso-age { display: flex; flex-direction: column; align-items: center; gap: .15rem; }
    .uso-age__bar { display: flex; align-items: flex-end; width: 100%; max-width: 3rem; height: 6rem; }
    .uso-age__bar span { display: block; width: 100%; border-radius: .4rem .4rem 0 0; background: #1f93b0; }
    .uso-age small { color: var(--cg-muted); }
    .uso-empty { padding: 1rem; text-align: center; color: var(--cg-stone); }

    /* languages & sign-in */
    .uso-split { display: flex; height: 1.9rem; border-radius: 2rem; overflow: hidden; font-size: .78rem; font-weight: 700; color: #fff; }
    .uso-split span { display: flex; align-items: center; justify-content: center; min-width: 3.4rem; white-space: nowrap; }
    .uso-split__en { background: #1f93b0; }
    .uso-split__ka { background: #c4532d; }
    .uso-signin { display: grid; grid-template-columns: repeat(4, 1fr); gap: .5rem; }
    .uso-signin > div { display: flex; flex-direction: column; align-items: center; gap: .1rem; padding: .6rem .3rem; border-radius: var(--cg-r); background: var(--cg-chalk); text-align: center; }
    .uso-signin .fa { color: var(--cg-accent); font-size: 1.1rem; }
    .uso-signin strong { font-size: 1.15rem; }
    .uso-signin small { color: var(--cg-stone); font-size: .72rem; line-height: 1.2; }

    /* newest sign-ups */
    .uso-new { list-style: none; margin: 0; padding: 0; display: grid; grid-template-columns: repeat(auto-fill, minmax(15rem, 1fr)); gap: .5rem; }
    .uso-new__btn {
        display: flex; align-items: center; gap: .6rem; width: 100%;
        padding: .55rem .7rem; border: 1px solid var(--cg-line); border-radius: var(--cg-r);
        background: var(--cg-chalk); text-align: left; color: var(--cg-ink);
    }
    .uso-new__btn:hover { border-color: var(--cg-accent); background: var(--cg-accent-soft); }
    .uso-new__avatar { flex: 0 0 38px; width: 38px; height: 38px; border-radius: 50%; object-fit: cover; }
    .uso-new__avatar--empty { display: inline-flex; align-items: center; justify-content: center; background: var(--cg-accent-soft); color: var(--cg-accent-dark); font-weight: 800; font-size: .8rem; }
    .uso-new__text { flex: 1; min-width: 0; display: flex; flex-direction: column; }
    .uso-new__text strong { white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
    .uso-new__text small { color: var(--cg-stone); }

    @media (max-width: 575.98px) {
        .uso-bar { grid-template-columns: 7rem 1fr 2.6rem; font-size: .85rem; }
        .uso-roles li { grid-template-columns: 7.5rem 1fr 2rem; }
        .uso-signin { grid-template-columns: repeat(2, 1fr); }
        .uso-cols { height: 8rem; gap: .2rem; }
        .uso-col__m { font-size: .6rem; }
        .uso-kpi__num { font-size: 1.8rem; }
    }
</style>
