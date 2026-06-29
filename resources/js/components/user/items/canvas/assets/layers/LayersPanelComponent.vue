<template>
    <div class="layers-sidebar">
        <!-- Header -->
        <div class="d-flex align-items-center justify-content-between px-2 py-1 bg-light border rounded-top border-bottom-0">
            <span class="small fw-semibold text-secondary">
                <i class="fa fa-list me-1"></i> Layers
                <span class="badge bg-secondary ms-1">{{ layers.filter(l => !l.isRelated).length }}</span>
                <span v-if="layers.some(l => l.isRelated)" class="badge bg-light text-secondary border ms-1" style="font-size:9px;">
                    +{{ layers.filter(l => l.isRelated).length }} ref
                </span>
            </span>
            <div class="d-flex gap-1">
                <button type="button" class="btn btn-sm btn-secondary" @click="$emit('refresh-layers')" title="Refresh">
                    <i class="fa fa-refresh"></i>
                </button>
                <button type="button" class="btn btn-sm btn-primary" @click="$emit('toggle-all-visibility')" title="Toggle all visibility">
                    <i class="fa fa-eye"></i>
                </button>
                <button type="button" class="btn btn-sm btn-danger" :disabled="layers.length === 0" @click="$emit('delete-all-layers')" title="Delete all">
                    <i class="fa fa-trash"></i>
                </button>
            </div>
        </div>

        <!-- Layers list -->
        <div class="border rounded-bottom layers-list">

            <div v-if="layers.length === 0" class="text-center text-muted py-4 small">
                <i class="fa fa-paint-brush d-block mb-1" style="font-size:20px;"></i>
                No items yet
            </div>

            <div v-for="(layer, index) in layers" :key="layer.id || layer.name + index">

                <!-- Single-line layer row -->
                <div class="d-flex align-items-center border-bottom layer-row px-1"
                     :class="[layer.isRelated ? 'layer-related' : (layer.isGroup ? 'layer-group' : 'layer-item'), layer.locked ? 'layer-locked' : '']"
                     :style="{ borderLeft: '3px solid ' + (layer.color || '#999') }">

                    <!-- Reorder arrows -->
                    <div v-if="!layer.isRelated" class="d-flex flex-column me-1" style="gap:1px; flex-shrink:0;">
                        <button type="button" class="btn btn-sm btn-secondary p-0 lh-1 reorder-btn"
                                @click="$emit('move-layer-up', index)"
                                :disabled="index === 0 || layer.layerName !== layers[index-1].layerName">
                            <i class="fa fa-caret-up"></i>
                        </button>
                        <button type="button" class="btn btn-sm btn-secondary p-0 lh-1 reorder-btn"
                                @click="$emit('move-layer-down', index)"
                                :disabled="index === layers.length-1 || layer.layerName !== layers[index+1].layerName">
                            <i class="fa fa-caret-down"></i>
                        </button>
                    </div>
                    <div v-else style="width:18px; flex-shrink:0;" class="me-1"></div>

                    <!-- Type icon / group toggle -->
                    <button v-if="layer.isGroup" type="button" class="btn btn-sm btn-primary p-0 me-1 layer-icon-btn"
                            @click="$emit('toggle-group-expansion', layer)">
                        <i :class="layer.expanded ? 'fa fa-folder-open' : 'fa fa-folder'"></i>
                    </button>
                    <span v-else class="me-1 layer-icon-btn text-center">
                        <i :class="'fa ' + getTypeIcon(layer.name)" :style="{ color: layer.color || '#999' }"></i>
                    </span>

                    <!-- Name (editable) -->
                    <div class="overflow-hidden small layer-name me-1" style="min-width:0; max-width:90px;">
                        <template v-if="layer.isRelated">
                            <span class="text-muted fst-italic">{{ layer.displayName }}</span>
                            <span class="badge ms-1" :style="{ background: layer.color || '#999', fontSize: '8px', verticalAlign: 'middle' }">ref</span>
                        </template>
                        <template v-else-if="!layer.isText">
                            <span v-if="!layer.isEditing"
                                  @click="startEditingLayerName(layer)"
                                  class="editable-name" :title="'Click to rename: ' + (layer.displayName || layer.name)">
                                {{ layer.displayName || layer.name }}
                            </span>
                            <input v-else v-model="layer.editName"
                                   class="form-control form-control-sm py-0"
                                   @blur="onNameBlur(layer)"
                                   @keydown.enter.prevent="finishEditingLayerName(layer)"
                                   @keydown.esc.prevent="cancelEditingLayerName(layer)"
                                   :ref="'nameInput-' + layer.id" />
                        </template>
                        <template v-else>
                            <span class="text-muted" style="font-size:9px;">text</span>
                            <span v-if="!layer.isEditing"
                                  @click="startEditingText(layer)"
                                  class="text-truncate d-block editable-name layer-text-preview"
                                  :title="'Click to edit: ' + (layer.textContent || 'Empty')">
                                "{{ layer.textContent || 'Empty' }}"
                            </span>
                            <input v-else v-model="layer.editText"
                                   class="form-control form-control-sm py-0"
                                   @blur="onTextBlur(layer)"
                                   @keydown.enter.prevent="finishEditingText(layer)"
                                   @keydown.esc.prevent="cancelEditingText(layer)"
                                   :ref="'textInput-' + layer.id" />
                        </template>
                    </div>

                    <!-- Color + size -->
                    <input type="color" :value="layer.color || '#999999'"
                           @change="!layer.isRelated && $emit('change-layer-color', layer, $event.target.value)"
                           :disabled="layer.isRelated"
                           class="layer-color-swatch me-1"
                           :title="layer.isRelated ? 'Reference route color' : 'Color'" />

                    <template v-if="!layer.isRelated">
                        <input type="number"
                               :value="layer.strokeWidth || (layer.isText ? 16 : 3)"
                               :min="layer.isText ? 6 : 1"
                               :max="layer.isText ? 120 : 20"
                               @change="$emit('change-layer-size', layer, $event.target.value)"
                               class="layer-size-input me-1"
                               :title="layer.isText ? 'Font size (pt)' : 'Stroke width (px)'" />
                    </template>

                    <!-- Push buttons to far right -->
                    <div class="flex-grow-1"></div>

                    <!-- Action buttons -->
                    <div class="d-flex gap-1" style="flex-shrink:0;">
                        <!-- Group actions -->
                        <template v-if="!layer.isRelated && !layer.isGroup">
                            <button type="button" class="btn btn-sm btn-primary layer-action-btn"
                                    @click="$emit('create-group-from-layer', layer)" title="Create group">
                                <i class="fa fa-object-group"></i>
                            </button>
                            <button v-if="availableGroups.length > 0"
                                    type="button" class="btn btn-sm btn-primary layer-action-btn"
                                    @click="$emit('show-move-to-group-modal', layer)" title="Move into group">
                                <i class="fa fa-arrow-right"></i>
                            </button>
                        </template>
                        <button v-if="!layer.isRelated && layer.isGroup"
                                type="button" class="btn btn-sm btn-warning layer-action-btn"
                                @click="$emit('ungroup-layer', layer)" title="Ungroup">
                            <i class="fa fa-object-ungroup"></i>
                        </button>

                        <!-- Visibility -->
                        <button type="button" class="btn btn-sm btn-secondary layer-action-btn"
                                @click="$emit('toggle-layer-visibility', layer)"
                                :title="layer.visible ? 'Hide' : 'Show'">
                            <i :class="layer.visible ? 'fa fa-eye' : 'fa fa-eye-slash'"></i>
                        </button>

                        <!-- Lock -->
                        <button type="button" class="btn btn-sm btn-warning layer-action-btn"
                                @click="$emit('toggle-layer-lock', layer)"
                                :title="layer.locked ? 'Unlock item' : 'Lock item'">
                            <i :class="layer.locked ? 'fa fa-lock' : 'fa fa-unlock'"></i>
                        </button>

                        <!-- Delete -->
                        <button v-if="!layer.isRelated"
                                type="button" class="btn btn-sm btn-danger layer-action-btn"
                                @click="$emit('delete-layer-item', layer)" title="Delete">
                            <i class="fa fa-trash"></i>
                        </button>
                    </div>
                </div>

                <!-- Group children (single-line, indented) -->
                <template v-if="layer.isGroup && layer.expanded">
                    <div v-for="child in layer.children" :key="child.id || child.name"
                         class="d-flex align-items-center border-bottom layer-row layer-child px-1"
                         :style="{ borderLeft: '3px solid ' + (child.color || '#999'), paddingLeft: '28px' }">

                        <span class="me-1 layer-icon-btn text-center" style="flex-shrink:0;">
                            <i :class="'fa ' + getTypeIcon(child.name)" :style="{ color: child.color || '#999' }"></i>
                        </span>

                        <div class="overflow-hidden small layer-name me-1" style="min-width:0; max-width:80px;">
                            <template v-if="!child.isText">
                                <span v-if="!child.isEditing"
                                      @click="startEditingChildName(layer, child)"
                                      class="editable-name" :title="'Click to rename: ' + (child.displayName || child.name)">
                                    {{ child.displayName || child.name }}
                                </span>
                                <input v-else v-model="child.editName"
                                       class="form-control form-control-sm py-0"
                                       @blur="onChildNameBlur(layer, child)"
                                       @keydown.enter.prevent="finishEditingChildName(layer, child)"
                                       @keydown.esc.prevent="cancelEditingChildName(child)"
                                       :ref="'childNameInput-' + child.id" />
                            </template>
                            <template v-else>
                                <span v-if="!child.isEditing"
                                      @click="startEditingChildText(layer, child)"
                                      class="text-truncate d-block editable-name layer-text-preview"
                                      :title="'Click to edit: ' + (child.textContent || 'Empty')">
                                    "{{ child.textContent || 'Empty' }}"
                                </span>
                                <input v-else v-model="child.editText"
                                       class="form-control form-control-sm py-0"
                                       @blur="onChildTextBlur(layer, child)"
                                       @keydown.enter.prevent="finishEditingChildText(layer, child)"
                                       @keydown.esc.prevent="cancelEditingChildText(child)"
                                       :ref="'childTextInput-' + child.id" />
                            </template>
                        </div>

                        <input type="color" :value="child.color || '#999999'"
                               @change="$emit('change-child-color', layer, child, $event.target.value)"
                               class="layer-color-swatch me-1" title="Color" />

                        <input type="number"
                               :value="child.strokeWidth || (child.isText ? 16 : 3)"
                               :min="child.isText ? 6 : 1"
                               :max="child.isText ? 120 : 20"
                               @change="$emit('change-child-size', layer, child, $event.target.value)"
                               class="layer-size-input me-1"
                               :title="child.isText ? 'Font size (pt)' : 'Stroke width (px)'" />

                        <div class="flex-grow-1"></div>

                        <div class="d-flex gap-1" style="flex-shrink:0;">
                            <button type="button" class="btn btn-sm btn-primary layer-action-btn"
                                    @click="$emit('move-child-out-of-group', layer, child)" title="Move out of group">
                                <i class="fa fa-arrow-left"></i>
                            </button>
                            <button type="button" class="btn btn-sm btn-secondary layer-action-btn"
                                    @click="$emit('toggle-child-visibility', layer, child)"
                                    :title="child.visible ? 'Hide' : 'Show'">
                                <i :class="child.visible ? 'fa fa-eye' : 'fa fa-eye-slash'"></i>
                            </button>
                            <button type="button" class="btn btn-sm btn-warning layer-action-btn"
                                    @click="$emit('toggle-child-lock', layer, child)"
                                    :title="child.locked ? 'Unlock' : 'Lock'">
                                <i :class="child.locked ? 'fa fa-lock' : 'fa fa-unlock'"></i>
                            </button>
                            <button type="button" class="btn btn-sm btn-danger layer-action-btn"
                                    @click="$emit('delete-child-item', layer, child)" title="Delete">
                                <i class="fa fa-trash"></i>
                            </button>
                        </div>
                    </div>
                </template>

            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: "LayersPanelComponent",
    props: {
        layers: {
            type: Array,
            default: () => []
        },
        showLayersTable: {
            type: Boolean,
            default: true
        }
    },
    emits: [
        'refresh-layers', 'toggle-all-visibility', 'delete-all-layers',
        'move-layer-up', 'move-layer-down',
        'toggle-group-expansion', 'create-group-from-layer', 'show-move-to-group-modal',
        'ungroup-layer', 'toggle-layer-visibility', 'toggle-layer-lock', 'delete-layer-item',
        'move-child-out-of-group', 'toggle-child-visibility', 'toggle-child-lock', 'delete-child-item',
        'finish-editing-layer-name', 'cancel-editing-layer-name',
        'finish-editing-child-name', 'cancel-editing-child-name',
        'finish-editing-text', 'cancel-editing-text',
        'finish-editing-child-text', 'cancel-editing-child-text',
        'change-layer-color', 'change-layer-size',
        'change-child-color', 'change-child-size',
    ],
    computed: {
        availableGroups() {
            return this.layers.filter(l => l.isGroup);
        }
    },
    methods: {
        getTypeIcon(name) {
            if (!name) return 'fa-file-o';
            if (name.startsWith('related-')) return 'fa-map-marker';
            if (name.startsWith('line')) return 'fa-minus';
            if (name.startsWith('point')) return 'fa-circle';
            if (name.startsWith('rectangle')) return 'fa-square-o';
            if (name.startsWith('circle')) return 'fa-circle-o';
            if (name.startsWith('text')) return 'fa-font';
            if (name.startsWith('polygon')) return 'fa-star';
            if (name.startsWith('ellipse')) return 'fa-ellipsis-h';
            return 'fa-file-o';
        },

        startEditingLayerName(layer) {
            this.layers.forEach(l => { if (l.isEditing) this.cancelEditingLayerName(l); });
            layer.isEditing = true;
            layer.originalName = layer.name;
            layer.editName = layer.name;
            this.$nextTick(() => {
                const ref = this.$refs['nameInput-' + layer.id];
                const el = Array.isArray(ref) ? ref[0] : ref;
                if (el) { el.focus(); el.select(); }
            });
        },

        onNameBlur(layer) {
            setTimeout(() => this.finishEditingLayerName(layer), 100);
        },

        finishEditingLayerName(layer) {
            if (!layer.isEditing) return;
            const newName = (layer.editName || '').trim();
            if (newName && newName !== layer.originalName) {
                this.$emit('finish-editing-layer-name', layer, newName);
            }
            layer.isEditing = false;
            delete layer.originalName;
            delete layer.editName;
        },

        cancelEditingLayerName(layer) {
            layer.isEditing = false;
            delete layer.originalName;
            delete layer.editName;
            this.$emit('cancel-editing-layer-name');
        },

        startEditingChildName(layer, child) {
            this.layers.forEach(l => {
                if (l.children) l.children.forEach(c => { if (c.isEditing) this.cancelEditingChildName(c); });
            });
            child.isEditing = true;
            child.originalName = child.name;
            child.editName = child.name;
            this.$nextTick(() => {
                const ref = this.$refs['childNameInput-' + child.id];
                const el = Array.isArray(ref) ? ref[0] : ref;
                if (el) { el.focus(); el.select(); }
            });
        },

        onChildNameBlur(layer, child) {
            setTimeout(() => this.finishEditingChildName(layer, child), 100);
        },

        finishEditingChildName(layer, child) {
            if (!child.isEditing) return;
            const newName = (child.editName || '').trim();
            if (newName && newName !== child.originalName) {
                this.$emit('finish-editing-child-name', layer, child, newName);
            }
            child.isEditing = false;
            delete child.originalName;
            delete child.editName;
        },

        cancelEditingChildName(child) {
            child.isEditing = false;
            delete child.originalName;
            delete child.editName;
            this.$emit('cancel-editing-child-name');
        },

        startEditingText(layer) {
            this.layers.forEach(l => { if (l.isEditing) this.cancelEditingText(l); });
            layer.isEditing = true;
            layer.originalText = layer.textContent;
            layer.editText = layer.textContent || '';
            this.$nextTick(() => {
                const ref = this.$refs['textInput-' + layer.id];
                const el = Array.isArray(ref) ? ref[0] : ref;
                if (el) { el.focus(); el.select(); }
            });
        },

        onTextBlur(layer) {
            setTimeout(() => this.finishEditingText(layer), 100);
        },

        finishEditingText(layer) {
            if (!layer.isEditing) return;
            const newText = (layer.editText || '').trim();
            if (newText !== layer.originalText) {
                this.$emit('finish-editing-text', layer, newText);
            } else {
                layer.textContent = layer.originalText;
            }
            layer.isEditing = false;
            delete layer.originalText;
            delete layer.editText;
        },

        cancelEditingText(layer) {
            layer.isEditing = false;
            delete layer.originalText;
            delete layer.editText;
            this.$emit('cancel-editing-text');
        },

        startEditingChildText(layer, child) {
            this.layers.forEach(l => {
                if (l.children) l.children.forEach(c => { if (c.isEditing) this.cancelEditingChildText(c); });
            });
            child.isEditing = true;
            child.originalText = child.textContent;
            child.editText = child.textContent || '';
            this.$nextTick(() => {
                const ref = this.$refs['childTextInput-' + child.id];
                const el = Array.isArray(ref) ? ref[0] : ref;
                if (el) { el.focus(); el.select(); }
            });
        },

        onChildTextBlur(layer, child) {
            setTimeout(() => this.finishEditingChildText(layer, child), 100);
        },

        finishEditingChildText(layer, child) {
            if (!child.isEditing) return;
            const newText = (child.editText || '').trim();
            if (newText !== child.originalText) {
                this.$emit('finish-editing-child-text', layer, child, newText);
            } else {
                child.textContent = child.originalText;
            }
            child.isEditing = false;
            delete child.originalText;
            delete child.editText;
        },

        cancelEditingChildText(child) {
            child.isEditing = false;
            delete child.originalText;
            delete child.editText;
            this.$emit('cancel-editing-child-text');
        }
    }
}
</script>

