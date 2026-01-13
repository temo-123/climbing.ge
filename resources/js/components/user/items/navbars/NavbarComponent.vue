<template>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark bg-perple fixed-top admin_page_header_navbar" :class="{ animate: animate_enabled }">
        <div class="mx-auto order-0 mobile_title">
            <router-link :to="{name: 'home'}" class="navbar-brand mx-auto" exact>Welcome to climbing.ge user page</router-link>
        </div>

        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation" v-if="user.length != 0">
            <i class="fa fa-bars " aria-hidden="true"></i>
        </button>
        
        <div class="navbar-collapse collapse w-100 order-1 order-md-0 dual-collapse2 mobali_menu" id="navbarNav">
            <ul  class="navbar-nav admin_navbar">
                <li v-for="menu_item in menu_items" :key="menu_item.id" v-if="menu_item.routes && haveMenuBlockPermission(menu_item)" class="nav-item dropdown">
                    <a href="#" class="nav-link dropdown-toggle" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">{{menu_item.title}}</a>
                    <div class="dropdown-menu" aria-labelledby="navbarDropdown" >
                        <router-link :to="{path: menu_but.route}" lass="nav-link" class="dropdown-item" v-for="menu_but in menu_item.routes" :key="menu_but.id" v-if="menu_but.hasOwnProperty('permissions') && haveMenuButPermission(menu_but.permissions)">
                            {{menu_but.name}}
                        </router-link>
                    </div>
                </li>
                
                <li v-else-if="menu_item.route && menu_item.hasOwnProperty('permissions') && haveMenuButPermission(menu_item.permissions)" class="nav-item">
                    <router-link :to="{path: menu_item.route}" lass="nav-link" class="nav-link">{{menu_item.title}}</router-link>
                </li>
                
                <li v-else-if="menu_item.route && !menu_item.hasOwnProperty('permissions')" class="nav-item">
                    <router-link :to="{path: menu_item.route}" lass="nav-link" class="nav-link">{{menu_item.title}}</router-link>
                </li>

                <li class="nav-item">
                    <router-link :to="{name: 'cart'}" class="nav-link" exact>
                        <span>
                            <i class="fa fa-shopping-cart" aria-hidden="true"></i>
                        </span>
                    </router-link>
                </li>

                <li class="nav-item dropdown">
                    <!-- <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"> -->
                    <a class="nav-link" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <span>
                            <i class="fa fa-user-circle"></i>
                        </span>
                    </a>
                    <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                        <!-- <a class="dropdown-item" href="#">Options</a> -->

                        <router-link :to="'/options'" class="dropdown-item">
                            {{ $t('user.menu.options') }}
                        </router-link>

                        <router-link :to="'/my_comments_and_reviews'" class="dropdown-item">
                            {{ $t('user.menu.my comments') }}
                        </router-link>

                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item" href="#" @click="logout()">{{ $t('user.menu.logout') }}</a>

                        <!-- <ul style="padding-left: 0px;" @click="logout()">
                            <li><a>{{ $t('user.menu.logout') }}</a></li>
                        </ul> -->
                    </div>
                </li>

                <!-- <li class="nav-item">
                    <a class="nav-link" @click="logout()">{{ $t('user.menu.logout') }}</a>
                </li> -->
            
            </ul>
        </div>
            
        <div class="navbar-collapse collapse w-100 order-1 order-md-0 dual-collapse2" v-if="user.length != 0">
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
                <!-- <li class="nav-item">
                    <router-link :to="{name: 'myComentsList'}" class="nav-link" exact>{{ $t('user.menu.my comments') }}</router-link>
                </li>
                <li class="nav-item">
                    <router-link :to="{name: 'myOrders'}" class="nav-link" exact>{{ $t('user.menu.my orders') }}</router-link>
                </li> -->

                <li>
                    <router-link :to="{name: 'cart'}" class="nav-link" exact>
                        <span>
                            <i class="fa fa-shopping-cart" aria-hidden="true"></i>
                        </span>
                    </router-link>
                </li>

                <li class="nav-item dropdown" v-if="user.length != 0">
                    <a class="nav-link" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <span>
                            <i class="fa fa-user-circle"></i>
                        </span>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                        <!-- <a class="dropdown-item" href="#">Options</a> -->
                        <router-link :to="'/options'" class="dropdown-item">
                            {{ $t('user.menu.options') }}
                        </router-link>

                        <router-link :to="'/my_comments_and_reviews'" class="dropdown-item">
                            {{ $t('user.menu.my comments') }}
                        </router-link>

                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item" href="#" @click="logout()">{{ $t('user.menu.logout') }}</a>

                        <!-- <ul style="padding-left: 0px;" @click="logout()">
                            <li><a>{{ $t('user.menu.logout') }}</a></li>
                        </ul> -->
                    </div>
                </li>
            </ul>
        </div>
    </nav>
