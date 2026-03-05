<template>
    <div class="toolbar">
        <!-- Column 1: Basic Actions -->
        <div class="toolbar-column">
            <button type="button" class="btn btn-danger" @click.prevent="$emit('reset')" title="Reset Canvas - Clear all drawings">
                <i class="fa fa-times" aria-hidden="true"></i>
            </button>

            <button type="button" class="btn btn-warning" @click.prevent="$emit('undo')" :disabled="historyLength === 0" title="Undo last action">
                <i class="fa fa-undo" aria-hidden="true"></i> {{ historyLength > 0 ? '(' + historyLength + ')' : '' }}
            </button>

            <button type="button" class="btn btn-warning" @click.prevent="$emit('redo')" :disabled="redoLength === 0" title="Redo last undone action">
                <i class="fa fa-repeat" aria-hidden="true"></i> {{ redoLength > 0 ? '(' + redoLength + ')' : '' }}
            </button>
        </div>

        <!-- Column 2: Drawing Tools -->
        <div class="toolbar-column">
            <button type="button" class="btn btn-primary but_action" @click.prevent="$emit('line')" v-if="action === 1" title="Draw freehand lines">
                <i class="fa fa-pencil" aria-hidden="true"></i>
            </button>
            <button type="button" class="btn btn-primary" @click.prevent="$emit('line')" v-else title="Draw freehand lines">
                <i class="fa fa-pencil" aria-hidden="true"></i>
            </button>

            <button type="button" class="btn btn-primary but_action" @click.prevent="$emit('point')" v-if="action === 2" title="Add point markers">
                <i class="fa fa-circle" aria-hidden="true"></i>
            </button>
            <button type="button" class="btn btn-primary" @click.prevent="$emit('point')" v-else title="Add point markers">
                <i class="fa fa-circle" aria-hidden="true"></i>
            </button>

            <button type="button" class="btn btn-primary but_action" @click.prevent="$emit('number')" v-if="action === 3" title="Draw lines with numbered points">
                <i class="fa fa-pencil" aria-hidden="true"></i> +
                <i class="fa fa-circle" aria-hidden="true"></i>
            </button>
            <button type="button" class="btn btn-primary" @click.prevent="$emit('number')" v-else title="Draw lines with numbered points">
                <i class="fa fa-pencil" aria-hidden="true"></i> +
                <i class="fa fa-circle" aria-hidden="true"></i>
            </button>

            <button type="button" class="btn btn-primary but_action" @click.prevent="$emit('rectangle')" v-if="action === 4" title="Draw rectangles">
                <i class="fa fa-square-o" aria-hidden="true"></i>
            </button>
            <button type="button" class="btn btn-primary" @click.prevent="$emit('rectangle')" v-else title="Draw rectangles">
                <i class="fa fa-square-o" aria-hidden="true"></i>
            </button>

            <button type="button" class="btn btn-success but_action" @click.prevent="$emit('combined')" v-if="action === 7" title="Draw rectangles with lines and points">
                <i class="fa fa-square-o" aria-hidden="true"></i> + <i class="fa fa-pencil" aria-hidden="true"></i> + <i class="fa fa-circle" aria-hidden="true"></i>
            </button>
            <button type="button" class="btn btn-success" @click.prevent="$emit('combined')" v-else title="Draw rectangles with lines and points">
                <i class="fa fa-square-o" aria-hidden="true"></i> + <i class="fa fa-pencil" aria-hidden="true"></i> + <i class="fa fa-circle" aria-hidden="true"></i>
            </button>
        </div>

        <!-- Column 3: Editing Tools -->
        <div class="toolbar-column">
            <button type="button" class="btn btn-warning but_action" @click.prevent="$emit('eraser')" v-if="action === 5" title="Erase entire items">
                <i class="fa fa-eraser" aria-hidden="true"></i>
            </button>
            <button type="button" class="btn btn-warning" @click.prevent="$emit('eraser')" v-else title="Erase entire items">
                <i class="fa fa-eraser" aria-hidden="true"></i>
            </button>

            <button type="button" class="btn btn-danger but_action" @click.prevent="$emit('erase_segment')" v-if="action === 6" title="Erase line segments">
                <i class="fa fa-scissors" aria-hidden="true"></i>
            </button>
            <button type="button" class="btn btn-danger" @click.prevent="$emit('erase_segment')" v-else title="Erase line segments">
                <i class="fa fa-scissors" aria-hidden="true"></i>
            </button>

            <button type="button" class="btn btn-info but_action" @click.prevent="$emit('move')" v-if="action === 8" title="Move selected items">
                <i class="fa fa-arrows" aria-hidden="true"></i>
            </button>
            <button type="button" class="btn btn-info" @click.prevent="$emit('move')" v-else title="Move selected items">
                <i class="fa fa-arrows" aria-hidden="true"></i>
            </button>
        </div>

        <!-- Column 4: Zoom and Pan -->
        <div class="toolbar-column">
            <button type="button" class="btn btn-secondary" @click.prevent="$emit('zoom-in')" title="Zoom In">
                <i class="fa fa-search-plus" aria-hidden="true"></i>
            </button>
            <button type="button" class="btn btn-secondary" @click.prevent="$emit('zoom-out')" title="Zoom Out">
                <i class="fa fa-search-minus" aria-hidden="true"></i>
            </button>
            <button type="button" class="btn btn-secondary but_action" @click.prevent="$emit('pan')" v-if="action === 9" title="Pan">
                <i class="fa fa-hand-paper-o" aria-hidden="true"></i>
            </button>
            <button type="button" class="btn btn-secondary" @click.prevent="$emit('pan')" v-else title="Pan">
                <i class="fa fa-hand-paper-o" aria-hidden="true"></i>
            </button>
        </div>

        <!-- Column 5: Shape Tools -->
        <div class="toolbar-column">
            <button type="button" class="btn btn-primary but_action" @click.prevent="$emit('circle')" v-if="action === 10" title="Circle">
                <i class="fa fa-circle-o" aria-hidden="true"></i>
            </button>
            <button type="button" class="btn btn-primary" @click.prevent="$emit('circle')" v-else title="Circle">
                <i class="fa fa-circle-o" aria-hidden="true"></i>
            </button>

            <button type="button" class="btn btn-primary but_action" @click.prevent="$emit('ellipse')" v-if="action === 11" title="Ellipse">
                <i class="fa fa-circle" aria-hidden="true"></i>
            </button>
            <button type="button" class="btn btn-primary" @click.prevent="$emit('ellipse')" v-else title="Ellipse">
                <i class="fa fa-circle" aria-hidden="true"></i>
            </button>

            <button type="button" class="btn btn-primary but_action" @click.prevent="$emit('polygon')" v-if="action === 12" title="Polygon">
                <i class="fa fa-star-o" aria-hidden="true"></i>
            </button>
            <button type="button" class="btn btn-primary" @click.prevent="$emit('polygon')" v-else title="Polygon">
                <i class="fa fa-star-o" aria-hidden="true"></i>
            </button>

            <button type="button" class="btn btn-primary but_action" @click.prevent="$emit('text')" v-if="action === 13" title="Text">
                <i class="fa fa-font" aria-hidden="true"></i>
            </button>
            <button type="button" class="btn btn-primary" @click.prevent="$emit('text')" v-else title="Text">
                <i class="fa fa-font" aria-hidden="true"></i>
            </button>

            <button type="button" class="btn btn-success but_action" @click.prevent="$emit('selection')" v-if="action === 14" title="Selection">
                <i class="fa fa-mouse-pointer" aria-hidden="true"></i>
            </button>
            <button type="button" class="btn btn-success" @click.prevent="$emit('selection')" v-else title="Selection">
                <i class="fa fa-mouse-pointer" aria-hidden="true"></i>
            </button>
        </div>

        <!-- Column 6: Export -->
        <div class="toolbar-column">
            <button type="button" class="btn btn-info" @click.prevent="$emit('export-png')" title="Export PNG">
                <i class="fa fa-download" aria-hidden="true"></i> PNG
            </button>
            <button type="button" class="btn btn-info" @click.prevent="$emit('export-svg')" title="Export SVG">
                <i class="fa fa-download" aria-hidden="true"></i> SVG
            </button>
            <button type="button" class="btn btn-success" @click.prevent="$emit('save-image')" title="Save Image to Server">
                <i class="fa fa-save" aria-hidden="true"></i> Save
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

