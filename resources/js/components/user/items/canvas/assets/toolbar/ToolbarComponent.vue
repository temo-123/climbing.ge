<template>
    <div class="canvas-toolbar">

        <!-- Tool buttons row -->
        <div class="d-flex flex-wrap align-items-end gap-2 px-2 pt-2 pb-1 bg-light border rounded-top">

            <!-- History -->
            <div class="tool-group">
                <div class="btn-group btn-group-sm">
                    <button type="button" class="btn btn-danger" @click.prevent="$emit('reset')" :title="$t('admin.articles.canvas_editor.clear_all_drawings_tooltip')">
                        <i class="fa fa-times"></i>
                    </button>
                    <button type="button" class="btn btn-warning" :disabled="historyLength === 0" @click.prevent="$emit('undo')" :title="$t('admin.articles.canvas_editor.undo_tooltip', { count: historyLength })">
                        <i class="fa fa-undo"></i>
                        <span v-if="historyLength > 0" class="badge bg-dark ms-1">{{ historyLength }}</span>
                    </button>
                    <button type="button" class="btn btn-warning" :disabled="redoLength === 0" @click.prevent="$emit('redo')" :title="$t('admin.articles.canvas_editor.redo_tooltip', { count: redoLength })">
                        <i class="fa fa-repeat"></i>
                        <span v-if="redoLength > 0" class="badge bg-dark ms-1">{{ redoLength }}</span>
                    </button>
                </div>
                <div class="tool-group-label">{{ $t('admin.articles.canvas_editor.history_group_label') }}</div>
            </div>

            <div class="vr align-self-stretch my-1"></div>

            <!-- Draw tools -->
            <div class="tool-group">
                <div class="btn-group btn-group-sm">
                    <button type="button" :class="['btn', 'btn-primary', { active: action === 1 }]" @click.prevent="$emit('line')" :title="$t('admin.articles.canvas_editor.freehand_line_tooltip')">
                        <i class="fa fa-pencil"></i>
                    </button>
                    <button type="button" :class="['btn', 'btn-primary', { active: action === 20 }]" @click.prevent="$emit('continue-line')" :disabled="!hasDrawing" :title="$t('admin.articles.canvas_editor.continue_from_endpoint_tooltip')">
                        <i class="fa fa-pencil"></i><i class="fa fa-long-arrow-right" style="font-size:9px; margin-left:1px;"></i>
                    </button>
                    <button type="button" :class="['btn', 'btn-primary', { active: action === 2 }]" @click.prevent="$emit('point')" :title="$t('admin.articles.canvas_editor.point_dot_tooltip')">
                        <i class="fa fa-circle" style="font-size:8px;"></i>
                    </button>
                    <button type="button" :class="['btn', 'btn-primary', { active: action === 3 }]" @click.prevent="$emit('number')" :title="$t('admin.articles.canvas_editor.numbered_route_tooltip')">
                        <i class="fa fa-pencil"></i>&thinsp;<i class="fa fa-dot-circle-o" style="font-size:9px;"></i>
                    </button>
                    <button type="button" :class="['btn', 'btn-primary', { active: action === 7 }]" @click.prevent="$emit('combined')" :title="$t('admin.articles.canvas_editor.combined_tooltip')">
                        <span style="display:inline-flex; align-items:center; gap:2px; vertical-align:middle;">
                            <b style="font-size:10px; font-weight:bold; line-height:1;">1</b>
                            <span style="width:8px; height:2px; background:currentColor; display:inline-block;"></span>
                            <i class="fa fa-circle" style="font-size:5px;"></i>
                        </span>
                    </button>
                    <button type="button" :class="['btn', 'btn-primary', { active: action === 4 }]" @click.prevent="$emit('rectangle')" :title="$t('admin.articles.canvas_editor.rectangle_tooltip')">
                        <i class="fa fa-square-o"></i>
                    </button>
                    <button type="button" :class="['btn', 'btn-primary', { active: action === 10 }]" @click.prevent="$emit('circle')" :title="$t('admin.articles.canvas_editor.circle_tooltip')">
                        <i class="fa fa-circle-o"></i>
                    </button>
                    <button type="button" :class="['btn', 'btn-primary', { active: action === 11 }]" @click.prevent="$emit('ellipse')" :title="$t('admin.articles.canvas_editor.ellipse_tooltip')">
                        <i class="fa fa-circle-o"></i><span style="font-size:9px; margin-left:1px; line-height:1; font-style:italic;">e</span>
                    </button>
                    <button type="button" :class="['btn', 'btn-primary', { active: action === 13 }]" @click.prevent="$emit('text')" :title="$t('admin.articles.canvas_editor.text_label_tooltip')">
                        <i class="fa fa-font"></i>
                    </button>
                </div>
                <div class="tool-group-label">{{ $t('admin.articles.canvas_editor.draw_group_label') }}</div>
            </div>

            <div class="vr align-self-stretch my-1"></div>

            <!-- Edit tools -->
            <div class="tool-group">
                <div class="btn-group btn-group-sm">
                    <button type="button" :class="['btn', 'btn-warning', { active: action === 5 }]" :disabled="!hasDrawing" @click.prevent="$emit('eraser')" :title="$t('admin.articles.canvas_editor.erase_item_tooltip')">
                        <i class="fa fa-eraser"></i>
                    </button>
                    <button type="button" :class="['btn', 'btn-warning', { active: action === 8 }]" :disabled="!hasUnlockedDrawing" @click.prevent="$emit('move')" :title="$t('admin.articles.canvas_editor.move_single_item_tooltip')">
                        <i class="fa fa-arrows"></i>
                    </button>
                    <button type="button" :class="['btn', 'btn-warning', { active: action === 17 }]" :disabled="!hasUnlockedDrawing" @click.prevent="$emit('move-all')" :title="$t('admin.articles.canvas_editor.move_all_objects_tooltip')">
                        <i class="fa fa-arrows-alt"></i>
                    </button>
                    <button type="button" :class="['btn', 'btn-warning', { active: action === 14 }]" :disabled="!hasUnlockedDrawing" @click.prevent="$emit('selection')" :title="$t('admin.articles.canvas_editor.select_area_tooltip')">
                        <i class="fa fa-mouse-pointer"></i>
                    </button>
                    <button type="button" :class="['btn', 'btn-warning', { active: action === 19 }]" :disabled="!hasUnlockedDrawing" @click.prevent="$emit('resize')" :title="$t('admin.articles.canvas_editor.resize_shape_tooltip')">
                        <i class="fa fa-expand"></i>
                    </button>
                    <button type="button" :class="['btn', 'btn-warning', { active: action === 15 }]" @click.prevent="$emit('crop')" :title="$t('admin.articles.canvas_editor.crop_canvas_tooltip')">
                        <i class="fa fa-crop"></i>
                    </button>
                    <button type="button" :class="['btn', 'btn-warning', { active: action === 16 }]" :disabled="!hasDrawing" @click.prevent="$emit('edit-points')" :title="$t('admin.articles.canvas_editor.edit_path_points_tooltip')">
                        <i class="fa fa-share-alt"></i>
                    </button>
                </div>
                <div class="tool-group-label">{{ $t('admin.articles.canvas_editor.edit_group_label') }}</div>
            </div>

            <div class="vr align-self-stretch my-1"></div>

            <!-- View / Navigate -->
            <div class="tool-group">
                <div class="btn-group btn-group-sm">
                    <button type="button" class="btn btn-secondary" @click.prevent="$emit('zoom-in')" :title="$t('admin.articles.canvas_editor.zoom_in_tooltip')">
                        <i class="fa fa-search-plus"></i>
                    </button>
                    <button type="button" class="btn btn-secondary" @click.prevent="$emit('zoom-reset')" :title="$t('admin.articles.canvas_editor.reset_zoom_fit_tooltip')">
                        <i class="fa fa-search"></i>
                    </button>
                    <button type="button" class="btn btn-secondary" @click.prevent="$emit('zoom-out')" :title="$t('admin.articles.canvas_editor.zoom_out_tooltip')">
                        <i class="fa fa-search-minus"></i>
                    </button>
                    <button type="button" :class="['btn', 'btn-secondary', { active: action === 9 }]" @click.prevent="$emit('pan')" :title="$t('admin.articles.canvas_editor.pan_scroll_tooltip')">
                        <i class="fa fa-hand-paper-o"></i>
                    </button>
                </div>
                <div class="tool-group-label">{{ $t('admin.articles.canvas_editor.navigate_group_label') }}</div>
            </div>

            <div class="vr align-self-stretch my-1"></div>

            <!-- Export -->
            <div class="tool-group">
                <div class="btn-group btn-group-sm">
                    <button type="button" class="btn btn-secondary" @click.prevent="$emit('export-png')" :title="$t('admin.articles.canvas_editor.export_png_tooltip')">
                        <i class="fa fa-download"></i> PNG
                    </button>
                    <button type="button" class="btn btn-secondary" @click.prevent="$emit('export-svg')" :title="$t('admin.articles.canvas_editor.export_svg_tooltip')">
                        <i class="fa fa-download"></i> SVG
                    </button>
                    <button type="button" class="btn btn-secondary" @click.prevent="$emit('save-image')" :title="$t('admin.articles.canvas_editor.download_canvas_tooltip')">
                        <i class="fa fa-save"></i>
                    </button>
                </div>
                <div class="tool-group-label">{{ $t('admin.articles.canvas_editor.export_group_label') }}</div>
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
                1:  this.$t('admin.articles.canvas_editor.action_label_line'),
                2:  this.$t('admin.articles.canvas_editor.action_label_point'),
                3:  this.$t('admin.articles.canvas_editor.action_label_number'),
                4:  this.$t('admin.articles.canvas_editor.action_label_rectangle'),
                5:  this.$t('admin.articles.canvas_editor.action_label_eraser'),
                7:  this.$t('admin.articles.canvas_editor.action_label_combined'),
                8:  this.$t('admin.articles.canvas_editor.action_label_move_item'),
                9:  this.$t('admin.articles.canvas_editor.action_label_pan'),
                10: this.$t('admin.articles.canvas_editor.action_label_circle'),
                11: this.$t('admin.articles.canvas_editor.action_label_ellipse'),
                13: this.$t('admin.articles.canvas_editor.action_label_text'),
                14: this.$t('admin.articles.canvas_editor.action_label_select'),
                15: this.$t('admin.articles.canvas_editor.action_label_crop'),
                16: this.$t('admin.articles.canvas_editor.action_label_edit_points'),
                17: this.$t('admin.articles.canvas_editor.action_label_move_all'),
                19: this.$t('admin.articles.canvas_editor.action_label_resize'),
                20: this.$t('admin.articles.canvas_editor.action_label_continue_line'),
            };
            return labels[this.action] || this.$t('admin.articles.canvas_editor.action_label_line');
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
