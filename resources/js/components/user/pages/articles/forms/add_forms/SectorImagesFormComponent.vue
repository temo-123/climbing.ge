<template>
    <gallery_images_add
        :title_prop="$t('admin.articles.locale_form.sector_images_title')"
        @update_gallery_images="$emit('upload_img', $event)"
    />
</template>

<script>
    import gallery_images_add from '../../../../items/gallery/galleryImageAddComponent.vue'
    export default {
        components: { gallery_images_add }
    }
</script>
