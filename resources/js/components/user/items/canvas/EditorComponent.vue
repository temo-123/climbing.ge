<template>
    <div class="canvas-editor mb-5">

        <!-- Toolbar + style bar (visually connected) -->
        <div class="mb-2">
            <ToolbarComponent
                :action="action"
                :history-length="historyCount"
                :redo-length="redoCount"
                :has-drawing="hasDrawing"
                :has-unlocked-drawing="hasUnlockedDrawing"
                :legend-position="legendPosition"
                @legend-position-change="handleLegendPositionChange"
                :legend-scale="legendScale"
                @legend-scale-change="handleLegendScaleChange"
                @reset="handleReset"
                @undo="handleUndo"
                @redo="handleRedo"
                @line="handleLine"
                @continue-line="handleContinueLine"
                @point="handlePoint"
                @number="handleNumber"
                @rectangle="handleRectangle"
                @combined="handleCombined"
                @eraser="handleEraser"
                @move="handleMove"
                @move-all="handleMoveAll"
                @zoom-in="handleZoomIn"
                @zoom-out="handleZoomOut"
                @zoom-reset="handleZoomReset"
                @pan="handlePan"
                @circle="handleCircle"
                @ellipse="handleEllipse"
                @arrow="handleArrow"
                @rappel="handleRappel"
                @bolt="handleBolt"
                @pin="handlePin"
                @pendulum-left="handlePendulumLeft"
                @pendulum-right="handlePendulumRight"
                @crux="handleCrux"
                @anchor-good="handleAnchorGood"
                @anchor-mid="handleAnchorMid"
                @anchor-bad="handleAnchorBad"
                @portaledge-anchor-good="handlePortaledgeAnchorGood"
                @portaledge-anchor-mid="handlePortaledgeAnchorMid"
                @portaledge-anchor-bad="handlePortaledgeAnchorBad"
                @rappel-anchor-good="handleRappelAnchorGood"
                @rappel-anchor-mid="handleRappelAnchorMid"
                @rappel-anchor-bad="handleRappelAnchorBad"
                @rescue-anchor="handleRescueAnchor"
                @summit="handleSummit"
                @tent="handleTent"
                @parking="handleParking"
                @poi-hiking="handlePoiHiking"
                @poi-bed="handlePoiBed"
                @poi-bike="handlePoiBike"
                @poi-water="handlePoiWater"
                @poi-food="handlePoiFood"
                @poi-tent="handlePoiTent"
                @poi-medical="handlePoiMedical"
                @polygon="handlePolygon"
                @text="handleText"
                @selection="handleSelection"
                @resize="handleResize"
                @crop="handleCrop"
                @edit-points="handleEditPoints"
                @export-png="handleExportPNG"
                @export-svg="handleExportSVG"
                @save-image="handleSaveImage"
            />
        </div>

        <!-- Style controls bar (attached visually to toolbar) — Stroke/Fill and
             Width/Text/Symbol are each grouped into one visibly bordered
             "chip" with a small caption on its LEFT edge (all on one line —
             no stacked rows), so it's clear which controls affect color vs.
             size without the bar turning into stacked/uneven rows. -->
        <div class="d-flex flex-wrap align-items-center gap-3 mb-2 px-3 py-2 bg-white border border-top-0 rounded-bottom" style="border-top: 1px solid #dee2e6 !important; border-radius: 0 0 4px 4px !important;">

            <!-- Colors -->
            <div class="style-chip">
                <span class="style-chip-label"><i class="fa fa-paint-brush"></i> {{ $t('admin.articles.canvas_editor.colors_section_label') }}</span>

                <!-- Stroke color -->
                <div class="d-flex align-items-center gap-2" :title="$t('admin.articles.canvas_editor.stroke_color_tooltip')">
                    <span class="small text-muted">{{ $t('admin.articles.canvas_editor.stroke_label') }}</span>
                    <input type="color" :value="currentStrokeColor"
                           @input="handleColorChange('stroke', $event.target.value)"
                           class="color-swatch-input"
                           style="width:28px; height:24px;">
                    <code class="small" style="font-size:10px; color:#555;">{{ currentStrokeColor }}</code>
                </div>

                <div class="style-chip-sep"></div>

                <!-- Fill color with on/off toggle -->
                <div class="d-flex align-items-center gap-2" :title="fillEnabled ? $t('admin.articles.canvas_editor.fill_color_enabled_tooltip') : $t('admin.articles.canvas_editor.fill_color_disabled_tooltip')">
                    <span class="small text-muted">{{ $t('admin.articles.canvas_editor.fill_label') }}</span>
                    <button type="button"
                            :class="['btn btn-sm py-0 px-2 toggle-btn', fillEnabled ? 'btn-secondary' : 'btn-dark']"
                            @click="toggleFill"
                            :title="fillEnabled ? $t('admin.articles.canvas_editor.disable_fill_tooltip') : $t('admin.articles.canvas_editor.enable_fill_tooltip')">
                        <i class="fa fa-tint"></i> {{ fillEnabled ? $t('admin.articles.canvas_editor.on_label') : $t('admin.articles.canvas_editor.off_label') }}
                    </button>
                    <input type="color" :value="fillColor || '#ffffff'"
                           @input="handleColorChange('fill', $event.target.value)"
                           :disabled="!fillEnabled"
                           class="color-swatch-input"
                           :style="{ width: '28px', height: '24px', opacity: fillEnabled ? 1 : 0.3, cursor: fillEnabled ? 'pointer' : 'default' }">
                    <code v-if="fillEnabled" class="small" style="font-size:10px; color:#555;">{{ fillColor || '#fff' }}</code>
                </div>
            </div>

            <!-- Sizes -->
            <div class="style-chip">
                <span class="style-chip-label"><i class="fa fa-arrows-alt-v"></i> {{ $t('admin.articles.canvas_editor.sizes_section_label') }}</span>

                <!-- Stroke width -->
                <div class="d-flex align-items-center gap-2" :title="$t('admin.articles.canvas_editor.stroke_width_pixels_tooltip')">
                    <span class="small text-muted">{{ $t('admin.articles.canvas_editor.width_label') }}</span>
                    <input type="range" min="1" max="20" :value="strokeWidth"
                           @input="handleStrokeWidthChange(parseInt($event.target.value))"
                           class="form-range" style="width:70px;">
                    <span class="badge bg-primary" style="min-width:22px; font-size:11px;">{{ strokeWidth }}</span>
                    <span class="small text-muted" style="font-size:10px;">px</span>
                </div>

                <div class="style-chip-sep"></div>

                <!-- Text size -->
                <div class="d-flex align-items-center gap-2" :title="$t('admin.articles.canvas_editor.text_size_pixels_tooltip')">
                    <span class="small text-muted">{{ $t('admin.articles.canvas_editor.text_size_label') }}</span>
                    <input type="range" min="8" max="60" :value="textSize"
                           @input="handleTextSizeChange(parseInt($event.target.value))"
                           class="form-range" style="width:70px;">
                    <span class="badge bg-primary" style="min-width:22px; font-size:11px;">{{ textSize }}</span>
                    <span class="small text-muted" style="font-size:10px;">px</span>
                </div>

                <div class="style-chip-sep"></div>

                <!-- Symbol size (was labeled "Dot" — now also drives every
                     topo/anchor/landmark symbol's overall size, not just
                     plain dots) -->
                <div class="d-flex align-items-center gap-2" :title="$t('admin.articles.canvas_editor.dot_size_pixels_tooltip')">
                    <span class="small text-muted">{{ $t('admin.articles.canvas_editor.dot_size_label') }}</span>
                    <input type="range" min="1" max="50" :value="dotSize"
                           @input="handleDotSizeChange(parseInt($event.target.value))"
                           class="form-range" style="width:70px;">
                    <span class="badge bg-primary" style="min-width:22px; font-size:11px;">{{ dotSize }}</span>
                    <span class="small text-muted" style="font-size:10px;">px</span>
                </div>
            </div>

            <!-- Smooth freehand lines toggle -->
            <div class="style-chip" :title="smoothLines ? $t('admin.articles.canvas_editor.smooth_lines_enabled_tooltip') : $t('admin.articles.canvas_editor.smooth_lines_disabled_tooltip')">
                <span class="small text-muted">{{ $t('admin.articles.canvas_editor.smooth_lines_label') }}</span>
                <button type="button"
                        :class="['btn btn-sm py-0 px-2 toggle-btn', smoothLines ? 'btn-info' : 'btn-outline-info']"
                        @click="toggleSmoothLines">
                    <i class="fa fa-magic"></i> {{ smoothLines ? $t('admin.articles.canvas_editor.on_label') : $t('admin.articles.canvas_editor.off_label') }}
                </button>
            </div>

            <div class="vr"></div>

            <!-- Zoom -->
            <div class="style-chip">
                <span class="small text-muted">{{ $t('admin.articles.canvas_editor.zoom_label') }}</span>
                <span class="badge bg-secondary" style="min-width:44px; font-size:11px; cursor:pointer;" @click="handleZoomReset" :title="$t('admin.articles.canvas_editor.click_to_reset_zoom_tooltip')">
                    {{ Math.round(currentZoom * 100) }}%
                </span>
                <button type="button" class="btn btn-sm btn-success py-0 px-2"
                        @click="handleZoomReset" :title="$t('admin.articles.canvas_editor.reset_zoom_to_fit_canvas_tooltip')">
                    <i class="fa fa-search"></i>
                </button>
            </div>

        </div>

        <!-- Canvas + Layers sidebar -->
        <div class="row g-2">
            <div :class="canvas_col_class">
                <CanvasContainerComponent
                    :action="action"
                    :json_prop="json_prop"
                    :json_meta="json_meta"
                    :related_jsons="related_jsons"
                    :related_jsons_meta="related_jsons_meta"
                    :related_first_label="related_first_label"
                    :disable_auto_legend="disable_auto_legend"
                    :image="image"
                    ref="canvasContainer"
                    @canvas_data="handleCanvasData"
                    @layers_updated="updateLayersList"
                    @layers_ready="updateLayersList"
                    @history-changed="onHistoryChanged"
                    @zoom-changed="onZoomChanged"
                    @crop-save="handleCropSave"
                />
            </div>
            <div :class="layers_col_class">
                <LayersPanelComponent
                    :layers="layers"
                    :selected-layer-ids="selectedLayerIds"
                    :show-layers-table="true"
                    @refresh-layers="updateLayersList"
                    @toggle-all-visibility="toggleLayersVisibility"
                    @delete-all-layers="deleteAllLayers"
                    @move-layer-up="moveLayerUp"
                    @move-layer-down="moveLayerDown"
                    @toggle-layer-selection="toggleLayerSelection"
                    @create-group-from-selection="createGroupFromSelection"
                    @assign-item-group="assignItemToGroup"
                    @ungroup-layer="ungroupLayer"
                    @toggle-layer-visibility="toggleLayerVisibility"
                    @toggle-layer-lock="toggleLayerLock"
                    @delete-layer-item="deleteLayerItem"
                    @toggle-child-visibility="toggleChildVisibility"
                    @toggle-child-lock="toggleChildLock"
                    @delete-child-item="deleteChildItem"
                    @toggle-group-expansion="toggleGroupExpansion"
                    @finish-editing-layer-name="finishEditingLayerName"
                    @cancel-editing-layer-name="cancelEditingLayerName"
                    @finish-editing-child-name="finishEditingChildName"
                    @cancel-editing-child-name="cancelEditingChildName"
                    @finish-editing-text="finishEditingText"
                    @cancel-editing-text="cancelEditingText"
                    @finish-editing-child-text="finishEditingChildText"
                    @cancel-editing-child-text="cancelEditingChildText"
                    @change-layer-color="changeLayerColor"
                    @change-layer-size="changeLayerSize"
                    @change-child-color="changeChildColor"
                    @change-child-size="changeChildSize"
                    @highlight-layer="highlightLayerOnCanvas"
                    @unhighlight-layer="unhighlightLayerOnCanvas"
                />
            </div>
        </div>
    </div>
