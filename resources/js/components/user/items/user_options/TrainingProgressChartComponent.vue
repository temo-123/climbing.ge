<template>
    <div class="training-progress-chart">
        <svg :viewBox="`0 0 ${width} ${height}`" class="training-progress-chart__svg" preserveAspectRatio="none">
            <line :x1="padding" :y1="height - padding" :x2="width - padding" :y2="height - padding" class="training-progress-chart__axis" />

            <g v-for="(bar, i) in bars" :key="bar.date">
                <rect
                    :x="bar.x"
                    :y="bar.y"
                    :width="barWidth"
                    :height="bar.h"
                    :class="bar.status === 'success' ? 'training-progress-chart__bar--success' : 'training-progress-chart__bar--failed'"
                    rx="2"
                >
                    <title>{{ bar.date }} — {{ bar.volume }}</title>
                </rect>
                <text
                    v-if="i % labelStep === 0"
                    :x="bar.x + barWidth / 2"
                    :y="height - padding + 14"
                    class="training-progress-chart__label"
                    text-anchor="middle"
                >{{ bar.shortDate }}</text>
            </g>
        </svg>

        <div class="training-progress-chart__legend">
            <span class="training-progress-chart__legend-item"><i class="training-progress-chart__dot training-progress-chart__dot--success"></i>{{ successLabel }}</span>
            <span class="training-progress-chart__legend-item"><i class="training-progress-chart__dot training-progress-chart__dot--failed"></i>{{ failedLabel }}</span>
        </div>
    </div>
</template>

<script setup>
    import { computed } from 'vue'

    const props = defineProps({
        history: { type: Array, required: true },
        successLabel: { type: String, default: 'Success' },
        failedLabel: { type: String, default: 'Failed' },
    })

    const width = 640
    const height = 200
    const padding = 24

    const barWidth = computed(() => {
        const count = Math.max(props.history.length, 1)
        const available = width - padding * 2
        return Math.min(28, Math.max(6, (available / count) - 6))
    })

    const labelStep = computed(() => {
        // Thin out x-axis labels so they don't overlap when there are many bars.
        const count = props.history.length
        if (count <= 8) return 1
        if (count <= 14) return 2
        return 3
    })

    const maxVolume = computed(() => {
        const volumes = props.history.map(h => Math.max((h.repsCompleted || 0) * (h.setsCompleted || 0), 1))
        return Math.max(...volumes, 1)
    })

    function shortDate(dateStr) {
        const d = new Date(dateStr)
        if (isNaN(d)) return ''
        return (d.getMonth() + 1) + '/' + d.getDate()
    }

    const bars = computed(() => {
        const count = Math.max(props.history.length, 1)
        const available = width - padding * 2
        const step = available / count
        const chartTop = padding
        const chartBottom = height - padding
        const usableHeight = chartBottom - chartTop

        return props.history.map((h, i) => {
            const volume = Math.max((h.repsCompleted || 0) * (h.setsCompleted || 0), 1)
            const barHeight = Math.max((volume / maxVolume.value) * usableHeight, 4)
            return {
                date: h.date,
                shortDate: shortDate(h.date),
                status: h.status,
                volume,
                x: padding + i * step + (step - barWidth.value) / 2,
                y: chartBottom - barHeight,
                h: barHeight,
            }
        })
    })
</script>

<style scoped>
    .training-progress-chart {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 10px;
    }
    .training-progress-chart__svg {
        width: 100%;
        height: 180px;
    }
    .training-progress-chart__axis {
        stroke: #d7dee1;
        stroke-width: 1;
    }
    .training-progress-chart__bar--success {
        fill: #28a745;
    }
    .training-progress-chart__bar--failed {
        fill: #dc3545;
    }
    .training-progress-chart__label {
        font-size: 9px;
        fill: #8a9aa1;
    }
    .training-progress-chart__legend {
        display: flex;
        gap: 20px;
        font-size: 0.9rem;
        color: #6c757d;
    }
    .training-progress-chart__legend-item {
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }
    .training-progress-chart__dot {
        width: 10px;
        height: 10px;
        border-radius: 50%;
        display: inline-block;
    }
    .training-progress-chart__dot--success {
        background: #28a745;
    }
    .training-progress-chart__dot--failed {
        background: #dc3545;
    }
</style>
