<template>
    <div>
        <div class="h-recent-work">
            <div class="container">
                <span v-html="$siteData.data.summit_terms_of_use"></span>
            </div>
        </div>

        <messageComponent />

        <metaData
            :title="$t('terms.title')"
            :description="$t('terms.meta_description')"
            :image="'/public/images/meta_img/mountain.jpg'"
        />
    </div>
</template>

<script>
    import metaData from '../items/MetaDataComponent.vue'
    import messageComponent from '../../global_components/MessageComponent.vue'

    export default {
        name: 'TermsOfUsePage',
        components: { metaData, messageComponent },
    }
</script>
