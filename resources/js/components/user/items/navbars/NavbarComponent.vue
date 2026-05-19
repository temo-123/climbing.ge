<template>
    <nav :key="menuKey" class="navbar navbar-expand-lg navbar-dark bg-dark bg-perple fixed-top admin_page_header_navbar" :class="{ animate: animate_enabled }">
        <div class="mx-auto order-0 mobile_title">
            <router-link :to="{name: 'home'}" class="navbar-brand mx-auto" exact>Welcome to climbing.ge user page</router-link>
        </div>

        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation" v-if="user.length != 0">
            <i class="fa fa-bars " aria-hidden="true"></i>
        </button>
        
        <div class="navbar-collapse collapse w-100 order-1 order-md-0 dual-collapse2 mobali_menu" id="navbarNav">
            <ul v-if="!menuLoading" class="navbar-nav admin_navbar">
                <template v-for="(menuItem, index) in safe_menu_items" :key="menuItem?.id || index">
                    <li v-if="menuItem.routes && haveMenuBlockPermission(menuItem)" class="nav-item dropdown">
                        <a href="#" class="nav-link dropdown-toggle" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">{{menuItem.title}}</a>
                        <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                            <template v-for="(menu_but, menuButIndex) in menuItem.routes || []" :key="menu_but?.id || menuButIndex">
                                <router-link v-if="menu_but && (menu_but.hasOwnProperty('permissions') ? haveMenuButPermission(menu_but.permissions) : true)" :to="{path: menu_but.route}" class="dropdown-item">
                                {{menu_but.name}}
                                </router-link>
                            </template>
                        </div>
                    </li>
                    <li v-else-if="menuItem.route && menuItem.hasOwnProperty('permissions') && haveMenuButPermission(menuItem.permissions)" class="nav-item">
                        <router-link :to="{path: menuItem.route}" class="nav-link">{{menuItem.title}}</router-link>
                    </li>
                    <li v-else-if="menuItem.route && !menuItem.hasOwnProperty('permissions')" class="nav-item">
                        <router-link :to="{path: menuItem.route}" class="nav-link">{{menuItem.title}}</router-link>
                    </li>
                </template>

                <nav-badges />

                <li class="nav-item dropdown">
                    <a class="nav-link" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <span>
                            <i class="fa fa-bars"></i>
                        </span>
                    </a>
                    <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                        <router-link :to="'/options'" class="dropdown-item">
                            {{ $t('user.menu.options') }}
                        </router-link>

                        <router-link :to="'/my_comments_and_reviews'" class="dropdown-item">
                            {{ $t('user.menu.my comments and product reviews') }}
                        </router-link>

                        <router-link :to="'/purchases'" class="dropdown-item">
                            {{ $t('user.menu.my purchases') }}
                        </router-link>

                        <div class="dropdown-divider"></div>

                        <a class="dropdown-item" href="#" @click="logout()">{{ $t('user.menu.logout') }}</a>
                    </div>
                </li>
            </ul>
            <ul v-else class="navbar-nav admin_navbar" style="min-height: 50px;"></ul>
        </div>
            
        <div class="navbar-collapse collapse w-100 order-1 order-md-0 dual-collapse2" v-if="user.length != 0 && hasLeftMenu">
            <ul class="navbar-nav mr-auto">
                <li class="nav-item">
                    <label for="check" @click="toggle_menu">
                        <span id="open_menu" class="menu_but">
                            <i class="fa fa-bars cursor_pointer" aria-hidden="true"></i>
                        </span>
                    </label>
                </li>
            </ul>
        </div>

        <div class="mx-auto order-0 desktop_title">
            <router-link :to="{name: 'home'}" class="navbar-brand mx-auto" exact>Welcome to climbing.ge user page</router-link>
        </div>

        <div class="navbar-collapse collapse w-100 order-3 dual-collapse2">
            <ul class="navbar-nav ml-auto">
                <nav-badges />
            </ul>
        </div>
    </nav>
</template>

<script>
    import navbar_pages_mixin from '../../../../mixins/navbar_pages_mixin.js'
    
    export default {
        mixins: [navbar_pages_mixin],
        computed: {
            menu_items() {
                return this.admin_all_menu() || [];
            },
            safe_menu_items() {
                return this.menuLoading ? [] : (this.menu_items || []);
            }
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
            this.$bus.$on('left-menu-mounted', () => { this.hasLeftMenu = true; });
            this.$bus.$on('left-menu-unmounted', () => { this.hasLeftMenu = false; });
        },
        beforeUnmount() {
            this.$bus.$off('menu-toggle');
            this.$bus.$off('permissions-loaded');
            this.$bus.$off('logged-in');
            this.$bus.$off('left-menu-mounted');
            this.$bus.$off('left-menu-unmounted');
        },
        watch: {
            '$route' (to, from) {
                window.scrollTo(0,0)
                let navbar = document.getElementById("navbarNav")
                if (navbar) navbar.classList.remove("show")
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
            haveMenuBlockPermission(menu_section){
                if (!menu_section) return false;
                if (!menu_section.routes || !Array.isArray(menu_section.routes)) return false;
                if (!this.$ability) return true;
                
                let perm_num = 0;
                for (let j = 0; j < menu_section.routes.length; j++) {
                    const routeItem = menu_section.routes[j];
                    if (!routeItem) continue;
                    
                    if (routeItem.hasOwnProperty('permissions') && routeItem.permissions && routeItem.permissions.length) {
                        for (let i = 0; i < routeItem.permissions.length; i++) {
                            if(this.$can(routeItem.permissions[i][0], routeItem.permissions[i][1])){
                                perm_num++
                                break;
                            }
                        }
                    } else {
                        perm_num++;
                    }
                }
                return perm_num > 0;
            },
            haveMenuButPermission(permissions){
                if (!permissions || !Array.isArray(permissions) || permissions.length === 0) {
                    return true;
                }
                let perms = permissions
                for (let i = 0; i < perms.length; i++) {
                    if (!perms[i]) continue;
                    if(this.$can(perms[i][0], perms[i][1])){
                        return true
                    }
                }
                return false;
            },
            toggle_menu(){
                if (this.menuLoading) return;
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

<style>
    .admin_navbar{
        max-height: 380px;
        overflow-y: auto;
    }
    @media (max-width: 992px) {
        .mobali_menu .admin_navbar {
            max-height: none;
            overflow-y: visible;
        }
        .mobali_menu .dropdown-menu {
            position: static !important;
            float: none;
            width: 100%;
            border: none;
            box-shadow: none;
            background-color: transparent;
        }
        .mobali_menu .dropdown-menu .dropdown-item {
            color: rgba(255, 255, 255, 0.5);
            padding-left: 2rem;
        }
        .mobali_menu .dropdown-menu .dropdown-item:hover {
            color: #fff;
            background-color: transparent;
        }
    }
    .menu_but{
        position: fixed;
        font-size: 200%;
        top: 0;

        padding-right: .5rem;
        padding-left: .5rem;
        margin-top: .7rem
    }
    @media (max-width: 1230px) {
        .navbar-collapse {
            border-top: none;
            box-shadow: none;
        }
    }
    @media (min-width: 993px) {
        #navbarNav{
            display: none !important;
        }
    }
    @media (max-width: 990px) {
        .navbar-nav {
            margin: 7.5px 0;
        }
        .desktop_title{
            display: none;
        }
    }
    @media (min-width: 990px) {
        .mobile_title{
            display: none;
        }
    }
    .animate {
        transition: all .5s ease;
    }
</style>
