<template>
    <stack-modal
            size="xl"
            :show="is_live_camera_edit_model"
            :title="$t('admin.live_camera.edit_live_camera_title')"
            @close="close_modal"
            @save="edit_live_camera"
            :saveButton="{ visible: true, title: $t('admin.live_camera.edit_live_camera_title'), btnClass: { 'btn btn-primary': true } }"
            :cancelButton="{ visible: false, title: $t('common.close'), btnClass: { 'btn btn-danger': true } }"
        >
        <div>
            <div v-if="validation_errors" class="alert alert-danger">
                <div v-for="(messages, field) in validation_errors" :key="field">
                    <div v-for="msg in messages" :key="msg">{{ msg }}</div>
                </div>
            </div>
            <form v-on:submit.prevent="edit_live_camera" id="edit_live_camera">
                <input type="text" class="form-control" v-model="data.name" name="Name" :placeholder="$t('common.name')" title="Name" required>

                <select class="form-control" v-model="data.source" name="source" required>
                    <option value="link">{{ $t('admin.live_camera.source_link') }}</option>
                    <option value="ubia">{{ $t('admin.live_camera.source_ubia') }}</option>
                    <option value="upload">{{ $t('admin.live_camera.source_upload') }}</option>
                </select>

                <input v-if="data.source === 'link'" type="link" class="form-control" v-model="data.link" name="link" :placeholder="$t('admin.live_camera.link_placeholder')" title="link" required>

                <template v-else-if="data.source === 'ubia'">
                    <input type="text" class="form-control" v-model="data.ubia_device_uid" name="ubia_device_uid" :placeholder="$t('admin.live_camera.device_uid_placeholder')" title="ubia_device_uid" required>
                    <button type="button" class="btn btn-secondary" @click="preview_ubia_camera" :disabled="!data.ubia_device_uid || preview_loading">
                        {{ $t('admin.live_camera.preview_button') }}
                    </button>
                    <div v-if="preview_photos !== null">
                        <p v-if="preview_photos.length === 0">{{ $t('admin.live_camera.preview_no_photos') }}</p>
                        <div v-else class="d-flex flex-wrap gap-2">
                            <div v-for="photo in preview_photos" :key="photo.image_url">
                                <img :src="photo.image_url" style="max-width: 120px; max-height: 90px;" />
                                <div><small>{{ photo.captured_at }}</small></div>
                            </div>
                        </div>
                    </div>
                </template>

                <div v-else>
                    <gallery_images_edit
                        :key="gallery_remount_key"
                        ref="gallery_manager"
                        @update_gallery_images="update_gallery_images"
                        :item_id_prop="editing_camera_id"
                        :image_path_prop="'images/live_cameras_img/'"
                        :image_del_route_prop="'/set_live_camera/delete_live_camera_photo/'"
                        :get_images_route_prop="'/set_live_camera/get_editing_live_camera_photos/'"
                        :title_prop="$t('admin.live_camera.upload_photos_label')"
                    />
                    <p v-if="uploading_photos" class="text-muted">{{ $t('admin.live_camera.uploading_status') }}</p>
                    <div v-else-if="upload_error" class="alert alert-danger">{{ upload_error }}</div>
                </div>

                <select class="form-control" v-model="data.published" name="published" required>
                    <option :value="0">{{ $t('admin.common.not_public') }}</option>
                    <option :value="1">{{ $t('admin.common.public') }}</option>
                </select>

                <select
                    class="form-control"
                    name="article_id"
                    v-model="data.article_id"
                    required
                >
                    <option
                        v-bind:value="0"
                        disabled
                    >
                        {{ $t('admin.routes_sectors.select_outdoor_area') }}
                    </option>
                    <option
                        v-for="region in regions"
                        :key="region"
                        v-bind:value="region.id"
                    >
                        {{ region.url_title }}
                    </option>
                </select>
            </form>
        </div>
    </stack-modal>
</template>

