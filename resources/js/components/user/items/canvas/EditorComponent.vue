<template>
    <div>
        <div class="row ">

            <div class="col-md-2">
                <button type="button" class="btn btn-danger btn-block" @click.prevent="reset">
                    <i class="fa fa-times" aria-hidden="true"></i>
                </button>

                <button type="button" class="btn btn-warning btn-block" @click.prevent="back">
                    <i class="fa fa-step-backward" aria-hidden="true"></i>
                </button>

                <hr />

                <button type="button" class="btn btn-primary btn-block but_action" @click.prevent="line" v-if="action == 1">
                    <i class="fa fa-pencil" aria-hidden="true"></i>
                </button>
                <button type="button" class="btn btn-primary btn-block" @click.prevent="line" v-else>
                    <i class="fa fa-pencil" aria-hidden="true"></i>
                </button>

                <button type="button" class="btn btn-primary btn-block but_action" @click.prevent="point" v-if="action == 2">
                    <i class="fa fa-circle" aria-hidden="true"></i>
                </button>
                <button type="button" class="btn btn-primary btn-block" @click.prevent="point" v-else>
                    <i class="fa fa-circle" aria-hidden="true"></i>
                </button>

                <button type="button" class="btn btn-primary btn-block but_action" @click.prevent="number" v-if="action == 3">
                    <i class="fa fa-pencil" aria-hidden="true"></i>
                     +
                    <i class="fa fa-circle" aria-hidden="true"></i>
                </button>
                <button type="button" class="btn btn-primary btn-block" @click.prevent="number" v-else>
                    <i class="fa fa-pencil" aria-hidden="true"></i>
                     +
                    <i class="fa fa-circle" aria-hidden="true"></i>
                </button>

                <button type="button" class="btn btn-primary btn-block but_action" @click.prevent="rectangle" v-if="action == 4">
                    <i class="fa fa-square-o" aria-hidden="true"></i>
                </button>
                <button type="button" class="btn btn-primary btn-block" @click.prevent="rectangle" v-else>
                    <i class="fa fa-square-o" aria-hidden="true"></i>
                </button>

                <hr />

                <button type="button" class="btn btn-warning btn-block but_action" @click.prevent="eraser" v-if="action == 5">
                    <i class="fa fa-eraser" aria-hidden="true"></i>
                </button>
                <button type="button" class="btn btn-warning btn-block" @click.prevent="eraser" v-else>
                    <i class="fa fa-eraser" aria-hidden="true"></i>
                </button>

                <button type="button" class="btn btn-danger btn-block but_action" @click.prevent="erase_segment" v-if="action == 6">
                    <i class="fa fa-scissors" aria-hidden="true"></i>
                </button>
                <button type="button" class="btn btn-danger btn-block" @click.prevent="erase_segment" v-else>
                    <i class="fa fa-scissors" aria-hidden="true"></i>
                </button>
            </div>

            <div class="col-md-10">
                <Canvas
                    :canvas_id_prop="'canvas-one'"
                    :action_prop="action"
                    :json_prop="json_prop"
                    :related_jsons="related_jsons"

                    ref="childCanvas"

                    :style="{
                        backgroundImage: image ? 'url(' + image + ')' : 'none',
                        backgroundSize: '100%'
                    }"
                    @canvas_data="canvas_data"
                    @layers_updated="updateLayersList"
                    @layers_ready="updateLayersList"
                />

                <!-- Layers Table under canvas -->
                <div class="layers-panel mt-3">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <!-- <button type="button" class="btn btn-sm btn-info" @click="showLayersList">Show layouts list</button> -->
                        <div>
                            <button type="button" class="btn btn-sm btn-info me-1" @click="updateLayersList">Refresh</button>
                            <button type="button" class="btn btn-sm btn-secondary" @click="toggleLayersVisibility">
                                <i class="fa fa-eye" aria-hidden="true"></i> Show/Hide All
                            </button>
                        </div>
                    </div>
                    <div class="layers-list" style="max-height: 200px; overflow-y: auto;">
                        <div v-for="layer in layers" :key="layer.name" class="layer-item d-flex align-items-center justify-content-between p-2 border-bottom">
                            <span class="layer-name flex-grow-1">{{ layer.name }}</span>
                            <div class="layer-controls">
                                <button
                                    type="button"
                                    class="btn btn-sm btn-outline-secondary me-1"
                                    @click="toggleLayerVisibility(layer)"
                                    :title="layer.visible ? 'Hide item' : 'Show item'"
                                >
                                    <i :class="layer.visible ? 'fa fa-eye' : 'fa fa-eye-slash'"></i>
                                </button>
                                <button
                                    type="button"
                                    class="btn btn-sm btn-outline-warning me-1"
                                    @click="toggleLayerLock(layer)"
                                    :disabled="layer.layerName === 'main'"
                                    :title="layer.locked ? 'Unlock item' : 'Lock item'"
                                >
                                    <i :class="layer.locked ? 'fa fa-lock' : 'fa fa-unlock'"></i>
                                </button>
                                <button
                                    type="button"
                                    class="btn btn-sm btn-outline-danger"
                                    @click="deleteLayerItem(layer)"
                                    :disabled="layer.layerName && layer.layerName.startsWith('related')"
                                    :title="layer.layerName && layer.layerName.startsWith('related') ? 'Cannot delete related route items' : 'Delete item'"
                                >
                                    <i class="fa fa-trash" aria-hidden="true"></i>
                                </button>
                            </div>
                        </div>
                        <div v-if="layers.length === 0" class="text-center text-muted p-3">
                            No items drawn yet
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</template>

