<template>
    <stack-modal
        size="xxl"
        :show="is_open"
        :title="$t('admin.live_camera.photos_modal_title') + (camera ? ' — ' + camera.name : '')"
        @close="close_modal"
        :saveButton="{ visible: false }"
        :cancelButton="{ visible: true, title: $t('common.close'), btnClass: { 'btn btn-secondary': true } }"
    >
        <div>
            <div v-if="loading">{{ $t('admin.live_camera.detail_loading') }}</div>

            <template v-else-if="camera">
                <div v-if="camera.source !== 'upload'" class="alert alert-warning">
                    {{ $t('admin.live_camera.photos_source_warning') }}
                </div>

                <gallery_images_edit
                    :key="gallery_remount_key"
                    @update_gallery_images="update_gallery_images"
                    :item_id_prop="camera_id"
                    :image_path_prop="'images/live_cameras_img/'"
                    :image_del_route_prop="'/set_live_camera/delete_live_camera_photo/'"
                    :get_images_route_prop="'/set_live_camera/get_editing_live_camera_photos/'"
                    :title_prop="$t('admin.live_camera.upload_photos_label')"
                />
                <p v-if="uploading_photos" class="text-muted">{{ $t('admin.live_camera.uploading_status') }}</p>
                <div v-else-if="upload_error" class="alert alert-danger">{{ upload_error }}</div>
            </template>
        </div>
    </stack-modal>
</template>

<script>
    import gallery_images_edit from '../gallery/galleryImageEditComponent.vue'

    // Manual photo upload for a live camera (source = "upload", e.g. photos from an SD card).
    // Split out of the Edit modal so the camera settings form stays short.
    export default {
        components: {
            gallery_images_edit,
        },
        data(){
            return {
                is_open: false,
                loading: false,
                camera_id: 0,
                camera: null,

                pending_gallery_images: [],
                uploading_photos: false,
                upload_error: null,
                gallery_remount_key: 0,
            }
        },
        methods: {
            show_modal(id){
                this.camera_id = id
                this.camera = null
                this.upload_error = null
                this.pending_gallery_images = []
                this.gallery_remount_key++
                this.is_open = true
                this.get_camera()
            },
            close_modal(){
                this.is_open = false
                this.camera = null
                this.pending_gallery_images = []
                this.upload_error = null
            },
            get_camera(){
                this.loading = true
                axios
                .get('/set_live_camera/get_editing_live_camera/' + this.camera_id)
                .then(response => {
                    this.camera = response.data
                })
                .catch(error => console.log(error))
                .finally(() => {
                    this.loading = false
                })
            },
            update_gallery_images(images){
                this.pending_gallery_images = images.map(image => image.image).filter(Boolean)

                // Auto-upload as soon as files are staged — a separate manual "Upload"
                // button used to go unnoticed (people expect "add" to mean "saved").
                if (this.pending_gallery_images.length && !this.uploading_photos) {
                    this.upload_photos()
                }
            },
            upload_photos(){
                this.uploading_photos = true
                this.upload_error = null

                const formData = new FormData()
                formData.append('live_camera_id', this.camera_id)
                this.pending_gallery_images.forEach(file => formData.append('photos[]', file))

                axios
                .post('/set_live_camera/upload_live_camera_photos', formData)
                .then(() => {
                    this.pending_gallery_images = []
                    // Remounting the gallery manager clears its staged (now
                    // persisted) files and re-fetches old_images from the server.
                    this.gallery_remount_key++
                })
                .catch(error => {
                    console.log(error)
                    this.upload_error = (error.response && error.response.data && (error.response.data.message || JSON.stringify(error.response.data.errors))) || error.message || 'Upload failed'
                })
                .finally(() => {
                    this.uploading_photos = false
                })
            },
        }
    }
</script>
