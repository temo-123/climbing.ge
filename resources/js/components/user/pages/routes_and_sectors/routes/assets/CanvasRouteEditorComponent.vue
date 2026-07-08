<template>
    <div class="form-group clearfix row">
        <div class="col-md-12">
            <h4>{{ $t('admin.routes_sectors.route_drawing_editor_title') }}</h4>
            <p class="text-muted">{{ $t('admin.routes_sectors.route_drawing_editor_hint') }}</p>
        </div>

        <div class="form-group clearfix row" v-if="show_alert_prop">
          <div role="alert" class="alert alert-danger cursor_pointer">
            <div class="row">
              <div class="col-md-12">
                <p>{{ $t('admin.routes_sectors.no_drawing_yet_alert') }}</p>
              </div>
            </div>
          </div>
        </div>

        <div class="form-group clearfix row" v-if="sector_id_prop != ''">
          <div class="col-md-12">
            <div class="row">
                <div class="col-md-12">
                    <button
                        type="button"
                        class="btn"
                        :class="show_editor ? 'btn-danger' : 'btn-primary'"
                        @click="toggleEditor"
                    >
                        {{ show_editor ? $t('admin.routes_sectors.close_editor') : $t('admin.routes_sectors.open_editor') }}
                    </button>
                </div>
            </div>

            <StackModal
                :show="show_editor"
                :title="$t('admin.routes_sectors.editor_title')"
                size="fullscreen"
                :saveButton="{ visible: false }"
                :cancelButton="{ visible: false }"
                @close="show_editor = false"
            >
              <div class="" v-if="show_editor && sector_images.length > 0">
                  <div class="tabs">
                      <input
                          v-for="(image, index) in sector_images"
                          :key="'input-' + image.id + '-' + index"
                          type="radio"
                          :id="'input-' + image.id"
                          :value="image.id"
                          v-model="images_tab_num"
                          @change="updateSectorImageId"
                      />
                      <label v-for="(image, index) in sector_images" :key="'label-' + image.id + '-' + index" :for="'input-' + image.id">
                          {{ $t('admin.routes_sectors.image_id_label') }} {{ image.id }}
                          <span v-if="image.has_original" class="badge badge-success ml-1" style="font-size:10px;">{{ $t('admin.routes_sectors.original_saved') }}</span>
                          <img
                              :src="getSectorImageThumb(image)"
                              :alt="'Sector Image ' + image.id"
                              class="img-thumbnail"
                              style="max-width: 100px; max-height: 100px; margin-left: 10px;" />
                      </label>
                  </div>

                  <div class="col-md-12 mt-2 mb-2">
                      <button
                          type="button"
                          class="btn"
                          :class="extra_drawing_mode ? 'btn-info' : 'btn-outline-info'"
                          :disabled="extra_drawing_loading"
                          @click="toggleExtraDrawingMode"
                      >
                          <i class="fa fa-map-marker"></i>
                          {{ extra_drawing_loading ? $t('admin.routes_sectors.loading_ellipsis') : (extra_drawing_mode ? $t('admin.routes_sectors.extra_drawing_mode_on') : $t('admin.routes_sectors.add_extra_drawing')) }}
                      </button>
                      <button
                          type="button"
                          class="btn btn-success ml-2"
                          :disabled="drawing_saving"
                          @click="saveRouteDrawing"
                      >
                          <i class="fa fa-save"></i>
                          {{ drawing_saving ? $t('admin.routes_sectors.saving_ellipsis') : (extra_drawing_mode ? $t('admin.routes_sectors.save_extra_drawing') : $t('admin.routes_sectors.save_drawing')) }}
                      </button>
                      <button
                          type="button"
                          class="btn btn-danger ml-2"
                          :disabled="drawing_deleting"
                          @click="deleteRouteDrawing"
                      >
                          <i class="fa fa-trash"></i>
                          {{ drawing_deleting ? $t('admin.routes_sectors.deleting_ellipsis') : (extra_drawing_mode ? $t('admin.routes_sectors.delete_extra_drawing') : $t('admin.routes_sectors.delete_drawing')) }}
                      </button>
                      <span v-if="drawing_save_status" class="ml-2" :class="drawing_save_status === 'ok' ? 'text-success' : 'text-danger'">
                          {{ drawing_save_status === 'ok' ? '✓ ' + $t('admin.routes_sectors.drawing_saved') : drawing_save_status === 'deleted' ? '✓ ' + $t('admin.routes_sectors.drawing_deleted') : '✗ ' + $t('admin.routes_sectors.error') }}
                      </span>
                      <p v-if="extra_drawing_mode" class="text-muted mt-1 mb-0" style="font-size:12px;">
                          {{ $t('admin.routes_sectors.extra_drawing_mode_hint') }}
                      </p>
                  </div>

                  <Editor
                    ref="editorRef"
                    :image_prop="getSectorImage()"
                    :json_prop="activeJsonProp"
                    :json_meta="activeJsonMeta"
                    :related_jsons="related_jsons"
                    :related_jsons_meta="related_jsons_meta"
                    :route_name="extra_drawing_mode ? 'extra info' : route_name_prop"
                    @canvas_data="handleCanvasData"
                  />
              </div>
            </StackModal>

            <div class="row" v-if="!show_editor">
                <div class="col-md-12 text-center">
                    <p>{{ $t('admin.routes_sectors.click_open_editor_hint') }}</p>
                </div>
            </div>
            <div class="row" v-else>
                <div class="col-md-12 text-center">
                    <p>{{ $t('admin.routes_sectors.loading_sector_image') }}</p>
                </div>
            </div>
          </div>
        </div>
    </div>
