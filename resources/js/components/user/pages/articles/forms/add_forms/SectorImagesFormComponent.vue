<template>
    <gallery_images_add
        title_prop="Sector Images"
        @update_gallery_images="$emit('upload_img', $event)"
    />
</template>

<script>
    import gallery_images_add from '../../../../items/gallery/galleryImageAddComponent.vue'
    export default {
        components: { gallery_images_add }
    }
</script>
