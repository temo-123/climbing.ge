<template>
    <div>
        <div class="h-recent-work">
            <div class="container">
                <legalDocument :slugs="['summit_' + doc, doc]" />
            </div>
        </div>

        <messageComponent />

        <metaData
            :title="doc === 'privacy_policy' ? $t('privacy.title') : doc === 'donation_terms_of_use' ? $t('donation_terms.title') : $t('terms.title')"
            :description="doc === 'privacy_policy' ? $t('privacy.meta_description') : doc === 'donation_terms_of_use' ? $t('donation_terms.meta_description') : $t('terms.meta_description')"
            :image="'/public/images/meta_img/mountain.jpg'"
        />
    </div>
</template>

<script>
    import metaData from '../items/MetaDataComponent.vue'
    import legalDocument from '../../global_components/LegalDocumentComponent.vue'
    import messageComponent from '../../global_components/MessageComponent.vue'

    export default {
        name: 'TermsOfUsePage',
        components: { metaData, messageComponent, legalDocument },
        // Same page serves the privacy policy (route props: { doc: 'privacy_policy' })
        props: {
            doc: { type: String, default: 'terms_of_use' },
        },
    }
</script>