</template>

<script>
import Editor from '../../../../items/canvas/EditorComponent.vue'

export default {
    components: { Editor },
    props: {
        sector_id_prop: { default: '' },
        route_json_prop: { default: null },
        show_alert_prop: { default: false },
        sector_image_id_prop: { default: '' },
        route_id_prop: { default: null },
        route_name_prop: { default: '' },
        json_meta_prop: { type: Object, default: () => null },
    },
    emits: ['update:route_json_prop', 'update:sector_image_id_prop'],
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
            // "Extra drawing" mode: a general annotation layer tied only to the
            // sector image (not this route) — see SectorImageExtraDrawing.
            extra_drawing_mode: false,
            extra_drawing_json: null,
            extra_drawing_meta: null,
            extra_drawing_loading: false,
        }
    },
    computed: {
        // What the Editor actually shows/edits — the route's own drawing
        // normally, or the image's general extra-info layer while that mode
        // is toggled on.
        activeJsonProp() {
            return this.extra_drawing_mode ? this.extra_drawing_json : this.route_json_prop;
        },
        activeJsonMeta() {
            return this.extra_drawing_mode ? this.extra_drawing_meta : this.json_meta_prop;
        },
    },
    watch: {
        sector_id_prop(newVal) {
            if (newVal) {
                this.get_sector_images(newVal);
            }
        },
        sector_image_id_prop(newVal) {
            if (newVal && !this.images_tab_num) {
                this.images_tab_num = newVal;
            }
        },
    },
    mounted() {
        if (this.sector_image_id_prop) {
            this.images_tab_num = this.sector_image_id_prop;
        }
        if (this.sector_id_prop) {
            this.get_sector_images(this.sector_id_prop);
        }
    },
    methods: {
        toggleEditor() {
            this.show_editor = !this.show_editor;
        },
        // Switches the editor between "this route's drawing" and the image's
        // general extra-info layer — fetches the extra drawing once per
        // sector image the first time it's needed, same as related_jsons does.
        async toggleExtraDrawingMode() {
            if (!this.extra_drawing_mode && !this.images_tab_num) {
                alert(this.$t('admin.routes_sectors.select_sector_image_first'));
                return;
            }
            if (!this.extra_drawing_mode) {
                this.extra_drawing_loading = true;
                try {
                    const response = await axios.get('/set_sector/set_sector_image_extra_drawing/get_for_editor/' + this.images_tab_num);
                    const drawing = response.data && response.data.extra_drawing;
                    this.extra_drawing_json = drawing ? drawing.json : null;
                    this.extra_drawing_meta = drawing ? {
                        canvas_width: drawing.canvas_width, canvas_height: drawing.canvas_height,
                        bg_left: drawing.bg_left, bg_top: drawing.bg_top,
                        bg_width: drawing.bg_width, bg_height: drawing.bg_height,
                    } : null;
                } catch (e) {
                    this.extra_drawing_json = null;
                    this.extra_drawing_meta = null;
                } finally {
                    this.extra_drawing_loading = false;
                }
            }
            this.extra_drawing_mode = !this.extra_drawing_mode;
        },
        getSectorImage() {
            if (this.sector_images.length > 0) {
                const img = this.sector_images.find(i => i.id === this.images_tab_num) || this.sector_images[0];
                const dir = img.has_original
                    ? '/public/images/sector_img/origin_img/'
                    : '/public/images/sector_img/';
                return dir + img.image;
            }
            return null;
        },
        getSectorImageThumb(image) {
            return '/public/images/sector_img/' + image.image;
        },
        handleCanvasData(canvasData) {
            if (this.extra_drawing_mode) {
                this.extra_drawing_json = canvasData;
            } else {
                this.$emit('update:route_json_prop', canvasData);
            }
        },
        updateSectorImageId() {
            this.$emit('update:sector_image_id_prop', this.images_tab_num);
            this.get_related_routes_jsons(this.images_tab_num, this.route_id_prop);
            // Switching images while in extra-drawing mode must reload that
            // OTHER image's own extra drawing, not keep showing the previous
            // image's annotations on the new background.
            if (this.extra_drawing_mode) {
                this.extra_drawing_json = null;
                this.extra_drawing_meta = null;
                axios.get('/set_sector/set_sector_image_extra_drawing/get_for_editor/' + this.images_tab_num)
                    .then(response => {
                        const drawing = response.data && response.data.extra_drawing;
                        this.extra_drawing_json = drawing ? drawing.json : null;
                        this.extra_drawing_meta = drawing ? {
                            canvas_width: drawing.canvas_width, canvas_height: drawing.canvas_height,
                            bg_left: drawing.bg_left, bg_top: drawing.bg_top,
                            bg_width: drawing.bg_width, bg_height: drawing.bg_height,
                        } : null;
                    })
                    .catch(() => { this.extra_drawing_json = null; this.extra_drawing_meta = null; });
            }
        },
        get_sector_images(sectorId) {
            axios.get('/get_sector/get_sector_images/' + sectorId)
                .then(response => {
                    this.sector_images = response.data;
                    if (!this.images_tab_num && this.sector_images.length > 0) {
                        this.images_tab_num = this.sector_images[0].id;
                        this.$emit('update:sector_image_id_prop', this.images_tab_num);
                    }
                    if (this.images_tab_num) {
                        this.get_related_routes_jsons(this.images_tab_num, this.route_id_prop);
                    }
                })
                .catch(() => {});
        },
        get_related_routes_jsons(sectorImageId, excludeRouteId) {
            axios.get('/get_route/get_related_routes_jsons', {
                params: { sector_image_id: sectorImageId, exclude_route_id: excludeRouteId }
            })
                .then(response => {
                    const items = response.data || [];
                    this.related_jsons = items.map(i => i.json);
                    this.related_jsons_meta = items;
                })
                .catch(() => {});
        },
        getAndEmitCanvasData() {
            if (this.$refs.editorRef && typeof this.$refs.editorRef.getAndEmitCanvasData === 'function') {
                this.$refs.editorRef.getAndEmitCanvasData();
            }
        },
        // Exposes canvas_width/canvas_height/bg_* for the CURRENT drawing session so a
        // parent that saves through its own endpoint (e.g. routeAddComponent creating a
        // brand-new route via /set_route/add_route, instead of this component's own
        // saveRouteDrawing) can persist the same metadata a route gets when drawn via
        // the edit flow — without it, a route's FIRST drawing would have no bg_* at all.
        getDrawingMeta() {
            const canvasContainer = this.$refs.editorRef && this.$refs.editorRef.$refs.canvasContainer;
            const scope = canvasContainer ? canvasContainer.getCanvasScope() : null;
            return {
                canvas_width:  scope && scope.view ? Math.round(scope.view.viewSize.width)  : null,
                canvas_height: scope && scope.view ? Math.round(scope.view.viewSize.height) : null,
                ...this.bgBoundsPayload(canvasContainer),
            };
        },
        // The background photo's own actual position + size within the Paper.js
        // view — the editor fits it with a uniform cover-scale, centered, so it
        // doesn't necessarily start at (0,0) or fill the view exactly. Without
        // this, every viewer had to assume zero offset, which is exactly what
        // let saved strokes land in the wrong place once redrawn elsewhere.
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
        async saveRouteDrawing() {
            if (this.extra_drawing_mode) { return this.saveExtraDrawing(); }

            if (!this.images_tab_num) { alert(this.$t('admin.routes_sectors.select_sector_image_first')); return; }
            if (!this.$refs.editorRef) { alert(this.$t('admin.routes_sectors.editor_not_open')); return; }

            this.drawing_saving = true;
            this.drawing_save_status = null;

            try {
                const canvasContainer = this.$refs.editorRef.$refs.canvasContainer;

                // Export only the current route's drawing (no background, no related routes).
                let json = this.route_json_prop;
                if (canvasContainer && typeof canvasContainer.getCleanJson === 'function') {
                    const cleanJson = canvasContainer.getCleanJson();
                    if (cleanJson) { json = cleanJson; this.$emit('update:route_json_prop', json); }
                }
                if (!json) { alert(this.$t('admin.routes_sectors.no_drawing_data_found')); return; }

                const selectedImage = this.sector_images.find(img => img.id === this.images_tab_num);
                // Background is always the original clean photo (origin_img/) if it exists,
                // otherwise the current sector image (which hasn't been overwritten yet).
                const bgPath = selectedImage && selectedImage.has_original
                    ? '/public/images/sector_img/origin_img/' + selectedImage.image
                    : '/public/images/sector_img/' + (selectedImage ? selectedImage.image : '');

                // Capture ALL drawing strokes (current route + related routes, no bg raster).
                // This ensures the saved composite image includes every route drawn on this image.
                const drawingDataUrl = this.captureAllDrawingStrokes(canvasContainer);

                // Composite: original photo + drawing strokes
                const editedImageData = await this.compositeImages(bgPath, drawingDataUrl,
                    canvasContainer.$refs.canvasManager.$el);

                // Paper.js view size at save time — the canvas is sized responsively to the
                // browser container width, not to the photo's pixel dimensions, so any other
                // renderer (e.g. the public guidebook page) needs this to rescale correctly.
                const scope = canvasContainer.getCanvasScope();
                const canvasWidth  = scope && scope.view ? Math.round(scope.view.viewSize.width)  : null;
                const canvasHeight = scope && scope.view ? Math.round(scope.view.viewSize.height) : null;

                const response = await axios.post('/set_route/save_route_drawing', {
                    route_id:        this.route_id_prop,
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
                    this.$bus.$emit('route-drawing-updated', { sector_image_id: this.images_tab_num });
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

        async deleteRouteDrawing() {
            if (this.extra_drawing_mode) { return this.deleteExtraDrawing(); }

            if (!this.route_id_prop) { alert(this.$t('admin.routes_sectors.no_route_selected')); return; }
            if (!confirm(this.$t('admin.routes_sectors.confirm_delete_route_drawing'))) return;

            this.drawing_deleting = true;
            this.drawing_save_status = null;
            try {
                const deletedImageId = this.images_tab_num;
                await axios.delete('/set_route/set_route_json/del_route_json/' + this.route_id_prop);
                // Clear parent's json and sector_image_id so the editor reflects no drawing
                this.$emit('update:route_json_prop', null);
                this.$emit('update:sector_image_id_prop', '');
                this.$bus.$emit('route-drawing-updated', { sector_image_id: deletedImageId });
                this.drawing_save_status = 'deleted';
                setTimeout(() => { this.drawing_save_status = null; }, 3000);
            } catch (e) {
                this.drawing_save_status = 'error';
            } finally {
                this.drawing_deleting = false;
            }
        },

        // Extra-drawing-mode counterparts of saveRouteDrawing/deleteRouteDrawing
        // above — same composite-image approach, but targets
        // SectorImageExtraDrawing (keyed only by sector_image_id) instead of
        // ClimbingRoutesJson (keyed by route_id).
        async saveExtraDrawing() {
            if (!this.images_tab_num) { alert(this.$t('admin.routes_sectors.select_sector_image_first')); return; }
            if (!this.$refs.editorRef) { alert(this.$t('admin.routes_sectors.editor_not_open')); return; }

            this.drawing_saving = true;
            this.drawing_save_status = null;

            try {
                const canvasContainer = this.$refs.editorRef.$refs.canvasContainer;

                let json = this.extra_drawing_json;
                if (canvasContainer && typeof canvasContainer.getCleanJson === 'function') {
                    const cleanJson = canvasContainer.getCleanJson();
                    if (cleanJson) { json = cleanJson; this.extra_drawing_json = json; }
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

                const response = await axios.post('/set_sector/set_sector_image_extra_drawing/save/' + this.images_tab_num, {
                    json,
                    edited_image:  editedImageData,
                    canvas_width:  canvasWidth,
                    canvas_height: canvasHeight,
                    ...this.bgBoundsPayload(canvasContainer),
                });

                if (response.data.success) {
                    this.drawing_save_status = 'ok';
                    if (selectedImage) selectedImage.has_original = true;
                    this.$bus.$emit('route-drawing-updated', { sector_image_id: this.images_tab_num });
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

        async deleteExtraDrawing() {
            if (!this.images_tab_num) { alert(this.$t('admin.routes_sectors.no_sector_image_selected')); return; }
            if (!confirm(this.$t('admin.routes_sectors.confirm_delete_extra_drawing'))) return;

            this.drawing_deleting = true;
            this.drawing_save_status = null;
            try {
                await axios.delete('/set_sector/set_sector_image_extra_drawing/delete/' + this.images_tab_num);
                this.extra_drawing_json = null;
                this.$bus.$emit('route-drawing-updated', { sector_image_id: this.images_tab_num });
                this.drawing_save_status = 'deleted';
                setTimeout(() => { this.drawing_save_status = null; }, 3000);
            } catch (e) {
                this.drawing_save_status = 'error';
            } finally {
                this.drawing_deleting = false;
            }
        },

        // Capture all routes with their ORIGINAL drawn colors for the composite.
        // The display-tinted related layers (blue/green) are hidden and replaced with
        // temporary layers imported directly from the raw JSON (original colors preserved).
        captureAllDrawingStrokes(canvasContainer) {
            const scope = canvasContainer.getCanvasScope();
            if (!scope) return null;

            // Reset to zoom=1 / pan=0 so strokes land at correct positions.
            const savedZoom    = scope.view.zoom;
            const savedCenterX = scope.view.center.x;
            const savedCenterY = scope.view.center.y;
            canvasContainer.updateView(1, { x: 0, y: 0 });

            const bgLayer       = scope.project.layers.find(l => l.name === 'background');
            const relatedLayers = scope.project.layers.filter(l => l.name && l.name.startsWith('related-'));

            // Hide background (composited separately from the clean photo).
            const wasBgVisible = bgLayer ? bgLayer.visible : false;
            if (bgLayer) bgLayer.visible = false;

            // Hide display-tinted related layers — their artificial colors must not be baked in.
            const relatedWasVisible = relatedLayers.map(l => l.visible);
            relatedLayers.forEach(l => { l.visible = false; });

            // Temporarily import related routes from their original JSON so they appear
            // in the composite with the colors they were actually drawn with.
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

            // Remove temp layers and restore everything.
            tempLayers.forEach(l => { try { l.remove(); } catch (_) {} });
            if (bgLayer) bgLayer.visible = wasBgVisible;
            relatedLayers.forEach((l, i) => { l.visible = relatedWasVisible[i]; });
            const vs = scope.view.viewSize;
            canvasContainer.updateView(savedZoom, {
                x: savedCenterX - vs.width  / 2,
                y: savedCenterY - vs.height / 2,
            });
            scope.view.update();

            return dataUrl;
        },

        // Composite: draw the background photo, then draw the drawing strokes (PNG) on top.
        // Renders at the PHOTO's own native resolution, not the browser's current
        // on-screen canvas size — otherwise every save silently downscales the sector
        // image to whatever width the editor happened to be rendered at (e.g. a photo
        // saved at its native 1000x1000 would get permanently shrunk to 780x780 just
        // because the browser window was narrower on save).
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
    }
}
</script>
