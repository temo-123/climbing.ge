<template>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark bg-perple fixed-top">
        
        <router-link :to="{name: 'home'}" class="navbar-brand">
            Hi 
        </router-link>

        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav mr-auto">
                <li class="nav-item active">
                    <router-link :to="{name: 'adminPage'}" class="navbar-brand" exact>
                        Dashboard
                    </router-link>
                </li>

                <!-- <li class="nav-item">
                    <a class="nav-link" href="#">Routes</a>
                </li> -->

                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        Lang
                    </a>
                    <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                        <li v-if="activ_lang == 'ka' || activ_lang == 'ru'"><a class="" @click="activ_lang = 'en', localization('en')"><countryFlag country='usa' size='big'/></a></li>
                        <li v-if="activ_lang == 'en' || activ_lang == 'ru'"><a class="" @click="activ_lang = 'ka', localization('ka')"><countryFlag country='geo' size='big'/></a></li>
                        <li v-if="activ_lang == 'ka' || activ_lang == 'en'"><a class="" @click="activ_lang = 'ru', localization('ru')"><countryFlag country='rus' size='big'/></a></li>
                        <!-- <div class="dropdown-divider"></div>
                        <a class="dropdown-item" href="#">Something else here</a> -->
                    </div>
                </li>
            </ul>
        </div>
    </nav>
</template>

<script>
    import countryFlag from 'vue-country-flag' // https://www.npmjs.com/package/vue-country-flag

    export default {
        data: function () {
            return {
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
                this.get_user()
                window.scrollTo(0,0)
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
            }
        },
    }
</script>

<style>
.bg-perple {
    background-color: #7427bb !important;
}
</style>