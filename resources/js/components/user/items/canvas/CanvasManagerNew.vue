<template>
    <canvas
        ref="canvas"
        class="canvas-style"
        @mousedown="handleMouseDown"
        @wheel="handleMouseWheel"
    />
</template>

<script setup>
import { ref, onMounted, onUnmounted, reactive, watch, nextTick } from 'vue'
import paper from 'paper'

const props = defineProps({
    canvasId: { type: String, default: 'canvas-one' },
    action: { type: Number, default: 1 },
    jsonProp: { type: String, default: null },
    relatedJsons: { type: Array, default: () => [] },
    strokeColor: { type: String, default: '#ff0000' },
    fillColor: { type: String, default: null },
    strokeWidth: { type: Number, default: 3 },
    zoomLevel: { type: Number, default: 1 },
    panOffset: { type: Object, default: () => ({ x: 0, y: 0 }) }
})

const emit = defineEmits(['canvas_data', 'layers_updated', 'layers_ready', 'zoom-changed'])

const canvas = ref(null)
const scope = ref(null)
const layers = reactive({
    line: 0,
    point: 0,
    rectangle: 0,
    circle: 0,
    ellipse: 0,
    polygon: 0,
    text: 0,
    group: 0
})

const state = reactive({
    path: null,
    currentLine: null,
    selectedItem: null,
    group: null,
    groupCounter: 0,
    history: [],
    redoStack: [],
    isPanning: false,
    panStartPoint: null
})

let tool = null

onMounted(() => {
    scope.value = new paper.PaperScope()
    scope.value.setup(canvas.value)
    scope.value.activate()
    scope.value.project.clear()
    updateView(props.zoomLevel, props.panOffset)
    importRelatedJsons()
    if (props.jsonProp) importJsonData(props.jsonProp)
    saveCanvasData()
    canvas.value.addEventListener('wheel', handleMouseWheel, { passive: false })
    emit('layers_ready')
})

onUnmounted(() => {
    if (canvas.value) {
        canvas.value.removeEventListener('wheel', handleMouseWheel)
    }
})

watch(() => props.jsonProp, (newVal) => {
    if (newVal) importJsonData(newVal)
})

watch(() => props.relatedJsons, () => {
    importRelatedJsons()
})

const createTool = () => {
    scope.value.activate()
    return new paper.Tool()
}

const handleMouseDown = (event) => {
    const paperEvent = new paper.Point(event.offsetX / props.zoomLevel - props.panOffset.x, event.offsetY / props.zoomLevel - props.panOffset.y)
    tool = createTool()
    
    tool.onMouseDown = (e) => onMouseDown(e, paperEvent)
    tool.onMouseDrag = onMouseDrag
    tool.onMouseUp = onMouseUp
}

const onMouseDown = (event, paperEvent) => {
    if (props.action === 1) {
        add_line()
    } else if (props.action === 2) {
        add_point(event.point)
    } else if (props.action === 3) {
        createGroup()
        add_line()
    } // ... other actions
}

const add_line = () => {
    layers.line++
    state.path = new paper.Path({
        strokeColor: props.strokeColor,
        strokeWidth: props.strokeWidth,
        strokeJoin: 'round',
        name: `line ${layers.line}`
    })
    if (state.group) state.group.addChild(state.path)
}

const add_point = (point) => {
    layers.point++
    const circle = new paper.Path.Circle({
        center: point,
        radius: 7,
        fillColor: props.strokeColor,
        strokeColor: props.strokeColor,
        name: `point ${layers.point}`
    })
    if (state.group) state.group.addChild(circle)
}

const saveCanvasData = () => {
    const canvasData = JSON.stringify(scope.value.project.exportJSON())
    emit('canvas_data', canvasData)
}

const importJsonData = (jsonData) => {
    if (jsonData && scope.value) {
        try {
            const parsedData = typeof jsonData === 'string' ? JSON.parse(jsonData) : jsonData
            scope.value.project.importJSON(parsedData)
            scope.value.view.update()
        } catch (error) {
            console.error('Error importing JSON:', error)
        }
    }
}

const updateView = (zoom, offset) => {
    if (scope.value?.view) {
        scope.value.view.zoom = zoom
        scope.value.view.center = new paper.Point(
            scope.value.view.viewSize.width / 2 + offset.x,
            scope.value.view.viewSize.height / 2 + offset.y
        )
    }
}

const handleMouseWheel = (event) => {
    event.preventDefault()
    const delta = event.deltaY > 0 ? 0.9 : 1.1
    const newZoom = scope.value.view.zoom * delta
    if (newZoom >= 0.1 && newZoom <= 10) {
        scope.value.view.zoom = newZoom
        emit('zoom-changed', newZoom)
    }
}

defineExpose({
    reset: () => scope.value.project.clear(),
    undoLastAction: () => { /* impl */ },
    redoLastAction: () => { /* impl */ },
    saveCanvasData,
    exportCanvas: (format) => { /* impl */ }
})
</script>

<style scoped>
.canvas-style {
    cursor: crosshair;
    width: 100%;
    height: 500px;
    border: 2px solid rgb(139, 139, 139);
    display: block;
    margin: auto;
}
</style>
