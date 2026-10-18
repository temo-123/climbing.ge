<template>
    <div class="row">
        <hr class="footer_bottom_line">
        <p class="footer_bottom_text footer_legal_links">
            <router-link :to="locale_path('/terms_of_use')">{{ $t('global.footer.terms_of_use') }}</router-link>
            &middot;
            <router-link :to="locale_path('/privacy_policy')">{{ $t('global.footer.privacy_policy') }}</router-link>
        </p>
        <p class="footer_bottom_text">© 2017-{{ active_year }} Climbing In Georgia. Project by Temo Samsonadze <a href="https://github.com/temo-123" target="_blank">(Github -> temo-123)</a></p>
    </div>
</template>

<script>
    export default {
        data: function () {
            return {
                active_year: new Date().getFullYear(),
            };
        },
        methods: {
            // Every subdomain router serves /terms_of_use and /privacy_policy under the optional /ka prefix
            locale_path(path) {
                return (this.$i18n.locale === 'ka' ? '/ka' : '') + path
            },
        },
    }
</script>

<style>
    .footer_bottom_line {
        margin: 5% 5% 2%;
        border-color: #565656;
    }
    .footer_bottom_text{
        text-align: center;
        color: #878787;
    }
    .footer_legal_links a {
        color: #878787;
        text-decoration: underline;
    }
</style>