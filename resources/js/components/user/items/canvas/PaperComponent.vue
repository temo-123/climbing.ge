ut<template>
    <!-- <div> -->
        <canvas
            :id="canvas_id_prop"
            class="canvas-style"
            v-on:mousedown="mouseDown"
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
            selectedItem: null, // for move action
            group: null, // for grouping multiple objects as sublayers
            groupCounter: 0, // counter for unique group names
            layerCounters: {
                line: 0,
                point: 0,
                rectangle: 0,
                group: 0
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

            // Save initial empty state for undo functionality
            this.saveCanvasData();

            // paperjsLayersPanel.create();
        },
        methods: {
            reset() {
                this.scope.project.activeLayer.removeChildren();
                this.selectedItem = null; // Clear selection on reset
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
                // If a group is active, add the point as a sublayer to the group
                if (this.group) {
                    this.group.addChild(point);
                }
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
                // If a group is active, add the line as a sublayer to the group
                if (this.group) {
                    this.group.addChild(this.path);
                }
                return this.path;
            },

            createGroup() {
                // Create a new group for grouping multiple objects as sublayers
                this.groupCounter++;
                this.group = new paper.Group();
                this.group.name = `group ${this.groupCounter}`;
                // Add the new group to the active layer
                this.scope.project.activeLayer.addChild(this.group);
            },

            add_line_and_point(){

            },

            add_line_for_combined(mousePoint){
                this.layerCounters.line++;
                this.currentLine = new paper.Path({
                    strokeColor: "#ff0000",
                    strokeWidth: 3,
                    strokeJoin: 'round',
                    name: `line ${this.layerCounters.line}`
                });
                // Start the line from the closest point on the rectangle perimeter to the mouse position
                if (this.path && this.path.data && this.path.data.isRectangle) {
                    const rectBounds = this.path.bounds;
                    const startPoint = this.getClosestPerimeterPoint(rectBounds, mousePoint);
                    this.currentLine.add(startPoint);
                }
                return this.currentLine;
            },

            getClosestPerimeterPoint(rectBounds, mousePoint) {
                // Define corners and midpoints of sides
                const points = [
                    { point: new paper.Point(rectBounds.left, rectBounds.top), direction: new paper.Point(-1, -1) },         // top-left corner
                    { point: new paper.Point(rectBounds.center.x, rectBounds.top), direction: new paper.Point(0, -1) },     // top middle
                    { point: new paper.Point(rectBounds.right, rectBounds.top), direction: new paper.Point(1, -1) },      // top-right corner
                    { point: new paper.Point(rectBounds.right, rectBounds.center.y), direction: new paper.Point(1, 0) },   // right middle
                    { point: new paper.Point(rectBounds.right, rectBounds.bottom), direction: new paper.Point(1, 1) },    // bottom-right corner
                    { point: new paper.Point(rectBounds.center.x, rectBounds.bottom), direction: new paper.Point(0, 1) },  // bottom middle
                    { point: new paper.Point(rectBounds.left, rectBounds.bottom), direction: new paper.Point(-1, 1) },    // bottom-left corner
                    { point: new paper.Point(rectBounds.left, rectBounds.center.y), direction: new paper.Point(-1, 0) }    // left middle
                ];

                let closestPoint = points[0];
                let minDistance = mousePoint.getDistance(closestPoint.point);

                points.forEach(point => {
                    const distance = mousePoint.getDistance(point.point);
                    if (distance < minDistance) {
                        minDistance = distance;
                        closestPoint = point;
                    }
                });

                // Return a point slightly outside the rectangle from the closest point
                const offset = 3; // Small offset to ensure line starts outside
                return new paper.Point(
                    closestPoint.point.x + (closestPoint.direction.x * offset),
                    closestPoint.point.y + (closestPoint.direction.y * offset)
                );
            },

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
                        // Combined action: line + point, create new group for grouping
                        this.createGroup();
                        this.add_line();
                        // this.add_point(event);
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
                    else if (this.action_prop == 7){
                        // Combined action: small rectangle + line + point, create new group for grouping
                        this.createGroup();
                        this.currentLine = null; // Reset any previous line
                        this.add_small_rectangle(event);
                        // Don't start line yet, wait for drag to determine start position
                    }
                    else if (this.action_prop == 8){
                        // Move action: select and move objects
                        this.selectItemForMove(event);
                        return; // Don't execute other actions when in move mode
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
                    } else if (this.action_prop == 7) {
                        // For combined action, start line on first drag and add points during drag but avoid going inside rectangle
                        if (!this.currentLine && this.path && this.path.data && this.path.data.isRectangle) {
                            // Start the line from the closest perimeter point to the current mouse position
                            this.add_line_for_combined(event.point);
                        }
                        if (this.currentLine && this.path && this.path.data && this.path.data.isRectangle) {
                            const rectBounds = this.path.bounds;
                            // Check if the point is inside the rectangle
                            const isInsideRect = event.point.x >= rectBounds.left &&
                                               event.point.x <= rectBounds.right &&
                                               event.point.y >= rectBounds.top &&
                                               event.point.y <= rectBounds.bottom;

                            // Only add the point if it's not inside the rectangle
                            if (!isInsideRect) {
                                this.currentLine.add(event.point);
                            }
                        }
                    } else if (this.action_prop == 8) {
                        // Move action: drag selected item
                        if (this.selectedItem) {
                            this.selectedItem.position = this.selectedItem.position.add(event.delta);
                            // Also move associated text label if it exists
                            if (this.selectedItem.data && this.selectedItem.data.textLabel) {
                                this.selectedItem.data.textLabel.position = this.selectedItem.data.textLabel.position.add(event.delta);
                            }
                        }
                    }
                };

                this.tool.onMouseUp = (event) => {
                    if (this.action_prop == 1 || this.action_prop == 3) {
                        if (this.path) {
                            this.add_point(event);
                        }
                    }
                    if (this.action_prop == 4 && this.path && this.path.data && this.path.data.isRectangle) {
                        // Update text label position to center of rectangle
                        const bounds = this.path.bounds;
                        const center = bounds.center;
                        if (this.path.data.textLabel) {
                            this.path.data.textLabel.point = center;
                        }
                    }
                    // For action_prop == 7, text is already centered inside the small rectangle
                    if (this.action_prop == 7) {
                        // For combined action, add point on mouse up
                        this.add_point(event);
                        this.currentLine = null; // Reset current line
                    }
                    if (this.action_prop == 8) {
                        // Move action: save canvas data after moving
                        if (this.selectedItem) {
                            this.saveCanvasData();
                        }
                    }
                    this.path = null;
                    // Only save canvas data for actions that actually create/modify items
                    if (this.action_prop !== 5 && this.action_prop !== 6) { // Don't save for erase actions
                        // For combined actions (3 and 7), only save once at the end
                        if (this.action_prop !== 3 && this.action_prop !== 7 && this.action_prop !== 8) {
                            this.saveCanvasData(); // Save canvas data after drawing
                        }
                    }
                    // For combined actions (3 and 7), save after all objects are added and reset group
                    if (this.action_prop == 3 || this.action_prop == 7) {
                        this.saveCanvasData();
                        this.group = null; // Reset group after combined action
                    }
                    // Emit event to update layers list in parent component
                    this.$emit('layers_updated');
                }
            },

            selectItemForMove(event) {
                // Find item at click point for moving (only editable items, not locked ones)
                const hitResult = this.scope.project.hitTest(event.point, {
                    fill: true,
                    stroke: true,
                    segments: true,
                    tolerance: 15 // Increased tolerance for easier clicking
                });

                if (hitResult && hitResult.item) {
                    let itemToSelect = hitResult.item;

                    // If clicked on a text label, select the associated rectangle instead
                    if (itemToSelect instanceof paper.PointText && itemToSelect.name && itemToSelect.name.startsWith('text ')) {
                        // Find the associated rectangle by matching the number
                        const textNumber = itemToSelect.name.replace('text ', '');
                        this.scope.project.layers.forEach(layer => {
                            layer.children.forEach(item => {
                                if (item.name === `rectangle ${textNumber}` && !item.locked) {
                                    itemToSelect = item;
                                }
                            });
                        });
                    }

                    // Only select if the item is not locked (related routes are locked)
                    if (!itemToSelect.locked) {
                        this.selectedItem = itemToSelect;
                        console.log('Item selected for moving:', this.selectedItem.name);
                    } else {
                        console.log('Cannot move locked item (related route)');
                        this.selectedItem = null;
                    }
                } else {
                    console.log('No item found at click point');
                    this.selectedItem = null;
                }
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

                // Add text label with route number at the center
                const text = new paper.PointText({
                    point: event.point, // Will be updated to center in onMouseUp
                    content: this.layerCounters.rectangle.toString(),
                    fillColor: '#ff0000',
                    fontFamily: 'Arial',
                    fontSize: 14,
                    justification: 'center'
                });
                text.name = `text ${this.layerCounters.rectangle}`;
                rect.data.textLabel = text;
                // Position text at the bottom of the rectangle initially
                text.point = new paper.Point(event.point.x, event.point.y + 20); // Adjust Y to place below

                return rect;
            },

            add_small_rectangle(event){
                this.layerCounters.rectangle++;
                const size = 20; // Fixed small size
                const halfSize = size / 2;
                const center = event.point;

                // Create a small rectangle centered at event.point with rounded corners
                const rect = new paper.Path.Rectangle({
                    point: [center.x - halfSize, center.y - halfSize],
                    size: [size, size],
                    radius: 3, // Rounded corners
                    strokeColor: '#ff0000',
                    strokeWidth: 3,
                    fillColor: null,
                    name: `rectangle ${this.layerCounters.rectangle}`
                });

                // Mark this path as a rectangle
                rect.data = { isRectangle: true, isSmall: true };
                this.path = rect;

                // Add text label with route number inside the rectangle (bigger and stronger)
                const text = new paper.PointText({
                    point: new paper.Point(center.x, center.y + 3), // Adjust Y slightly for better vertical centering
                    content: this.layerCounters.rectangle.toString(),
                    fillColor: '#ff0000',
                    fontFamily: 'Arial',
                    fontSize: 20,
                    fontWeight: 'bold',
                    justification: 'center'
                });
                text.name = `text ${this.layerCounters.rectangle}`;
                rect.data.textLabel = text;

                // If a group is active, add the rectangle and text as sublayers to the group
                if (this.group) {
                    this.group.addChild(rect);
                    this.group.addChild(text);
                }

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
                                        // Add text label for imported rectangle
                                        const bounds = item.bounds;
                                        const bottomCenter = new paper.Point(bounds.center.x, bounds.bottom);
                                        const text = new paper.PointText({
                                            point: bottomCenter,
                                            content: this.layerCounters.rectangle.toString(),
                                            fillColor: '#ff0000',
                                            fontFamily: 'Arial',
                                            fontSize: 14,
                                            justification: 'center'
                                        });
                                        text.name = `text ${this.layerCounters.rectangle}`;
                                        item.data = item.data || {};
                                        item.data.textLabel = text;
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
                                // Add text label for imported rectangle
                                const bounds = importedItems.bounds;
                                const bottomCenter = new paper.Point(bounds.center.x, bounds.bottom);
                                const text = new paper.PointText({
                                    point: bottomCenter,
                                    content: this.layerCounters.rectangle.toString(),
                                    fillColor: '#ff0000',
                                    fontFamily: 'Arial',
                                    fontSize: 14,
                                    justification: 'center'
                                });
                                text.name = `text ${this.layerCounters.rectangle}`;
                                importedItems.data = importedItems.data || {};
                                importedItems.data.textLabel = text;
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
                        // Also remove associated text label if it exists
                        if (hitResult.item.data && hitResult.item.data.textLabel) {
                            hitResult.item.data.textLabel.remove();
                        }
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
                                // Also remove associated text label if it exists
                                if (hitResult.item.data && hitResult.item.data.textLabel) {
                                    hitResult.item.data.textLabel.remove();
                                }
                                hitResult.item.remove();
                                console.log('Small path erased completely');
                            }
                        } else {
                            // For non-path items (like circles), remove the entire item
                            console.log('Item is not a path, removing entire item');
                            // Also remove associated text label if it exists
                            if (hitResult.item.data && hitResult.item.data.textLabel) {
                                hitResult.item.data.textLabel.remove();
                            }
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
                // Store current state in history before saving
                if (this.$parent && this.$parent.history) {
                    // Only add to history if the canvas has actually changed
                    const currentState = JSON.parse(canvasData);
                    const lastState = this.$parent.history.length > 0 ? this.$parent.history[this.$parent.history.length - 1] : null;

                    // Compare states to avoid duplicate entries
                    if (!lastState || JSON.stringify(currentState) !== JSON.stringify(lastState)) {
                        this.$parent.history.push(currentState);
                        // Limit history to last 10 states
                        if (this.$parent.history.length > 10) {
                            this.$parent.history.shift();
                        }
                        // Clear redo stack when new action is performed
                        if (this.$parent.redoStack) {
                            this.$parent.redoStack = [];
                        }
                    }
                }
                this.$emit('canvas_data', canvasData)
            },

            undoLastAction() {
                if (this.$parent && this.$parent.history && this.$parent.history.length > 0) {
                    // Save current state to redo stack before undoing
                    const currentState = JSON.stringify(this.scope.project.exportJSON());
                    this.$parent.redoStack.push(JSON.parse(currentState));

                    const lastState = this.$parent.history.pop();
                    // Restore the previous state
                    this.scope.project.clear();
                    if (lastState) {
                        this.scope.project.importJSON(lastState);
                    }
                    this.scope.view.update();
                    this.$emit('layers_updated');
                    // Emit the restored canvas data
                    this.$emit('canvas_data', JSON.stringify(this.scope.project.exportJSON()));
                } else {
                    console.log('No more actions to undo');
                }
                // Limit redo stack to last 10 states
                if (this.$parent.redoStack && this.$parent.redoStack.length > 10) {
                    this.$parent.redoStack = this.$parent.redoStack.slice(-10);
                }
            },

            redoLastAction() {
                if (this.$parent && this.$parent.redoStack && this.$parent.redoStack.length > 0) {
                    // Save current state to history before redoing
                    const currentState = JSON.stringify(this.scope.project.exportJSON());
                    this.$parent.history.push(JSON.parse(currentState));

                    const nextState = this.$parent.redoStack.pop();
                    // Restore the next state
                    this.scope.project.clear();
                    if (nextState) {
                        this.scope.project.importJSON(nextState);
                    }
                    this.scope.view.update();
                    this.$emit('layers_updated');
                    // Emit the restored canvas data
                    this.$emit('canvas_data', JSON.stringify(this.scope.project.exportJSON()));
                } else {
                    console.log('No more actions to redo');
                }
                // Limit history to last 10 states
                if (this.$parent.history && this.$parent.history.length > 10) {
                    this.$parent.history = this.$parent.history.slice(-10);
                }
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