<template>
    <gallery_images_edit
        title_prop="Mount Route Images"
        image_path_prop="images/mount_route_img/"
        get_images_route_prop="/get_mount_route/get_mount_routes_images/"
        image_del_route_prop="/get_mount_route/del_mount_route_image/"
        @update_gallery_images="$emit('mount_route_img', $event)"
    />
</template>

<script>
    import gallery_images_edit from '../../../../items/gallery/galleryImageEditComponent.vue'
    export default {
        components: { gallery_images_edit }
    }
</script>
