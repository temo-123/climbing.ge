<script>
import paper from 'paper';

export default {
    methods: {
        _stroke() { return this.currentStrokeColor || '#ff0000'; },
        _fill()   { return this.currentFillColor   || null; },
        _width()  { return this.currentStrokeWidth  || 3; },

        createTool(scope) {
            scope.activate();
            return new paper.Tool();
        },

        add_point(event) {
            this.layerCounters.point++;
            const dotColor = (this.currentFillColor != null) ? this.currentFillColor : '#ffd700';
            const point = new paper.Path.Circle({
                center: event.point,
                radius: 7,
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
            if (this.path && this.path.data && this.path.data.isRectangle) {
                const rectBounds = this.path.bounds;
                const startPoint = this.getClosestPerimeterPoint(rectBounds, mousePoint);
                this.currentLine.add(startPoint);
            }
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

            const text = new paper.PointText({
                point: event.point,
                content: this.layerCounters.rectangle.toString(),
                fillColor: this._stroke(),
                fontFamily: 'Arial',
                fontSize: 14,
                justification: 'center'
            });
            text.name = `text ${this.layerCounters.rectangle}`;
            rect.data.textLabel = text;

            return rect;
        },

        add_small_rectangle(event) {
            this.layerCounters.rectangle++;
            const size = 20;
            const halfSize = size / 2;
            const center = event.point;

            const rect = new paper.Path.Rectangle({
                point: [center.x - halfSize, center.y - halfSize],
                size: [size, size],
                radius: 3,
                strokeColor: this._stroke(),
                strokeWidth: this._width(),
                fillColor: this._fill(),
                name: `rectangle ${this.layerCounters.rectangle}`
            });
            rect.data = { isRectangle: true, isSmall: true };
            this.path = rect;

            const text = new paper.PointText({
                point: new paper.Point(center.x, center.y + 3),
                content: this.layerCounters.rectangle.toString(),
                fillColor: this._stroke(),
                fontFamily: 'Arial',
                fontSize: 20,
                fontWeight: 'bold',
                justification: 'center'
            });
            text.name = `text ${this.layerCounters.rectangle}`;
            rect.data.textLabel = text;

            if (this.group) {
                this.group.addChild(rect);
                this.group.addChild(text);
            }

            return rect;
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

        add_text(event) {
            this.layerCounters.text++;
            const text = new paper.PointText({
                point: event.point,
                content: 'Text',
                fillColor: this._stroke(),
                fontFamily: 'Arial',
                fontSize: 16,
                justification: 'center',
                name: `text ${this.layerCounters.text}`
            });
            if (this.group) this.group.addChild(text);
            return text;
        }
    }
}
</script>
