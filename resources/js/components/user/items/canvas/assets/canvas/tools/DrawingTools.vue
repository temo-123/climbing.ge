<script>
import paper from 'paper';

export default {
    methods: {
        _stroke()    { return this.currentStrokeColor || '#ff0000'; },
        _fill()      { return this.currentFillColor   || null; },
        _width()     { return this.currentStrokeWidth  || 3; },
        _textSize()  { return this.currentTextSize     || 16; },
        _dotSize()   { return this.currentDotSize      || 4; },

        // True for a Group that is one of the "atomic", indivisible multi-part
        // symbols (Arrow/Rappel/Bolt/Pin/Pendulum/Crux) — as opposed to a
        // generic numbered-route `isLayerGroup`, whose members are meant to
        // stay independently selectable/movable/erasable. CanvasHandlers uses
        // this to decide when a hit on one of a symbol's own child paths
        // (e.g. a bolt's diag1, a rappel's circle) should act on the whole
        // symbol instead of just that one child — hitTest naturally returns
        // whichever child path/segment was actually under the cursor, and
        // without this check, moving or erasing "the symbol" only ever
        // touched that one child, visibly tearing the rest of it apart.
        _isAtomicMarkerGroup(item) {
            return !!(item instanceof paper.Group && item.data && (
                item.data.isArrow || item.data.isRappel || item.data.isBolt ||
                item.data.isPin || item.data.isPendulum || item.data.isCrux
            ));
        },

        createTool(scope) {
            scope.activate();
            return new paper.Tool();
        },

        add_point(event) {
            this.layerCounters.point++;
            const dotColor = (this.currentFillColor != null) ? this.currentFillColor : '#ffd700';
            // Radius follows its own reactive dot-size control (see
            // EditorComponent's currentDotSize/handleDotSizeChange), independent
            // of the line stroke width — it used to be hardcoded to 7, then
            // briefly shared the line-width slider before dot size got its own
            // dedicated control.
            const point = new paper.Path.Circle({
                center: event.point,
                radius: this._dotSize(),
                fillColor: dotColor,
                strokeColor: dotColor,
                name: `point ${this.layerCounters.point}`
            });
            if (this.group) this.group.addChild(point);
            return point;
        },

        add_line() {
            this.layerCounters.line++;
            this.path = new paper.Path({
                strokeColor: this._stroke(),
                strokeWidth: this._width(),
                strokeJoin: 'round',
                name: `line ${this.layerCounters.line}`
            });
            if (this.group) this.group.addChild(this.path);
            return this.path;
        },

        createGroup() {
            this.groupCounter++;
            this.group = new paper.Group();
            this.group.name = `group ${this.groupCounter}`;
            // Stable role marker the layers panel uses to identify this as a group
            // regardless of what the user later renames it to (see EditorComponent's
            // _isGroupContainer) — the name alone used to be the only signal, so
            // renaming a group away from the "group " prefix silently broke its own
            // color/width controls.
            this.group.data = { isLayerGroup: true };
            this.scope.project.activeLayer.addChild(this.group);
        },

        add_line_and_point() {},

        add_line_for_combined(mousePoint) {
            this.layerCounters.line++;
            this.currentLine = new paper.Path({
                strokeColor: this._stroke(),
                strokeWidth: this._width(),
                strokeJoin: 'round',
                name: `line ${this.layerCounters.line}`
            });
            // Stable role marker so a group's own width control can find "the
            // route line" (as opposed to the number label) even after a rename —
            // see EditorComponent._getItemWidth's group branch.
            this.currentLine.data = { isRouteLine: true };
            if (this.path && this.path.data && this.path.data.isRectangle) {
                const rectBounds = this.path.bounds;
                const startPoint = this.getClosestPerimeterPoint(rectBounds, mousePoint);
                this.currentLine.add(startPoint);
            }
            if (this.group) this.group.addChild(this.currentLine);
            return this.currentLine;
        },

        getClosestPerimeterPoint(rectBounds, mousePoint) {
            const points = [
                { point: new paper.Point(rectBounds.left, rectBounds.top), direction: new paper.Point(-1, -1) },
                { point: new paper.Point(rectBounds.center.x, rectBounds.top), direction: new paper.Point(0, -1) },
                { point: new paper.Point(rectBounds.right, rectBounds.top), direction: new paper.Point(1, -1) },
                { point: new paper.Point(rectBounds.right, rectBounds.center.y), direction: new paper.Point(1, 0) },
                { point: new paper.Point(rectBounds.right, rectBounds.bottom), direction: new paper.Point(1, 1) },
                { point: new paper.Point(rectBounds.center.x, rectBounds.bottom), direction: new paper.Point(0, 1) },
                { point: new paper.Point(rectBounds.left, rectBounds.bottom), direction: new paper.Point(-1, 1) },
                { point: new paper.Point(rectBounds.left, rectBounds.center.y), direction: new paper.Point(-1, 0) }
            ];

            let closestPoint = points[0];
            let minDistance = mousePoint.getDistance(closestPoint.point);
            points.forEach(point => {
                const distance = mousePoint.getDistance(point.point);
                if (distance < minDistance) { minDistance = distance; closestPoint = point; }
            });

            const offset = 3;
            return new paper.Point(
                closestPoint.point.x + (closestPoint.direction.x * offset),
                closestPoint.point.y + (closestPoint.direction.y * offset)
            );
        },

        add_rectangle(event) {
            this.layerCounters.rectangle++;
            const rect = new paper.Path({
                strokeColor: this._stroke(),
                strokeWidth: this._width(),
                fillColor: this._fill(),
                closed: true,
                name: `rectangle ${this.layerCounters.rectangle}`
            });

            rect.add(event.point);
            rect.add(event.point);
            rect.add(event.point);
            rect.add(event.point);
            rect.data = { isRectangle: true, startPoint: event.point };
            this.path = rect;

            return rect;
        },

        add_combined_number(event) {
            this.layerCounters.rectangle++;
            const center = event.point;
            const text = new paper.PointText({
                point: new paper.Point(center.x, center.y + 6),
                content: this.layerCounters.rectangle.toString(),
                fillColor: this._stroke(),
                fontFamily: 'Arial',
                fontSize: this._textSize(),
                fontWeight: 'bold',
                justification: 'center',
                name: `text ${this.layerCounters.rectangle}`
            });
            text.data = { isRectangle: true, startPoint: center };
            this.path = text;
            if (this.group) this.group.addChild(text);
            return text;
        },

        add_circle(event) {
            this.layerCounters.circle++;
            const circle = new paper.Path.Circle({
                center: event.point,
                radius: 1,
                strokeColor: this._stroke(),
                strokeWidth: this._width(),
                fillColor: this._fill(),
                name: `circle ${this.layerCounters.circle}`
            });
            circle.data = { isCircle: true, center: event.point };
            this.path = circle;
            if (this.group) this.group.addChild(circle);
            return circle;
        },

        add_ellipse(event) {
            this.layerCounters.ellipse++;
            const ellipse = new paper.Path.Ellipse({
                point: event.point,
                size: [1, 1],
                strokeColor: this._stroke(),
                strokeWidth: this._width(),
                fillColor: this._fill(),
                name: `ellipse ${this.layerCounters.ellipse}`
            });
            ellipse.data = { isEllipse: true, startPoint: event.point };
            this.path = ellipse;
            if (this.group) this.group.addChild(ellipse);
            return ellipse;
        },

        // Builds polygon segments in-place (no remove/recreate needed during drag).
        _buildPolygon(path, center, radius, sides) {
            path.removeSegments();
            for (let i = 0; i < sides; i++) {
                const angle = (i / sides) * Math.PI * 2 - Math.PI / 2;
                path.add(new paper.Point(
                    center.x + Math.cos(angle) * radius,
                    center.y + Math.sin(angle) * radius
                ));
            }
        },

        // Polygon starts tiny; drag from center to define radius (6 sides default).
        add_polygon(event) {
            this.layerCounters.polygon++;
            const polygon = new paper.Path({
                closed: true,
                strokeColor: this._stroke(),
                strokeWidth: this._width(),
                fillColor: this._fill(),
                name: `polygon ${this.layerCounters.polygon}`
            });
            polygon.data = { isPolygon: true, center: event.point, sides: 6 };
            this._buildPolygon(polygon, event.point, 5, 6);
            this.path = polygon;
            if (this.group) this.group.addChild(polygon);
            return polygon;
        },

        // Arrow = a Group containing the shaft (a straight 2-point Path) + a filled
        // triangular head. Modeled on add_rectangle's "seed a fixed structure on
        // mousedown, then reposition on drag" pattern (see updateArrow) rather than
        // add_line's freehand point-accumulation, since an arrow always has exactly
        // one start point and one end point.
        add_arrow(event) {
            this.layerCounters.arrow++;
            const n = this.layerCounters.arrow;

            const shaft = new paper.Path({
                strokeColor: this._stroke(),
                strokeWidth: this._width(),
                strokeJoin: 'round',
                name: `arrow-shaft ${n}`
            });
            shaft.add(event.point);
            shaft.add(event.point);
            // Stable role markers so resize/color logic can find these by role
            // rather than by name — same reasoning as isRouteLine/isLayerGroup above.
            shaft.data = { isArrowShaft: true };

            const head = new paper.Path({
                closed: true,
                fillColor: this._stroke(),
                strokeColor: this._stroke(),
                name: `arrow-head ${n}`
            });
            head.add(event.point);
            head.add(event.point);
            head.add(event.point);
            head.data = { isArrowHead: true };

            const arrow = new paper.Group([shaft, head]);
            arrow.name = `arrow ${n}`;
            arrow.data = { isArrow: true, startPoint: event.point };

            if (this.group) this.group.addChild(arrow);
            this.path = arrow;
            return arrow;
        },

        // Repositions the shaft and recomputes the triangular head so the arrow
        // points from startPoint to endPoint. Called on every onMouseDrag frame
        // while the arrow tool is active (see CanvasHandlers.vue), where the head
        // size is derived from the CURRENT default stroke width (_width()). Also
        // reused by resizeArrow() below with an explicit widthOverride so an
        // EXISTING arrow's head can be rescaled to a specific width chosen
        // afterwards (e.g. via the layers panel), rather than the tool's default.
        updateArrow(arrowGroup, startPoint, endPoint, widthOverride) {
            const shaft = arrowGroup.children[0];
            const head  = arrowGroup.children[1];
            if (!shaft || !head) return;

            const strokeW    = widthOverride != null ? widthOverride : this._width();
            const headLength = Math.max(10, strokeW * 4);
            const headAngle  = Math.PI / 7; // ~25.7°, typical arrowhead spread

            const dx = endPoint.x - startPoint.x;
            const dy = endPoint.y - startPoint.y;
            const lineAngle = Math.atan2(dy, dx);
            const dist = startPoint.getDistance(endPoint);

            // Pull the shaft's end back so it doesn't poke through the head's tip.
            const shaftEndDist = Math.max(0, dist - headLength * 0.6);
            const shaftEnd = new paper.Point(
                startPoint.x + Math.cos(lineAngle) * shaftEndDist,
                startPoint.y + Math.sin(lineAngle) * shaftEndDist
            );
            shaft.segments[0].point = startPoint;
            shaft.segments[1].point = shaftEnd;

            const backLeft = new paper.Point(
                endPoint.x - headLength * Math.cos(lineAngle - headAngle),
                endPoint.y - headLength * Math.sin(lineAngle - headAngle)
            );
            const backRight = new paper.Point(
                endPoint.x - headLength * Math.cos(lineAngle + headAngle),
                endPoint.y - headLength * Math.sin(lineAngle + headAngle)
            );
            head.segments[0].point = endPoint;
            head.segments[1].point = backLeft;
            head.segments[2].point = backRight;
        },

        // Rescales an EXISTING arrow's shaft width AND its arrowhead size together,
        // keeping the same start/tip points — used when the width is changed after
        // the arrow was already drawn (e.g. the layers panel's size input). Without
        // this, only the shaft's strokeWidth would change, leaving the arrowhead's
        // triangle geometry at its original (usually much smaller) fixed size, so a
        // thickened shaft visually swallows the now-comparatively-tiny head instead
        // of the whole arrow scaling up together.
        resizeArrow(arrowGroup, width) {
            const shaft = arrowGroup.children[0];
            const head  = arrowGroup.children[1];
            if (!shaft || !head || shaft.segments.length < 2 || head.segments.length < 3) return;

            const startPoint = shaft.segments[0].point;
            const endPoint    = head.segments[0].point; // the true tip — shaft's own end point is pulled back, not the tip
            shaft.strokeWidth = width;
            this.updateArrow(arrowGroup, startPoint, endPoint, width);
        },

        add_text(event) {
            this.layerCounters.text++;
            const text = new paper.PointText({
                point: event.point,
                content: 'Text',
                fillColor: this._stroke(),
                fontFamily: 'Arial',
                fontSize: this._textSize(),
                justification: 'center',
                name: `text ${this.layerCounters.text}`
            });
            if (this.group) this.group.addChild(text);
            return text;
        },

        // Rappel/lower-off marker: a solid circle (the bolt/anchor the user
        // clicks on) with a downward shaft + open chevron below it — the
        // standard topo symbol for a rappel station, distinct from the generic
        // add_arrow above (which points in an arbitrary drag-defined direction
        // with a solid triangular head). There's no drag step: like add_point,
        // it's a single fixed-shape click, sized off the same dot-size control
        // rather than needing its own dedicated size control. Proportions are
        // scaled from a 30px-radius reference design (gemini-svg.svg: circle
        // r=30 at cx=50,cy=40; shaft (50,70)→(50,135); chevron 22,110 → 50,145 →
        // 78,110; stroke-width 14) by k = R/30.
        add_rappel(event) {
            this.layerCounters.rappel++;
            const n = this.layerCounters.rappel;
            // Defaults to the reference design's green rather than the shared
            // stroke color (same reasoning as add_point's gold default above) —
            // a rappel marker reads as its own fixed symbol, not a line the
            // user is actively styling, so it shouldn't inherit whatever red/
            // etc. the stroke picker happens to be set to. Still overridable:
            // an explicitly-chosen fill color takes precedence, and the color
            // can be changed afterward via the layers panel like anything else.
            const color = (this.currentFillColor != null) ? this.currentFillColor : '#22C55E';
            // Same dot-size control add_point uses, read the same way (no extra
            // multiplier) — so the existing size slider resizes this marker the
            // same way it already resizes points, with the same default (4).
            const R = this._dotSize();
            const k = R / 30;
            const p = event.point;

            const circle = new paper.Path.Circle({
                center: p,
                radius: R,
                fillColor: color,
                strokeColor: color,
                strokeWidth: 14 * k,
                name: `rappel-circle ${n}`
            });

            const shaft = new paper.Path({
                strokeColor: color,
                strokeWidth: 14 * k,
                strokeCap: 'round',
                name: `rappel-shaft ${n}`
            });
            shaft.add(new paper.Point(p.x, p.y + 30 * k));
            shaft.add(new paper.Point(p.x, p.y + 95 * k));

            const head = new paper.Path({
                strokeColor: color,
                strokeWidth: 14 * k,
                strokeCap: 'round',
                strokeJoin: 'round',
                name: `rappel-head ${n}`
            });
            head.add(new paper.Point(p.x - 28 * k, p.y + 70 * k));
            head.add(new paper.Point(p.x,          p.y + 105 * k));
            head.add(new paper.Point(p.x + 28 * k, p.y + 70 * k));

            // isRappel (its own flag, not isLayerGroup) — matches add_arrow's
            // precedent: this is one atomic symbol, not a multi-part route the
            // user would ever want to expand and edit piece-by-piece in the
            // layers panel, so it should show as a single "rappel N" row with
            // its own icon, not a generic expandable folder. See
            // EditorComponent's _isRappelContainer + resizeRappel below.
            const rappel = new paper.Group([circle, shaft, head]);
            rappel.name = `rappel ${n}`;
            rappel.data = { isRappel: true };

            if (this.group) this.group.addChild(rappel);
            this.path = rappel;
            return rappel;
        },

        // Rescales an EXISTING rappel marker's circle/shaft/chevron together to
        // a specific width, keeping the circle's current center fixed — same
        // reasoning as resizeArrow above (used by the layers panel's size
        // input). Paper.js can't resize a Path.Circle via bounds directly (see
        // the resize-action note elsewhere in this file), so the circle is
        // replaced rather than mutated; the shaft/chevron are plain paths and
        // just get their segment points reassigned.
        resizeRappel(rappelGroup, width) {
            const circle = rappelGroup.children[0];
            const shaft  = rappelGroup.children[1];
            const head   = rappelGroup.children[2];
            if (!circle || !shaft || !head || head.segments.length < 3) return;

            const center = circle.position;
            const color  = circle.fillColor;
            const k = width / 14;
            const R = k * 30;

            const newCircle = new paper.Path.Circle({
                center: center,
                radius: R,
                fillColor: color,
                strokeColor: color,
                strokeWidth: width,
                name: circle.name
            });
            circle.replaceWith(newCircle);

            shaft.strokeWidth = width;
            shaft.segments[0].point = new paper.Point(center.x, center.y + 30 * k);
            shaft.segments[1].point = new paper.Point(center.x, center.y + 95 * k);

            head.strokeWidth = width;
            head.segments[0].point = new paper.Point(center.x - 28 * k, center.y + 70 * k);
            head.segments[1].point = new paper.Point(center.x,          center.y + 105 * k);
            head.segments[2].point = new paper.Point(center.x + 28 * k, center.y + 70 * k);
        },

        // Shared stroke-width reference for every topo-symbol marker below
        // (bolt/pin/pendulum/crux) — one constant so all four read as the same
        // visual "family" at the same dot-size setting, rather than each having
        // its own ad-hoc thickness. Matches the boldness of add_rappel's 14
        // closely enough to feel related without being so thick it collapses
        // the hollow rings (pin/crux) into solid blobs.
        _markerStroke() { return 12; },

        // Shared default color for every topo-symbol marker below (bolt/pin/
        // pendulum/crux) — green (`#22C55E`), same reasoning as add_rappel:
        // these read as fixed topo symbols rather than a line the user is
        // actively styling, so they shouldn't silently inherit whatever red/
        // etc. the stroke picker happens to be set to. An explicitly-chosen
        // fill color still overrides it, and it can be recolored afterward
        // via the layers panel like anything else.
        _markerColor() {
            return (this.currentFillColor != null) ? this.currentFillColor : '#22C55E';
        },

        // Bolt gets its own (bolder) stroke reference rather than sharing
        // _markerStroke() with Pin/Crux — those two have hollow rings that
        // collapse into solid blobs if their stroke gets too thick relative
        // to their radius, but a bolt is just two straight lines with no such
        // ceiling, so it can afford to read as visibly stronger/bolder.
        _boltStroke() { return 16; },

        // Bolt marker: a simple "X" — two crossing strokes, following the same
        // pattern as add_rappel above (click-to-place, no drag, scaled off the
        // shared dot-size control rather than the line-width control). Uses
        // `strokeCap: 'square'` rather than 'round': a round cap adds a
        // semicircular bulge beyond each arm's actual endpoint, and at a bold
        // stroke width that bulge becomes very visible once the whole symbol
        // is scaled up (each arm's tip reads as a soft round blob rather than
        // a sharp point) — a square cap stays crisp and geometric at any
        // scale instead.
        add_bolt(event) {
            this.layerCounters.bolt++;
            const n = this.layerCounters.bolt;
            const color = this._markerColor();
            const R = this._dotSize();
            const k = R / 30;
            const SW = this._boltStroke();
            const p = event.point;

            const diag1 = new paper.Path({
                strokeColor: color,
                strokeWidth: SW * k,
                strokeCap: 'square',
                name: `bolt-diag1 ${n}`
            });
            diag1.add(new paper.Point(p.x - 30 * k, p.y - 30 * k));
            diag1.add(new paper.Point(p.x + 30 * k, p.y + 30 * k));

            const diag2 = new paper.Path({
                strokeColor: color,
                strokeWidth: SW * k,
                strokeCap: 'square',
                name: `bolt-diag2 ${n}`
            });
            diag2.add(new paper.Point(p.x - 30 * k, p.y + 30 * k));
            diag2.add(new paper.Point(p.x + 30 * k, p.y - 30 * k));

            // isBolt (own flag, not isLayerGroup) — same reasoning as isRappel:
            // one atomic symbol, single non-expandable layers-panel row.
            const bolt = new paper.Group([diag1, diag2]);
            bolt.name = `bolt ${n}`;
            bolt.data = { isBolt: true };

            if (this.group) this.group.addChild(bolt);
            this.path = bolt;
            return bolt;
        },

        // Rescales an EXISTING bolt's two arms together around their shared
        // center (the group's own centroid, which is exactly the click point
        // for a symmetric X) — same reasoning as resizeRappel/resizeArrow.
        resizeBolt(boltGroup, width) {
            const diag1 = boltGroup.children[0];
            const diag2 = boltGroup.children[1];
            if (!diag1 || !diag2 || diag1.segments.length < 2 || diag2.segments.length < 2) return;

            const center = boltGroup.position;
            const k = width / this._boltStroke();

            diag1.strokeWidth = width;
            diag1.segments[0].point = new paper.Point(center.x - 30 * k, center.y - 30 * k);
            diag1.segments[1].point = new paper.Point(center.x + 30 * k, center.y + 30 * k);

            diag2.strokeWidth = width;
            diag2.segments[0].point = new paper.Point(center.x - 30 * k, center.y + 30 * k);
            diag2.segments[1].point = new paper.Point(center.x + 30 * k, center.y - 30 * k);
        },

        // Peg/pin/piton marker: a diagonal shaft (the blade driven into a crack)
        // with a hollow ring (the eye a biner clips into) at its upper end — the
        // standard topo symbol for a fixed piton, reading as a stylized cursive
        // "P". The click point is the shaft's tip (where it's driven into the
        // rock), not the ring, so it lines up with the feature on the topo the
        // way add_rappel's circle lines up with the anchor. The ring's center is
        // placed exactly one radius beyond the shaft's top end, along the
        // shaft's own direction, so the shaft always terminates precisely on
        // the ring's edge (a tangent join) instead of stopping just short of
        // it — a small but very visible gap otherwise, since it scales together
        // with everything else. Same click-to-place, dot-size-driven scaling
        // as add_rappel/add_bolt (k = dotSize / 30).
        add_pin(event) {
            this.layerCounters.pin++;
            const n = this.layerCounters.pin;
            const color = this._markerColor();
            const R = this._dotSize();
            const k = R / 30;
            const SW = this._markerStroke();
            const ringR = 16 * k;
            const tip = event.point;
            const shaftTop = new paper.Point(tip.x + 30 * k, tip.y - 48 * k);

            const shaft = new paper.Path({
                strokeColor: color,
                strokeWidth: SW * k,
                strokeCap: 'round',
                name: `pin-shaft ${n}`
            });
            shaft.add(tip);
            shaft.add(shaftTop);

            const dir = shaftTop.subtract(tip).normalize();
            const ringCenter = shaftTop.add(dir.multiply(ringR));

            const ring = new paper.Path.Circle({
                center: ringCenter,
                radius: ringR,
                strokeColor: color,
                strokeWidth: SW * k,
                fillColor: null,
                name: `pin-ring ${n}`
            });

            const pin = new paper.Group([shaft, ring]);
            pin.name = `pin ${n}`;
            pin.data = { isPin: true };

            if (this.group) this.group.addChild(pin);
            this.path = pin;
            return pin;
        },

        // Rescales an EXISTING pin marker's shaft + ring together, keeping the
        // shaft's tip (the point actually placed against the rock) fixed —
        // same reasoning as resizeRappel. Paper.js can't resize a Path.Circle's
        // bounds directly, so the ring is replaced rather than mutated.
        resizePin(pinGroup, width) {
            const shaft = pinGroup.children[0];
            const ring  = pinGroup.children[1];
            if (!shaft || !ring || shaft.segments.length < 2) return;

            const tip = shaft.segments[0].point;
            const color = ring.strokeColor;
            const k = width / this._markerStroke();
            const ringR = 16 * k;
            const shaftTop = new paper.Point(tip.x + 30 * k, tip.y - 48 * k);

            shaft.strokeWidth = width;
            shaft.segments[1].point = shaftTop;

            const dir = shaftTop.subtract(tip).normalize();
            const ringCenter = shaftTop.add(dir.multiply(ringR));

            const newRing = new paper.Path.Circle({
                center: ringCenter,
                radius: ringR,
                strokeColor: color,
                strokeWidth: width,
                fillColor: null,
                name: ring.name
            });
            ring.replaceWith(newRing);
        },

        // Reference dot radius the pendulum's anchor circle grows FROM when
        // resized (see resizePendulum's damping below) — the same default the
        // shared dot-size control starts at.
        _pendulumDotBaseR() { return 4; },

        // How much of a proportional resize actually reaches the pendulum's
        // anchor dot — 1 would be fully proportional (like every other
        // marker); this is intentionally much less than 1 so enlarging a
        // pendulum grows the swing arc/chevron a lot while the dot only grows
        // a little, instead of blowing up the whole symbol uniformly.
        _pendulumDotGrowth() { return 0.35; },

        // Builds the pendulum's curved "swing" path as an explicit 2-segment
        // Bezier curve rather than a 3-point circular arc (`paper.Path.Arc`) —
        // a 3-point arc gives no control over how far the resulting circle
        // sweeps, and the reference points used previously happened to define
        // a circle the path wrapped ~167° around, producing a tight spiral/
        // hook shape instead of a shallow pendulum swing. A Bezier built from
        // explicit handles is fully controlled instead: it always leaves
        // `start` moving straight down (continuing the shaft's own direction,
        // via `handleOut`) and always arrives at `end` moving exactly
        // horizontally in the swing direction (via `handleIn`), regardless of
        // how far apart the two points are.
        _buildSwingCurve(start, end, dir, pull) {
            const curve = new paper.Path();
            curve.add(new paper.Segment(start, null, new paper.Point(0, pull)));
            curve.add(new paper.Segment(end, new paper.Point(-dir * pull, 0), null));
            return curve;
        },

        // Builds the arrowhead (an open "V", not a filled triangle — a chevron
        // resting on the path rather than a solid marker) at the END of an
        // already-built arc, oriented along the arc's OWN tangent there
        // (via Path#getTangentAt) rather than a hand-guessed direction. This
        // is the same construction add_arrow/updateArrow use for its
        // straight-line arrowhead, just fed the curve's tangent instead of a
        // fixed line angle — reused here because the previous fixed-point
        // chevron wasn't actually aligned with the arc's true end direction
        // and was sized almost as large as the arc itself, which is what
        // made the pendulum's arrow look wrong.
        _arcArrowhead(arc, headLen, headAngle) {
            const tip = arc.lastSegment.point;
            const tangent = arc.getTangentAt(arc.length);
            const lineAngle = Math.atan2(tangent.y, tangent.x);
            const backLeft = tip.subtract(new paper.Point(
                Math.cos(lineAngle - headAngle), Math.sin(lineAngle - headAngle)
            ).multiply(headLen));
            const backRight = tip.subtract(new paper.Point(
                Math.cos(lineAngle + headAngle), Math.sin(lineAngle + headAngle)
            ).multiply(headLen));
            return { tip, backLeft, backRight };
        },

        // Pendulum marker (shared builder for the left/right toolbar variants):
        // a solid anchor dot, a short shaft, then a curved swing arc ending in
        // an open chevron pointing along the arc's own tangent — the topo
        // symbol for a pendulum/tension traverse move. The dot is filled
        // (matching add_rappel's solid anchor point) rather than hollow, since
        // it marks the same kind of fixed hardware point Rappel does. `dir` is
        // -1 for a leftward swing, +1 for rightward — it's the only difference
        // between the two variants, and is stored on the group
        // (`data.direction`) so resizePendulum can rebuild the correct
        // mirrored geometry later.
        _buildPendulum(event, dir) {
            this.layerCounters.pendulum++;
            const n = this.layerCounters.pendulum;
            const color = this._markerColor();
            const R = this._dotSize();
            const k = R / 30;
            const SW = this._markerStroke();
            const p = event.point;

            const circle = new paper.Path.Circle({
                center: p,
                radius: R,
                fillColor: color,
                strokeColor: color,
                strokeWidth: SW * k,
                name: `pendulum-circle ${n}`
            });

            const shaft = new paper.Path({
                strokeColor: color,
                strokeWidth: SW * k,
                strokeCap: 'round',
                name: `pendulum-shaft ${n}`
            });
            shaft.add(new paper.Point(p.x, p.y + R + 2 * k));
            shaft.add(new paper.Point(p.x, p.y + 55 * k));

            const arc = this._buildSwingCurve(
                new paper.Point(p.x, p.y + 55 * k),
                new paper.Point(p.x + dir * 50 * k, p.y + 85 * k),
                dir, 28 * k
            );
            arc.strokeColor = color;
            arc.strokeWidth = SW * k;
            arc.strokeCap = 'round';
            arc.fillColor = null;
            arc.name = `pendulum-arc ${n}`;

            const head = new paper.Path({
                strokeColor: color,
                strokeWidth: SW * k,
                strokeCap: 'round',
                strokeJoin: 'round',
                name: `pendulum-head ${n}`
            });
            const { tip, backLeft, backRight } = this._arcArrowhead(arc, 18 * k, Math.PI / 6);
            head.add(backLeft);
            head.add(tip);
            head.add(backRight);

            const pendulum = new paper.Group([circle, shaft, arc, head]);
            pendulum.name = `pendulum ${n}`;
            pendulum.data = { isPendulum: true, direction: dir };

            if (this.group) this.group.addChild(pendulum);
            this.path = pendulum;
            return pendulum;
        },

        add_pendulum_left(event)  { return this._buildPendulum(event, -1); },
        add_pendulum_right(event) { return this._buildPendulum(event, 1); },

        // Rescales an EXISTING pendulum marker's circle/shaft/arc/chevron,
        // keeping the circle's center fixed — same reasoning as resizeRappel.
        // Reads the swing direction back off `data.direction` so the left/
        // right variants share one resize path. UNLIKE every other marker
        // here, this is deliberately NOT a uniform proportional scale: the
        // shaft/arc/chevron scale fully with `k` (same as always), but the
        // anchor dot's radius is heavily damped (see _pendulumDotGrowth) so
        // it only grows a little while the swing shape grows a lot — matching
        // how a real pendulum-swing topo mark reads (a small fixed anchor
        // point, a bigger expressive swing line), rather than the whole glyph
        // just scaling up like a bigger version of Rappel/Bolt/Pin/Crux. The
        // circle AND the arc both get replaced (Paper.js can't resize a
        // Path.Circle's bounds, and reconstructing the arc via Path.Arc is
        // simpler/more robust than repositioning its interpolated curve
        // segments by hand); the shaft/chevron are plain paths and just get
        // their segment points reassigned.
        resizePendulum(pendulumGroup, width) {
            const circle = pendulumGroup.children[0];
            const shaft  = pendulumGroup.children[1];
            const arc    = pendulumGroup.children[2];
            const head   = pendulumGroup.children[3];
            if (!circle || !shaft || !arc || !head || head.segments.length < 3) return;

            const dir = (pendulumGroup.data && pendulumGroup.data.direction === 1) ? 1 : -1;
            const center = circle.position;
            const color  = circle.fillColor || circle.strokeColor;
            const k = width / this._markerStroke();
            const proportionalR = k * 30;
            const baseR = this._pendulumDotBaseR();
            const R = baseR + (proportionalR - baseR) * this._pendulumDotGrowth();

            const newCircle = new paper.Path.Circle({
                center: center,
                radius: R,
                fillColor: color,
                strokeColor: color,
                strokeWidth: width,
                name: circle.name
            });
            circle.replaceWith(newCircle);

            shaft.strokeWidth = width;
            shaft.segments[0].point = new paper.Point(center.x, center.y + R + 2 * k);
            shaft.segments[1].point = new paper.Point(center.x, center.y + 55 * k);

            const newArc = this._buildSwingCurve(
                new paper.Point(center.x, center.y + 55 * k),
                new paper.Point(center.x + dir * 50 * k, center.y + 85 * k),
                dir, 28 * k
            );
            newArc.strokeColor = color;
            newArc.strokeWidth = width;
            newArc.strokeCap = 'round';
            newArc.fillColor = null;
            newArc.name = arc.name;
            arc.replaceWith(newArc);

            const { tip, backLeft, backRight } = this._arcArrowhead(newArc, 18 * k, Math.PI / 6);

            head.strokeWidth = width;
            head.segments[0].point = backLeft;
            head.segments[1].point = tip;
            head.segments[2].point = backRight;
        },

        // Sequence/crux-pitch marker: a "key" — bow (hollow ring, the part a
        // keyring passes through) + shaft (the blade) + a one-sided comb of
        // teeth near the tip (the bit) — marking the crux move/sequence on a
        // pitch (the "key" to the pitch). The earlier version had two ticks
        // straddling BOTH sides of the shaft, which read as random hatch
        // marks rather than a key; real key teeth all cut into one side of
        // the blade, at different depths, so this draws two teeth of
        // different lengths on the SAME side instead — much closer to an
        // actual key silhouette. Click point is the bow's center, matching
        // add_rappel/add_pendulum's circle-center convention; the shaft
        // starts exactly on the bow's edge (tangent join, same reasoning as
        // add_pin) rather than near its center.
        add_crux(event) {
            this.layerCounters.crux++;
            const n = this.layerCounters.crux;
            const color = this._markerColor();
            const R = this._dotSize();
            const k = R / 30;
            const SW = this._markerStroke();
            const bowR = 16 * k;
            const p = event.point;
            const dir = new paper.Point(1, -1).normalize(); // 45° up-right shaft direction
            const perp = new paper.Point(-dir.y, dir.x); // the one side the teeth cut into

            const bow = new paper.Path.Circle({
                center: p,
                radius: bowR,
                strokeColor: color,
                strokeWidth: SW * k,
                fillColor: null,
                name: `crux-bow ${n}`
            });

            const shaftStart = p.add(dir.multiply(bowR));
            const shaftEnd = p.add(dir.multiply(54 * k));

            const shaft = new paper.Path({
                strokeColor: color,
                strokeWidth: SW * k,
                strokeCap: 'round',
                name: `crux-shaft ${n}`
            });
            shaft.add(shaftStart);
            shaft.add(shaftEnd);

            // Two teeth of different depths, both cutting into the same side
            // of the shaft near the tip — the "bit" of the key.
            const tooth1Base = p.add(dir.multiply(36 * k));
            const tooth2Base = p.add(dir.multiply(46 * k));

            const tooth1 = new paper.Path({
                strokeColor: color,
                strokeWidth: SW * k,
                strokeCap: 'round',
                name: `crux-tooth1 ${n}`
            });
            tooth1.add(tooth1Base);
            tooth1.add(tooth1Base.add(perp.multiply(14 * k)));

            const tooth2 = new paper.Path({
                strokeColor: color,
                strokeWidth: SW * k,
                strokeCap: 'round',
                name: `crux-tooth2 ${n}`
            });
            tooth2.add(tooth2Base);
            tooth2.add(tooth2Base.add(perp.multiply(9 * k)));

            const crux = new paper.Group([bow, shaft, tooth1, tooth2]);
            crux.name = `crux ${n}`;
            crux.data = { isCrux: true };

            if (this.group) this.group.addChild(crux);
            this.path = crux;
            return crux;
        },

        // Rescales an EXISTING crux/key marker's bow/shaft/teeth together,
        // keeping the bow's center fixed — same reasoning as resizeRappel/
        // resizePin. The bow is replaced (Path.Circle bounds can't be resized
        // directly); the shaft/teeth are plain paths and just get their segment
        // points reassigned.
        resizeCrux(cruxGroup, width) {
            const bow    = cruxGroup.children[0];
            const shaft  = cruxGroup.children[1];
            const tooth1 = cruxGroup.children[2];
            const tooth2 = cruxGroup.children[3];
            if (!bow || !shaft || !tooth1 || !tooth2 || shaft.segments.length < 2) return;

            const center = bow.position;
            const color  = bow.strokeColor;
            const k = width / this._markerStroke();
            const bowR = 16 * k;
            const dir = new paper.Point(1, -1).normalize();
            const perp = new paper.Point(-dir.y, dir.x);

            const newBow = new paper.Path.Circle({
                center: center,
                radius: bowR,
                strokeColor: color,
                strokeWidth: width,
                fillColor: null,
                name: bow.name
            });
            bow.replaceWith(newBow);

            shaft.strokeWidth = width;
            shaft.segments[0].point = center.add(dir.multiply(bowR));
            shaft.segments[1].point = center.add(dir.multiply(54 * k));

            const tooth1Base = center.add(dir.multiply(36 * k));
            const tooth2Base = center.add(dir.multiply(46 * k));

            tooth1.strokeWidth = width;
            tooth1.segments[0].point = tooth1Base;
            tooth1.segments[1].point = tooth1Base.add(perp.multiply(14 * k));

            tooth2.strokeWidth = width;
            tooth2.segments[0].point = tooth2Base;
            tooth2.segments[1].point = tooth2Base.add(perp.multiply(9 * k));
        }
    }
}
</script>
