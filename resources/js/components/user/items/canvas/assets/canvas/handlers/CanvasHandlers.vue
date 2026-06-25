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
                    this.startSelection(event);
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
                } else if (this.action == 5) {
                    // Erase continuously while dragging
                    this.erase_at_point(event);
                } else if (this.action == 6) {
                    // Erase segments continuously while dragging
                    this.erase_segment_at_point(event);
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
                            this.selectedItem.data.textLabel.position =
                                this.selectedItem.data.textLabel.position.add(event.delta);
                        }
                    }
                } else if (this.action == 9) {
                    this.panCanvas(event);
                } else if (this.action == 10) {
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
                } else if (this.action == 12) {
                    // Resize polygon radius by dragging
                    if (this.path && this.path.data && this.path.data.isPolygon) {
                        const center = this.path.data.center;
                        const radius = Math.max(5, center.getDistance(event.point));
                        this._buildPolygon(this.path, center, radius, this.path.data.sides || 6);
                    }
                } else if (this.action == 14) {
                    this.updateSelectionRectangle(event);
                }
            };

            this.tool.onMouseUp = (event) => {
                // Action 1 = plain line — no endpoint dot.
                // Action 3 = numbered route (line + group) — adds endpoint dot.
                if (this.action == 3) {
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
                    // Clear visual selection highlight after move
                    this.selectedItem.selected = false;
                    this.saveCanvasData();
                }
                if (this.action == 9) {
                    this.endPan();
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

        // Pan uses native screen coordinates so deltas remain correct after view.center changes.
        startPan(event) {
            this.isPanning = true;
            this.panLastScreen = new paper.Point(event.event.offsetX, event.event.offsetY);
            this.scope.view.element.style.cursor = 'grabbing';
        },

        panCanvas(event) {
            if (!this.isPanning || !this.panLastScreen) return;
            const screenPoint = new paper.Point(event.event.offsetX, event.event.offsetY);
            const delta = screenPoint.subtract(this.panLastScreen);
            // Convert screen-pixel delta to project units and move center in opposite direction
            this.scope.view.center = this.scope.view.center.subtract(
                delta.divide(this.scope.view.zoom)
            );
            this.panLastScreen = screenPoint;
            this._clampPan();
        },

        endPan() {
            this.isPanning   = false;
            this.panLastScreen = null;
            this.scope.view.element.style.cursor = 'crosshair';
        },

        // Draws a dashed selection rectangle; items are highlighted on mouseUp.
        startSelection(event) {
            this.path = new paper.Path.Rectangle(event.point, event.point);
            this.path.strokeColor = '#007bff';
            this.path.strokeWidth = 1;
            this.path.dashArray   = [5, 5];
            this.path.fillColor   = new paper.Color(0, 0.5, 1, 0.1);
            this.path.data        = { isSelection: true, startPoint: event.point };
        },

        updateSelectionRectangle(event) {
            if (this.path && this.path.data && this.path.data.isSelection) {
                const start = this.path.data.startPoint;
                this.path.segments[0].point = start;
                this.path.segments[1].point = new paper.Point(event.point.x, start.y);
                this.path.segments[2].point = event.point;
                this.path.segments[3].point = new paper.Point(start.x, event.point.y);
            }
        },

        // Highlights matching items visually; does NOT auto-group them.
        finishSelection(event) {
            if (!this.path || !this.path.data || !this.path.data.isSelection) return;
            const bounds = this.path.bounds;

            // Clear any previous selection highlight
            this.scope.project.layers.forEach(layer => {
                layer.children.forEach(item => { item.selected = false; });
            });

            // Highlight items that intersect the selection rectangle
            this.scope.project.layers.forEach(layer => {
                layer.children.forEach(item => {
                    if (!item.locked && item !== this.path && item.bounds.intersects(bounds)) {
                        item.selected = true;
                    }
                });
            });

            this.path.remove();
            this.path = null;
            this.scope.view.update();
        },

        // Selects an item for moving; bubbles up to the parent Group when a child is hit.
        selectItemForMove(event) {
            // Clear previous selection highlight
            if (this.selectedItem) {
                this.selectedItem.selected = false;
                this.selectedItem = null;
            }

            const hitResult = this.scope.project.hitTest(event.point, {
                fill: true,
                stroke: true,
                segments: true,
                tolerance: 15
            });

            if (!hitResult || !hitResult.item) return;

            let item = hitResult.item;

            // Bubble up to the topmost unlocked Group
            while (item.parent && item.parent instanceof paper.Group && !item.parent.locked) {
                item = item.parent;
            }

            // If a text label is clicked, select the associated rectangle instead
            if (item instanceof paper.PointText && item.name && item.name.startsWith('text ')) {
                const num = item.name.replace('text ', '');
                let found = null;
                this.scope.project.layers.forEach(layer => {
                    layer.children.forEach(child => {
                        if (child.name === `rectangle ${num}` && !child.locked) found = child;
                    });
                });
                if (found) item = found;
            }

            if (!item.locked) {
                this.selectedItem = item;
                item.selected = true;
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

        // Trims the path: finds the segment closest to the click and removes
        // it plus all segments from that point to the nearest end of the path.
        // This gives a natural "cut the tail" behaviour when editing route lines.
        erase_segment_at_point(event) {
            const hitResult = this.scope.project.hitTest(event.point, {
                fill: true,
                stroke: true,
                segments: true,
                tolerance: 20
            });

            if (!hitResult || !hitResult.item || hitResult.item.locked) return;

            const item = hitResult.item;
            if (item instanceof paper.Path && item.segments && item.segments.length > 1) {
                // Find the closest segment index
                let closestIndex = 0;
                let minDistance = Infinity;
                item.segments.forEach((seg, i) => {
                    const d = seg.point.getDistance(event.point);
                    if (d < minDistance) { minDistance = d; closestIndex = i; }
                });

                const total = item.segments.length;
                // Trim toward whichever end is closer to the click point
                const trimFromEnd = closestIndex >= total / 2;
                if (trimFromEnd) {
                    // Remove from closestIndex to the end
                    const count = total - closestIndex;
                    for (let i = 0; i < count; i++) {
                        item.removeSegment(closestIndex);
                    }
                } else {
                    // Remove from start to closestIndex (inclusive)
                    for (let i = 0; i <= closestIndex; i++) {
                        item.removeSegment(0);
                    }
                }

                // Remove the entire item if fewer than 2 segments remain
                if (item.segments.length < 2) {
                    if (item.data && item.data.textLabel) item.data.textLabel.remove();
                    item.remove();
                }
            } else {
                if (item.data && item.data.textLabel) item.data.textLabel.remove();
                item.remove();
            }
            this.scope.view.update();
            this.saveCanvasData();
        },

        importJsonData(jsonData) {
            if (!jsonData || !this.scope) return;
            try {
                let parsedData = typeof jsonData === 'string' ? JSON.parse(jsonData) : jsonData;
                this.scope.activate();

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
                            if (item.fillColor   !== undefined) item.fillColor   = color;
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
