<template>
    <div class="cg-menu">
        <div class="cg-menu__search">
            <i class="fa fa-search" aria-hidden="true"></i>
            <input
                v-model="query"
                type="search"
                class="cg-menu__search-input"
                :placeholder="$t('user.nav.find_page')"
                autocomplete="off"
            >
        </div>

        <div v-if="!menu_items.length" class="cg-menu__empty">{{ $t('user.nav.loading_menu') }}</div>
        <div v-else-if="!visible_items.length" class="cg-menu__empty">{{ $t('user.nav.no_pages') }}</div>

        <ul v-else class="cg-menu__list">
            <template v-for="(item, index) in visible_items" :key="(item.id || item.title || index) + '-' + permissionsKey">
                <!-- Group -->
                <li v-if="item.routes" class="cg-menu__group" :class="{ 'is-open': is_open(item), 'is-active': isAnySubActive(item.routes) }">
                    <button type="button" class="cg-menu__group-btn" @click="toggle_dropdown(item.title)" :aria-expanded="is_open(item)">
                        <span class="cg-menu__icon"><i :class="'fa ' + icon_for(item)" aria-hidden="true"></i></span>
                        <span class="cg-menu__label">{{ item.title }}</span>
                        <span v-if="group_badge(item)" class="cg-menu__badge" :class="group_badge(item).cls" :title="group_badge(item).title">{{ group_badge(item).n }}</span>
                        <span v-else class="cg-menu__count">{{ item.routes.length }}</span>
                        <i class="fa fa-angle-down cg-menu__caret" aria-hidden="true"></i>
                    </button>
                    <ul v-show="is_open(item)" class="cg-menu__sub">
                        <li v-for="(menu_but, i) in item.routes" :key="menu_but.name + i">
                            <router-link :to="{ path: menu_but.route }" class="cg-menu__link" :class="{ 'is-active': isActive(menu_but.route) }" @click="$emit('navigate')">
                                <span class="cg-menu__link-text">{{ menu_but.name }}</span>
                                <span v-if="link_badge(menu_but.route)" class="cg-menu__badge" :class="link_badge(menu_but.route).cls" :title="link_badge(menu_but.route).title">{{ link_badge(menu_but.route).n }}</span>
                            </router-link>
                        </li>
                    </ul>
                </li>

                <!-- Single link -->
                <li v-else class="cg-menu__single">
                    <router-link :to="{ path: item.route }" class="cg-menu__group-btn" :class="{ 'is-active': isActive(item.route) }" @click="$emit('navigate')">
                        <span class="cg-menu__icon"><i :class="'fa ' + icon_for(item)" aria-hidden="true"></i></span>
                        <span class="cg-menu__label">{{ item.title }}</span>
                        <span v-if="link_badge(item.route)" class="cg-menu__badge" :class="link_badge(item.route).cls" :title="link_badge(item.route).title">{{ link_badge(item.route).n }}</span>
                    </router-link>
                </li>
            </template>
        </ul>
    </div>
</template>

