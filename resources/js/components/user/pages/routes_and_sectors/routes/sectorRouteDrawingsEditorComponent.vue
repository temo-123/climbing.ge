<template>
    <div class="col-md-12">
        <div class="container-fluid">
            <div class="row mb-3">
                <div class="col-12 d-flex align-items-center gap-3">
                    <button class="btn btn-secondary btn-sm" @click="goBack">← Go Back</button>
                    <h1 class="mb-0 h4">Routes Drawing Editor <small v-if="sector" class="text-muted">— {{ sector.name }}</small></h1>
                </div>
            </div>

            <div class="row mb-3">
                <!-- Left: routes list -->
                <div class="col-md-4">
                    <h5>Routes <small class="text-muted">(1 drawing per route)</small></h5>
                    <div class="route-list mb-2" style="max-height:320px; overflow-y:auto;">
                        <div
                            v-for="route in routes"
                            :key="route.id"
                            class="route-item d-flex align-items-center justify-content-between mb-1 p-2"
                            :class="{ 'route-active': selectedRouteId === route.id }"
                            style="border:1px solid #dee2e6; border-radius:4px; background:#f8f9fa; cursor:pointer;"
                            @click="selectRoute(route.id)"
                        >
                            <span style="font-size:0.95rem; flex:1;">
                                <template v-if="route.num">{{ route.num }}.</template>
                                {{ route.name }}
                                <small class="text-muted">{{ route.grade }}</small>
                            </span>
                            <span v-if="drawingsByRoute[route.id]" class="badge bg-success ms-1" style="font-size:10px;">has drawing</span>
                        </div>
                        <div v-if="routes.length === 0" class="text-muted small p-2">No routes in this sector.</div>
                    </div>
                </div>

                <!-- Right: sector image selector -->
                <div class="col-md-8">
                    <h5>Select background image</h5>
                    <p v-if="!selectedRouteId" class="text-muted small mb-2">Select a route first.</p>
                    <template v-else>
                        <div style="max-height:220px; overflow-y:auto; border:1px solid #dee2e6; border-radius:4px; padding:8px;">
                            <div v-for="image in sectorImages" :key="image.id" class="form-check mb-1 d-flex align-items-center gap-2">
                                <input
                                    class="form-check-input"
                                    type="radio"
                                    :id="'img-' + image.id"
                                    :value="image.id"
                                    v-model="selectedImageId"
                                    :disabled="lockImageChoice"
                                >
                                <label class="form-check-label d-flex align-items-center gap-2" :for="'img-' + image.id">
                                    <img :src="'/public/images/sector_img/' + image.image" class="img-thumbnail" style="max-width:80px; max-height:80px;">
                                    Image #{{ image.id }}
                                </label>
                            </div>
                            <div v-if="sectorImages.length === 0" class="text-muted small">No images for this sector.</div>
                        </div>
                        <p v-if="lockImageChoice" class="text-muted small mt-1">
                            This route already has a drawing on this image. Delete the drawing to pick a different image.
                        </p>
                    </template>
                </div>
            </div>

            <!-- Save / delete + status -->
            <div class="row mb-2" v-if="selectedRouteId">
                <div class="col-12 d-flex align-items-center gap-2">
                    <button class="btn btn-success" :disabled="saving || !selectedImageId" @click="saveChanges">
                        <i class="fa fa-save"></i> {{ saving ? 'Saving…' : 'Save Drawing' }}
                    </button>
                    <button class="btn btn-danger" :disabled="deleting || !drawingsByRoute[selectedRouteId]" @click="deleteDrawing">
                        <i class="fa fa-trash"></i> {{ deleting ? 'Deleting…' : 'Delete Drawing' }}
                    </button>
                    <span v-if="saveStatus" :class="saveStatus === 'error' ? 'text-danger' : 'text-success'">
                        {{ saveStatus === 'ok' ? '✓ Saved' : saveStatus === 'deleted' ? '✓ Deleted' : '✗ Error' }}
                    </span>
                </div>
            </div>

            <!-- Canvas editor -->
            <div class="row" v-if="selectedRouteId">
                <div class="col-12">
                    <Editor
                        v-if="imageUrl"
                        ref="editorComponent"
                        :image_prop="imageUrl"
                        :json_prop="canvasData"
                        :related_jsons="relatedJsons"
                        :route_name="selectedRouteName"
                        @canvas_data="handleCanvasData"
                    />
                    <div v-else class="text-muted p-4 text-center border rounded">Select a background image to start drawing.</div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import Editor from '../../../items/canvas/EditorComponent.vue'

