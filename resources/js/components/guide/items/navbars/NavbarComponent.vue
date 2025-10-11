<template>
    <nav class="navbar navbar-inverse navbar-fixed-top bg-dark">
        <div class="container-fluid">

            <!-- BRAND -->
            <div class="navbar-header">

                <span>
                    <router-link style="font-size: 1.5em;" :to="{name: 'index'}" exact class="site_title">
                        {{ $t('guide.name') }}
                    </router-link>
                </span>

                <!-- <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#alignment-example" aria-expanded="false" > -->
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" aria-expanded="false" @click="open_navbar()">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <!-- <a class="navbar-brand" href="#">Your Brand</a> -->
            </div>

            <!-- COLLAPSIBLE NAVBAR --> 
            <!-- <div class="collapse navbar-collapse" id="alignment-example"> -->
            <div :class='[navbar_class]' id="alignment-example">

                <!-- Links -->
                <ul class="nav navbar-nav navbar-right">
                    <li class="dropdown">
                        <a data-toggle="dropdown" class="cursor_pointer"> 
                            <span>{{ $t('guide.menu.climbing') }}</span>
                        </a>

                        <ul class="dropdown-menu dropdown_menu_right_unset" role="menu" style="background-color: #278da5;">
                            <li><router-link style="font-size: 1.5em;" :to="{name: 'indoors', params: {lang: this.$i18n.locale}}" exact>  <span> {{ $t('guide.menu.indoor climbing') }}  </span> </router-link></li>
                            <li><router-link style="font-size: 1.5em;" :to="{name: 'outdoors', params: {lang: this.$i18n.locale}}" exact> <span> {{ $t('guide.menu.outdoor climbing') }} </span> </router-link></li>
                            <li><router-link style="font-size: 1.5em;" :to="{name: 'spot_projects', params: {lang: this.$i18n.locale}}" exact> <span> {{ $t('guide.menu.spots projects') }} </span> </router-link></li>
                        </ul>
                    </li>

                    <li><router-link :to="{name: 'mountaineerings', params: {lang: this.$i18n.locale}}" exact> <span> {{ $t('guide.menu.mountaineering') }}      </span> </router-link></li>
                    <li><router-link :to="{name: 'ices', params: {lang: this.$i18n.locale}}" exact>            <span> {{ $t('guide.menu.ice climbing') }}        </span> </router-link></li>
                    <li><router-link :to="{name: 'events', params: {lang: this.$i18n.locale}}" exact>          <span> {{ $t('guide.menu.events') }}              </span> </router-link></li>
                    <li><router-link :to="{name: 'others', params: {lang: this.$i18n.locale}}" exact>          <span> {{ $t('guide.menu.other') }}               </span> </router-link></li>
                    <li><router-link :to="{name: 'about_us', params: {lang: this.$i18n.locale}}" exact>        <span> {{ $t('guide.menu.about us') }}            </span> </router-link></li>

                    <li class="dropdown">
                        <a class="margin_right cursor_pointer" data-toggle="dropdown">
                            <span>
                                <i class="fa fa-search"  aria-hidden="true"></i>
                            </span>
                        </a>
                        <ul class="dropdown-menu shadows search_window" role="menu">
                            <li>
                                <form action="/search" id="searchForm" method="POST" role="search" @submit.prevent="search" class="monail_navbar_form">
                                    <input v-if="activ_lang == 'us' || activ_lang == 'en'" class="form-control" name="request" v-model="search_query" v-on:keypress="isLetter($event)" placeholder="Search climbing spot here. (English words only!)" autocomplete="off" autofocus="autofocus" type="text" lang="en-US">
                                    <input v-else-if="activ_lang == 'ka'" class="form-control" name="request" v-model="search_query" v-on:keypress="isLetter($event)" placeholder="რისი მოძებნა გსურთ? (გამოიყენეთ მხოლოდ ქართული ალფავიტი)" autocomplete="off" autofocus="autofocus" type="text" lang="ka-GE">
                                </form>
                                <button type="submit" form="searchForm" class="monail_navbar_form_button btn btn-success"> 
                                    <span class="glyphicon glyphicon-search"></span>
                                </button>
                            </li>
                        </ul>
                    </li>

                    <li class="dropdown">
                        <a data-toggle="dropdown" class="cursor_pointer">
                            <span>
                                <i class="fa fa-heartbeat" aria-hidden="true"></i>
                            </span>
                        </a>

                        <ul class="dropdown-menu shadows" role="menu">
                            <li v-if="activ_grade == 'UIAA' || activ_grade == 'uiaa'">
                                <a class="grade_chart" @click=" activ_grade = 'yds', grade_charts('yds')">
                                    YDS
                                </a>
                            </li>
                            <li v-if="activ_grade == 'YDS' || activ_grade == 'yds'">
                                <a class="grade_chart" @click="activ_grade = 'UIAA', grade_charts('UIAA')">
                                    UIAA
                                </a>
                            </li>
                        </ul>
                    </li>

                    <localeSwitcher />

                    <li>
                        <a @click="go_to_service('shop')" class="cursor_pointer"> 
                            <span>
                                <i class="fa fa-shopping-bag" aria-hidden="true"></i>
                            </span>
                        </a>
                    </li>

                    <goToAdminPage :go_to_page="'/'" />

                </ul>
            </div>

        </div>
    </nav>
