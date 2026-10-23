<template>
    <div class="container legal_links_block">
        <h2 class="legal_links_title">{{ $t('global.legal_links.title') }}</h2>
        <p class="legal_links_text">{{ $t('global.legal_links.text') }}</p>
        <div class="legal_links_buttons">
            <router-link :to="locale_path('/terms_of_use')" class="legal_links_btn">
                <i class="fa fa-file-text-o" aria-hidden="true"></i> {{ $t('global.footer.terms_of_use') }}
            </router-link>
            <router-link :to="locale_path('/privacy_policy')" class="legal_links_btn">
                <i class="fa fa-shield" aria-hidden="true"></i> {{ $t('global.footer.privacy_policy') }}
            </router-link>
        </div>
    </div>
</template>

<script>
    // Terms of Use + Privacy Policy links for the About Us pages (every subdomain
    // router serves /terms_of_use and /privacy_policy under the optional /ka prefix)
    export default {
        name: 'LegalLinksComponent',
        methods: {
            locale_path(path) {
                return (this.$i18n.locale === 'ka' ? '/ka' : '') + path
            },
        },
    }
</script>

<style>
    .legal_links_block {
        margin: 30px auto;
        text-align: center;
    }
    .legal_links_title {
        margin-bottom: 8px;
    }
    .legal_links_text {
        color: var(--cg-stone);
        margin-bottom: 16px;
    }
    .legal_links_buttons {
        display: flex;
        flex-wrap: wrap;
        justify-content: center;
        gap: 12px;
    }
    .legal_links_btn {
        display: inline-block;
        padding: 10px 20px;
        border: 1px solid #ccc;
        border-radius: 6px;
        color: var(--cg-ink-2);
        text-decoration: none;
    }
    .legal_links_btn:hover {
        background: var(--cg-chalk);
        text-decoration: none;
    }
</style>
