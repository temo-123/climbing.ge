<template>
    <li class="dropdown cursor_pointer">
        <a data-toggle="dropdown">
            <span>
                <i class="fa fa-language" aria-hidden="true"></i>
            </span>
        </a>

        <ul class="dropdown-menu shadows" role="menu">
            <li v-if="activ_lang == 'ka' || activ_lang != 'en'"><a class="localizatione_flag" @click="activ_lang = 'en', localization('en')">EN</a></li>
            <li v-if="activ_lang == 'en' || activ_lang != 'ka'"><a class="localizatione_flag"  @click="activ_lang = 'ka', localization('ka')">KA</a></li>
        </ul>
    </li>
</template>

<script>
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
            // 
        },
        mounted() {
            // 
        },
        watch: {
            '$route' (to, from) {
                // 
            }
        },
        methods: {
            localization(locale){
                if(this.$i18n.locale !== locale){
                    this.$i18n.locale = locale;
                    localStorage.setItem('lang', locale);

                    const currentPath = this.$route.path;
                    const pathParts = currentPath.split('/').filter(Boolean);
                    if (pathParts[0] === 'ka') {
                        pathParts.shift();
                    }
                    const cleanPath = pathParts.length ? '/' + pathParts.join('/') : '/';
                    const newPath = locale === 'en' ? cleanPath : '/ka' + cleanPath;
                    this.$router.push(newPath).then(() => window.location.reload());
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