<template>
    <header class="cg-nav blog-navbar" :class="{ 'is-open': is_open }">
        <div class="cg-nav__inner">

            <cg-brand service="blog" to="/" />

            <button type="button" class="cg-nav__toggle" :aria-expanded="is_open" :aria-label="$t('global.menu_toggle')" @click="open_navbar()">
                <span></span><span></span><span></span>
            </button>

            <nav class="cg-nav__menu" id="alignment-example">
                <ul class="cg-nav__links">
                    <li><router-link :to="{name: 'about_us', params: {locale: this.$i18n.locale}}" exact><span>{{ $t('blog.menu.about_us') }}</span></router-link></li>
                </ul>

                <ul class="cg-nav__tools">
                    <navbar-search site="blog" search_route="search_posts" />
                    <localeSwitcher />
                    <services-switcher current="blog" />
                    <nav-badges />
                </ul>
            </nav>

        </div>
    </header>
</template>

<script>
    // import search from './SearchComponent'
    import localeSwitcher from '../../global_components/LocaleChangeComponent.vue'
    import go_to_service_mixin from '../../../mixins/go_to_service_mixin.js'

    export default {
        mixins: [
            go_to_service_mixin
        ],
        data: function () {
            return {
                MIX_SITE_URL: process.env.MIX_SITE_URL,
                MIX_APP_SSH: process.env.MIX_APP_SSH,

                is_open: false,
                
                get activ_lang() {
                    return localStorage.getItem('lang') || 'en';
                },
                set activ_lang(value) {
                    localStorage.setItem('lang', value);
                },
            };
        },
        components: {
            localeSwitcher,
        },
        mounted() {
        },
        watch: {
            '$route' (to, from) {
                this.is_open = false
            }
        },
        methods: {
            open_navbar(){
                this.is_open = !this.is_open
            }
        }
    }
</script>
