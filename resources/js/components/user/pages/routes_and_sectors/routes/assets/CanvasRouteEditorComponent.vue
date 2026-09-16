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
                        :class="show_editor ? 'btn-secondary' : 'btn-primary'"
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
              <div class="position-relative" ref="canvasOverlayAnchor" v-if="show_editor && sector_images.length > 0">
                  <div class="tabs">
                      <input
                          v-for="(image, index) in sector_images"
                          :key="'input-' + image.id + '-' + index"
                          type="radio"
                          :id="'input-' + image.id"
                          :value="image.id"
                          :checked="images_tab_num === image.id"
                          @change="selectSectorImageTab(image.id)"
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

                  <div class="col-md-12 mt-2 mb-2 d-flex align-items-center justify-content-between flex-wrap">
                      <div class="d-flex align-items-center">
                          <button
                              type="button"
                              class="btn"
                              :class="extra_drawing_mode ? 'btn-info' : 'btn-success'"
                              :disabled="extra_drawing_loading"
                              @click="toggleExtraDrawingMode"
                          >
                              <i class="fa fa-map-marker"></i>
                              {{ extra_drawing_loading ? $t('admin.routes_sectors.loading_ellipsis') : (extra_drawing_mode ? $t('admin.routes_sectors.extra_drawing_mode_on') : $t('admin.routes_sectors.add_extra_drawing')) }}
                          </button>
                      </div>
                      <div class="d-flex align-items-center">
                          <button
                              type="button"
                              class="btn btn-success"
                              :disabled="saving"
                              @click="saveRouteDrawing"
                          >
                              <i class="fa fa-save"></i>
                              {{ saving ? $t('admin.routes_sectors.saving_ellipsis') : (extra_drawing_mode ? $t('admin.routes_sectors.save_extra_drawing') : $t('admin.routes_sectors.save_drawing')) }}
                          </button>
                          <button
                              type="button"
                              class="btn btn-danger ms-2"
                              :disabled="extra_drawing_mode ? deletingExtraDrawing : drawing_deleting"
                              @click="deleteRouteDrawing"
                          >
                              <i class="fa fa-trash"></i>
                              {{ (extra_drawing_mode ? deletingExtraDrawing : drawing_deleting) ? $t('admin.routes_sectors.deleting_ellipsis') : (extra_drawing_mode ? $t('admin.routes_sectors.delete_extra_drawing') : $t('admin.routes_sectors.delete_drawing')) }}
                          </button>
                          <span v-if="saveStatus" class="ms-2" :class="saveStatus === 'ok' ? 'text-success' : 'text-danger'">
                              {{ saveStatus === 'ok' ? '✓ ' + $t('admin.routes_sectors.drawing_saved') : saveStatus === 'deleted' ? '✓ ' + $t('admin.routes_sectors.drawing_deleted') : '✗ ' + $t('admin.routes_sectors.error') }}
                          </span>
                      </div>
                      <p v-if="extra_drawing_mode" class="text-muted mt-1 mb-0 w-100" style="font-size:12px;">
                          {{ $t('admin.routes_sectors.extra_drawing_mode_hint') }}
                      </p>
                  </div>

                  <Editor
                    ref="editorComponent"
                    :image_prop="lockedImageUrl"
                    :json_prop="activeJsonProp"
                    :json_meta="activeJsonMeta"
                    :related_jsons="related_jsons"
                    :related_jsons_meta="related_jsons_meta"
                    :related_first_label="related_first_label"
                    :route_name="extra_drawing_mode ? 'extra info' : route_name_prop"
                    :disable_auto_legend="true"
                    :has_legend_symbols="hasLegendSymbols"
                    canvas_col_class="col-lg-8 col-md-8"
                    layers_col_class="col-lg-4 col-md-4"
                    @canvas_data="handleCanvasData"
                  />

                  <!-- Item-name labels + leader lines + combined-legend preview —
                       shared across every canvas-editor page/modal; see
                       canvasOverlaysMixin.js and CanvasOverlaysComponent.vue. -->
                  <CanvasOverlaysComponent
                      ref="canvasOverlays"
                      :editor-labels="editorLabels"
                      :labels-clip-style="labelsClipStyle"
                      :legend-preview-style="legendPreviewStyle"
                      :legend-clip-style="legendClipStyle"
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
import CanvasOverlaysComponent from '../../../../items/canvas/assets/canvas/CanvasOverlaysComponent.vue'
import canvasOverlaysMixin from '../../../../items/canvas/mixins/canvasOverlaysMixin.js'
import canvasExtraDrawingMixin from '../../../../items/canvas/mixins/canvasExtraDrawingMixin.js'
import { drawItem, drawItemScaled } from '../../../../../../services/canvas/paperJsonRenderer.js'
import { drawCombinedLegend } from '../../../../../../services/canvas/legendRenderer.js'
import { canvasToJpegSized, COMPOSITE_JPEG_MIN_BYTES, COMPOSITE_JPEG_MAX_BYTES } from '../../../../../../services/canvas/imageSizing.js'

