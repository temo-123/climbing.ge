<template>
    <gallery_images_add
        :title_prop="$t('admin.articles.locale_form.mount_route_images_title')"
        @update_gallery_images="$emit('mount_route_img', $event)"
    />
</template>

<script>
    import gallery_images_add from '../../../../items/gallery/galleryImageAddComponent.vue'
    export default {
        components: { gallery_images_add }
    }
</script>