</template>

<script>
import ToolbarComponent from "./assets/toolbar/ToolbarComponent.vue";
import LayersPanelComponent from "./assets/layers/LayersPanelComponent.vue";
import CanvasContainerComponent from "./assets/canvas/CanvasContainerComponent.vue";
import paper from 'paper';

export default {
    components: {
        ToolbarComponent,
        LayersPanelComponent,
        CanvasContainerComponent
    },
        props: {
            image_prop: {
                type: String,
                default: null
            },
            json_prop: {
                type: String,
                default: null
            },
            json_meta: {
                type: Object,
                default: () => null
            },
            related_jsons: {
                type: Array,
                default: () => []
            },
            related_jsons_meta: {
                type: Array,
                default: () => []
            },
            // Non-null when related_jsons[0] isn't actually "another route/layout"
            // (it's the extra-info drawing, or the currently-edited item shown as
            // reference while extra-drawing mode is on) — see updateLayersList().
            related_first_label: {
                type: String,
                default: null
            },
            route_name: {
                type: String,
                default: ''
            },
            // Column split for the canvas/layers row — overridable per page (e.g. the
            // sector local-image editor wants a wider drawing area) without changing
            // the default for every other page that embeds this same editor.
            canvas_col_class: {
                type: String,
                default: 'col-lg-9 col-md-8'
            },
            layers_col_class: {
                type: String,
                default: 'col-lg-3 col-md-4'
            },
            // See CanvasManager.vue's disableAutoLegend prop.
            disable_auto_legend: {
                type: Boolean,
                default: false
            }
        },
        data: () => ({
            image: '',

            action: 1,
            layers: [],
            selectedLayerIds: [],
            historyCount: 0,
            redoCount: 0,
            // Style controls
            currentStrokeColor: '#ff0000',
            currentFillColor: null,
            fillEnabled: false,
            strokeWidth: 3,
            textSize: 16,
            dotSize: 4,
            smoothLines: true,
            // Mirrors whatever's stored on the canvas's 'main' layer (see
            // DrawingTools.vue's getLegendPosition) — kept in sync by
            // updateLayersList() so the toolbar picker reflects the CURRENT
            // drawing's own choice whenever it's loaded/switched.
            legendPosition: 'top-right',
            // Same mirroring as legendPosition, for the legend's overall size
            // multiplier (see DrawingTools.vue's getLegendScale) — 1 = 100%.
            legendScale: 1,
            zoomLevel: 1,
            currentZoom: 1,
            panOffset: { x: 0, y: 0 },
            selectedItems: [],
            isPanning: false,
            panStartPoint: null,
            // Tracks which Paper.js item is currently hover-highlighted from the
            // layers panel, and its selected state from BEFORE the hover started,
            // so unhighlighting can restore it instead of blindly clearing it.
            hoverHighlightId: null,
            hoverHighlightPrevSelected: false
        }),
        mounted() {
            if (this.image_prop) {
                this.image = this.image_prop;
            }
            this.$nextTick(() => {
                setTimeout(() => {
                    this.updateLayersList();
                    // Sync initial color/stroke/text/dot values into the canvas manager
                    if (this.$refs.canvasContainer && this.$refs.canvasContainer.updateColors) {
                        this.$refs.canvasContainer.updateColors(this.currentStrokeColor, this.currentFillColor, this.strokeWidth, this.textSize, this.dotSize);
                    }
                    if (this.$refs.canvasContainer && this.$refs.canvasContainer.setSmoothLines) {
                        this.$refs.canvasContainer.setSmoothLines(this.smoothLines);
                    }
                }, 150);
            });

            window.addEventListener('keydown', this.handleKeyDown);
        },

        beforeUnmount() {
            // Remove keyboard event listeners
            window.removeEventListener('keydown', this.handleKeyDown);
        },
        watch: {
            image_prop: function(newVal, oldVal) {
                if (newVal && newVal !== oldVal) {
                    this.image = newVal;
                }
            },
            json_prop: {
                handler: function(newVal, oldVal) {
                    // Props flow automatically through template bindings — no manual mutation needed.
                    // Only refresh the layers panel after the canvas processes the new JSON.
                    this.$nextTick(() => {
                        setTimeout(() => { this.updateLayersList(); }, 300);
                    });
                },
                immediate: false
            },
            related_jsons: {
                handler: function(newVal, oldVal) {
                    // Props flow automatically through template bindings — no manual mutation needed.
                    this.$nextTick(() => {
                        setTimeout(() => { this.updateLayersList(); }, 300);
                    });
                },
                immediate: false
            }
        },
        computed: {
            availableGroups() {
                return this.layers.filter(layer => layer.isGroup);
            },
            hasDrawing() {
                return this.layers.some(l => !l.isRelated);
            },
            hasUnlockedDrawing() {
                return this.layers.some(l => !l.isRelated && !l.locked);
            },
            // Effective fill color: null when disabled so canvas draws no fill
            fillColor() {
                return this.fillEnabled ? this.currentFillColor || '#ffffff' : null;
            }
        },
        methods: {
            // Toolbar event handlers
            handleReset() {
                if (confirm(this.$t('admin.articles.canvas_editor.confirm_clear_area'))) {
                    this.$refs.canvasContainer.reset();
                }
            },

            handlePoint() {
                this.action = 2;
            },

            handleLine() {
                this.action = 1;
            },

            handleNumber() {
                this.action = 3;
            },

            handleRectangle() {
                this.action = 4;
            },

            handleEraser() {
                this.action = 5;
            },

            handleCombined() {
                this.action = 7;
            },

            handleMove() {
                this.action = 8;
            },

            // New toolbar event handlers
            handleZoomIn() {
                this.$refs.canvasContainer.zoomIn();
            },

            handleZoomOut() {
                this.$refs.canvasContainer.zoomOut();
            },

            handleZoomReset() {
                this.$refs.canvasContainer.zoomReset();
            },

            handlePan() {
                this.action = 9;
            },

            handleCircle() {
                this.action = 10;
            },

            handleEllipse() {
                this.action = 11;
            },

            handleArrow() {
                this.action = 21;
            },

            handleRappel() {
                this.action = 22;
            },

            handleBolt() {
                this.action = 23;
            },

            handlePin() {
                this.action = 24;
            },

            handlePendulumLeft() {
                this.action = 25;
            },

            handlePendulumRight() {
                this.action = 26;
            },

            handleCrux() {
                this.action = 27;
            },

            handleAnchorGood()           { this.action = 28; },
            handleAnchorMid()            { this.action = 29; },
            handleAnchorBad()            { this.action = 30; },
            handlePortaledgeAnchorGood() { this.action = 31; },
            handlePortaledgeAnchorMid()  { this.action = 32; },
            handlePortaledgeAnchorBad()  { this.action = 33; },
            handleRappelAnchorGood()     { this.action = 34; },
            handleRappelAnchorMid()      { this.action = 35; },
            handleRappelAnchorBad()      { this.action = 36; },
            handleRescueAnchor()         { this.action = 37; },
            handleSummit()               { this.action = 38; },
            handleTent()                 { this.action = 39; },
            handleParking()              { this.action = 40; },
            handlePoiHiking()            { this.action = 41; },
            handlePoiBed()               { this.action = 42; },
            handlePoiBike()              { this.action = 43; },
            handlePoiWater()             { this.action = 44; },
            handlePoiFood()              { this.action = 45; },
            handlePoiTent()              { this.action = 46; },
            handlePoiMedical()           { this.action = 47; },

            // Toolbar legend-position picker — 'hidden' means "don't show it".
            // rebuildLegend() both applies the new position immediately and
            // persists it on the canvas's 'main' layer (see DrawingTools.vue),
            // then this saves so the choice isn't lost if the user navigates
            // away before making another edit.
            handleLegendPositionChange(position) {
                this.legendPosition = position;
                if (this.$refs.canvasContainer && this.$refs.canvasContainer.rebuildLegend) {
                    this.$refs.canvasContainer.rebuildLegend(position, this.legendScale);
                }
                const scope = this.$refs.canvasContainer.getCanvasScope();
                if (scope) scope.view.update();
                this.saveCanvasData();
            },

            // Toolbar legend-scale picker — same reasoning/flow as
            // handleLegendPositionChange above, just for the size multiplier.
            handleLegendScaleChange(scale) {
                this.legendScale = scale;
                if (this.$refs.canvasContainer && this.$refs.canvasContainer.rebuildLegend) {
                    this.$refs.canvasContainer.rebuildLegend(this.legendPosition, scale);
                }
                const scope = this.$refs.canvasContainer.getCanvasScope();
                if (scope) scope.view.update();
                this.saveCanvasData();
            },

            handlePolygon() {
                this.action = 12;
            },

            handleText() {
                this.action = 13;
            },

            handleSelection() {
                this.action = 14;
            },

            handleCrop() {
                this.action = 15;
            },

            handleEditPoints() {
                this.action = 16;
            },

            handleMoveAll() {
                this.action = 17;
            },

            handleResize() {
                this.action = 19;
            },

            handleContinueLine() {
                this.action = 20;
            },

            // Export methods are now handled directly in the new methods below

            handleUndo() {
                this.$refs.canvasContainer.undoLastAction();
            },

            handleRedo() {
                this.$refs.canvasContainer.redoLastAction();
            },

            onHistoryChanged(histLen, redoLen) {
                this.historyCount = histLen;
                this.redoCount = redoLen;
            },

            onZoomChanged(zoom) {
                this.currentZoom = zoom;
            },

            // Canvas event handlers
            handleCanvasData(event) {
                this.$emit('canvas_data', event);
            },

            // Method to trigger canvas data emission
            emitCanvasData() {
                this.$refs.canvasContainer.saveCanvasData();
            },

            // Method to get current canvas data and emit it
            getAndEmitCanvasData() {
                // Delegate to saveCanvasData() which uses _getDrawingJson() to exclude
                // background and related layers from the emitted JSON.
                this.$refs.canvasContainer.saveCanvasData();
            },

            // Finds a Paper.js item anywhere in the project by its unique numeric id.
            // Using id instead of name avoids false matches when two items share the same name.
            _itemById(id) {
                const scope = this.$refs.canvasContainer.getCanvasScope();
                if (!scope || !scope.project) return null;
                for (const layer of scope.project.layers) {
                    for (const item of layer.children) {
                        if (item.id === id) return item;
                        if (item.children) {
                            for (const child of item.children) {
                                if (child.id === id) return child;
                            }
                        }
                    }
                }
                return null;
            },

            // Finds a Paper.js Layer by its numeric id (used for related layers).
            _layerById(id) {
                const scope = this.$refs.canvasContainer.getCanvasScope();
                if (!scope || !scope.project) return null;
                return scope.project.layers.find(l => l.id === id) || null;
            },

            // Resolves a layers-panel row (top-level item/group, related-route
            // entry, or group child) to its live Paper.js item/Layer.
            _resolveLayerNode(node) {
                return node.isRelated ? this._layerById(node.id) : this._itemById(node.id);
            },

            // Hover-highlight: when the cursor sits on a layer row, show exactly
            // where that item lives on the drawing paper. Reuses Paper.js's own
            // native "selected" bounds/handles rendering (the same visual the
            // selection/move tools already use — see CanvasHandlers.vue) instead
            // of drawing a separate overlay, so no extra canvas items are added
            // that would need excluding from JSON export / undo history / the
            // layers list. Saves/restores the item's PRIOR selected state so a
            // hover never clobbers a real, tool-driven selection underneath it.
            highlightLayerOnCanvas(node) {
                const item = this._resolveLayerNode(node);
                if (!item) return;
                if (this.hoverHighlightId !== null && this.hoverHighlightId !== item.id) {
                    this._restoreHoverHighlight();
                }
                this.hoverHighlightId = item.id;
                this.hoverHighlightPrevSelected = item.selected;
                item.selected = true;
                const scope = this.$refs.canvasContainer.getCanvasScope();
                if (scope) scope.view.update();
            },

            unhighlightLayerOnCanvas() {
                this._restoreHoverHighlight();
                const scope = this.$refs.canvasContainer.getCanvasScope();
                if (scope) scope.view.update();
            },

            _restoreHoverHighlight() {
                if (this.hoverHighlightId === null) return;
                const item = this._itemById(this.hoverHighlightId) || this._layerById(this.hoverHighlightId);
                if (item) item.selected = this.hoverHighlightPrevSelected;
                this.hoverHighlightId = null;
                this.hoverHighlightPrevSelected = false;
            },

            // Recursively sets locked state on an item and all its children.
            _deepSetLocked(item, locked) {
                if (!item) return;
                item.locked = locked;
                if (item.children) item.children.forEach(c => this._deepSetLocked(c, locked));
            },

            // Stable type checks based on Paper.js item.data flags (set at creation
            // time — see DrawingTools.vue's createGroup/add_arrow), falling back to
            // the legacy name-prefix convention for items saved before these flags
            // existed. Using .data instead of parsing item.name means renaming a
            // group or arrow in the layers panel can no longer silently break its
            // own color/width controls — previously anything checking
            // `name.startsWith('group ')`/`'arrow '` simply stopped matching the
            // moment a user renamed the item.
            _isGroupContainer(item) {
                if (!item) return false;
                return !!(item.data && item.data.isLayerGroup) || !!(item.name && item.name.startsWith('group '));
            },
            _isArrowContainer(item) {
                if (!item) return false;
                return !!(item.data && item.data.isArrow) || !!(item.name && item.name.startsWith('arrow '));
            },
            _isRappelContainer(item) {
                if (!item) return false;
                return !!(item.data && item.data.isRappel) || !!(item.name && item.name.startsWith('rappel '));
            },
            _isBoltContainer(item) {
                if (!item) return false;
                return !!(item.data && item.data.isBolt) || !!(item.name && item.name.startsWith('bolt '));
            },
            _isPinContainer(item) {
                if (!item) return false;
                return !!(item.data && item.data.isPin) || !!(item.name && item.name.startsWith('pin '));
            },
            _isPendulumContainer(item) {
                if (!item) return false;
                return !!(item.data && item.data.isPendulum) || !!(item.name && item.name.startsWith('pendulum '));
            },
            _isCruxContainer(item) {
                if (!item) return false;
                return !!(item.data && item.data.isCrux) || !!(item.name && item.name.startsWith('crux '));
            },
            // Anchor-family marker (see DrawingTools.vue's add_anchor/
            // _buildAnchorParts) — general/rappel/rescue hollow-ring quality
            // markers, distinct from the older solid-dot isRappel above.
            _isAnchorContainer(item) {
                return !!(item && item.data && item.data.isAnchorSymbol);
            },
            // Landmark markers (see DrawingTools.vue's add_summit/add_tent) —
            // general map-style glyphs, unrelated to the anchor family above.
            _isSummitContainer(item) {
                return !!(item && item.data && item.data.isSummitMarker);
            },
            _isTentContainer(item) {
                return !!(item && item.data && item.data.isTentMarker);
            },
            _isParkingContainer(item) {
                return !!(item && item.data && item.data.isParkingMarker);
            },
            // Points-of-interest pins (see DrawingTools.vue's add_poi/
            // _buildPoiParts) — hiking/bed/bike/water/food/tent/medical.
            _isPoiContainer(item) {
                return !!(item && item.data && item.data.isPoiMarker);
            },
            // True for any marker in the toolbar's "Points of Interest" group
            // — the 7 isPoiMarker pins above PLUS Summit and Parking (moved
            // into that same toolbar group; see ToolbarComponent.vue). All of
            // them are fixed-meaning signs/glyphs, not artist-drawn shapes,
            // so color must never be user-editable for any of them — used by
            // _setItemColor's early-return below and by the `isPoi` layer-row
            // flag (drives LayersPanelComponent's disabled color swatch +
            // 100px size cap). Deliberately excludes Tent, which stayed in
            // the Anchors group and keeps its own selectable color.
            _isColorLockedMarker(item) {
                return this._isPoiContainer(item) || this._isSummitContainer(item) || this._isParkingContainer(item);
            },
            // Sector name-label (see sectorLocaleImageEditorComponent.vue's
            // _createSectorLabel) — a [bg, text] Group, children[0]=bg.
            _isSectorLabelContainer(item) {
                return !!(item && item.data && item.data.isSectorLabel);
            },
            // True for any of the fixed-shape single-click marker types above
            // (bolt/pin/pendulum/crux/anchor/summit/tent/parking/poi/sector-
            // label) — used wherever they all behave the same way for READING
            // color/width (children[0]-driven), so callers don't need to
            // chain all these checks individually. NOT used for writing color
            // (see _setItemColor's own summit/tent/parking/poi/sector-label
            // branch, which must skip the white hole/doorway/border/letter/
            // icon/text instead of recoloring every child uniformly).
            _isFixedMarkerContainer(item) {
                return this._isBoltContainer(item) || this._isPinContainer(item)
                    || this._isPendulumContainer(item) || this._isCruxContainer(item)
                    || this._isAnchorContainer(item) || this._isSummitContainer(item)
                    || this._isTentContainer(item) || this._isParkingContainer(item)
                    || this._isPoiContainer(item) || this._isSectorLabelContainer(item);
            },
            _isTextItem(item) {
                return !!item && (item instanceof paper.PointText || (item.name && item.name.startsWith('text ')));
            },

            // Returns the CSS hex color of a Paper.js item (or its first child for groups/arrows/rappel/bolt/pin/pendulum/crux markers).
            _getItemColor(item) {
                if (!item) return '#999999';
                if ((this._isGroupContainer(item) || this._isArrowContainer(item) || this._isRappelContainer(item) || this._isFixedMarkerContainer(item)) && item.children && item.children.length > 0) {
                    return this._getItemColor(item.children[0]);
                }
                const color = item.strokeColor || item.fillColor;
                if (!color) return '#999999';
                try { return color.toCSS(true); } catch (e) { return '#999999'; }
            },

            _getItemWidth(item) {
                if (!item) return 3;
                if (this._isGroupContainer(item) && item.children && item.children.length > 0) {
                    // The group-level control is labeled/behaves as the route LINE's stroke
                    // width (see LayersPanelComponent's "stroke_width_px_tooltip"), so it must
                    // read the route-line child specifically, not children[0] (which is the
                    // number label) — otherwise it displays the label's font size here.
                    const lineChild = item.children.find(c => (c.data && c.data.isRouteLine) || (c.name && c.name.startsWith('line ')));
                    return this._getItemWidth(lineChild || item.children[0]);
                }
                if (this._isArrowContainer(item) && item.children && item.children.length > 0) {
                    // An arrow is a Group of [shaft, head] — its "width" is the shaft's
                    // stroke width; the head's size is derived from that (see updateArrow).
                    const shaft = item.children.find(c => (c.data && c.data.isArrowShaft) || (c.name && c.name.startsWith('arrow-shaft')));
                    return this._getItemWidth(shaft || item.children[0]);
                }
                if (this._isRappelContainer(item) && item.children && item.children.length > 0) {
                    // A rappel marker is a Group of [circle, shaft, head] — its "width" is
                    // the circle's stroke width, which add_rappel/resizeRappel always keep
                    // equal to the shaft/chevron's (see DrawingTools.vue).
                    return this._getItemWidth(item.children[0]);
                }
                if (this._isSectorLabelContainer(item) && item.children) {
                    // A sector label's meaningful "size" is its TEXT's font
                    // size, not children[0] (the bg box's 1.5px border stroke)
                    // — the generic isFixedMarkerContainer branch below would
                    // display that border width instead, which looked like
                    // "the size control does nothing" even once resizeSectorLabel
                    // (see DrawingTools.vue) made writes work.
                    const text = item.children.find(c => this._isTextItem(c));
                    return text ? (Math.round(text.fontSize) || 15) : 15;
                }
                if (this._isFixedMarkerContainer(item) && item.children && item.children.length > 0) {
                    // Bolt/pin/pendulum/crux markers are Groups whose parts all share one
                    // stroke width (see their add_*/resize* pairs in DrawingTools.vue) —
                    // same reasoning as the rappel branch above.
                    return this._getItemWidth(item.children[0]);
                }
                if (this._isTextItem(item)) {
                    return Math.round(item.fontSize) || 16;
                }
                return item.strokeWidth || 3;
            },

            _setItemColor(item, color) {
                if (!item) return;
                // A "Points of Interest" family marker (POI pin, Summit, or
                // Parking — see _isColorLockedMarker) is a fixed-color sign,
                // never user-recolorable — see DrawingTools.vue's _poiColor/
                // _summitColor/_parkingColor (which also ignore the
                // toolbar's chosen fill color at creation time) and
                // LayersPanelComponent.vue, which disables this item's color
                // swatch entirely for the same reason.
                if (this._isColorLockedMarker(item)) return;
                if ((this._isTentContainer(item) || this._isSectorLabelContainer(item)) && item.children) {
                    // Recolor only the solid parts — skip the white hole/
                    // doorway/border/letter/icon/text (see DrawingTools.vue's
                    // _buildTentParts/sectorLocaleImageEditorComponent's
                    // _createSectorLabel, which all tag it `data.isHole`), or
                    // the generic "recolor every child" behavior below would
                    // paint over the punched-out look that makes these read
                    // as a tent silhouette, or turn a sector label's black
                    // text the same color as its box.
                    item.children.forEach(child => {
                        if (child.data && child.data.isHole) return;
                        this._setItemColor(child, color);
                    });
                    return;
                }
                if ((this._isGroupContainer(item) || this._isArrowContainer(item) || this._isRappelContainer(item) || this._isFixedMarkerContainer(item)) && item.children) {
                    [...item.children].forEach(child => this._setItemColor(child, color));
                    return;
                }
                if (item.strokeColor !== null && item.strokeColor !== undefined) item.strokeColor = color;
                if (item.fillColor !== null && item.fillColor !== undefined) item.fillColor = color;
            },

            _setItemWidth(item, width) {
                if (!item) return;
                if (this._isGroupContainer(item) && item.children) {
                    // The group-level control only represents/edits the route LINE's stroke
                    // width (see _getItemWidth above and the "stroke_width_px_tooltip" label
                    // it's shown under) — it must never also resize the number label, or one
                    // control silently inflates both the line and the number together. The
                    // number label has its own dedicated size control in the expanded
                    // per-child rows.
                    [...item.children].forEach(child => {
                        if (this._isTextItem(child)) return;
                        this._setItemWidth(child, width);
                    });
                    return;
                }
                if (this._isArrowContainer(item)) {
                    // Resize the shaft AND recompute the arrowhead geometry together
                    // (see CanvasContainerComponent.resizeArrow / DrawingTools.resizeArrow)
                    // — setting only the shaft's strokeWidth would leave the head at its
                    // original fixed size, so a thick shaft visually swallows a tiny head.
                    this.$refs.canvasContainer.resizeArrow(item, width);
                    return;
                }
                if (this._isRappelContainer(item)) {
                    // Same reasoning as the arrow branch above, for the circle/shaft/
                    // chevron trio (see CanvasContainerComponent.resizeRappel /
                    // DrawingTools.resizeRappel).
                    this.$refs.canvasContainer.resizeRappel(item, width);
                    return;
                }
                if (this._isBoltContainer(item)) {
                    this.$refs.canvasContainer.resizeBolt(item, width);
                    return;
                }
                if (this._isPinContainer(item)) {
                    this.$refs.canvasContainer.resizePin(item, width);
                    return;
                }
                if (this._isPendulumContainer(item)) {
                    this.$refs.canvasContainer.resizePendulum(item, width);
                    return;
                }
                if (this._isCruxContainer(item)) {
                    this.$refs.canvasContainer.resizeCrux(item, width);
                    return;
                }
                if (this._isAnchorContainer(item)) {
                    this.$refs.canvasContainer.resizeAnchor(item, width);
                    return;
                }
                if (this._isSummitContainer(item)) {
                    this.$refs.canvasContainer.resizeSummit(item, width);
                    return;
                }
                if (this._isTentContainer(item)) {
                    this.$refs.canvasContainer.resizeTent(item, width);
                    return;
                }
                if (this._isParkingContainer(item)) {
                    this.$refs.canvasContainer.resizeParking(item, width);
                    return;
                }
                if (this._isPoiContainer(item)) {
                    this.$refs.canvasContainer.resizePoi(item, width);
                    return;
                }
                if (this._isSectorLabelContainer(item)) {
                    this.$refs.canvasContainer.resizeSectorLabel(item, width);
                    return;
                }
                if (this._isTextItem(item)) {
                    item.fontSize = width;
                } else if (item.strokeWidth !== undefined) {
                    item.strokeWidth = width;
                }
            },

            changeLayerColor(layer, color) {
                const item = this._itemById(layer.id);
                if (!item) return;
                this._setItemColor(item, color);
                const scope = this.$refs.canvasContainer.getCanvasScope();
                if (scope) scope.view.update();
                this.updateLayersList();
                this.saveCanvasData();
            },

            changeLayerSize(layer, width) {
                const item = this._itemById(layer.id);
                if (!item) return;
                this._setItemWidth(item, parseInt(width));
                const scope = this.$refs.canvasContainer.getCanvasScope();
                if (scope) scope.view.update();
                this.updateLayersList();
                this.saveCanvasData();
            },

            changeChildColor(layer, child, color) {
                const item = this._itemById(child.id);
                if (!item) return;
                this._setItemColor(item, color);
                const scope = this.$refs.canvasContainer.getCanvasScope();
                if (scope) scope.view.update();
                this.updateLayersList();
                this.saveCanvasData();
            },

            changeChildSize(layer, child, width) {
                const item = this._itemById(child.id);
                if (!item) return;
                this._setItemWidth(item, parseInt(width));
                const scope = this.$refs.canvasContainer.getCanvasScope();
                if (scope) scope.view.update();
                this.updateLayersList();
                this.saveCanvasData();
            },

            // Builds a human-readable display name for a layer item.
            // Renames "point" → "dot", strips "group" prefix for groups,
            // and appends the route name when one is provided.
            _formatLayerName(rawName, isGroup) {
                if (!rawName) return 'unnamed';
                let display = rawName;
                if (isGroup) {
                    display = display.replace(/^group /, '');
                } else {
                    display = display.replace(/^point /, 'dot ');
                }
                if (this.route_name) display += ` ${this.route_name}`;
                return display;
            },

            updateLayersList() {
                const scope = this.$refs.canvasContainer.getCanvasScope();
                if (!scope || !scope.project) { this.layers = []; return; }

                if (this.$refs.canvasContainer.getLegendPosition) {
                    this.legendPosition = this.$refs.canvasContainer.getLegendPosition();
                }
                if (this.$refs.canvasContainer.getLegendScale) {
                    this.legendScale = this.$refs.canvasContainer.getLegendScale();
                }

                // Keyed by id, not name — two groups can end up with the same
                // user-given name (nothing enforces uniqueness), which would
                // otherwise bleed one group's expanded state into the other's.
                const expandedStates = {};
                this.layers.forEach(layer => {
                    if (layer.isGroup && layer.expanded) expandedStates[layer.id] = true;
                });

                // Colors must match those used in importRelatedJsons
                const relatedColors = ['#0000ff', '#00cc00', '#ff00ff', '#cccc00', '#00cccc', '#ff8000', '#8000ff', '#00ff80', '#ff0080', '#808080'];
                const mainItems = [];
                const relatedEntries = [];

                scope.project.layers.forEach(layer => {
                    if (layer.name === 'background') return;

                    if (layer.name && layer.name.startsWith('related-')) {
                        const idx = parseInt(layer.name.replace('related-', ''));
                        const firstChild = layer.children[0];
                        const isLocked = !firstChild || firstChild.locked !== false;
                        // related_first_label reserves index 0 for something that
                        // ISN'T another route/layout (the extra drawing, or the
                        // currently-edited item shown as reference) — see the
                        // relatedJsons computed in the page component. Without this,
                        // it silently got numbered/colored as if it were just one
                        // more route, and shifted every real route's number by one.
                        const hasSpecialFirst = !!this.related_first_label;
                        const isSpecialFirst = hasSpecialFirst && idx === 0;
                        const routeNumber = idx - (hasSpecialFirst ? 1 : 0) + 1;
                        relatedEntries.push({
                            id: layer.id,
                            name: layer.name,
                            displayName: isSpecialFirst ? this.related_first_label : `Route ${routeNumber}`,
                            color: isSpecialFirst ? '#808080' : relatedColors[(routeNumber - 1) % relatedColors.length],
                            visible: layer.visible !== false,
                            locked: isLocked,
                            isRelated: true,
                            isExtraInfo: isSpecialFirst,
                            isGroup: false,
                            children: []
                        });
                        return;
                    }

                    layer.children.forEach(item => {
                        // The auto-generated topo-symbol legend (DrawingTools.vue's
                        // rebuildLegend) is derived/system-managed content, not a
                        // normal user layer — its only control is the toolbar's
                        // position picker, so it's hidden from this list rather than
                        // shown as a deletable/colorable row that would just get
                        // regenerated on the next add/erase anyway.
                        if (item.data && item.data.isLegend) return;
                        if (this._isGroupContainer(item)) {
                            mainItems.push({
                                id: item.id,
                                name: item.name || 'unnamed',
                                displayName: this._formatLayerName(item.name, true),
                                color: this._getItemColor(item),
                                strokeWidth: this._getItemWidth(item),
                                visible: item.visible !== false,
                                locked: item.locked || false,
                                layerName: layer.name,
                                isGroup: true,
                                expanded: expandedStates[item.id] || false,
                                isEditing: false,
                                editText: '',
                                children: item.children.map(child => ({
                                    id: child.id,
                                    name: child.name || 'unnamed',
                                    displayName: this._formatLayerName(child.name, false),
                                    color: this._getItemColor(child),
                                    strokeWidth: this._getItemWidth(child),
                                    visible: child.visible !== false,
                                    locked: child.locked || false,
                                    parentGroup: item.name,
                                    isLine: !!(child.data && child.data.isRouteLine) || (child.name && child.name.startsWith('line ')),
                                    isText: this._isTextItem(child),
                                    textContent: (child instanceof paper.PointText) ? child.content : (child.name && child.name.startsWith('text ') ? child.content : null),
                                    isEditing: false,
                                    editText: ''
                                }))
                            });
                        } else if (!item.parent || !this._isGroupContainer(item.parent)) {
                            mainItems.push({
                                id: item.id,
                                name: item.name || 'unnamed',
                                displayName: this._formatLayerName(item.name, false),
                                color: this._getItemColor(item),
                                strokeWidth: this._getItemWidth(item),
                                visible: item.visible !== false,
                                locked: item.locked || false,
                                layerName: layer.name,
                                isGroup: false,
                                isArrow: this._isArrowContainer(item),
                                isRappel: this._isRappelContainer(item),
                                isBolt: this._isBoltContainer(item),
                                isPin: this._isPinContainer(item),
                                isPendulum: this._isPendulumContainer(item),
                                isCrux: this._isCruxContainer(item),
                                isAnchor: this._isAnchorContainer(item),
                                isSummit: this._isSummitContainer(item),
                                isTent: this._isTentContainer(item),
                                isParking: this._isParkingContainer(item),
                                isPoi: this._isColorLockedMarker(item),
                                isSectorLabel: this._isSectorLabelContainer(item),
                                isText: this._isTextItem(item),
                                textContent: (item instanceof paper.PointText) ? item.content : (item.name && item.name.startsWith('text ') ? item.content : null),
                                isEditing: false,
                                editText: ''
                            });
                        }
                    });
                });

                this.layers = [...mainItems, ...relatedEntries];

                // Drop selection ids for items that no longer exist as selectable
                // (non-group, non-related) rows — e.g. deleted, or just grouped away.
                const selectableIds = new Set(mainItems.filter(l => !l.isGroup).map(l => l.id));
                this.selectedLayerIds = this.selectedLayerIds.filter(id => selectableIds.has(id));
            },

            toggleLayerVisibility(layer) {
                if (layer.isRelated) {
                    const paperLayer = this._layerById(layer.id);
                    if (!paperLayer) return;
                    paperLayer.visible = !paperLayer.visible;
                    const scope = this.$refs.canvasContainer.getCanvasScope();
                    if (scope) scope.view.update();
                    this.updateLayersList();
                    return;
                }
                const item = this._itemById(layer.id);
                if (!item) return;
                item.visible = !item.visible;
                const scope = this.$refs.canvasContainer.getCanvasScope();
                if (scope) scope.view.update();
                this.updateLayersList();
            },

            toggleLayerLock(layer) {
                if (layer.isRelated) {
                    const paperLayer = this._layerById(layer.id);
                    if (!paperLayer) return;
                    const newLocked = !layer.locked;
                    [...paperLayer.children].forEach(child => this._deepSetLocked(child, newLocked));
                    const scope = this.$refs.canvasContainer.getCanvasScope();
                    if (scope) scope.view.update();
                    this.updateLayersList();
                    return;
                }
                const item = this._itemById(layer.id);
                if (!item) return;
                item.locked = !item.locked;
                const scope = this.$refs.canvasContainer.getCanvasScope();
                if (scope) scope.view.update();
                this.updateLayersList();
            },

            toggleLayersVisibility() {
                const scope = this.$refs.canvasContainer.getCanvasScope();
                if (!scope || !scope.project) return;
                const allVisible = this.layers.every(layer => layer.visible);
                const newVisibility = !allVisible;
                this.layers.forEach(layer => {
                    const item = this._itemById(layer.id);
                    if (item) item.visible = newVisibility;
                });
                scope.view.update();
                this.updateLayersList();
            },

            deleteLayerItem(layer) {
                const confirmKey = layer.isGroup ? 'admin.articles.canvas_editor.confirm_delete_group_named' : 'admin.articles.canvas_editor.confirm_delete_item_named';
                if (!confirm(this.$t(confirmKey, { name: layer.displayName }))) return;
                const item = this._itemById(layer.id);
                if (!item) return;
                // Remove associated text label if this is a rectangle
                if (item.data && item.data.textLabel) item.data.textLabel.remove();
                // Sector-name label — remove its sibling leader line too (a
                // separate top-level item, found by flag rather than a stored
                // reference — see CanvasHandlers.vue's action-8 drag handler).
                if (item.data && item.data.isSectorLabel && item.layer) {
                    const line = item.layer.children.find(c => c.data && c.data.isSectorLabelLine);
                    if (line) line.remove();
                }
                item.remove();
                if (this.$refs.canvasContainer.rebuildLegend) this.$refs.canvasContainer.rebuildLegend();
                const scope = this.$refs.canvasContainer.getCanvasScope();
                if (scope) scope.view.update();
                this.updateLayersList();
                this.saveCanvasData();
            },

            toggleGroupExpansion(layer) {
                layer.expanded = !layer.expanded;
            },

            toggleChildVisibility(layer, child) {
                const item = this._itemById(child.id);
                if (!item) return;
                item.visible = !item.visible;
                const scope = this.$refs.canvasContainer.getCanvasScope();
                if (scope) scope.view.update();
                this.updateLayersList();
            },

            toggleChildLock(layer, child) {
                const item = this._itemById(child.id);
                if (!item) return;
                item.locked = !item.locked;
                const scope = this.$refs.canvasContainer.getCanvasScope();
                if (scope) scope.view.update();
                this.updateLayersList();
            },

            deleteChildItem(layer, child) {
                if (!confirm(this.$t('admin.articles.canvas_editor.confirm_delete_child_from_group', { child: child.displayName, group: layer.displayName }))) return;
                const item = this._itemById(child.id);
                if (!item) return;
                if (item.data && item.data.textLabel) item.data.textLabel.remove();
                item.remove();
                if (this.$refs.canvasContainer.rebuildLegend) this.$refs.canvasContainer.rebuildLegend();
                const scope = this.$refs.canvasContainer.getCanvasScope();
                if (scope) scope.view.update();
                this.updateLayersList();
                this.saveCanvasData();
            },

            moveLayerUp(index) {
                if (index > 0) {
                    const currentLayer = this.layers[index];
                    const aboveLayer = this.layers[index - 1];
                    if (currentLayer.isRelated || aboveLayer.isRelated) return;

                    if (currentLayer.layerName === aboveLayer.layerName) {
                        // Swap the layer with the one above it
                        const temp = this.layers[index];
                        this.layers.splice(index, 1);
                        this.layers.splice(index - 1, 0, temp);
                        // Update the actual Paper.js layer order
                        this.updatePaperLayerOrder();
                    }
                }
            },

            moveLayerDown(index) {
                if (index < this.layers.length - 1) {
                    const currentLayer = this.layers[index];
                    const belowLayer = this.layers[index + 1];
                    if (currentLayer.isRelated || belowLayer.isRelated) return;

                    if (currentLayer.layerName === belowLayer.layerName) {
                        // Swap the layer with the one below it
                        const temp = this.layers[index];
                        this.layers.splice(index, 1);
                        this.layers.splice(index + 1, 0, temp);
                        // Update the actual Paper.js layer order
                        this.updatePaperLayerOrder();
                    }
                }
            },

            updatePaperLayerOrder() {
                const scope = this.$refs.canvasContainer.getCanvasScope();
                if (!scope || !scope.project) return;

                // Group the UI layers by their layerName (paper layer name)
                const layersByName = {};
                this.layers.forEach(layer => {
                    if (!layersByName[layer.layerName]) layersByName[layer.layerName] = [];
                    layersByName[layer.layerName].push(layer);
                });

                // Reorder each paper layer's children to match the UI order using item IDs
                scope.project.layers.forEach(paperLayer => {
                    const ordered = layersByName[paperLayer.name];
                    if (!ordered) return;
                    // Build the desired ordered list of Paper.js items
                    const orderedItems = ordered
                        .map(l => paperLayer.children.find(c => c.id === l.id))
                        .filter(Boolean);
                    // Append each item in order (Paper.js moves the item to the end of children)
                    orderedItems.forEach(item => paperLayer.addChild(item));
                });

                scope.view.update();
                this.saveCanvasData();
            },

            toggleLayerSelection(layer) {
                const idx = this.selectedLayerIds.indexOf(layer.id);
                if (idx === -1) this.selectedLayerIds.push(layer.id);
                else this.selectedLayerIds.splice(idx, 1);
            },

            // Groups multiple selected top-level items into one new group at once,
            // running the same paper.Group#addChild reparenting assignItemToGroup
            // uses for a single item, once per selected item.
            createGroupFromSelection(ids) {
                if (!ids || ids.length < 2) return;
                if (!confirm(this.$t('admin.articles.canvas_editor.confirm_create_group_from_selection', { count: ids.length }))) return;
                const scope = this.$refs.canvasContainer.getCanvasScope();
                if (!scope || !scope.project) return;
                const items = ids.map(id => this._itemById(id)).filter(Boolean);
                if (items.length < 2) return;
                const foundLayer = items[0].layer;
                const newGroup = new paper.Group();
                const currentCount = this.$refs.canvasContainer.getGroupCounter();
                newGroup.name = `group ${currentCount + 1}`;
                newGroup.data = { isLayerGroup: true };
                this.$refs.canvasContainer.setGroupCounter(currentCount + 1);
                items.forEach(item => newGroup.addChild(item)); // re-parents each, preserves order
                foundLayer.addChild(newGroup);
                this.selectedLayerIds = [];
                scope.view.update();
                this.updateLayersList();
                this.saveCanvasData();
            },

            ungroupLayer(layer) {
                if (!confirm(this.$t('admin.articles.canvas_editor.confirm_ungroup', { name: layer.displayName }))) return;
                const scope = this.$refs.canvasContainer.getCanvasScope();
                if (!scope || !scope.project) return;
                const foundGroup = this._itemById(layer.id);
                if (!foundGroup || !(foundGroup instanceof paper.Group)) return;
                const parentLayer = foundGroup.parent; // Direct parent (the Layer)
                // Copy children first — live list shifts as items are re-parented
                const children = [...foundGroup.children];
                children.forEach(child => parentLayer.addChild(child)); // re-parents each child
                foundGroup.remove();
                scope.view.update();
                this.updateLayersList();
                this.saveCanvasData();
            },

            // Unified group (re)assignment for one item, used by BOTH the layers
            // panel's per-row "move to group" picker and drag-and-drop — replacing
            // the old create-group / move-to-group-modal / move-child-out-of-group
            // trio, which each duplicated similar reparenting logic and, in the
            // move-to-group-modal case, silently moved the item into whichever
            // group happened to be FIRST in the list instead of asking which one
            // the user actually meant (the root cause of "doesn't add to the
            // correct group"). `value` is one of:
            //   ''       — ungroup / move to top level
            //   '__new__' — create a brand-new group containing just this item
            //   <id>     — move into the existing group with that Paper.js id
            // Works identically whether `itemId` currently sits at the top level
            // or inside a different group, so moving an already-grouped item
            // straight into another group is a single action, not ungroup-then-regroup.
            assignItemToGroup(itemId, value) {
                const scope = this.$refs.canvasContainer.getCanvasScope();
                if (!scope || !scope.project) return;
                const foundItem = this._itemById(itemId);
                if (!foundItem) return;

                if (value === '__new__') {
                    const foundLayer = foundItem.layer;
                    const newGroup = new paper.Group();
                    const currentCount = this.$refs.canvasContainer.getGroupCounter();
                    newGroup.name = `group ${currentCount + 1}`;
                    newGroup.data = { isLayerGroup: true };
                    this.$refs.canvasContainer.setGroupCounter(currentCount + 1);
                    newGroup.addChild(foundItem); // re-parents automatically
                    foundLayer.addChild(newGroup);
                } else if (!value) {
                    // foundItem.layer walks all the way up to the enclosing Layer
                    // regardless of nesting depth, so this always lands at top level.
                    foundItem.layer.addChild(foundItem);
                } else {
                    const targetId = parseInt(value, 10);
                    const foundGroup = this._itemById(targetId);
                    // _isGroupContainer (not instanceof paper.Group) on purpose — an
                    // arrow is ALSO a paper.Group internally, but must never be used
                    // as a drop target for other items.
                    if (!foundGroup || !this._isGroupContainer(foundGroup)) return;
                    foundGroup.addChild(foundItem); // re-parents automatically
                }

                scope.view.update();
                this.updateLayersList();
                this.saveCanvasData();
            },

            deleteAllLayers() {
                if (!confirm(this.$t('admin.articles.canvas_editor.confirm_delete_all_layers'))) return;
                const scope = this.$refs.canvasContainer.getCanvasScope();
                if (!scope || !scope.project) return;
                scope.project.layers.forEach(layer => {
                    if (layer.name && (layer.name.startsWith('related-') || layer.name === 'background')) return;
                    layer.removeChildren();
                });
                if (this.$refs.canvasContainer.rebuildLegend) this.$refs.canvasContainer.rebuildLegend();
                scope.view.update();
                this.updateLayersList();
                this.saveCanvasData();
            },

            finishEditingLayerName(layer, newName) {
                const foundItem = this._itemById(layer.id);
                if (foundItem && newName) {
                    // Role (group/arrow) is tracked via foundItem.data, not the name
                    // (see _isGroupContainer/_isArrowContainer), so renaming is now
                    // free-form and can no longer break the item's own color/width
                    // controls. Backfill the flag for legacy items that only ever
                    // had the old name-prefix convention, so it keeps working going
                    // forward even without the prefix.
                    if (layer.isGroup) foundItem.data = { ...foundItem.data, isLayerGroup: true };
                    if (layer.isArrow) foundItem.data = { ...foundItem.data, isArrow: true };
                    if (layer.isRappel) foundItem.data = { ...foundItem.data, isRappel: true };
                    if (layer.isBolt) foundItem.data = { ...foundItem.data, isBolt: true };
                    if (layer.isPin) foundItem.data = { ...foundItem.data, isPin: true };
                    if (layer.isPendulum) foundItem.data = { ...foundItem.data, isPendulum: true };
                    if (layer.isCrux) foundItem.data = { ...foundItem.data, isCrux: true };
                    foundItem.name = newName;
                    this.saveCanvasData();
                    this.updateLayersList();
                }
            },

            cancelEditingLayerName() {
                // LayersPanelComponent already restored display state; just refresh the list
                this.updateLayersList();
            },

            finishEditingChildName(layer, child, newName) {
                const foundItem = this._itemById(child.id);
                if (foundItem && newName) {
                    // Backfill the route-line role marker for legacy items so the
                    // group's own width control (see _getItemWidth) can still find
                    // it after this rename even without the "line " prefix.
                    if (child.isLine) foundItem.data = { ...foundItem.data, isRouteLine: true };
                    foundItem.name = newName;
                    this.saveCanvasData();
                    this.updateLayersList();
                }
            },

            cancelEditingChildName() {
                this.updateLayersList();
            },

            finishEditingText(layer, newText) {
                const foundItem = this._itemById(layer.id);
                if (foundItem && foundItem instanceof paper.PointText) {
                    foundItem.content = newText;
                    this.saveCanvasData();
                    this.updateLayersList();
                }
            },

            cancelEditingText() {
                this.updateLayersList();
            },

            finishEditingChildText(layer, child, newText) {
                const foundItem = this._itemById(child.id);
                if (foundItem && foundItem instanceof paper.PointText) {
                    foundItem.content = newText;
                    this.saveCanvasData();
                    this.updateLayersList();
                }
            },

            cancelEditingChildText() {
                this.updateLayersList();
            },

            saveCanvasData() {
                this.$refs.canvasContainer.saveCanvasData();
            },

            // New methods for enhanced features
            updateCanvasView() {
                if (this.$refs.canvasContainer && this.$refs.canvasContainer.updateView) {
                    this.$refs.canvasContainer.updateView(this.zoomLevel, this.panOffset);
                }
            },

            handleExportPNG() {
                if (this.$refs.canvasContainer && this.$refs.canvasContainer.exportCanvas) {
                    this.$refs.canvasContainer.exportCanvas('png');
                }
            },

            handleExportSVG() {
                if (this.$refs.canvasContainer && this.$refs.canvasContainer.exportCanvas) {
                    this.$refs.canvasContainer.exportCanvas('svg');
                }
            },

            handleSaveImage() {
                if (this.$refs.canvasContainer && this.$refs.canvasContainer.exportCanvas) {
                    this.$refs.canvasContainer.exportCanvas('png');
                }
            },

            handleCropSave(croppedDataUrl) {
                // Emit to parent so each editor (sector, route, pitch) can save via its own endpoint
                this.$emit('crop-save', croppedDataUrl);
            },

            toggleFill() {
                this.fillEnabled = !this.fillEnabled;
                if (this.fillEnabled && !this.currentFillColor) {
                    this.currentFillColor = '#ffffff';
                }
                if (this.$refs.canvasContainer && this.$refs.canvasContainer.updateColors) {
                    this.$refs.canvasContainer.updateColors(this.currentStrokeColor, this.fillColor, this.strokeWidth, this.textSize, this.dotSize);
                }
            },

            handleColorChange(type, color) {
                if (type === 'stroke') {
                    this.currentStrokeColor = color;
                } else if (type === 'fill') {
                    this.currentFillColor = color;
                    this.fillEnabled = true;
                }
                if (this.$refs.canvasContainer && this.$refs.canvasContainer.updateColors) {
                    this.$refs.canvasContainer.updateColors(this.currentStrokeColor, this.fillColor, this.strokeWidth, this.textSize, this.dotSize);
                }
            },

            handleStrokeWidthChange(width) {
                this.strokeWidth = width;
                if (this.$refs.canvasContainer && this.$refs.canvasContainer.updateColors) {
                    this.$refs.canvasContainer.updateColors(this.currentStrokeColor, this.fillColor, this.strokeWidth, this.textSize, this.dotSize);
                }
            },

            // Text size and dot size are kept as their own reactive data properties
            // (not local variables in the draw methods) so a value set while
            // editing one route/layout stays in effect when switching to another —
            // see DrawingTools.vue's _textSize()/_dotSize() helpers, which read
            // the synced currentTextSize/currentDotSize on the canvas manager.
            handleTextSizeChange(size) {
                this.textSize = size;
                if (this.$refs.canvasContainer && this.$refs.canvasContainer.updateColors) {
                    this.$refs.canvasContainer.updateColors(this.currentStrokeColor, this.fillColor, this.strokeWidth, this.textSize, this.dotSize);
                }
            },

            handleDotSizeChange(size) {
                this.dotSize = size;
                if (this.$refs.canvasContainer && this.$refs.canvasContainer.updateColors) {
                    this.$refs.canvasContainer.updateColors(this.currentStrokeColor, this.fillColor, this.strokeWidth, this.textSize, this.dotSize);
                }
            },

            toggleSmoothLines() {
                this.smoothLines = !this.smoothLines;
                if (this.$refs.canvasContainer && this.$refs.canvasContainer.setSmoothLines) {
                    this.$refs.canvasContainer.setSmoothLines(this.smoothLines);
                }
            },

            // Keyboard shortcuts
            handleKeyDown(event) {
                // Don't intercept keyboard events when typing in input fields
                const isInputField = event.target.tagName === 'INPUT' || 
                                    event.target.tagName === 'TEXTAREA' || 
                                    event.target.tagName === 'SELECT' ||
                                    event.target.isContentEditable ||
                                    event.target.getAttribute('contenteditable') === 'true';
                
                if (isInputField) {
                    return; // Allow normal input behavior
                }
                
                if (event.ctrlKey || event.metaKey) {
                    switch (event.key) {
                        case 'z':
                            event.preventDefault();
                            if (event.shiftKey) {
                                this.handleRedo();
                            } else {
                                this.handleUndo();
                            }
                            break;
                        case 'y':
                            event.preventDefault();
                            this.handleRedo();
                            break;
                    }
                } else {
                    switch (event.key) {
                        case 'z':
                        case 'Z':
                            event.preventDefault();
                            this.handleZoomIn();
                            break;
                        case 'x':
                        case 'X':
                            event.preventDefault();
                            this.handleZoomOut();
                            break;
                        case 'p':
                        case 'P':
                            event.preventDefault();
                            this.handlePan();
                            break;
                        case 's':
                        case 'S':
                            event.preventDefault();
                            this.handleSelection();
                            break;
                    }
                }
            }
        }
    }
