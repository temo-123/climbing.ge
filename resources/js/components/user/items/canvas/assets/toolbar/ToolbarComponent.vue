<template>
    <div class="canvas-toolbar">

        <!-- Tool buttons row -->
        <div class="d-flex flex-wrap align-items-end gap-2 px-2 pt-2 pb-1 bg-light border rounded-top">

            <!-- History -->
            <div class="tool-group">
                <div class="btn-group btn-group-sm">
                    <button type="button" class="btn btn-danger" @click.prevent="$emit('reset')" title="Clear all drawings">
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
                <div class="tool-group-label">History</div>
            </div>

            <div class="vr align-self-stretch my-1"></div>

            <!-- Draw tools -->
            <div class="tool-group">
                <div class="btn-group btn-group-sm">
                    <button type="button" :class="['btn', 'btn-primary', { active: action === 1 }]" @click.prevent="$emit('line')" title="Freehand line [L]">
                        <i class="fa fa-pencil"></i>
                    </button>
                    <button type="button" :class="['btn', 'btn-primary', { active: action === 20 }]" @click.prevent="$emit('continue-line')" :disabled="!hasDrawing" title="Continue from endpoint [E]">
                        <i class="fa fa-pencil"></i><i class="fa fa-long-arrow-right" style="font-size:9px; margin-left:1px;"></i>
                    </button>
                    <button type="button" :class="['btn', 'btn-primary', { active: action === 2 }]" @click.prevent="$emit('point')" title="Point / dot [P]">
                        <i class="fa fa-circle" style="font-size:8px;"></i>
                    </button>
                    <button type="button" :class="['btn', 'btn-primary', { active: action === 3 }]" @click.prevent="$emit('number')" title="Numbered route (line + point)">
                        <i class="fa fa-pencil"></i>&thinsp;<i class="fa fa-dot-circle-o" style="font-size:9px;"></i>
                    </button>
                    <button type="button" :class="['btn', 'btn-primary', { active: action === 7 }]" @click.prevent="$emit('combined')" title="Combined: number + line + dot">
                        <span style="display:inline-flex; align-items:center; gap:2px; vertical-align:middle;">
                            <b style="font-size:10px; font-weight:bold; line-height:1;">1</b>
                            <span style="width:8px; height:2px; background:currentColor; display:inline-block;"></span>
                            <i class="fa fa-circle" style="font-size:5px;"></i>
                        </span>
                    </button>
                    <button type="button" :class="['btn', 'btn-primary', { active: action === 4 }]" @click.prevent="$emit('rectangle')" title="Rectangle [R]">
                        <i class="fa fa-square-o"></i>
                    </button>
                    <button type="button" :class="['btn', 'btn-primary', { active: action === 10 }]" @click.prevent="$emit('circle')" title="Circle (drag to size) [C]">
                        <i class="fa fa-circle-o"></i>
                    </button>
                    <button type="button" :class="['btn', 'btn-primary', { active: action === 11 }]" @click.prevent="$emit('ellipse')" title="Ellipse (drag to size)">
                        <i class="fa fa-circle-o"></i><span style="font-size:9px; margin-left:1px; line-height:1; font-style:italic;">e</span>
                    </button>
                    <button type="button" :class="['btn', 'btn-primary', { active: action === 13 }]" @click.prevent="$emit('text')" title="Text label [T]">
                        <i class="fa fa-font"></i>
                    </button>
                </div>
                <div class="tool-group-label">Draw</div>
            </div>

            <div class="vr align-self-stretch my-1"></div>

            <!-- Edit tools -->
            <div class="tool-group">
                <div class="btn-group btn-group-sm">
                    <button type="button" :class="['btn', 'btn-warning', { active: action === 5 }]" :disabled="!hasDrawing" @click.prevent="$emit('eraser')" title="Erase item (click to delete) [Del]">
                        <i class="fa fa-eraser"></i>
                    </button>
                    <button type="button" :class="['btn', 'btn-warning', { active: action === 8 }]" :disabled="!hasUnlockedDrawing" @click.prevent="$emit('move')" title="Move single item (drag) [M]">
                        <i class="fa fa-arrows"></i>
                    </button>
                    <button type="button" :class="['btn', 'btn-warning', { active: action === 17 }]" :disabled="!hasUnlockedDrawing" @click.prevent="$emit('move-all')" title="Move all objects together">
                        <i class="fa fa-arrows-alt"></i>
                    </button>
                    <button type="button" :class="['btn', 'btn-warning', { active: action === 14 }]" :disabled="!hasUnlockedDrawing" @click.prevent="$emit('selection')" title="Select area (rubber-band) [S]">
                        <i class="fa fa-mouse-pointer"></i>
                    </button>
                    <button type="button" :class="['btn', 'btn-warning', { active: action === 19 }]" :disabled="!hasUnlockedDrawing" @click.prevent="$emit('resize')" title="Resize shape (click then drag corner)">
                        <i class="fa fa-expand"></i>
                    </button>
                    <button type="button" :class="['btn', 'btn-warning', { active: action === 15 }]" @click.prevent="$emit('crop')" title="Crop canvas area (zoom to selection)">
                        <i class="fa fa-crop"></i>
                    </button>
                    <button type="button" :class="['btn', 'btn-warning', { active: action === 16 }]" :disabled="!hasDrawing" @click.prevent="$emit('edit-points')" title="Edit path points (drag a segment point)">
                        <i class="fa fa-share-alt"></i>
                    </button>
                </div>
                <div class="tool-group-label">Edit</div>
            </div>

            <div class="vr align-self-stretch my-1"></div>

            <!-- View / Navigate -->
            <div class="tool-group">
                <div class="btn-group btn-group-sm">
                    <button type="button" class="btn btn-secondary" @click.prevent="$emit('zoom-in')" title="Zoom In [+]">
                        <i class="fa fa-search-plus"></i>
                    </button>
                    <button type="button" class="btn btn-secondary" @click.prevent="$emit('zoom-reset')" title="Reset zoom to fit [0]">
                        <i class="fa fa-search"></i>
                    </button>
                    <button type="button" class="btn btn-secondary" @click.prevent="$emit('zoom-out')" title="Zoom Out [-]">
                        <i class="fa fa-search-minus"></i>
                    </button>
                    <button type="button" :class="['btn', 'btn-secondary', { active: action === 9 }]" @click.prevent="$emit('pan')" title="Pan / scroll canvas (drag) [Space]">
                        <i class="fa fa-hand-paper-o"></i>
                    </button>
                </div>
                <div class="tool-group-label">Navigate</div>
            </div>

            <div class="vr align-self-stretch my-1"></div>

            <!-- Export -->
            <div class="tool-group">
                <div class="btn-group btn-group-sm">
                    <button type="button" class="btn btn-secondary" @click.prevent="$emit('export-png')" title="Export as PNG file">
                        <i class="fa fa-download"></i> PNG
                    </button>
                    <button type="button" class="btn btn-secondary" @click.prevent="$emit('export-svg')" title="Export as SVG file">
                        <i class="fa fa-download"></i> SVG
                    </button>
                    <button type="button" class="btn btn-secondary" @click.prevent="$emit('save-image')" title="Download canvas image">
                        <i class="fa fa-save"></i>
                    </button>
                </div>
                <div class="tool-group-label">Export</div>
            </div>

            <!-- Active tool badge pushed to the right -->
            <div class="ms-auto d-flex align-items-center pb-1">
                <span class="badge rounded-pill" :class="activeBadgeClass" style="font-size:11px; padding:4px 10px;">
                    <i :class="activeActionIcon" class="me-1"></i>{{ actionLabel }}
                </span>
            </div>

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
        },
        hasDrawing: {
            type: Boolean,
            default: false
        },
        hasUnlockedDrawing: {
            type: Boolean,
            default: true
        }
    },
    computed: {
        actionLabel() {
            const labels = {
                1:  'Line',
                2:  'Point',
                3:  'Number',
                4:  'Rectangle',
                5:  'Eraser',
                7:  'Combined',
                8:  'Move Item',
                9:  'Pan',
                10: 'Circle',
                11: 'Ellipse',
                13: 'Text',
                14: 'Select',
                15: 'Crop',
                16: 'Edit Points',
                17: 'Move All',
                19: 'Resize',
                20: 'Continue Line',
            };
            return labels[this.action] || 'Line';
        },
        activeActionIcon() {
            const icons = {
                1:  'fa fa-pencil',
                2:  'fa fa-circle',
                3:  'fa fa-pencil',
                4:  'fa fa-square-o',
                5:  'fa fa-eraser',
                7:  'fa fa-pencil',
                8:  'fa fa-arrows',
                9:  'fa fa-hand-paper-o',
                10: 'fa fa-circle-o',
                11: 'fa fa-circle-o',
                13: 'fa fa-font',
                14: 'fa fa-mouse-pointer',
                15: 'fa fa-crop',
                16: 'fa fa-share-alt',
                17: 'fa fa-arrows-alt',
                19: 'fa fa-expand',
                20: 'fa fa-pencil',
            };
            return icons[this.action] || 'fa fa-pencil';
        },
        activeBadgeClass() {
            // Draw tools = primary, edit tools = warning, view = secondary
            const primaryActions  = [1, 2, 3, 4, 7, 10, 11, 13, 20];
            const warningActions  = [5, 8, 14, 15, 16, 17, 19];
            if (primaryActions.includes(this.action))  return 'bg-primary';
            if (warningActions.includes(this.action))  return 'bg-warning text-dark';
            return 'bg-secondary';
        }
    }
}
</script>

<style scoped>
.canvas-toolbar {
    user-select: none;
}
.tool-group {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 2px;
}
.tool-group-label {
    font-size: 9px;
    color: #6c757d;
    text-transform: uppercase;
    letter-spacing: 0.04em;
    line-height: 1;
    text-align: center;
}
</style>
