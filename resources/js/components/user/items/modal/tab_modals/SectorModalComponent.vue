<template>
    <StackModal
        v-model="is_show_sector_modal"
        :title="$t('admin.users.sector_title')"
        @close="close_sector_model()"
        @save="save_routes_sequence"
        :modal-class="{ [SectorModalClass]: true }"
        :saveButton="{ visible: true, title: $t('admin.users.save_sequence_btn') }"
        :cancelButton="{ title: $t('common.close'), btnClass: { 'btn btn-primary': true } }">

        <div v-show="is_show_sector_modal" class="root">
            <div class="col-md-12">

                <!-- Edit link -->
                <div class="row mb-2">
                    <router-link
                        class="btn btn-primary btn-sm"
                        :to="{ name: 'sectorEdit', params: { id: activ_sector_id }, query: { locale: 'en' } }"
                    >
                        <i class="fa fa-pencil"></i> {{ $t('admin.users.edit_sector_btn') }}
                    </router-link>
                </div>

                <!-- Sector images -->
                <div v-if="sector_images.length > 0" class="row mb-3">
                    <h4>{{ $t('admin.users.images_title') }} <small class="text-muted">{{ $t('admin.users.drag_to_reorder_hint') }}</small></h4>
                    <table class="table table-sm drag-table">
                        <thead>
                            <tr>
                                <td>{{ $t('common.id') }}</td>
                                <td>{{ $t('admin.users.col_num') }}</td>
                                <td>{{ $t('admin.users.canvas_drawing_col') }} <small class="text-muted">{{ $t('admin.users.json_data_hint') }}</small></td>
                                <td>{{ $t('admin.users.saved_image_col') }} <small class="text-muted">{{ $t('admin.users.on_disk_hint') }}</small></td>
                                <td>{{ $t('admin.users.actions_col') }}</td>
                            </tr>
                        </thead>
                        <tbody>
                            <tr
                                v-for="(image, index) in sector_images"
                                :key="image.id || index"
                                draggable="true"
                                :class="{ 'drag-over': dragOver === 'images-' + index }"
                                @dragstart="onDragStart('images', index)"
                                @dragover.prevent="dragOver = 'images-' + index"
                                @dragleave="dragOver = null"
                                @drop.prevent="onDrop('images', index)"
                                @dragend="dragOver = null"
                            >
                                <td>{{ image.id }}</td>
                                <td>{{ image.num }}</td>

                                <!-- Canvas drawing: routes from JSON on original photo (or dark bg if no original) -->
                                <td @click.stop class="img-cell">
                                    <canvas-json-show
                                        :fetch_url="'get_route/get_route_jsons_for_sector_image'"
                                        :fetch_id="image.id"
                                        :image_src="image.has_original
                                            ? '/public/images/sector_img/origin_img/' + image.image
                                            : null"
                                        :preview_all="true"
                                        refresh_event="route-drawing-updated"
                                    />
                                </td>

                                <!-- Saved composite image currently on disk -->
                                <td class="img-cell">
                                    <img
                                        class="image_in_model_tab"
                                        :src="'/public/images/sector_img/' + image.image + '?v=' + (img_cache_bust[image.id] || 0)"
                                        :alt="image.image"
                                    />
                                </td>

                                <!-- Actions: del canvas + upload -->
                                <td @click.stop class="actions-cell">

                                    <!-- Del Canvas: inline two-step confirmation -->
                                    <div class="mb-1">
                                        <template v-if="canvas_confirm_pending === image.id">
                                            <span class="text-warning small me-1">{{ $t('admin.users.sure_question') }}</span>
                                            <button
                                                class="btn btn-danger btn-sm me-1"
                                                :disabled="canvas_deleting === image.id"
                                                @click.stop="do_del_sector_canvas(image.id)"
                                            >{{ $t('admin.export.yes_label') }}</button>
                                            <button
                                                class="btn btn-secondary btn-sm"
                                                @click.stop="canvas_confirm_pending = null"
                                            >{{ $t('admin.site_info.no_label') }}</button>
                                        </template>
                                        <template v-else>
                                            <button
                                                class="btn btn-warning btn-sm"
                                                :disabled="canvas_deleting === image.id"
                                                @click.stop="canvas_confirm_pending = image.id"
                                            >
                                                <i class="fa fa-eraser"></i>
                                                {{ canvas_deleting === image.id ? $t('admin.users.deleting_ellipsis') : $t('admin.users.del_canvas_btn') }}
                                            </button>
                                        </template>

                                        <span
                                            v-if="canvas_del_status[image.id]"
                                            class="ms-1 small"
                                            :class="canvas_del_status[image.id] === 'ok' ? 'text-success' : 'text-danger'"
                                        >
                                            {{ canvas_del_status[image.id] === 'ok' ? $t('admin.users.cleared_status') : $t('admin.users.error_status') }}
                                        </span>
                                    </div>

                                    <!-- Upload new image (always visible) -->
                                    <div class="upload-wrap">
                                        <label class="small text-muted mb-0">{{ $t('admin.users.replace_image_colon_label') }}</label>
                                        <input
                                            type="file"
                                            accept="image/*"
                                            class="form-control form-control-sm mt-1"
                                            :disabled="img_uploading === image.id"
                                            @change="uploadSectorImage(image.id, $event)"
                                        />
                                        <span
                                            v-if="img_upload_status[image.id]"
                                            class="small"
                                            :class="img_upload_status[image.id] === 'ok' ? 'text-success' : 'text-danger'"
                                        >
                                            {{ img_upload_status[image.id] === 'ok' ? $t('admin.users.uploaded_status') : $t('admin.users.upload_failed_status') }}
                                        </span>
                                        <span v-if="img_uploading === image.id" class="small text-muted">{{ $t('admin.users.uploading_ellipsis') }}</span>
                                    </div>

                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Sport routes -->
                <div v-if="sector_routes.length > 0" class="row mb-3">
                    <h4>{{ $t('admin.users.sport_climbing_routes_title') }} <small class="text-muted">{{ $t('admin.users.drag_to_reorder_hint') }}</small></h4>
                    <table class="table table-sm drag-table">
                        <thead><tr><td>{{ $t('common.id') }}</td><td>{{ $t('admin.users.col_num') }}</td><td>{{ $t('common.name') }}</td><td>{{ $t('common.grade') }}</td><td>{{ $t('common.edit') }}</td></tr></thead>
                        <tbody>
                            <tr
                                v-for="(route, index) in sector_routes"
                                :key="route.id || index"
                                draggable="true"
                                :class="{ 'drag-over': dragOver === 'routes-' + index }"
                                @dragstart="onDragStart('routes', index)"
                                @dragover.prevent="dragOver = 'routes-' + index"
                                @dragleave="dragOver = null"
                                @drop.prevent="onDrop('routes', index)"
                                @dragend="dragOver = null"
                            >
                                <td>{{ route.id }}</td>
                                <td>{{ route.num }}</td>
                                <td>{{ route.name }}</td>
                                <td>{{ route.grade }}{{ route.or_grade ? ' / ' + route.or_grade : '' }}</td>
                                <td><router-link class="btn btn-primary btn-sm" :to="{ name: 'routeEdit', params: { id: route.id } }">{{ $t('common.edit') }}</router-link></td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Multi-pitches -->
                <div v-if="sector_mtps.length > 0" class="row mb-3">
                    <h4>{{ $t('admin.users.multi_pitches_title') }} <small class="text-muted">{{ $t('admin.users.drag_to_reorder_hint') }}</small></h4>
                    <table class="table table-sm drag-table">
                        <thead><tr><td>{{ $t('common.id') }}</td><td>{{ $t('admin.users.col_num') }}</td><td>{{ $t('common.name') }}</td><td>{{ $t('common.edit') }}</td></tr></thead>
                        <tbody>
                            <tr
                                v-for="(mtp, index) in sector_mtps"
                                :key="mtp.id || index"
                                draggable="true"
                                :class="{ 'drag-over': dragOver === 'mtps-' + index }"
                                @dragstart="onDragStart('mtps', index)"
                                @dragover.prevent="dragOver = 'mtps-' + index"
                                @dragleave="dragOver = null"
                                @drop.prevent="onDrop('mtps', index)"
                                @dragend="dragOver = null"
                            >
                                <td>{{ mtp.id }}</td>
                                <td>{{ mtp.num }}</td>
                                <td>{{ mtp.name }}</td>
                                <td><router-link class="btn btn-primary btn-sm" :to="{ name: 'MTPEdit', params: { id: mtp.id } }">{{ $t('common.edit') }}</router-link></td>
                            </tr>
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </StackModal>
</template>

