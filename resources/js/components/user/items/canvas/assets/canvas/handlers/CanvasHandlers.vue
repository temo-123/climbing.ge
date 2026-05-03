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
                } else if (this.action == 9) {
                    this.startPan(event);
                } else if (this.action == 10) {
                    this.add_circle(event);
                } else if (this.action == 11) {
                    this.add_ellipse(event);
                } else if (this.action == 12) {
                    this.add_polygon(event);
                } else if (this.action == 13) {
                    this.add_text(event);
                } else if (this.action == 14) {
                    this.selectMultipleItems(event);
                }
            };

            this.tool.onMouseDrag = (event) => {
                if (this.action == 1 || this.action == 3) {
                    if (this.path) this.path.add(event.point);
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
                        if (!isInsideRect) this.currentLine.add(event.point);
                    }
                } else if (this.action == 8) {
                    if (this.selectedItem) {
                        this.selectedItem.position = this.selectedItem.position.add(event.delta);
                        if (this.selectedItem.data && this.selectedItem.data.textLabel) {
                            this.selectedItem.data.textLabel.position = this.selectedItem.data.textLabel.position.add(event.delta);
                        }
                    }
                } else if (this.action == 9) {
                    this.panCanvas(event);
                } else if (this.action == 10) {
                    // Resize circle in-place (no counter increment, no recreation)
                    if (this.path && this.path.data && this.path.data.isCircle) {
                        const center = this.path.data.center;
                        const radius = Math.max(1, center.getDistance(event.point));
                        const savedName = this.path.name;
                        const savedData = this.path.data;
                        this.path.remove();
                        this.path = new paper.Path.Circle({
                            center: center,
                            radius: radius,
                            strokeColor: this._stroke(),
                            strokeWidth: this._width(),
                            fillColor: this._fill(),
                            name: savedName
                        });
                        this.path.data = savedData;
                    }
                } else if (this.action == 11) {
                    // Resize ellipse in-place
                    if (this.path && this.path.data && this.path.data.isEllipse) {
                        const startPoint = this.path.data.startPoint;
                        const width  = Math.max(1, Math.abs(event.point.x - startPoint.x));
                        const height = Math.max(1, Math.abs(event.point.y - startPoint.y));
                        const savedName = this.path.name;
                        const savedData = this.path.data;
                        this.path.remove();
                        this.path = new paper.Path.Ellipse({
                            point: startPoint,
                            size: [width, height],
                            strokeColor: this._stroke(),
                            strokeWidth: this._width(),
                            fillColor: this._fill(),
                            name: savedName
                        });
                        this.path.data = savedData;
                    }
                } else if (this.action == 14) {
                    this.updateSelectionRectangle(event);
                }
            };

            this.tool.onMouseUp = (event) => {
                if (this.action == 1 || this.action == 3) {
                    if (this.path) this.add_point(event);
                }
                if (this.action == 4 && this.path && this.path.data && this.path.data.isRectangle) {
                    if (this.path.data.textLabel) {
                        this.path.data.textLabel.point = this.path.bounds.center;
                    }
                }
                if (this.action == 7) {
                    this.add_point(event);
                    this.currentLine = null;
                }
                if (this.action == 8 && this.selectedItem) {
                    this.saveCanvasData();
                }
                if (this.action == 9) {
                    this.endPan();
                }
                if (this.action == 12) {
                    this.finish_polygon(event);
                }
                if (this.action == 14) {
                    this.finishSelection(event);
                }
                this.path = null;
                if (this.action !== 5 && this.action !== 6) {
                    if (this.action !== 3 && this.action !== 7 && this.action !== 8 && this.action !== 9) {
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

        startPan(event) {
            this.isPanning = true;
            this.panStartPoint = event.point;
            this.scope.view.element.style.cursor = 'grabbing';
        },

        panCanvas(event) {
            if (this.isPanning && this.panStartPoint) {
                const delta = event.point.subtract(this.panStartPoint);
                this.scope.view.center = this.scope.view.center.subtract(delta);
                this.panStartPoint = event.point;
            }
        },

        endPan() {
            this.isPanning = false;
            this.panStartPoint = null;
            this.scope.view.element.style.cursor = 'crosshair';
        },

        selectMultipleItems(event) {
            this.selectionRect = new paper.Path.Rectangle(event.point, event.point);
            this.selectionRect.strokeColor = '#007bff';
            this.selectionRect.strokeWidth = 1;
            this.selectionRect.dashArray = [5, 5];
            this.selectionRect.fillColor = new paper.Color(0, 0.5, 1, 0.1);
            this.path = this.selectionRect;
            this.path.data = { isSelection: true };
        },

        updateSelectionRectangle(event) {
            if (this.path && this.path.data && this.path.data.isSelection) {
                const startPoint = this.path.segments[0].point;
                this.path.segments[1].point = new paper.Point(event.point.x, startPoint.y);
                this.path.segments[2].point = event.point;
                this.path.segments[3].point = new paper.Point(startPoint.x, event.point.y);
            }
        },

        finishSelection(event) {
            if (this.path && this.path.data && this.path.data.isSelection) {
                const bounds = this.path.bounds;
                const selectedItems = [];

                this.scope.project.layers.forEach(layer => {
                    layer.children.forEach(item => {
                        if (!item.locked && item.isInside(bounds)) {
                            selectedItems.push(item);
                        }
                    });
                });

                if (selectedItems.length > 1) {
                    const group = new paper.Group(selectedItems);
                    group.name = `group ${this.groupCounter + 1}`;
                    this.groupCounter++;
                    this.selectedItem = group;
                } else if (selectedItems.length === 1) {
                    this.selectedItem = selectedItems[0];
                }

                this.path.remove();
                this.saveCanvasData();
            }
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
                } else {
                    this.selectedItem = null;
                }
            } else {
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

            if (hitResult && hitResult.item && !hitResult.item.locked) {
                if (hitResult.item.data && hitResult.item.data.textLabel) {
                    hitResult.item.data.textLabel.remove();
                }
                hitResult.item.remove();
                this.scope.view.update();
                this.saveCanvasData();
            }
        },

        erase_segment_at_point(event) {
            const hitResult = this.scope.project.hitTest(event.point, {
                fill: true,
                stroke: true,
                segments: true,
                tolerance: 20
            });

            if (hitResult && hitResult.item && !hitResult.item.locked) {
                const item = hitResult.item;
                if (item instanceof paper.Path && item.segments && item.segments.length > 2) {
                    let closestSegment = null;
                    let minDistance = Infinity;
                    item.segments.forEach((segment, index) => {
                        const distance = segment.point.getDistance(event.point);
                        if (distance < minDistance) {
                            minDistance = distance;
                            closestSegment = { segment, index };
                        }
                    });
                    if (closestSegment) {
                        item.removeSegment(closestSegment.index);
                    }
                } else {
                    if (item.data && item.data.textLabel) item.data.textLabel.remove();
                    item.remove();
                }
                this.scope.view.update();
                this.saveCanvasData();
            }
        },

        importJsonData(jsonData) {
            if (!jsonData || !this.scope) return;
            try {
                let parsedData = typeof jsonData === 'string' ? JSON.parse(jsonData) : jsonData;
                this.scope.activate();

                // Remove existing main layer to prevent duplicates
                const existing = this.scope.project.layers.find(l => l.name === 'main');
                if (existing) existing.remove();

                const mainLayer = new paper.Layer();
                mainLayer.name = 'main';
                mainLayer.importJSON(parsedData);

                this.scope.project.addLayer(mainLayer);
                this.scope.view.update();
                this.$emit('layers_ready');
            } catch (e) {}
        },

        importRelatedJsons() {
            if (!this.scope) return;

            // Remove any previously imported related layers before re-importing
            const toRemove = this.scope.project.layers.filter(l => l.name && l.name.startsWith('related-'));
            toRemove.forEach(l => l.remove());

            if (!this.relatedJsons || !Array.isArray(this.relatedJsons) || this.relatedJsons.length === 0) return;

            const colors = ['#0000ff', '#00cc00', '#ff00ff', '#cccc00', '#00cccc', '#ff8000', '#8000ff', '#00ff80', '#ff0080', '#808080'];

            this.relatedJsons.forEach((jsonData, index) => {
                if (!jsonData) return;
                try {
                    let parsedData = typeof jsonData === 'string' ? JSON.parse(jsonData) : jsonData;
                    this.scope.activate();

                    const relatedLayer = new paper.Layer();
                    relatedLayer.name = `related-${index}`;

                    let importedItems;
                    try {
                        importedItems = relatedLayer.importJSON(parsedData);
                    } catch (e) {
                        return;
                    }

                    const color = colors[index % colors.length];

                    const applyColor = (item) => {
                        if (!item || typeof item !== 'object') return;
                        try {
                            if (item.strokeColor !== undefined) item.strokeColor = color;
                            if (item.fillColor !== undefined) item.fillColor = color;
                            item.locked = true;
                            if (item.children) item.children.forEach(child => applyColor(child));
                        } catch (e) {}
                    };

                    if (Array.isArray(importedItems)) {
                        importedItems.forEach(item => applyColor(item));
                    } else if (importedItems) {
                        applyColor(importedItems);
                    }

                    this.scope.project.addLayer(relatedLayer);
                    this.scope.view.update();
                } catch (e) {}

            });
        }
    }
}
</script>
