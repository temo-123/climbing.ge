<template>
    <stack-modal
            :show="is_live_camera_add_model"
            :title="$t('admin.live_camera.add_live_camera_title')"
            @close="close_modal"
            @save="add_live_camera"
            :saveButton="{ visible: true, title: $t('admin.live_camera.add_live_camera_title'), btnClass: { 'btn btn-primary': true } }"
            :cancelButton="{ visible: false, title: $t('common.close'), btnClass: { 'btn btn-danger': true } }"
        >
        <div>
            <div v-if="validation_errors" class="alert alert-danger">
                <div v-for="(messages, field) in validation_errors" :key="field">
                    <div v-for="msg in messages" :key="msg">{{ msg }}</div>
                </div>
            </div>
            <form v-on:submit.prevent="add_live_camera" id="add_live_camera">
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

                <p v-else class="text-muted">{{ $t('admin.live_camera.upload_photos_hint') }}</p>

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
    export default {
        components: {
            // StackModal,
            SlickItem,
            SlickList,
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
                is_live_camera_add_model: false,

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
            }
        },
        methods: {
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

                    this.is_live_camera_add_model = true
                })
            },
            add_live_camera(){
                    this.validation_errors = null

                    axios
                    .post('/set_live_camera/add_live_camera/', {
                        data: this.data,

                        _method: 'Post'
                    })
                    .then(Response => {
                        alert(this.$t('admin.live_camera.added_successfully'))
                        this.$emit('update')
                        this.is_live_camera_add_model = false
                        this.clear_form()
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
            },
            show_modal(){
                this.get_region_data()
            },
            close_modal(is_back_action_query = false){
                if(is_back_action_query){
                    if(confirm(this.$t('admin.live_camera.confirm_close_form_discard'))){
                        this.is_live_camera_add_model = false
                        this.clear_form()
                    }
                }else {
                    this.is_live_camera_add_model = false
                    this.clear_form()
                }
            },
        }
    }
</script>

<style>

</style>