<script>
import Canvas from "./PaperComponent.vue";
    export default {
        components: {
            Canvas
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
                    if (newVal && newVal !== oldVal && this.$refs.childCanvas) {
                        // Pass the JSON data to the PaperComponent
                        this.$refs.childCanvas.json_prop = newVal;
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
                    if (newVal && newVal !== oldVal && this.$refs.childCanvas) {
                        // Pass the related JSONs to the PaperComponent
                        this.$refs.childCanvas.related_jsons = newVal;
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
        methods: {
            reset() {
                if (confirm('Do you want clear area?')) {
                    this.$refs.childCanvas.reset();
                }
            },

            point(){
                this.action = 2
            },

            line(){
                this.action = 1
            },

            number(){
                this.action = 3
            },

            rectangle(){
                this.action = 4
            },

            eraser(){
                this.action = 5
            },

            erase_segment(){
                this.action = 6
            },

            // change_image(event){
            //     if (event) {
            //         this.image = '/public/images/sector_img' + event;
            //     } else {
            //         this.image = '';
            //     }
            // },

            canvas_data(event){
                this.$emit('canvas_data', event)
            },

            // Method to trigger canvas data emission
            emitCanvasData() {
                if (this.$refs.childCanvas && typeof this.$refs.childCanvas.saveCanvasData === 'function') {
                    this.$refs.childCanvas.saveCanvasData();
                } else {
                    console.log('Canvas ref not available or saveCanvasData method not found');
                }
            },

            // Method to get current canvas data and emit it
            getAndEmitCanvasData() {
                if (this.$refs.childCanvas && this.$refs.childCanvas.scope) {
                    const canvasData = JSON.stringify(this.$refs.childCanvas.scope.project.exportJSON());
                    this.$emit('canvas_data', canvasData);
                } else {
                    console.log('Canvas not available for data extraction');
                }
            },

            import_json_in_editor(event){
                console.log("🚀 ~ import_json_in_editor ~ event:", event)
                // this.$refs.childCanvas.import_json(json)
            },

            updateLayersList() {
                console.log('updateLayersList called');
                if (this.$refs.childCanvas && this.$refs.childCanvas.scope && this.$refs.childCanvas.scope.project) {
                    console.log('Canvas project found, layers:', this.$refs.childCanvas.scope.project.layers.length);
                    this.layers = [];
                    this.$refs.childCanvas.scope.project.layers.forEach(layer => {
                        layer.children.forEach(item => {
                            this.layers.push({
                                name: item.name || 'unnamed',
                                visible: item.visible !== false,
                                locked: item.locked || false,
                                layerName: layer.name // Keep track of which layer the item belongs to
                            });
                        });
                    });
                    console.log('Items updated:', this.layers);
                } else {
                    console.log('Canvas not ready for layers update, retrying...');
                    this.layers = [];
                    // Retry after a short delay if canvas is not ready
                    setTimeout(() => {
                        this.updateLayersList();
                    }, 100);
                }
            },

            toggleLayerVisibility(layer) {
                console.log('toggleLayerVisibility called for item:', layer);
                if (this.$refs.childCanvas && this.$refs.childCanvas.scope && this.$refs.childCanvas.scope.project) {
                    // Find the item by name across all layers
                    let foundItem = null;
                    this.$refs.childCanvas.scope.project.layers.forEach(paperLayer => {
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
                        this.$refs.childCanvas.scope.view.update();
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
                if (this.$refs.childCanvas && this.$refs.childCanvas.scope && this.$refs.childCanvas.scope.project) {
                    // Find the item by name across all layers
                    let foundItem = null;
                    this.$refs.childCanvas.scope.project.layers.forEach(paperLayer => {
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
                        this.$refs.childCanvas.scope.view.update();
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
                if (this.$refs.childCanvas && this.$refs.childCanvas.scope && this.$refs.childCanvas.scope.project) {
                    // Check if all items are currently visible
                    const allVisible = this.layers.every(layer => layer.visible);

                    // Set all items to the opposite state
                    const newVisibility = !allVisible;

                    this.$refs.childCanvas.scope.project.layers.forEach(paperLayer => {
                        paperLayer.children.forEach(item => {
                            item.visible = newVisibility;
                        });
                    });

                    this.$refs.childCanvas.scope.view.update();
                    console.log('All items visibility set to:', newVisibility);
                    // Refresh the layers list to reflect changes
                    this.updateLayersList();
                } else {
                    console.log('Canvas not available for visibility toggle');
                }
            },

            deleteLayerItem(layer) {
                console.log('deleteLayerItem called for item:', layer);
                if (layer.layerName && layer.layerName.startsWith('related')) {
                    console.log('Cannot delete related route items');
                    return;
                }

                if (confirm(`Are you sure you want to delete "${layer.name}"? This action cannot be undone.`)) {
                    if (this.$refs.childCanvas && this.$refs.childCanvas.scope && this.$refs.childCanvas.scope.project) {
                        // Find and remove the item
                        let foundItem = null;
                        let foundLayer = null;
                        this.$refs.childCanvas.scope.project.layers.forEach(paperLayer => {
                            paperLayer.children.forEach(item => {
                                if (item.name === layer.name) {
                                    foundItem = item;
                                    foundLayer = paperLayer;
                                }
                            });
                        });

                        if (foundItem && foundLayer) {
                            foundItem.remove();
                            this.$refs.childCanvas.scope.view.update();
                            console.log('Item deleted successfully');
                            // Refresh the layers list to reflect changes
                            this.updateLayersList();
                        } else {
                            console.log('Item not found for deletion');
                        }
                    } else {
                        console.log('Canvas not available for item deletion');
                    }
                }
            }


        },
    }
</script>

<style scoped>
    .but_action{
        background-color: #a5a6a7;
    }
</style>