export default {
    components: { Editor },
    data() {
        return {
            sectorId: this.$route.params.id,
            sector: null,
            routes: [],
            sectorImages: [],
            drawingsByRoute: {}, // route_id -> sector_image_id

            selectedRouteId: null,
            selectedImageId: null,
            canvasData: null,
            relatedJsons: [],

            saving: false,
            deleting: false,
            saveStatus: null,
        }
    },
    computed: {
        selectedRouteName() {
            const r = this.routes.find(r => r.id === this.selectedRouteId);
            return r ? r.name : '';
        },
        selectedImage() {
            return this.sectorImages.find(i => i.id === this.selectedImageId) || null;
        },
        imageUrl() {
            if (!this.selectedImage) return null;
            return this.selectedImage.has_original
                ? '/public/images/sector_img/origin_img/' + this.selectedImage.image
                : '/public/images/sector_img/' + this.selectedImage.image;
        },
        // Once a route has a saved drawing its background image is fixed — changing it
        // here would silently orphan the drawing already baked into the old image.
        lockImageChoice() {
            return !!(this.selectedRouteId && this.drawingsByRoute[this.selectedRouteId]);
        },
    },
    watch: {
        // Switching routes must fully reset canvas state before loading the new route's
        // own drawing — otherwise the previous route's strokes stay live on the canvas
        // and get saved (duplicated) into the newly selected route.
        selectedRouteId(routeId) {
            this.canvasData = null;
            this.relatedJsons = [];
            this.selectedImageId = null;
            this.saveStatus = null;

            if (!routeId) return;

            const existingImageId = this.drawingsByRoute[routeId] || null;
            if (existingImageId) this.loadRouteJson(routeId);
        },
        // Manual image pick (route has no drawing yet) — loads the other routes already
        // drawn on that image as a locked reference layer.
        selectedImageId(imageId) {
            if (imageId && !this.drawingsByRoute[this.selectedRouteId]) {
                this.fetchRelatedJsons(imageId, this.selectedRouteId);
            }
        },
    },
    mounted() {
        document.querySelector('body').style.marginLeft = '0';
        const nav = document.querySelector('.admin_page_header_navbar');
        if (nav) nav.style.marginLeft = '0';
        this.loadSectorData();
    },
    methods: {
        loadSectorData() {
            axios.get('/set_route/get_sector_routes_drawing_data/' + this.sectorId)
                .then(response => {
                    const d = response.data || {};
                    this.sector = d.sector || null;
                    this.routes = d.routes || [];
                    this.sectorImages = d.images || [];
                    const map = {};
                    (d.drawings || []).forEach(row => { map[row.route_id] = row.sector_image_id; });
                    this.drawingsByRoute = map;
                })
                .catch(error => console.log(error));
        },

        selectRoute(routeId) {
            if (this.selectedRouteId === routeId) return;
            this.selectedRouteId = routeId;
        },

        loadRouteJson(routeId) {
            axios.get('/set_route/get_route_editing_data/' + routeId)
                .then(response => {
                    if (this.selectedRouteId !== routeId) return; // user already switched again
                    const d = response.data || {};
                    this.canvasData = d.json || null;
                    const imageId = d.sector_image_id || null;
                    this.selectedImageId = imageId;
                    if (imageId) this.fetchRelatedJsons(imageId, routeId);
                })
                .catch(error => console.log(error));
        },

        fetchRelatedJsons(sectorImageId, excludeRouteId) {
            axios.get('/get_route/get_related_routes_jsons', {
                params: { sector_image_id: sectorImageId, exclude_route_id: excludeRouteId }
            })
                .then(response => { this.relatedJsons = response.data || []; })
                .catch(() => {});
        },

        handleCanvasData(data) {
            this.canvasData = data;
        },

        async saveChanges() {
            if (!this.selectedRouteId) { alert('Select a route first.'); return; }
            if (!this.selectedImageId) { alert('Select a background image first.'); return; }
            if (!this.$refs.editorComponent) { alert('Editor is not ready.'); return; }

            this.saving = true;
            this.saveStatus = null;

            try {
                const canvasContainer = this.$refs.editorComponent.$refs.canvasContainer;

                let json = this.canvasData;
                if (canvasContainer && typeof canvasContainer.getCleanJson === 'function') {
                    const cleanJson = canvasContainer.getCleanJson();
                    if (cleanJson) { json = cleanJson; this.canvasData = json; }
                }
                if (!json) { alert('Draw something on the canvas first.'); this.saving = false; return; }

                const selectedImage = this.selectedImage;
                const bgPath = selectedImage && selectedImage.has_original
                    ? '/public/images/sector_img/origin_img/' + selectedImage.image
                    : '/public/images/sector_img/' + (selectedImage ? selectedImage.image : '');

                const drawingDataUrl = this.captureAllDrawingStrokes(canvasContainer);
                const editedImageData = await this.compositeImages(
                    bgPath, drawingDataUrl, canvasContainer.$refs.canvasManager.$el
                );

                // Paper.js view size at save time — the canvas is sized responsively to the
                // browser container width, not to the photo's pixel dimensions, so any other
                // renderer (e.g. the public guidebook page) needs this to rescale correctly.
                const scope = canvasContainer.getCanvasScope();
                const canvasWidth  = scope && scope.view ? Math.round(scope.view.viewSize.width)  : null;
                const canvasHeight = scope && scope.view ? Math.round(scope.view.viewSize.height) : null;

                const response = await axios.post('/set_route/save_route_drawing', {
                    route_id: this.selectedRouteId,
                    sector_image_id: this.selectedImageId,
                    json,
                    edited_image: editedImageData,
                    canvas_width: canvasWidth,
                    canvas_height: canvasHeight,
                });

                if (response.data.success) {
                    this.saveStatus = 'ok';
                    this.drawingsByRoute = { ...this.drawingsByRoute, [this.selectedRouteId]: this.selectedImageId };
                    if (selectedImage) selectedImage.has_original = true;
                    this.$bus.$emit('route-drawing-updated', { sector_image_id: this.selectedImageId });
                    setTimeout(() => { this.saveStatus = null; }, 3000);
                } else {
                    this.saveStatus = 'error';
                }
            } catch (e) {
                console.error(e);
                this.saveStatus = 'error';
            } finally {
                this.saving = false;
            }
        },

        async deleteDrawing() {
            if (!this.selectedRouteId) return;
            if (!confirm('Delete the drawing for this route? The JSON data will be removed.')) return;

            this.deleting = true;
            this.saveStatus = null;
            try {
                const imageId = this.selectedImageId;
                await axios.delete('/set_route/set_route_json/del_route_json/' + this.selectedRouteId);

                const map = { ...this.drawingsByRoute };
                delete map[this.selectedRouteId];
                this.drawingsByRoute = map;

                this.canvasData = null;
                // Keep the image selected (now unlocked) so the admin can redraw on the same photo.
                if (imageId) this.fetchRelatedJsons(imageId, this.selectedRouteId);

                this.$bus.$emit('route-drawing-updated', { sector_image_id: imageId });
                this.saveStatus = 'deleted';
                setTimeout(() => { this.saveStatus = null; }, 3000);
            } catch (e) {
                console.error(e);
                this.saveStatus = 'error';
            } finally {
                this.deleting = false;
            }
        },

        // Capture ALL drawing strokes (current route + related, original colors, no bg raster).
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

        goBack() { this.$router.go(-1); },
    }
}
</script>

<style scoped>
.route-active {
    border-color: #0d6efd !important;
    background: #e7f1ff !important;
}
</style>