<script>
    import { SlickList, SlickItem } from 'vue-slicksort'; //https://github.com/Jexordexan/vue-slicksort
    // import StackModal from '@innologica/vue-stackable-modal'  // Global now
    import gallery_images_edit from '../../../gallery/galleryImageEditComponent.vue'
    export default {
        components: {
            // StackModal,
            SlickItem,
            SlickList,
            gallery_images_edit,
        },
        props: [
            'table_info',
        ],
        mounted(){
            // console.log(this.table_info)
            // this.get_region_data()
        },
        data(){
            return{
                is_live_camera_edit_model: false,
                is_live_camera_edit_model: false,

                editing_camera_id: 0,

                regions: [],
                data: {
                    name: '',
                    link: '',
                    source: 'link',
                    ubia_device_uid: '',
                    published: 0,
                    article_id: 0
                },
                preview_photos: null,
                preview_loading: false,
                validation_errors: null,

                pending_gallery_images: [],
                uploading_photos: false,
                upload_error: null,
                gallery_remount_key: 0,
            }
        },
        methods: {
            update_gallery_images(images){
                this.pending_gallery_images = images.map(image => image.image).filter(Boolean)

                // Auto-upload as soon as files are staged — there used to be a
                // separate manual "Upload" button here, but it went unnoticed
                // (people expect "add" to mean "saved"), so nothing ever
                // reached the server. Uploading immediately removes that gap.
                if (this.pending_gallery_images.length && !this.uploading_photos) {
                    this.upload_photos()
                }
            },
            upload_photos(){
                this.uploading_photos = true
                this.upload_error = null

                const formData = new FormData()
                formData.append('live_camera_id', this.editing_camera_id)
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
            preview_ubia_camera(){
                this.preview_loading = true
                this.preview_photos = null

                axios
                .post('/set_live_camera/preview_ubia_camera', { device_uid: this.data.ubia_device_uid })
                .then(response => {
                    this.preview_photos = response.data.photos
                })
                .catch(error => console.log(error))
                .finally(() => {
                    this.preview_loading = false
                })
            },
            get_region_data: function () {
                axios
                .get("/get_article/get_category_articles/ice")
                .then(response => {
                    this.regions = response.data
                })
            },
            get_editing_region_data: function () {
                axios
                .get("/set_live_camera/get_editing_live_camera/" + this.editing_camera_id)
                .then(response => {
                    this.data = response.data

                    this.is_live_camera_edit_model = true
                })
            },
            edit_live_camera(){
                    this.validation_errors = null

                    axios
                    .post('/set_live_camera/edit_live_camera/' + this.editing_camera_id, {
                        data: this.data,
                        _method: 'post',
                    })

                    .then(Response => {
                        alert(this.$t('admin.live_camera.updated_successfully'))
                        this.$emit('update')
                        this.is_live_camera_edit_model = false
                        this.clear_form(true)
                    })
                    .catch(error => {
                        if (error.response && error.response.status === 422) {
                            this.validation_errors = error.response.data.errors
                        } else {
                            console.log(error)
                        }
                    })
            },
            clear_form(){
                this.data = {
                    name: '',
                    link: '',
                    source: 'link',
                    ubia_device_uid: '',
                    published: 0,
                    article_id: 0
                }
                this.preview_photos = null
                this.validation_errors = null
                this.pending_gallery_images = []
                this.upload_error = null
                this.gallery_remount_key++
            },
            show_modal(id){
                // this.is_live_camera_edit_model = true

                this.editing_camera_id = id

                this.get_region_data()
                this.get_editing_region_data()
            },
            close_modal(is_back_action_query = false){
                if(is_back_action_query){
                    if(confirm(this.$t('admin.live_camera.confirm_close_form_discard'))){
                        this.is_live_camera_edit_model = false
                        this.clear_form()
                    }
                }else {
                    this.is_live_camera_edit_model = false
                    this.clear_form()
                }
            },
        }
    }
</script>

<style>

</style>