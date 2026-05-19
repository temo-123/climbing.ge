<template>
    <div class="col-md-12">
        <h4>Product Gallery</h4>

        <div class="row mb-3">
            <div class="col-md-12">
                <label class="btn btn-primary">
                    <i class="fa fa-upload"></i> Upload Images
                    <input
                        type="file"
                        multiple
                        accept="image/*"
                        style="display:none"
                        @change="onFileChange"
                    />
                </label>
                <span v-if="uploading" class="ml-2 text-muted">
                    <i class="fa fa-spinner fa-spin"></i> Uploading...
                </span>
            </div>
        </div>

        <div v-if="is_loading" class="text-center py-4">
            <i class="fa fa-spinner fa-spin fa-2x"></i>
        </div>

        <div v-else-if="images.length === 0" class="alert alert-info">
            No gallery images yet. Upload some to display on the product page.
        </div>

        <div v-else class="product-images-grid">
            <div
                v-for="img in images"
                :key="img.id"
                class="product-image-item"
            >
                <img
                    :src="'/public/images/product_img/' + img.image"
                    class="img-thumbnail"
                    alt="Product gallery image"
                />
                <button
                    class="btn btn-danger btn-sm del-btn"
                    @click="del_image(img.id)"
                    :disabled="deleting === img.id"
                >
                    <i class="fa fa-trash"></i>
                </button>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: 'ProductImagesComponent',
    data() {
        return {
            images: [],
            is_loading: false,
            uploading: false,
            deleting: null,
        }
    },
    computed: {
        product_id() {
            return this.$route.params.id
        }
    },
    mounted() {
        this.load_images()
    },
    methods: {
        load_images() {
            this.is_loading = true
            axios.get(`/set_product/set_product_img/get_images/${this.product_id}`)
                .then(res => { this.images = res.data })
                .catch(() => {})
                .finally(() => { this.is_loading = false })
        },
        onFileChange(event) {
            const files = event.target.files
            if (!files || files.length === 0) return
            const formData = new FormData()
            for (let i = 0; i < files.length; i++) {
                formData.append('images[]', files[i])
            }
            this.uploading = true
            axios.post(`/set_product/set_product_img/add_images/${this.product_id}`, formData, {
                headers: { 'Content-Type': 'multipart/form-data' }
            })
                .then(() => { this.load_images() })
                .catch(() => { alert('Upload failed. Please try again.') })
                .finally(() => { this.uploading = false })
            event.target.value = ''
        },
        del_image(image_id) {
            if (!confirm('Delete this image?')) return
            this.deleting = image_id
            axios.delete(`/set_product/set_product_img/del_image/${image_id}`)
                .then(() => { this.images = this.images.filter(i => i.id !== image_id) })
                .catch(() => { alert('Delete failed.') })
                .finally(() => { this.deleting = null })
        }
    }
}
</script>

<style scoped>
.product-images-grid {
    display: flex;
    flex-wrap: wrap;
    gap: 12px;
}
.product-image-item {
    position: relative;
    width: 120px;
}
.product-image-item img {
    width: 120px;
    height: 120px;
    object-fit: cover;
}
.del-btn {
    position: absolute;
    top: 4px;
    right: 4px;
    padding: 2px 6px;
    font-size: 11px;
}
</style>
