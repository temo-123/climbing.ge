<script>
import paper from 'paper';

export default {
    methods: {
        createTool(scope) {
            scope.activate();
            return new paper.Tool();
        },

        add_point(event) {
            this.layerCounters.point++;
            const point = new paper.Path.Circle({
                center: event.point,
                radius: 7,
                fillColor: '#ff0000',
                strokeColor: '#ff0000',
                name: `point ${this.layerCounters.point}`
            });
            if (this.group) {
                this.group.addChild(point);
            }
            return point;
        },

        add_line() {
            this.layerCounters.line++;
            this.path = new paper.Path({
                strokeColor: "#ff0000",
                strokeWidth: 3,
                strokeJoin: 'round',
                name: `line ${this.layerCounters.line}`
            });
            if (this.group) {
                this.group.addChild(this.path);
            }
            return this.path;
        },

        createGroup() {
            this.groupCounter++;
            this.group = new paper.Group();
            this.group.name = `group ${this.groupCounter}`;
            this.scope.project.activeLayer.addChild(this.group);
        },

        add_line_and_point() {
            // Placeholder for combined actions
        },

        add_line_for_combined(mousePoint) {
            this.layerCounters.line++;
            this.currentLine = new paper.Path({
                strokeColor: "#ff0000",
                strokeWidth: 3,
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
                if (distance < minDistance) {
                    minDistance = distance;
                    closestPoint = point;
                }
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
                strokeColor: '#ff0000',
                strokeWidth: 3,
                fillColor: null,
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
                fillColor: '#ff0000',
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
                strokeColor: '#ff0000',
                strokeWidth: 3,
                fillColor: null,
                name: `rectangle ${this.layerCounters.rectangle}`
            });

            rect.data = { isRectangle: true, isSmall: true };
            this.path = rect;

            const text = new paper.PointText({
                point: new paper.Point(center.x, center.y + 3),
                content: this.layerCounters.rectangle.toString(),
                fillColor: '#ff0000',
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

        // New drawing tools
        add_circle(event) {
            this.layerCounters.circle = (this.layerCounters.circle || 0) + 1;
            const circle = new paper.Path.Circle({
                center: event.point,
                radius: 1,
                strokeColor: '#ff0000',
                strokeWidth: 3,
                fillColor: null,
                name: `circle ${this.layerCounters.circle}`
            });

            circle.data = { isCircle: true, center: event.point };
            this.path = circle;

            if (this.group) {
                this.group.addChild(circle);
            }

            return circle;
        },

        add_circle_at_point(center, radius) {
            this.layerCounters.circle = (this.layerCounters.circle || 0) + 1;
            const circle = new paper.Path.Circle({
                center: center,
                radius: radius,
                strokeColor: '#ff0000',
                strokeWidth: 3,
                fillColor: null,
                name: `circle ${this.layerCounters.circle}`
            });

            return circle;
        },

        add_ellipse(event) {
            this.layerCounters.ellipse = (this.layerCounters.ellipse || 0) + 1;
            const ellipse = new paper.Path.Ellipse({
                point: event.point,
                size: [1, 1],
                strokeColor: '#ff0000',
                strokeWidth: 3,
                fillColor: null,
                name: `ellipse ${this.layerCounters.ellipse}`
            });

            ellipse.data = { isEllipse: true, startPoint: event.point };
            this.path = ellipse;

            if (this.group) {
                this.group.addChild(ellipse);
            }

            return ellipse;
        },

        add_ellipse_at_point(startPoint, width, height) {
            this.layerCounters.ellipse = (this.layerCounters.ellipse || 0) + 1;
            const ellipse = new paper.Path.Ellipse({
                point: startPoint,
                size: [width, height],
                strokeColor: '#ff0000',
                strokeWidth: 3,
                fillColor: null,
                name: `ellipse ${this.layerCounters.ellipse}`
            });

            return ellipse;
        },

        add_polygon(event) {
            this.layerCounters.polygon = (this.layerCounters.polygon || 0) + 1;
            // Start with a triangle as default
            const sides = 3;
            const radius = 30;
            const points = [];

            for (let i = 0; i < sides; i++) {
                const angle = (i / sides) * Math.PI * 2;
                const x = event.point.x + Math.cos(angle) * radius;
                const y = event.point.y + Math.sin(angle) * radius;
                points.push(new paper.Point(x, y));
            }

            const polygon = new paper.Path({
                segments: points,
                closed: true,
                strokeColor: '#ff0000',
                strokeWidth: 3,
                fillColor: null,
                name: `polygon ${this.layerCounters.polygon}`
            });

            polygon.data = { isPolygon: true };
            this.path = polygon;

            if (this.group) {
                this.group.addChild(polygon);
            }

            return polygon;
        },

        update_polygon_preview(mousePoint) {
            // This would be complex to implement fully, for now just keep the initial polygon
        },

        finish_polygon(event) {
            // Polygon is already created in add_polygon
        },

        add_text(event) {
            this.layerCounters.text = (this.layerCounters.text || 0) + 1;
            const text = new paper.PointText({
                point: event.point,
                content: 'Text',
                fillColor: '#ff0000',
                fontFamily: 'Arial',
                fontSize: 16,
                justification: 'center',
                name: `text ${this.layerCounters.text}`
            });

            if (this.group) {
                this.group.addChild(text);
            }

            return text;
        }
    }
}
</script>