export default {
    components: { Editor, CanvasOverlaysComponent },
    mixins: [canvasOverlaysMixin, canvasExtraDrawingMixin],
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
            // The background URL actually fed to the live canvas — frozen at
            // the moment images_tab_num/sector_images changes; see
            // _refreshLockedImageUrl()'s comment for why.
            lockedImageUrl: null,
            // Raw "other routes drawn on this image" fetch — always excludes the
            // current route server-side, regardless of extra-drawing mode. The
            // related_jsons/related_jsons_meta computed below layer the current
            // route's own drawing / the extra drawing on top of this as needed.
            otherRoutesJson: [],
            otherRoutesJsonMeta: [],
            saving: false,
            drawing_deleting: false,
            saveStatus: null,
            // canvasExtraDrawingMixin's endpoint/alert config — see its own
            // header comment for the full contract. "Extra drawing" mode: a
            // general annotation layer tied only to the sector image (not this
            // route) — see SectorImageExtraDrawing.
            extraDrawingEndpoints: {
                getForEditor: (id) => '/set_sector/set_sector_image_extra_drawing/get_for_editor/' + id,
                save:         (id) => '/set_sector/set_sector_image_extra_drawing/save/' + id,
                delete:       (id) => '/set_sector/set_sector_image_extra_drawing/delete/' + id,
            },
            extraDrawingAlertKeys: {
                noResourceSelected: 'admin.routes_sectors.select_sector_image_first',
                noDrawingData:      'admin.routes_sectors.no_drawing_data_found',
                confirmDelete:      'admin.routes_sectors.confirm_delete_extra_drawing',
                confirmSaveBeforeSwitch: 'admin.routes_sectors.confirm_save_before_switch',
            },
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
        // Mirrors CanvasPitchEditorComponent.vue's pitch_id_prop watcher — if
        // this component instance is ever reused across two different
        // routes (e.g. the parent doesn't remount it on route-param
        // navigation, the same reuse pattern that watcher's own comment
        // documents for EditPitchModalComponent.vue), every field below used
        // to leak from the PREVIOUS route into the new one: stale
        // `otherRoutesJson`/`otherRoutesJsonMeta` reference overlays, a
        // stuck `extra_drawing_mode`/dirty flag, or `images_tab_num`
        // resolving to a sector-image tab that doesn't even belong to the
        // new route's sector (fixed September 2026 — same bug class already
        // fixed for pitches, left unfixed for routes).
        route_id_prop(newVal, oldVal) {
            if (newVal === oldVal) return;
            this.images_tab_num        = this.sector_image_id_prop || '';
            this.otherRoutesJson       = [];
            this.otherRoutesJsonMeta   = [];
            this.extra_drawing_mode    = false;
            this.extra_drawing_json    = null;
            this.extra_drawing_meta    = null;
            this.extra_drawing_loading = false;
            this.deletingExtraDrawing  = false;
            this._mainDrawingDirty     = false;
            this._extraDrawingDirty    = false;
            this.saving                = false;
            this.saveStatus            = null;
            this.drawing_deleting      = false;
            if (!this.images_tab_num && this.sector_images.length > 0) {
                this.images_tab_num = this.sector_images[0].id;
            }
            this._refreshLockedImageUrl();
            if (this.images_tab_num) {
                this.get_related_routes_jsons(this.images_tab_num, this.route_id_prop);
                this.loadExtraDrawing();
            }
        },
    },
    mounted() {
        if (this.sector_image_id_prop) {
            this.images_tab_num = this.sector_image_id_prop;
        }
        this._refreshLockedImageUrl();
        if (this.sector_id_prop) {
            this.get_sector_images(this.sector_id_prop);
        }
    },
    methods: {
        toggleEditor() {
            this.show_editor = !this.show_editor;
        },
        // canvasExtraDrawingMixin's HOST CONTRACT hook — see its own header
        // comment for the full contract. loadExtraDrawing/toggleExtraDrawingMode/
        // saveExtraDrawing/deleteExtraDrawing themselves come entirely from
        // the mixin now.
        _extraDrawingResourceId() {
            return this.images_tab_num;
        },
        _resolveImageUrl(image) {
            if (!image) return null;
            return image.has_original
                ? '/public/images/sector_img/origin_img/' + image.image
                : '/public/images/sector_img/' + image.image;
        },
        // Freeze the canvas's background URL — has_original can flip true
        // mid-session once this route's first save completes and backs up
        // the origin photo, but the canvas already has that exact same
        // clean photo loaded; reactively pointing image_prop at the new
        // origin_img/ URL (the old `getSectorImage()`, called live from the
        // template on every render) only forces CanvasManager to silently
        // reload the background async (loadBackgroundRaster/bgLoadInFlight).
        // If the admin toggles into extra-drawing mode while that reload is
        // still in flight, its completion re-imports whatever jsonProp has
        // become BY THEN onto the live canvas, wiping the route's
        // just-saved strokes from view. Reported September 2026 as "extra
        // drawing save deletes the route I was just editing." Call this
        // explicitly wherever images_tab_num/sector_images is freshly
        // established — NOT a value-change watcher, since sector_images
        // populates asynchronously after images_tab_num is first set.
        _refreshLockedImageUrl() {
            const img = this.sector_images.find(i => i.id === this.images_tab_num) || null;
            this.lockedImageUrl = this._resolveImageUrl(img);
        },
        getSectorImageThumb(image) {
            return '/public/images/sector_img/' + image.image;
        },
        handleCanvasData(canvasData) {
            this.markDrawingDirty();
            if (this.extra_drawing_mode) {
                this.extra_drawing_json = canvasData;
            } else {
                this.$emit('update:route_json_prop', canvasData);
            }
        },
        // canvasExtraDrawingMixin's HOST CONTRACT hook for the confirm-before-
        // switch flow — saveRouteDrawing() already guards `if (extra_drawing_mode)
        // return saveExtraDrawing()`, so calling it here (only while still
        // false) safely runs just the main-drawing save path.
        _saveMainDrawing() {
            return this.saveRouteDrawing();
        },

        // canvasExtraDrawingMixin's HOST CONTRACT hook — without this, the
        // mixin leaves extra_drawing_meta permanently null (missing on this
        // host until now), so relatedJsonsMeta's inclusion of the extra
        // drawing carried a null meta that renderCompositeAtFullResolution
        // silently drops — the extra-info layer was correctly saved but
        // never appeared in the baked composite whenever this route was
        // saved. Mirrors sectorLocaleImageEditorComponent.vue/
        // spotRockImageEditorComponent.vue's identical helper.
        _layoutMeta(layout) {
            if (!layout) return null;
            return {
                canvas_width: layout.canvas_width, canvas_height: layout.canvas_height,
                bg_left: layout.bg_left, bg_top: layout.bg_top,
                bg_width: layout.bg_width, bg_height: layout.bg_height,
            };
        },
        // The image-tab radio picker used to be a plain v-model — clicking a
        // radio updates the underlying data SYNCHRONOUSLY, before any custom
        // logic gets a chance to intervene with a confirm(). Converted to
        // :checked/@change so an unsaved dirty drawing (most concretely, the
        // extra-info layer this switch reloads unconditionally below) can be
        // confirm-saved (or the pick can be REJECTED, leaving the radio's
        // checked state exactly as it visually was since images_tab_num never
        // actually changes) first — see canvasExtraDrawingMixin.js's
        // confirmSaveIfDirty().
        async selectSectorImageTab(imageId) {
            if (this.images_tab_num === imageId) return;
            if (!(await this.confirmSaveIfDirty())) return;
            this.images_tab_num = imageId;
            this.updateSectorImageId();
        },
        updateSectorImageId() {
            this.$emit('update:sector_image_id_prop', this.images_tab_num);
            this.get_related_routes_jsons(this.images_tab_num, this.route_id_prop);
            this._refreshLockedImageUrl();
            // Switching images must reload that OTHER image's own extra drawing —
            // not keep showing the previous image's annotations on the new
            // background — regardless of whether extra-drawing mode is currently on
            // (it needs to be loaded eagerly so it also shows as a reference overlay
            // in normal route-editing mode, not just once toggled into directly).
            this.extra_drawing_json = null;
            this.extra_drawing_meta = null;
            if (this.images_tab_num) this.loadExtraDrawing();
        },
        get_sector_images(sectorId) {
            axios.get('/get_sector/get_sector_images/' + sectorId)
                .then(response => {
                    this.sector_images = response.data;
                    if (!this.images_tab_num && this.sector_images.length > 0) {
                        this.images_tab_num = this.sector_images[0].id;
                        this.$emit('update:sector_image_id_prop', this.images_tab_num);
                    }
                    this._refreshLockedImageUrl();
                    if (this.images_tab_num) {
                        this.get_related_routes_jsons(this.images_tab_num, this.route_id_prop);
                        this.loadExtraDrawing();
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
            if (this.$refs.editorComponent && typeof this.$refs.editorComponent.getAndEmitCanvasData === 'function') {
                this.$refs.editorComponent.getAndEmitCanvasData();
            }
        },
        // Exposes canvas_width/canvas_height/bg_* for the CURRENT drawing session so a
        // parent that saves through its own endpoint (e.g. routeAddComponent creating a
        // brand-new route via /set_route/add_route, instead of this component's own
        // saveRouteDrawing) can persist the same metadata a route gets when drawn via
        // the edit flow — without it, a route's FIRST drawing would have no bg_* at all.
        getDrawingMeta() {
            const canvasContainer = this.$refs.editorComponent && this.$refs.editorComponent.$refs.canvasContainer;
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
        // Returns `true`/`false` — see canvasExtraDrawingMixin.js's
        // `_saveMainDrawing()` contract for why a falsy result here matters:
        // it stops the confirm-before-switch flow from proceeding to switch
        // modes after a save that didn't actually happen.
        async saveRouteDrawing() {
            if (this.extra_drawing_mode) { return this.saveExtraDrawing(); }

            if (!this.images_tab_num) { alert(this.$t('admin.routes_sectors.select_sector_image_first')); return false; }
            if (!this.$refs.editorComponent) { alert(this.$t('admin.routes_sectors.editor_not_open')); return false; }

            this.saving = true;
            this.saveStatus = null;

            try {
                const canvasContainer = this.$refs.editorComponent.$refs.canvasContainer;

                // Export only the current route's drawing (no background, no related routes).
                let json = this.route_json_prop;
                if (canvasContainer && typeof canvasContainer.getCleanJson === 'function') {
                    const cleanJson = canvasContainer.getCleanJson();
                    if (cleanJson) { json = cleanJson; this.$emit('update:route_json_prop', json); }
                }
                if (!json) { alert(this.$t('admin.routes_sectors.no_drawing_data_found')); return false; }

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
                    this.saveStatus = 'ok';
                    this._mainDrawingDirty = false;
                    if (selectedImage) selectedImage.has_original = true;
                    this.$bus.$emit('route-drawing-updated', { sector_image_id: this.images_tab_num });
                    setTimeout(() => { this.saveStatus = null; }, 3000);
                    return true;
                } else {
                    this.saveStatus = 'error';
                    return false;
                }
            } catch (e) {
                this.saveStatus = 'error';
                return false;
            } finally {
                this.saving = false;
            }
        },

        async deleteRouteDrawing() {
            if (this.extra_drawing_mode) { return this.deleteExtraDrawing(); }

            if (!this.route_id_prop) { alert(this.$t('admin.routes_sectors.no_route_selected')); return; }
            if (!confirm(this.$t('admin.routes_sectors.confirm_delete_route_drawing'))) return;

            this.drawing_deleting = true;
            this.saveStatus = null;
            try {
                const deletedImageId = this.images_tab_num;
                await axios.delete('/set_route/set_route_json/del_route_json/' + this.route_id_prop);
                // Clear parent's json and sector_image_id so the editor reflects no drawing
                this.$emit('update:route_json_prop', null);
                this.$emit('update:sector_image_id_prop', '');
                this.$bus.$emit('route-drawing-updated', { sector_image_id: deletedImageId });
                this.saveStatus = 'deleted';
                setTimeout(() => { this.saveStatus = null; }, 3000);
            } catch (e) {
                this.saveStatus = 'error';
            } finally {
                this.drawing_deleting = false;
            }
        },

        // canvasExtraDrawingMixin's HOST CONTRACT hooks for save/delete — see
        // its own header comment for the full contract. saveExtraDrawing/
        // deleteExtraDrawing themselves come entirely from the mixin now;
        // this just supplies the composite-image build (same approach as
        // saveRouteDrawing above, targeting SectorImageExtraDrawing instead
        // of ClimbingRoutesJson) and the route-drawing-updated bus event a
        // sibling component (e.g. sectorEditComponent.vue's thumbnail)
        // listens for to refresh.
        // Fetches the COMPLETE, current set of routes + pitches saved on this
        // sector image straight from the server — deliberately NOT
        // this.related_jsons_meta, which only reflects whichever route
        // happens to still be loaded in this component's in-memory state.
        // See sectorRouteDrawingsEditorComponent.vue's identical helper for
        // the full rationale.
        async _fetchAllSiblingMetas(sectorImageId) {
            if (!sectorImageId) return [];
            const [routesRes, pitchesRes] = await Promise.all([
                axios.get('/get_route/get_related_routes_jsons', { params: { sector_image_id: sectorImageId } }).catch(() => ({ data: [] })),
                axios.get('/set_mtp/set_mtp_pitch/get_pitch_jsons_for_sector_image', { params: { sector_image_id: sectorImageId } }).catch(() => ({ data: [] })),
            ]);
            const metas = [];
            (routesRes.data || []).forEach(r => metas.push({
                json: r.json, canvas_width: r.canvas_width, canvas_height: r.canvas_height,
                bg_left: r.bg_left, bg_top: r.bg_top, bg_width: r.bg_width, bg_height: r.bg_height,
            }));
            (pitchesRes.data || []).forEach(p => metas.push({
                json: p.json, canvas_width: p.canvas_width, canvas_height: p.canvas_height,
                bg_left: p.bg_left, bg_top: p.bg_top, bg_width: p.bg_width, bg_height: p.bg_height,
            }));
            return metas;
        },

        async _buildExtraDrawingComposite(json) {
            const canvasContainer = this.$refs.editorComponent?.$refs.canvasContainer;
            const selectedImage = this.sector_images.find(img => img.id === this.images_tab_num);
            const bgPath = this._resolveImageUrl(selectedImage);

            let canvasWidth = null, canvasHeight = null;
            if (canvasContainer) {
                const scope = canvasContainer.getCanvasScope();
                canvasWidth  = scope && scope.view ? Math.round(scope.view.viewSize.width)  : null;
                canvasHeight = scope && scope.view ? Math.round(scope.view.viewSize.height) : null;
            }
            const bgBoundsPayload = this.bgBoundsPayload(canvasContainer);
            const ownMeta = {
                json,
                canvas_width: canvasWidth, canvas_height: canvasHeight,
                bg_left: bgBoundsPayload.bg_left, bg_top: bgBoundsPayload.bg_top,
                bg_width: bgBoundsPayload.bg_width, bg_height: bgBoundsPayload.bg_height,
            };
            const siblingMetas = await this._fetchAllSiblingMetas(this.images_tab_num);
            const editedImageData = await this.renderCompositeAtFullResolution(bgPath, ownMeta, siblingMetas);

            this._extraDrawingSelectedImage = selectedImage; // read back in _onExtraDrawingSaved
            return { editedImageData, canvasWidth, canvasHeight, bgBoundsPayload };
        },

        _onExtraDrawingSaved(responseData) {
            if (responseData.success && this._extraDrawingSelectedImage) this._extraDrawingSelectedImage.has_original = true;
            this.$bus.$emit('route-drawing-updated', { sector_image_id: this.images_tab_num });
        },

        _onExtraDrawingDeleted() {
            this.$bus.$emit('route-drawing-updated', { sector_image_id: this.images_tab_num });
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
                // This composite JPEG is a cosmetic best-effort add-on — the
                // REAL data being saved is the Paper.js json, already
                // captured before this ever runs. But the save flow `await`s
                // this whole promise BEFORE posting that json, so if it
                // never settles, the actual save never even reaches the
                // server. `bg.onload`'s body runs as a raw DOM event
                // callback, not inside an async function — any exception
                // escaping it does NOT reject this Promise, it just
                // vanishes, leaving `resolve` never called and the entire
                // save hung forever with no error shown anywhere (fixed
                // September 2026, confirmed CRITICAL via production data on
                // the sector-local-image/spot-rock siblings: saved rows had
                // completed their initial insert but had NEVER once
                // successfully recorded a later update). `finish()`
                // guarantees exactly one resolve no matter which path is
                // taken, and the timeout guarantees one fires even if the
                // image itself never loads or errors.
                let settled = false;
                const finish = (value) => { if (settled) return; settled = true; clearTimeout(timeoutId); resolve(value); };
                const timeoutId = setTimeout(() => finish(null), 15000);
                const bg = new Image();
                bg.onload = () => {
                    try {
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

                        // Bakes the ONE combined legend (every symbol type present
                        // across this route + every sibling route sharing this
                        // sector image) into the saved composite — see
                        // legendRenderer.js's drawCombinedLegend for why this
                        // is safe (never reads any item's own baked-in isLegend
                        // group) and necessary (previously no legend was ever
                        // saved into the actual image file at all).
                        try {
                            // Siblings BEFORE own on purpose (bug fixed September
                            // 2026, reported as "legend position isn't synced
                            // between pitches/routes" — see canvasOverlaysMixin
                            // .js's computeEditorLegend for the same fix and its
                            // full rationale): every save bakes into this SAME
                            // shared photo file regardless of which sibling
                            // triggered it, so "own first" meant the baked
                            // position could shift depending on whichever item
                            // was saved LAST.
                            const allJsons = [...(relatedMetas || []).map(m => m && m.json), ownMeta && ownMeta.json];
                            const refWidth = (ownMeta && (ownMeta.bg_width || ownMeta.canvas_width)) || w;
                            drawCombinedLegend(ctx, w, h, allJsons, refWidth, {
                                drawItem,
                                translate: (key) => this.$t('admin.articles.canvas_editor.' + key),
                            });
                        } catch (e) { console.error('drawCombinedLegend failed:', e); }

                        finish(canvasToJpegSized(canvas, COMPOSITE_JPEG_MIN_BYTES, COMPOSITE_JPEG_MAX_BYTES));
                    } catch (e) {
                        console.error('renderCompositeAtFullResolution failed:', e);
                        finish(null);
                    }
                };
                bg.onerror = () => finish(null);
                bg.src = bgPath;
            });
        },
    }
}
</script>
