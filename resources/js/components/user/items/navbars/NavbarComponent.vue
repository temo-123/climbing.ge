<template>
    <header class="cg-nav cg-topbar admin_page_header_navbar" :class="{ animate: animate_enabled, 'has-sidebar': hasLeftMenu && sidebar_open }">
        <div class="cg-nav__inner">
            <!-- desktop: show/hide the sidebar of pages that have one -->
            <button
                v-if="user.length != 0 && hasLeftMenu"
                type="button"
                class="cg-icon-btn cg-topbar__toggle d-none d-lg-inline-flex"
                :aria-label="$t('user.nav.menu_header')"
                @click="toggle_menu"
            >
                <i class="fa fa-bars" aria-hidden="true"></i>
            </button>
            <!-- mobile: full menu in a drawer -->
            <button
                v-if="user.length != 0"
                type="button"
                class="cg-icon-btn cg-topbar__toggle d-lg-none"
                :aria-label="$t('user.nav.menu_header')"
                @click="drawer_open = true"
            >
                <i class="fa fa-bars" aria-hidden="true"></i>
            </button>

            <cg-brand service="user" :to="{ name: 'home' }" class="cg-topbar__brand" />

            <ul class="cg-nav__tools cg-topbar__tools">
                <motion-toggle />
                <localeSwitcher />
                <services-switcher current="user" />
                <nav-badges />
            </ul>
        </div>

        <teleport to="body">
            <transition name="cg-fade">
                <div v-if="drawer_open" class="cg-drawer-backdrop" @click="drawer_open = false"></div>
            </transition>
            <aside class="cg-drawer" :class="{ 'is-open': drawer_open }" :aria-hidden="!drawer_open">
                <div class="cg-sidebar__head">
                    <cg-brand service="user" :to="{ name: 'home' }" />
                    <button type="button" class="cg-sidebar__close" @click="drawer_open = false" :aria-label="$t('user.nav.menu_header')">
                        <i class="fa fa-times" aria-hidden="true"></i>
                    </button>
                </div>
                <div class="cg-sidebar__body">
                    <admin-menu-tree v-if="!menuLoading" :key="menuKey" @navigate="drawer_open = false" />
                    <div v-else class="cg-menu__empty">{{ $t('user.nav.loading_menu') }}</div>
                </div>
            </aside>
        </teleport>
    </header>
</template>

<script>
    import localeSwitcher from '../../../global_components/LocaleChangeComponent.vue'
    import AdminMenuTree from './AdminMenuTreeComponent.vue'

    export default {
        components: {
            localeSwitcher,
            'admin-menu-tree': AdminMenuTree,
        },
        name: 'NavbarComponent',
        data() {
            return {
                menuLoading: true,
                menuItem: null,
                menu_but: null,
                animate_enabled: false,
                menuKey: 0,
                user: [],
                hasLeftMenu: false,
                sidebar_open: false,
                drawer_open: false,
            };
        },
        mounted(){
            // Register BEFORE loadPermissions so we don't miss the synchronous emit
            this.$bus.$on('permissions-loaded', (permissions) => {
                if (this.$ability) {
                    this.$ability.update(permissions)
                }
                this.$nextTick(() => {
                    this.menuLoading = false;
                    this.menuKey++;
                })
            })
            this.$bus.$on('logged-in', () => {
                this.fetchPermissions()
                this.get_user()
            })
            this.get_user()
            this.loadPermissions()
            this.$bus.$on('menu-toggle', () => {
                this.animate_enabled = true;
                requestAnimationFrame(() => {
                    setTimeout(() => {
                        this.animate_enabled = false;
                    }, 500);
                });
            });
            this.$bus.$on('sidebar-state', (open) => { this.sidebar_open = open; });
            this.$bus.$on('left-menu-mounted', () => { this.hasLeftMenu = true; });
            this.$bus.$on('left-menu-unmounted', () => { this.hasLeftMenu = false; });
        },
        beforeUnmount() {
            this.$bus.$off('menu-toggle');
            this.$bus.$off('permissions-loaded');
            this.$bus.$off('logged-in');
            this.$bus.$off('left-menu-mounted');
            this.$bus.$off('left-menu-unmounted');
            this.$bus.$off('sidebar-state');
        },
        watch: {
            '$route' (to, from) {
                window.scrollTo(0,0)
                this.drawer_open = false
            }
        },
        methods: {
            loadPermissions() {
                const storedPermissions = localStorage.getItem('user_permissions')
                if (storedPermissions) {
                    try {
                        const permissions = JSON.parse(storedPermissions)
                        if (permissions && permissions.length > 0 && this.$ability) {
                            this.$ability.update(permissions)
                            this.$bus.$emit('permissions-loaded', permissions)
                            this.$nextTick(() => {
                                this.menuLoading = false;
                            })
                            return
                        }
                    } catch (e) {
                        console.error('Error parsing stored permissions:', e)
                    }
                }
                this.fetchPermissions()
            },
            fetchPermissions() {
                axios
                    .get('get_user/get_auth_user_permissions')
                    .then(response => {
                        if (this.$ability) {
                            this.$ability.update(response.data)
                            this.$bus.$emit('permissions-loaded', response.data)
                            this.$nextTick(() => {
                                this.menuLoading = false;
                            })
                        }
                        localStorage.setItem('user_permissions', JSON.stringify(response.data))
                    })
                    .catch(error => {
                        console.log('Error fetching permissions:', error)
                        this.menuLoading = false
                    })
            },
            get_user(){
                axios
                .get('auth_user')
                .then((response)=>{
                    this.user = response.data['name']
                })
                .catch()
            },
            logout(){
                axios.post('logout')
                    .finally(() => {
                        localStorage.removeItem('x_xsrf_token');
                        localStorage.removeItem('user_permissions');
                        localStorage.removeItem('auth_token');
                        if (this.$ability) this.$ability.update([]);
                        this.$bus.$emit('logged-out');
                        this.$router.push({ name: 'login' });
                    });
            },
            toggle_menu(){
                if (this.menuLoading) return;
                this.$bus.$emit('sidebar-toggle');
                this.animate_enabled = true;
                this.$nextTick(() => {
                    requestAnimationFrame(() => {
                        setTimeout(() => {
                            this.animate_enabled = false;
                        }, 500);
                    });
                });
            },
        },
    }
</script>
