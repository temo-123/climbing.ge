<template>
    <div class="col-md-12">
        <div class="container-fluid">
            <div class="row mb-3">
                <div class="col-12 d-flex align-items-center gap-3">
                    <button class="btn btn-secondary btn-sm" @click="goBack">← Go Back</button>
                    <h1 class="mb-0 h4">Sector Local Image — Canvas Editor</h1>
                </div>
            </div>

            <div class="row mb-3">
                <!-- Left: layouts list -->
                <div class="col-md-4">
                    <h5>Layouts <small class="text-muted">(1 per sector)</small></h5>
                    <div class="layout-list mb-2">
                        <div
                            v-for="(layout, index) in layouts"
                            :key="layout.id"
                            class="layout-item d-flex align-items-center justify-content-between mb-1 p-2"
                            :class="{ 'layout-active': activeLayoutId === layout.id }"
                            style="border:1px solid #dee2e6; border-radius:4px; background:#f8f9fa; cursor:pointer;"
                            @click="selectLayout(layout.id)"
                        >
                            <span style="font-size:0.95rem; flex:1;">
                                {{ layout.sector ? layout.sector.name : ('Layout #' + layout.id) }}
                            </span>
                            <div class="d-flex gap-1 ms-1" @click.stop>
                                <button class="btn btn-outline-secondary btn-sm p-0"
                                    style="width:22px;height:22px;line-height:1;"
                                    :disabled="index === 0"
                                    @click="moveLayoutUp(index)" title="Move up">↑</button>
                                <button class="btn btn-outline-secondary btn-sm p-0"
                                    style="width:22px;height:22px;line-height:1;"
                                    :disabled="index >= layouts.length - 1"
                                    @click="moveLayoutDown(index)" title="Move down">↓</button>
                                <button class="btn btn-danger btn-sm p-0"
                                    style="width:22px;height:22px;line-height:1;"
                                    @click="deleteLayout(layout.id)">✕</button>
                            </div>
                        </div>
                        <div v-if="layouts.length === 0" class="text-muted small p-2">No layouts yet.</div>
                    </div>
                    <button class="btn btn-outline-primary btn-sm" @click="newLayout">+ New Layout</button>
                </div>

                <!-- Right: sector selector (radio — one sector per layout) -->
                <div class="col-md-8">
                    <h5>Select sector for this drawing</h5>
                    <p class="text-muted small mb-2">Each layout represents ONE sector's area on the image.</p>
                    <div style="max-height:220px; overflow-y:auto; border:1px solid #dee2e6; border-radius:4px; padding:8px;">
                        <div v-for="sector in availableSectors" :key="sector.id" class="form-check mb-1">
                            <input
                                class="form-check-input"
                                type="radio"
                                :id="'sec-' + sector.id"
                                :value="sector.id"
                                v-model="selectedSectorId"
                            >
                            <label class="form-check-label" :for="'sec-' + sector.id">
                                {{ sector.name }} <small class="text-muted">(#{{ sector.id }})</small>
                                <span v-if="layoutBySector(sector.id)" class="badge bg-success ms-1" style="font-size:10px;">has drawing</span>
                            </label>
                        </div>
                        <div v-if="availableSectors.length === 0" class="text-muted small">No sectors linked to this image.</div>
                    </div>
                </div>
            </div>

            <!-- Save button + status -->
            <div class="row mb-2">
                <div class="col-12 d-flex align-items-center gap-2">
                    <button
                        class="btn"
                        :class="extra_drawing_mode ? 'btn-info' : 'btn-outline-info'"
                        :disabled="extra_drawing_loading"
                        @click="toggleExtraDrawingMode"
                    >
                        <i class="fa fa-map-marker"></i>
                        {{ extra_drawing_loading ? 'Loading…' : (extra_drawing_mode ? 'Extra Drawing Mode: ON' : 'Add Extra Drawing') }}
                    </button>
                    <button class="btn btn-success" :disabled="saving" @click="saveChanges">
                        <i class="fa fa-save"></i> {{ saving ? 'Saving…' : (extra_drawing_mode ? 'Save Extra Drawing' : 'Save Layout') }}
                    </button>
                    <button v-if="extra_drawing_mode" class="btn btn-danger" :disabled="deletingExtraDrawing" @click="deleteExtraDrawing">
                        <i class="fa fa-trash"></i> {{ deletingExtraDrawing ? 'Deleting…' : 'Delete Extra Drawing' }}
                    </button>
                    <span v-if="saveStatus" :class="saveStatus === 'ok' ? 'text-success' : 'text-danger'">
                        {{ saveStatus === 'ok' ? '✓ Saved' : '✗ Error' }}
                    </span>
                    <span v-if="imageInfo && imageInfo.has_original" class="badge bg-success ms-2" style="font-size:11px;">original saved</span>
                </div>
                <div class="col-12" v-if="extra_drawing_mode">
                    <p class="text-muted mb-0" style="font-size:12px;">
                        Extra drawing mode: this layer isn't tied to any one sector — it's shared by every sector that uses this image. Use it for general info (approach notes, hazards, landmarks). Click the button again to go back to editing the selected sector's own layout.
                    </p>
                </div>
            </div>

            <!-- Canvas editor -->
            <div class="row">
                <div class="col-12">
                    <Editor
                        v-if="imageUrl"
                        ref="editorComponent"
                        :image_prop="imageUrl"
                        :json_prop="activeJsonProp"
                        :json_meta="activeJsonMeta"
                        :related_jsons="relatedJsons"
                        :related_jsons_meta="relatedJsonsMeta"
                        @canvas_data="handleCanvasData"
                    />
                    <div v-else class="text-muted p-4 text-center border rounded">Loading image…</div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import Editor from '../../items/canvas/EditorComponent.vue'

export default {
    components: { Editor },
    data() {
        return {
            imageInfo:        null,
            imageUrl:         '',
            canvasData:       null,
            canvasJsonMeta:   null,
            layouts:          [],
            availableSectors: [],
            selectedSectorId: null,
            activeLayoutId:   null,
            saving:           false,
            saveStatus:       null,
            // "Extra drawing" mode: a general annotation layer tied only to
            // this image (not to any one sector) — see SectorLocalImageExtraDrawing.
            extra_drawing_mode:    false,
            extra_drawing_json:    null,
            extra_drawing_meta:    null,
            extra_drawing_loading: false,
            deletingExtraDrawing:  false,
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
        },
    },
    computed: {
        relatedJsons() {
            return this.layouts
                .filter(l => l.id !== this.activeLayoutId && l.json)
                .map(l => l.json);
        },
        // Sibling to relatedJsons, aligned by index — the background photo's own
        // position/size within each layout's OWN save-time view, so the editor can
        // rescale each overlay onto the current background fit.
        relatedJsonsMeta() {
            return this.layouts
                .filter(l => l.id !== this.activeLayoutId && l.json)
                .map(l => this._layoutMeta(l));
        },
        bgImageUrl() {
            if (!this.imageInfo || !this.imageInfo.image) return null;
            return this.imageInfo.has_original
                ? '/public/images/sector_local_img/origin_img/' + this.imageInfo.image
                : '/public/images/sector_local_img/' + this.imageInfo.image;
        },
        // What the Editor actually shows/edits — the selected sector's own
        // layout normally, or the image's general extra-info layer while
        // that mode is toggled on.
        activeJsonProp() {
            return this.extra_drawing_mode ? this.extra_drawing_json : this.canvasData;
        },
        activeJsonMeta() {
            return this.extra_drawing_mode ? this.extra_drawing_meta : this.canvasJsonMeta;
        },
    },
    mounted() {
        document.querySelector('body').style.marginLeft = '0';
        const nav = document.querySelector('.admin_page_header_navbar');
        if (nav) nav.style.marginLeft = '0';
        this.loadImageData();
    },
    methods: {
        loadImageData() {
            axios.get('/set_sector/set_sector_local_images/get_for_editor/' + this.$route.params.id)
                .then(response => {
                    const d = response.data || {};
                    if (d.image) {
                        this.imageInfo = d.image;
                        // Use original as background for drawing if backed up; otherwise current image
                        this.imageUrl = d.image.has_original
                            ? '/public/images/sector_local_img/origin_img/' + d.image.image
                            : '/public/images/sector_local_img/' + d.image.image;
                    }
                    this.availableSectors = d.sectors || [];
                    this.layouts = d.layouts || [];
                })
                .catch(error => console.log(error));
        },

        reloadLayouts() {
            axios.get('/set_sector/set_sector_local_images/get_for_editor/' + this.$route.params.id)
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

        selectLayout(layoutId) {
            const layout = this.layouts.find(l => l.id === layoutId);
            if (!layout) return;
            this.canvasData       = layout.json;
            this.canvasJsonMeta   = this._layoutMeta(layout);
            this.selectedSectorId = layout.sector_id;
            this.activeLayoutId   = layout.id;
        },

        moveLayoutUp(index) {
            if (index === 0) return;
            const arr = [...this.layouts];
            [arr[index - 1], arr[index]] = [arr[index], arr[index - 1]];
            this.layouts = arr;
        },

        moveLayoutDown(index) {
            if (index >= this.layouts.length - 1) return;
            const arr = [...this.layouts];
            [arr[index], arr[index + 1]] = [arr[index + 1], arr[index]];
            this.layouts = arr;
        },

        newLayout() {
            this.canvasData       = null;
            this.canvasJsonMeta   = null;
            this.selectedSectorId = null;
            this.activeLayoutId   = null;
        },

        handleCanvasData(data) {
            if (this.extra_drawing_mode) {
                this.extra_drawing_json = data;
            } else {
                this.canvasData = data;
            }
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

        // Switches the editor between "the selected sector's own layout" and
        // the image's general extra-info layer, shared by every sector using
        // this image — fetched once when entering the mode.
        async toggleExtraDrawingMode() {
            if (!this.extra_drawing_mode) {
                this.extra_drawing_loading = true;
                try {
                    const response = await axios.get('/set_sector/set_sector_local_image_extra_drawing/get_for_editor/' + this.$route.params.id);
                    const drawing = response.data && response.data.extra_drawing;
                    this.extra_drawing_json = drawing ? drawing.json : null;
                    this.extra_drawing_meta = this._layoutMeta(drawing);
                } catch (e) {
                    this.extra_drawing_json = null;
                    this.extra_drawing_meta = null;
                } finally {
                    this.extra_drawing_loading = false;
                }
            }
            this.extra_drawing_mode = !this.extra_drawing_mode;
        },

        async saveExtraDrawing() {
            if (!this.$route.params.id) { alert('No image selected'); return; }

            this.saving    = true;
            this.saveStatus = null;

            try {
                const canvasContainer = this.$refs.editorComponent?.$refs.canvasContainer;

                let json = this.extra_drawing_json;
                if (canvasContainer && typeof canvasContainer.getCleanJson === 'function') {
                    const cleanJson = canvasContainer.getCleanJson();
                    if (cleanJson) { json = cleanJson; this.extra_drawing_json = json; }
                }
                if (!json) { alert('No drawing data found. Draw something first.'); return; }

                let editedImageData = null;
                let canvasWidth = 0;
                let canvasHeight = 0;
                if (canvasContainer) {
                    const scope = canvasContainer.getCanvasScope();
                    if (scope && scope.view) {
                        canvasWidth  = Math.round(scope.view.viewSize.width);
                        canvasHeight = Math.round(scope.view.viewSize.height);
                    }
                    const drawingDataUrl = this.captureAllDrawingStrokes(canvasContainer);
                    editedImageData = await this.compositeImages(
                        this.bgImageUrl,
                        drawingDataUrl,
                        canvasContainer.$refs.canvasManager.$el
                    );
                }

                const response = await axios.post(
                    '/set_sector/set_sector_local_image_extra_drawing/save/' + this.$route.params.id,
                    {
                        json, edited_image: editedImageData, canvas_width: canvasWidth, canvas_height: canvasHeight,
                        ...this.bgBoundsPayload(canvasContainer),
                    }
                );

                this.saveStatus = response.data.success ? 'ok' : 'error';
                if (response.data.success && this.imageInfo) {
                    this.imageInfo.has_original = true;
                    this.imageUrl = '/public/images/sector_local_img/origin_img/' + this.imageInfo.image;
                }
                setTimeout(() => { this.saveStatus = null; }, 3000);
            } catch (e) {
                console.error(e);
                this.saveStatus = 'error';
            } finally {
                this.saving = false;
            }
        },

        async deleteExtraDrawing() {
            if (!confirm('Delete the extra drawing for this image? The JSON data will be removed.')) return;

            this.deletingExtraDrawing = true;
            try {
                await axios.delete('/set_sector/set_sector_local_image_extra_drawing/delete/' + this.$route.params.id);
                this.extra_drawing_json = null;
                this.saveStatus = 'ok';
                setTimeout(() => { this.saveStatus = null; }, 3000);
            } catch (e) {
                this.saveStatus = 'error';
            } finally {
                this.deletingExtraDrawing = false;
            }
        },

        async saveChanges() {
            if (this.extra_drawing_mode) { return this.saveExtraDrawing(); }

            if (!this.canvasData)       { alert('Draw something on the canvas first.'); return; }
            if (!this.selectedSectorId) { alert('Select a sector first.'); return; }

            this.saving    = true;
            this.saveStatus = null;

            try {
                const canvasContainer = this.$refs.editorComponent?.$refs.canvasContainer;

                // Get most up-to-date JSON directly from Paper.js
                let json = this.canvasData;
                if (canvasContainer && typeof canvasContainer.getCleanJson === 'function') {
                    const cleanJson = canvasContainer.getCleanJson();
                    if (cleanJson) json = cleanJson;
                }

                // Build composite: background photo + all drawn strokes baked in
                let editedImageData = null;
                let canvasWidth = 0;
                let canvasHeight = 0;
                if (canvasContainer) {
                    const scope = canvasContainer.getCanvasScope();
                    if (scope && scope.view) {
                        canvasWidth  = Math.round(scope.view.viewSize.width);
                        canvasHeight = Math.round(scope.view.viewSize.height);
                    }
                    const drawingDataUrl = this.captureAllDrawingStrokes(canvasContainer);
                    editedImageData = await this.compositeImages(
                        this.bgImageUrl,
                        drawingDataUrl,
                        canvasContainer.$refs.canvasManager.$el
                    );
                }

                const response = await axios.post(
                    '/set_sector/set_sector_local_images/save_canvas_data/' + this.$route.params.id,
                    {
                        canvasData:    json,
                        sectorId:      this.selectedSectorId,
                        edited_image:  editedImageData,
                        canvas_width:  canvasWidth,
                        canvas_height: canvasHeight,
                        ...this.bgBoundsPayload(canvasContainer),
                    }
                );

                this.saveStatus    = 'ok';
                this.activeLayoutId = response.data.layout_id;

                // After first save the original is backed up — switch editor background to origin_img/
                if (response.data.has_original && this.imageInfo) {
                    this.imageInfo.has_original = true;
                    this.imageUrl = '/public/images/sector_local_img/origin_img/' + this.imageInfo.image;
                }

                this.reloadLayouts();
                setTimeout(() => { this.saveStatus = null; }, 3000);
            } catch (e) {
                console.error(e);
                this.saveStatus = 'error';
            } finally {
                this.saving = false;
            }
        },

        // Capture all drawing strokes (all layouts) with original colors — no background raster.
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

            // Re-import related layouts with original colors (not tinted display colors)
            const tempLayers = [];
            if (this.relatedJsons && this.relatedJsons.length > 0) {
                scope.activate();
                this.relatedJsons.forEach(jsonData => {
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
                x: savedCenterX - vs.width  / 2,
                y: savedCenterY - vs.height / 2,
            });
            scope.view.update();

            return dataUrl;
        },

        // Composite: draw background photo then drawing strokes on top. Renders at the
        // PHOTO's own native resolution, not the browser's current on-screen canvas
        // size — otherwise every save silently downscales the sector image to
        // whatever width the editor happened to be rendered at.
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

        deleteLayout(layoutId) {
            if (!confirm('Delete this layout?')) return;
            axios.delete('/set_sector/set_sector_local_images/del_layout/' + layoutId)
                .then(() => {
                    if (this.activeLayoutId === layoutId) this.newLayout();
                    this.reloadLayouts();
                })
                .catch(error => console.log(error));
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
