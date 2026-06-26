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
            <button type="button" :class="['btn', 'btn-primary', { active: action === 7 }]" @click.prevent="$emit('combined')" title="Combined (number + line + point)">
                <span style="display:inline-flex; align-items:center; gap:2px; vertical-align:middle;">
                    <b style="font-size:11px; font-weight:bold; line-height:1;">1</b>
                    <span style="width:10px; height:2px; background:currentColor; display:inline-block; flex-shrink:0;"></span>
                    <i class="fa fa-circle" style="font-size:6px;"></i>
                </span>
            </button>
            <button type="button" :class="['btn', 'btn-primary', { active: action === 10 }]" @click.prevent="$emit('circle')" title="Circle (drag to resize)">
                <i class="fa fa-circle-o"></i>
            </button>
            <button type="button" :class="['btn', 'btn-primary', { active: action === 11 }]" @click.prevent="$emit('ellipse')" title="Ellipse (drag to resize)">
                <i class="fa fa-circle-o"></i><small class="ms-1">E</small>
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
            <button type="button" :class="['btn', 'btn-warning', { active: action === 15 }]" @click.prevent="$emit('crop')" title="Crop / cut image area">
                <i class="fa fa-crop"></i>
            </button>
            <button type="button" :class="['btn', 'btn-warning', { active: action === 16 }]" @click.prevent="$emit('edit-points')" title="Edit line points (drag a segment point)">
                <i class="fa fa-share-alt"></i>
            </button>
        </div>

        <div class="vr mx-1"></div>

        <!-- View tools -->
        <div class="btn-group btn-group-sm">
            <button type="button" class="btn btn-secondary" @click.prevent="$emit('zoom-in')" title="Zoom In (scroll up)">
                <i class="fa fa-search-plus"></i>
            </button>
            <button type="button" class="btn btn-secondary" @click.prevent="$emit('zoom-out')" title="Zoom Out (scroll down)">
                <i class="fa fa-search-minus"></i>
            </button>
            <button type="button" class="btn btn-secondary" @click.prevent="$emit('zoom-reset')" title="Reset zoom 1:1">
                <i class="fa fa-search"></i> 1:1
            </button>
            <button type="button" :class="['btn', 'btn-secondary', { active: action === 9 }]" @click.prevent="$emit('pan')" title="Pan (drag to move)">
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
