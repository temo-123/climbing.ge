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
                        <span class="cg-menu__count">{{ item.routes.length }}</span>
                        <i class="fa fa-angle-down cg-menu__caret" aria-hidden="true"></i>
                    </button>
                    <ul v-show="is_open(item)" class="cg-menu__sub">
                        <li v-for="(menu_but, i) in item.routes" :key="menu_but.name + i">
                            <router-link :to="{ path: menu_but.route }" class="cg-menu__link" :class="{ 'is-active': isActive(menu_but.route) }" @click="$emit('navigate')">
                                {{ menu_but.name }}
                            </router-link>
                        </li>
                    </ul>
                </li>

                <!-- Single link -->
                <li v-else class="cg-menu__single">
                    <router-link :to="{ path: item.route }" class="cg-menu__group-btn" :class="{ 'is-active': isActive(item.route) }" @click="$emit('navigate')">
                        <span class="cg-menu__icon"><i :class="'fa ' + icon_for(item)" aria-hidden="true"></i></span>
                        <span class="cg-menu__label">{{ item.title }}</span>
                    </router-link>
                </li>
            </template>
        </ul>
    </div>
</template>

<script>
    import navbar_pages_mixin from '../../../../mixins/navbar_pages_mixin.js'

    // Menu tree shared by the desktop sidebar (LeftMenuComponent) and the mobile
    // drawer in the top bar. Reads the menu from navbar_pages_mixin and filters
    // every entry through CASL, exactly like the old sidebar did.
    const SECTION_ICONS = {
        favorites: 'fa-star',
        guide: 'fa-map-o',
        guide_tasks: 'fa-tasks',
        shop: 'fa-shopping-bag',
        shop_tasks: 'fa-clipboard',
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
            }
            this.$bus.$on('permissions-loaded', this._onPermissions)
        },
        beforeUnmount() {
            this.$bus.$off('permissions-loaded', this._onPermissions)
        },
        methods: {
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
