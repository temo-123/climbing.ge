<template>
    <div class="container">
        <div class="aboutus-section">
            <div class="container">
                <div class="row">
                    <div class="col-md-12 col-sm-12 col-xs-12">
                        <div class="aboutus">
                            <legalDocument :slugs="['user_' + doc, doc]" />
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <metaData
            :title="doc === 'privacy_policy' ? $t('privacy.title') : $t('terms.title')"
            :description="doc === 'privacy_policy' ? $t('privacy.meta_description') : $t('terms.meta_description')"
            :image="'/public/images/meta_img/outdoor.jpg'"
        />
    </div>
</template>

<script>
    import metaData from '../items/MetaDataComponent.vue'
    import legalDocument from '../../global_components/LegalDocumentComponent.vue'

    export default {
        name: 'TermsOfUsePage',
        components: { metaData, legalDocument },
        // Same page serves the privacy policy (route props: { doc: 'privacy_policy' })
        props: {
            doc: { type: String, default: 'terms_of_use' },
        },
    }
</script>
