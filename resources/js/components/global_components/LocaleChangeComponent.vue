<template>
    <li class="dropdown cg-locale">
        <a class="cg-icon-btn cg-locale__btn" role="button" data-bs-toggle="dropdown" aria-expanded="false" :title="$t('global.language')">
            {{ activ_lang === 'ka' ? 'KA' : 'EN' }}
        </a>

        <ul class="dropdown-menu dropdown-menu-end" role="menu">
            <li><div class="cg-dropdown-label">{{ $t('global.language') }}</div></li>
            <li>
                <a class="dropdown-item" :class="{ active: activ_lang !== 'ka' }" @click="activ_lang = 'en', localization('en')">
                    <span class="cg-locale__code">EN</span> English
                </a>
            </li>
            <li>
                <a class="dropdown-item" :class="{ active: activ_lang === 'ka' }" @click="activ_lang = 'ka', localization('ka')">
                    <span class="cg-locale__code">KA</span> ქართული
                </a>
            </li>
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
