<template>
    <div class="container">
        <div class="aboutus-section">
            <div class="container">
                <div class="row">
                    <div class="col-md-12 col-sm-12 col-xs-12">
                        <div class="aboutus">
                            <legalDocument :slugs="['blog_' + doc, doc]" />
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <messageComponent />

        <metaData
            :title="doc === 'privacy_policy' ? $t('privacy.title') : $t('terms.title')"
            :description="doc === 'privacy_policy' ? $t('privacy.meta_description') : $t('terms.meta_description')"
            :image="'/public/images/meta_img/outdoor.jpg'"
        />
    </div>
</template>

<script>
    import messageComponent from '../../global_components/MessageComponent.vue'
    import metaData from '../items/MetaDataComponent.vue'
    import legalDocument from '../../global_components/LegalDocumentComponent.vue'

    export default {
        name: 'TermsOfUsePage',
        components: { metaData, messageComponent, legalDocument },
        // Same page serves the privacy policy (route props: { doc: 'privacy_policy' })
        props: {
            doc: { type: String, default: 'terms_of_use' },
        },
    }
</script>
