<template>
    <div class="col-md-12">
        <gallery_images_edit
            :key="gallery_key"
            :title_prop="$t('admin.shop.product_gallery_title')"
            image_path_prop="images/product_img/"
            get_images_route_prop="/set_product/set_product_img/get_images/"
            image_del_route_prop="/set_product/set_product_img/del_image/"
            :item_id_prop="product_id"
            :crop_ratio_prop="{ width: 16, height: 9 }"
            @update_gallery_images="new_images = $event"
        />

        <div v-if="new_images.length > 0" class="mt-3">
            <button class="btn btn-primary" @click="upload_images" :disabled="uploading">
                <span v-if="uploading"><i class="fa fa-spinner fa-spin"></i> {{ $t('admin.shop.uploading_ellipsis') }}</span>
                <span v-else>{{ $t('admin.shop.upload_n_new_images', { count: new_images.length }) }}</span>
            </button>
        </div>
    </div>
</template>

<script>
import gallery_images_edit from '../../../items/gallery/galleryImageEditComponent.vue'

export default {
    components: { gallery_images_edit },
    data() {
        return {
            new_images: [],
            uploading: false,
            gallery_key: 0,
        }
    },
    computed: {
        product_id() {
            return this.$route.params.id
        }
    },
    methods: {
        async upload_images() {
            const files = this.new_images.filter(img => img.image)
            if (files.length === 0) return
            this.uploading = true
            try {
                const formData = new FormData()
                files.forEach(img => formData.append('images[]', img.image))
                await axios.post(`/set_product/set_product_img/add_images/${this.product_id}`, formData, {
                    headers: { 'Content-Type': 'multipart/form-data' }
                })
                this.new_images = []
                this.gallery_key++
            } catch (e) {
                this.$bus.$emit('toast', { type: 'error', title: this.$t('admin.shop.upload_failed_title'), message: this.$t('admin.shop.upload_failed_message'), duration: 4000 })
            } finally {
                this.uploading = false
            }
        }
    }
}
</script>
