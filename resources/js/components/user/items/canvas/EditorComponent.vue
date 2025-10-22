<template>
    <div>
        <div class="row">
            <div class="col-md-2">
                <ToolbarComponent
                    :action="action"
                    :history-length="history.length"
                    :redo-length="redoStack.length"
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
                />
            </div>

            <div class="col-md-10">
                <CanvasContainerComponent
                    :action="action"
                    :json_prop="json_prop"
                    :related_jsons="related_jsons"
                    :image="image"
                    ref="canvasContainer"
                    @canvas_data="handleCanvasData"
                    @layers_updated="updateLayersList"
                    @layers_ready="updateLayersList"
                />

                <LayersPanelComponent
                    :layers="layers"
                    :show-layers-table="showLayersTable"
                    @toggle-layers-table="toggleLayersTable"
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
            }
        },
        data: () => ({
            image: '',

            img_h: 0,
            img_w: 0,

            action: 1,
            layers: [],
            showLayersTable: false,
            history: [], // Store drawing history for undo functionality
            redoStack: [], // Store undone states for redo functionality
            moveToGroupModal: {
                show: false,
                layer: null,
                targetGroup: null
            }
        }),
        mounted() {
            if (this.image_prop) {
                // this.change_image(this.image_prop);
                this.image = this.image_prop;
            }
            // Delay layers update to ensure canvas is fully initialized
            this.$nextTick(() => {
                setTimeout(() => {
                    this.updateLayersList();
                }, 100);
            });
        },
        updated() {
            // Also update layers when component updates
            this.$nextTick(() => {
                setTimeout(() => {
                    this.updateLayersList();
                }, 50);
            });
        },
        watch: {
            image_prop: function(newVal, oldVal) {
                if (newVal && newVal !== oldVal) {
                    // this.change_image(newVal);
                    this.image = this.newVal;
                }
            },
            json_prop: {
                handler: function(newVal, oldVal) {
                    if (newVal && newVal !== oldVal && this.$refs.canvasContainer) {
                        // Pass the JSON data to the CanvasContainerComponent
                        this.$refs.canvasContainer.json_prop = newVal;
                        console.log('JSON prop updated in EditorComponent:', newVal);
                    }
                    // Update layers list when JSON changes
                    this.$nextTick(() => {
                        setTimeout(() => {
                            this.updateLayersList();
                        }, 200);
                    });
                },
                immediate: true
            },
            related_jsons: {
                handler: function(newVal, oldVal) {
                    if (newVal && newVal !== oldVal && this.$refs.canvasContainer) {
                        // Pass the related JSONs to the CanvasContainerComponent
                        this.$refs.canvasContainer.related_jsons = newVal;
                        console.log('Related JSONs prop updated in EditorComponent:', newVal);
                    }
                    // Update layers list when related JSONs change
                    this.$nextTick(() => {
                        setTimeout(() => {
                            this.updateLayersList();
                        }, 200);
                    });
                },
                immediate: true
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

            handleUndo() {
                this.$refs.canvasContainer.undoLastAction();
            },

            handleRedo() {
                this.$refs.canvasContainer.redoLastAction();
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
                const scope = this.$refs.canvasContainer.getCanvasScope();
                if (scope) {
                    const canvasData = JSON.stringify(scope.project.exportJSON());
                    this.$emit('canvas_data', canvasData);
                } else {
                    console.log('Canvas not available for data extraction');
                }
            },

            import_json_in_editor(event) {
                console.log("🚀 ~ import_json_in_editor ~ event:", event);
                // this.$refs.canvasContainer.import_json(json)
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
                        layer.children.forEach(item => {
                            if (item.name && item.name.startsWith('group ')) {
                                // Handle groups: add the group itself and its children
                                this.layers.push({
                                    name: item.name || 'unnamed',
                                    displayName: item.name ? item.name.replace('group ', '') : 'unnamed',
                                    visible: item.visible !== false,
                                    locked: item.locked || false,
                                    layerName: layer.name,
                                    isGroup: true,
                                    expanded: expandedStates[item.name] || false,
                                    children: item.children.map(child => ({
                                        name: child.name || 'unnamed',
                                        displayName: child.name || 'unnamed',
                                        visible: child.visible !== false,
                                        locked: child.locked || false,
                                        parentGroup: item.name
                                    }))
                                });
                            } else if (!item.parent || !item.parent.name || !item.parent.name.startsWith('group ')) {
                                // Only add non-group items or items not already included in groups
                                this.layers.push({
                                    name: item.name || 'unnamed',
                                    displayName: item.name || 'unnamed',
                                    visible: item.visible !== false,
                                    locked: item.locked || false,
                                    layerName: layer.name
                                });
                            }
                        });
                    });
                } else {
                    this.layers = [];
                    // Retry after a short delay if canvas is not ready
                    setTimeout(() => {
                        this.updateLayersList();
                    }, 100);
                }
            },

            toggleLayerVisibility(layer) {
                console.log('toggleLayerVisibility called for item:', layer);
                const scope = this.$refs.canvasContainer.getCanvasScope();
                if (scope && scope.project) {
                    // Find the item by name across all layers
                    let foundItem = null;
                    scope.project.layers.forEach(paperLayer => {
                        paperLayer.children.forEach(item => {
                            if (item.name === layer.name) {
                                foundItem = item;
                            }
                        });
                    });

                    if (foundItem) {
                        // Toggle the visibility
                        foundItem.visible = !foundItem.visible;
                        console.log('Toggled item visibility to:', foundItem.visible);
                        scope.view.update();
                        console.log('Item visibility updated');
                        // Refresh the layers list to reflect changes
                        this.updateLayersList();
                    } else {
                        console.log('Item not found');
                    }
                } else {
                    console.log('Canvas not available for visibility toggle');
                }
            },

            toggleLayerLock(layer) {
                console.log('toggleLayerLock called for item:', layer);
                const scope = this.$refs.canvasContainer.getCanvasScope();
                if (scope && scope.project) {
                    // Find the item by name across all layers
                    let foundItem = null;
                    scope.project.layers.forEach(paperLayer => {
                        paperLayer.children.forEach(item => {
                            if (item.name === layer.name) {
                                foundItem = item;
                            }
                        });
                    });

                    if (foundItem && layer.layerName !== 'main') {
                        // Toggle the lock status
                        foundItem.locked = !foundItem.locked;
                        console.log('Toggled item lock to:', foundItem.locked);
                        scope.view.update();
                        console.log('Item lock updated');
                        // Refresh the layers list to reflect changes
                        this.updateLayersList();
                    } else if (layer.layerName === 'main') {
                        console.log('Cannot lock main layer items');
                    } else {
                        console.log('Item not found');
                    }
                } else {
                    console.log('Canvas not available for lock toggle');
                }
            },

            toggleLayersVisibility() {
                console.log('toggleLayersVisibility called');
                const scope = this.$refs.canvasContainer.getCanvasScope();
                if (scope && scope.project) {
                    // Check if all items are currently visible
                    const allVisible = this.layers.every(layer => layer.visible);

                    // Set all items to the opposite state
                    const newVisibility = !allVisible;

                    scope.project.layers.forEach(paperLayer => {
                        paperLayer.children.forEach(item => {
                            item.visible = newVisibility;
                        });
                    });

                    scope.view.update();
                    console.log('All items visibility set to:', newVisibility);
                    // Refresh the layers list to reflect changes
                    this.updateLayersList();
                } else {
                    console.log('Canvas not available for visibility toggle');
                }
            },

            deleteLayerItem(layer) {
                console.log('deleteLayerItem called for item:', layer);

                const itemType = layer.isGroup ? 'group' : 'item';
                if (confirm(`Are you sure you want to delete the ${itemType} "${layer.name}"? This action cannot be undone.`)) {
                    const scope = this.$refs.canvasContainer.getCanvasScope();
                    if (scope && scope.project) {
                        // Find and remove the item
                        let foundItem = null;
                        let foundLayer = null;
                        scope.project.layers.forEach(paperLayer => {
                            paperLayer.children.forEach(item => {
                                if ((item.name || 'unnamed') === layer.name) {
                                    foundItem = item;
                                    foundLayer = paperLayer;
                                }
                            });
                        });

                        if (foundItem && foundLayer) {
                            foundItem.remove();
                            scope.view.update();
                            console.log(`${itemType} deleted successfully`);
                            // Refresh the layers list to reflect changes
                            this.updateLayersList();
                            // Save the canvas data
                            this.saveCanvasData();
                        } else {
                            console.log(`${itemType} not found for deletion`);
                        }
                    } else {
                        console.log('Canvas not available for item deletion');
                    }
                } else {
                    console.log(`Delete ${itemType} cancelled by user`);
                }
            },

            toggleLayersTable() {
                this.showLayersTable = !this.showLayersTable;
            },

            toggleGroupExpansion(layer) {
                this.$set(layer, 'expanded', !layer.expanded);
            },

            toggleChildVisibility(layer, child) {
                console.log('toggleChildVisibility called for child:', child, 'in group:', layer);
                const scope = this.$refs.canvasContainer.getCanvasScope();
                if (scope && scope.project) {
                    // Find the group by name
                    let foundGroup = null;
                    scope.project.layers.forEach(paperLayer => {
                        paperLayer.children.forEach(item => {
                            if (item.name === layer.name) {
                                foundGroup = item;
                            }
                        });
                    });

                    if (foundGroup) {
                        // Find the child item within the group
                        foundGroup.children.forEach(item => {
                            if (item.name === child.name) {
                                item.visible = !item.visible;
                                console.log('Toggled child visibility to:', item.visible);
                            }
                        });
                        scope.view.update();
                        // Refresh the layers list to reflect changes
                        this.updateLayersList();
                    } else {
                        console.log('Group not found');
                    }
                } else {
                    console.log('Canvas not available for child visibility toggle');
                }
            },

            toggleChildLock(layer, child) {
                console.log('toggleChildLock called for child:', child, 'in group:', layer);
                const scope = this.$refs.canvasContainer.getCanvasScope();
                if (scope && scope.project) {
                    // Find the group by name
                    let foundGroup = null;
                    scope.project.layers.forEach(paperLayer => {
                        paperLayer.children.forEach(item => {
                            if (item.name === layer.name) {
                                foundGroup = item;
                            }
                        });
                    });

                    if (foundGroup) {
                        // Find the child item within the group
                        foundGroup.children.forEach(item => {
                            if (item.name === child.name) {
                                item.locked = !item.locked;
                                console.log('Toggled child lock to:', item.locked);
                            }
                        });
                        scope.view.update();
                        // Refresh the layers list to reflect changes
                        this.updateLayersList();
                    } else {
                        console.log('Group not found');
                    }
                } else {
                    console.log('Canvas not available for child lock toggle');
                }
            },

            deleteChildItem(layer, child) {
                console.log('deleteChildItem called for child:', child, 'in group:', layer);
                if (confirm(`Are you sure you want to delete "${child.name}" from group "${layer.name}"? This action cannot be undone.`)) {
                    const scope = this.$refs.canvasContainer.getCanvasScope();
                    if (scope && scope.project) {
                        // Find the group by name
                        let foundGroup = null;
                        scope.project.layers.forEach(paperLayer => {
                            paperLayer.children.forEach(item => {
                                if (item.name === layer.name) {
                                    foundGroup = item;
                                }
                            });
                        });

                        if (foundGroup) {
                            // Find and remove the child item from the group
                            let childToRemove = null;
                            foundGroup.children.forEach(item => {
                                if (item.name === child.name) {
                                    childToRemove = item;
                                }
                            });

                            if (childToRemove) {
                                childToRemove.remove();
                                scope.view.update();
                                console.log('Child item deleted successfully');
                                // Refresh the layers list to reflect changes
                                this.updateLayersList();
                            } else {
                                console.log('Child item not found for deletion');
                            }
                        } else {
                            console.log('Group not found');
                        }
                    } else {
                        console.log('Canvas not available for child item deletion');
                    }
                }
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
                if (scope && scope.project) {
                    // Group the UI layers by their layerName (paper layer name)
                    const layersByName = {};
                    this.layers.forEach(layer => {
                        if (!layersByName[layer.layerName]) layersByName[layer.layerName] = [];
                        layersByName[layer.layerName].push(layer);
                    });

                    // For each paper layer, reorder its top-level children based on the UI order
                    scope.project.layers.forEach(paperLayer => {
                        const orderedLayers = layersByName[paperLayer.name];
                        if (orderedLayers) {
                            const orderedChildren = [];
                            orderedLayers.forEach(layer => {
                                const child = paperLayer.children.find(c => c.name === layer.name);
                                if (child) orderedChildren.push(child);
                            });
                            paperLayer.children = orderedChildren;
                        }
                    });

                    scope.view.update();
                    this.saveCanvasData();
                }
            },

            createGroupFromLayer(layer) {
                if (confirm(`Create a new group containing "${layer.name}"?`)) {
                    const scope = this.$refs.canvasContainer.getCanvasScope();
                    if (scope && scope.project) {
                        // Find the item in Paper.js
                        let foundItem = null;
                        let foundLayer = null;
                        scope.project.layers.forEach(paperLayer => {
                            paperLayer.children.forEach(item => {
                                if (item.name === layer.name) {
                                    foundItem = item;
                                    foundLayer = paperLayer;
                                }
                            });
                        });

                        if (foundItem && foundLayer) {
                            // Create a new group
                            const newGroup = new paper.Group();
                            newGroup.name = `group ${this.$refs.canvasContainer.groupCounter + 1}`;
                            this.$refs.canvasContainer.groupCounter++;

                            // Move the item into the group
                            foundItem.remove();
                            newGroup.addChild(foundItem);

                            // Add the group to the layer
                            foundLayer.addChild(newGroup);

                            scope.view.update();
                            this.updateLayersList();
                            this.saveCanvasData();
                        }
                    }
                }
            },

            ungroupLayer(layer) {
                if (confirm(`Ungroup "${layer.name}"? All items will become individual layers.`)) {
                    const scope = this.$refs.canvasContainer.getCanvasScope();
                    if (scope && scope.project) {
                        // Find the group in Paper.js
                        let foundGroup = null;
                        let foundLayer = null;
                        scope.project.layers.forEach(paperLayer => {
                            paperLayer.children.forEach(item => {
                                if (item.name === layer.name && item instanceof paper.Group) {
                                    foundGroup = item;
                                    foundLayer = paperLayer;
                                }
                            });
                        });

                        if (foundGroup && foundLayer) {
                            // Move all children out of the group
                            const children = foundGroup.children.slice(); // Copy the children array
                            children.forEach(child => {
                                child.remove();
                                foundLayer.addChild(child);
                            });

                            // Remove the empty group
                            foundGroup.remove();

                            scope.view.update();
                            this.updateLayersList();
                            this.saveCanvasData();
                        }
                    }
                } else {
                    console.log('Ungroup cancelled by user');
                }
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
                if (scope && scope.project) {
                    // Find the item and target group in Paper.js
                    let foundItem = null;
                    let foundGroup = null;
                    scope.project.layers.forEach(paperLayer => {
                        paperLayer.children.forEach(item => {
                            if (item.name === layer.name) {
                                foundItem = item;
                            }
                            if (item.name === targetGroup.name && item instanceof paper.Group) {
                                foundGroup = item;
                            }
                        });
                    });

                    if (foundItem && foundGroup) {
                        // Move the item into the group
                        foundItem.remove();
                        foundGroup.addChild(foundItem);

                        scope.view.update();
                        this.updateLayersList();
                        this.saveCanvasData();
                    }
                }
                this.moveToGroupModal.show = false;
            },

            moveChildOutOfGroup(layer, child) {
                if (confirm(`Move "${child.name}" out of group "${layer.name}"?`)) {
                    const scope = this.$refs.canvasContainer.getCanvasScope();
                    if (scope && scope.project) {
                        // Find the group in Paper.js
                        let foundGroup = null;
                        let foundLayer = null;
                        scope.project.layers.forEach(paperLayer => {
                            paperLayer.children.forEach(item => {
                                if (item.name === layer.name && item instanceof paper.Group) {
                                    foundGroup = item;
                                    foundLayer = paperLayer;
                                }
                            });
                        });

                        if (foundGroup && foundLayer) {
                            // Find and move the child out of the group
                            foundGroup.children.forEach(item => {
                                if (item.name === child.name) {
                                    item.remove();
                                    foundLayer.addChild(item);
                                }
                            });

                            scope.view.update();
                            this.updateLayersList();
                            this.saveCanvasData();
                        }
                    }
                }
            },

            deleteAllLayers() {
                if (confirm('Are you sure you want to delete ALL layers? This action cannot be undone.')) {
                    const scope = this.$refs.canvasContainer.getCanvasScope();
                    if (scope && scope.project) {
                        // Clear all layers except the main layer if it exists
                        scope.project.layers.forEach(layer => {
                            if (layer.name !== 'main') {
                                layer.removeChildren();
                            }
                        });
                        scope.view.update();
                        this.updateLayersList();
                        this.saveCanvasData();
                    }
                } else {
                    console.log('Delete all layers cancelled by user');
                }
            },

            startEditingLayerName(layer, event) {
                // Stop editing any other layer first
                this.layers.forEach(l => {
                    if (l.isEditing) {
                        this.cancelEditingLayerName(l);
                    }
                });
                this.$set(layer, 'isEditing', true);
                this.$set(layer, 'originalName', layer.name);
                this.$nextTick(() => {
                    if (event && event.target) {
                        event.target.focus();
                        // Select all text
                        const range = document.createRange();
                        range.selectNodeContents(event.target);
                        const selection = window.getSelection();
                        selection.removeAllRanges();
                        selection.addRange(range);
                    }
                });
            },

            finishEditingLayerName(layer, newName) {
                if (newName && newName !== layer.originalName) {
                    // Update the name in Paper.js
                    const scope = this.$refs.canvasContainer.getCanvasScope();
                    if (scope && scope.project) {
                        let foundItem = null;
                        scope.project.layers.forEach(paperLayer => {
                            paperLayer.children.forEach(item => {
                                if (item.name === layer.originalName) {
                                    foundItem = item;
                                }
                            });
                        });

                        if (foundItem) {
                            // For groups, ensure the name starts with 'group '
                            if (layer.isGroup && !newName.startsWith('group ')) {
                                foundItem.name = `group ${newName}`;
                            } else {
                                foundItem.name = newName;
                            }
                            layer.name = foundItem.name;
                            layer.displayName = layer.isGroup ? newName : foundItem.name;
                            this.saveCanvasData();
                            // Update layers list to reflect the change
                            this.updateLayersList();
                        }
                    }
                }
                this.$set(layer, 'isEditing', false);
                delete layer.originalName;
            },

            cancelEditingLayerName(layer) {
                this.$set(layer, 'isEditing', false);
                delete layer.originalName;
                this.updateLayersList(); // Refresh to reset the display
            },

            startEditingChildName(layer, child, event) {
                // Stop editing any other child first
                this.layers.forEach(l => {
                    if (l.children) {
                        l.children.forEach(c => {
                            if (c.isEditing) {
                                this.cancelEditingChildName(c);
                            }
                        });
                    }
                });
                this.$set(child, 'isEditing', true);
                this.$set(child, 'originalName', child.name);
                this.$nextTick(() => {
                    if (event && event.target) {
                        event.target.focus();
                        // Select all text
                        const range = document.createRange();
                        range.selectNodeContents(event.target);
                        const selection = window.getSelection();
                        selection.removeAllRanges();
                        selection.addRange(range);
                    }
                });
            },

            finishEditingChildName(layer, child, newName) {
                if (newName && newName !== child.originalName) {
                    // Update the name in Paper.js
                    const scope = this.$refs.canvasContainer.getCanvasScope();
                    if (scope && scope.project) {
                        let foundGroup = null;
                        scope.project.layers.forEach(paperLayer => {
                            paperLayer.children.forEach(item => {
                                if (item.name === layer.name && item instanceof paper.Group) {
                                    foundGroup = item;
                                }
                            });
                        });

                        if (foundGroup) {
                            foundGroup.children.forEach(item => {
                                if (item.name === child.originalName) {
                                    item.name = newName;
                                    child.name = newName;
                                    child.displayName = newName;
                                    this.saveCanvasData();
                                    // Update layers list to reflect the change
                                    this.updateLayersList();
                                }
                            });
                        }
                    }
                }
                this.$set(child, 'isEditing', false);
                delete child.originalName;
            },

            cancelEditingChildName(child) {
                this.$set(child, 'isEditing', false);
                delete child.originalName;
                this.updateLayersList(); // Refresh to reset the display
            },

            saveCanvasData() {
                this.$refs.canvasContainer.saveCanvasData();
            }
        }
    }
</script>

<style scoped>
    .but_action{
        background-color: #a5a6a7;
    }
</style>
