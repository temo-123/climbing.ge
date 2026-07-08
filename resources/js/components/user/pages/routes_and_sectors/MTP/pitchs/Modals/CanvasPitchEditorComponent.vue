<template>
    <div class="form-group clearfix row">
        <div class="col-md-12">
            <h5>{{ $t('admin.routes_sectors.pitch_drawing_editor_title') }}</h5>
            <p class="text-muted small">{{ $t('admin.routes_sectors.pitch_drawing_editor_hint') }}</p>
        </div>

        <div class="col-md-12" v-if="sector_id_prop">
            <button
                type="button"
                class="btn"
                :class="show_editor ? 'btn-danger' : 'btn-primary'"
                @click="toggleEditor"
            >
                {{ show_editor ? $t('admin.routes_sectors.close_pitch_editor') : $t('admin.routes_sectors.open_pitch_editor') }}
            </button>

            <StackModal
                :show="show_editor"
                :title="$t('admin.routes_sectors.pitch_drawing_editor_title')"
                size="fullscreen"
                :saveButton="{ visible: false }"
                :cancelButton="{ visible: false }"
                @close="show_editor = false"
            >
                <div v-if="show_editor && sector_images.length > 0">
                    <div class="tabs">
                        <input
                            v-for="(image, index) in sector_images"
                            :key="'input-' + image.id + '-' + index"
                            type="radio"
                            :id="'pitch-input-' + image.id"
                            :value="image.id"
                            v-model="images_tab_num"
                            @change="updateSectorImageId"
                        />
                        <label v-for="(image, index) in sector_images" :key="'label-' + image.id + '-' + index" :for="'pitch-input-' + image.id">
                            {{ $t('admin.routes_sectors.image_id_label') }} {{ image.id }}
                            <span v-if="image.has_original" class="badge badge-success ml-1" style="font-size:10px;">{{ $t('admin.routes_sectors.original_saved') }}</span>
                            <img
                                :src="'/public/images/sector_img/' + image.image"
                                :alt="'Sector Image ' + image.id"
                                class="img-thumbnail"
                                style="max-width: 100px; max-height: 100px; margin-left: 10px;" />
                        </label>
                    </div>

                    <div class="col-md-12 mt-2 mb-2">
                        <button
                            type="button"
                            class="btn btn-success"
                            :disabled="drawing_saving"
                            @click="savePitchDrawing"
                        >
                            <i class="fa fa-save"></i>
                            {{ drawing_saving ? $t('admin.routes_sectors.saving_ellipsis') : $t('admin.routes_sectors.save_drawing') }}
                        </button>
                        <button
                            type="button"
                            class="btn btn-danger ml-2"
                            :disabled="drawing_deleting"
                            @click="deletePitchDrawing"
                        >
                            <i class="fa fa-trash"></i>
                            {{ drawing_deleting ? $t('admin.routes_sectors.deleting_ellipsis') : $t('admin.routes_sectors.delete_drawing') }}
                        </button>
                        <span v-if="drawing_save_status" class="ml-2" :class="drawing_save_status === 'ok' ? 'text-success' : 'text-danger'">
                            {{ drawing_save_status === 'ok' ? '✓ ' + $t('admin.routes_sectors.drawing_saved') : drawing_save_status === 'deleted' ? '✓ ' + $t('admin.routes_sectors.drawing_deleted') : '✗ ' + $t('admin.routes_sectors.error') }}
                        </span>
                    </div>

                    <Editor
                        ref="editorRef"
                        :image_prop="getSectorImage()"
                        :json_prop="pitch_json_prop"
                        :json_meta="pitch_json_meta_prop"
                        :related_jsons="related_jsons"
                        :related_jsons_meta="related_jsons_meta"
                        :route_name="'Pitch ' + pitch_id_prop"
                        @canvas_data="handleCanvasData"
                    />
                </div>
            </StackModal>
        </div>
    </div>
</template>

<script>
import Editor from '../../../../../items/canvas/EditorComponent.vue'