<script>
    import navbar_pages_mixin from '../../../../mixins/navbar_pages_mixin.js'
    import { menu_badges, menu_badge, sum_badges, load_menu_badges, seen_menu_path } from '../../../../services/menuBadges.js'

    // Menu tree shared by the desktop sidebar (LeftMenuComponent) and the mobile
    // drawer in the top bar. Reads the menu from navbar_pages_mixin and filters
    // every entry through CASL, exactly like the old sidebar did.
    const SECTION_ICONS = {
        favorites: 'fa-star',
        guide: 'fa-map-o',
        guide_tasks: 'fa-tasks',
        shop: 'fa-shopping-bag',
        shop_tasks: 'fa-clipboard',
        tour_guide: 'fa-compass',
        blog: 'fa-newspaper-o',
        films: 'fa-film',
        summit: 'fa-flag',
        training: 'fa-heartbeat',
        partner_organizations: 'fa-handshake-o',
        site_options: 'fa-sliders',
    }

    export default {
        name: 'AdminMenuTree',
        mixins: [navbar_pages_mixin],
        emits: ['navigate'],
        data() {
            return {
                query: '',
                open_dropdowns: JSON.parse(localStorage.getItem('left_menu_open_dropdowns') || '{}'),
                permissionsKey: 0,
            }
        },
        computed: {
            menu_items() {
                // permissionsKey dependency makes this recompute when permissions change
                if (this.permissionsKey < 0) return []
                return (this.admin_all_menu() || [])
                    .map(item => {
                        if (!item) return null
                        if (item.routes) {
                            const routes = (item.routes || []).filter(r => r && this.haveMenuButPermission(r.permissions))
                            return routes.length ? { ...item, routes } : null
                        }
                        if (item.route && this.haveMenuButPermission(item.permissions)) return item
                        return null
                    })
                    .filter(Boolean)
            },
            visible_items() {
                const q = this.query.trim().toLowerCase()
                if (!q) return this.menu_items
                return this.menu_items
                    .map(item => {
                        if (item.routes) {
                            if (item.title.toLowerCase().includes(q)) return item
                            const routes = item.routes.filter(r => r.name.toLowerCase().includes(q))
                            return routes.length ? { ...item, routes } : null
                        }
                        return item.title.toLowerCase().includes(q) ? item : null
                    })
                    .filter(Boolean)
            },
        },
        mounted() {
            this._onPermissions = (permissions) => {
                if (this.$ability) this.$ability.update(permissions)
                this.permissionsKey++
                load_menu_badges(this.$router, this.$ability, { force: true })
            }
            this.$bus.$on('permissions-loaded', this._onPermissions)

            // alert badges (services/menuBadges.js): shared store, refetched at most every 2 min
            load_menu_badges(this.$router, this.$ability).then(() => seen_menu_path(this.$route.path))
            this._onProfile = () => load_menu_badges(this.$router, this.$ability, { force: true })
            this.$bus.$on('profile-updated', this._onProfile)
            this._onVisible = () => { if (!document.hidden) load_menu_badges(this.$router, this.$ability) }
            document.addEventListener('visibilitychange', this._onVisible)
            this._badgeTimer = setInterval(() => load_menu_badges(this.$router, this.$ability), 5 * 60 * 1000)
        },
        beforeUnmount() {
            this.$bus.$off('permissions-loaded', this._onPermissions)
            this.$bus.$off('profile-updated', this._onProfile)
            document.removeEventListener('visibilitychange', this._onVisible)
            clearInterval(this._badgeTimer)
        },
        watch: {
            // opening a page marks its "new" alerts as seen
            '$route.path'(path) { seen_menu_path(path) },
        },
        methods: {
            // urgent (red) wins over new / unread (accent); shows the matching count
            badge_view(b) {
                if (!b) return null
                if (b.urgent > 0) return { n: b.urgent > 99 ? '99+' : b.urgent, cls: 'is-urgent', title: this.$t('user.nav.badge_urgent', { n: b.urgent }) }
                if (b.fresh > 0) return { n: b.fresh > 99 ? '99+' : b.fresh, cls: 'is-fresh', title: this.$t('user.nav.badge_new', { n: b.fresh }) }
                return null
            },
            link_badge(route) {
                // reading menu_badges.routes keeps this reactive
                return menu_badges.routes && this.badge_view(menu_badge(route))
            },
            group_badge(item) {
                return menu_badges.routes && this.badge_view(sum_badges(item.routes.map(r => r.route)))
            },
            icon_for(item) {
                if (!item.routes) return 'fa-home'
                return SECTION_ICONS[item.name] || 'fa-folder-o'
            },
            haveMenuButPermission(permissions) {
                if (!permissions || !Array.isArray(permissions) || !permissions.length) return true
                if (!this.$ability) return true
                return permissions.some(p => p && this.$can(p[0], p[1]))
            },
            isActive(route) {
                return this.$route.path === route
            },
            isAnySubActive(routes) {
                return routes.some(sub => this.isActive(sub.route))
            },
            is_open(item) {
                // while filtering, every matching group is expanded
                if (this.query.trim()) return true
                if (this.open_dropdowns[item.title] === undefined) return this.isAnySubActive(item.routes)
                return this.open_dropdowns[item.title] === true
            },
            toggle_dropdown(menu_name) {
                const willOpen = !this.is_open({ title: menu_name, routes: (this.menu_items.find(i => i.title === menu_name) || {}).routes || [] })
                const next = {}
                Object.keys(this.open_dropdowns).forEach(k => { next[k] = false })
                next[menu_name] = willOpen
                this.open_dropdowns = next
                localStorage.setItem('left_menu_open_dropdowns', JSON.stringify(next))
            },
        },
    }
</script>
