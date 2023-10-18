<template>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark bg-perple fixed-top admin_page_header_navbar">

        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
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
                    <a class="nav-link">{{ $t('user.menu.logout') }}</a>
                </li>
            
            </ul>
        </div>
            
        <div class="navbar-collapse collapse w-100 order-1 order-md-0 dual-collapse2">
            <ul class="navbar-nav mr-auto">
                <li class="nav-item">
                    <label for="check">
                        <span id="open_menu" class="menu_but">
                            <i class="fa fa-bars cursor_pointer" aria-hidden="true"></i>
                        </span>
                    </label>
                </li>
            </ul>
        </div>
        <div class="mx-auto order-0">
            <router-link :to="{name: 'home'}" class="navbar-brand mx-auto" exact>Hi</router-link>
        </div>
        <div class="navbar-collapse collapse w-100 order-3 dual-collapse2">
            <!-- <ul class="navbar-nav ml-auto">
                <li class="nav-item">
                    <router-link :to="{name: 'myComentsList'}" class="nav-link" exact>{{ $t('user.menu.my comments') }}</router-link>
                </li>
                <li class="nav-item">
                    <router-link :to="{name: 'myOrders'}" class="nav-link" exact>{{ $t('user.menu.my orders') }}</router-link>
                </li>
                <li class="nav-item">
                    <router-link :to="{name: 'cart'}" class="nav-link" exact>{{ $t('user.menu.cart') }}</router-link>
                </li>
            </ul> -->
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
                
                get activ_lang() {
                    return localStorage.getItem('lang') || 'en';
                },
                set activ_lang(value) {
                    localStorage.setItem('lang', value);
                },
                
                user: '',
            };
        },
        mounted(){
            this.get_user()
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
                .get('/api/auth_user')
                .then((response)=>{
                    this.user = response.data['name']
                })
                .catch(
                    // this.user = 'Boss'
                );
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
        },
    }
</script>
    
<style>
    .admin_navbar{
        max-height: 380px;
        overflow-y: auto;
    }
    .admin_page_header_navbar{
        transition: all .5s;
    }
    .menu_but{
        position: fixed;
        font-size: 200%;
        top: 0;
    }
    .bg-perple {
        background-color: #7427bb !important;
    }
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
</style>
    