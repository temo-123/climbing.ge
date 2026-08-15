<template>
    <div class="radar-stats-chart">
        <svg :viewBox="`0 0 ${size} ${size}`" class="radar-stats-chart__svg">
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
                :cx="p.x" :cy="p.y" r="1.5"
                :style="{ fill: color }"
            />
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
        size: { type: Number, default: 200 },
    })

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
    stroke-width: 1.2;
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
    font-size: 1.6rem;
    font-weight: 700;
    line-height: 1.2;
}
.radar-stats-chart__legend-label {
    font-size: 1.1rem;
    color: #8a9aa1;
}
</style>
