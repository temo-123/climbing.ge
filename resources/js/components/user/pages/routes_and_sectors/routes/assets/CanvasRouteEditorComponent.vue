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
                    :related_first_label="related_first_label"
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
import { drawItemScaled } from '../../../../../../services/canvas/paperJsonRenderer.js'

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
            // Raw "other routes drawn on this image" fetch — always excludes the
            // current route server-side, regardless of extra-drawing mode. The
            // related_jsons/related_jsons_meta computed below layer the current
            // route's own drawing / the extra drawing on top of this as needed.
            otherRoutesJson: [],
            otherRoutesJsonMeta: [],
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
        // Reference-only overlay shown alongside whatever's actively being edited.
        // - In extra-drawing mode, this route isn't "the active drawing" anymore
        //   (the extra drawing is), so this route's own strokes join the other
        //   routes as reference — otherwise it just vanishes off the canvas.
        // - In normal route mode, the extra drawing isn't being edited, so it
        //   shows as reference too, same as any other route on this image.
        // Non-null when related_jsons[0] is this route's own drawing or the extra
        // drawing rather than another route — always placed FIRST (not appended)
        // so other routes keep a stable position/number regardless of whether
        // this extra entry has finished loading yet. Tells the Editor to label/
        // color that one entry with this name instead of numbering it as just
        // another route.
        related_first_label() {
            if (this.extra_drawing_mode) return this.route_json_prop ? (this.route_name_prop || 'this route') : null;
            return this.extra_drawing_json ? 'extra info' : null;
        },
        related_jsons() {
            const jsons = [...this.otherRoutesJson];
            if (this.extra_drawing_mode) {
                if (this.route_json_prop) jsons.unshift(this.route_json_prop);
            } else if (this.extra_drawing_json) {
                jsons.unshift(this.extra_drawing_json);
            }
            return jsons;
        },
        related_jsons_meta() {
            const metas = [...this.otherRoutesJsonMeta];
            if (this.extra_drawing_mode) {
                if (this.route_json_prop) metas.unshift(this.json_meta_prop);
            } else if (this.extra_drawing_json) {
                metas.unshift(this.extra_drawing_meta);
            }
            return metas;
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
        // Loaded eagerly (not just on first toggle, and not just while extra-drawing
        // mode is on) so it's already available to show as a reference overlay in
        // normal route-editing mode from the start.
        async loadExtraDrawing(sectorImageId) {
            try {
                const response = await axios.get('/set_sector/set_sector_image_extra_drawing/get_for_editor/' + sectorImageId);
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
            }
        },
        // Switches the editor between "this route's drawing" and the image's
        // general extra-info layer. Re-fetches on entry to make sure it's the
        // freshest copy (e.g. in case it changed since the eager load).
        async toggleExtraDrawingMode() {
            if (!this.extra_drawing_mode && !this.images_tab_num) {
                alert(this.$t('admin.routes_sectors.select_sector_image_first'));
                return;
            }
            if (!this.extra_drawing_mode) {
                this.extra_drawing_loading = true;
                await this.loadExtraDrawing(this.images_tab_num);
                this.extra_drawing_loading = false;
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
            // Switching images must reload that OTHER image's own extra drawing —
            // not keep showing the previous image's annotations on the new
            // background — regardless of whether extra-drawing mode is currently on
            // (it needs to be loaded eagerly so it also shows as a reference overlay
            // in normal route-editing mode, not just once toggled into directly).
            this.extra_drawing_json = null;
            this.extra_drawing_meta = null;
            if (this.images_tab_num) this.loadExtraDrawing(this.images_tab_num);
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
                        this.loadExtraDrawing(this.images_tab_num);
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
                    this.otherRoutesJson = items.map(i => i.json);
                    this.otherRoutesJsonMeta = items;
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
                // Paper.js view size at save time — the canvas is sized responsively to the
                // browser container width, not to the photo's pixel dimensions, so any other
                // renderer (e.g. the public guidebook page) needs this to rescale correctly.
                // Read it from Paper.js's own state (not the DOM element's clientWidth/Height)
                // so it stays correct even if the canvas happens to be hidden/unlaid-out at
                // save time (e.g. a tab switch) — clientWidth would silently read 0 then.
                const scope = canvasContainer.getCanvasScope();
                const canvasWidth  = scope && scope.view ? Math.round(scope.view.viewSize.width)  : null;
                const canvasHeight = scope && scope.view ? Math.round(scope.view.viewSize.height) : null;
                const bgBounds = this.bgBoundsPayload(canvasContainer);

                const ownMeta = {
                    json,
                    canvas_width: canvasWidth, canvas_height: canvasHeight,
                    bg_left: bgBounds.bg_left, bg_top: bgBounds.bg_top,
                    bg_width: bgBounds.bg_width, bg_height: bgBounds.bg_height,
                };
                const editedImageData = await this.renderCompositeAtFullResolution(bgPath, ownMeta, this.related_jsons_meta);

                const response = await axios.post('/set_route/save_route_drawing', {
                    route_id:        this.route_id_prop,
                    sector_image_id: this.images_tab_num,
                    json,
                    edited_image:    editedImageData,
                    canvas_width:    canvasWidth,
                    canvas_height:   canvasHeight,
                    ...bgBounds,
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

                const scope = canvasContainer.getCanvasScope();
                const canvasWidth  = scope && scope.view ? Math.round(scope.view.viewSize.width)  : null;
                const canvasHeight = scope && scope.view ? Math.round(scope.view.viewSize.height) : null;
                const bgBounds = this.bgBoundsPayload(canvasContainer);

                const ownMeta = {
                    json,
                    canvas_width: canvasWidth, canvas_height: canvasHeight,
                    bg_left: bgBounds.bg_left, bg_top: bgBounds.bg_top,
                    bg_width: bgBounds.bg_width, bg_height: bgBounds.bg_height,
                };
                const editedImageData = await this.renderCompositeAtFullResolution(bgPath, ownMeta, this.related_jsons_meta);

                const response = await axios.post('/set_sector/set_sector_image_extra_drawing/save/' + this.images_tab_num, {
                    json,
                    edited_image:  editedImageData,
                    canvas_width:  canvasWidth,
                    canvas_height: canvasHeight,
                    ...bgBounds,
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

        // Bakes the saved composite image by drawing the background photo at its own
        // full native resolution, then drawing this route's own strokes plus every
        // sibling route's strokes directly from their Paper.js JSON via the shared
        // paperJsonRenderer — the SAME algorithm the public guidebook viewer uses to
        // redraw a route from its JSON. Replaces an earlier raster-capture-then-
        // stretch approach that blurred every edge and could shift colors slightly.
        renderCompositeAtFullResolution(bgPath, ownMeta, relatedMetas) {
            return new Promise((resolve) => {
                if (!bgPath) { resolve(null); return; }
                const bg = new Image();
                bg.onload = () => {
                    const w = bg.naturalWidth, h = bg.naturalHeight;
                    const canvas = document.createElement('canvas');
                    canvas.width = w; canvas.height = h;
                    const ctx = canvas.getContext('2d');
                    ctx.drawImage(bg, 0, 0, w, h);

                    (relatedMetas || []).forEach(meta => {
                        if (!meta || !meta.json) return;
                        try { drawItemScaled(ctx, meta, w, h, null, null, null, 1, 1); } catch (_) {}
                    });
                    if (ownMeta && ownMeta.json) {
                        try { drawItemScaled(ctx, ownMeta, w, h, null, null, null, 1, 1); } catch (_) {}
                    }

                    resolve(canvas.toDataURL('image/jpeg', 0.92));
                };
                bg.onerror = () => resolve(null);
                bg.src = bgPath;
            });
        },
    }
}
</script>