</template>

<script>
    import countryFlag from 'vue-country-flag' // https://www.npmjs.com/package/vue-country-flag
    import localeSwitcher from '../../../global_components/LocaleChangeComponent.vue'

    export default {
        data: function () {
            return {
                search_query: null,
                show: false,
                navbar_general_class: 'collapse navbar-collapse',
                navbar_class: 'collapse navbar-collapse',
                navbar_open_class: 'collapse navbar-collapse show',

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
            countryFlag,
        },
        mounted() {
            this.navbar_class = this.navbar_general_class
            
            if(!localStorage.getItem('grade')){
                this.grade_charts("YDS")
            }
        },
        watch: {
            '$route' (to, from) {
                this.navbar_class = this.navbar_general_class
            }
        },

        methods: {
            go_to_service(service){
                if(service == "shop"){
                    if(window.location.href == this.MIX_APP_SSH + 'shop.' + this.MIX_SITE_URL + '/about_us'){
                        this.$router.push({name: 'catalog'});
                    }
                    else{
                        window.open(this.MIX_APP_SSH + 'shop.' + this.MIX_SITE_URL + '')
                    }
                }
                else if(service == "guid"){
                    if(window.location.href == this.MIX_APP_SSH + this.MIX_SITE_URL + '/about_us'){
                        this.$router.push({name: 'index'});
                    }
                    else{
                        window.open(this.MIX_APP_SSH  + this.MIX_SITE_URL + '/')
                    }
                }
                else if(service == "films"){
                    if(window.location.href == this.MIX_APP_SSH + 'films.' + this.MIX_SITE_URL + '/about_us'){
                        this.$router.push({name: 'studia'});
                    }
                    else{
                        window.open(this.MIX_APP_SSH  + 'films.' + this.MIX_SITE_URL + '/')
                    }
                }
            },

            search(){
                this.$router.push({name: 'search_articles', query: { 'query': this.search_query} });
                this.search_query = ''
            },
            isLetter(e) {
                let char = String.fromCharCode(e.keyCode); // Get the character
                if(/^[A-Za-z]+$/.test(char)) return true; // Match with regex 
                else e.preventDefault(); // If not match, don't add to input text
            },
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
                if(this.navbar_class == 'collapse navbar-collapse'){
                    this.navbar_class = this.navbar_open_class
                }
                else if(this.navbar_class == 'collapse navbar-collapse show'){
                    this.navbar_class = this.navbar_general_class
                }
                else{
                    this.navbar_class = this.navbar_general_class
                }
            }
        }
    }
</script>

<style>
.grade_chart{
    width: 50%; 
    margin-left: 25%;
}

@media (max-width: 767px) {
    .grade_chart{
        width: 50%; 
        margin-left: 0%;
    }
}

.search_window{
    width: 1500%;
    background-color: #dfdfdf;
    border-radius: 0 0 20px 20px;
}
</style>