</template>
    
<script>
    import countryFlag from 'vue-country-flag' // https://www.npmjs.com/package/vue-country-flag
    
    import { navbar } from '../../../../mixins/navbar_pages_mixin.js'
    
    export default {
        mixins: [
            navbar
        ],
        name: 'leftMenu',
        data(){
            return {
                menu_items: navbar.admin_all_menu(),
                animate_enabled: false,
                
                get activ_lang() {
                    return localStorage.getItem('lang') || 'en';
                },
                set activ_lang(value) {
                    localStorage.setItem('lang', value);
                },
                
                user: [],
            };
        },
        mounted(){
            this.get_user()
            // Listen for menu toggle events from LeftMenuComponent
            this.$root.$on('menu-toggle', () => {
                this.animate_enabled = true;
                // Use requestAnimationFrame to ensure CSS transition is applied before position change
                requestAnimationFrame(() => {
                    setTimeout(() => {
                        this.animate_enabled = false;
                    }, 500);
                });
            });
        },
        beforeUnmount() {
            this.$root.$off('menu-toggle');
        },
        components: {
            countryFlag,
        },
        watch: {
            '$route' (to, from) {
                this.user = '',
                this.get_user(),
                window.scrollTo(0,0)

                let navbar = document.getElementById("navbarNav")
                navbar.classList.remove("show")
            }
        },
        methods: {
            get_user(){
                axios
                .get('/auth_user')
                .then((response)=>{
                    this.user = response.data['name']
                })
                .catch(
                    // this.user = 'Boss'
                );
            },

            logout(){
                axios
                .post(process.env.MIX_APP_SSH + process.env.MIX_USER_PAGE_URL + '/logout')
                .then(()=>{
                    localStorage.removeItem('x_xsrf_token');
                    this.$router.push({ name: "login" });
                })
                
            },
        
            haveMenuBlockPermission(menu_section){
                let perm_num = 0;
                
                for (let j = 0; j < menu_section.routes.length; j++) {
                    if (menu_section.routes[j].hasOwnProperty('permissions') && menu_section.routes[j].permissions.length) {
                        for (let i = 0; i < menu_section.routes[j].permissions.length; i++) {
                            if(this.$can(menu_section.routes[j].permissions[i][0], menu_section.routes[j].permissions[i][1])){
                                perm_num++
                            }
                        }
                    }
                    else if(!menu_section.routes[j].hasOwnProperty('permissions') || !menu_section.routes[j].permissions.length){
                        perm_num++
                    }
                }
                
                if(perm_num > 0){
                    return true
                }
                else{
                    return false
                }
            },
        
            haveMenuButPermission(permissions){
                if(permissions.length){
                    let perms = permissions
                    for (let i = 0; i < perms.length; i++) {
                        if(this.$can(perms[i][0], perms[i][1])){
                            return true
                        }
                        else{
                            return false
                        }
                    }
                }
                else{
                    return true
                }
            },
            
            toggle_menu(){
                this.animate_enabled = true;
                // Use requestAnimationFrame to ensure CSS transition is applied before position change
                requestAnimationFrame(() => {
                    setTimeout(() => {
                        this.animate_enabled = false;
                    }, 500);
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
    .menu_but{
        position: fixed;
        font-size: 200%;
        top: 0;
    }
    /* .bg-perple {
        background-color: #7427bb !important;
    } */
    /*@media (max-width: 993px) {
        .dropdown-menu .dropdown-item{
            color: #2b2a2a !important;
        }
    }*/
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
    