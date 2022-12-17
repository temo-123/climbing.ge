<template>
    <ul class="list-group user_page_menu">
         <li v-if="menu_but" class="list-group-item text-muted admin_menu_static_block">Munu</li>
        <li v-if="!menu_but" class="list-group-item text-muted admin_menu_static_block admin_menu_open" @click="open_menu">Open munu</li>

        <div v-if="menu">
            <router-link :to="{name: 'home'}" exact>
                <li class="list-group-item text-muted">
                    <span class="admin_menu">{{ $t('user menu home') }}</span>
                </li>
            </router-link>
            <router-link :to="{name: 'adminPage'}" exact>
                <li class="list-group-item text-muted">
                    <span class="admin_menu">{{ $t('user menu admin page') }}</span>
                </li>
            </router-link>

            <li class="list-group-item text-muted admin_menu_static_block"></li>

            <router-link :to="{name: 'cart'}" exact> 
                <li class="list-group-item text-muted ">
                    <span class="admin_menu">{{ $t('user menu cart') }}</span>
                </li>
            </router-link>
            <router-link :to="{name: 'myOrders'}" exact> 
                <li class="list-group-item text-muted ">
                    <span class="admin_menu">{{ $t('user menu my orders') }}</span>
                </li>
            </router-link>

            <li class="list-group-item text-muted admin_menu_static_block"></li>

            <router-link :to="{name: 'myComentsList'}" exact> 
                <li class="list-group-item text-muted ">
                    <span class="admin_menu">{{ $t('user menu my comments') }}</span>
                </li>
            </router-link>
            <!-- <router-link :to="{name: 'myPosts'}" exact> 
                <li class="list-group-item text-muted ">
                    <span class="admin_menu">{{ $t('user menu my posts') }}</span>
                </li>
            </router-link> -->

            <li class="list-group-item text-muted admin_menu_static_block"></li>

            <router-link :to="{name: 'favoriteProducts'}" exact> 
                <li class="list-group-item text-muted">
                    <span class="admin_menu">{{ $t('user menu favorite products') }}</span>
                </li>
            </router-link>
            <router-link :to="{name: 'favoriteFilmsPage'}" exact> 
                <li class="list-group-item text-muted">
                    <span class="admin_menu">{{ $t('user menu favorite films') }}</span>
                </li>
            </router-link>

            <router-link :to="{name: 'favoriteOutdoorArea'}" exact> 
                <li class="list-group-item text-muted ">
                    <span class="admin_menu">{{ $t('user menu my favorite outdoor area') }}</span>
                </li>
            </router-link>

            <router-link :to="{name: 'interestedEvents'}" exact> 
                <li class="list-group-item text-muted ">
                    <span class="admin_menu">{{ $t('user menu my interested events') }}</span>
                </li>
            </router-link>

            <!-- <li class="list-group-item text-muted admin_menu_static_block"></li>

            <router-link :to="{name: 'followersPage'}" exact> 
                <li class="list-group-item text-muted ">
                    <span class="admin_menu">{{ $t('user menu followers') }}</span>
                </li>
            </router-link> -->

            <li class="list-group-item text-muted admin_menu_static_block"></li>

            <router-link :to="{name: 'productsList'}" exact> 
                <li class="list-group-item text-muted">
                    <span class="admin_menu">{{ $t('user menu products & category') }}</span>
                </li>
            </router-link>
            <router-link :to="{name: 'servicesList'}" exact> 
                <li class="list-group-item text-muted ">
                    <span class="admin_menu">{{ $t('user menu services') }}</span>
                </li>
            </router-link>

            <li class="list-group-item text-muted admin_menu_static_block"></li>

            <router-link :to="{name: 'filmsList'}">
                <li class="list-group-item text-muted m">
                    <span class="admin_menu">{{ $t('user menu films') }}(Beta)</span>
                </li>
            </router-link>

            <li class="list-group-item text-muted admin_menu_static_block"></li>

            <router-link :to="{name: 'localBisnesList'}">
                <li class="list-group-item text-muted m">
                    <span class="admin_menu">{{ $t('user menu local bisnes') }}</span>
                </li>
            </router-link>

            <li class="list-group-item text-muted admin_menu_static_block"></li>

            <router-link :to="{ name: 'articlelist', params: { article_category: 'outdoor' } }">
                <li class="list-group-item text-muted">
                    <span class="admin_menu">{{ $t('user menu outdoor climbing') }}(Beta)</span>
                </li>
            </router-link>
            <router-link :to="{name: 'routeAndSectorList'}" exact>
                <li class="list-group-item text-muted mbing Secrots And Route">
                    <span class="admin_menu">{{ $t('user menu sectors & routes') }}</span>
                </li>
            </router-link>

            <router-link :to="{name: 'sectorLocalImagesList'}" exact>
                <li class="list-group-item text-muted mbing Secrots And Route">
                    <span class="admin_menu">{{ $t('Sector local images') }}</span>
                </li>
            </router-link>

            <li class="list-group-item text-muted admin_menu_static_block"></li>

            <router-link :to="{ name: 'mountlist' }">
                <li class="list-group-item text-muted">
                    <span class="admin_menu">{{ $t('user menu mountaineering') }}</span>
                </li>
            </router-link>

            <li class="list-group-item text-muted admin_menu_static_block"></li>

            <router-link :to="{ name: 'articlelist', params: { article_category: 'indoor' } }">
                <li class="list-group-item text-muted">
                    <span class="admin_menu">{{ $t('user menu indoor climbing') }}</span>
                </li>
            </router-link>
            <router-link :to="{ name: 'articlelist', params: { article_category: 'ice' } }">
                <li class="list-group-item text-muted">
                    <span class="admin_menu">{{ $t('user menu ice climbing') }}</span>
                </li>
            </router-link>
            <router-link :to="{ name: 'articlelist', params: { article_category: 'news' } }">
                <li class="list-group-item text-muted ">
                    <span class="admin_menu">{{ $t('user menu news') }}</span>
                </li>
            </router-link>
            <router-link :to="{ name: 'articlelist', params: { article_category: 'other' } }">
                <li class="list-group-item text-muted" >
                    <span class="admin_menu">{{ $t('user menu other') }}</span>
                </li>
            </router-link>
            <router-link :to="{ name: 'articlelist', params: { article_category: 'tech_tip' } }">
                <li class="list-group-item text-muted ">
                    <span class="admin_menu">{{ $t('user menu security') }}</span>
                </li>
            </router-link>
            <router-link :to="{ name: 'articlelist', params: { article_category: 'partners' } }">
                <li class="list-group-item text-muted ">
                    <span class="admin_menu">{{ $t('user menu partners') }}</span>
                </li>
            </router-link>

            <li class="list-group-item text-muted admin_menu_static_block"></li>

            <router-link :to="{ name: 'eventList' }">
                <li class="list-group-item text-muted ">
                    <span class="admin_menu">{{ $t('user menu events') }}</span>
                </li>
            </router-link>

            <li class="list-group-item text-muted admin_menu_static_block"></li>
            
            <router-link :to="{name: 'siteInfo'}" exact> 
                <li class="list-group-item text-muted ">
                    <span class="admin_menu">{{ $t('user menu about') }} (Beta)</span>
                </li>
            </router-link>

            <li class="list-group-item text-muted admin_menu_static_block"></li>

            <router-link :to="{name: 'gallery'}" exact>
                <li class="list-group-item text-muted ">
                    <span class="admin_menu">{{ $t('user menu gallery') }}</span>
                </li>
            </router-link>

            <li class="list-group-item text-muted admin_menu_static_block"></li>

            <router-link :to="{name: 'comentsList'}" exact>
                <li class="list-group-item text-muted ">
                    <span class="admin_menu">{{ $t('user menu all comments') }}</span>
                </li>
            </router-link>
            <router-link :to="{name: 'allOrders'}" exact> 
                <li class="list-group-item text-muted">
                    <span class="admin_menu">{{ $t('user menu total orders') }}</span>
                </li>
            </router-link>
            <!-- <router-link :to="{name: 'allPosts'}" exact> 
                <li class="list-group-item text-muted">
                    <span class="admin_menu">{{ $t('user menu all posts') }}</span>
                </li>
            </router-link> -->

            <li class="list-group-item text-muted admin_menu_static_block"></li>

            <router-link :to="{name: 'usersList'}" exact> 
                <li class="list-group-item text-muted">
                    <span class="admin_menu">{{ $t('user menu users') }} (Beta)</span>
                </li>
            </router-link>

            <router-link :to="{name: 'options'}" exact>
                <li class="list-group-item text-muted ">
                    <span class="admin_menu">{{ $t('user menu options') }}</span>
                </li>
            </router-link>

            <li class="list-group-item text-muted admin_menu_static_block"></li>

            <li class="list-group-item text-muted" @click="logout()" :style="'cursor: pointer'">
                <span class="admin_menu">{{ $t('user menu logout') }}</span>
            </li>
        </div>
    </ul>
</template>

<script>
     export default {
	    name: 'leftMenu',
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
            logout(){
                axios
                .post('/logout')
                .then(()=>{
                    // window.location.href = "http://climibng.loc";
                    localStorage.removeItem('x_xsrf_token');
                    this.$router.push({ name: "login" });
                })
                
            },
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