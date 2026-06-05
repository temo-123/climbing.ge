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
                v-for="(img, index) in images"
                :key="img.id"
                class="product-image-item"
            >
                <img
                    :src="'/public/images/product_img/' + img.image"
                    class="img-thumbnail"
                    alt="Product gallery image"
                    @click="open_image(index)"
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

        <div class="lb-overlay" v-if="open_img" @click.self="close_image">
            <div class="lb-close" @click="close_image">&#x2715;</div>
            <img :src="'/public/images/product_img/' + images[active_index].image" alt="Product gallery image" class="lb-img" />
            <div class="lb-nav" v-if="images.length > 1">
                <div class="lb-prev" @click="prev_image"><i class="fa fa-chevron-left"></i></div>
                <div class="lb-next" @click="next_image"><i class="fa fa-chevron-right"></i></div>
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
            open_img: false,
            active_index: 0,
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
        open_image(index) {
            this.active_index = index
            this.open_img = true
            this.add_keyboard_actions()
        },
        close_image() {
            this.open_img = false
        },
        prev_image() {
            this.active_index = this.active_index === 0 ? this.images.length - 1 : this.active_index - 1
            this.add_keyboard_actions()
        },
        next_image() {
            this.active_index = this.active_index === this.images.length - 1 ? 0 : this.active_index + 1
            this.add_keyboard_actions()
        },
        add_keyboard_actions() {
            const that = this
            document.addEventListener('keydown', function handler(evt) {
                if (evt.keyCode === 27) that.close_image()
                else if (evt.keyCode === 37) that.prev_image()
                else if (evt.keyCode === 39) that.next_image()
                document.removeEventListener('keydown', handler)
            }, { once: true })
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
.product-image-item img { cursor: pointer; }

.lb-overlay {
    position: fixed; top: 0; right: 0; bottom: 0; left: 0;
    z-index: 9999; background: #000000d9;
}
.lb-img {
    max-width: 92%; max-height: 85%;
    position: absolute; top: 50%; left: 50%;
    transform: translate(-50%, -50%);
}
.lb-close {
    position: absolute; top: 14px; right: 18px;
    color: #ccc; font-size: 2em; cursor: pointer; z-index: 10000;
}
.lb-close:hover { color: #fff; }
.lb-nav {
    position: absolute; top: 50%; left: 0; right: 0;
    transform: translateY(-50%);
    display: flex; justify-content: space-between;
}
.lb-prev, .lb-next {
    cursor: pointer; font-size: 3em; color: #ccc; padding: 0 14px;
}
.lb-prev:hover, .lb-next:hover { color: #fff; }

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
