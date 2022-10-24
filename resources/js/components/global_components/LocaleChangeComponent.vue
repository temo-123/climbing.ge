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
        methods: {
            localization(locale){
                if(this.$i18n.locale !== locale){
                    localStorage.setItem('lang', locale)
                    this.$i18n.locale = locale;

                    const to = this.$router.resolve({params: {locale}})
                    this.$router.push(to.location)
                }
            },
        }
    }
</script>

<style>

</style>