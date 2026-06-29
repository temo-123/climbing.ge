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
                    <button class="btn btn-success" :disabled="saving" @click="saveChanges">
                        <i class="fa fa-save"></i> {{ saving ? 'Saving…' : 'Save Layout' }}
                    </button>
                    <span v-if="saveStatus" :class="saveStatus === 'ok' ? 'text-success' : 'text-danger'">
                        {{ saveStatus === 'ok' ? '✓ Saved' : '✗ Error' }}
                    </span>
                    <span v-if="imageInfo && imageInfo.has_original" class="badge bg-success ms-2" style="font-size:11px;">original saved</span>
                </div>
            </div>

            <!-- Canvas editor -->
            <div class="row">
                <div class="col-12">
                    <Editor
                        v-if="imageUrl"
                        ref="editorComponent"
                        :image_prop="imageUrl"
                        :json_prop="canvasData"
                        :related_jsons="relatedJsons"
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
            layouts:          [],
            availableSectors: [],
            selectedSectorId: null,
            activeLayoutId:   null,
            saving:           false,
            saveStatus:       null,
        }
    },
    computed: {
        relatedJsons() {
            return this.layouts
                .filter(l => l.id !== this.activeLayoutId && l.json)
                .map(l => l.json);
        },
        bgImageUrl() {
            if (!this.imageInfo || !this.imageInfo.image) return null;
            return this.imageInfo.has_original
                ? '/public/images/sector_local_img/origin_img/' + this.imageInfo.image
                : '/public/images/sector_local_img/' + this.imageInfo.image;
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

        selectLayout(layoutId) {
            const layout = this.layouts.find(l => l.id === layoutId);
            if (!layout) return;
            this.canvasData       = layout.json;
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
            this.selectedSectorId = null;
            this.activeLayoutId   = null;
        },

        handleCanvasData(data) {
            this.canvasData = data;
        },

        async saveChanges() {
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

        // Composite: draw background photo then drawing strokes on top
        compositeImages(bgPath, drawingDataUrl, paperCanvas) {
            return new Promise((resolve) => {
                const w = paperCanvas.width;
                const h = paperCanvas.height;
                const offscreen = document.createElement('canvas');
                offscreen.width  = w;
                offscreen.height = h;
                const ctx = offscreen.getContext('2d');

                const drawStrokes = () => {
                    if (!drawingDataUrl) { resolve(offscreen.toDataURL('image/jpeg', 0.9)); return; }
                    const si = new Image();
                    si.onload  = () => { ctx.drawImage(si, 0, 0, w, h); resolve(offscreen.toDataURL('image/jpeg', 0.9)); };
                    si.onerror = () => resolve(offscreen.toDataURL('image/jpeg', 0.9));
                    si.src = drawingDataUrl;
                };

                if (!bgPath) { drawStrokes(); return; }

                const bg = new Image();
                bg.onload  = () => { ctx.drawImage(bg, 0, 0, w, h); drawStrokes(); };
                bg.onerror = () => drawStrokes();
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
