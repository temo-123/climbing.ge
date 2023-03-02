<template>
    <li class="dropdown display-none-720px">
        <a data-toggle="dropdown">
            <i class="fa fa-language" aria-hidden="true"></i>
        </a>

        <ul class="dropdown-menu shadows" role="menu">
            <!-- <li v-if="activ_lang == 'ka' || activ_lang == 'ru'"><a style="width: 50%; margin-left: 25%;"  @click="localization('en')"><countryFlag country='usa' size='big'/></a></li>
            <li v-if="activ_lang == 'en' || activ_lang == 'ru'"><a style="width: 50%; margin-left: 25%;"  @click="localization('ka')"><countryFlag country='geo' size='big'/></a></li>
            <li v-if="activ_lang == 'ka' || activ_lang == 'en'"><a style="width: 50%; margin-left: 25%;"  @click="localization('ru')"><countryFlag country='rus' size='big'/></a></li> -->


            <li v-if="activ_lang == 'ka' || activ_lang == 'ru'"><a style="width: 50%; margin-left: 25%;"  @click="activ_lang = 'en', localization('en')"><countryFlag country='usa' size='big'/></a></li>
            <li v-if="activ_lang == 'en' || activ_lang == 'ru'"><a style="width: 50%; margin-left: 25%;"  @click="activ_lang = 'ka', localization('ka')"><countryFlag country='geo' size='big'/></a></li>
            <li v-if="activ_lang == 'ka' || activ_lang == 'en'"><a style="width: 50%; margin-left: 25%;"  @click="activ_lang = 'ru', localization('ru')"><countryFlag country='rus' size='big'/></a></li>
        </ul>

        <!-- - {{token}} -
        <button @click="token++"> + </button> -->
    </li>
</template>

<script>
    import countryFlag from 'vue-country-flag' // https://www.npmjs.com/package/vue-country-flag

    export default {
        data: function () {
            return {
                // activ_lang: localStorage.getItem('lang'),

                get activ_lang() {
                    return localStorage.getItem('lang') || 'en';
                },
                set activ_lang(value) {
                    localStorage.setItem('lang', value);
                },

                // get token() {
                //     return localStorage.getItem('token') || 0;
                // },
                // set token(value) {
                //     localStorage.setItem('token', value);
                // }
            };
        },
        components: {
            countryFlag,
        },
        mounted() {
            // console.log(this.$route.params.locale || 'en');
        },
        watch: {
            '$route' (to, from) {
                // let locale = localStorage.getItem("lang")
                // console.log("🚀 ~ file: LocaleChangeComponent.vue:55 ~ locale:", locale)

                // if(locale){
                    // if(locale != 'en'){
                    //     let actice_locale = locale

                    //     const loc = this.$router.resolve({params: {actice_locale}})
                    //     this.$router.push(loc.location)
                    // }
                    // else if(locale == 'en'){
                    //     localStorage.setItem('lang', locale)
                    //     this.$i18n.locale = locale;

                    //     let activ_path_without_locale = this.$router.history.pending.path.split("/").splice(2).join("/")
                        
                    //     this.$router.push( '/' + activ_path_without_locale )
                    // }
                // }
                // this.localization(locale)
            }
        },
        methods: {
            localization(locale){
                if(this.$i18n.locale !== locale){
                    if(locale != 'en'){
                        localStorage.setItem('lang', locale)
                        this.$i18n.locale = locale;

                        const to = this.$router.resolve({params: {locale}})
                        this.$router.push(to.location)
                    }
                    else if(locale == 'en'){
                        localStorage.setItem('lang', locale)
                        this.$i18n.locale = locale;

                        let activ_path_without_locale = this.$router.history.pending.path.split("/").splice(2).join("/")
                        
                        this.$router.push( '/' + activ_path_without_locale )
                    }
                }
            },
        }
    }
</script>

<style>

</style>