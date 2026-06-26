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
                    this.add_combined_number(event);
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
                } else if (this.action == 15) {
                    this.startCropSelection(event);
                } else if (this.action == 16) {
                    this.selectSegmentPoint(event);
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
                } else if (this.action == 15) {
                    this.updateCropRectangle(event);
                } else if (this.action == 16) {
                    if (this.editingSegment) {
                        this.editingSegment.point = event.point;
                        if (this.editingSegmentDot) {
                            this.editingSegmentDot.position = this.editingSegmentDot.position.add(event.delta);
                        }
                        this.scope.view.update();
                    }
                }
            };

            this.tool.onMouseUp = (event) => {
                // Action 1 = plain line — no endpoint dot.
                // Action 3 = numbered route (line + group) — adds endpoint dot.
                if (this.action == 3) {
                    if (this.path) this.add_point(event);
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
                if (this.action == 15) {
                    this.finishCropAndDownload();
                }
                if (this.action == 16) {
                    if (this.editingSegment) {
                        this.editingSegment = null;
                        this.editingSegmentDot = null;
                        this.saveCanvasData();
                    }
                }
                this.path = null;
                if (this.action !== 5 && this.action !== 6) {
                    if (this.action !== 3 && this.action !== 7 && this.action !== 8 && this.action !== 9 && this.action !== 15 && this.action !== 16) {
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

        // Draws a dashed orange rectangle; on mouseUp the area is cropped and downloaded.
        startCropSelection(event) {
            if (this.path) { this.path.remove(); this.path = null; }
            this.path = new paper.Path.Rectangle(event.point, event.point);
            this.path.strokeColor = '#ff6600';
            this.path.strokeWidth = 2;
            this.path.dashArray = [8, 4];
            this.path.fillColor = new paper.Color(1, 0.4, 0, 0.15);
            this.path.data = { isCrop: true, startPoint: event.point };
        },

        updateCropRectangle(event) {
            if (this.path && this.path.data && this.path.data.isCrop) {
                const start = this.path.data.startPoint;
                this.path.segments[0].point = start;
                this.path.segments[1].point = new paper.Point(event.point.x, start.y);
                this.path.segments[2].point = event.point;
                this.path.segments[3].point = new paper.Point(start.x, event.point.y);
            }
        },

        // Crops the canvas pixel area matching the selection and triggers a PNG download.
        finishCropAndDownload() {
            if (!this.path || !this.path.data || !this.path.data.isCrop) {
                this.path = null;
                return;
            }
            const bounds = this.path.bounds;
            this.path.remove();
            this.path = null;
            this.scope.view.update();

            if (bounds.width < 5 || bounds.height < 5) return;

            const tl = this.scope.view.projectToView(bounds.topLeft);
            const br = this.scope.view.projectToView(bounds.bottomRight);
            const x = Math.round(Math.min(tl.x, br.x));
            const y = Math.round(Math.min(tl.y, br.y));
            const w = Math.round(Math.abs(br.x - tl.x));
            const h = Math.round(Math.abs(br.y - tl.y));
            if (w <= 0 || h <= 0) return;

            const temp = document.createElement('canvas');
            temp.width = w;
            temp.height = h;
            temp.getContext('2d').drawImage(this.scope.view.element, x, y, w, h, 0, 0, w, h);
            const link = document.createElement('a');
            link.download = 'cropped-image.png';
            link.href = temp.toDataURL('image/png');
            link.click();
        },

        // Finds the closest segment point on any unlocked Path within a 20px tolerance.
        // If it's an endpoint of a line inside a Group, also tracks the associated dot
        // so dragging moves both the segment and the endpoint circle together.
        selectSegmentPoint(event) {
            this.editingSegment = null;
            this.editingSegmentDot = null;
            let closestSeg = null;
            let minDist = 20;

            const search = (item) => {
                if (!item || item.locked) return;
                if (item instanceof paper.Path && item.segments) {
                    item.segments.forEach(seg => {
                        const d = seg.point.getDistance(event.point);
                        if (d < minDist) { minDist = d; closestSeg = seg; }
                    });
                }
                if (item.children) item.children.forEach(search);
            };

            this.scope.project.layers.forEach(layer => {
                if (layer.locked) return;
                layer.children.forEach(search);
            });

            this.editingSegment = closestSeg;

            if (closestSeg) {
                const p = closestSeg.path;
                const isEndpoint = closestSeg.index === 0 || closestSeg.index === p.segments.length - 1;
                if (isEndpoint && p.parent instanceof paper.Group) {
                    const segPos = closestSeg.point;
                    let closestDot = null;
                    let minDotDist = 40;
                    p.parent.children.forEach(child => {
                        if (child instanceof paper.Path && (child.name || '').startsWith('point ')) {
                            const d = child.bounds.center.getDistance(segPos);
                            if (d < minDotDist) { minDotDist = d; closestDot = child; }
                        }
                    });
                    this.editingSegmentDot = closestDot;
                }
            }
        },

        // Reposition all related-* layers so they sit BELOW 'main' in the layer stack.
        // 'main' must be the topmost drawing layer so hit-testing (erase, move) favours it
        // over locked related-route overlays.
        _repositionRelatedLayersBelow() {
            if (!this.scope) return;
            const mainLayer = this.scope.project.layers.find(l => l.name === 'main');
            if (!mainLayer) return;
            this.scope.project.layers
                .filter(l => l.name && l.name.startsWith('related-'))
                .sort((a, b) => parseInt(a.name.split('-')[1]) - parseInt(b.name.split('-')[1]))
                .forEach(l => l.insertBelow(mainLayer));
        },

        importJsonData(jsonData) {
            if (!jsonData || !this.scope) return;
            try {
                let parsedData = typeof jsonData === 'string' ? JSON.parse(jsonData) : jsonData;
                this.scope.activate();

                // Remove existing main layer
                const existingMain = this.scope.project.layers.find(l => l.name === 'main');
                if (existingMain) existingMain.remove();

                // Stash related layers so project.importJSON doesn't lose them
                const related = this.scope.project.layers.filter(l => l.name && l.name.startsWith('related-'));
                related.forEach(l => l.remove());

                // project.importJSON appends layers at the correct top level (no nesting)
                this.scope.project.importJSON(parsedData);

                // Rename any imported layers to 'main'
                this.scope.project.layers
                    .filter(l => !l.name || (!l.name.startsWith('related-') && l.name !== 'background'))
                    .forEach(l => { l.name = 'main'; });

                // Restore related layers then reposition them BELOW 'main' so that
                // hit-testing always hits the editable main layer first.
                related.forEach(l => this.scope.project.addLayer(l));
                this._repositionRelatedLayersBelow();

                this._activateMainLayer();
                this.scope.view.update();
                this.$emit('layers_ready');
            } catch (e) {}
        },

        importRelatedJsons() {
            if (!this.scope) return;

            // Remove old related layers
            this.scope.project.layers.filter(l => l.name && l.name.startsWith('related-')).forEach(l => l.remove());

            if (!this.relatedJsons || !Array.isArray(this.relatedJsons) || this.relatedJsons.length === 0) return;

            const colors = ['#0000ff', '#00cc00', '#ff00ff', '#cccc00', '#00cccc', '#ff8000', '#8000ff', '#00ff80', '#ff0080', '#808080'];

            this.scope.activate();

            this.relatedJsons.forEach((jsonData, index) => {
                if (!jsonData) return;
                try {
                    let parsedData = typeof jsonData === 'string' ? JSON.parse(jsonData) : jsonData;

                    // Record existing layer count so we can identify newly-added layers
                    const before = this.scope.project.layers.length;

                    // project.importJSON appends layers — no nesting into a wrapper layer
                    this.scope.project.importJSON(parsedData);

                    // Grab only the layers that were just added
                    const newLayers = this.scope.project.layers.slice(before);
                    const color = colors[index % colors.length];

                    newLayers.forEach(layer => {
                        layer.name = `related-${index}`;
                        layer.locked = true;

                        const applyColor = (item) => {
                            if (!item || typeof item !== 'object') return;
                            try {
                                if (typeof item.strokeColor !== 'undefined') item.strokeColor = color;
                                if (typeof item.fillColor   !== 'undefined') item.fillColor   = null;
                                item.locked = true;
                                if (item.children && item.children.length) {
                                    item.children.forEach(applyColor);
                                }
                            } catch (_) {}
                        };

                        if (layer.children) layer.children.forEach(applyColor);
                    });

                    this.scope.view.update();
                } catch (e) {}
            });

            // Activate main before repositioning so _activateMainLayer can find / create it,
            // then move related layers below it so hit-testing favours the main layer.
            this._activateMainLayer();
            this._repositionRelatedLayersBelow();
        }
    }
}
</script>