<style scoped>
    .toolbar {
        padding: 15px;
        background-color: #f8f9fa;
        border-radius: 8px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        display: flex;
        flex-wrap: wrap;
        gap: 15px;
        justify-content: space-between;
    }

    .toolbar-column {
        display: flex;
        flex-direction: column;
        gap: 8px;
        min-width: 60px;
        flex: 1;
    }

    .toolbar button {
        transition: all 0.2s ease;
        width: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 12px;
        padding: 8px 10px;
        border-radius: 4px;
        min-height: 36px;
        flex-shrink: 0;
    }

    .toolbar button:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 8px rgba(0,0,0,0.15);
    }

    .toolbar button:active {
        transform: translateY(0);
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }

    .toolbar button:disabled {
        opacity: 0.6;
        cursor: not-allowed;
        transform: none !important;
    }

    .but_action {
        background-color: #a5a6a7;
        box-shadow: 0 0 0 0.2rem rgba(165, 166, 167, 0.25);
    }

    .but_action:hover {
        background-color: #939495;
        box-shadow: 0 0 0 0.2rem rgba(147, 148, 149, 0.25);
    }

    /* Responsive adjustments */
    @media (max-width: 1200px) {
        .toolbar {
            gap: 10px;
        }

        .toolbar-column {
            min-width: 50px;
        }

        .toolbar button {
            font-size: 11px;
            padding: 6px 8px;
            min-height: 32px;
        }
    }

    @media (max-width: 992px) {
        .toolbar {
            gap: 8px;
        }

        .toolbar-column {
            min-width: 45px;
        }

        .toolbar button {
            font-size: 10px;
            padding: 5px 6px;
            min-height: 28px;
        }
    }

    @media (max-width: 768px) {
        .toolbar {
            padding: 12px;
            gap: 6px;
        }

        .toolbar-column {
            min-width: 40px;
        }

        .toolbar button {
            font-size: 9px;
            padding: 4px 5px;
            min-height: 24px;
        }
    }

    @media (max-width: 576px) {
        .toolbar {
            padding: 10px;
            gap: 4px;
        }

        .toolbar-column {
            min-width: 35px;
        }

        .toolbar button {
            font-size: 8px;
            padding: 3px 4px;
            min-height: 20px;
        }
    }
</style>
