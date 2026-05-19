<template>
    <div class="d-flex flex-wrap align-items-center gap-1 p-2 bg-light border rounded">

        <!-- History -->
        <div class="btn-group btn-group-sm">
            <button type="button" class="btn btn-danger" @click.prevent="$emit('reset')" title="Clear all">
                <i class="fa fa-times"></i>
            </button>
            <button type="button" class="btn btn-warning" :disabled="historyLength === 0" @click.prevent="$emit('undo')" :title="'Undo (' + historyLength + ')'">
                <i class="fa fa-undo"></i>
                <span v-if="historyLength > 0" class="badge bg-dark ms-1">{{ historyLength }}</span>
            </button>
            <button type="button" class="btn btn-warning" :disabled="redoLength === 0" @click.prevent="$emit('redo')" :title="'Redo (' + redoLength + ')'">
                <i class="fa fa-repeat"></i>
                <span v-if="redoLength > 0" class="badge bg-dark ms-1">{{ redoLength }}</span>
            </button>
        </div>

        <div class="vr mx-1"></div>

        <!-- Draw tools -->
        <div class="btn-group btn-group-sm">
            <button type="button" :class="['btn', 'btn-primary', { active: action === 1 }]" @click.prevent="$emit('line')" title="Line">
                <i class="fa fa-pencil"></i>
            </button>
            <button type="button" :class="['btn', 'btn-primary', { active: action === 2 }]" @click.prevent="$emit('point')" title="Point">
                <i class="fa fa-circle"></i>
            </button>
            <button type="button" :class="['btn', 'btn-primary', { active: action === 3 }]" @click.prevent="$emit('number')" title="Numbered route">
                <i class="fa fa-pencil"></i>&thinsp;<i class="fa fa-dot-circle-o"></i>
            </button>
            <button type="button" :class="['btn', 'btn-primary', { active: action === 4 }]" @click.prevent="$emit('rectangle')" title="Rectangle">
                <i class="fa fa-square-o"></i>
            </button>
            <button type="button" :class="['btn', 'btn-primary', { active: action === 7 }]" @click.prevent="$emit('combined')" title="Combined (rect + line + point)">
                <i class="fa fa-object-group"></i>
            </button>

            
            <button type="button" :class="['btn', 'btn-primary', { active: action === 10 }]" @click.prevent="$emit('circle')" title="Circle">
                <i class="fa fa-circle-o"></i>
            </button>
            <button type="button" :class="['btn', 'btn-primary', { active: action === 11 }]" @click.prevent="$emit('ellipse')" title="Ellipse">
                <i class="fa fa-circle-o"></i><small class="ms-1">E</small>
            </button>
            <button type="button" :class="['btn', 'btn-primary', { active: action === 12 }]" @click.prevent="$emit('polygon')" title="Polygon">
                <i class="fa fa-star-o"></i>
            </button>
            <button type="button" :class="['btn', 'btn-primary', { active: action === 13 }]" @click.prevent="$emit('text')" title="Text">
                <i class="fa fa-font"></i>
            </button>
        </div>

        <div class="vr mx-1"></div>

        <!-- Edit tools -->
        <div class="btn-group btn-group-sm">
            <button type="button" :class="['btn', 'btn-warning', { active: action === 5 }]" @click.prevent="$emit('eraser')" title="Erase item">
                <i class="fa fa-eraser"></i>
            </button>
            <button type="button" :class="['btn', 'btn-warning', { active: action === 6 }]" @click.prevent="$emit('erase_segment')" title="Erase segment">
                <i class="fa fa-scissors"></i>
            </button>
            <button type="button" :class="['btn', 'btn-warning', { active: action === 8 }]" @click.prevent="$emit('move')" title="Move item">
                <i class="fa fa-arrows"></i>
            </button>
            <button type="button" :class="['btn', 'btn-warning', { active: action === 14 }]" @click.prevent="$emit('selection')" title="Select multiple">
                <i class="fa fa-mouse-pointer"></i>
            </button>
        </div>

        <div class="vr mx-1"></div>

        <!-- View tools (active = grey) -->
        <div class="btn-group btn-group-sm">
            <button type="button" class="btn btn-secondary" @click.prevent="$emit('zoom-in')" title="Zoom In">
                <i class="fa fa-search-plus"></i>
            </button>
            <button type="button" class="btn btn-secondary" @click.prevent="$emit('zoom-out')" title="Zoom Out">
                <i class="fa fa-search-minus"></i>
            </button>
            <button type="button" :class="['btn', action === 9 ? 'btn-secondary' : 'btn-secondary']" @click.prevent="$emit('pan')" title="Pan">
                <i class="fa fa-hand-paper-o"></i>
            </button>
        </div>

        <div class="vr mx-1"></div>

        <!-- Export -->
        <div class="btn-group btn-group-sm">
            <button type="button" class="btn btn-secondary" @click.prevent="$emit('export-png')" title="Export PNG">
                <i class="fa fa-download"></i> PNG
            </button>
            <button type="button" class="btn btn-secondary" @click.prevent="$emit('export-svg')" title="Export SVG">
                <i class="fa fa-download"></i> SVG
            </button>
            <button type="button" class="btn btn-secondary" @click.prevent="$emit('save-image')" title="Download image">
                <i class="fa fa-save"></i>
            </button>
        </div>

    </div>
</template>

<script>
export default {
    name: "ToolbarComponent",
    props: {
        action: {
            type: Number,
            default: 1
        },
        historyLength: {
            type: Number,
            default: 0
        },
        redoLength: {
            type: Number,
            default: 0
        }
    }
}
</script>
