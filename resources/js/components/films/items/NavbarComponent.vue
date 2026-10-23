<template>
    <header class="cg-nav films-navbar" :class="{ 'is-open': is_open }">
        <div class="cg-nav__inner">

            <cg-brand service="films" :to="{ name: 'studia' }" />

            <button type="button" class="cg-nav__toggle" :aria-expanded="is_open" :aria-label="$t('global.menu_toggle')" @click="open_navbar()">
                <span></span><span></span><span></span>
            </button>

            <nav class="cg-nav__menu">
                <ul class="cg-nav__links">
                    <li><router-link :to="{name: 'studia'}" exact><i class="fa fa-film" aria-hidden="true"></i> <span>{{ $t('films.menu.all_films') }}</span></router-link></li>
                    <li><router-link :to="{name: 'about_films'}" exact><span>{{ $t('films.menu.about_us') }}</span></router-link></li>
                </ul>

                <ul class="cg-nav__tools">
                    <li class="dropdown">
                        <a class="cg-icon-btn" role="button" data-bs-toggle="dropdown" data-bs-auto-close="outside" aria-expanded="false" :title="$t('global.search')">
                            <i class="fa fa-search" aria-hidden="true"></i>
                        </a>
                        <div class="dropdown-menu dropdown-menu-end cg-nav__search-menu">
                            <form role="search" @submit.prevent="search">
                                <div class="input-group">
                                    <input class="form-control" name="request" v-model="search_query" :placeholder="$t('films.search_films_title')" autocomplete="off" type="text">
                                    <button type="submit" class="btn btn-primary"><i class="fa fa-search" aria-hidden="true"></i></button>
                                </div>
                            </form>
                        </div>
                    </li>
                    <localeSwitcher />
                    <services-switcher current="films" />
                    <nav-badges />
                </ul>
            </nav>

        </div>
    </header>
</template>

<script>
    // import countryFlagWrapper from '../../global_components/CountryFlagWrapper.vue'
    // import search from './SearchComponent'
    import localeSwitcher from '../../global_components/LocaleChangeComponent.vue'

    export default {
        data: function () {
            return {
                search_query: '',
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
            // countryFlagWrapper,
        },
        mounted() {
        },
        watch: {
            '$route' (to, from) {
                this.is_open = false
            }
        },
        methods: {
            search(){
                this.$router.push({name: 'search_films', query: { 'query': this.search_query} });
                this.search_query = ''
            },
            // localization(leng){
            //     window.location.href = leng;
            // },
            goTo(page = ''){
                window.open(this.MIX_APP_SSH + 'user.' + this.MIX_SITE_URL + page) ;
            },
            
            open_navbar(){
                this.is_open = !this.is_open
            }
        }
    }
</script>