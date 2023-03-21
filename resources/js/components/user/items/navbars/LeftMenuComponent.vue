<template>
    <ul class="list-group user_page_menu">
         <li v-if="menu_but" class="list-group-item text-muted admin_menu_static_block">Munu</li>
        <li v-if="!menu_but" class="list-group-item text-muted admin_menu_static_block admin_menu_open" @click="open_menu">Open munu</li>

        <div v-if="menu">
            <router-link :to="{name: 'home'}" exact>
                <li class="list-group-item text-muted">
                    <span class="admin_menu">{{ $t('user.menu.home') }}</span>
                </li>
            </router-link>
            <router-link :to="{name: 'adminPage'}" exact 
                v-if="
                    $can('show', 'dashboard')
                "     
            >
                <li class="list-group-item text-muted">
                    <span class="admin_menu">{{ $t('user.menu.admin dashboard') }}</span>
                </li>
            </router-link>

            <li class="list-group-item text-muted admin_menu_static_block"></li>

            <router-link :to="{name: 'cart'}" exact> 
                <li class="list-group-item text-muted ">
                    <span class="admin_menu">{{ $t('user.menu.cart') }}</span>
                </li>
            </router-link>
            <router-link :to="{name: 'myOrders'}" exact> 
                <li class="list-group-item text-muted ">
                    <span class="admin_menu">{{ $t('user.menu.my orders') }}</span>
                </li>
            </router-link>

            <li class="list-group-item text-muted admin_menu_static_block"></li>

            <router-link :to="{name: 'myComentsList'}" exact> 
                <li class="list-group-item text-muted ">
                    <span class="admin_menu">{{ $t('user.menu.my comments') }}</span>
                </li>
            </router-link>
            <!-- <router-link :to="{name: 'myPosts'}" exact> 
                <li class="list-group-item text-muted ">
                    <span class="admin_menu">{{ $t('user.menu.my posts') }}</span>
                </li>
            </router-link> -->

            <li class="list-group-item text-muted admin_menu_static_block"></li>

            <router-link :to="{name: 'favoriteProducts'}" exact> 
                <li class="list-group-item text-muted">
                    <span class="admin_menu">{{ $t('user.menu.favorite products') }}</span>
                </li>
            </router-link>
            <!-- <router-link :to="{name: 'favoriteFilmsPage'}" exact> 
                <li class="list-group-item text-muted">
                    <span class="admin_menu">{{ $t('user.menu.favorite films') }}</span>
                </li>
            </router-link> -->

            <router-link :to="{name: 'favoriteOutdoorArea'}" exact> 
                <li class="list-group-item text-muted ">
                    <span class="admin_menu">{{ $t('user.menu.my favorite outdoor area') }}</span>
                </li>
            </router-link>

            <router-link :to="{name: 'interestedEvents'}" exact> 
                <li class="list-group-item text-muted ">
                    <span class="admin_menu">{{ $t('user.menu.my interested events') }}</span>
                </li>
            </router-link>

            <!-- <li class="list-group-item text-muted admin_menu_static_block"></li>

            <router-link :to="{name: 'followersPage'}" exact> 
                <li class="list-group-item text-muted ">
                    <span class="admin_menu">{{ $t('user.menu.followers') }}</span>
                </li>
            </router-link> -->

            <li class="list-group-item text-muted admin_menu_static_block"
                v-if="
                    $can('add', 'product') ||
                    $can('edit', 'product') ||
                    $can('del', 'product') ||
                    $can('add', 'product_category') ||
                    $can('edit', 'product_category') ||
                    $can('del', 'product_category') ||
                    $can('add', 'product_option') ||
                    $can('edit', 'product_option') ||
                    $can('del', 'sale_code') ||
                    $can('add', 'sale_code') ||
                    $can('edit', 'sale_code') ||
                    $can('del', 'sale_code')
                "
            > </li>

            <router-link :to="{name: 'productsList'}" exact 
                v-if="
                    $can('add', 'product') ||
                    $can('edit', 'product') ||
                    $can('del', 'product') ||
                    $can('add', 'product_category') ||
                    $can('edit', 'product_category') ||
                    $can('del', 'product_category') ||
                    $can('add', 'product_option') ||
                    $can('edit', 'product_option') ||
                    $can('del', 'sale_code') ||
                    $can('add', 'sale_code') ||
                    $can('edit', 'sale_code') ||
                    $can('del', 'sale_code')
                "
            > 
                <li class="list-group-item text-muted">
                    <span class="admin_menu">{{ $t('user.menu.products & category') }}</span>
                </li>
            </router-link>
            <router-link :to="{name: 'servicesList'}" exact
                v-if="
                    $can('add', 'services') ||
                    $can('edit', 'services') ||
                    $can('del', 'services')
                "        
            > 
                <li class="list-group-item text-muted ">
                    <span class="admin_menu">{{ $t('user.menu.services') }}</span>
                </li>
            </router-link>

            <li class="list-group-item text-muted admin_menu_static_block"
                v-if="
                    $can('add', 'film') ||
                    $can('edit', 'film') ||
                    $can('del', 'film') ||
                    $can('add', 'film_teg') ||
                    $can('edit', 'film_teg') ||
                    $can('del', 'film_teg') ||
                    $can('add', 'film_category') ||
                    $can('edit', 'film_category') ||
                    $can('del', 'film_category')
                "     
            > </li>

            <router-link :to="{name: 'filmsList'}"
                v-if="
                    $can('add', 'film') ||
                    $can('edit', 'film') ||
                    $can('del', 'film') ||
                    $can('add', 'film_teg') ||
                    $can('edit', 'film_teg') ||
                    $can('del', 'film_teg') ||
                    $can('add', 'film_category') ||
                    $can('edit', 'film_category') ||
                    $can('del', 'film_category')
                "        
            > 
                <li class="list-group-item text-muted m">
                    <span class="admin_menu">{{ $t('user.menu.films') }}(Beta)</span>
                </li>
            </router-link>

            <li class="list-group-item text-muted admin_menu_static_block"
                v-if="
                    $can('add', 'local_bisnes') ||
                    $can('edit', 'local_bisnes') ||
                    $can('del', 'local_bisnes')
                "     
            > </li>

            <router-link :to="{name: 'localBisnesList'}"
                v-if="
                    $can('add', 'local_bisnes') ||
                    $can('edit', 'local_bisnes') ||
                    $can('del', 'local_bisnes')
                "        
            > 
                <li class="list-group-item text-muted m">
                    <span class="admin_menu">{{ $t('user.menu.local bisnes') }}</span>
                </li>
            </router-link>

            <li class="list-group-item text-muted admin_menu_static_block"
                v-if="
                    $can('add', 'article') ||
                    $can('edit', 'article') ||
                    $can('del', 'article')
                "     
            > </li>

            <router-link :to="{ name: 'articlelist', params: { article_category: 'outdoor' } }"
                v-if="
                    $can('add', 'article') ||
                    $can('edit', 'article') ||
                    $can('del', 'article')
                "        
            > 
                <li class="list-group-item text-muted">
                    <span class="admin_menu">{{ $t('user.menu.outdoor climbing') }}</span>
                </li>
            </router-link>

            <li class="list-group-item text-muted admin_menu_static_block"
                v-if="
                    $can('add', 'route') ||
                    $can('edit', 'route') ||
                    $can('del', 'route') ||

                    $can('add', 'mtp') ||
                    $can('edit', 'mtp') ||
                    $can('del', 'mtp')||

                    $can('add', 'mtp_pitch') ||
                    $can('edit', 'mtp_pitch') ||
                    $can('del', 'mtp_pitch')||

                    $can('add', 'sector') ||
                    $can('edit', 'sector') ||
                    $can('del', 'sector')
                "     
            > </li>

            <router-link :to="{name: 'routeAndSectorList'}" exact
                v-if="
                    $can('add', 'route') ||
                    $can('edit', 'route') ||
                    $can('del', 'route') ||

                    $can('add', 'mtp') ||
                    $can('edit', 'mtp') ||
                    $can('del', 'mtp')||

                    $can('add', 'mtp_pitch') ||
                    $can('edit', 'mtp_pitch') ||
                    $can('del', 'mtp_pitch')||

                    $can('add', 'sector') ||
                    $can('edit', 'sector') ||
                    $can('del', 'sector')
                "        
            >
                <li class="list-group-item text-muted mbing Secrots And Route">
                    <span class="admin_menu">{{ $t('user.menu.sectors & routes') }}</span>
                </li>
            </router-link>

            <router-link :to="{name: 'sectorLocalImagesList'}" exact
                v-if="
                    $can('add', 'sector_local_images') ||
                    $can('edit', 'sector_local_images') ||
                    $can('del', 'sector_local_images')
                "        
            >
                <li class="list-group-item text-muted mbing Secrots And Route">
                    <span class="admin_menu">{{ $t('Sect.r lo.al images') }}</span>
                </li>
            </router-link>

            <li class="list-group-item text-muted admin_menu_static_block"
            v-if="
                    $can('add', 'mount_route') ||
                    $can('edit', 'mount_route') ||
                    $can('del', 'mount_route') ||
                    $can('add', 'mount_massive') ||
                    $can('edit', 'mount_massive') ||
                    $can('del', 'mount_massive')
                "        
            > </li>

            <router-link :to="{ name: 'mountlist' }"
                v-if="
                    $can('add', 'mount_route') ||
                    $can('edit', 'mount_route') ||
                    $can('del', 'mount_route') ||
                    $can('add', 'mount_massive') ||
                    $can('edit', 'mount_massive') ||
                    $can('del', 'mount_massive')
                "        
            > 
                <li class="list-group-item text-muted">
                    <span class="admin_menu">{{ $t('user.menu.mountaineering') }}</span>
                </li>
            </router-link>

            <li class="list-group-item text-muted admin_menu_static_block"
                v-if="
                    $can('add', 'article') ||
                    $can('edit', 'article') ||
                    $can('del', 'article')
                "     
            > </li>

            <router-link :to="{ name: 'articlelist', params: { article_category: 'indoor' } }"
                v-if="
                    $can('add', 'article') ||
                    $can('edit', 'article') ||
                    $can('del', 'article')
                "        
            > 
                <li class="list-group-item text-muted">
                    <span class="admin_menu">{{ $t('user.menu.indoor climbing') }}</span>
                </li>
            </router-link>
            <router-link :to="{ name: 'articlelist', params: { article_category: 'ice' } }"
                v-if="
                    $can('add', 'article') ||
                    $can('edit', 'article') ||
                    $can('del', 'article')
                "        
            > 
                <li class="list-group-item text-muted">
                    <span class="admin_menu">{{ $t('user.menu.ice climbing') }}</span>
                </li>
            </router-link>
            <router-link :to="{ name: 'articlelist', params: { article_category: 'news' } }"
                v-if="
                    $can('add', 'article') ||
                    $can('edit', 'article') ||
                    $can('del', 'article')
                "        
            > 
                <li class="list-group-item text-muted ">
                    <span class="admin_menu">{{ $t('user.menu.news') }}</span>
                </li>
            </router-link>
            <router-link :to="{ name: 'articlelist', params: { article_category: 'other' } }"
                v-if="
                    $can('add', 'article') ||
                    $can('edit', 'article') ||
                    $can('del', 'article')
                "        
            > 
                <li class="list-group-item text-muted" >
                    <span class="admin_menu">{{ $t('user.menu.other') }}</span>
                </li>
            </router-link>
            <router-link :to="{ name: 'articlelist', params: { article_category: 'tech_tip' } }"
                v-if="
                    $can('add', 'article') ||
                    $can('edit', 'article') ||
                    $can('del', 'article')
                "        
            > 
                <li class="list-group-item text-muted ">
                    <span class="admin_menu">{{ $t('user.menu.security') }}</span>
                </li>
            </router-link>
            <router-link :to="{ name: 'articlelist', params: { article_category: 'partners' } }"
                v-if="
                    $can('add', 'article') ||
                    $can('edit', 'article') ||
                    $can('del', 'article')
                "        
            > 
                <li class="list-group-item text-muted ">
                    <span class="admin_menu">{{ $t('user.menu.partners') }}</span>
                </li>
            </router-link>

            <li class="list-group-item text-muted admin_menu_static_block"
                v-if="
                    $can('add', 'event') ||
                    $can('edit', 'event') ||
                    $can('del', 'event')
                "        
            > </li>

            <router-link :to="{ name: 'eventList' }"
                v-if="
                    $can('add', 'event') ||
                    $can('edit', 'event') ||
                    $can('del', 'event')
                "        
            > 
                <li class="list-group-item text-muted ">
                    <span class="admin_menu">{{ $t('user.menu.events') }}</span>
                </li>
            </router-link>

            <li class="list-group-item text-muted admin_menu_static_block"
                v-if="
                    $can('edit', 'site_data')
                "        
            > </li>
            
            <router-link :to="{name: 'siteInfo'}" exact
                v-if="
                    $can('edit', 'site_data')
                "        
            > 
                <li class="list-group-item text-muted ">
                    <span class="admin_menu">{{ $t('user.menu.about') }}</span>
                </li>
            </router-link>

            <li class="list-group-item text-muted admin_menu_static_block" 
                v-if="
                    $can('add', 'product') ||
                    $can('edit', 'product') ||
                    $can('del', 'product')
                "        
            > </li>

            <router-link :to="{name: 'gallery'}" exact
                v-if="
                    $can('add', 'product') ||
                    $can('edit', 'product') ||
                    $can('del', 'product')
                "        
            > 
                <li class="list-group-item text-muted ">
                    <span class="admin_menu">{{ $t('user.menu.gallery') }}</span>
                </li>
            </router-link>

            <li class="list-group-item text-muted admin_menu_static_block"
                v-if="
                    $can('show', 'comments') ||
                    $can('del_comment', 'comments') 
                "        
            > </li>

            <router-link :to="{name: 'comentsList'}" exact
                v-if="
                    $can('show', 'comments') ||
                    $can('del_comment', 'comments')
                "        
            > 
                <li class="list-group-item text-muted ">
                    <span class="admin_menu">{{ $t('user.menu.all comments') }}</span>
                </li>
            </router-link>

            <router-link :to="{name: 'allOrders'}" exact
                v-if="
                    $can('edit_order_status', 'order')
                "        
            > 
                <li class="list-group-item text-muted">
                    <span class="admin_menu">{{ $t('user.menu.total orders') }}</span>
                </li>
            </router-link>

            <!-- <router-link :to="{name: 'allPosts'}" exact> 
                <li class="list-group-item text-muted">
                    <span class="admin_menu">{{ $t('user.menu.all posts') }}</span>
                </li>
            </router-link> -->

            <li class="list-group-item text-muted admin_menu_static_block"></li>

            <router-link :to="{name: 'usersList'}" exact 
                v-if="
                    $can('add', 'user') ||
                    $can('del', 'user') ||
                    $can('edit_permissions', 'user') ||
                    $can('create_ban', 'user')
                "        
            > 
                <li class="list-group-item text-muted">
                    <span class="admin_menu">{{ $t('user.menu.users') }}</span>
                </li>
            </router-link>

            <router-link :to="{name: 'options'}" exact>
                <li class="list-group-item text-muted ">
                    <span class="admin_menu">{{ $t('user.menu.options') }}</span>
                </li>
            </router-link>

            <li class="list-group-item text-muted admin_menu_static_block"></li>

            <router-link :to="{name: 'multimedia'}" exact 
                v-if="
                    1 == 1
                "        
            > 
                <li class="list-group-item text-muted">
                    <span class="admin_menu">Media (Beta)</span>
                </li>
            </router-link>

            <li class="list-group-item text-muted admin_menu_static_block"></li>

            <li class="list-group-item text-muted" @click="logout()" :style="'cursor: pointer'">
                <span class="admin_menu">{{ $t('user.menu.logout') }}</span>
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


                menu_array: {
                    
                }
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
                .post(process.env.MIX_APP_SSH + process.env.MIX_USER_PAGE_URL + '/logout')
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