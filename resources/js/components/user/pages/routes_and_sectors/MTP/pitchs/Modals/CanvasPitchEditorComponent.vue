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
                :class="show_editor ? 'btn-secondary' : 'btn-primary'"
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
                <div class="position-relative" ref="canvasOverlayAnchor" v-if="show_editor && sector_images.length > 0">
                    <div class="tabs">
                        <input
                            v-for="(image, index) in sector_images"
                            :key="'input-' + image.id + '-' + index"
                            type="radio"
                            :id="'pitch-input-' + image.id"
                            :value="image.id"
                            :checked="images_tab_num === image.id"
                            @change="selectSectorImageTab(image.id)"
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
                                @click="savePitchDrawing"
                            >
                                <i class="fa fa-save"></i>
                                {{ saving ? $t('admin.routes_sectors.saving_ellipsis') : (extra_drawing_mode ? $t('admin.routes_sectors.save_extra_drawing') : $t('admin.routes_sectors.save_drawing')) }}
                            </button>
                            <button
                                type="button"
                                class="btn btn-danger ms-2"
                                :disabled="extra_drawing_mode ? deletingExtraDrawing : drawing_deleting"
                                @click="deletePitchDrawing"
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
                        :route_name="extra_drawing_mode ? 'extra info' : ('Pitch ' + (pitch_name_prop || pitch_id_prop))"
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
        </div>
    </div>
</template>

<script>
import Editor from '../../../../../items/canvas/EditorComponent.vue'
import CanvasOverlaysComponent from '../../../../../items/canvas/assets/canvas/CanvasOverlaysComponent.vue'
import canvasOverlaysMixin from '../../../../../items/canvas/mixins/canvasOverlaysMixin.js'
import canvasExtraDrawingMixin from '../../../../../items/canvas/mixins/canvasExtraDrawingMixin.js'
import { drawItem, drawItemScaled } from '../../../../../../../services/canvas/paperJsonRenderer.js'
import { drawCombinedLegend } from '../../../../../../../services/canvas/legendRenderer.js'
import { canvasToJpegSized, COMPOSITE_JPEG_MIN_BYTES, COMPOSITE_JPEG_MAX_BYTES } from '../../../../../../../services/canvas/imageSizing.js'

