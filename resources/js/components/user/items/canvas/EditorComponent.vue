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

        <!-- Style controls bar (attached visually to toolbar) -->
        <div class="d-flex flex-wrap align-items-center gap-2 mb-2 px-2 py-1 bg-white border border-top-0 rounded-bottom" style="border-top: 1px solid #dee2e6 !important; border-radius: 0 0 4px 4px !important;">

            <!-- Stroke color -->
            <div class="d-flex align-items-center gap-1" :title="$t('admin.articles.canvas_editor.stroke_color_tooltip')">
                <span class="small text-muted">{{ $t('admin.articles.canvas_editor.stroke_label') }}</span>
                <input type="color" :value="currentStrokeColor"
                       @input="handleColorChange('stroke', $event.target.value)"
                       class="color-swatch-input"
                       style="width:28px; height:24px;">
                <code class="small" style="font-size:10px; color:#555;">{{ currentStrokeColor }}</code>
            </div>

            <div class="vr"></div>

            <!-- Fill color with on/off toggle -->
            <div class="d-flex align-items-center gap-1" :title="fillEnabled ? $t('admin.articles.canvas_editor.fill_color_enabled_tooltip') : $t('admin.articles.canvas_editor.fill_color_disabled_tooltip')">
                <span class="small text-muted">{{ $t('admin.articles.canvas_editor.fill_label') }}</span>
                <button type="button"
                        class="btn btn-sm py-0 px-1"
                        :class="fillEnabled ? 'btn-primary' : 'btn-outline-secondary'"
                        style="font-size:10px; line-height:1.6;"
                        @click="toggleFill"
                        :title="fillEnabled ? $t('admin.articles.canvas_editor.disable_fill_tooltip') : $t('admin.articles.canvas_editor.enable_fill_tooltip')">
                    <i :class="fillEnabled ? 'fa fa-tint' : 'fa fa-tint'" style="opacity: fillEnabled ? 1 : 0.4;"></i>
                </button>
                <input type="color" :value="fillColor || '#ffffff'"
                       @input="handleColorChange('fill', $event.target.value)"
                       :disabled="!fillEnabled"
                       class="color-swatch-input"
                       :style="{ width: '28px', height: '24px', opacity: fillEnabled ? 1 : 0.3, cursor: fillEnabled ? 'pointer' : 'default' }">
                <code v-if="fillEnabled" class="small" style="font-size:10px; color:#555;">{{ fillColor || '#fff' }}</code>
                <span v-else class="small text-muted" style="font-size:10px; font-style:italic;">{{ $t('admin.articles.canvas_editor.fill_off_label') }}</span>
            </div>

            <div class="vr"></div>

            <!-- Stroke width -->
            <div class="d-flex align-items-center gap-1" :title="$t('admin.articles.canvas_editor.stroke_width_pixels_tooltip')">
                <span class="small text-muted">{{ $t('admin.articles.canvas_editor.width_label') }}</span>
                <input type="range" min="1" max="20" :value="strokeWidth"
                       @input="handleStrokeWidthChange(parseInt($event.target.value))"
                       class="form-range" style="width:70px;">
                <span class="badge bg-primary" style="min-width:22px; font-size:11px;">{{ strokeWidth }}</span>
                <span class="small text-muted" style="font-size:10px;">px</span>
            </div>

            <div class="vr"></div>

            <!-- Smooth freehand lines toggle -->
            <div class="d-flex align-items-center gap-1" :title="smoothLines ? $t('admin.articles.canvas_editor.smooth_lines_enabled_tooltip') : $t('admin.articles.canvas_editor.smooth_lines_disabled_tooltip')">
                <span class="small text-muted">{{ $t('admin.articles.canvas_editor.smooth_lines_label') }}</span>
                <button type="button"
                        class="btn btn-sm py-0 px-1"
                        :class="smoothLines ? 'btn-primary' : 'btn-outline-secondary'"
                        style="font-size:10px; line-height:1.6;"
                        @click="toggleSmoothLines">
                    <i class="fa fa-magic"></i>
                </button>
            </div>

            <div class="vr"></div>

            <!-- Zoom -->
            <div class="d-flex align-items-center gap-1">
                <span class="small text-muted">{{ $t('admin.articles.canvas_editor.zoom_label') }}</span>
                <span class="badge bg-secondary" style="min-width:44px; font-size:11px; cursor:pointer;" @click="handleZoomReset" :title="$t('admin.articles.canvas_editor.click_to_reset_zoom_tooltip')">
                    {{ Math.round(currentZoom * 100) }}%
                </span>
                <button type="button" class="btn btn-sm btn-outline-secondary py-0 px-1" style="font-size:10px; line-height:1.6;"
                        @click="handleZoomReset" :title="$t('admin.articles.canvas_editor.reset_zoom_to_fit_canvas_tooltip')">
                    <i class="fa fa-search"></i>
                </button>
            </div>

        </div>

        <!-- Canvas + Layers sidebar -->
        <div class="row g-2">
            <div class="col-lg-9 col-md-8">
                <CanvasContainerComponent
                    :action="action"
                    :json_prop="json_prop"
                    :json_meta="json_meta"
                    :related_jsons="related_jsons"
                    :related_jsons_meta="related_jsons_meta"
                    :related_first_label="related_first_label"
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
            <div class="col-lg-3 col-md-4">
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
            smoothLines: true,
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
                    // Sync initial color/stroke values into the canvas manager
                    if (this.$refs.canvasContainer && this.$refs.canvasContainer.updateColors) {
                        this.$refs.canvasContainer.updateColors(this.currentStrokeColor, this.currentFillColor, this.strokeWidth);
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
            _isTextItem(item) {
                return !!item && (item instanceof paper.PointText || (item.name && item.name.startsWith('text ')));
            },

            // Returns the CSS hex color of a Paper.js item (or its first child for groups/arrows).
            _getItemColor(item) {
                if (!item) return '#999999';
                if ((this._isGroupContainer(item) || this._isArrowContainer(item)) && item.children && item.children.length > 0) {
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
                if (this._isTextItem(item)) {
                    return Math.round(item.fontSize) || 16;
                }
                return item.strokeWidth || 3;
            },

            _setItemColor(item, color) {
                if (!item) return;
                if ((this._isGroupContainer(item) || this._isArrowContainer(item)) && item.children) {
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
                item.remove();
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
                    this.$refs.canvasContainer.updateColors(this.currentStrokeColor, this.fillColor, this.strokeWidth);
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
                    this.$refs.canvasContainer.updateColors(this.currentStrokeColor, this.fillColor, this.strokeWidth);
                }
            },

            handleStrokeWidthChange(width) {
                this.strokeWidth = width;
                if (this.$refs.canvasContainer && this.$refs.canvasContainer.updateColors) {
                    this.$refs.canvasContainer.updateColors(this.currentStrokeColor, this.fillColor, this.strokeWidth);
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
</style>
