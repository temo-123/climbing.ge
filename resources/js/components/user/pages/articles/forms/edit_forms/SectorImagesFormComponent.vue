<template>
    <gallery_images_edit
        title_prop="Sector Images"
        image_path_prop="images/spot_rocks_img/"
        get_images_route_prop="/get_sector/get_spot_rock_images/get_spot_rock_images/"
        image_del_route_prop="/set_sector/set_spot_rock_images/del_spot_rock_image/"
        @update_gallery_images="$emit('area_images', $event)"
    />
</template>

<script>
    import gallery_images_edit from '../../../../items/gallery/galleryImageEditComponent.vue'
    export default {
        components: { gallery_images_edit }
    }
</script>
