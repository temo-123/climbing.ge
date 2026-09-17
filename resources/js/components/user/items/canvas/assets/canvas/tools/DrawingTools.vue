<script>
import paper from 'paper';
import { TOPO_SYMBOL_TYPES } from './topoSymbolTypes.js';
import { findLegendMeta } from '../../../../../../../services/canvas/legendRenderer.js';
import * as textStyle from './textStyleHelpers.js';

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
                item.data.isPin || item.data.isPendulum || item.data.isCrux ||
                item.data.isAnchorSymbol || item.data.isSummitMarker ||
                item.data.isTentMarker || item.data.isParkingMarker ||
                item.data.isPoiMarker || item.data.isLegend ||
                item.data.isSectorLabel || item.data.isTextGroup
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
            // A dedicated flag, NOT `isRectangle` (fixed September 2026): this
            // is a PointText, and several OTHER consumers of `data.isRectangle`
            // (CanvasHandlers.vue's action-8 sector-label leader-line
            // re-anchoring, action-19 resize, both keyed by `find()`/boolean
            // checks against real shape items) assume any item carrying that
            // flag is an actual rectangle with `.segments`/`.bounds` usable as
            // a real boundary shape. A combined-number label sharing that flag
            // could be mistaken for the sector's own boundary rectangle (wrong
            // leader-line anchor point) or dragged as if it needed to drag the
            // leader line along with it — both real, reachable bugs since the
            // combined-number tool is exposed on every host page, including
            // the sector-local-image editor where sector labels/leader lines
            // exist. `isCombinedNumber` keeps this item's OWN mousedrag logic
            // (CanvasHandlers.vue action 7) working without colliding with the
            // real-rectangle consumers.
            text.data = { isCombinedNumber: true, startPoint: center };
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
                // Bold by default — matches paperJsonRenderer.js's own
                // always-bold fallback (every text item saved before this
                // toggle existed rendered bold in the baked composite
                // regardless of the live editor, which showed normal
                // weight; this makes new text WYSIWYG-consistent instead of
                // perpetuating that mismatch).
                fontWeight: 'bold',
                justification: 'center',
                name: `text ${this.layerCounters.text}`
            });
            if (this.group) this.group.addChild(text);
            return text;
        },

        // Layers-panel per-item text style toggles (Bold/Italic/
        // Strikethrough/Highlight), requested September 2026 as "main
        // functions, not a lot" for the text tool. Logic lives in
        // textStyleHelpers.js — shared with EditorComponent.vue's own
        // changeLayer* handlers (a different component, no mixin in
        // common), so there's exactly one copy of the tricky bits (bare vs.
        // group-promoted text, the reversible italic shear).
        toggleTextBold(item) { textStyle.toggleBold(item); },
        toggleTextItalic(item) { textStyle.toggleItalic(item); },
        toggleTextStrikethrough(item) { return textStyle.toggleStrikethrough(item); },
        toggleTextHighlight(item, color) { return textStyle.toggleHighlight(item, color); },

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
            // The anchor dot's build radius must go through the SAME damping
            // formula resizePendulum() uses (baseR + (proportionalR-baseR) *
            // growth) — building it at the raw, undamped dot-size instead
            // meant the very first resize (even with the item's own
            // unchanged width, e.g. just opening the width control) silently
            // shrank the dot: resizePendulum re-derives R from `width` through
            // the damped formula, which only reproduces the undamped build
            // radius when the dot-size setting happens to equal baseR (4)
            // (fixed September 2026).
            const R0 = this._dotSize();
            const k = R0 / 30;
            const R = this._pendulumDotBaseR() + (R0 - this._pendulumDotBaseR()) * this._pendulumDotGrowth();
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
        },

        // ── Anchor family (hollow-ring quality markers) ──────────────────────
        // Standard topo anchor glyphs: a HOLLOW ring (unlike add_rappel's solid
        // filled dot above) with a quality decoration through the middle —
        // 'good' = horizontal bar, 'mid' = vertical bar ("precarious"), 'bad' =
        // diagonal slash ("poor") — plus an optional accent above/below marking
        // what the anchor is FOR. Four distinct kinds:
        //   'anchor'     — plain ring + quality bar only, no accent. The bare
        //                  "just a circle with a line" anchor sign.
        //   'portaledge' — same ring + quality bar, PLUS a chevron floating
        //                  above it (a hanging/portaledge stance). Visually
        //                  distinct from 'anchor' — a separate tool/glyph, not
        //                  a decoration option on it.
        //   'rappel'     — same ring + quality bar, PLUS a shaft + downward
        //                  chevron below (a dedicated rappel/abseil point),
        //                  same visual language as add_rappel's chevron above,
        //                  just off a hollow ring instead of a solid dot.
        //   'rescue'     — no quality/accent at all — a fixed concentric
        //                  double-ring ("point ONLY for rescue rigging").
        //
        // Default colors are per-kind (not the shared _markerColor() green
        // bolt/pin/pendulum/crux use above) since these families are meant to
        // stay visually distinguishable from each other at a glance: yellow =
        // anchor/portaledge (both read as "general anchor station"), green =
        // rappel anchor, blue = rescue anchor.
        _anchorColor()       { return (this.currentFillColor != null) ? this.currentFillColor : '#eab308'; },
        _rappelAnchorColor() { return (this.currentFillColor != null) ? this.currentFillColor : '#22C55E'; },
        _rescueAnchorColor() { return (this.currentFillColor != null) ? this.currentFillColor : '#2b6cff'; },

        // Builds the anchor's part-Paths (NOT yet grouped) around `center` at
        // radius `R` — shared by add_anchor (fresh placement) and resizeAnchor
        // (existing item resize) so the two can never draw it differently.
        // `kind` is 'anchor' | 'portaledge' | 'rappel' | 'rescue' (see above).
        // `quality` is 'good' | 'mid' | 'bad', ignored for 'rescue'.
        _buildAnchorParts(center, R, color, kind, quality, n) {
            const k  = R / 30;
            const SW = this._markerStroke() * k;
            const parts = [];

            if (kind === 'rescue') {
                parts.push(new paper.Path.Circle({ center, radius: R, strokeColor: color, strokeWidth: SW, fillColor: null, name: `anchor-ring ${n}` }));
                parts.push(new paper.Path.Circle({ center, radius: R * 0.5, strokeColor: color, strokeWidth: SW, fillColor: null, name: `anchor-inner ${n}` }));
                return parts;
            }

            parts.push(new paper.Path.Circle({ center, radius: R, strokeColor: color, strokeWidth: SW, fillColor: null, name: `anchor-ring ${n}` }));

            const deco = new paper.Path({ strokeColor: color, strokeWidth: SW, strokeCap: 'round', name: `anchor-deco ${n}` });
            if (quality === 'good') {
                deco.add(new paper.Point(center.x - R, center.y));
                deco.add(new paper.Point(center.x + R, center.y));
            } else if (quality === 'mid') {
                deco.add(new paper.Point(center.x, center.y - R));
                deco.add(new paper.Point(center.x, center.y + R));
            } else { // 'bad'
                const d = R * Math.SQRT1_2;
                deco.add(new paper.Point(center.x - d, center.y + d));
                deco.add(new paper.Point(center.x + d, center.y - d));
            }
            parts.push(deco);

            if (kind === 'portaledge') {
                // Upward chevron floating clearly ABOVE the ring (not touching
                // it) — "this is a hanging/portaledge stance", distinct from
                // the plain 'anchor' kind below which has no accent at all.
                // Sized and gapped deliberately larger than a first pass at
                // this glyph, which sat close enough to the ring to visually
                // fuse into one egg/teardrop shape instead of reading as two
                // separate marks.
                const chevron = new paper.Path({
                    strokeColor: color, strokeWidth: SW, strokeCap: 'round', strokeJoin: 'round',
                    name: `anchor-chevron ${n}`
                });
                chevron.add(new paper.Point(center.x - R * 0.75, center.y - R * 1.35));
                chevron.add(new paper.Point(center.x,            center.y - R * 2.15));
                chevron.add(new paper.Point(center.x + R * 0.75, center.y - R * 1.35));
                parts.push(chevron);
            } else if (kind === 'rappel') {
                // Shaft + downward chevron below the ring — "this point is for
                // rappelling/abseiling", same visual language as add_rappel's
                // solid-dot version above, just off a hollow ring instead.
                const shaft = new paper.Path({ strokeColor: color, strokeWidth: SW, strokeCap: 'round', name: `anchor-shaft ${n}` });
                shaft.add(new paper.Point(center.x, center.y + R));
                shaft.add(new paper.Point(center.x, center.y + R * 2.6));
                parts.push(shaft);

                const head = new paper.Path({
                    strokeColor: color, strokeWidth: SW, strokeCap: 'round', strokeJoin: 'round',
                    name: `anchor-head ${n}`
                });
                head.add(new paper.Point(center.x - R * 0.55, center.y + R * 2.05));
                head.add(new paper.Point(center.x,             center.y + R * 2.9));
                head.add(new paper.Point(center.x + R * 0.55, center.y + R * 2.05));
                parts.push(head);
            }

            return parts;
        },

        add_anchor(event, kind, quality, color) {
            this.layerCounters.anchor++;
            const n = this.layerCounters.anchor;
            const R = this._dotSize();
            const parts = this._buildAnchorParts(event.point, R, color, kind, quality, n);

            const anchor = new paper.Group(parts);
            anchor.name = `anchor-${kind}${quality ? '-' + quality : ''} ${n}`;
            anchor.data = { isAnchorSymbol: true, anchorKind: kind, anchorQuality: quality || null };

            if (this.group) this.group.addChild(anchor);
            this.path = anchor;
            return anchor;
        },

        add_anchor_good(event)            { return this.add_anchor(event, 'anchor',     'good', this._anchorColor()); },
        add_anchor_mid(event)             { return this.add_anchor(event, 'anchor',     'mid',  this._anchorColor()); },
        add_anchor_bad(event)             { return this.add_anchor(event, 'anchor',     'bad',  this._anchorColor()); },
        add_portaledge_anchor_good(event) { return this.add_anchor(event, 'portaledge', 'good', this._anchorColor()); },
        add_portaledge_anchor_mid(event)  { return this.add_anchor(event, 'portaledge', 'mid',  this._anchorColor()); },
        add_portaledge_anchor_bad(event)  { return this.add_anchor(event, 'portaledge', 'bad',  this._anchorColor()); },
        add_rappel_anchor_good(event)     { return this.add_anchor(event, 'rappel',     'good', this._rappelAnchorColor()); },
        add_rappel_anchor_mid(event)      { return this.add_anchor(event, 'rappel',     'mid',  this._rappelAnchorColor()); },
        add_rappel_anchor_bad(event)      { return this.add_anchor(event, 'rappel',     'bad',  this._rappelAnchorColor()); },
        add_rescue_anchor(event)          { return this.add_anchor(event, 'rescue',     null,   this._rescueAnchorColor()); },

        // Rescales an EXISTING anchor-family marker to a specific stroke width,
        // keeping the ring's current center fixed — same reasoning/pattern as
        // resizeCrux/resizePin above. Rebuilds all parts from scratch via
        // _buildAnchorParts rather than repositioning each part's segments
        // individually, since which parts exist differs by kind (rescue has no
        // deco, only 'anchor'/'rappel' have a chevron/shaft+head).
        resizeAnchor(anchorGroup, width) {
            if (!anchorGroup.data) return;
            const kind    = anchorGroup.data.anchorKind;
            const quality = anchorGroup.data.anchorQuality;
            if (!kind) return;
            const ring = anchorGroup.children[0];
            if (!ring) return;

            const center = ring.position;
            const color  = ring.strokeColor;
            const k = width / this._markerStroke();
            const R = k * 30;
            const n = (anchorGroup.name || '').split(' ').pop();

            [...anchorGroup.children].forEach(child => child.remove());
            this._buildAnchorParts(center, R, color, kind, quality, n).forEach(part => anchorGroup.addChild(part));
        },

        // ── Landmark markers (summit / tent) ─────────────────────────────────
        // Two general map-style landmark glyphs, unrelated to the anchor/
        // topo-symbol families above — a solid "map pin" for a summit/peak
        // location, and a solid tent silhouette for a camp/bivouac site. Both
        // are single, fixed-meaning markers (no quality tiers), click-to-place
        // like every other symbol here, and store their own effective radius
        // on children[0]'s `strokeWidth` even though children[0] has no
        // visible stroke (`strokeColor: null`) — a size-storage trick that
        // lets them reuse the SAME generic children[0]-driven color/width
        // reading _getItemColor/_getItemWidth already do for bolt/pin/
        // pendulum/crux/anchor, without needing their own bespoke read path.
        // Summit is grouped under "Points of Interest" in the toolbar (see
        // ToolbarComponent.vue) — same as the fixed-color POI markers below
        // (_poiColor()), it's just a sign/glyph with one fixed meaning, not
        // an artist-drawn shape, so it must ignore the Fill toggle/color
        // picker entirely instead of picking up currentFillColor whenever
        // Fill happens to be enabled.
        _summitColor() { return '#e53935'; },
        _tentColor()   { return (this.currentFillColor != null) ? this.currentFillColor : '#795548'; },

        // Builds a "map pin" (teardrop) silhouette: a filled circle "head"
        // plus a filled triangular "tail" tapering to `tip` (the click point —
        // the pin's point touches the exact marked location, same convention
        // real map-pin icons use), with a smaller white circle punched near
        // the top to read as the classic pin "eye". The head/tail are drawn
        // with NO stroke and the same solid fill so their shared edge is
        // invisible — a true boolean cutout isn't needed since nothing
        // overlaps the white "hole" except the head it sits on.
        _buildSummitParts(tip, R, color, n) {
            const tailLen = R * 2;
            const head = new paper.Point(tip.x, tip.y - tailLen);
            const shoulderAngle = 55 * Math.PI / 180; // from straight down
            const shoulderLeft  = new paper.Point(head.x - R * Math.sin(shoulderAngle), head.y + R * Math.cos(shoulderAngle));
            const shoulderRight = new paper.Point(head.x + R * Math.sin(shoulderAngle), head.y + R * Math.cos(shoulderAngle));

            // strokeWidth doubles as this item's stored "size" (see resizeSummit) —
            // invisible since strokeColor stays null, but round-trips through
            // exportJSON/importJSON like any other property.
            const headCircle = new paper.Path.Circle({
                center: head, radius: R, fillColor: color, strokeColor: null, strokeWidth: R,
                name: `summit-head ${n}`
            });

            const tail = new paper.Path({ closed: true, fillColor: color, strokeColor: null, name: `summit-tail ${n}` });
            tail.add(shoulderLeft);
            tail.add(tip);
            tail.add(shoulderRight);

            const hole = new paper.Path.Circle({
                center: head, radius: R * 0.45, fillColor: '#ffffff', strokeColor: null,
                name: `summit-hole ${n}`
            });
            hole.data = { isHole: true };

            return [headCircle, tail, hole];
        },

        add_summit(event) {
            this.layerCounters.summit++;
            const n = this.layerCounters.summit;
            // Doubled relative to the shared dot-size control's raw value —
            // a pin built at the bare default (4px) reads as a barely-visible
            // speck; still fully driven by the same control, just scaled up
            // to a legible size for this particular glyph.
            const R = this._dotSize() * 2;
            const summit = new paper.Group(this._buildSummitParts(event.point, R, this._summitColor(), n));
            summit.name = `summit ${n}`;
            summit.data = { isSummitMarker: true };

            if (this.group) this.group.addChild(summit);
            this.path = summit;
            return summit;
        },

        // Rescales an EXISTING summit marker, keeping its tip (the actual
        // marked point) fixed — reads the tip back from the tail path's own
        // middle segment rather than storing it separately, since that
        // segment IS the tip by construction (see _buildSummitParts).
        resizeSummit(summitGroup, width) {
            const head = summitGroup.children[0];
            const tail = summitGroup.children[1];
            if (!head || !tail || tail.segments.length < 2) return;

            const tip   = tail.segments[1].point;
            const color = head.fillColor;
            const R     = width;
            const n     = (summitGroup.name || '').split(' ').pop();

            [...summitGroup.children].forEach(child => child.remove());
            this._buildSummitParts(tip, R, color, n).forEach(part => summitGroup.addChild(part));
        },

        // Builds a filled tent silhouette anchored at `base` (the click
        // point — the tent's ground/base center, same "location is where the
        // shape touches down" convention as the summit pin above): a solid
        // triangular body, a white rounded "doorway" punched into the base
        // (same same-fill-no-stroke technique as the summit's hole), a bold
        // ground/base line, and two short crossed strokes at the peak for the
        // tent-pole tips poking through the apex.
        _buildTentParts(base, R, color, n) {
            const halfW   = R * 1.3;
            const height  = R * 2.2;
            const apex        = new paper.Point(base.x, base.y - height);
            const bottomLeft  = new paper.Point(base.x - halfW, base.y);
            const bottomRight = new paper.Point(base.x + halfW, base.y);

            const body = new paper.Path({ closed: true, fillColor: color, strokeColor: null, name: `tent-body ${n}` });
            body.add(bottomLeft);
            body.add(apex);
            body.add(bottomRight);
            // strokeWidth doubles as this item's stored "size" (see resizeTent),
            // same invisible round-tripping trick as the summit marker above.
            body.strokeWidth = R;

            const door = new paper.Path.Circle({
                center: new paper.Point(base.x, base.y - R * 0.35), radius: R * 0.45,
                fillColor: '#ffffff', strokeColor: null, name: `tent-door ${n}`
            });
            door.data = { isHole: true };

            const baseLine = new paper.Path({
                strokeColor: color, strokeWidth: R * 0.35, strokeCap: 'round', name: `tent-base ${n}`
            });
            baseLine.add(new paper.Point(base.x - halfW * 1.15, base.y));
            baseLine.add(new paper.Point(base.x + halfW * 1.15, base.y));

            // Crossed pole tips poking through the roof's peak.
            const pole1 = new paper.Path({
                strokeColor: color, strokeWidth: R * 0.35, strokeCap: 'round', name: `tent-pole1 ${n}`
            });
            pole1.add(new paper.Point(apex.x - R * 0.35, apex.y - R * 0.55));
            pole1.add(new paper.Point(apex.x + R * 0.35, apex.y + R * 0.15));

            const pole2 = new paper.Path({
                strokeColor: color, strokeWidth: R * 0.35, strokeCap: 'round', name: `tent-pole2 ${n}`
            });
            pole2.add(new paper.Point(apex.x + R * 0.35, apex.y - R * 0.55));
            pole2.add(new paper.Point(apex.x - R * 0.35, apex.y + R * 0.15));

            return [body, door, baseLine, pole1, pole2];
        },

        add_tent(event) {
            this.layerCounters.tent++;
            const n = this.layerCounters.tent;
            const R = this._dotSize() * 2;
            const tent = new paper.Group(this._buildTentParts(event.point, R, this._tentColor(), n));
            tent.name = `tent ${n}`;
            tent.data = { isTentMarker: true };

            if (this.group) this.group.addChild(tent);
            this.path = tent;
            return tent;
        },

        // Rescales an EXISTING tent marker, keeping its base center (the
        // ground point) fixed — read back from the body triangle's own
        // bottom-left/bottom-right segments rather than stored separately.
        resizeTent(tentGroup, width) {
            const body = tentGroup.children[0];
            if (!body || body.segments.length < 3) return;

            const bl = body.segments[0].point;
            const br = body.segments[2].point;
            const base  = new paper.Point((bl.x + br.x) / 2, bl.y);
            const color = body.fillColor;
            const R     = width;
            const n     = (tentGroup.name || '').split(' ').pop();

            [...tentGroup.children].forEach(child => child.remove());
            this._buildTentParts(base, R, color, n).forEach(part => tentGroup.addChild(part));
        },

        // Same fixed-color reasoning as _summitColor() above — Parking also
        // moved into "Points of Interest", a standard blue parking-sign glyph
        // with one fixed meaning, so the Fill toggle must never recolor it.
        _parkingColor() { return '#1565c0'; },

        // Builds a "parking sign" — a filled square body, a thin white inset
        // border stripe, and a bold white "P" letter — same standard blue
        // parking-sign glyph as everywhere else. Centered on `center` (the
        // click point), unlike the summit/tent markers above whose click
        // point is a "touches the ground" tip/base — a flat sign like this is
        // more naturally anchored at its own middle, same convention bolt/
        // crux/anchor already use.
        _buildParkingParts(center, R, color, n) {
            const half = R;
            const body = new paper.Path({
                closed: true, fillColor: color, strokeColor: null, strokeWidth: R,
                name: `parking-body ${n}`
            });
            body.add(new paper.Point(center.x - half, center.y - half));
            body.add(new paper.Point(center.x + half, center.y - half));
            body.add(new paper.Point(center.x + half, center.y + half));
            body.add(new paper.Point(center.x - half, center.y + half));

            const innerHalf = half * 0.8;
            const innerBorder = new paper.Path({
                closed: true, fillColor: null, strokeColor: '#ffffff', strokeWidth: Math.max(1, R * 0.1),
                name: `parking-inner-border ${n}`
            });
            innerBorder.add(new paper.Point(center.x - innerHalf, center.y - innerHalf));
            innerBorder.add(new paper.Point(center.x + innerHalf, center.y - innerHalf));
            innerBorder.add(new paper.Point(center.x + innerHalf, center.y + innerHalf));
            innerBorder.add(new paper.Point(center.x - innerHalf, center.y + innerHalf));
            innerBorder.data = { isHole: true };

            // PointText's `point` is the text BASELINE, not its vertical
            // center — a capital letter's cap-height sits entirely above the
            // baseline, so centering it on `center.y` requires dropping the
            // baseline down by roughly half the cap-height (~0.36 × fontSize
            // for Arial Bold's "P"), not just nudging it a little.
            const letter = new paper.PointText({
                point: new paper.Point(center.x, center.y + R * 0.58),
                content: 'P',
                fillColor: '#ffffff',
                fontFamily: 'Arial',
                fontWeight: 'bold',
                fontSize: R * 1.6,
                justification: 'center',
                name: `parking-letter ${n}`
            });
            letter.data = { isHole: true };

            return [body, innerBorder, letter];
        },

        add_parking(event) {
            this.layerCounters.parking++;
            const n = this.layerCounters.parking;
            const R = this._dotSize() * 2;
            const parking = new paper.Group(this._buildParkingParts(event.point, R, this._parkingColor(), n));
            parking.name = `parking ${n}`;
            parking.data = { isParkingMarker: true };

            if (this.group) this.group.addChild(parking);
            this.path = parking;
            return parking;
        },

        // Rescales an EXISTING parking sign, keeping its center fixed — read
        // back from the body square's own corner segments (their average IS
        // the center, since the body is always an axis-aligned square).
        resizeParking(parkingGroup, width) {
            const body = parkingGroup.children[0];
            if (!body || body.segments.length < 4) return;

            const pts    = body.segments.map(s => s.point);
            const center = new paper.Point((pts[0].x + pts[2].x) / 2, (pts[0].y + pts[2].y) / 2);
            const color  = body.fillColor;
            const R      = width;
            const n      = (parkingGroup.name || '').split(' ').pop();

            [...parkingGroup.children].forEach(child => child.remove());
            this._buildParkingParts(center, R, color, n).forEach(part => parkingGroup.addChild(part));
        },

        // ── Points-of-interest pins (hiking/bed/bike/water/food/camp/medical) ──
        // A shared "map pin" shell (same teardrop shape as add_summit above,
        // built independently rather than sharing code with it so summit stays
        // untouched) with a small white circle inset, holding a hand-drawn
        // black pictogram — matches the standard "red pin + white circle +
        // black icon" POI marker convention. All hand-vectored with Path
        // primitives (no icon fonts): a PointText using the FontAwesome font
        // would render correctly in the live editor but NOT in the baked
        // composite image, since paperJsonRenderer.js's drawItem() hardcodes
        // `font: bold ${fs}px Arial` for every PointText regardless of what
        // font the original was authored with — silently dropping the icon
        // from the actual saved topo photo. Hand-drawn Paths render
        // identically in both places, like every other symbol in this file.
        // Always the fixed default red — unlike every other topo symbol, a
        // POI pin is a plain informational sign (parking/water/food/etc, not
        // a climbing-relevant quality marker), so it never takes the
        // toolbar's currentFillColor the way other markers do. See
        // EditorComponent.vue's _setItemColor, which also refuses to
        // recolor an existing POI item afterward for the same reason.
        _poiColor() { return '#c62828'; },

        // Vue's `methods` option binds every entry as a function — a plain
        // object literal here would silently become a no-op, so this map is
        // returned from a method rather than stored as one.
        _poiIconMethodName(kind) {
            return {
                hiking:  '_drawPoiHikingIcon',
                bed:     '_drawPoiBedIcon',
                bike:    '_drawPoiBikeIcon',
                water:   '_drawPoiWaterIcon',
                food:    '_drawPoiFoodIcon',
                tent:    '_drawPoiTentIcon',
                medical: '_drawPoiMedicalIcon',
            }[kind];
        },

        // Redesigned again — the straight-limb stick figure read as flat/
        // lifeless. Bending the front leg at the knee (an actual walking
        // stride, not a straight splayed line) and adding a small backpack
        // bump gives it real hiking-pictogram silhouette instead of a bare
        // wireframe; limbs are also bolder (thicker relative to the icon) so
        // it reads as a solid figure, not wiry scribbles.
        _drawPoiHikingIcon(c, r, color, n) {
            const SW = r * 0.17;
            const head = new paper.Path.Circle({
                center: new paper.Point(c.x - r * 0.08, c.y - r * 0.62), radius: r * 0.15,
                fillColor: color, strokeColor: null, name: `poi-hike-head ${n}`
            });
            const shoulder = new paper.Point(c.x - r * 0.08, c.y - r * 0.4);
            const hip      = new paper.Point(c.x + r * 0.1, c.y + r * 0.02);

            // Small backpack bump on the back, behind the torso/head.
            const pack = new paper.Path({ closed: true, fillColor: color, strokeColor: null, name: `poi-hike-pack ${n}` });
            pack.add(new paper.Point(c.x - r * 0.3, c.y - r * 0.34));
            pack.add(new paper.Point(c.x - r * 0.13, c.y - r * 0.4));
            pack.add(new paper.Point(c.x - r * 0.15, c.y - r * 0.1));
            pack.add(new paper.Point(c.x - r * 0.32, c.y - r * 0.08));

            const torso = new paper.Path({ strokeColor: color, strokeWidth: SW, strokeCap: 'round', name: `poi-hike-torso ${n}` });
            torso.add(shoulder); torso.add(hip);

            // Front leg bends at the knee — a walking stride, not a rigid line.
            const knee      = new paper.Point(c.x + r * 0.3, c.y + r * 0.2);
            const footFront = new paper.Point(c.x + r * 0.2, c.y + r * 0.58);
            const legFrontUpper = new paper.Path({ strokeColor: color, strokeWidth: SW, strokeCap: 'round', name: `poi-hike-legfu ${n}` });
            legFrontUpper.add(hip); legFrontUpper.add(knee);
            const legFrontLower = new paper.Path({ strokeColor: color, strokeWidth: SW, strokeCap: 'round', name: `poi-hike-legfl ${n}` });
            legFrontLower.add(knee); legFrontLower.add(footFront);

            const legBack = new paper.Path({ strokeColor: color, strokeWidth: SW, strokeCap: 'round', name: `poi-hike-legback ${n}` });
            legBack.add(hip); legBack.add(new paper.Point(c.x - r * 0.36, c.y + r * 0.52));

            const armTip = new paper.Point(c.x + r * 0.4, c.y - r * 0.1);
            const arm = new paper.Path({ strokeColor: color, strokeWidth: SW, strokeCap: 'round', name: `poi-hike-arm ${n}` });
            arm.add(shoulder); arm.add(armTip);

            // Pole continues the arm at the SAME stroke width (a thinner
            // second segment used to look like a snapped/discontinuous line).
            const pole = new paper.Path({ strokeColor: color, strokeWidth: SW, strokeCap: 'round', name: `poi-hike-pole ${n}` });
            pole.add(armTip); pole.add(new paper.Point(c.x + r * 0.6, c.y + r * 0.58));

            return [head, pack, torso, legFrontUpper, legFrontLower, legBack, arm, pole];
        },

        // Simplified to match the standard "hotel pin" convention (a bold
        // letter in the circle, same idea as add_parking's "P") after the
        // hand-drawn bed silhouette kept reading as generic furniture no
        // matter how much detail was added — a single bold "H" is
        // unambiguous at any size.
        _drawPoiBedIcon(c, r, color, n) {
            const letter = new paper.PointText({
                point: new paper.Point(c.x, c.y + r * 0.58),
                content: 'H',
                fillColor: color,
                fontFamily: 'Arial',
                fontWeight: 'bold',
                fontSize: r * 1.6,
                justification: 'center',
                name: `poi-bed-letter ${n}`
            });
            return [letter];
        },

        // Redesigned — the wheels used to sit close enough together (and the
        // frame low enough) that the whole thing read as one overlapping
        // pretzel shape instead of two wheels with a frame between them.
        // Wheels are now spaced with a clear gap, and every frame vertex
        // (seat/handlebar) sits well ABOVE the wheel tops instead of down
        // inside them.
        // Added wheel hubs plus small seat/handlebar caps — bare frame lines
        // floating between two plain rings read as an abstract diamond, not
        // specifically a BICYCLE; the hubs and grip marks are what make it
        // unmistakably a bike silhouette.
        _drawPoiBikeIcon(c, r, color, n) {
            const wheelR = r * 0.28, wheelSW = r * 0.14, frameSW = r * 0.12;
            const rearC  = new paper.Point(c.x - r * 0.4, c.y + r * 0.32);
            const frontC = new paper.Point(c.x + r * 0.4, c.y + r * 0.32);
            const rearWheel  = new paper.Path.Circle({ center: rearC,  radius: wheelR, strokeColor: color, strokeWidth: wheelSW, fillColor: null, name: `poi-bike-rear ${n}` });
            const frontWheel = new paper.Path.Circle({ center: frontC, radius: wheelR, strokeColor: color, strokeWidth: wheelSW, fillColor: null, name: `poi-bike-front ${n}` });
            const rearHub  = new paper.Path.Circle({ center: rearC,  radius: r * 0.055, fillColor: color, strokeColor: null, name: `poi-bike-rearhub ${n}` });
            const frontHub = new paper.Path.Circle({ center: frontC, radius: r * 0.055, fillColor: color, strokeColor: null, name: `poi-bike-fronthub ${n}` });

            const pedalPt = new paper.Point(c.x, c.y + r * 0.05);
            const seatPt  = new paper.Point(c.x - r * 0.15, c.y - r * 0.38);
            const barPt   = new paper.Point(c.x + r * 0.3, c.y - r * 0.28);

            const seg = (a, b, name, sw) => {
                const p = new paper.Path({ strokeColor: color, strokeWidth: sw || frameSW, strokeCap: 'round', name });
                p.add(a); p.add(b);
                return p;
            };

            // Seat: a short cap sitting atop the seat tube.
            const seat = seg(
                new paper.Point(seatPt.x - r * 0.12, seatPt.y - r * 0.02),
                new paper.Point(seatPt.x + r * 0.1, seatPt.y - r * 0.02),
                `poi-bike-seat ${n}`, frameSW * 1.2
            );
            // Handlebar: a short angled cap at the top of the fork.
            const handlebar = seg(
                new paper.Point(barPt.x - r * 0.14, barPt.y + r * 0.02),
                new paper.Point(barPt.x + r * 0.04, barPt.y - r * 0.1),
                `poi-bike-bar ${n}`, frameSW * 1.1
            );

            return [
                rearWheel, frontWheel, rearHub, frontHub,
                seg(rearC, seatPt, `poi-bike-seatstay ${n}`),
                seg(seatPt, pedalPt, `poi-bike-seattube ${n}`),
                seg(pedalPt, rearC, `poi-bike-chainstay ${n}`),
                seg(pedalPt, frontC, `poi-bike-downtube ${n}`),
                seg(seatPt, barPt, `poi-bike-toptube ${n}`),
                seg(barPt, frontC, `poi-bike-fork ${n}`),
                seat, handlebar,
            ];
        },

        // Classic "raindrop" icon per reference: a big droplet — pointed at
        // the TOP, rounded at the bottom (the mirror image of the outer pin
        // shell's own round-top/pointed-bottom teardrop, so the two can't be
        // confused) — with a smaller droplet of the same shape punched out
        // in white near the bottom, giving it the familiar layered-highlight
        // look instead of a flat silhouette.
        _drawPoiWaterIcon(c, r, color, n) {
            const angle = 50 * Math.PI / 180; // from straight up

            const buildDrop = (bottomCenter, radius, fill, name, hole) => {
                // Tip sits 2x the radius above the circle's center — same
                // proportions as the outer pin shell's own teardrop, just
                // flipped (tip up instead of down).
                const top = new paper.Point(bottomCenter.x, bottomCenter.y - radius * 2);
                // Shoulders sit on the circle, `angle` from straight up.
                const shoulderL = new paper.Point(bottomCenter.x - radius * Math.sin(angle), bottomCenter.y - radius * Math.cos(angle));
                const shoulderR = new paper.Point(bottomCenter.x + radius * Math.sin(angle), bottomCenter.y - radius * Math.cos(angle));

                const circle = new paper.Path.Circle({ center: bottomCenter, radius, fillColor: fill, strokeColor: null, name: `${name}-circle` });
                const wedge = new paper.Path({ closed: true, fillColor: fill, strokeColor: null, name: `${name}-wedge` });
                wedge.add(shoulderL); wedge.add(top); wedge.add(shoulderR);
                if (hole) { circle.data = { isHole: true }; wedge.data = { isHole: true }; }
                return [circle, wedge];
            };

            const outer = buildDrop(new paper.Point(c.x, c.y + r * 0.15), r * 0.42, color, `poi-water-outer ${n}`, false);
            const inner = buildDrop(new paper.Point(c.x, c.y + r * 0.3), r * 0.15, '#ffffff', `poi-water-inner ${n}`, true);

            return [...outer, ...inner];
        },

        // Redesigned — 3 thin, closely-spaced tines rendered as an
        // unreadable blur at small sizes. Down to 2 bolder tines joined by a
        // crossbar (reading clearly as a fork head) and a wider, bolder
        // blade.
        _drawPoiFoodIcon(c, r, color, n) {
            const SW = r * 0.15;
            const forkX = c.x - r * 0.25;
            const forkHandle = new paper.Path({ strokeColor: color, strokeWidth: SW, strokeCap: 'round', name: `poi-food-forkhandle ${n}` });
            forkHandle.add(new paper.Point(forkX, c.y - r * 0.05));
            forkHandle.add(new paper.Point(forkX, c.y + r * 0.58));

            const tineSW = r * 0.12;
            const tines = [-r * 0.12, r * 0.12].map((dx, i) => {
                const tine = new paper.Path({ strokeColor: color, strokeWidth: tineSW, strokeCap: 'round', name: `poi-food-tine${i} ${n}` });
                tine.add(new paper.Point(forkX + dx, c.y - r * 0.58));
                tine.add(new paper.Point(forkX + dx, c.y - r * 0.05));
                return tine;
            });
            const tineBridge = new paper.Path({ strokeColor: color, strokeWidth: tineSW, strokeCap: 'round', name: `poi-food-bridge ${n}` });
            tineBridge.add(new paper.Point(forkX - r * 0.12, c.y - r * 0.05));
            tineBridge.add(new paper.Point(forkX + r * 0.12, c.y - r * 0.05));

            const knifeX = c.x + r * 0.28;
            const knifeHandle = new paper.Path({ strokeColor: color, strokeWidth: SW, strokeCap: 'round', name: `poi-food-knifehandle ${n}` });
            knifeHandle.add(new paper.Point(knifeX, c.y + r * 0.1));
            knifeHandle.add(new paper.Point(knifeX, c.y + r * 0.58));
            const blade = new paper.Path({ closed: true, fillColor: color, strokeColor: null, name: `poi-food-blade ${n}` });
            blade.add(new paper.Point(knifeX - r * 0.05, c.y + r * 0.1));
            blade.add(new paper.Point(knifeX - r * 0.2, c.y - r * 0.58));
            blade.add(new paper.Point(knifeX + r * 0.08, c.y - r * 0.58));
            blade.add(new paper.Point(knifeX + r * 0.05, c.y + r * 0.1));

            return [forkHandle, ...tines, tineBridge, knifeHandle, blade];
        },

        // Plain outline triangle (unlike add_tent's solid filled tent above) —
        // this is the minimalist POI-pin variant for a camp site, distinct
        // from the standalone filled tent marker (both stay available; see
        // the "don't delete existing, just add new" instruction this and the
        // whole POI-pin family were built under). A bare triangle alone reads
        // as a hazard/warning sign, so a ground line extending past both
        // base corners is added — warning triangles never have that — to
        // read unambiguously as a tent pitched on the ground.
        _drawPoiTentIcon(c, r, color, n) {
            const SW = r * 0.16;
            const apex = new paper.Point(c.x, c.y - r * 0.55);
            const bl   = new paper.Point(c.x - r * 0.55, c.y + r * 0.5);
            const br   = new paper.Point(c.x + r * 0.55, c.y + r * 0.5);
            const tri = new paper.Path({ closed: true, strokeColor: color, strokeWidth: SW, strokeJoin: 'round', fillColor: null, name: `poi-tent-tri ${n}` });
            tri.add(bl); tri.add(apex); tri.add(br);
            const doorway = new paper.Path({ strokeColor: color, strokeWidth: SW * 0.8, strokeCap: 'round', name: `poi-tent-door ${n}` });
            doorway.add(new paper.Point(c.x, c.y + r * 0.5));
            doorway.add(new paper.Point(c.x, c.y - r * 0.05));
            const ground = new paper.Path({ strokeColor: color, strokeWidth: SW * 0.8, strokeCap: 'round', name: `poi-tent-ground ${n}` });
            ground.add(new paper.Point(c.x - r * 0.68, c.y + r * 0.5));
            ground.add(new paper.Point(c.x + r * 0.68, c.y + r * 0.5));
            return [tri, doorway, ground];
        },

        _drawPoiMedicalIcon(c, r, color, n) {
            const armLen = r * 0.5, armThick = r * 0.32;
            const vBar = new paper.Path({ closed: true, fillColor: color, strokeColor: null, name: `poi-medical-vbar ${n}` });
            vBar.add(new paper.Point(c.x - armThick / 2, c.y - armLen));
            vBar.add(new paper.Point(c.x + armThick / 2, c.y - armLen));
            vBar.add(new paper.Point(c.x + armThick / 2, c.y + armLen));
            vBar.add(new paper.Point(c.x - armThick / 2, c.y + armLen));

            const hBar = new paper.Path({ closed: true, fillColor: color, strokeColor: null, name: `poi-medical-hbar ${n}` });
            hBar.add(new paper.Point(c.x - armLen, c.y - armThick / 2));
            hBar.add(new paper.Point(c.x + armLen, c.y - armThick / 2));
            hBar.add(new paper.Point(c.x + armLen, c.y + armThick / 2));
            hBar.add(new paper.Point(c.x - armLen, c.y + armThick / 2));

            return [vBar, hBar];
        },

        // Shared shell for every POI pin: the same teardrop shape as
        // add_summit (headCircle + tail, both solid `color`, no stroke so
        // their shared edge is invisible), plus a white icon-background disc,
        // plus whichever hand-drawn pictogram matches `kind`. The white disc
        // AND every icon part are tagged `data.isHole` so a marker-color
        // change (layers panel) only ever recolors the pin body, never the
        // fixed white-background-plus-black-icon look.
        _buildPoiParts(tip, R, color, kind, n) {
            const tailLen = R * 2;
            const head = new paper.Point(tip.x, tip.y - tailLen);
            const shoulderAngle = 55 * Math.PI / 180;
            const shoulderLeft  = new paper.Point(head.x - R * Math.sin(shoulderAngle), head.y + R * Math.cos(shoulderAngle));
            const shoulderRight = new paper.Point(head.x + R * Math.sin(shoulderAngle), head.y + R * Math.cos(shoulderAngle));

            const headCircle = new paper.Path.Circle({
                center: head, radius: R, fillColor: color, strokeColor: null, strokeWidth: R,
                name: `poi-head ${n}`
            });
            const tail = new paper.Path({ closed: true, fillColor: color, strokeColor: null, name: `poi-tail ${n}` });
            tail.add(shoulderLeft); tail.add(tip); tail.add(shoulderRight);

            const iconBg = new paper.Path.Circle({
                center: head, radius: R * 0.62, fillColor: '#ffffff', strokeColor: null, name: `poi-iconbg ${n}`
            });
            iconBg.data = { isHole: true };

            const methodName = this._poiIconMethodName(kind);
            const iconParts = methodName && this[methodName] ? this[methodName](head, R * 0.62, '#1a1a1a', n) : [];
            iconParts.forEach(part => { part.data = { isHole: true }; });

            return [headCircle, tail, iconBg, ...iconParts];
        },

        add_poi(event, kind) {
            this.layerCounters.poi++;
            const n = this.layerCounters.poi;
            const R = this._dotSize() * 2;
            const poi = new paper.Group(this._buildPoiParts(event.point, R, this._poiColor(), kind, n));
            poi.name = `poi-${kind} ${n}`;
            poi.data = { isPoiMarker: true, poiKind: kind };

            if (this.group) this.group.addChild(poi);
            this.path = poi;
            return poi;
        },

        add_poi_hiking(event)  { return this.add_poi(event, 'hiking'); },
        add_poi_bed(event)     { return this.add_poi(event, 'bed'); },
        add_poi_bike(event)    { return this.add_poi(event, 'bike'); },
        add_poi_water(event)   { return this.add_poi(event, 'water'); },
        add_poi_food(event)    { return this.add_poi(event, 'food'); },
        add_poi_tent(event)    { return this.add_poi(event, 'tent'); },
        add_poi_medical(event) { return this.add_poi(event, 'medical'); },

        // Rescales an EXISTING POI pin, keeping its tip fixed — same pattern
        // as resizeSummit above (the tail's own middle segment IS the tip).
        resizePoi(poiGroup, width) {
            if (!poiGroup.data) return;
            const kind = poiGroup.data.poiKind;
            if (!kind) return;
            const head = poiGroup.children[0];
            const tail = poiGroup.children[1];
            if (!head || !tail || tail.segments.length < 2) return;

            const tip   = tail.segments[1].point;
            const color = head.fillColor;
            const R     = width;
            const n     = (poiGroup.name || '').split(' ').pop();

            [...poiGroup.children].forEach(child => child.remove());
            this._buildPoiParts(tip, R, color, kind, n).forEach(part => poiGroup.addChild(part));
        },

        // Rescales an EXISTING sector name-label's font size, keeping the
        // label's CENTER fixed (same convention as
        // sectorLocaleImageEditorComponent.vue's _resizeSectorLabelBg, which
        // this mirrors) and re-gluing the leader line's label-side endpoint to
        // the box's new bottom-center — this is what was MISSING before: the
        // "size" control in the Layers panel had no branch at all for a
        // sector label, so it silently did nothing (a Group has no
        // strokeWidth of its own to fall back to).
        resizeSectorLabel(labelGroup, width) {
            if (!labelGroup || !labelGroup.children) return;
            const text = labelGroup.children.find(c => c instanceof paper.PointText);
            const bg   = labelGroup.children.find(c => c !== text);
            if (!text || !bg || bg.segments.length < 4) return;

            const fontSize = Math.max(6, parseInt(width) || text.fontSize);
            const center = bg.bounds.center;
            text.fontSize = fontSize;

            const padX = 10, padY = 6;
            const boxW = text.bounds.width + padX * 2;
            const boxH = fontSize + padY * 2;

            bg.segments[0].point = new paper.Point(center.x - boxW / 2, center.y - boxH / 2);
            bg.segments[1].point = new paper.Point(center.x + boxW / 2, center.y - boxH / 2);
            bg.segments[2].point = new paper.Point(center.x + boxW / 2, center.y + boxH / 2);
            bg.segments[3].point = new paper.Point(center.x - boxW / 2, center.y + boxH / 2);
            text.point = new paper.Point(center.x, center.y + fontSize * 0.35);

            const layer = labelGroup.layer;
            const line = layer && layer.children.find(c => c.data && c.data.isSectorLabelLine);
            if (line && line.segments && line.segments.length >= 2) {
                line.segments[line.segments.length - 1].point = new paper.Point(center.x, center.y + boxH / 2);
            }
        },

        // ── Topo-symbol legend ───────────────────────────────────────────────
        // Auto-generated legend box: one icon+label row per DISTINCT topo-symbol
        // type (see TOPO_SYMBOL_TYPES) currently present anywhere on the canvas.
        // It is entirely DERIVED state — never hand-edited — so the only user
        // control over it is the toolbar's position picker (see EditorComponent's
        // legendPosition/handleLegendPositionChange); adding/removing a symbol
        // just makes rebuildLegend() include/exclude that row on the next call.

        // i18n helper usable from this mixin (Vue's $t is available on `this`
        // wherever DrawingTools is mixed in, since it's a global property).
        _legendT(key, fallback) {
            return (this.$t ? this.$t(`admin.articles.canvas_editor.${key}`) : null) || fallback;
        },

        // Finds one representative Paper.js item for each present symbol type,
        // walking the WHOLE main layer recursively (so a symbol nested inside a
        // numbered-route group still counts) — same recursive-search pattern as
        // CanvasHandlers.vue's selectSegmentPoint. Skips the legend's own
        // subtree entirely so it can never "detect itself".
        //
        // Deliberately scans ONLY 'main', never any 'related-N' reference
        // overlay — an earlier version also scanned related-N (to build a
        // "combined" legend across sibling sectors sharing one photo), but
        // that made this legend depend on live reference-overlay state:
        // reference items are recolored for display (see
        // CanvasHandlers.vue's importRelatedJsons applyColor), so a symbol
        // found only in a sibling's overlay got that TINT baked permanently
        // into the legend icon instead of its real color; and since
        // related-N is only ever as fresh as whichever document was loaded
        // last, switching between sectors made the legend gain/lose rows
        // with nothing actually drawn or erased. The real combined-legend
        // feature (one legend covering every sector sharing a photo) is
        // computed separately, straight from each sector's own saved JSON,
        // by the PUBLIC viewer (see SectorLocalImageCanvasComponent.vue's
        // _buildCombinedLegendEntries) — never baked into any one sector's
        // own document here.
        _findPresentSymbols() {
            const mainLayer = this.scope.project.layers.find(l => l.name === 'main');
            if (!mainLayer) return [];
            const foundSample = {};
            const visit = (item) => {
                if (!item || (item.data && item.data.isLegend)) return;
                if (item.data) {
                    TOPO_SYMBOL_TYPES.forEach(type => {
                        if (!foundSample[type.key] && type.match(item.data)) {
                            foundSample[type.key] = item;
                        }
                    });
                }
                if (item.children) item.children.forEach(visit);
            };
            mainLayer.children.forEach(visit);
            return TOPO_SYMBOL_TYPES
                .filter(type => foundSample[type.key])
                .map(type => ({ ...type, sample: foundSample[type.key] }));
        },

        // Builds the legend Group: a background card plus one [icon, label] row
        // per present symbol type. Icons are CLONES of the actual symbol found on
        // the canvas (not redrawn from scratch), so the legend always matches the
        // real color/size in use and renders correctly through the shared
        // paperJsonRenderer.js walker (plain Group/Path/PointText primitives,
        // same as every other symbol) with zero changes needed there. `scale`
        // (default 1 = 100%) is the user's chosen overall legend size — see
        // getLegendScale/rebuildLegend.
        _buildLegendGroup(symbols, scale) {
            const pad = 12, rowH = 32, iconBox = 22, gap = 12;
            const fontSize = 13, titleSize = 15;

            const title = new paper.PointText({
                point: new paper.Point(0, 0),
                content: this._legendT('legend_title', 'Legend'),
                fillColor: '#1a1a1a',
                fontFamily: 'Arial',
                fontWeight: 'bold',
                fontSize: titleSize,
                justification: 'left',
                name: 'legend-title'
            });

            const rows = symbols.map(sym => {
                const icon = sym.sample.clone({ insert: false });
                // Strip role flags/name so the clone is inert content, never
                // mistaken for a real symbol by a future _findPresentSymbols scan.
                icon.data = {};
                icon.selected = false;
                icon.locked = false;
                // Normalize by strokeBounds (geometry + line thickness), not the
                // tight geometric bounds — symbol types differ a lot in how thick
                // their stroke is relative to their own size (e.g. a bolt's "X" is
                // drawn much bolder relative to its span than a rappel's shaft/
                // chevron), so fitting only the bare geometry into a fixed box
                // left some icons looking far heavier/bigger than others even
                // though their skeletons measured the same.
                const b = icon.strokeBounds || icon.bounds;
                const maxDim = Math.max(b.width, b.height) || 1;
                icon.scale(iconBox / maxDim);

                const label = new paper.PointText({
                    point: new paper.Point(0, 0),
                    content: this._legendT(sym.labelKey, sym.key),
                    fillColor: '#1a1a1a',
                    fontFamily: 'Arial',
                    fontSize: fontSize,
                    justification: 'left',
                    name: `legend-label-${sym.key}`
                });

                return { icon, label };
            });

            const contentWidth = Math.max(
                title.bounds.width,
                ...rows.map(r => iconBox + gap + r.label.bounds.width)
            );
            const boxW = contentWidth + pad * 2;

            let y = pad + titleSize;
            title.point = new paper.Point(pad, y);
            y += 14;
            rows.forEach(({ icon, label }) => {
                const rowCenterY = y + rowH / 2;
                icon.position = new paper.Point(pad + iconBox / 2, rowCenterY);
                label.point = new paper.Point(pad + iconBox + gap, rowCenterY + fontSize * 0.35);
                y += rowH;
            });
            const boxH = y + pad;

            // Built as a manual 4-segment closed Path (same approach as
            // add_rectangle above) rather than paper.Path.Rectangle's object-
            // literal shorthand, to avoid relying on less-common corner-radius
            // option parsing.
            const bg = new paper.Path({
                closed: true,
                fillColor: new paper.Color(1, 1, 1, 0.88),
                strokeColor: '#333333',
                strokeWidth: 1,
                name: 'legend-background'
            });
            bg.add(new paper.Point(0, 0));
            bg.add(new paper.Point(boxW, 0));
            bg.add(new paper.Point(boxW, boxH));
            bg.add(new paper.Point(0, boxH));

            const children = [bg, title];
            rows.forEach(({ icon, label }) => { children.push(icon, label); });

            const legend = new paper.Group(children);
            legend.applyMatrix = true;
            // Scales the whole finished card uniformly around its own center —
            // applied AFTER layout rather than baked into pad/rowH/fontSize
            // above, so every proportion (padding, row spacing, icon-to-label
            // gap) scales together instead of just the fonts/icons growing
            // while the card's whitespace stays fixed.
            if (scale && scale !== 1) legend.scale(scale);
            return legend;
        },

        // Positions the legend's card at one of the 8 corners/edges of the
        // background photo (falls back to the current view bounds if no
        // background is loaded yet).
        _positionLegend(legend, position) {
            if (!legend) return;
            const rb = this.rasterBounds;
            const bounds = rb
                ? new paper.Rectangle(rb.left, rb.top, rb.right - rb.left, rb.bottom - rb.top)
                : this.scope.view.bounds;
            const margin = 12;
            const w = legend.bounds.width, h = legend.bounds.height;
            let x, y;
            switch (position) {
                case 'top-left':    x = bounds.left + margin;               y = bounds.top + margin; break;
                case 'top-mid':     x = bounds.left + (bounds.width - w)/2; y = bounds.top + margin; break;
                case 'top-right':   x = bounds.right - w - margin;          y = bounds.top + margin; break;
                case 'left-mid':    x = bounds.left + margin;               y = bounds.top + (bounds.height - h)/2; break;
                case 'right-mid':   x = bounds.right - w - margin;          y = bounds.top + (bounds.height - h)/2; break;
                case 'bot-left':    x = bounds.left + margin;               y = bounds.bottom - h - margin; break;
                case 'bot-mid':     x = bounds.left + (bounds.width - w)/2; y = bounds.bottom - h - margin; break;
                case 'bot-right':   x = bounds.right - w - margin;          y = bounds.bottom - h - margin; break;
                default:            x = bounds.right - w - margin;          y = bounds.top + margin;
            }
            legend.position = new paper.Point(x + w / 2, y + h / 2);
        },

        // Reads the user's last-chosen legend position, stored on the 'main'
        // layer's own `.data` (persisted through exportJSON/importJSON like any
        // other item) rather than on the legend item itself — so the preference
        // survives even while there are zero symbols on canvas (and so "hidden"
        // doesn't require keeping an invisible item around, see rebuildLegend).
        getLegendPosition() {
            if (!this.scope) return 'top-right';
            const mainLayer = this.scope.project.layers.find(l => l.name === 'main');
            return (mainLayer && mainLayer.data && mainLayer.data.legendPosition) || 'top-right';
        },

        // Same storage pattern as getLegendPosition — the chosen overall size
        // multiplier (1 = 100%), stored on the 'main' layer's own `.data` so it
        // survives even while there are zero symbols on canvas.
        getLegendScale() {
            if (!this.scope) return 1;
            const mainLayer = this.scope.project.layers.find(l => l.name === 'main');
            const s = mainLayer && mainLayer.data && mainLayer.data.legendScale;
            return (typeof s === 'number' && s > 0) ? s : 1;
        },

        // What the toolbar's Position/Size pickers should actually SHOW —
        // the same "latest sibling wins" resolved position/scale that
        // computeEditorLegend()/drawCombinedLegend() use to decide what's
        // actually drawn (see legendRenderer.js's findLegendMeta), NOT just
        // this one document's own stored choice (getLegendPosition/
        // getLegendScale above, which rebuildLegend's internal refresh calls
        // still correctly use as-is — deliberately NOT changed, so an
        // implicit "just refresh after adding a symbol" call never
        // overwrites this document's own stored preference with a
        // sibling's).
        //
        // Without this, editing sector A while sector B (sharing the same
        // photo) has a MORE RECENTLY chosen position/scale showed the
        // toolbar highlighting sector A's own (older/possibly "hidden")
        // choice while the actual live legend on screen displayed sector
        // B's — the toolbar and the thing it's supposed to control looked
        // completely out of sync (fixed September 2026, reported as "legend
        // sincronithation for editing is not working normal"). Picking a
        // toolbar option while editing A still works exactly as before: it
        // stamps A's own legendUpdatedAt to now, which — via this same
        // "latest wins" comparison — immediately makes A's new choice win on
        // the very next resolve.
        getDisplayedLegendMeta() {
            const fallback = { position: this.getLegendPosition(), scale: this.getLegendScale() };
            if (!this.scope) return fallback;
            const mainLayer = this.scope.project.layers.find(l => l.name === 'main');
            const ownLd = (mainLayer && mainLayer.data) || {};

            let meta = null, hiddenMeta = null;
            const consider = (position, scale, updatedAt) => {
                if (!position) return;
                if (position === 'hidden') {
                    if (!hiddenMeta || (updatedAt || 0) > (hiddenMeta.updatedAt || 0)) hiddenMeta = { position, scale };
                } else if (!meta || (updatedAt || 0) > (meta.updatedAt || 0)) {
                    meta = { position, scale, updatedAt };
                }
            };
            if (ownLd.legendPosition || ownLd.legendScale) {
                consider(ownLd.legendPosition || 'top-right', ownLd.legendScale || 1, ownLd.legendUpdatedAt || 0);
            }
            (this.relatedJsons || []).forEach(raw => {
                if (!raw) return;
                let json = raw;
                try {
                    if (typeof json === 'string') json = JSON.parse(json);
                    if (typeof json === 'string') json = JSON.parse(json);
                } catch (_) { return; }
                const m = findLegendMeta(json);
                if (m) consider(m.position, m.scale, m.updatedAt);
            });
            const resolved = meta || hiddenMeta;
            return resolved ? { position: resolved.position, scale: resolved.scale || 1 } : fallback;
        },

        // (Re)builds the legend from scratch: removes whatever legend Group is
        // currently on canvas, then — unless the resolved position is 'hidden' or
        // no topo symbols are present at all — recreates it fresh from the
        // CURRENT set of symbols and re-positions it. Always safe/idempotent to
        // call after any add/erase/delete, which is exactly how it's used (see
        // CanvasHandlers.vue's onMouseUp for actions 22-27 and erase_at_point,
        // and EditorComponent.vue's deleteLayerItem/deleteChildItem/
        // deleteAllLayers) — this file's own single source of truth beats trying
        // to patch every individual delete call site to know about legend rows.
        // `position`/`scale` (both optional) are an explicit choice from the
        // toolbar's pickers; omit either to keep whatever was last stored.
        rebuildLegend(position, scale) {
            if (!this.scope) return;
            this.scope.activate();
            const mainLayer = this.scope.project.layers.find(l => l.name === 'main');
            if (!mainLayer) return;

            mainLayer.activate();
            mainLayer.children
                .filter(item => item.data && item.data.isLegend)
                .forEach(item => item.remove());

            const resolvedPosition = position || this.getLegendPosition();
            const resolvedScale = scale || this.getLegendScale();
            // legendUpdatedAt only advances on an EXPLICIT toolbar choice
            // (position/scale actually passed in, not the internal
            // "just refresh with whatever was already stored" calls after
            // every add/erase/delete) — see the combined-legend resolvers in
            // SectorLocalImageCanvasComponent.vue's _resolveLegendMeta and
            // canvasOverlaysMixin.js's computeEditorLegend, which pick
            // whichever sibling sector's legend meta has the LATEST
            // timestamp instead of an arbitrary fixed sibling, so the admin's
            // most recent choice on ANY sector sharing this photo always
            // wins (fixed September 2026, round 6 — reported as "legend
            // position and size is not changing", i.e. the toolbar picker
            // silently doing nothing whenever some OTHER sector on the same
            // photo already had a real position saved first).
            const legendUpdatedAt = (position !== undefined || scale !== undefined)
                ? Date.now()
                : (mainLayer.data && mainLayer.data.legendUpdatedAt) || undefined;
            mainLayer.data = { ...mainLayer.data, legendPosition: resolvedPosition, legendScale: resolvedScale, legendUpdatedAt };

            // sector_local_image/spot_rock_image editors compute their OWN
            // combined legend as a separate live PREVIEW (see
            // computeEditorLegend in those page components, and
            // resources/js/services/canvas/legendRenderer.js) — a real
            // per-sector Paper.js legend item here would be a SECOND,
            // contradicting legend: an actual drawing element that pans/
            // zooms with the canvas (so it can end up looking huge or
            // scrolled out of view depending on zoom), gets saved into this
            // ONE sector's own JSON, and gets baked into the shared
            // composite photo on save — permanently duplicating whatever the
            // live preview already shows on top of it. Position/scale ARE
            // still recorded just above (the toolbar picker stays
            // functional, and the live preview reads it), just no actual
            // legend Group gets built below.
            if (this.disableAutoLegend) return;

            if (resolvedPosition === 'hidden') return;

            const symbols = this._findPresentSymbols();
            if (!symbols.length) return;

            const legend = this._buildLegendGroup(symbols, resolvedScale);
            legend.name = 'legend 1';
            legend.data = { isLegend: true };
            mainLayer.addChild(legend);
            this._positionLegend(legend, resolvedPosition);
        }
    }
}
</script>
