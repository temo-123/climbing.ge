<template>
    <div class="row">
        <hr class="footer_bottom_line">
        <p class="footer_bottom_text footer_legal_links">
            <router-link :to="locale_path('/terms_of_use')">{{ $t('global.footer.terms_of_use') }}</router-link>
            &middot;
            <router-link :to="locale_path('/privacy_policy')">{{ $t('global.footer.privacy_policy') }}</router-link>
            &middot;
            <router-link :to="locale_path('/donation_terms')">{{ $t('global.footer.donation_terms') }}</router-link>
            &middot;
            <!-- Account deletion page lives on the user subdomain (Google Play data-deletion URL) -->
            <router-link v-if="is_user_page" :to="{ name: 'delete_account' }">{{ $t('global.footer.delete_account') }}</router-link>
            <a v-else :href="delete_account_url">{{ $t('global.footer.delete_account') }}</a>
        </p>
        <p class="footer_bottom_text">© 2017-{{ active_year }} Climbing In Georgia. Project by Temo Samsonadze <a href="https://github.com/temo-123" target="_blank">(Github -> temo-123)</a></p>
    </div>
</template>

<script>
    export default {
        data: function () {
            return {
                active_year: new Date().getFullYear(),
                is_user_page: window.location.hostname == process.env.MIX_USER_PAGE_URL,
                delete_account_url: (process.env.MIX_APP_SSH || 'https://') + process.env.MIX_USER_PAGE_URL + '/delete-account',
            };
        },
        methods: {
            // Every subdomain router serves /terms_of_use, /privacy_policy and /donation_terms under the optional /ka prefix
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