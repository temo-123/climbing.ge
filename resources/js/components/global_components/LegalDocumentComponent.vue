<template>
    <div class="legal_document">
        <div v-if="!$siteData.loaded" class="text-muted">{{ $t('global.loading') }}</div>
        <div v-else-if="html" v-html="html"></div>
        <!-- No locale_sites row for these slugs (e.g. migration not run on this server) -->
        <div v-else class="text-muted">{{ $t('global.legal_links.not_available') }}</div>
    </div>
</template>

<script>
    /**
     * Renders a legal text from site data (locale_sites slug) — terms of use,
     * privacy policy. `slugs` are tried in order, so a subdomain-specific slug
     * (e.g. shop_terms_of_use) can fall back to the shared one (terms_of_use).
     * Plain-text content (no HTML tags) keeps its line breaks.
     */
    export default {
        name: 'LegalDocumentComponent',
        props: {
            slugs: {
                type: Array,
                required: true,
            },
        },
        computed: {
            html() {
                const data = this.$siteData.data || {}
                const content = this.slugs.map(slug => data[slug]).find(value => value && String(value).trim()) || ''

                if (/<[a-z][\s\S]*>/i.test(content)) return content

                const escaped = content.replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;')
                return escaped.split(/\n{2,}/).map(paragraph => '<p>' + paragraph.replace(/\n/g, '<br>') + '</p>').join('')
            },
        },
    }
</script>

<style>
    .legal_document {
        text-align: left;
        line-height: 1.7;
    }
    .legal_document h2,
    .legal_document h3 {
        margin-top: 1.4em;
    }
</style>