<style scoped>
.layers-sidebar {
    height: 100%;
}

.layers-list {
    overflow-y: auto;
    max-height: 540px;
    background: #fff;
}

.layer-row {
    transition: background 0.1s;
    min-height: 30px;
    padding-top: 2px;
    padding-bottom: 2px;
}

.layer-row:hover {
    background: #f0f4ff !important;
}

.layer-group {
    background: #eef2ff;
    font-weight: 600;
}

.layer-locked {
    opacity: 0.75;
}

.layer-locked .layer-name {
    text-decoration: line-through;
    text-decoration-color: #aaa;
}

.layer-child {
    background: #fafafa;
}

.layer-item {
    background: #fff;
}

.layer-related {
    background: #f5f5f5;
    opacity: 0.85;
}

.layer-icon-btn {
    width: 20px;
    font-size: 12px;
    flex-shrink: 0;
    padding: 0 !important;
    min-width: 20px;
}

.layer-action-btn {
    padding: 1px 5px !important;
    font-size: 11px !important;
    line-height: 1.4 !important;
}

.reorder-btn {
    width: 16px;
    height: 13px;
    font-size: 9px !important;
    display: flex;
    align-items: center;
    justify-content: center;
}

.layer-name {
    min-width: 0;
    font-size: 12px;
}

.editable-name {
    cursor: pointer;
    outline: none;
    display: block;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.editable-name:focus {
    background: #fff3cd;
    outline: 1px solid #ffc107;
    border-radius: 2px;
    padding: 0 2px;
}

.layer-type-label {
    font-size: 9px;
    line-height: 1;
}

.layer-text-preview {
    font-size: 11px;
    color: #555;
}

.layer-color-swatch {
    width: 22px;
    height: 22px;
    padding: 0;
    border: 1px solid #ccc;
    border-radius: 3px;
    cursor: pointer;
    flex-shrink: 0;
}

.layer-size-input {
    width: 38px;
    font-size: 11px;
    padding: 1px 3px;
    text-align: center;
    border: 1px solid #ccc;
    border-radius: 3px;
    flex-shrink: 0;
    height: 22px;
}
</style>
