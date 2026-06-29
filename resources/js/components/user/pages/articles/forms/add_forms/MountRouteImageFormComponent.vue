<template>
    <gallery_images_add
        title_prop="Mount Route Images"
        @update_gallery_images="$emit('mount_route_img', $event)"
    />
</template>

<script>
    import gallery_images_add from '../../../../items/gallery/galleryImageAddComponent.vue'
    export default {
        components: { gallery_images_add }
    }
</script>