export default {
    components: { Editor, CanvasOverlaysComponent },
    mixins: [canvasOverlaysMixin, canvasExtraDrawingMixin],
    props: {
        pitch_id_prop:        { default: null },
        sector_id_prop:       { default: '' },
        pitch_json_prop:      { default: null },
        pitch_json_meta_prop: { type: Object, default: () => null },
        sector_image_id_prop: { default: '' },
        pitch_name_prop:      { default: '' },
    },
    emits: ['update:pitch_json_prop', 'update:sector_image_id_prop'],
    data() {
        return {
            show_editor: false,
            sector_images: [],
            images_tab_num: '',
            // The background URL actually fed to the live canvas — frozen at
            // the moment images_tab_num changes, deliberately NOT recomputed
            // when has_original later flips true after a same-session save
            // (previously `getSectorImage()` was called directly from the
            // template, re-evaluating live on every render). See the
            // images_tab_num watcher below for why.
            lockedImageUrl: null,
            // Raw "other pitches drawn on this image" fetch — always excludes
            // the current pitch server-side, regardless of extra-drawing mode.
            // The related_jsons/related_jsons_meta computed below layer the
            // current pitch's own drawing / the extra drawing on top of this
            // as needed (mirrors CanvasRouteEditorComponent.vue).
            otherPitchesJson: [],
            otherPitchesJsonMeta: [],
            saving: false,
            drawing_deleting: false,
            saveStatus: null,
            // canvasExtraDrawingMixin's endpoint/alert config — see its own
            // header comment for the full contract. Same sector_image-keyed
            // endpoints CanvasRouteEditorComponent.vue uses — a pitch is drawn
            // on the same shared sector image a route would be.
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
        // What the Editor actually shows/edits — this pitch's own drawing
        // normally, or the image's general extra-info layer while that mode
        // is toggled on. Mirrors CanvasRouteEditorComponent.vue.
        activeJsonProp() {
            return this.extra_drawing_mode ? this.extra_drawing_json : this.pitch_json_prop;
        },
        activeJsonMeta() {
            return this.extra_drawing_mode ? this.extra_drawing_meta : this.pitch_json_meta_prop;
        },
        // Reference-only overlay shown alongside whatever's actively being edited —
        // mirrors CanvasRouteEditorComponent.vue's related_jsons/related_jsons_meta.
        // In extra-drawing mode this pitch's own strokes join the sibling pitches
        // as reference (otherwise it just vanishes off the canvas); in normal
        // pitch mode the extra drawing shows as reference instead.
        related_first_label() {
            if (this.extra_drawing_mode) return this.pitch_json_prop ? (this.pitch_name_prop || 'this pitch') : null;
            return this.extra_drawing_json ? 'extra info' : null;
        },
        related_jsons() {
            const jsons = [...this.otherPitchesJson];
            if (this.extra_drawing_mode) {
                if (this.pitch_json_prop) jsons.unshift(this.pitch_json_prop);
            } else if (this.extra_drawing_json) {
                jsons.unshift(this.extra_drawing_json);
            }
            return jsons;
        },
        related_jsons_meta() {
            const metas = [...this.otherPitchesJsonMeta];
            if (this.extra_drawing_mode) {
                if (this.pitch_json_prop) metas.unshift(this.pitch_json_meta_prop);
            } else if (this.extra_drawing_json) {
                metas.unshift(this.extra_drawing_meta);
            }
            return metas;
        },
    },
    watch: {
        sector_id_prop(newVal) {
            if (newVal) this.get_sector_images(newVal);
        },
        sector_image_id_prop(newVal) {
            if (newVal && !this.images_tab_num) this.images_tab_num = newVal;
        },
        // EditPitchModalComponent.vue reuses this SAME component instance
        // across every pitch ever opened in one session — its `v-if` only
        // depends on `editing_pitch_id` being truthy, which stays truthy
        // when switching from editing one pitch to another (close_modal()
        // never clears it back to null), so this component is never
        // destroyed/recreated between pitches; only its props change.
        // Without this reset, every piece of local per-pitch state —
        // `images_tab_num` (which sector image tab is active), the extra-
        // drawing mode/json/dirty flags, `otherPitchesJson` — silently
        // carried over from whichever pitch was open previously. Since
        // `_extraDrawingResourceId()` returns `images_tab_num`, a stale
        // value there meant toggling/saving "extra drawing" for the
        // NEWLY opened pitch actually read/wrote the PREVIOUS pitch's
        // sector image instead — a real bug (fixed September 2026):
        // reported as "extra drawing switching saves for a[nother] item,
        // not for the one actually selected."
        pitch_id_prop(newVal, oldVal) {
            if (newVal === oldVal) return;
            this.images_tab_num        = this.sector_image_id_prop || '';
            this.otherPitchesJson      = [];
            this.otherPitchesJsonMeta  = [];
            this.extra_drawing_mode    = false;
            this.extra_drawing_json    = null;
            this.extra_drawing_meta    = null;
            this.extra_drawing_loading = false;
            this.deletingExtraDrawing  = false;
            this._extraDrawingSelectedImage = null;
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
                this.get_related_jsons(this.images_tab_num);
                this.loadExtraDrawing();
            }
        },
    },
    mounted() {
        if (this.sector_image_id_prop) this.images_tab_num = this.sector_image_id_prop;
        this._refreshLockedImageUrl();
        if (this.sector_id_prop) this.get_sector_images(this.sector_id_prop);
    },
    methods: {
        toggleEditor() { this.show_editor = !this.show_editor; },

        // canvasExtraDrawingMixin's HOST CONTRACT hook — see its own header
        // comment for the full contract.
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
        // mid-session once this pitch's first save completes and backs up
        // the origin photo, but the canvas already has that exact same
        // clean photo loaded; reactively pointing image_prop at the new
        // origin_img/ URL (the old `getSectorImage()`, called live from the
        // template on every render) only forces CanvasManager to silently
        // reload the background async (loadBackgroundRaster/bgLoadInFlight).
        // If the admin toggles into extra-drawing mode while that reload is
        // still in flight, its completion re-imports whatever jsonProp has
        // become BY THEN onto the live canvas, wiping the pitch's
        // just-saved strokes from view. Reported September 2026 as "extra
        // drawing save deletes the pitch I was just editing." Call this
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
                // Was `this.canvas_json = canvasData` — a local property
                // nothing ever read (activeJsonProp reads `pitch_json_prop`,
                // the v-model prop), so live edits never actually reached the
                // parent. Emitting here mirrors CanvasRouteEditorComponent
                // .vue's identical handler and keeps `pitch_json_prop`
                // current as the user draws, not just at save time.
                this.$emit('update:pitch_json_prop', canvasData);
            }
        },
        // canvasExtraDrawingMixin's HOST CONTRACT hook for the confirm-before-
        // switch flow — savePitchDrawing() already guards `if (extra_drawing_mode)
        // return saveExtraDrawing()`, so calling it here (only while still
        // false) safely runs just the main-drawing save path.
        _saveMainDrawing() {
            return this.savePitchDrawing();
        },
        // The image-tab radio picker used to be a plain v-model — see
        // CanvasRouteEditorComponent.vue's identical selectSectorImageTab()
        // for the full rationale (confirm-save an unsaved dirty drawing, most
        // concretely the extra-info layer this switch reloads unconditionally
        // below, before actually switching backgrounds).
        async selectSectorImageTab(imageId) {
            if (this.images_tab_num === imageId) return;
            if (!(await this.confirmSaveIfDirty())) return;
            this.images_tab_num = imageId;
            this.updateSectorImageId();
        },
        updateSectorImageId() {
            this.$emit('update:sector_image_id_prop', this.images_tab_num);
            this.get_related_jsons(this.images_tab_num);
            this._refreshLockedImageUrl();
            // Switching images must reload that OTHER image's own extra drawing —
            // not keep showing the previous image's annotations on the new
            // background (mirrors CanvasRouteEditorComponent.vue).
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
                    }
                    this._refreshLockedImageUrl();
                    if (this.images_tab_num) {
                        this.get_related_jsons(this.images_tab_num);
                        this.loadExtraDrawing();
                    }
                })
                .catch(() => {});
        },
        get_related_jsons(sectorImageId) {
            axios.get('/set_mtp/set_mtp_pitch/get_pitch_jsons_for_sector_image', {
                params: { sector_image_id: sectorImageId, exclude_pitch_id: this.pitch_id_prop }
            })
                .then(response => {
                    const items = response.data || [];
                    this.otherPitchesJson = items.map(i => i.json);
                    this.otherPitchesJsonMeta = items;
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

        // Bakes the saved composite image by drawing the background photo at its own
        // full native resolution, then drawing this pitch's own strokes plus every
        // sibling pitch's strokes directly from their Paper.js JSON via the shared
        // paperJsonRenderer — the SAME algorithm the public guidebook viewer uses to
        // redraw a route/pitch from its JSON. Replaces an earlier raster-capture-
        // then-stretch approach that blurred every edge and could shift colors.
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
                        // across this pitch + every sibling pitch sharing this
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

        // Returns `true`/`false` — see canvasExtraDrawingMixin.js's
        // `_saveMainDrawing()` contract for why a falsy result here matters:
        // it stops the confirm-before-switch flow from proceeding to switch
        // modes after a save that didn't actually happen.
        async savePitchDrawing() {
            if (this.extra_drawing_mode) { return this.saveExtraDrawing(); }

            if (!this.images_tab_num) { alert(this.$t('admin.routes_sectors.select_sector_image_first')); return false; }
            if (!this.$refs.editorComponent) { alert(this.$t('admin.routes_sectors.editor_not_open')); return false; }

            this.saving = true;
            this.saveStatus = null;

            try {
                const canvasContainer = this.$refs.editorComponent.$refs.canvasContainer;

                let json = this.pitch_json_prop;
                if (canvasContainer && typeof canvasContainer.getCleanJson === 'function') {
                    const cleanJson = canvasContainer.getCleanJson();
                    if (cleanJson) { json = cleanJson; this.$emit('update:pitch_json_prop', json); }
                }
                if (!json) { alert(this.$t('admin.routes_sectors.no_drawing_data_found')); return false; }

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
                // Capture the pitch/sector-image identity BEFORE the await —
                // renderCompositeAtFullResolution takes real wall-clock time
                // (image decode + full-resolution redraw), during which the
                // admin can switch to a different pitch via EditPitchModalComponent's
                // reused instance. Reading this.pitch_id_prop/this.images_tab_num
                // at the post() call site (after the await) would then save
                // the NEW pitch's drawing under the OLD pitch's id, or vice
                // versa — same race class already fixed on the sector-local-image
                // and spot-rock editors.
                const savingPitchId = this.pitch_id_prop;
                const savingSectorImageId = this.images_tab_num;
                const editedImageData = await this.renderCompositeAtFullResolution(bgPath, ownMeta, this.related_jsons_meta);

                const response = await axios.post('/set_mtp/set_mtp_pitch/save_pitch_drawing', {
                    pitch_id:        savingPitchId,
                    sector_image_id: savingSectorImageId,
                    json,
                    edited_image:    editedImageData,
                    canvas_width:    canvasWidth,
                    canvas_height:   canvasHeight,
                    ...bgBounds,
                });

                if (response.data.success) {
                    if (this.pitch_id_prop === savingPitchId) {
                        this.saveStatus = 'ok';
                        this._mainDrawingDirty = false;
                        setTimeout(() => { this.saveStatus = null; }, 3000);
                    }
                    if (selectedImage) selectedImage.has_original = true;
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

        async deletePitchDrawing() {
            if (this.extra_drawing_mode) { return this.deleteExtraDrawing(); }

            if (!this.pitch_id_prop) return;
            if (!confirm(this.$t('admin.routes_sectors.confirm_delete_pitch_drawing'))) return;

            this.drawing_deleting = true;
            this.saveStatus = null;
            try {
                await axios.delete('/set_mtp/set_mtp_pitch/del_pitch_drawing/' + this.pitch_id_prop);
                this.$emit('update:pitch_json_prop', null);
                this.saveStatus = 'deleted';
                setTimeout(() => { this.saveStatus = null; }, 3000);
            } catch (e) {
                this.saveStatus = 'error';
            } finally {
                this.drawing_deleting = false;
            }
        },

        // canvasExtraDrawingMixin's HOST CONTRACT hooks for save — see its own
        // header comment for the full contract. saveExtraDrawing/
        // deleteExtraDrawing themselves come entirely from the mixin; this
        // just supplies the composite-image build (same approach as
        // savePitchDrawing above, targeting SectorImageExtraDrawing instead
        // of MtpPitchJson).
        async _buildExtraDrawingComposite(json) {
            const canvasContainer = this.$refs.editorComponent?.$refs.canvasContainer;
            const selectedImage = this.sector_images.find(img => img.id === this.images_tab_num);
            const bgPath = selectedImage && selectedImage.has_original
                ? '/public/images/sector_img/origin_img/' + selectedImage.image
                : '/public/images/sector_img/' + (selectedImage ? selectedImage.image : '');

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
            const editedImageData = await this.renderCompositeAtFullResolution(bgPath, ownMeta, this.related_jsons_meta);

            this._extraDrawingSelectedImage = selectedImage; // read back in _onExtraDrawingSaved
            return { editedImageData, canvasWidth, canvasHeight, bgBoundsPayload };
        },

        _onExtraDrawingSaved(responseData) {
            if (responseData.success && this._extraDrawingSelectedImage) this._extraDrawingSelectedImage.has_original = true;
        },
    }
}
</script>
