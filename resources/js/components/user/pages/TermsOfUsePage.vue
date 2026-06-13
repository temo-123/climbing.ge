<template>
    <div class="container">
        <div class="aboutus-section">
            <div class="container">
                <div class="row">
                    <div class="col-md-12 col-sm-12 col-xs-12">
                        <div class="aboutus">
                            <span v-html="$siteData.data.user_terms_of_use"></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <metaData
            :title="$t('terms.title')"
            :description="$t('terms.meta_description')"
            :image="'/public/images/meta_img/outdoor.jpg'"
        />
    </div>
</template>

<script>
    import metaData from '../items/MetaDataComponent.vue'

    export default {
        name: 'TermsOfUsePage',
        components: { metaData },
    }
</script>
