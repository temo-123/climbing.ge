<template>
    <div class="canvas-editor">

        <!-- Toolbar -->
        <div class="mb-1">
            <ToolbarComponent
                :action="action"
                :history-length="historyCount"
                :redo-length="redoCount"
                @reset="handleReset"
                @undo="handleUndo"
                @redo="handleRedo"
                @line="handleLine"
                @point="handlePoint"
                @number="handleNumber"
                @rectangle="handleRectangle"
                @combined="handleCombined"
                @eraser="handleEraser"
                @erase_segment="handleEraseSegment"
                @move="handleMove"
                @zoom-in="handleZoomIn"
                @zoom-out="handleZoomOut"
                @zoom-reset="handleZoomReset"
                @pan="handlePan"
                @circle="handleCircle"
                @ellipse="handleEllipse"
                @polygon="handlePolygon"
                @text="handleText"
                @selection="handleSelection"
                @export-png="handleExportPNG"
                @export-svg="handleExportSVG"
                @save-image="handleSaveImage"
            />
        </div>

        <!-- Color & Stroke Controls -->
        <div class="d-flex flex-wrap align-items-center gap-2 mb-2 px-2 py-1 bg-light border rounded">
            <span class="small fw-semibold text-secondary me-1">Style:</span>

            <div class="d-flex align-items-center gap-1">
                <label class="mb-0 small text-muted">Stroke</label>
                <input type="color" :value="currentStrokeColor"
                       @input="handleColorChange('stroke', $event.target.value)"
                       class="form-control form-control-sm p-0 border-0"
                       style="width:30px; height:26px; cursor:pointer;">
                <span class="small text-muted" style="font-size:11px;">{{ currentStrokeColor }}</span>
            </div>

            <div class="vr"></div>

            <div class="d-flex align-items-center gap-1">
                <label class="mb-0 small text-muted">Fill</label>
                <input type="color" :value="currentFillColor || '#ffffff'"
                       @input="handleColorChange('fill', $event.target.value)"
                       class="form-control form-control-sm p-0 border-0"
                       style="width:30px; height:26px; cursor:pointer;">
                <span class="small text-muted" style="font-size:11px;">{{ currentFillColor || 'none' }}</span>
            </div>

            <div class="vr"></div>

            <div class="d-flex align-items-center gap-2">
                <label class="mb-0 small text-muted">Width:</label>
                <input type="range" min="1" max="20" :value="strokeWidth"
                       @input="handleStrokeWidthChange(parseInt($event.target.value))"
                       class="form-range" style="width:80px;">
                <span class="badge bg-primary" style="min-width:24px;">{{ strokeWidth }}</span>
            </div>

            <div class="vr"></div>

            <div class="d-flex align-items-center gap-1">
                <label class="mb-0 small text-muted">Zoom:</label>
                <span class="badge bg-secondary" style="min-width:46px; font-size:12px;">{{ Math.round(currentZoom * 100) }}%</span>
                <button type="button" class="btn btn-sm btn-secondary py-0 px-1" style="font-size:11px; line-height:1.6;"
                        @click="handleZoomReset" title="Reset to fit">⊙</button>
            </div>
        </div>

        <!-- Canvas + Layers sidebar -->
        <div class="row g-2">
            <div class="col-lg-9 col-md-8">
                <CanvasContainerComponent
                    :action="action"
                    :json_prop="json_prop"
                    :related_jsons="related_jsons"
                    :image="image"
                    ref="canvasContainer"
                    @canvas_data="handleCanvasData"
                    @layers_updated="updateLayersList"
                    @layers_ready="updateLayersList"
                    @history-changed="onHistoryChanged"
                    @zoom-changed="onZoomChanged"
                />
            </div>
            <div class="col-lg-3 col-md-4">
                <LayersPanelComponent
                    :layers="layers"
                    :show-layers-table="true"
                    @refresh-layers="updateLayersList"
                    @toggle-all-visibility="toggleLayersVisibility"
                    @delete-all-layers="deleteAllLayers"
                    @move-layer-up="moveLayerUp"
                    @move-layer-down="moveLayerDown"
                    @create-group-from-layer="createGroupFromLayer"
                    @show-move-to-group-modal="showMoveToGroupModal"
                    @ungroup-layer="ungroupLayer"
                    @toggle-layer-visibility="toggleLayerVisibility"
                    @toggle-layer-lock="toggleLayerLock"
                    @delete-layer-item="deleteLayerItem"
                    @move-child-out-of-group="moveChildOutOfGroup"
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
            related_jsons: {
                type: Array,
                default: () => []
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
            historyCount: 0,
            redoCount: 0,
            moveToGroupModal: {
                show: false,
                layer: null,
                targetGroup: null
            },
            // New properties for enhanced features
            currentStrokeColor: '#ff0000',
            currentFillColor: null,
            strokeWidth: 3,
            zoomLevel: 1,
            currentZoom: 1,
            panOffset: { x: 0, y: 0 },
            selectedItems: [], // For multi-selection
            isPanning: false,
            panStartPoint: null
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
            }
        },
        methods: {
            // Toolbar event handlers
            handleReset() {
                if (confirm('Do you want clear area?')) {
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

            handleEraseSegment() {
                this.action = 6;
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

            handlePolygon() {
                this.action = 12;
            },

            handleText() {
                this.action = 13;
            },

            handleSelection() {
                this.action = 14;
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

            // Returns the CSS hex color of a Paper.js item (or its first child for groups).
            _getItemColor(item) {
                if (!item) return '#999999';
                if (item.name && item.name.startsWith('group ') && item.children && item.children.length > 0) {
                    return this._getItemColor(item.children[0]);
                }
                const color = item.strokeColor || item.fillColor;
                if (!color) return '#999999';
                try { return color.toCSS(true); } catch (e) { return '#999999'; }
            },

            _getItemWidth(item) {
                if (!item) return 3;
                if (item.name && item.name.startsWith('group ') && item.children && item.children.length > 0) {
                    return this._getItemWidth(item.children[0]);
                }
                if (item.name && item.name.startsWith('text ')) {
                    return Math.round(item.fontSize) || 16;
                }
                return item.strokeWidth || 3;
            },

            _setItemColor(item, color) {
                if (!item) return;
                if (item.name && item.name.startsWith('group ') && item.children) {
                    [...item.children].forEach(child => this._setItemColor(child, color));
                    return;
                }
                if (item.strokeColor !== null && item.strokeColor !== undefined) item.strokeColor = color;
                if (item.fillColor !== null && item.fillColor !== undefined) item.fillColor = color;
            },

            _setItemWidth(item, width) {
                if (!item) return;
                if (item.name && item.name.startsWith('group ') && item.children) {
                    [...item.children].forEach(child => this._setItemWidth(child, width));
                    return;
                }
                if (item.name && item.name.startsWith('text ')) {
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
                if (scope && scope.project) {
                    // Preserve expanded states before resetting layers
                    const expandedStates = {};
                    this.layers.forEach(layer => {
                        if (layer.isGroup && layer.expanded) {
                            expandedStates[layer.name] = true;
                        }
                    });
                    this.layers = [];
                    scope.project.layers.forEach(layer => {
                                    // Skip background and read-only related layers
                                    if (layer.name === 'background' || (layer.name && layer.name.startsWith('related-'))) return;
                                    layer.children.forEach(item => {
                                        if (item.name && item.name.startsWith('group ')) {
                                            // Handle groups: add the group itself and its children
                                            this.layers.push({
                                                id: item.id,
                                                name: item.name || 'unnamed',
                                                displayName: this._formatLayerName(item.name, true),
                                                color: this._getItemColor(item),
                                                strokeWidth: this._getItemWidth(item),
                                                visible: item.visible !== false,
                                                locked: item.locked || false,
                                                layerName: layer.name,
                                                isGroup: true,
                                                expanded: expandedStates[item.name] || false,
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
                                                    isText: child instanceof paper.PointText || (child.name && child.name.startsWith('text ')),
                                                    textContent: (child instanceof paper.PointText) ? child.content : (child.name && child.name.startsWith('text ') ? child.content : null),
                                                    isEditing: false,
                                                    editText: ''
                                                }))
                                            });
                                        } else if (!item.parent || !item.parent.name || !item.parent.name.startsWith('group ')) {
                                            // Only add non-group items or items not already included in groups
                                            this.layers.push({
                                                id: item.id,
                                                name: item.name || 'unnamed',
                                                displayName: this._formatLayerName(item.name, false),
                                                color: this._getItemColor(item),
                                                strokeWidth: this._getItemWidth(item),
                                                visible: item.visible !== false,
                                                locked: item.locked || false,
                                                layerName: layer.name,
                                                isText: item instanceof paper.PointText || (item.name && item.name.startsWith('text ')),
                                                textContent: (item instanceof paper.PointText) ? item.content : (item.name && item.name.startsWith('text ') ? item.content : null),
                                                isEditing: false,
                                                editText: ''
                                            });
                                        }
                                    });
                    });
                } else {
                    this.layers = [];
                }
            },

            toggleLayerVisibility(layer) {
                const item = this._itemById(layer.id);
                if (!item) return;
                item.visible = !item.visible;
                const scope = this.$refs.canvasContainer.getCanvasScope();
                if (scope) scope.view.update();
                this.updateLayersList();
            },

            toggleLayerLock(layer) {
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
                const itemType = layer.isGroup ? 'group' : 'item';
                if (!confirm(`Are you sure you want to delete the ${itemType} "${layer.displayName}"?`)) return;
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
                if (!confirm(`Are you sure you want to delete "${child.displayName}" from group "${layer.displayName}"?`)) return;
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
                    // Check if the layer above is in the same group (same layerName)
                    const currentLayer = this.layers[index];
                    const aboveLayer = this.layers[index - 1];

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
                    // Check if the layer below is in the same group (same layerName)
                    const currentLayer = this.layers[index];
                    const belowLayer = this.layers[index + 1];

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

            createGroupFromLayer(layer) {
                if (!confirm(`Create a new group containing "${layer.displayName}"?`)) return;
                const scope = this.$refs.canvasContainer.getCanvasScope();
                if (!scope || !scope.project) return;
                const foundItem = this._itemById(layer.id);
                if (!foundItem) return;
                const foundLayer = foundItem.layer; // Paper.js item.layer = the Layer it belongs to
                const newGroup = new paper.Group();
                const currentCount = this.$refs.canvasContainer.getGroupCounter();
                newGroup.name = `group ${currentCount + 1}`;
                this.$refs.canvasContainer.setGroupCounter(currentCount + 1);
                newGroup.addChild(foundItem); // addChild re-parents automatically
                foundLayer.addChild(newGroup);
                scope.view.update();
                this.updateLayersList();
                this.saveCanvasData();
            },

            ungroupLayer(layer) {
                if (!confirm(`Ungroup "${layer.displayName}"? All items will become individual layers.`)) return;
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

            showMoveToGroupModal(layer) {
                this.moveToGroupModal.layer = layer;
                this.moveToGroupModal.show = true;
                // For simplicity, we'll just move to the first available group
                // In a real implementation, you'd show a modal to select the target group
                if (this.availableGroups.length > 0) {
                    this.moveLayerToGroup(layer, this.availableGroups[0]);
                }
            },

            moveLayerToGroup(layer, targetGroup) {
                const scope = this.$refs.canvasContainer.getCanvasScope();
                if (!scope || !scope.project) return;
                const foundItem  = this._itemById(layer.id);
                const foundGroup = this._itemById(targetGroup.id);
                if (foundItem && foundGroup && foundGroup instanceof paper.Group) {
                    foundGroup.addChild(foundItem); // re-parents automatically
                    scope.view.update();
                    this.updateLayersList();
                    this.saveCanvasData();
                }
                this.moveToGroupModal.show = false;
            },

            moveChildOutOfGroup(layer, child) {
                if (!confirm(`Move "${child.displayName}" out of group "${layer.displayName}"?`)) return;
                const scope = this.$refs.canvasContainer.getCanvasScope();
                if (!scope || !scope.project) return;
                const foundChild = this._itemById(child.id);
                if (!foundChild) return;
                const parentLayer = foundChild.layer; // The Layer the group belongs to
                parentLayer.addChild(foundChild); // re-parents: removes from group, adds to layer
                scope.view.update();
                this.updateLayersList();
                this.saveCanvasData();
            },

            deleteAllLayers() {
                if (!confirm('Are you sure you want to delete ALL layers? This action cannot be undone.')) return;
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
                    const finalName = layer.isGroup && !newName.startsWith('group ') ? `group ${newName}` : newName;
                    foundItem.name = finalName;
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

            handleColorChange(type, color) {
                if (type === 'stroke') {
                    this.currentStrokeColor = color;
                } else if (type === 'fill') {
                    this.currentFillColor = color;
                }
                // Update canvas with new colors if needed
                if (this.$refs.canvasContainer && this.$refs.canvasContainer.updateColors) {
                    this.$refs.canvasContainer.updateColors(this.currentStrokeColor, this.currentFillColor, this.strokeWidth);
                }
            },

            handleStrokeWidthChange(width) {
                this.strokeWidth = width;
                if (this.$refs.canvasContainer && this.$refs.canvasContainer.updateColors) {
                    this.$refs.canvasContainer.updateColors(this.currentStrokeColor, this.currentFillColor, this.strokeWidth);
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
    .but_action{
        background-color: #a5a6a7;
    }
</style>