export default {
    components: { Editor },
    props: {
        pitch_id_prop:        { default: null },
        sector_id_prop:       { default: '' },
        pitch_json_prop:      { default: null },
        pitch_json_meta_prop: { type: Object, default: () => null },
        sector_image_id_prop: { default: '' },
    },
    emits: ['update:pitch_json_prop', 'update:sector_image_id_prop'],
    data() {
        return {
            show_editor: false,
            sector_images: [],
            images_tab_num: '',
            related_jsons: [],
            related_jsons_meta: [],
            drawing_saving: false,
            drawing_deleting: false,
            drawing_save_status: null,
            canvas_json: null,
        }
    },
    watch: {
        sector_id_prop(newVal) {
            if (newVal) this.get_sector_images(newVal);
        },
        sector_image_id_prop(newVal) {
            if (newVal && !this.images_tab_num) this.images_tab_num = newVal;
        },
    },
    mounted() {
        if (this.sector_image_id_prop) this.images_tab_num = this.sector_image_id_prop;
        if (this.sector_id_prop) this.get_sector_images(this.sector_id_prop);
    },
    methods: {
        toggleEditor() { this.show_editor = !this.show_editor; },

        getSectorImage() {
            if (!this.sector_images.length) return null;
            const img = this.sector_images.find(i => i.id === this.images_tab_num) || this.sector_images[0];
            const dir = img.has_original ? '/public/images/sector_img/origin_img/' : '/public/images/sector_img/';
            return dir + img.image;
        },
        getSectorImageThumb(image) {
            return '/public/images/sector_img/' + image.image;
        },
        handleCanvasData(canvasData) {
            this.canvas_json = canvasData;
        },
        updateSectorImageId() {
            this.$emit('update:sector_image_id_prop', this.images_tab_num);
            this.get_related_jsons(this.images_tab_num);
        },
        get_sector_images(sectorId) {
            axios.get('/get_sector/get_sector_images/' + sectorId)
                .then(response => {
                    this.sector_images = response.data;
                    if (!this.images_tab_num && this.sector_images.length > 0) {
                        this.images_tab_num = this.sector_images[0].id;
                    }
                    if (this.images_tab_num) this.get_related_jsons(this.images_tab_num);
                })
                .catch(() => {});
        },
        get_related_jsons(sectorImageId) {
            axios.get('/set_mtp/set_mtp_pitch/get_pitch_jsons_for_sector_image', {
                params: { sector_image_id: sectorImageId, exclude_pitch_id: this.pitch_id_prop }
            })
                .then(response => {
                    const items = response.data || [];
                    this.related_jsons = items.map(i => i.json);
                    this.related_jsons_meta = items;
                })
                .catch(() => {});
        },

        // The background photo's own actual position + size within the Paper.js
        // view — needed so the editor can rescale saved strokes onto the current
        // background fit next time this pitch is reopened in a differently-sized
        // container (mirrors CanvasRouteEditorComponent.vue's bgBoundsPayload).
        bgBoundsPayload(canvasContainer) {
            const bounds = canvasContainer && typeof canvasContainer.getBackgroundBounds === 'function'
                ? canvasContainer.getBackgroundBounds()
                : null;
            return {
                bg_left:   bounds ? bounds.left   : null,
                bg_top:    bounds ? bounds.top    : null,
                bg_width:  bounds ? bounds.width  : null,
                bg_height: bounds ? bounds.height : null,
            };
        },

        captureAllDrawingStrokes(canvasContainer) {
            const scope = canvasContainer.getCanvasScope();
            if (!scope) return null;

            const savedZoom    = scope.view.zoom;
            const savedCenterX = scope.view.center.x;
            const savedCenterY = scope.view.center.y;
            canvasContainer.updateView(1, { x: 0, y: 0 });

            const bgLayer       = scope.project.layers.find(l => l.name === 'background');
            const relatedLayers = scope.project.layers.filter(l => l.name && l.name.startsWith('related-'));

            const wasBgVisible = bgLayer ? bgLayer.visible : false;
            if (bgLayer) bgLayer.visible = false;

            const relatedWasVisible = relatedLayers.map(l => l.visible);
            relatedLayers.forEach(l => { l.visible = false; });

            const tempLayers = [];
            if (this.related_jsons && this.related_jsons.length > 0) {
                scope.activate();
                this.related_jsons.forEach(jsonData => {
                    if (!jsonData) return;
                    try {
                        let parsed = typeof jsonData === 'string' ? JSON.parse(jsonData) : jsonData;
                        if (typeof parsed === 'string') parsed = JSON.parse(parsed);
                        const before = scope.project.layers.length;
                        scope.project.importJSON(parsed);
                        scope.project.layers.slice(before).forEach(l => {
                            if (l.name === 'background') { l.remove(); return; }
                            l.name = 'temp-capture';
                            tempLayers.push(l);
                        });
                    } catch (_) {}
                });
            }

            scope.view.update();
            const canvas = canvasContainer.$refs.canvasManager.$el;
            const dataUrl = canvas.toDataURL('image/png');

            tempLayers.forEach(l => { try { l.remove(); } catch (_) {} });
            if (bgLayer) bgLayer.visible = wasBgVisible;
            relatedLayers.forEach((l, i) => { l.visible = relatedWasVisible[i]; });
            const vs = scope.view.viewSize;
            canvasContainer.updateView(savedZoom, {
                x: savedCenterX - vs.width / 2,
                y: savedCenterY - vs.height / 2,
            });
            scope.view.update();

            return dataUrl;
        },

        // Renders at the PHOTO's own native resolution, not the browser's current
        // on-screen canvas size — otherwise every save silently downscales the sector
        // image to whatever width the editor happened to be rendered at.
        compositeImages(bgPath, drawingDataUrl, paperCanvas) {
            return new Promise((resolve) => {
                const drawStrokesThenResolve = (ctx, w, h) => {
                    if (!drawingDataUrl) { resolve(ctx.canvas.toDataURL('image/jpeg', 0.9)); return; }
                    const si = new Image();
                    si.onload  = () => { ctx.drawImage(si, 0, 0, w, h); resolve(ctx.canvas.toDataURL('image/jpeg', 0.9)); };
                    si.onerror = () => resolve(ctx.canvas.toDataURL('image/jpeg', 0.9));
                    si.src = drawingDataUrl;
                };
                const fallback = () => {
                    const w = paperCanvas.width, h = paperCanvas.height;
                    const offscreen = document.createElement('canvas');
                    offscreen.width = w; offscreen.height = h;
                    drawStrokesThenResolve(offscreen.getContext('2d'), w, h);
                };

                if (!bgPath) { fallback(); return; }
                const bg = new Image();
                bg.onload = () => {
                    const w = bg.naturalWidth  || paperCanvas.width;
                    const h = bg.naturalHeight || paperCanvas.height;
                    const offscreen = document.createElement('canvas');
                    offscreen.width = w; offscreen.height = h;
                    const ctx = offscreen.getContext('2d');
                    ctx.drawImage(bg, 0, 0, w, h);
                    drawStrokesThenResolve(ctx, w, h);
                };
                bg.onerror = fallback;
                bg.src = bgPath;
            });
        },

        async savePitchDrawing() {
            if (!this.images_tab_num) { alert(this.$t('admin.routes_sectors.select_sector_image_first')); return; }
            if (!this.$refs.editorRef) { alert(this.$t('admin.routes_sectors.editor_not_open')); return; }

            this.drawing_saving = true;
            this.drawing_save_status = null;

            try {
                const canvasContainer = this.$refs.editorRef.$refs.canvasContainer;

                let json = this.pitch_json_prop;
                if (canvasContainer && typeof canvasContainer.getCleanJson === 'function') {
                    const cleanJson = canvasContainer.getCleanJson();
                    if (cleanJson) { json = cleanJson; this.$emit('update:pitch_json_prop', json); }
                }
                if (!json) { alert(this.$t('admin.routes_sectors.no_drawing_data_found')); return; }

                const selectedImage = this.sector_images.find(img => img.id === this.images_tab_num);
                const bgPath = selectedImage && selectedImage.has_original
                    ? '/public/images/sector_img/origin_img/' + selectedImage.image
                    : '/public/images/sector_img/' + (selectedImage ? selectedImage.image : '');

                const drawingDataUrl = this.captureAllDrawingStrokes(canvasContainer);
                const editedImageData = await this.compositeImages(bgPath, drawingDataUrl,
                    canvasContainer.$refs.canvasManager.$el);

                const scope = canvasContainer.getCanvasScope();
                const canvasWidth  = scope && scope.view ? Math.round(scope.view.viewSize.width)  : null;
                const canvasHeight = scope && scope.view ? Math.round(scope.view.viewSize.height) : null;

                const response = await axios.post('/set_mtp/set_mtp_pitch/save_pitch_drawing', {
                    pitch_id:        this.pitch_id_prop,
                    sector_image_id: this.images_tab_num,
                    json,
                    edited_image:    editedImageData,
                    canvas_width:    canvasWidth,
                    canvas_height:   canvasHeight,
                    ...this.bgBoundsPayload(canvasContainer),
                });

                if (response.data.success) {
                    this.drawing_save_status = 'ok';
                    if (selectedImage) selectedImage.has_original = true;
                    setTimeout(() => { this.drawing_save_status = null; }, 3000);
                } else {
                    this.drawing_save_status = 'error';
                }
            } catch (e) {
                this.drawing_save_status = 'error';
            } finally {
                this.drawing_saving = false;
            }
        },

        async deletePitchDrawing() {
            if (!this.pitch_id_prop) return;
            if (!confirm(this.$t('admin.routes_sectors.confirm_delete_pitch_drawing'))) return;

            this.drawing_deleting = true;
            this.drawing_save_status = null;
            try {
                await axios.delete('/set_mtp/set_mtp_pitch/del_pitch_drawing/' + this.pitch_id_prop);
                this.$emit('update:pitch_json_prop', null);
                this.drawing_save_status = 'deleted';
                setTimeout(() => { this.drawing_save_status = null; }, 3000);
            } catch (e) {
                this.drawing_save_status = 'error';
            } finally {
                this.drawing_deleting = false;
            }
        },
    }
}
</script>
