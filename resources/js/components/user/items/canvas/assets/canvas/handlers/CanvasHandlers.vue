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
        // Distance-throttles freehand point-adding while smoothLines is on, so a
        // slow/jittery drag doesn't pile up hundreds of near-duplicate segments
        // (the main cause of the "so sensitive" jagged lines and the occasional
        // UI freeze on long strokes). Scaled by zoom so it reads as a constant
        // on-screen distance regardless of how far in/out the view is.
        _shouldAddDragPoint(path, point) {
            if (!this.smoothLines || !path || path.segments.length === 0) return true;
            const zoom = (this.scope && this.scope.view && this.scope.view.zoom) || 1;
            const minDistance = 2 / zoom;
            return path.lastSegment.point.getDistance(point) >= minDistance;
        },

        // Flattens a just-drawn freehand path into a smooth curve-fitted line
        // and drops redundant segments — only when smoothLines is enabled.
        // `simplify(tolerance)`'s tolerance is in PROJECT-space units, same as
        // segment coordinates — on a large background photo viewed zoomed-out
        // (the normal case), a flat tolerance was a fraction of a screen
        // pixel, so the toggle visibly did nothing ("smooth doesn't work").
        // Scale by 1/zoom, same as _shouldAddDragPoint's minDistance above, so
        // it reads as a constant ~1.5 SCREEN-pixel smoothing regardless of
        // zoom or photo resolution. Below paper.js's own default (2.5) —
        // keeps more of the hand-drawn shape/segment count; a higher value
        // cuts corners and reads as "too straight".
        _simplifyIfSmooth(path) {
            if (this.smoothLines && path && path.segments && path.segments.length > 2) {
                const zoom = (this.scope && this.scope.view && this.scope.view.zoom) || 1;
                path.simplify(1.5 / zoom);
            }
        },

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
                } else if (this.action == 21) {
                    this.add_arrow(event);
                } else if (this.action == 22) {
                    this.add_rappel(event);
                } else if (this.action == 23) {
                    this.add_bolt(event);
                } else if (this.action == 24) {
                    this.add_pin(event);
                } else if (this.action == 25) {
                    this.add_pendulum_left(event);
                } else if (this.action == 26) {
                    this.add_pendulum_right(event);
                } else if (this.action == 27) {
                    this.add_crux(event);
                } else if (this.action == 28) {
                    this.add_anchor_good(event);
                } else if (this.action == 29) {
                    this.add_anchor_mid(event);
                } else if (this.action == 30) {
                    this.add_anchor_bad(event);
                } else if (this.action == 31) {
                    this.add_portaledge_anchor_good(event);
                } else if (this.action == 32) {
                    this.add_portaledge_anchor_mid(event);
                } else if (this.action == 33) {
                    this.add_portaledge_anchor_bad(event);
                } else if (this.action == 34) {
                    this.add_rappel_anchor_good(event);
                } else if (this.action == 35) {
                    this.add_rappel_anchor_mid(event);
                } else if (this.action == 36) {
                    this.add_rappel_anchor_bad(event);
                } else if (this.action == 37) {
                    this.add_rescue_anchor(event);
                } else if (this.action == 38) {
                    this.add_summit(event);
                } else if (this.action == 39) {
                    this.add_tent(event);
                } else if (this.action == 40) {
                    this.add_parking(event);
                } else if (this.action == 41) {
                    this.add_poi_hiking(event);
                } else if (this.action == 42) {
                    this.add_poi_bed(event);
                } else if (this.action == 43) {
                    this.add_poi_bike(event);
                } else if (this.action == 44) {
                    this.add_poi_water(event);
                } else if (this.action == 45) {
                    this.add_poi_food(event);
                } else if (this.action == 46) {
                    this.add_poi_tent(event);
                } else if (this.action == 47) {
                    this.add_poi_medical(event);
                }
            };

            this.tool.onMouseDrag = (event) => {
                if (this.action == 1 || this.action == 3) {
                    if (this.path && this._shouldAddDragPoint(this.path, event.point)) this.path.add(event.point);
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
                    if (!this.currentLine && this.path && this.path.data && this.path.data.isCombinedNumber) {
                        this.add_line_for_combined(event.point);
                    }
                    if (this.currentLine && this.path && this.path.data && this.path.data.isCombinedNumber) {
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
                        // Sector-name label (see sectorLocaleImageEditorComponent.vue) —
                        // its leader line is a SEPARATE sibling item (found by flag,
                        // never a live reference stored in `.data`: Paper.js JSON-
                        // serializes `.data` recursively, so a stored Item reference
                        // would get deep-embedded on save and come back as inert data,
                        // not a live Item, after any reload). The LABEL-side endpoint
                        // (the line's last segment) always follows the drag; the
                        // RECTANGLE-side endpoint (segment 0) live-re-anchors to
                        // whichever corner/side-center of the sector's own rectangle
                        // is currently closest to the label — recomputed on every
                        // drag frame, directly from this drag, like any other
                        // connected-item behavior in this editor (e.g. textLabel
                        // above) — NOT from a separate background timer, unless the
                        // user has manually pinned it via the "Edit Points" tool
                        // (anchorAuto: false, see the action-16 handler below).
                        if (this.selectedItem.data && this.selectedItem.data.isSectorLabel) {
                            const layer = this.selectedItem.layer;
                            const line = layer && layer.children.find(c => c.data && c.data.isSectorLabelLine);
                            if (line && line.segments && line.segments.length >= 2) {
                                const last = line.segments[line.segments.length - 1];
                                last.point = last.point.add(event.delta);

                                if (!(line.data && line.data.anchorAuto === false)) {
                                    const rectItem = layer.children.find(c => c.data &&
                                        (c.data.isRectangle || c.data.isCircle || c.data.isEllipse));
                                    if (rectItem) {
                                        const b = rectItem.bounds;
                                        const midX = (b.left + b.right) / 2, midY = (b.top + b.bottom) / 2;
                                        const candidates = [
                                            new paper.Point(b.left, b.top),    new paper.Point(midX, b.top),    new paper.Point(b.right, b.top),
                                            new paper.Point(b.left, midY),                                       new paper.Point(b.right, midY),
                                            new paper.Point(b.left, b.bottom), new paper.Point(midX, b.bottom), new paper.Point(b.right, b.bottom),
                                        ];
                                        const labelCenter = this.selectedItem.position;
                                        let best = candidates[0], bestDist = Infinity;
                                        candidates.forEach(c => {
                                            const d = c.getDistance(labelCenter);
                                            if (d < bestDist) { bestDist = d; best = c; }
                                        });
                                        line.segments[0].point = best;
                                    }
                                }
                            }
                        }
                        // Reverse case: dragging the RECTANGLE/circle/ellipse
                        // itself (not the label) must also drag the leader
                        // line's rectangle-side endpoint (segment 0) along
                        // with it — previously only the label-drag direction
                        // above was handled, so moving the shape visibly left
                        // the line's other end planted at its old spot.
                        if (this.selectedItem.data && (this.selectedItem.data.isRectangle
                            || this.selectedItem.data.isCircle || this.selectedItem.data.isEllipse)) {
                            const layer = this.selectedItem.layer;
                            const line = layer && layer.children.find(c => c.data && c.data.isSectorLabelLine);
                            if (line && line.segments && line.segments.length >= 1) {
                                if (!(line.data && line.data.anchorAuto === false)) {
                                    // Auto mode: recompute the closest corner/side-center
                                    // of the shape's NEW bounds to the (fixed) label
                                    // position — same rule used when the label moves.
                                    const labelGroup = layer.children.find(c => c.data && c.data.isSectorLabel);
                                    const b = this.selectedItem.bounds;
                                    const midX = (b.left + b.right) / 2, midY = (b.top + b.bottom) / 2;
                                    const candidates = [
                                        new paper.Point(b.left, b.top),    new paper.Point(midX, b.top),    new paper.Point(b.right, b.top),
                                        new paper.Point(b.left, midY),                                       new paper.Point(b.right, midY),
                                        new paper.Point(b.left, b.bottom), new paper.Point(midX, b.bottom), new paper.Point(b.right, b.bottom),
                                    ];
                                    const labelCenter = labelGroup ? labelGroup.position : line.segments[line.segments.length - 1].point;
                                    let best = candidates[0], bestDist = Infinity;
                                    candidates.forEach(c => {
                                        const d = c.getDistance(labelCenter);
                                        if (d < bestDist) { bestDist = d; best = c; }
                                    });
                                    line.segments[0].point = best;
                                } else {
                                    // Manual mode: the user pinned this exact point on
                                    // the shape via the Edit Points tool — keep it
                                    // rigidly attached to the shape as it translates.
                                    line.segments[0].point = line.segments[0].point.add(event.delta);
                                }
                            }
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
                    if (this.path && this._shouldAddDragPoint(this.path, event.point)) this.path.add(event.point);
                } else if (this.action == 21) {
                    if (this.path && this.path.data && this.path.data.isArrow) {
                        this.updateArrow(this.path, this.path.data.startPoint, event.point);
                    }
                }
            };

            this.tool.onMouseUp = (event) => {
                if (this.action == 3) {
                    if (this.path) this.add_point(event);
                }
                if (this.action == 7) {
                    this._simplifyIfSmooth(this.currentLine);
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
                        // The rectangle-side endpoint of a sector-name label's
                        // leader line (see sectorLocaleImageEditorComponent.vue)
                        // auto-tracks the closest corner/side-center to the
                        // label by default — manually dragging THAT endpoint
                        // here means the user wants a specific point instead,
                        // so stop auto-repositioning it on future sync ticks.
                        // The label-side endpoint (any other segment index)
                        // is left alone — that one's supposed to keep
                        // following the label, dragged via the Move tool.
                        const seg = this.editingSegment;
                        if (seg.path && seg.path.data && seg.path.data.isSectorLabelLine && seg.index === 0) {
                            seg.path.data = { ...seg.path.data, anchorAuto: false };
                        }
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
                    this._simplifyIfSmooth(this.path);
                    this.path = null;
                    this.saveCanvasData();
                }

                if (this.action == 1 || this.action == 3) {
                    this._simplifyIfSmooth(this.path);
                }

                this.path = null;

                // Placing a topo symbol (rappel/bolt/pin/pendulum/crux, one of
                // the anchor-family markers, or a landmark marker) can change
                // WHICH symbol types are present on canvas — refresh the
                // legend before the auto-save below captures the state.
                if (this.action >= 22 && this.action <= 47) {
                    this.rebuildLegend();
                }

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
            //
            // EXCEPTION: multi-part atomic symbols (Arrow/Rappel/Bolt/Pin/Pendulum/
            // Crux) are one indivisible glyph internally built as a Group of several
            // paths (shaft+head, circle+shaft+chevron, etc.), not an organizational
            // group like the numbered-route feature above — dragging just one of
            // their child paths visibly tears the symbol apart (e.g. a rappel's
            // circle moves off while its shaft/chevron stay put). Bubble up to the
            // parent ONLY when it's one of these atomic types (see
            // DrawingTools._isAtomicMarkerGroup); a generic numbered-route
            // `isLayerGroup` is deliberately left alone so its members keep moving
            // independently as before.
            while (item.parent && this._isAtomicMarkerGroup(item.parent)) {
                item = item.parent;
            }

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
                let item = hitResult.item;
                // Same reasoning as selectItemForMove's atomic-marker bubble-up:
                // erasing one child of a multi-part symbol must erase the whole
                // symbol, not leave the rest of it behind as orphaned pieces.
                while (item.parent && this._isAtomicMarkerGroup(item.parent)) {
                    item = item.parent;
                }
                if (item.data && item.data.textLabel) {
                    item.data.textLabel.remove();
                }
                // Sector-name label — remove its sibling leader line too (found
                // by flag, see the action-8 drag handler above for why not a
                // stored reference), or erasing the label leaves an orphaned
                // line pointing at nothing.
                if (item.data && item.data.isSectorLabel && item.layer) {
                    const line = item.layer.children.find(c => c.data && c.data.isSectorLabelLine);
                    if (line) line.remove();
                }
                item.remove();
                // Erasing a topo symbol may drop the last instance of its type —
                // refresh the legend so that row disappears too.
                this.rebuildLegend();
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

            // Resizing the sector's rectangle/circle/ellipse also moves its
            // corners/side-centers, so the leader line's rectangle-side
            // endpoint (if still auto-tracking) needs to be re-anchored to
            // whichever one is now closest to the label — same rule used
            // when the shape is translated (see the action-8 onMouseDrag
            // handler above) or the label itself is dragged.
            if (item.data && (item.data.isRectangle || item.data.isCircle || item.data.isEllipse)) {
                const layer = item.layer;
                const line = layer && layer.children.find(c => c.data && c.data.isSectorLabelLine);
                if (line && line.segments && line.segments.length >= 1 && !(line.data && line.data.anchorAuto === false)) {
                    const labelGroup = layer.children.find(c => c.data && c.data.isSectorLabel);
                    const b = item.bounds;
                    const midX = (b.left + b.right) / 2, midY = (b.top + b.bottom) / 2;
                    const candidates = [
                        new paper.Point(b.left, b.top),    new paper.Point(midX, b.top),    new paper.Point(b.right, b.top),
                        new paper.Point(b.left, midY),                                       new paper.Point(b.right, midY),
                        new paper.Point(b.left, b.bottom), new paper.Point(midX, b.bottom), new paper.Point(b.right, b.bottom),
                    ];
                    const labelCenter = labelGroup ? labelGroup.position : line.segments[line.segments.length - 1].point;
                    let best = candidates[0], bestDist = Infinity;
                    candidates.forEach(c => {
                        const d = c.getDistance(labelCenter);
                        if (d < bestDist) { bestDist = d; best = c; }
                    });
                    line.segments[0].point = best;
                }
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

            // Same reasoning as CanvasManager.exportCanvas: a selected item's
            // handles are drawn straight onto view.element, so drawImage() below
            // would bake them into the cropped snapshot if we didn't clear first.
            const previouslySelected = this.scope.project.selectedItems.slice();
            this.scope.project.deselectAll();
            this.scope.view.update();

            let croppedDataUrl = null;
            if (w > 0 && h > 0) {
                const temp = document.createElement('canvas');
                temp.width  = w;
                temp.height = h;
                temp.getContext('2d').drawImage(this.scope.view.element, x, y, w, h, 0, 0, w, h);
                croppedDataUrl = temp.toDataURL('image/jpeg', 0.92);
            }

            previouslySelected.forEach(item => { try { item.selected = true; } catch (_) {} });

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

        // Rescales/translates freshly-imported Paper.js layers from the coordinate
        // space they were saved in (meta.bg_*) into the CURRENT background raster's
        // fit (see loadBackgroundRaster/getBackgroundBounds). Without this, strokes
        // saved while the canvas container was one width render mis-scaled/offset
        // once the background is re-fit to a different width (e.g. editing inside a
        // modal vs. the dedicated full-width drawing page).
        _rescaleToCurrentBackground(layers, meta) {
            if (!meta || !layers || !layers.length) return;
            const current = this.getBackgroundBounds ? this.getBackgroundBounds() : null;
            if (!current) return;

            let oldW = meta.bg_width, oldH = meta.bg_height;
            let oldL = meta.bg_left,  oldT = meta.bg_top;
            if (!oldW || !oldH || oldL == null || oldT == null) {
                // Legacy rows saved before bg_* tracking existed only recorded the raw
                // view size — fall back to it, assuming zero offset. This matches every
                // row that DOES have bg_* recorded (bg_left/bg_top are always 0 there),
                // since loadBackgroundRaster locks the view's aspect ratio to the image's,
                // so the background always ends up filling the view edge-to-edge in
                // practice. Same fallback tier CanvasJsonDataShowComponent already uses.
                if (meta.canvas_width && meta.canvas_height) {
                    oldW = meta.canvas_width;
                    oldH = meta.canvas_height;
                    oldL = 0;
                    oldT = 0;
                } else {
                    return;
                }
            }

            const sx = current.width  / oldW;
            const sy = current.height / oldH;
            if (!isFinite(sx) || !isFinite(sy) || sx <= 0 || sy <= 0) return;

            // Already fit the same way at save and load time — skip to avoid float drift.
            if (Math.abs(sx - 1) < 0.001 && Math.abs(sy - 1) < 0.001 &&
                Math.abs(current.left - oldL) < 0.5 && Math.abs(current.top - oldT) < 0.5) {
                return;
            }

            const tx = current.left - oldL * sx;
            const ty = current.top  - oldT * sy;
            const matrix = new paper.Matrix(sx, 0, 0, sy, tx, ty);
            layers.forEach(l => { if (l.name !== 'background') l.transform(matrix); });
        },

        // ── JSON import ───────────────────────────────────────────────────────
        importJsonData(jsonData, meta) {
            if (!jsonData || !this.scope) return;
            try {
                let parsedData = typeof jsonData === 'string' ? JSON.parse(jsonData) : jsonData;
                this.scope.activate();

                const existingMain = this.scope.project.layers.find(l => l.name === 'main');
                if (existingMain) existingMain.remove();

                const beforeCount = this.scope.project.layers.length;
                this.scope.project.importJSON(parsedData);
                const importedLayers = this.scope.project.layers.slice(beforeCount);
                this._rescaleToCurrentBackground(importedLayers, meta !== undefined ? meta : this.jsonMeta);

                this.scope.project.layers
                    .filter(l => !l.name || (!l.name.startsWith('related-') && l.name !== 'background'))
                    .forEach(l => { l.name = 'main'; });

                // Rebuilds related-N FRESH from the CURRENT relatedJsons/
                // relatedJsonsMeta props (already updated to their new value
                // by the time ANY watcher fires, even though the LIVE
                // Paper.js layers below haven't caught up yet) instead of
                // preserving whatever related-N layers happened to exist
                // before this import.
                //
                // BUG this fixed: for a page where switching the active
                // document ALSO changes who counts as "related" (e.g.
                // sectorLocaleImageEditorComponent.vue switching which
                // sector is being edited swaps which siblings show as
                // reference), the OLD code here re-added the STALE related-N
                // layers verbatim — still showing the PREVIOUSLY active
                // sector's own siblings for one frame (e.g. the sector-label
                // reference overlay's box/anchor briefly reflecting the wrong
                // sibling). importRelatedJsons() already removes any existing
                // related-N layers itself before rebuilding, and ends by
                // re-activating 'main' and repositioning them below it, so
                // nothing else here needs to duplicate that.
                this.importRelatedJsons();
                this.scope.view.update();

                // _lastDrawingJson is the jsonProp watcher's echo-guard — it exists to
                // recognize "this incoming value is just an echo of what we ourselves
                // JUST exported" (e.g. a save flow round-tripping getCleanJson() back
                // through jsonProp) so it can skip a redundant reimport that would
                // otherwise apply the rescale correction twice and drift coordinates.
                // But it was only ever updated on EXPORT, never on import — so after
                // switching the active document (e.g. route drawing ↔ extra drawing)
                // and switching back WITHOUT drawing anything new in between, the guard
                // still held the stale value from before the switch, matched the
                // incoming (correct) value by coincidence, and silently skipped the
                // reimport — leaving the OTHER document's content stuck on the main
                // layer. Updating it here too keeps it meaning "what's actually
                // rendered right now", so a genuine switch back always re-renders.
                this._lastDrawingJson = typeof jsonData === 'string' ? jsonData : JSON.stringify(jsonData);

                // Self-heals the legend for drawings saved before this feature existed
                // (adds one if symbols are present but no legend was ever saved), and
                // keeps it in sync when switching between documents (e.g. route ↔
                // extra drawing) that may have different symbols/no legend at all.
                this.rebuildLegend();

                this.$emit('layers_ready');
            } catch (e) {}
        },

        importRelatedJsons() {
            if (!this.scope) return;

            // relatedJsons is a freshly-mapped/filtered array every time its parent
            // recomputes it (e.g. after any save reloads the sibling layouts list),
            // so this rebuild fires far more often than the actual reference content
            // changes. Without remembering which related-N layer the user toggled
            // off via the Layers panel, every such rebuild silently makes it visible
            // again — a hidden sibling drawing "coming back" for no visible reason.
            const previousVisibility = {};
            this.scope.project.layers
                .filter(l => l.name && l.name.startsWith('related-'))
                .forEach(l => { previousVisibility[l.name] = l.visible; });

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
                    // A sibling route's stored JSON may still carry a baked-in
                    // "selected":true from before _getDrawingJson() started
                    // deselecting on export (older saves). importJSON() faithfully
                    // restores that, which Paper.js then renders as a permanent
                    // handle square at every segment of that route's line (reading
                    // as a solid color turning into a dotted look) plus a crosshair
                    // marker — never trust it on a read-only reference overlay.
                    newLayers.forEach(l => { try { l.selected = false; } catch (_) {} });
                    const meta = (this.relatedJsonsMeta && this.relatedJsonsMeta[index]) || null;
                    this._rescaleToCurrentBackground(newLayers, meta);
                    // A sibling sector's own saved JSON now carries its own
                    // sector-name label + leader line (see
                    // sectorLocaleImageEditorComponent.vue's
                    // addOrUpdateSectorLabel) — that's a real, editable item in
                    // ITS OWN 'main' layer, so it comes along for the ride when
                    // that whole JSON is reused here as a read-only reference
                    // overlay for a DIFFERENT sector being edited. It must not
                    // stay as a live item here (it would get recolored/locked
                    // below like every other reference shape, showing as a
                    // stray dimmed box instead of the bright yellow label) —
                    // but its REAL saved position (now already rescaled onto
                    // the current background, same as everything else here) is
                    // captured onto the layer's own `.data` first, so
                    // computeEditorLabels() can draw the reference label at the
                    // position the admin actually placed it at, instead of a
                    // separately recomputed default (the same class of bug
                    // fixed on the public page's extractShapes/drawSectorLabels).
                    newLayers.forEach(layer => {
                        const toRemove = [];
                        const findLabels = (item) => {
                            if (!item) return;
                            if (item.data && item.data.isSectorLabel) {
                                const b = item.bounds;
                                layer.data = { ...layer.data, sectorLabelBox: { x: b.left, y: b.top, w: b.width, h: b.height } };
                                toRemove.push(item);
                                return;
                            }
                            if (item.data && item.data.isSectorLabelLine && item.segments && item.segments.length >= 1) {
                                const p = item.firstSegment.point;
                                layer.data = { ...layer.data, sectorLabelAnchor: { x: p.x, y: p.y } };
                                toRemove.push(item);
                                return;
                            }
                            // A sibling sector/route/mtp-pitch's own saved JSON
                            // may ALSO carry its own auto-generated topo-symbol
                            // legend (see DrawingTools.vue's rebuildLegend) —
                            // that's derived from ITS OWN symbols and has no
                            // business appearing here at all, let alone
                            // recolored/repositioned as if it were a real
                            // reference shape. Left unstripped, every sibling
                            // drawing that has any topo symbols contributes its
                            // OWN legend box on top of this drawing's real one —
                            // exactly the "legend duplicated" bug: one legend
                            // per sibling shown as a phantom, wrongly-tinted
                            // extra box, instead of ONE legend representing only
                            // this drawing's own symbols.
                            if (item.data && item.data.isLegend) {
                                toRemove.push(item);
                                return;
                            }
                            if (item.children) item.children.forEach(findLabels);
                        };
                        if (layer.children) layer.children.forEach(findLabels);
                        toRemove.forEach(item => item.remove());
                    });
                    // relatedFirstLabel reserves index 0 for something that ISN'T
                    // another route/layout — see EditorComponent.vue's updateLayersList,
                    // which uses this exact same color/index scheme so the Layers panel
                    // swatch always matches what's actually drawn on the canvas.
                    const hasSpecialFirst = !!this.relatedFirstLabel;
                    const isSpecialFirst = hasSpecialFirst && index === 0;
                    const routeColorIndex = index - (hasSpecialFirst ? 1 : 0);
                    const color = isSpecialFirst ? '#808080' : colors[routeColorIndex % colors.length];

                    newLayers.forEach(layer => {
                        layer.name    = `related-${index}`;
                        layer.locked  = true;
                        layer.data    = { ...layer.data, isExtraInfo: isSpecialFirst };
                        if (previousVisibility[layer.name] !== undefined) layer.visible = previousVisibility[layer.name];

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