<script>
export default {
    data(){
            return {
                is_show_sector_modal: false,
                sector: [],
                SectorModalClass: 'modal-xxxl',
                sector_routes: [],
                sector_mtps: [],
                sector_images: [],
                activ_sector_id: 0,
                dragOver: null,
                dragSrc: null,
                canvas_confirm_pending: null,
                canvas_deleting:   null,
                canvas_del_status: {},
                img_uploading:     null,
                img_upload_status: {},
                img_cache_bust:    {},
            }
        },

        methods: {
            show_sector_modal(sector_id){
                this.activ_sector_id = sector_id
                this.is_show_sector_modal = true
                axios
                    .get('/get_sector/get_sector_data_for_model/' + sector_id)
                    .then(response => {
                        this.sector = response.data
                        this.sector_images = this.sector.images || []
                        this.sector_routes = this.sector.routes || []
                        this.sector_mtps   = this.sector.mtps   || []
                    })
                    .catch(error => console.log(error))
            },

            close_sector_model(){
                this.is_show_sector_modal = false
                this.activ_sector_id = 0
                this.canvas_confirm_pending = null
                this.$nextTick(() => {
                    this.sector_routes = []
                    this.sector_mtps   = []
                    this.sector_images = []
                })
            },

            onDragStart(list, index) {
                this.dragSrc = { list, index }
            },

            onDrop(list, toIndex) {
                if (!this.dragSrc || this.dragSrc.list !== list) return
                const from = this.dragSrc.index
                if (from === toIndex) return
                const arr = list === 'routes' ? this.sector_routes
                          : list === 'mtps'   ? this.sector_mtps
                          :                     this.sector_images
                const moved = arr.splice(from, 1)[0]
                arr.splice(toIndex, 0, moved)
                this.dragSrc = null
                this.dragOver = null
            },

            async do_del_sector_canvas(image_id) {
                this.canvas_confirm_pending = null;
                this.canvas_deleting = image_id;
                this.canvas_del_status = { ...this.canvas_del_status, [image_id]: null };
                try {
                    await axios.delete('/set_route/del_sector_image_drawing/' + image_id);
                    this.canvas_del_status = { ...this.canvas_del_status, [image_id]: 'ok' };
                    // Update has_original so canvas drawing switches to dark background
                    const img = this.sector_images.find(i => i.id === image_id);
                    if (img) img.has_original = false;
                    this.$bus.$emit('route-drawing-updated', { sector_image_id: image_id });
                    setTimeout(() => {
                        this.canvas_del_status = { ...this.canvas_del_status, [image_id]: null };
                    }, 4000);
                } catch (e) {
                    this.canvas_del_status = { ...this.canvas_del_status, [image_id]: 'error' };
                } finally {
                    this.canvas_deleting = null;
                }
            },

            async uploadSectorImage(image_id, event) {
                const file = event.target.files[0];
                if (!file) return;

                this.img_uploading = image_id;
                this.img_upload_status = { ...this.img_upload_status, [image_id]: null };

                const formData = new FormData();
                formData.append('image', file);

                try {
                    const res = await axios.post('/set_sector/replace_sector_image/' + image_id, formData, {
                        headers: { 'Content-Type': 'multipart/form-data' },
                    });
                    if (res.data && res.data.image) {
                        const img = this.sector_images.find(i => i.id === image_id);
                        if (img) {
                            img.image = res.data.image;
                            img.has_original = false;
                        }
                        // Bust the cache so the new image loads
                        this.img_cache_bust = { ...this.img_cache_bust, [image_id]: Date.now() };
                    }
                    this.img_upload_status = { ...this.img_upload_status, [image_id]: 'ok' };
                    setTimeout(() => {
                        this.img_upload_status = { ...this.img_upload_status, [image_id]: null };
                    }, 4000);
                } catch (e) {
                    this.img_upload_status = { ...this.img_upload_status, [image_id]: 'error' };
                } finally {
                    this.img_uploading = null;
                    event.target.value = '';
                }
            },

            save_routes_sequence(){
                axios
                    .post('/set_sector/routes_sequence/', {
                        routes_sequence:         this.sector_routes,
                        mtp_sequence:            this.sector_mtps,
                        sector_images_sequence:  this.sector_images,
                    })
                    .then(() => this.close_sector_model())
                    .catch(error => console.log(error))
            },
        }
    }
</script>

<style scoped>
.drag-table tbody tr {
    cursor: grab;
    user-select: none;
}
.drag-table tbody tr.drag-over {
    background: #d0e8ff;
    outline: 2px dashed #0d6efd;
}
.image_in_model_tab {
    width: 100%;
    height: auto;
    display: block;
    border-radius: 4px;
}
.img-cell {
    width: 280px;
    min-width: 200px;
    vertical-align: top;
}
.actions-cell {
    min-width: 200px;
    vertical-align: top;
    padding: 8px;
}
.upload-wrap {
    margin-top: 6px;
}
</style>
