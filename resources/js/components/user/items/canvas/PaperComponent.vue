<template>
    <!-- <div> -->
        <canvas 
            :id="canvas_id_prop" 
            class="canvas-style" 
            v-on:mousedown="mouseDown"
            v-on:mouseup="mouseUp"
        />
    <!-- </div> -->
</template>

<script>
    // http://paperjs.org/
    const paper = require('paper');
    export default {
        name: "Canvas",
        props: [
            'canvas_id_prop',
            'action_prop',
            'json_prop',
            'related_jsons'
        ],
        data: () => ({
            path: null,
            scope: null,
            canvasData: null, // store canvas data
            old_json: null,
            layerCounters: {
                line: 0,
                point: 0,
                rectangle: 0
            }

        }),
        watch: {
            json_prop: {
                handler: function(newVal){
                    this.importJsonData(newVal);
                },
                immediate: true
            },
            related_jsons: {
                handler: function(newVal){
                    this.importRelatedJsons();
                },
                immediate: true
            },
        },
        mounted() {
            this.scope = new paper.PaperScope();
            this.scope.setup(this.canvas_id_prop);
            this.scope.activate();

            // Initialize with empty project
            this.scope.project.clear();

            // Import related JSONs first (background)
            this.importRelatedJsons();

            // Import JSON if it was passed during initialization (foreground, editable)
            if (this.json_prop) {
                this.importJsonData(this.json_prop);
            }

            // paperjsLayersPanel.create();
        },
        methods: {
            reset() {
                this.scope.project.activeLayer.removeChildren();
                this.saveCanvasData()
            },

            createTool(scope) {
                scope.activate();
                return new paper.Tool();
            },

            add_point(event){
                this.layerCounters.point++;
                const point = new paper.Path.Circle({
                    center: event.point,
                    radius: 7,
                    fillColor: '#ff0000',
                    strokeColor: '#ff0000',
                    name: `point ${this.layerCounters.point}`
                });
                return point;
            },

            add_line(){
                this.layerCounters.line++;
                this.path = new paper.Path({
                    strokeColor: "#ff0000",
                    strokeWidth: 3,
                    strokeJoin: 'round',
                    name: `line ${this.layerCounters.line}`
                });
            },

            add_rectangle(event){
                this.layerCounters.rectangle++;
                // Create a custom rectangle path with 4 corners
                const rect = new paper.Path({
                    strokeColor: '#ff0000',
                    strokeWidth: 3,
                    fillColor: null,
                    closed: true,
                    name: `rectangle ${this.layerCounters.rectangle}`
                });

                // Add the four corners of the rectangle
                rect.add(event.point); // top-left
                rect.add(event.point); // top-right (will be updated)
                rect.add(event.point); // bottom-right (will be updated)
                rect.add(event.point); // bottom-left (will be updated)

                // Mark this path as a rectangle for drag handling
                rect.data = { isRectangle: true, startPoint: event.point };
                this.path = rect;
                return rect;
            },

            import_json_in_canvas(){
                this.scope.project.importJSON(this.old_prop)
            },

            importJsonData(jsonData) {
                if (jsonData && this.scope) {
                    try {
                        let parsedData = jsonData;
                        if (typeof jsonData === 'string') {
                            parsedData = JSON.parse(jsonData);
                        }
                        this.scope.activate();

                        // Create a new layer for main drawing
                        const mainLayer = new paper.Layer();
                        mainLayer.name = 'main';

                        // Import into the main layer
                        const importedItems = mainLayer.importJSON(parsedData);

                        // Set color to red for main route items and add names if they don't have them
                        const setMainItemProperties = (item) => {
                            if (item.strokeColor) {
                                item.strokeColor = '#ff0000';
                            }
                            if (item.fillColor) {
                                item.fillColor = '#ff0000';
                            }
                            // Recursively apply to children
                            if (item.children) {
                                item.children.forEach(child => setMainItemProperties(child));
                            }
                        };

                        if (Array.isArray(importedItems)) {
                            importedItems.forEach((item, index) => {
                                setMainItemProperties(item);
                                if (!item.name) {
                                    // Determine type and assign appropriate name
                                    if (item instanceof paper.Path && item.closed) {
                                        this.layerCounters.rectangle++;
                                        item.name = `rectangle ${this.layerCounters.rectangle}`;
                                    } else if (item instanceof paper.Path) {
                                        this.layerCounters.line++;
                                        item.name = `line ${this.layerCounters.line}`;
                                    } else if (item instanceof paper.Path.Circle) {
                                        this.layerCounters.point++;
                                        item.name = `point ${this.layerCounters.point}`;
                                    }
                                }
                            });
                        } else if (importedItems && !importedItems.name) {
                            setMainItemProperties(importedItems);
                            // Single item
                            if (importedItems instanceof paper.Path && importedItems.closed) {
                                this.layerCounters.rectangle++;
                                importedItems.name = `rectangle ${this.layerCounters.rectangle}`;
                            } else if (importedItems instanceof paper.Path) {
                                this.layerCounters.line++;
                                importedItems.name = `line ${this.layerCounters.line}`;
                            } else if (importedItems instanceof paper.Path.Circle) {
                                this.layerCounters.point++;
                                importedItems.name = `point ${this.layerCounters.point}`;
                            }
                        }

                        // Add the main layer to the project
                        this.scope.project.addLayer(mainLayer);

                        this.scope.view.update();
                        console.log('Main JSON imported successfully');

                        // Emit event to notify parent that layers are ready
                        this.$emit('layers_ready');
                    } catch (error) {
                        console.log('Error importing main JSON:', error);
                    }
                }
            },

            importRelatedJsons() {
                if (this.related_jsons && Array.isArray(this.related_jsons) && this.scope) {
                    // Define a set of distinct colors for related routes
                    const colors = ['#0000ff', '#00ff00', '#ff00ff', '#ffff00', '#00ffff', '#ff8000', '#8000ff', '#00ff80', '#ff0080', '#808080'];

                    this.related_jsons.forEach((jsonData, index) => {
                        try {
                            let parsedData = jsonData;
                            if (typeof jsonData === 'string') {
                                parsedData = JSON.parse(jsonData);
                            }
                            this.scope.activate();

                            // Create a new layer for related drawings
                            const relatedLayer = new paper.Layer();
                            relatedLayer.name = `related-${index}`;

                            // Import into the new layer with error handling
                            let importedItems;
                            try {
                                // Validate that parsedData is a valid object for PaperJS
                                if (!parsedData || typeof parsedData !== 'object') {
                                    throw new Error('Invalid JSON data for PaperJS import');
                                }

                                // Try to import with PaperJS
                                importedItems = relatedLayer.importJSON(parsedData);
                            } catch (importError) {
                                console.error(`Error during importJSON for related route ${index + 1}:`, importError);
                                console.error('JSON data:', parsedData);
                                return; // Skip this route if import fails
                            }

                            // Apply different color for each related route
                            const color = colors[index % colors.length];

                            // Function to recursively set color and lock items
                            const setItemProperties = (item) => {
                                try {
                                    // Check if item is a valid PaperJS item
                                    if (item && typeof item === 'object' && item.constructor && item.constructor.name) {
                                        // Only process if it's a PaperJS item (has PaperJS-specific properties/methods)
                                        if (item.strokeColor !== undefined || item.fillColor !== undefined || item.locked !== undefined) {
                                            if (item.strokeColor !== undefined) {
                                                item.strokeColor = color;
                                            }
                                            if (item.fillColor !== undefined) {
                                                item.fillColor = color;
                                            }
                                            if (item.locked !== undefined) {
                                                item.locked = true; // Make non-editable
                                            }

                                            // Recursively apply to children
                                            if (item.children && Array.isArray(item.children)) {
                                                item.children.forEach(child => setItemProperties(child));
                                            }
                                        }
                                    }
                                } catch (propError) {
                                    console.warn(`Error setting properties for item in related route ${index + 1}:`, propError);
                                }
                            };

                            // Apply properties to imported items
                            if (Array.isArray(importedItems)) {
                                importedItems.forEach(item => setItemProperties(item));
                            } else if (importedItems) {
                                setItemProperties(importedItems);
                            }

                            // Add the layer to the project
                            this.scope.project.addLayer(relatedLayer);

                            this.scope.view.update();
                            console.log(`Related JSON ${index + 1} imported successfully with color ${color}`);
                        } catch (error) {
                            console.error(`Error importing related JSON ${index + 1}:`, error);
                        }
                    });
                }
            },


            // add_layer(){
            //     var layer = new paper.Layer({
            //         children: [path, path2],
            //         strokeColor: 'black',
            //         position: view.center
            //     });
            // },
            
            mouseDown() {
                this.tool = this.createTool(this.scope);

                this.tool.onMouseDown = (event) => {
                    if (this.action_prop == 1) {
                        this.add_line();
                    }
                    else if (this.action_prop == 2){
                        this.add_point(event);
                    }
                    else if (this.action_prop == 3){
                        this.add_line();
                        this.add_point(event);
                    }
                    else if (this.action_prop == 4){
                        this.add_rectangle(event)
                    }
                    else if (this.action_prop == 5){
                        this.erase_at_point(event);
                    }
                    else if (this.action_prop == 6){
                        this.erase_segment_at_point(event);
                    }
                };

                this.tool.onMouseDrag = (event) => {
                    if (this.action_prop == 1 || this.action_prop == 3) {
                        if (this.path) {
                            this.path.add(event.point);
                        }
                    } else if (this.action_prop == 4) {
                        // Update rectangle corners for live preview
                        if (this.path && this.path.data && this.path.data.isRectangle) {
                            const startPoint = this.path.data.startPoint;
                            // Update all four corners to create rectangle
                            this.path.segments[0].point = startPoint; // top-left
                            this.path.segments[1].point = new paper.Point(event.point.x, startPoint.y); // top-right
                            this.path.segments[2].point = event.point; // bottom-right
                            this.path.segments[3].point = new paper.Point(startPoint.x, event.point.y); // bottom-left
                        }
                    }
                };
                
                this.tool.onMouseUp = () => {
                    this.path = null;
                    this.saveCanvasData(); // Save canvas data after drawing
                    // Emit event to update layers list in parent component
                    this.$emit('layers_updated');
                }
            },

            mouseUp(){
                this.tool.onMouseUp = (event) => {
                    if (this.action_prop == 3){
                        this.add_point(event);
                    }
                    else if (this.action_prop == 4){
                        paper.Path.Rectangle({
                            to: [event.event.offsetX, event.event.offsetY],
                        })
                    }
                }

                this.saveCanvasData()
            },

            erase_at_point(event){
                // Find items at the click point and remove them completely (only editable items, not locked ones)
                const hitResult = this.scope.project.hitTest(event.point, {
                    fill: true,
                    stroke: true,
                    segments: true,
                    tolerance: 15 // Increased tolerance for easier clicking
                });

                if (hitResult && hitResult.item) {
                    // Only erase if the item is not locked (related routes are locked)
                    if (!hitResult.item.locked) {
                        hitResult.item.remove();
                        this.scope.view.update(); // Update the canvas view to show changes
                        this.saveCanvasData();
                        console.log('Item erased completely');
                    } else {
                        console.log('Cannot erase locked item (related route)');
                    }
                } else {
                    console.log('No item found at click point');
                }
            },

            erase_segment_at_point(event){
                // Find items at the click point and partially erase them (remove segments from paths)
                const hitResult = this.scope.project.hitTest(event.point, {
                    fill: true,
                    stroke: true,
                    segments: true,
                    tolerance: 20 // Increased tolerance for easier clicking
                });

                console.log('Erase segment called, hitResult:', hitResult);

                if (hitResult && hitResult.item) {
                    console.log('Item found:', hitResult.item, 'Locked:', hitResult.item.locked);
                    // Only erase if the item is not locked (related routes are locked)
                    if (!hitResult.item.locked) {
                        console.log('Item is not locked, proceeding with erase');
                        // If it's a path with segments, remove the closest segment
                        if (hitResult.item instanceof paper.Path && hitResult.item.segments && hitResult.item.segments.length > 1) {
                            console.log('Item is a path with', hitResult.item.segments.length, 'segments');
                            // Find the closest segment to the click point
                            let closestSegment = null;
                            let minDistance = Infinity;

                            hitResult.item.segments.forEach((segment, index) => {
                                const distance = segment.point.getDistance(event.point);
                                console.log('Segment', index, 'distance:', distance);
                                if (distance < minDistance) {
                                    minDistance = distance;
                                    closestSegment = { segment, index };
                                }
                            });

                            console.log('Closest segment:', closestSegment, 'at distance:', minDistance);

                            if (closestSegment && hitResult.item.segments.length > 2) {
                                // Remove the segment (but keep at least 2 segments for a valid path)
                                console.log('Removing segment at index', closestSegment.index);
                                hitResult.item.removeSegment(closestSegment.index);
                                console.log('Segment erased from path');
                            } else if (closestSegment && hitResult.item.segments.length <= 2) {
                                // If only 2 segments left, remove the entire path
                                console.log('Path has <=2 segments, removing entire path');
                                hitResult.item.remove();
                                console.log('Small path erased completely');
                            }
                        } else {
                            // For non-path items (like circles), remove the entire item
                            console.log('Item is not a path, removing entire item');
                            hitResult.item.remove();
                            console.log('Item erased completely');
                        }

                        this.scope.view.update(); // Update the canvas view to show changes
                        this.saveCanvasData();
                    } else {
                        console.log('Cannot erase locked item (related route)');
                    }
                } else {
                    console.log('No item found at click point');
                }
            },

            saveCanvasData() {
                const canvasData = JSON.stringify( this.scope.project.exportJSON());
                this.$emit('canvas_data', canvasData)
            }
        },
    }
</script>

<style scoped>
    .canvas-style {
        cursor: crosshair;
        width: 100% !important;
        height: 500px !important;
        border: 2px solid rgb(139, 139, 139);
        /* border-radius: 10px; */
        display: block;
        margin: auto;
        /* box-shadow: 0 10px 8px -8px black; */
    }

    /* stylize panel */
    paperjs-layer-panel main {
        position : fixed;
        top      : 15px;
        z-index  : 1;
        left     : 15px;
    }
</style>