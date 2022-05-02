<template>
    <li class="dropdown display-none-720px">
        <a href="#" data-toggle="dropdown">
            <i class="fa fa-language" aria-hidden="true"></i>
        </a>

        <ul class="dropdown-menu shadows" role="menu">
            <li :if="!lang == us"><a style="width: 50%; margin-left: 25%;"  @click="localization('us')"><country-flag country='usa' size='big'/></a></li>
            <li :if="!lang == ka"><a style="width: 50%; margin-left: 25%;"  @click="localization('ka')"><country-flag country='geo' size='big'/></a></li>
            <li :if="!lang == ru"><a style="width: 50%; margin-left: 25%;"  @click="localization('ru')"><country-flag country='rus' size='big'/></a></li>
        </ul>
    </li>
</template>

<script>
    import CountryFlag from 'vue-country-flag'

    export default {
        data: function () {
            return {
                // 
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
                this.lang = locale
                localStorage.setItem('lang', this.lang)
                this.$i18n.locale = locale;
            },
        }
    }
</script>

<style>

</style>