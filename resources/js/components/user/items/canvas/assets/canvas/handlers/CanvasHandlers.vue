<script>
import paper from 'paper';

export default {
    data: () => ({
        path: null,
        currentLine: null
    }),
    methods: {
        mouseDown() {
            this.tool = this.createTool(this.scope);

            this.tool.onMouseDown = (event) => {
                if (this.action == 1) {
                    this.add_line();
                } else if (this.action == 2) {
                    this.add_point(event);
                } else if (this.action == 3) {
                    this.createGroup();
                    this.add_line();
                } else if (this.action == 4) {
                    this.add_rectangle(event);
                } else if (this.action == 5) {
                    this.erase_at_point(event);
                } else if (this.action == 6) {
                    this.erase_segment_at_point(event);
                } else if (this.action == 7) {
                    this.createGroup();
                    this.currentLine = null;
                    this.add_small_rectangle(event);
                } else if (this.action == 8) {
                    this.selectItemForMove(event);
                    return;
                }
            };

            this.tool.onMouseDrag = (event) => {
                if (this.action == 1 || this.action == 3) {
                    if (this.path) {
                        this.path.add(event.point);
                    }
                } else if (this.action == 4) {
                    if (this.path && this.path.data && this.path.data.isRectangle) {
                        const startPoint = this.path.data.startPoint;
                        this.path.segments[0].point = startPoint;
                        this.path.segments[1].point = new paper.Point(event.point.x, startPoint.y);
                        this.path.segments[2].point = event.point;
                        this.path.segments[3].point = new paper.Point(startPoint.x, event.point.y);
                    }
                } else if (this.action == 7) {
                    if (!this.currentLine && this.path && this.path.data && this.path.data.isRectangle) {
                        this.add_line_for_combined(event.point);
                    }
                    if (this.currentLine && this.path && this.path.data && this.path.data.isRectangle) {
                        const rectBounds = this.path.bounds;
                        const isInsideRect = event.point.x >= rectBounds.left &&
                                           event.point.x <= rectBounds.right &&
                                           event.point.y >= rectBounds.top &&
                                           event.point.y <= rectBounds.bottom;

                        if (!isInsideRect) {
                            this.currentLine.add(event.point);
                        }
                    }
                } else if (this.action == 8) {
                    if (this.selectedItem) {
                        this.selectedItem.position = this.selectedItem.position.add(event.delta);
                        if (this.selectedItem.data && this.selectedItem.data.textLabel) {
                            this.selectedItem.data.textLabel.position = this.selectedItem.data.textLabel.position.add(event.delta);
                        }
                    }
                }
            };

            this.tool.onMouseUp = (event) => {
                if (this.action == 1 || this.action == 3) {
                    if (this.path) {
                        this.add_point(event);
                    }
                }
                if (this.action == 4 && this.path && this.path.data && this.path.data.isRectangle) {
                    const bounds = this.path.bounds;
                    const center = bounds.center;
                    if (this.path.data.textLabel) {
                        this.path.data.textLabel.point = center;
                    }
                }
                if (this.action == 7) {
                    this.add_point(event);
                    this.currentLine = null;
                }
                if (this.action == 8) {
                    if (this.selectedItem) {
                        this.saveCanvasData();
                    }
                }
                this.path = null;
                if (this.action !== 5 && this.action !== 6) {
                    if (this.action !== 3 && this.action !== 7 && this.action !== 8) {
                        this.saveCanvasData();
                    }
                }
                if (this.action == 3 || this.action == 7) {
                    this.saveCanvasData();
                    this.group = null;
                }
                this.$emit('layers_updated');
            };
        },

        selectItemForMove(event) {
            const hitResult = this.scope.project.hitTest(event.point, {
                fill: true,
                stroke: true,
                segments: true,
                tolerance: 15
            });

            if (hitResult && hitResult.item) {
                let itemToSelect = hitResult.item;

                if (itemToSelect instanceof paper.PointText && itemToSelect.name && itemToSelect.name.startsWith('text ')) {
                    const textNumber = itemToSelect.name.replace('text ', '');
                    this.scope.project.layers.forEach(layer => {
                        layer.children.forEach(item => {
                            if (item.name === `rectangle ${textNumber}` && !item.locked) {
                                itemToSelect = item;
                            }
                        });
                    });
                }

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

        erase_at_point(event) {
            const hitResult = this.scope.project.hitTest(event.point, {
                fill: true,
                stroke: true,
                segments: true,
                tolerance: 15
            });

            if (hitResult && hitResult.item) {
                if (!hitResult.item.locked) {
                    if (hitResult.item.data && hitResult.item.data.textLabel) {
                        hitResult.item.data.textLabel.remove();
                    }
                    hitResult.item.remove();
                    this.scope.view.update();
                    this.saveCanvasData();
                    console.log('Item erased completely');
                } else {
                    console.log('Cannot erase locked item (related route)');
                }
            } else {
                console.log('No item found at click point');
            }
        },

        erase_segment_at_point(event) {
            const hitResult = this.scope.project.hitTest(event.point, {
                fill: true,
                stroke: true,
                segments: true,
                tolerance: 20
            });

            console.log('Erase segment called, hitResult:', hitResult);

            if (hitResult && hitResult.item) {
                console.log('Item found:', hitResult.item, 'Locked:', hitResult.item.locked);
                if (!hitResult.item.locked) {
                    console.log('Item is not locked, proceeding with erase');
                    if (hitResult.item instanceof paper.Path && hitResult.item.segments && hitResult.item.segments.length > 1) {
                        console.log('Item is a path with', hitResult.item.segments.length, 'segments');
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
                            console.log('Removing segment at index', closestSegment.index);
                            hitResult.item.removeSegment(closestSegment.index);
                            console.log('Segment erased from path');
                        } else if (closestSegment && hitResult.item.segments.length <= 2) {
                            console.log('Path has <=2 segments, removing entire path');
                            if (hitResult.item.data && hitResult.item.data.textLabel) {
                                hitResult.item.data.textLabel.remove();
                            }
                            hitResult.item.remove();
                            console.log('Small path erased completely');
                        }
                    } else {
                        console.log('Item is not a path, removing entire item');
                        if (hitResult.item.data && hitResult.item.data.textLabel) {
                            hitResult.item.data.textLabel.remove();
                        }
                        hitResult.item.remove();
                        console.log('Item erased completely');
                    }

                    this.scope.view.update();
                    this.saveCanvasData();
                } else {
                    console.log('Cannot erase locked item (related route)');
                }
            } else {
                console.log('No item found at click point');
            }
        },

        importJsonData(jsonData) {
            if (jsonData && this.scope) {
                try {
                    let parsedData = jsonData;
                    if (typeof jsonData === 'string') {
                        parsedData = JSON.parse(jsonData);
                    }
                    this.scope.activate();

                    const mainLayer = new paper.Layer();
                    mainLayer.name = 'main';

                    const importedItems = mainLayer.importJSON(parsedData);

                    const setMainItemProperties = (item) => {
                        if (item.strokeColor) {
                            item.strokeColor = '#ff0000';
                        }
                        if (item.fillColor) {
                            item.fillColor = '#ff0000';
                        }
                        if (item.children) {
                            item.children.forEach(child => setMainItemProperties(child));
                        }
                    };

                    if (Array.isArray(importedItems)) {
                        importedItems.forEach((item, index) => {
                            setMainItemProperties(item);
                            if (!item.name) {
                                if (item instanceof paper.Path && item.closed) {
                                    this.layerCounters.rectangle++;
                                    item.name = `rectangle ${this.layerCounters.rectangle}`;
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
                        if (importedItems instanceof paper.Path && importedItems.closed) {
                            this.layerCounters.rectangle++;
                            importedItems.name = `rectangle ${this.layerCounters.rectangle}`;
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

                    this.scope.project.addLayer(mainLayer);
                    this.scope.view.update();
                    console.log('Main JSON imported successfully');
                    this.$emit('layers_ready');
                } catch (error) {
                    console.log('Error importing main JSON:', error);
                }
            }
        },

        importRelatedJsons() {
            if (this.relatedJsons && Array.isArray(this.relatedJsons) && this.scope) {
                const colors = ['#0000ff', '#00ff00', '#ff00ff', '#ffff00', '#00ffff', '#ff8000', '#8000ff', '#00ff80', '#ff0080', '#808080'];

                this.relatedJsons.forEach((jsonData, index) => {
                    try {
                        let parsedData = jsonData;
                        if (typeof jsonData === 'string') {
                            parsedData = JSON.parse(jsonData);
                        }
                        this.scope.activate();

                        const relatedLayer = new paper.Layer();
                        relatedLayer.name = `related-${index}`;

                        let importedItems;
                        try {
                            if (!parsedData || typeof parsedData !== 'object') {
                                throw new Error('Invalid JSON data for PaperJS import');
                            }
                            importedItems = relatedLayer.importJSON(parsedData);
                        } catch (importError) {
                            console.error(`Error during importJSON for related route ${index + 1}:`, importError);
                            console.error('JSON data:', parsedData);
                            return;
                        }

                        const color = colors[index % colors.length];

                        const setItemProperties = (item) => {
                            try {
                                if (item && typeof item === 'object' && item.constructor && item.constructor.name) {
                                    if (item.strokeColor !== undefined || item.fillColor !== undefined || item.locked !== undefined) {
                                        if (item.strokeColor !== undefined) {
                                            item.strokeColor = color;
                                        }
                                        if (item.fillColor !== undefined) {
                                            item.fillColor = color;
                                        }
                                        if (item.locked !== undefined) {
                                            item.locked = true;
                                        }
                                        if (item.children && Array.isArray(item.children)) {
                                            item.children.forEach(child => setItemProperties(child));
                                        }
                                    }
                                }
                            } catch (propError) {
                                console.warn(`Error setting properties for item in related route ${index + 1}:`, propError);
                            }
                        };

                        if (Array.isArray(importedItems)) {
                            importedItems.forEach(item => setItemProperties(item));
                        } else if (importedItems) {
                            setItemProperties(importedItems);
                        }

                        this.scope.project.addLayer(relatedLayer);
                        this.scope.view.update();
                        console.log(`Related JSON ${index + 1} imported successfully with color ${color}`);
                    } catch (error) {
                        console.error(`Error importing related JSON ${index + 1}:`, error);
                    }
                });
            }
        }
    }
}
</script>
