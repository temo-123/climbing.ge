<script>
import paper from 'paper';

export default {
    data: () => ({
        // Only primitive flags here — no Paper.js object refs in reactive data.
        _isDraggingSelection: false,
        _moveAllActive: false,
        _continueLineActive: false,
    }),

    mounted() {
        // All Paper.js item / object references as non-reactive instance props.
        // Vue's Proxy wraps data() values and breaks Paper.js matrix/transform ops.
        this.path                  = null;
        this.currentLine           = null;
        this._multiSelectedItems   = [];
        this._selectedResizeItem   = null;
        this._resizeHandle         = null;
        this._resizeStart          = null;
        this._resizeOriginalBounds = null;
    },

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
                } else if (this.action == 17) {
                    this._moveAllActive = true;
                } else if (this.action == 18) {
                    this.cutPathAtPoint(event);
                } else if (this.action == 19) {
                    this.startResizeOrSelect(event);
                } else if (this.action == 20) {
                    this.startContinueLine(event);
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
                    this.erase_at_point(event);
                } else if (this.action == 6) {
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
                        this.selectedItem.translate(event.delta);
                        if (this.selectedItem.data && this.selectedItem.data.textLabel) {
                            this.selectedItem.data.textLabel.translate(event.delta);
                        }
                        this.scope.view.update();
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
                    if (this.path && this.path.data && this.path.data.isPolygon) {
                        const center = this.path.data.center;
                        const radius = Math.max(5, center.getDistance(event.point));
                        this._buildPolygon(this.path, center, radius, this.path.data.sides || 6);
                    }
                } else if (this.action == 14) {
                    if (this._isDraggingSelection) {
                        (this._multiSelectedItems || []).forEach(item => {
                            if (!item.locked) item.translate(event.delta);
                        });
                        this.scope.view.update();
                    } else {
                        this.updateSelectionRectangle(event);
                    }
                } else if (this.action == 15) {
                    this.updateCropRectangle(event);
                } else if (this.action == 16) {
                    if (this.editingSegment) {
                        this.editingSegment.point = event.point;
                        if (this.editingSegmentDot) {
                            this.editingSegmentDot.translate(event.delta);
                        }
                        this.scope.view.update();
                    }
                } else if (this.action == 17) {
                    if (this._moveAllActive) {
                        const mainLayer = this.scope.project.layers.find(l => l.name === 'main');
                        if (mainLayer) mainLayer.children.forEach(item => item.translate(event.delta));
                        this.scope.view.update();
                    }
                } else if (this.action == 19) {
                    this.dragResize(event);
                } else if (this.action == 20) {
                    if (this.path) this.path.add(event.point);
                }
            };

            this.tool.onMouseUp = (event) => {
                if (this.action == 3) {
                    if (this.path) this.add_point(event);
                }
                if (this.action == 7) {
                    this.add_point(event);
                    this.currentLine = null;
                }
                if (this.action == 8 && this.selectedItem) {
                    this.selectedItem.selected = false;
                    this.saveCanvasData();
                }
                if (this.action == 9) {
                    this.endPan();
                }
                if (this.action == 14) {
                    if (this._isDraggingSelection) {
                        this._isDraggingSelection = false;
                        this.saveCanvasData();
                    } else {
                        this.finishSelection(event);
                    }
                    this.path = null;
                    this.$emit('layers_updated');
                    return;
                }
                if (this.action == 15) {
                    this.finishCrop();
                }
                if (this.action == 16) {
                    if (this.editingSegment) {
                        this.editingSegment = null;
                        this.editingSegmentDot = null;
                        this.saveCanvasData();
                    }
                }
                if (this.action == 17) {
                    this._moveAllActive = false;
                    this.saveCanvasData();
                }
                if (this.action == 19) {
                    if (this._resizeHandle) {
                        this._resizeHandle = null;
                        this._resizeStart = null;
                        this._resizeOriginalBounds = null;
                        this.saveCanvasData();
                    }
                }
                if (this.action == 20) {
                    this.path = null;
                    this.saveCanvasData();
                }

                this.path = null;

                // Auto-save for drawing actions that don't handle it themselves
                const noAutoSave = [5, 6, 8, 9, 15, 16, 17, 18, 19, 20];
                if (!noAutoSave.includes(this.action)) {
                    if (this.action !== 3 && this.action !== 7) {
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

        // ── Pan ──────────────────────────────────────────────────────────────
        startPan(event) {
            this.isPanning = true;
            this.panLastScreen = new paper.Point(event.event.offsetX, event.event.offsetY);
            this.scope.view.element.style.cursor = 'grabbing';
        },

        panCanvas(event) {
            if (!this.isPanning || !this.panLastScreen) return;
            const screenPoint = new paper.Point(event.event.offsetX, event.event.offsetY);
            const delta = screenPoint.subtract(this.panLastScreen);
            this.scope.view.center = this.scope.view.center.subtract(
                delta.divide(this.scope.view.zoom)
            );
            this.panLastScreen = screenPoint;
            this._clampPan();
        },

        endPan() {
            this.isPanning    = false;
            this.panLastScreen = null;
            this.scope.view.element.style.cursor = 'crosshair';
        },

        // ── Selection (action 14) ────────────────────────────────────────────
        startSelection(event) {
            // If items are already selected, check if clicking on one of them to drag
            if (this._multiSelectedItems && this._multiSelectedItems.length > 0) {
                const hitResult = this.scope.project.hitTest(event.point, {
                    fill: true, stroke: true, tolerance: 10
                });
                if (hitResult && hitResult.item && !hitResult.item.locked) {
                    // Bubble up: hitTest may return a child; check whether any ancestor is selected
                    let check = hitResult.item;
                    while (check) {
                        if (check.selected) { this._isDraggingSelection = true; return; }
                        check = check.parent;
                    }
                }
            }

            // Clear old selection and start rubber-band
            this._isDraggingSelection = false;
            this._multiSelectedItems = [];
            this.scope.project.layers.forEach(l => l.children.forEach(i => { i.selected = false; }));

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

        finishSelection(event) {
            if (!this.path || !this.path.data || !this.path.data.isSelection) return;
            const bounds = this.path.bounds;

            this.scope.project.layers.forEach(layer => {
                layer.children.forEach(item => { item.selected = false; });
            });

            this._multiSelectedItems = [];
            this.scope.project.layers.forEach(layer => {
                if (layer.name === 'background' || (layer.name && layer.name.startsWith('related-'))) return;
                layer.children.forEach(item => {
                    if (!item.locked && item !== this.path && item.bounds.intersects(bounds)) {
                        item.selected = true;
                        this._multiSelectedItems.push(item);
                    }
                });
            });

            this.path.remove();
            this.path = null;
            this.scope.view.update();
        },

        // ── Move single item (action 8) ──────────────────────────────────────
        selectItemForMove(event) {
            if (this.selectedItem) {
                this.selectedItem.selected = false;
                this.selectedItem = null;
            }

            const hitResult = this.scope.project.hitTest(event.point, {
                fill: true, stroke: true, segments: true, tolerance: 15
            });

            if (!hitResult || !hitResult.item) return;
            let item = hitResult.item;

            // Move exactly the item that was clicked, even if it belongs to a Group —
            // groups only organize items in the Layers panel, they should not force
            // every member to drag together. Previously this bubbled up to the
            // top-level Group, so moving one member of a group moved the whole group.

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
            } else {
                if (this.$bus) {
                    this.$bus.$emit('toast', {
                        type: 'warning',
                        title: 'Item locked',
                        message: 'Unlock this item in the Layers panel to move it.',
                        duration: 2500
                    });
                }
            }
        },

        // ── Erase (actions 5, 6) ─────────────────────────────────────────────
        erase_at_point(event) {
            const hitResult = this.scope.project.hitTest(event.point, {
                fill: true, stroke: true, segments: true, tolerance: 15
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
                fill: true, stroke: true, segments: true, tolerance: 20
            });
            if (!hitResult || !hitResult.item || hitResult.item.locked) return;

            const item = hitResult.item;
            if (item instanceof paper.Path && item.segments && item.segments.length > 1) {
                let closestIndex = 0;
                let minDistance = Infinity;
                item.segments.forEach((seg, i) => {
                    const d = seg.point.getDistance(event.point);
                    if (d < minDistance) { minDistance = d; closestIndex = i; }
                });
                const total = item.segments.length;
                const trimFromEnd = closestIndex >= total / 2;
                if (trimFromEnd) {
                    const count = total - closestIndex;
                    for (let i = 0; i < count; i++) item.removeSegment(closestIndex);
                } else {
                    for (let i = 0; i <= closestIndex; i++) item.removeSegment(0);
                }
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

        // ── Cut path at point (action 18) ────────────────────────────────────
        cutPathAtPoint(event) {
            const hitResult = this.scope.project.hitTest(event.point, {
                stroke: true, segments: true, tolerance: 15
            });
            if (!hitResult || !hitResult.item || hitResult.item.locked) return;

            const item = hitResult.item;
            if (!(item instanceof paper.Path) || item.segments.length < 2) return;

            const nearestPoint = item.getNearestPoint(event.point);
            const offset = item.getOffsetOf(nearestPoint);

            // Don't cut too close to endpoints
            if (offset < 2 || offset > item.length - 2) return;

            const secondPart = item.splitAt(offset);
            if (secondPart) {
                this.layerCounters.line++;
                secondPart.name        = `line ${this.layerCounters.line}`;
                secondPart.strokeColor = item.strokeColor;
                secondPart.strokeWidth = item.strokeWidth || this._width();
                if (item.parent) item.parent.addChild(secondPart);
            }
            this.scope.view.update();
            this.saveCanvasData();
            this.$emit('layers_updated');
        },

        // ── Move all (action 17) ─────────────────────────────────────────────
        // (drag is handled inline in onMouseDrag; mouseUp sets _moveAllActive=false)

        // ── Resize (action 19) ───────────────────────────────────────────────
        // Single click-drag: click anywhere on a shape and drag to resize.
        // The quadrant of the shape the user clicks determines which corner moves.
        startResizeOrSelect(event) {
            // Always clear previous state on each new click
            if (this._selectedResizeItem) {
                try { this._selectedResizeItem.selected = false; } catch (_) {}
                this._selectedResizeItem = null;
            }
            this._resizeHandle        = null;
            this._resizeStart         = null;
            this._resizeOriginalBounds = null;

            const hitResult = this.scope.project.hitTest(event.point, {
                fill: true, stroke: true, tolerance: 15
            });
            if (!hitResult || !hitResult.item || hitResult.item.locked) return;

            let item = hitResult.item;
            // Bubble up to topmost Group but stop at Layer (same fix as selectItemForMove)
            while (item.parent &&
                   !(item.parent instanceof paper.Layer) &&
                   item.parent instanceof paper.Group &&
                   !item.parent.locked) {
                item = item.parent;
            }
            if (item.locked) return;

            this._selectedResizeItem = item;
            item.selected = true;

            // Pick the corner to move based on which quadrant of the item was clicked.
            // The opposite corner acts as the fixed anchor during drag.
            const b  = item.bounds;
            const cx = (b.left + b.right)  / 2;
            const cy = (b.top  + b.bottom) / 2;
            const pt = event.point;

            if (pt.x <= cx && pt.y <= cy)      this._resizeHandle = 'topLeft';
            else if (pt.x > cx && pt.y <= cy)  this._resizeHandle = 'topRight';
            else if (pt.x <= cx && pt.y > cy)  this._resizeHandle = 'bottomLeft';
            else                               this._resizeHandle = 'bottomRight';

            this._resizeStart          = event.point;
            this._resizeOriginalBounds = item.bounds.clone();
        },

        dragResize(event) {
            if (!this._selectedResizeItem || !this._resizeHandle || !this._resizeOriginalBounds) return;

            let item    = this._selectedResizeItem;
            const orig  = this._resizeOriginalBounds;
            // Cumulative delta from the original mouseDown point
            const delta = event.point.subtract(this._resizeStart);

            let nl = orig.left, nt = orig.top, nr = orig.right, nb = orig.bottom;
            switch (this._resizeHandle) {
                case 'topLeft':     nl = orig.left + delta.x; nt = orig.top + delta.y; break;
                case 'topRight':    nr = orig.right + delta.x; nt = orig.top + delta.y; break;
                case 'bottomLeft':  nl = orig.left + delta.x; nb = orig.bottom + delta.y; break;
                case 'bottomRight': nr = orig.right + delta.x; nb = orig.bottom + delta.y; break;
                case 'topCenter':   nt = orig.top + delta.y; break;
                case 'bottomCenter':nb = orig.bottom + delta.y; break;
                case 'leftCenter':  nl = orig.left + delta.x; break;
                case 'rightCenter': nr = orig.right + delta.x; break;
            }
            if (nr - nl < 5) nr = nl + 5;
            if (nb - nt < 5) nb = nt + 5;

            if (item.data && item.data.isRectangle && item.segments && item.segments.length === 4) {
                // Direct segment update for rectangles — maintains sharp corners
                item.segments[0].point = new paper.Point(nl, nt);
                item.segments[1].point = new paper.Point(nr, nt);
                item.segments[2].point = new paper.Point(nr, nb);
                item.segments[3].point = new paper.Point(nl, nb);
            } else if (item.data && item.data.isCircle) {
                // Recreate circle fitted to new bounds
                const cx = (nl + nr) / 2;
                const cy = (nt + nb) / 2;
                const radius = Math.max(2, Math.min(nr - nl, nb - nt) / 2);
                item = this._recreateCircle(item, new paper.Point(cx, cy), radius);
            } else {
                // Generic: incremental fitBounds (ellipse, polygon, etc.)
                item.fitBounds(new paper.Rectangle(nl, nt, nr - nl, nb - nt));
            }

            this.scope.view.update();
        },

        _recreateCircle(oldItem, center, radius) {
            const parent   = oldItem.parent;
            const newCircle = new paper.Path.Circle({
                center,
                radius,
                strokeColor: oldItem.strokeColor,
                strokeWidth: oldItem.strokeWidth,
                fillColor:   oldItem.fillColor,
                name:        oldItem.name,
            });
            newCircle.data     = oldItem.data ? { ...oldItem.data } : {};
            newCircle.selected = true;
            oldItem.remove();
            if (parent) parent.addChild(newCircle);
            this._selectedResizeItem = newCircle;
            return newCircle;
        },

        // Clear resize selection when switching away from action 19
        clearResizeSelection() {
            if (this._selectedResizeItem) {
                try { this._selectedResizeItem.selected = false; } catch (_) {}
                this._selectedResizeItem  = null;
            }
            this._resizeHandle        = null;
            this._resizeStart         = null;
            this._resizeOriginalBounds = null;
        },

        // ── Continue line (action 20) ────────────────────────────────────────
        startContinueLine(event) {
            const tol = 20 / (this.scope.view.zoom || 1);
            let foundPath = null;
            let fromEnd   = true;
            let minDist   = tol;

            const search = (item) => {
                if (!item || item.locked) return;
                if (item instanceof paper.Path && !item.closed && item.segments.length >= 1) {
                    const dFirst = item.firstSegment.point.getDistance(event.point);
                    const dLast  = item.lastSegment.point.getDistance(event.point);
                    if (dFirst < minDist) { minDist = dFirst; foundPath = item; fromEnd = false; }
                    if (dLast  < minDist) { minDist = dLast;  foundPath = item; fromEnd = true;  }
                }
                if (item.children) item.children.forEach(search);
            };

            const mainLayer = this.scope.project.layers.find(l => l.name === 'main');
            if (mainLayer) [...mainLayer.children].forEach(search);

            if (foundPath) {
                // Reverse so we always append to the end
                if (!fromEnd) foundPath.reverse();
                this.path = foundPath;
                this._continueLineActive = true;
            } else {
                // No nearby endpoint — fall back to a new line
                this.add_line();
                this._continueLineActive = false;
            }
        },

        // ── Crop (action 15) ─────────────────────────────────────────────────
        startCropSelection(event) {
            if (this.path) { this.path.remove(); this.path = null; }
            this.path = new paper.Path.Rectangle(event.point, event.point);
            this.path.strokeColor = '#ff6600';
            this.path.strokeWidth = 2;
            this.path.dashArray   = [8, 4];
            this.path.fillColor   = new paper.Color(1, 0.4, 0, 0.15);
            this.path.data        = { isCrop: true, startPoint: event.point };
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

        finishCrop() {
            if (!this.path || !this.path.data || !this.path.data.isCrop) {
                this.path = null;
                return;
            }
            const bounds = this.path.bounds;
            this.path.remove();
            this.path = null;

            if (bounds.width < 5 || bounds.height < 5) {
                this.scope.view.update();
                return;
            }

            // Capture the cropped area BEFORE zooming (while coordinates are still valid)
            const tl = this.scope.view.projectToView(bounds.topLeft);
            const br = this.scope.view.projectToView(bounds.bottomRight);
            const x  = Math.round(Math.min(tl.x, br.x));
            const y  = Math.round(Math.min(tl.y, br.y));
            const w  = Math.round(Math.abs(br.x - tl.x));
            const h  = Math.round(Math.abs(br.y - tl.y));

            let croppedDataUrl = null;
            if (w > 0 && h > 0) {
                const temp = document.createElement('canvas');
                temp.width  = w;
                temp.height = h;
                temp.getContext('2d').drawImage(this.scope.view.element, x, y, w, h, 0, 0, w, h);
                croppedDataUrl = temp.toDataURL('image/jpeg', 0.92);
            }

            // Zoom canvas to show only the cropped area
            const viewSize = this.scope.view.viewSize;
            const zoomX = viewSize.width  / bounds.width;
            const zoomY = viewSize.height / bounds.height;
            const newZoom = Math.min(zoomX, zoomY);
            this.scope.view.zoom   = Math.max(this.minZoom || 0.1, Math.min(newZoom, 20));
            this.scope.view.center = bounds.center;
            if (this._clampPan) this._clampPan();
            this.scope.view.update();
            this.$emit('zoom-changed', this.scope.view.zoom);

            // Emit the cropped image for the parent to save
            this.$emit('crop-save', croppedDataUrl);

            if (this.$bus) {
                this.$bus.$emit('toast', {
                    type: 'success',
                    title: 'Crop applied',
                    message: 'Canvas zoomed to crop area. Press Save to upload to server.',
                    duration: 4000
                });
            }
        },

        // ── Edit segment points (action 16) ──────────────────────────────────
        selectSegmentPoint(event) {
            this.editingSegment    = null;
            this.editingSegmentDot = null;
            let closestSeg = null;
            let minDist    = 20;

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
                    let closestDot  = null;
                    let minDotDist  = 40;
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

        // ── Related layer helpers ─────────────────────────────────────────────
        _repositionRelatedLayersBelow() {
            if (!this.scope) return;
            const mainLayer = this.scope.project.layers.find(l => l.name === 'main');
            if (!mainLayer) return;
            this.scope.project.layers
                .filter(l => l.name && l.name.startsWith('related-'))
                .sort((a, b) => parseInt(a.name.split('-')[1]) - parseInt(b.name.split('-')[1]))
                .forEach(l => l.insertBelow(mainLayer));
        },

        // ── JSON import ───────────────────────────────────────────────────────
        importJsonData(jsonData) {
            if (!jsonData || !this.scope) return;
            try {
                let parsedData = typeof jsonData === 'string' ? JSON.parse(jsonData) : jsonData;
                this.scope.activate();

                const existingMain = this.scope.project.layers.find(l => l.name === 'main');
                if (existingMain) existingMain.remove();

                const related = this.scope.project.layers.filter(l => l.name && l.name.startsWith('related-'));
                related.forEach(l => l.remove());

                this.scope.project.importJSON(parsedData);

                this.scope.project.layers
                    .filter(l => !l.name || (!l.name.startsWith('related-') && l.name !== 'background'))
                    .forEach(l => { l.name = 'main'; });

                related.forEach(l => this.scope.project.addLayer(l));
                this._repositionRelatedLayersBelow();

                this._activateMainLayer();
                this.scope.view.update();
                this.$emit('layers_ready');
            } catch (e) {}
        },

        importRelatedJsons() {
            if (!this.scope) return;

            this.scope.project.layers.filter(l => l.name && l.name.startsWith('related-')).forEach(l => l.remove());

            if (!this.relatedJsons || !Array.isArray(this.relatedJsons) || this.relatedJsons.length === 0) return;

            const colors = ['#0000ff', '#00cc00', '#ff00ff', '#cccc00', '#00cccc', '#ff8000', '#8000ff', '#00ff80', '#ff0080', '#808080'];

            this.scope.activate();

            this.relatedJsons.forEach((jsonData, index) => {
                if (!jsonData) return;
                try {
                    let parsedData = typeof jsonData === 'string' ? JSON.parse(jsonData) : jsonData;
                    const before = this.scope.project.layers.length;
                    this.scope.project.importJSON(parsedData);
                    const newLayers = this.scope.project.layers.slice(before);
                    const color = colors[index % colors.length];

                    newLayers.forEach(layer => {
                        layer.name   = `related-${index}`;
                        layer.locked = true;

                        const applyColor = (item) => {
                            if (!item || typeof item !== 'object') return;
                            try {
                                if (typeof item.strokeColor !== 'undefined') item.strokeColor = color;
                                if (typeof item.fillColor   !== 'undefined') item.fillColor   = null;
                                item.locked = true;
                                if (item.children && item.children.length) item.children.forEach(applyColor);
                            } catch (_) {}
                        };

                        if (layer.children) layer.children.forEach(applyColor);
                    });

                    this.scope.view.update();
                } catch (e) {}
            });

            this._activateMainLayer();
            this._repositionRelatedLayersBelow();
        }
    }
}
</script>
