<template>
    <div class="col-md-12 position-relative" ref="canvasOverlayAnchor">
        <div class="container-fluid">
            <div class="row mb-3">
                <div class="col-12 d-flex align-items-center gap-3">
                    <button class="btn btn-secondary btn-sm" @click="goBack">← {{ $t('common.back') }}</button>
                    <h1 class="mb-0 h4">{{ $t('admin.articles.spot_rock_image_editor.title') }}</h1>
                </div>
            </div>

            <div class="row mb-3">
                <!-- Left: layouts list -->
                <div class="col-md-4">
                    <h5>{{ $t('admin.articles.sector_local_image_editor.layouts_title') }} <small class="text-muted">{{ $t('admin.articles.sector_local_image_editor.per_sector_hint') }}</small></h5>
                    <div class="layout-list mb-2">
                        <div
                            v-for="layout in layouts"
                            :key="layout.id"
                            class="layout-item d-flex align-items-center justify-content-between mb-1 p-2"
                            :class="{ 'layout-active': activeLayoutId === layout.id }"
                            style="border:1px solid #dee2e6; border-radius:4px; background:#f8f9fa; cursor:pointer;"
                            @click="selectLayout(layout.id)"
                        >
                            <span style="font-size:0.95rem; flex:1;">
                                {{ layout.sector ? layout.sector.name : $t('admin.articles.sector_local_image_editor.layout_number_prefix', { id: layout.id }) }}
                            </span>
                            <button class="btn btn-danger btn-sm p-0" style="width:22px;height:22px;line-height:1;" @click.stop="deleteLayout(layout.id)">✕</button>
                        </div>
                        <div v-if="layouts.length === 0" class="text-muted small p-2">{{ $t('admin.articles.sector_local_image_editor.no_layouts_yet') }}</div>
                    </div>
                </div>

                <!-- Right: sector selector (radio — one drawing per sector) -->
                <div class="col-md-8">
                    <h5>{{ $t('admin.articles.sector_local_image_editor.select_sector_title') }}</h5>
                    <p class="text-muted small mb-2">{{ $t('admin.articles.sector_local_image_editor.select_sector_hint') }}</p>
                    <div style="max-height:220px; overflow-y:auto; border:1px solid #dee2e6; border-radius:4px; padding:8px;">
                        <div v-for="sector in sectors" :key="sector.id" class="form-check mb-1">
                            <input
                                class="form-check-input"
                                type="radio"
                                :id="'sec-' + sector.id"
                                :value="sector.id"
                                :checked="selectedSectorId === sector.id"
                                @change="selectSector(sector.id)"
                            >
                            <label class="form-check-label" :for="'sec-' + sector.id">
                                {{ sector.name }} <small class="text-muted">(#{{ sector.id }})</small>
                                <span v-if="layoutBySector(sector.id)" class="badge bg-success ms-1" style="font-size:10px;">{{ $t('admin.articles.sector_local_image_editor.has_drawing_badge') }}</span>
                            </label>
                        </div>
                        <div v-if="sectors.length === 0" class="text-muted small">{{ $t('admin.articles.spot_rock_image_editor.no_sectors_for_article') }}</div>
                    </div>
                </div>
            </div>

            <!-- Mode/action buttons (left: toggle + label) and Save/Delete
                 (right) — same grouping as every other canvas-editor page/modal. -->
            <div class="row mb-2">
                <div class="col-12 d-flex align-items-center justify-content-between flex-wrap gap-2">
                    <div class="d-flex align-items-center gap-2">
                        <button
                            class="btn"
                            :class="extra_drawing_mode ? 'btn-info' : 'btn-success'"
                            :disabled="extra_drawing_loading"
                            @click="toggleExtraDrawingMode"
                        >
                            <i class="fa fa-map-marker"></i>
                            {{ extra_drawing_loading ? $t('admin.export.loading_ellipsis') : (extra_drawing_mode ? $t('admin.articles.sector_local_image_editor.extra_drawing_mode_on') : $t('admin.articles.sector_local_image_editor.add_extra_drawing_btn')) }}
                        </button>
                        <button v-if="!extra_drawing_mode" class="btn btn-warning" @click="addOrUpdateItemLabel">
                            <i class="fa fa-tag"></i> {{ $t('admin.articles.sector_local_image_editor.add_sector_label_btn') }}
                        </button>
                    </div>
                    <div class="d-flex align-items-center gap-2">
                        <button class="btn btn-success" :disabled="saving" @click="saveChanges">
                            <i class="fa fa-save"></i> {{ saving ? $t('admin.articles.spot_rock_image_editor.saving_ellipsis') : (extra_drawing_mode ? $t('admin.articles.sector_local_image_editor.save_extra_drawing_btn') : $t('admin.articles.spot_rock_image_editor.save_drawing_btn')) }}
                        </button>
                        <button
                            class="btn btn-danger"
                            :disabled="extra_drawing_mode ? deletingExtraDrawing : !activeLayoutId"
                            @click="deleteCurrentDrawing"
                        >
                            <i class="fa fa-trash"></i>
                            {{ (extra_drawing_mode && deletingExtraDrawing) ? $t('admin.users.deleting_ellipsis') : (extra_drawing_mode ? $t('admin.articles.sector_local_image_editor.delete_extra_drawing_btn') : $t('admin.articles.sector_local_image_editor.delete_layout_btn')) }}
                        </button>
                        <span v-if="saveStatus" :class="saveStatus === 'ok' ? 'text-success' : 'text-danger'">
                            {{ saveStatus === 'ok' ? $t('admin.articles.spot_rock_image_editor.saved_badge') : $t('admin.articles.spot_rock_image_editor.error_badge') }}
                        </span>
                        <span v-if="imageInfo && imageInfo.has_original" class="badge bg-success ms-2" style="font-size:11px;">{{ $t('admin.articles.spot_rock_image_editor.original_saved_badge') }}</span>
                    </div>
                </div>
                <div class="col-12" v-if="extra_drawing_mode">
                    <p class="text-muted mb-0" style="font-size:12px;">
                        {{ $t('admin.articles.sector_local_image_editor.extra_drawing_mode_hint') }}
                    </p>
                </div>
            </div>

            <!-- Canvas editor -->
            <div class="row">
                <div class="col-12">
                    <!-- Gated on selectedSectorId/extra_drawing_mode, not just
                         imageUrl — see sectorLocaleImageEditorComponent.vue's
                         matching comment for the full rationale (fixed
                         September 2026): without this, the canvas was
                         drawable before picking a sector, and picking one
                         afterward silently discarded whatever had just been
                         drawn (canvasData gets overwritten with that sector's
                         own saved json). -->
                    <Editor
                        v-if="imageUrl && (selectedSectorId || extra_drawing_mode)"
                        ref="editorComponent"
                        :image_prop="imageUrl"
                        :json_prop="activeJsonProp"
                        :json_meta="activeJsonMeta"
                        :related_jsons="relatedJsons"
                        :related_jsons_meta="relatedJsonsMeta"
                        :related_first_label="relatedFirstLabel"
                        :route_name="editorItemName"
                        :disable_auto_legend="true"
                        :has_legend_symbols="hasLegendSymbols"
                        canvas_col_class="col-lg-8 col-md-8"
                        layers_col_class="col-lg-4 col-md-4"
                        @canvas_data="handleCanvasData"
                    />
                    <div v-else-if="!imageUrl" class="text-muted p-4 text-center border rounded">{{ $t('admin.articles.spot_rock_image_editor.loading_image_ellipsis') }}</div>
                    <div v-else class="text-muted p-4 text-center border rounded">{{ $t('admin.articles.sector_local_image_editor.select_sector_first_alert') }}</div>
                </div>
            </div>
        </div>

        <!-- Item-name labels + leader lines + combined-legend preview — shared
             across every canvas-editor page/modal that lets several items
             share one background image; see canvasOverlaysMixin.js and
             CanvasOverlaysComponent.vue for the full rationale. -->
        <CanvasOverlaysComponent
            ref="canvasOverlays"
            :editor-labels="editorLabels"
            :labels-clip-style="labelsClipStyle"
            :legend-preview-style="legendPreviewStyle"
            :legend-clip-style="legendClipStyle"
        />
    </div>
</template>

<script>
import Editor from '../../items/canvas/EditorComponent.vue'
import CanvasOverlaysComponent from '../../items/canvas/assets/canvas/CanvasOverlaysComponent.vue'
import canvasOverlaysMixin from '../../items/canvas/mixins/canvasOverlaysMixin.js'
import canvasExtraDrawingMixin from '../../items/canvas/mixins/canvasExtraDrawingMixin.js'
import { drawItem, drawItemScaled } from '../../../../services/canvas/paperJsonRenderer.js'
import { drawCombinedLegend } from '../../../../services/canvas/legendRenderer.js'
import { canvasToJpegSized, COMPOSITE_JPEG_MIN_BYTES, COMPOSITE_JPEG_MAX_BYTES } from '../../../../services/canvas/imageSizing.js'

export default {
    components: { Editor, CanvasOverlaysComponent },
    mixins: [canvasOverlaysMixin, canvasExtraDrawingMixin],
    data() {
        return {
            imageInfo:        null,
            imageUrl:         '',
            canvasData:       null,
            canvasJsonMeta:   null,
            layouts:          [],
            sectors:          [],
            selectedSectorId: null,
            activeLayoutId:   null,
            saving:           false,
            saveStatus:       null,
            // canvasOverlaysMixin's item-label config — see its own header
            // comment for the full contract. Reuses sector_local_image_editor's
            // translation keys since the text is identical across both pages.
            itemLabelConfig: {
                drawSomethingAlertKey:   'admin.articles.sector_local_image_editor.draw_something_alert',
                selectItemFirstAlertKey: 'admin.articles.sector_local_image_editor.select_sector_first_alert',
            },
            // canvasExtraDrawingMixin's endpoint/alert config — see its own
            // header comment for the full contract.
            extraDrawingEndpoints: {
                getForEditor: (id) => '/set_sector/set_spot_rocks_image_extra_drawing/get_for_editor/' + id,
                save:         (id) => '/set_sector/set_spot_rocks_image_extra_drawing/save/' + id,
                delete:       (id) => '/set_sector/set_spot_rocks_image_extra_drawing/delete/' + id,
            },
            extraDrawingAlertKeys: {
                noResourceSelected: 'admin.articles.sector_local_image_editor.no_image_selected_alert',
                noDrawingData:      'admin.articles.sector_local_image_editor.no_drawing_data_alert',
                confirmDelete:      'admin.articles.sector_local_image_editor.confirm_delete_extra_drawing',
                confirmSaveBeforeSwitch: 'admin.articles.sector_local_image_editor.confirm_save_before_switch',
            },
        }
    },
    watch: {
        // Radio buttons only update selectedSectorId (no handler on the input itself),
        // so keep canvasData/activeLayoutId in sync whenever the sector changes —
        // otherwise the previous sector's strokes stay loaded in the canvas and get
        // saved (duplicated) into the newly selected sector's layout.
        selectedSectorId(sectorId) {
            const layout = this.layoutBySector(sectorId);
            this.canvasData     = layout ? layout.json : null;
            this.canvasJsonMeta = this._layoutMeta(layout);
            this.activeLayoutId = layout ? layout.id   : null;
            this._mainDrawingDirty = false;
        },
        // Vue Router reuses this component instance across two URLs matching
        // the SAME route record with only `:id` differing (e.g. opening a
        // different image's options without leaving this page) — mounted()
        // doesn't refire, so without this watcher every piece of state below
        // (including the "must select a sector first" gate's own
        // selectedSectorId) kept pointing at the PREVIOUS image, letting the
        // Editor render immediately against the new image with data that
        // doesn't belong to it (fixed September 2026).
        '$route.params.id'(newVal, oldVal) {
            if (newVal === oldVal) return;
            this.imageInfo        = null;
            this.imageUrl         = '';
            this.canvasData       = null;
            this.canvasJsonMeta   = null;
            this.layouts          = [];
            this.sectors          = [];
            this.selectedSectorId = null;
            this.activeLayoutId   = null;
            this.saving           = false;
            this.saveStatus       = null;
            this.extra_drawing_mode    = false;
            this.extra_drawing_json    = null;
            this.extra_drawing_meta    = null;
            this.extra_drawing_loading = false;
            this.deletingExtraDrawing  = false;
            this._mainDrawingDirty     = false;
            this._extraDrawingDirty    = false;
            this.loadImageData();
            this.loadExtraDrawing();
        },
    },
    computed: {
        // What the Editor actually shows/edits — the selected sector's own
        // drawing normally, or the image's general extra-info layer while
        // that mode is toggled on. Mirrors sectorLocaleImageEditorComponent.vue.
        activeJsonProp() {
            return this.extra_drawing_mode ? this.extra_drawing_json : this.canvasData;
        },
        activeJsonMeta() {
            return this.extra_drawing_mode ? this.extra_drawing_meta : this.canvasJsonMeta;
        },
        relatedFirstLabel() {
            return (!this.extra_drawing_mode && this.extra_drawing_json) ? 'extra info' : null;
        },
        // Reference-only overlay: every OTHER sector's own layout shows as a
        // dimmed reference while editing the active one — plus, in
        // extra-drawing mode, the active sector's own layout too (nothing is
        // "the active layout" while editing the shared extra-info layer).
        relatedJsons() {
            const jsons = this.layouts
                .filter(l => (this.extra_drawing_mode || l.id !== this.activeLayoutId) && l.json)
                .map(l => l.json);
            if (this.relatedFirstLabel) jsons.unshift(this.extra_drawing_json);
            return jsons;
        },
        relatedJsonsMeta() {
            const metas = this.layouts
                .filter(l => (this.extra_drawing_mode || l.id !== this.activeLayoutId) && l.json)
                .map(l => this._layoutMeta(l));
            if (this.relatedFirstLabel) metas.unshift(this.extra_drawing_meta);
            return metas;
        },
        // Sibling to relatedJsons/relatedJsonsMeta, same filter/order — the
        // sector name to label each 'related-N' overlay with (see
        // canvasOverlaysMixin's computeEditorLabels, which expects this exact
        // computed name).
        relatedItemNames() {
            const names = this.layouts
                .filter(l => (this.extra_drawing_mode || l.id !== this.activeLayoutId) && l.json)
                .map(l => (l.sector ? l.sector.name : ''));
            if (this.relatedFirstLabel) names.unshift('');
            return names;
        },
        bgImageUrl() {
            if (!this.imageInfo || !this.imageInfo.image) return null;
            return this.imageInfo.has_original
                ? '/public/images/spot_rocks_img/origin_img/' + this.imageInfo.image
                : '/public/images/spot_rocks_img/' + this.imageInfo.image;
        },
        // Fed to the Editor's `route_name` prop — see
        // sectorLocaleImageEditorComponent.vue's identical computed for the
        // full rationale (EditorComponent._formatLayerName appends this to
        // every drawn item's own Layers-panel display name).
        editorItemName() {
            if (this.extra_drawing_mode) return 'extra info';
            return this._currentItemLabelName() || '';
        },
    },
    mounted() {
        document.querySelector('body').style.marginLeft = '0';
        const nav = document.querySelector('.admin_page_header_navbar');
        if (nav) nav.style.marginLeft = '0';
        // This page needs the full viewport width for the canvas — HomeComponent.vue
        // wraps every user-subdomain page in a Bootstrap `.container`, which caps
        // max-width per breakpoint. Drop just that class (page-local DOM tweak, not
        // an edit to HomeComponent.vue itself) and restore it on leaving so no other
        // page under this same layout is affected.
        const container = document.querySelector('.container.top_menu_margin');
        if (container) container.classList.remove('container');
        this.loadImageData();
        // loadExtraDrawing/computeEditorLabels/computeEditorLegend come from
        // canvasExtraDrawingMixin/canvasOverlaysMixin — the overlays mixin's
        // own mounted() hook already starts the animation-frame sync loop,
        // this host just needs to kick off the initial extra-drawing fetch
        // so it's ready as a reference overlay from the start.
        this.loadExtraDrawing();
    },
    beforeUnmount() {
        const container = document.querySelector('.top_menu_margin');
        if (container) container.classList.add('container');
    },
    methods: {
        loadImageData() {
            axios.get('/set_sector/set_spot_rock_images/get_for_editor/' + this.$route.params.id)
                .then(response => {
                    const d = response.data || {};
                    if (d.image) {
                        this.imageInfo = d.image;
                        this.imageUrl = d.image.has_original
                            ? '/public/images/spot_rocks_img/origin_img/' + d.image.image
                            : '/public/images/spot_rocks_img/' + d.image.image;
                    }
                    this.sectors = d.sectors || [];
                    this.layouts = d.layouts || [];
                })
                .catch(error => console.log(error));
        },

        reloadLayouts() {
            axios.get('/set_sector/set_spot_rock_images/get_for_editor/' + this.$route.params.id)
                .then(response => {
                    this.layouts = (response.data && response.data.layouts) ? response.data.layouts : [];
                })
                .catch(error => console.log(error));
        },

        layoutBySector(sectorId) {
            return this.layouts.find(l => l.sector && l.sector.id === sectorId);
        },

        // The background photo's own position/size within a layout's OWN save-time
        // view — needed to rescale that layout's strokes onto the current fit.
        _layoutMeta(layout) {
            if (!layout) return null;
            return {
                canvas_width: layout.canvas_width, canvas_height: layout.canvas_height,
                bg_left: layout.bg_left, bg_top: layout.bg_top,
                bg_width: layout.bg_width, bg_height: layout.bg_height,
            };
        },

        // Picking a different layout abandons whatever's CURRENTLY shown
        // (this sector's own drawing, or the shared extra layer) — confirm-
        // save first if it has unsaved edits, same protection the extra-
        // drawing toggle button itself gets. See canvasExtraDrawingMixin
        // .js's confirmSaveIfDirty() (bug fixed September 2026).
        // The sector radio picker used to be a plain v-model — clicking a
        // radio updates the underlying data SYNCHRONOUSLY, before any custom
        // logic gets a chance to intervene with a confirm(). Converted to
        // :checked/@change so an unsaved dirty drawing can be confirm-saved
        // (or the pick can be REJECTED, leaving the radio's checked state
        // exactly as it visually was since selectedSectorId never actually
        // changes) first — see canvasExtraDrawingMixin.js's
        // confirmSaveIfDirty().
        async selectSector(sectorId) {
            if (this.selectedSectorId === sectorId) return;
            if (!(await this.confirmSaveIfDirty())) return;
            this.selectedSectorId = sectorId;
        },
        async selectLayout(layoutId) {
            if (this.activeLayoutId === layoutId) return;
            if (!(await this.confirmSaveIfDirty())) return;
            const layout = this.layouts.find(l => l.id === layoutId);
            if (!layout) return;
            this.canvasData        = layout.json;
            this.canvasJsonMeta    = this._layoutMeta(layout);
            this.selectedSectorId  = layout.sector_id;
            this.activeLayoutId    = layout.id;
            this._mainDrawingDirty = false;
        },

        handleCanvasData(data) {
            this.markDrawingDirty();
            if (this.extra_drawing_mode) {
                this.extra_drawing_json = data;
            } else {
                this.canvasData = data;
            }
        },

        // Host hook for canvasOverlaysMixin's addOrUpdateItemLabel() — the
        // display name to stamp on the CURRENTLY-edited item's own label.
        _currentItemLabelName() {
            const sector = this.selectedSectorId ? this.sectors.find(s => s.id === this.selectedSectorId) : null;
            return sector ? sector.name : null;
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

        // canvasExtraDrawingMixin's HOST CONTRACT hooks — see its own header
        // comment for the full contract. toggleExtraDrawingMode/
        // saveExtraDrawing/deleteExtraDrawing themselves come entirely from
        // the mixin.
        _extraDrawingResourceId() {
            return this.$route.params.id;
        },
        // canvasExtraDrawingMixin's HOST CONTRACT hook for the confirm-before-
        // switch flow — saveChanges() already guards `if (extra_drawing_mode)
        // return saveExtraDrawing()`, so calling it here (only while still
        // false) safely runs just the main-drawing save path.
        _saveMainDrawing() {
            return this.saveChanges();
        },

        async _buildExtraDrawingComposite(json) {
            const canvasContainer = this.$refs.editorComponent?.$refs.canvasContainer;
            let editedImageData = null;
            let canvasWidth = 0;
            let canvasHeight = 0;
            if (canvasContainer) {
                const scope = canvasContainer.getCanvasScope();
                if (scope && scope.view) {
                    canvasWidth  = Math.round(scope.view.viewSize.width);
                    canvasHeight = Math.round(scope.view.viewSize.height);
                }
                const bgBounds = canvasContainer.getBackgroundBounds ? canvasContainer.getBackgroundBounds() : null;
                const ownMeta = {
                    json,
                    canvas_width: canvasWidth, canvas_height: canvasHeight,
                    bg_left: bgBounds && bgBounds.left, bg_top: bgBounds && bgBounds.top,
                    bg_width: bgBounds && bgBounds.width, bg_height: bgBounds && bgBounds.height,
                };
                const relatedFull = this.relatedJsons.map((j, i) => ({ json: j, ...(this.relatedJsonsMeta[i] || {}) }));
                editedImageData = await this.renderCompositeAtFullResolution(this.bgImageUrl, ownMeta, relatedFull);
            }
            return {
                editedImageData, canvasWidth, canvasHeight,
                bgBoundsPayload: this.bgBoundsPayload(canvasContainer),
            };
        },

        _onExtraDrawingSaved(responseData) {
            if (responseData.success && this.imageInfo) {
                this.imageInfo.has_original = true;
                this.imageUrl = '/public/images/spot_rocks_img/origin_img/' + this.imageInfo.image;
            }
        },

        // Returns `true`/`false` — see canvasExtraDrawingMixin.js's
        // `_saveMainDrawing()` contract for why a falsy result here matters:
        // it stops the confirm-before-switch flow from proceeding to switch
        // modes after a save that didn't actually happen.
        async saveChanges() {
            if (this.extra_drawing_mode) { return this.saveExtraDrawing(); }

            if (!this.canvasData)       { alert(this.$t('admin.articles.sector_local_image_editor.draw_something_alert')); return false; }
            if (!this.selectedSectorId) { alert(this.$t('admin.articles.sector_local_image_editor.select_sector_first_alert')); return false; }
            if (!this.$refs.editorComponent) { alert(this.$t('admin.articles.spot_rock_image_editor.editor_not_ready')); return false; }

            // Captured up front, NOT re-read after the await below — see the
            // identical fix/comment in sectorLocaleImageEditorComponent.vue's
            // saveChanges() (fixed September 2026, reported as "saving
            // problems"): renderCompositeAtFullResolution takes real
            // wall-clock time, and nothing blocks picking a different sector
            // (or "+ New Layout") while it's in flight.
            const savingSectorId = this.selectedSectorId;

            this.saving    = true;
            this.saveStatus = null;

            try {
                const canvasContainer = this.$refs.editorComponent.$refs.canvasContainer;

                let json = this.canvasData;
                if (canvasContainer && typeof canvasContainer.getCleanJson === 'function') {
                    const cleanJson = canvasContainer.getCleanJson();
                    if (cleanJson) json = cleanJson;
                }

                let editedImageData = null;
                let canvasWidth = 0;
                let canvasHeight = 0;
                if (canvasContainer) {
                    const scope = canvasContainer.getCanvasScope();
                    if (scope && scope.view) {
                        canvasWidth  = Math.round(scope.view.viewSize.width);
                        canvasHeight = Math.round(scope.view.viewSize.height);
                    }
                    const bgBounds = canvasContainer.getBackgroundBounds ? canvasContainer.getBackgroundBounds() : null;
                    const ownMeta = {
                        json,
                        canvas_width: canvasWidth, canvas_height: canvasHeight,
                        bg_left: bgBounds && bgBounds.left, bg_top: bgBounds && bgBounds.top,
                        bg_width: bgBounds && bgBounds.width, bg_height: bgBounds && bgBounds.height,
                    };
                    // relatedJsons/relatedJsonsMeta are parallel arrays (aligned by
                    // index), not pre-merged objects — renderCompositeAtFullResolution
                    // needs each related item's own json inline alongside its bg_*.
                    const relatedFull = this.relatedJsons.map((j, i) => ({ json: j, ...(this.relatedJsonsMeta[i] || {}) }));
                    editedImageData = await this.renderCompositeAtFullResolution(this.bgImageUrl, ownMeta, relatedFull);
                }

                const response = await axios.post(
                    '/set_sector/set_spot_rock_images/save_drawing/' + this.$route.params.id,
                    {
                        json,
                        sector_id:     savingSectorId,
                        edited_image:  editedImageData,
                        canvas_width:  canvasWidth,
                        canvas_height: canvasHeight,
                        ...this.bgBoundsPayload(canvasContainer),
                    }
                );

                this.saveStatus = 'ok';
                // Only apply this save's side effects to activeLayoutId/the
                // dirty flag if still on the sector this save was for — see
                // the identical guard/comment in sectorLocaleImageEditorComponent.vue.
                if (this.selectedSectorId === savingSectorId) {
                    this._mainDrawingDirty = false;
                    this.activeLayoutId = response.data.layout_id;
                }

                if (response.data.has_original && this.imageInfo) {
                    this.imageInfo.has_original = true;
                    this.imageUrl = '/public/images/spot_rocks_img/origin_img/' + this.imageInfo.image;
                }

                this.reloadLayouts();
                setTimeout(() => { this.saveStatus = null; }, 3000);
                return true;
            } catch (e) {
                console.error(e);
                this.saveStatus = 'error';
                return false;
            } finally {
                this.saving = false;
            }
        },

        deleteLayout(layoutId) {
            if (!confirm(this.$t('admin.articles.sector_local_image_editor.confirm_delete_layout'))) return;
            axios.delete('/set_sector/set_spot_rock_images/del_layout/' + layoutId)
                .then(() => {
                    if (this.activeLayoutId === layoutId) {
                        this.canvasData = null;
                        this.canvasJsonMeta = null;
                        this.selectedSectorId = null;
                        this.activeLayoutId = null;
                    }
                    this.reloadLayouts();
                })
                .catch(error => console.log(error));
        },

        // Dispatcher bound to the action row's single Delete button — same
        // pattern as sectorLocaleImageEditorComponent.vue's deleteCurrentDrawing():
        // deletes whichever is currently active (the extra drawing, or the
        // selected sector's own layout) instead of requiring the admin to
        // find the same layout in the list on the left and click its own ✕.
        deleteCurrentDrawing() {
            if (this.extra_drawing_mode) { return this.deleteExtraDrawing(); }
            if (!this.activeLayoutId) { alert(this.$t('admin.articles.sector_local_image_editor.no_layout_selected_alert')); return; }
            this.deleteLayout(this.activeLayoutId);
        },

        // Bakes the saved composite image by drawing the background photo at its own
        // full native resolution, then drawing this layout's own strokes plus every
        // sibling layout's strokes directly from their Paper.js JSON via the shared
        // paperJsonRenderer — the SAME algorithm the public guidebook viewer uses to
        // redraw a route from its JSON. Replaces an earlier raster-capture-then-
        // stretch approach that blurred every edge and could shift colors slightly.
        renderCompositeAtFullResolution(bgPath, ownMeta, relatedMetas) {
            return new Promise((resolve) => {
                if (!bgPath) { resolve(null); return; }
                // This composite JPEG is a cosmetic best-effort add-on — the
                // REAL data being saved is the Paper.js json, already
                // captured before this ever runs. But saveExtraDrawing()/
                // saveChanges() `await` this whole promise BEFORE posting
                // that json, so if it never settles, the actual save never
                // even reaches the server. `bg.onload`'s body runs as a raw
                // DOM event callback, not inside an async function — any
                // exception escaping it does NOT reject this Promise, it
                // just vanishes, leaving `resolve` never called and the
                // entire save hung forever with no error shown anywhere
                // (fixed September 2026, confirmed CRITICAL via production
                // data on the sector-local-image sibling: every saved row
                // had completed its initial insert but had NEVER once
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
                        // across this layout + every sibling sharing this image)
                        // into the saved composite — see legendRenderer.js's
                        // drawCombinedLegend for why this is safe (never
                        // reads any item's own baked-in isLegend group) and
                        // necessary (previously no legend was ever saved into the
                        // actual image file at all).
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

        goBack() { this.$router.go(-1); },
    }
}
</script>

<style scoped>
.layout-active {
    border-color: #0d6efd !important;
    background: #e7f1ff !important;
}
</style>