</script>

<style scoped>
    .canvas-editor {
        user-select: none;
    }
    .but_action {
        background-color: #a5a6a7;
    }
    .color-swatch-input {
        padding: 0;
        border: 1px solid #ccc;
        border-radius: 3px;
        cursor: pointer;
        flex-shrink: 0;
    }
    .color-swatch-input:disabled {
        cursor: default;
        filter: grayscale(0.6);
    }
    /* Groups the style bar's Stroke/Fill controls into one "Colors" chip and
       Width/Text/Symbol controls into one "Sizes" chip — a bordered/tinted
       pill with its own caption on the left edge, all on a single line, so
       it's clear which controls affect color vs. size without stacking rows
       (which previously left an odd gap between the controls and caption). */
    .style-chip {
        display: flex;
        align-items: center;
        gap: 14px;
        background: #f8f9fa;
        border: 1px solid #e9ecef;
        border-radius: 8px;
        padding: 6px 14px;
    }
    .style-chip-label {
        font-size: 9px;
        font-weight: 600;
        color: #6c757d;
        text-transform: uppercase;
        letter-spacing: 0.04em;
        white-space: nowrap;
        padding-right: 10px;
        border-right: 1px solid #dee2e6;
        display: inline-flex;
        align-items: center;
        gap: 5px;
    }
    /* Thin vertical divider between individual controls INSIDE a chip — makes
       each control (stroke / fill, width / text / symbol) read as its own
       distinct unit instead of a run-on row, without the visual weight of a
       full "vr" divider between every field. */
    .style-chip-sep {
        width: 1px;
        align-self: stretch;
        background: #dee2e6;
    }
    /* Fill/Smooth on-off toggles: same footprint and a visible ON/OFF label
       (not just an icon-opacity change) so the current state is obvious at a
       glance instead of reading as broken/unresponsive. */
    .toggle-btn {
        font-size: 11px;
        line-height: 1.6;
        font-weight: 600;
        min-width: 52px;
    }
</style>
