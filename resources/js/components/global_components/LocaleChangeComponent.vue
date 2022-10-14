<template>
    <li class="dropdown display-none-720px">
        <a data-toggle="dropdown">
            <i class="fa fa-language" aria-hidden="true"></i>
        </a>

        <ul class="dropdown-menu shadows" role="menu">
            <li><a style="width: 50%; margin-left: 25%;"  @click="localization('en')"><countryFlag country='usa' size='big'/></a></li>
            <li><a style="width: 50%; margin-left: 25%;"  @click="localization('ka')"><countryFlag country='geo' size='big'/></a></li>
            <li><a style="width: 50%; margin-left: 25%;"  @click="localization('ru')"><countryFlag country='rus' size='big'/></a></li>
        </ul>
    </li>
</template>

<script>
    import countryFlag from 'vue-country-flag' // https://www.npmjs.com/package/vue-country-flag

    export default {
        data: function () {
            return {
                lang: localStorage.getItem('lang')
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