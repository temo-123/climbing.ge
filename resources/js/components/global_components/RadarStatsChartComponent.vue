<template>
    <div class="radar-stats-chart">
        <svg
            :viewBox="viewBox"
            class="radar-stats-chart__svg"
            :class="{ 'radar-stats-chart__svg--labeled': cornerLabels }"
            :style="cornerLabels ? { width: (size + pad.x * 2) + 'px' } : null"
            role="img"
            :aria-label="labels.map((l, i) => l + ': ' + (values[i] ?? 0)).join(', ')"
        >
            <polygon
                v-for="ring in rings"
                :key="ring"
                :points="ringPoints(ring)"
                class="radar-stats-chart__grid"
            />
            <line
                v-for="(axis, i) in axes"
                :key="'axis-' + i"
                :x1="center" :y1="center"
                :x2="axis.x" :y2="axis.y"
                class="radar-stats-chart__axis"
            />
            <polygon :points="dataPoints" class="radar-stats-chart__data" :style="{ fill: color + '55', stroke: color }" />
            <circle
                v-for="(p, i) in dataVertices"
                :key="'dot-' + i"
                :cx="p.x" :cy="p.y" :r="Math.max(1.5, size / 70)"
                :style="{ fill: color }"
            />
            <!-- axis name at each corner -->
            <template v-if="cornerLabels">
                <text
                    v-for="(c, i) in corners"
                    :key="'label-' + i"
                    :x="c.x" :y="c.y"
                    :text-anchor="c.anchor"
                    :font-size="FONT"
                    class="radar-stats-chart__corner"
                >
                    <tspan v-for="(line, j) in c.lines" :key="j" :x="c.x" :dy="j === 0 ? 0 : LINE_H">{{ line }}</tspan>
                </text>
            </template>
        </svg>

        <div class="radar-stats-chart__legend">
            <div v-for="(label, i) in labels" :key="'legend-' + i" class="radar-stats-chart__legend-item">
                <span class="radar-stats-chart__legend-count" :style="{ color: color }">{{ values[i] ?? 0 }}</span>
                <span class="radar-stats-chart__legend-label">{{ label }}</span>
            </div>
        </div>
    </div>
</template>

<script setup>
    import { computed } from 'vue'

    const props = defineProps({
        labels: { type: Array, required: true },
        values: { type: Array, required: true },
        color: { type: String, default: '#279fbb' },
        size: { type: Number, default: 280 },
        // names of the axes at the corners of the shape
        cornerLabels: { type: Boolean, default: true },
    })

    // Corner names are SVG text in viewBox units; the svg is drawn at 1 unit = 1 px
    // (width = viewBox width, shrinks on narrow screens), so they stay readable
    // even for the small chart on the dashboard card.
    const FONT = computed(() => props.size >= 220 ? 12 : 11)
    const LINE_H = computed(() => FONT.value + 1)
    const GAP = 7
    // side margin fits the longest (Georgian) names at this font size
    const pad = computed(() => props.cornerLabels ? { x: Math.round(FONT.value * 7.4), y: LINE_H.value * 2 + GAP + 3 } : { x: 0, y: 0 })
    const viewBox = computed(() => `${-pad.value.x} ${-pad.value.y} ${props.size + pad.value.x * 2} ${props.size + pad.value.y * 2}`)

    // "Summit Ascents" → ["Summit", "Ascents"]: at most two lines, split near the middle
    function splitLabel(label) {
        const text = String(label ?? '')
        const words = text.split(' ')
        if (words.length < 2 || text.length <= 12) return [text]
        let best = 1, bestDiff = Infinity
        for (let i = 1; i < words.length; i++) {
            const diff = Math.abs(words.slice(0, i).join(' ').length - words.slice(i).join(' ').length)
            if (diff < bestDiff) { bestDiff = diff; best = i }
        }
        return [words.slice(0, best).join(' '), words.slice(best).join(' ')]
    }

    const center = computed(() => props.size / 2)
    const radius = computed(() => props.size * 0.38)
    const rings = [0.34, 0.67, 1]

    // Evenly spaced axes, apex pointing straight up (-90deg) — one per label,
    // so 3 labels draw a triangle, 4 a diamond, etc.
    const angles = computed(() => {
        const count = props.labels.length
        return Array.from({ length: count }, (_, i) => (-90 + (360 / count) * i) * Math.PI / 180)
    })

    function vertexAt(angleRad, fraction) {
        return {
            x: center.value + Math.cos(angleRad) * radius.value * fraction,
            y: center.value + Math.sin(angleRad) * radius.value * fraction,
        }
    }

    const axes = computed(() => angles.value.map(a => vertexAt(a, 1)))

    // label anchor just outside each corner: left / right corners align away from
    // the shape, top / bottom ones are centred and stack upwards / downwards
    const corners = computed(() => angles.value.map((a, i) => {
        const lines = splitLabel(props.labels[i])
        const cos = Math.cos(a), sin = Math.sin(a)
        const p = vertexAt(a, 1)
        const x = p.x + cos * GAP
        let y = p.y + sin * GAP
        const block = (lines.length - 1) * LINE_H.value
        if (sin < -0.6) y -= block + 2                    // top: last line sits above the corner
        else if (sin > 0.6) y += FONT.value               // bottom: first line below the corner
        else y += FONT.value * 0.35 - block / 2           // sides: centred on the corner
        const anchor = cos > 0.2 ? 'start' : (cos < -0.2 ? 'end' : 'middle')
        return { x, y, anchor, lines }
    }))

    function ringPoints(fraction) {
        return angles.value.map(a => {
            const p = vertexAt(a, fraction)
            return `${p.x},${p.y}`
        }).join(' ')
    }

    // Square-root scale: raw counts (e.g. comments) tend to run far higher
    // than others (ascents, reviews), which pinned one axis to 100% and
    // squashed the rest to the floor. sqrt compresses that gap while still
    // preserving which axis is bigger.
    function scaled(v) {
        return Math.sqrt(Math.max(v, 0))
    }

    const maxValue = computed(() => Math.max(...props.values.map(scaled), 1))

    const dataVertices = computed(() => angles.value.map((a, i) => {
        const fraction = Math.max(scaled(props.values[i] ?? 0) / maxValue.value, 0.04)
        return vertexAt(a, fraction)
    }))

    const dataPoints = computed(() => dataVertices.value.map(p => `${p.x},${p.y}`).join(' '))
</script>

<style scoped>
.radar-stats-chart {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 10px;
}
.radar-stats-chart__svg {
    width: 100%;
    max-width: 220px;
    height: auto;
}
.radar-stats-chart__svg--labeled {
    max-width: 100%;
}
.radar-stats-chart__corner {
    font-weight: 600;
    fill: var(--cg-muted, #8a9aa1);
}
.radar-stats-chart__grid {
    fill: none;
    stroke: #e2e9ec;
    stroke-width: 1;
}
.radar-stats-chart__axis {
    stroke: #e2e9ec;
    stroke-width: 1;
}
.radar-stats-chart__data {
    stroke-width: 1.6;
    fill-rule: evenodd;
    transition: all 0.2s ease;
}
.radar-stats-chart__legend {
    display: flex;
    flex-wrap: wrap;
    justify-content: center;
    gap: 18px;
}
.radar-stats-chart__legend-item {
    display: flex;
    flex-direction: column;
    align-items: center;
}
.radar-stats-chart__legend-count {
    font-size: 16px;
    font-weight: 700;
    line-height: 1.2;
}
.radar-stats-chart__legend-label {
    font-size: 11px;
    color: #8a9aa1;
}
</style>
