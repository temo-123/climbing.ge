<script>
import paper from 'paper';

export default {
    methods: {
        _stroke()    { return this.currentStrokeColor || '#ff0000'; },
        _fill()      { return this.currentFillColor   || null; },
        _width()     { return this.currentStrokeWidth  || 3; },
        _textSize()  { return this.currentTextSize     || 16; },
        _dotSize()   { return this.currentDotSize      || 4; },

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
        }
    }
}
</script>
