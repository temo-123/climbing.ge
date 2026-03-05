<template>
    <li class="dropdown cursor_pointer">
        <a data-toggle="dropdown">
            <span>
                <i class="fa fa-language" aria-hidden="true"></i>
            </span>
        </a>

        <ul class="dropdown-menu shadows" role="menu">
            <li v-if="activ_lang == 'ka' || activ_lang == 'ru'"><a class="localizatione_flag" @click="activ_lang = 'en', localization('en')"><countryFlag country='usa' size='big'/></a></li>
            <li v-if="activ_lang == 'en' || activ_lang == 'ru'"><a class="localizatione_flag"  @click="activ_lang = 'ka', localization('ka')"><countryFlag country='geo' size='big'/></a></li>
            <!-- <li v-if="activ_lang == 'ka' || activ_lang == 'en'"><a class="localizatione_flag"  @click="activ_lang = 'ru', localization('ru')"><countryFlag country='rus' size='big'/></a></li> -->
        </ul>
    </li>
</template>

<script>
    import countryFlag from 'vue-country-flag' // https://www.npmjs.com/package/vue-country-flag
    import Vue from "vue";
    export default {
        data: function () {
            return {
                get activ_lang() {
                    return localStorage.getItem('lang') || 'en';
                },
                set activ_lang(value) {
                    localStorage.setItem('lang', value);
                },
            };
        },
        components: {
            countryFlag,
        },
        mounted() {
            // console.log(this.$route.params.locale || 'en');
            // if(!localStorage.getItem('lang')){
            //     this.grade_charts("en")
            // }
        },
        watch: {
            '$route' (to, from) {
                // this.localization(locale)
            }
        },
        methods: {
            localization(locale){
                if(this.$i18n.locale !== locale){
                    this.$i18n.locale = locale;
                    localStorage.setItem('lang', locale)
                    this.$route.params.locale = locale

                    if(locale == 'en'){
                        let activ_path_without_locale = this.$router.history.pending.path.split("/").splice(2).join("/")
                        this.$router.push( '/' + activ_path_without_locale )
                    }
                    else{
                        const to = this.$router.resolve({params: {locale}})
                        this.$router.push(to.location)
                    }

                    location.reload();
                }
            },
        }
    }
</script>

<style>
.localizatione_flag{
    width: 50%; 
    margin-left: 25%;
}

@media (max-width: 767px) {
    .localizatione_flag{
        width: 50%; 
        margin-left: 0%;
    }
}
</style>