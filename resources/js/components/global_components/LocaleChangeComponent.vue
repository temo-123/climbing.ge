<template>
    <li class="dropdown display-none-720px">
        <a data-toggle="dropdown">
            <i class="fa fa-language" aria-hidden="true"></i>
        </a>

        <ul class="dropdown-menu shadows" role="menu">
            <li><a style="width: 50%; margin-left: 25%;"  @click="localization('us')"><CountryFlag country='usa' size='big'/></a></li>
            <li><a style="width: 50%; margin-left: 25%;"  @click="localization('ka')"><CountryFlag country='geo' size='big'/></a></li>
            <li><a style="width: 50%; margin-left: 25%;"  @click="localization('ru')"><CountryFlag country='rus' size='big'/></a></li>
        </ul>
    </li>
</template>

<script>
    import CountryFlag from 'vue-country-flag'

    export default {
        data: function () {
            return {
                lang: localStorage.getItem('lang')
            };
        },
        components: {
            CountryFlag,
        },
        mounted() {
            //
        },
        methods: {
            localization(locale){
                // this.lang = locale
                localStorage.setItem('lang', locale)
                this.$i18n.locale = locale;
            },
        }
    }
</script>

<style>

</style>