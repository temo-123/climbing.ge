<template>
    <ul class="list-group user_page_menu">
         <li v-if="menu_but" class="list-group-item text-muted admin_menu_static_block">Munu</li>
        <li v-if="!menu_but" class="list-group-item text-muted admin_menu_static_block admin_menu_open" @click="open_menu">Open munu</li>

        <div v-if="menu">
            <router-link :to="{name: 'home'}" exact>
                <li class="list-group-item text-muted">
                    <span class="admin_menu">{{__ ('user menu home') }}</span>
                </li>
            </router-link>
            <router-link :to="{name: 'cart'}" exact> 
                <li class="list-group-item text-muted ">
                    <span class="admin_menu">{{__ ('user menu cart') }}</span>
                </li>
            </router-link>
            <router-link :to="{name: 'myOrders'}" exact> 
                <li class="list-group-item text-muted ">
                    <span class="admin_menu">{{__ ('user menu my orders') }}</span>
                </li>
            </router-link>
            <router-link :to="{name: 'favoriteProducts'}" exact> 
                <li class="list-group-item text-muted">
                    <span class="admin_menu">{{__ ('user menu favorite products') }}</span>
                </li>
            </router-link>
            <router-link :to="{name: 'interestedEvents'}" exact> 
                <li class="list-group-item text-muted ">
                    <span class="admin_menu">{{__ ('user menu interested events') }}</span>
                </li>
            </router-link>
            <router-link :to="{name: 'myComentsList'}" exact> 
                <li class="list-group-item text-muted ">
                    <span class="admin_menu">{{__ ('user menu my comments') }}</span>
                </li>
            </router-link>

            <li class="list-group-item text-muted admin_menu_static_block"></li>

            <router-link :to="{name: 'productsList'}" exact> 
                <li class="list-group-item text-muted">
                    <span class="admin_menu">{{__ ('user menu products & category') }}</span>
                </li>
            </router-link>
            <router-link :to="{name: 'servicesList'}" exact> 
                <li class="list-group-item text-muted ">
                    <span class="admin_menu">{{__ ('user menu services') }}</span>
                </li>
            </router-link>
            <router-link :to="{name: 'allOrders'}" exact> 
                <li class="list-group-item text-muted">
                    <span class="admin_menu">{{__ ('user menu total orders') }}</span>
                </li>
            </router-link>

            <li class="list-group-item text-muted admin_menu_static_block"></li>

            <router-link :to="{name: 'filmsList'}">
                <li class="list-group-item text-muted m">
                    <span class="admin_menu">{{__ ('user menu films') }}(Beta)</span>
                </li>
            </router-link>

            <li class="list-group-item text-muted admin_menu_static_block"></li>

            <router-link :to="'../article/outdoor'" exact> 
                <li class="list-group-item text-muted">
                    <span class="admin_menu">{{__ ('user menu outdoor climbing') }}</span>
                </li>
            </router-link>
            <router-link :to="{name: 'routeAndSectorList'}" exact>
                <li class="list-group-item text-muted mbing Secrots And Route">
                    <span class="admin_menu">{{__ ('user menu sectors & routes') }}</span>
                </li>
            </router-link>
            <router-link :to="'../article/mount'" exact> 
                <li class="list-group-item text-muted">
                    <span class="admin_menu">{{__ ('user menu mountaineering') }}</span>
                </li>
            </router-link>
            <router-link :to="'../article/indoor'" exact> 
                <li class="list-group-item text-muted">
                    <span class="admin_menu">{{__ ('user menu indoor climbing') }}</span>
                </li>
            </router-link>
            <router-link :to="'../article/ice'" exact>
                <li class="list-group-item text-muted">
                    <span class="admin_menu">{{__ ('user menu ice climbing') }}</span>
                </li>
            </router-link>
            <router-link :to="'../article/news'" exact> 
                <li class="list-group-item text-muted ">
                    <span class="admin_menu">{{__ ('user menu news') }}</span>
                </li>
            </router-link>
            <router-link :to="'../article/other'" exact> 
                <li class="list-group-item text-muted" >
                    <span class="admin_menu">{{__ ('user menu other') }}</span>
                </li>
            </router-link>
            <router-link :to="'../article/security'" exact>
                <li class="list-group-item text-muted ">
                    <span class="admin_menu">{{__ ('user menu security') }}</span>
                </li>
            </router-link>
            <router-link :to="'../article/partners'" exact> 
                <li class="list-group-item text-muted ">
                    <span class="admin_menu">{{__ ('user menu partners') }}</span>
                </li>
            </router-link>
            <router-link :to="'../article/events'" exact> 
                <li class="list-group-item text-muted ">
                    <span class="admin_menu">{{__ ('user menu events') }}</span>
                </li>
            </router-link>
            <router-link :to="{name: 'gallery'}" exact>
                <li class="list-group-item text-muted ">
                    <span class="admin_menu">{{__ ('user menu gallery') }}</span>
                </li>
            </router-link>
            <router-link :to="{name: 'siteInfo'}" exact> 
                <li class="list-group-item text-muted ">
                    <span class="admin_menu">{{__ ('user menu about') }}</span>
                </li>
            </router-link>
            

            <li class="list-group-item text-muted admin_menu_static_block"></li>

            <router-link :to="{name: 'comentsList'}" exact>
                <li class="list-group-item text-muted ">
                    <span class="admin_menu">{{__ ('user menu comments') }}</span>
                </li>
            </router-link>
            <router-link :to="{name: 'usersList'}" exact> 
                <li class="list-group-item text-muted">
                    <span class="admin_menu">{{__ ('user menu users') }}</span>
                </li>
            </router-link>

            <li class="list-group-item text-muted admin_menu_static_block"></li>

            <router-link :to="{name: 'options'}" exact>
                <li class="list-group-item text-muted ">
                    <span class="admin_menu">{{__ ('user menu options') }}</span>
                </li>
            </router-link>

            <li class="list-group-item text-muted">
                <a href=" route('logout') }}" onclick="event.preventDefault(); document.getElementById('frm-logout').submit();">
                    Logout
                </a>    
                <form id="frm-logout" action=" route('logout') }}" method="POST" style="display: none;">
                    csrf_field() }}
                </form>
            </li>
        </div>
    </ul>
</template>

<script>
     export default {
        data(){
            return {
                width: 0,
                menu: true,
                menu_but: false,
            }
        },
        mounted() {
            this.window_size()
        },
        unmounted() {
            this.window_size()
        },
        watch: {
            '$route' (to, from) {
                this.window_size()
            }
        },
        methods: {
            window_size: function(){
                this.width = innerWidth;
                if (this.width > 575) {
                    this.menu = true
                    this.menu_but = true
                }
                else{
                    this.menu = false
                    this.menu_but = false
                }
            },
            open_menu: function(){
                if (this.menu) {
                    this.menu = false
                }
                else{
                    this.menu = true
                }
            }
        }
    }
</script>

<style>
    .router-link-exact-active li{
        background-color: #ccc !important;
    }
    .router-link-exact-active span {
        color: #6c757d !important;
        text-shadow: black 1px 1px 0;
    }
    .admin_menu_static_block{
        background-color: rgb(240, 240, 240) !important;
    }
    .admin_menu_open{
        background-color: #7427bb61 !important;
    }
    .user_page_menu{
        margin-bottom: 5.5%;
    }
</style>