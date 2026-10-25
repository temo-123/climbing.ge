<template>
    <header class="cg-nav summit-navbar" :class="{ 'is-open': is_open }">
        <div class="cg-nav__inner">

            <cg-brand service="summit" to="/" />

            <button type="button" class="cg-nav__toggle" :aria-expanded="is_open" :aria-label="$t('global.menu_toggle')" @click="open_navbar()">
                <span></span><span></span><span></span>
            </button>

            <nav class="cg-nav__menu" id="alignment-example">
                <ul class="cg-nav__links">
                    <li><router-link :to="'/summits/list'"><i class="fa fa-list-ul" aria-hidden="true"></i> <span>{{ $t('summit.menu.list') }}</span></router-link></li>
                    <li><router-link :to="'/summits/map'"><i class="fa fa-map-marker" aria-hidden="true"></i> <span>{{ $t('summit.menu.map') }}</span></router-link></li>
                    <li><router-link :to="'/about_us'"><span>{{ $t('summit.menu.about') }}</span></router-link></li>
                </ul>

                <ul class="cg-nav__tools">
                    <navbar-search site="summit" search_route="search_summits" />
                    <motion-toggle />
                    <locale-switcher />
                    <services-switcher current="summit" />
                    <nav-badges />
                </ul>
            </nav>

        </div>
    </header>
</template>

<script>
import { RouterLink } from 'vue-router'
    import localeSwitcher from '../../global_components/LocaleChangeComponent.vue'
    import go_to_service_mixin from '../../../mixins/go_to_service_mixin.js'

    export default {
        components: {
            'router-link': RouterLink,
            localeSwitcher,
        },
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
        // Duplicate components removed
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
