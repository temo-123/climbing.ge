<template>
    <header class="cg-nav" :class="{ 'is-open': is_open }">
        <div class="cg-nav__inner">

            <cg-brand service="guide" :to="{ name: 'index', params: { locale: locale_param } }" />

            <button type="button" class="cg-nav__toggle" :aria-expanded="is_open" :aria-label="$t('global.menu_toggle')" @click="open_navbar()">
                <span></span><span></span><span></span>
            </button>

            <nav class="cg-nav__menu" id="alignment-example">
                <ul class="cg-nav__links">
                    <li class="dropdown" :class="{ active: is_climbing_active }">
                        <a class="dropdown-toggle" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <span>{{ $t('guide.menu.climbing') }}</span>
                        </a>
                        <ul class="dropdown-menu" role="menu">
                            <li><router-link :to="{name: 'indoors', params: {locale: locale_param}}" exact><i class="fa fa-building-o fa-fw" aria-hidden="true"></i> <span>{{ $t('guide.menu.indoor climbing') }}</span></router-link></li>
                            <li><router-link :to="{name: 'outdoors', params: {locale: locale_param}}" exact><i class="fa fa-tree fa-fw" aria-hidden="true"></i> <span>{{ $t('guide.menu.outdoor climbing') }}</span></router-link></li>
                            <li><router-link :to="{name: 'spot_projects', params: {locale: locale_param}}" exact><i class="fa fa-bullseye fa-fw" aria-hidden="true"></i> <span>{{ $t('guide.menu.spots projects') }}</span></router-link></li>
                        </ul>
                    </li>

                    <li><router-link :to="{name: 'mountaineerings', params: {locale: locale_param}}" exact><span>{{ $t('guide.menu.mountaineering') }}</span></router-link></li>
                    <li><router-link :to="{name: 'ices', params: {locale: locale_param}}" exact><span>{{ $t('guide.menu.ice climbing') }}</span></router-link></li>
                    <li><router-link :to="{name: 'events', params: {locale: locale_param}}" exact><span>{{ $t('guide.menu.events') }}</span></router-link></li>
                    <li><router-link :to="{name: 'others', params: {locale: locale_param}}" exact><span>{{ $t('guide.menu.other') }}</span></router-link></li>
                    <li><router-link :to="{name: 'about_us', params: {locale: locale_param}}" exact><span>{{ $t('guide.menu.about us') }}</span></router-link></li>
                </ul>

                <ul class="cg-nav__tools">
                    <navbar-search site="guide" search_route="search_articles" />

                    <li class="dropdown">
                        <a class="cg-icon-btn" role="button" data-bs-toggle="dropdown" aria-expanded="false" :title="$t('global.grade_system')">
                            <i class="fa fa-signal" aria-hidden="true"></i>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end" role="menu">
                            <li><div class="cg-dropdown-label">{{ $t('global.grade_system') }}</div></li>
                            <li>
                                <a class="grade_chart" :class="{ active: is_yds }" @click="activ_grade = 'yds', grade_charts('yds')">
                                    <span class="cg-grade">5.10a</span> YDS
                                </a>
                            </li>
                            <li>
                                <a class="grade_chart" :class="{ active: !is_yds }" @click="activ_grade = 'UIAA', grade_charts('UIAA')">
                                    <span class="cg-grade">VII-</span> UIAA
                                </a>
                            </li>
                        </ul>
                    </li>

                    <localeSwitcher />
                    <services-switcher current="guide" />
                    <nav-badges />
                </ul>
            </nav>

        </div>
    </header>
</template>

<script>
    import localeSwitcher from '../../../global_components/LocaleChangeComponent.vue'
    import go_to_service_mixin from '../../../../mixins/go_to_service_mixin.js'

    export default {
        mixins: [
            go_to_service_mixin
        ],
        data: function () {
            return {
                is_open: false,

                MIX_SITE_URL: process.env.MIX_SITE_URL,
                MIX_APP_SSH: process.env.MIX_APP_SSH,

                get activ_grade() {
                    return localStorage.getItem('grade') || 'yds';
                },
                set activ_grade(value) {
                    localStorage.setItem('grade', value);
                },

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
        computed: {
            locale_param() {
                return this.$i18n.locale === 'ka' ? 'ka' : undefined;
            },
            is_yds() {
                return (this.activ_grade || 'yds').toLowerCase() === 'yds';
            },
            is_climbing_active() {
                return ['indoors', 'outdoors', 'spot_projects'].includes(this.$route.name);
            },
        },
        mounted() {
            if(!localStorage.getItem('grade')){
                this.grade_charts("YDS")
            }
        },
        watch: {
            '$route' (to, from) {
                this.is_open = false
            }
        },

        methods: {
            grade_charts(grade){
                localStorage.setItem('grade', grade)

                this.return_data(grade)
                
                location.reload();
            },
            return_data(g){
                this.$emit('grade', g)
            },
            goTo(page = ''){
                window.open(this.MIX_APP_SSH + 'user.' + this.MIX_SITE_URL + page) ;
            },
            open_navbar(){
                this.is_open = !this.is_open
            }
        }
    }
</script>
