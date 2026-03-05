<template>
    <div class="layers-panel mt-3">
        <div class="d-flex justify-content-between align-items-center mb-2">
            <button type="button" class="btn btn-sm btn-info" @click="toggleLayersTable">
                <i class="fa fa-list" aria-hidden="true"></i> {{ showLayersTable ? 'Hide' : 'Show' }} Layers Table
            </button>
            <div v-if="showLayersTable">
                <button type="button" class="btn btn-sm btn-info" @click="$emit('refresh-layers')">Refresh</button>
                <button type="button" class="btn btn-sm btn-success" @click="$emit('toggle-all-visibility')">
                    <i class="fa fa-eye" aria-hidden="true"></i> Show / Hide All
                </button>
                <button type="button" class="btn btn-sm btn-danger" @click="$emit('delete-all-layers')" :disabled="layers.length === 0">
                    <i class="fa fa-trash" aria-hidden="true"></i> Delete All
                </button>
            </div>
        </div>
        <div v-if="showLayersTable" class="layers-list" style="max-height: 300px; overflow-y: auto; background-color: #f8f9fa; border: 1px solid #dee2e6; border-radius: 4px;">
            <div v-for="(layer, index) in layers" :key="layer.name" class="layer-item" :class="{ 'group-item': layer.isGroup, 'individual-item': !layer.isGroup }">
                <div class="d-flex align-items-center justify-content-between p-2 border-bottom" :style="{ backgroundColor: layer.isGroup ? 'rgb(184 184 184)' : 'rgb(220 220 220)' }">
                    <div class="d-flex align-items-center flex-grow-1">
                        <!-- Move up/down buttons -->
                        <div class="move-buttons mr-2">
                            <button
                                type="button"
                                class="btn btn-xs btn-info p-0 mr-1"
                                @click="$emit('move-layer-up', index)"
                                :disabled="index === 0 || (index > 0 && layer.layerName !== layers[index - 1].layerName)"
                                :title="'Move up'"
                            >
                                <i class="fa fa-chevron-up"></i>
                            </button>
                            <button
                                type="button"
                                class="btn btn-xs btn-info p-0"
                                @click="$emit('move-layer-down', index)"
                                :disabled="index === layers.length - 1 || (index < layers.length - 1 && layer.layerName !== layers[index + 1].layerName)"
                                :title="'Move down'"
                            >
                                <i class="fa fa-chevron-down"></i>
                            </button>
                        </div>

                        <!-- Group expansion toggle -->
                        <button
                            v-if="layer.isGroup"
                            type="button"
                            class="btn btn-xs btn-link p-0 mr-2"
                            @click="$emit('toggle-group-expansion', layer)"
                            :title="layer.expanded ? 'Collapse group' : 'Expand group'"
                        >
                            <i :class="layer.expanded ? 'fa fa-folder-open' : 'fa fa-folder'" style="color: #007bff;"></i>
                        </button>
                        <i v-else class="fa fa-file-o mr-2" style="color: #6c757d;"></i>

                        <div v-if="!layer.isText" class="layer-name flex-grow-1">
                            <span
                                :class="{ 'font-weight-bold': layer.isGroup }"
                                @click="startEditingLayerName(layer, $event)"
                                :contenteditable="layer.isEditing"
                                @blur="finishEditingLayerName(layer, $event)"
                                @keydown.enter.prevent="finishEditingLayerName(layer, $event)"
                                @keydown.esc.prevent="cancelEditingLayerName(layer)"
                                style="cursor: pointer; outline: none; min-width: 100px;"
                            >
                                {{ layer.displayName || layer.name }}
                            </span>
                        </div>
                        <div v-else class="layer-name flex-grow-1">
                            <div class="d-flex flex-column">
                                <small class="text-muted" style="font-size: 10px; line-height: 1;">
                                    {{ layer.displayName || layer.name }}
                                </small>
                                <div v-if="!layer.isEditing" @click="startEditingText(layer)" style="cursor: pointer; font-size: 12px;">
                                    "{{ layer.textContent || 'Empty text' }}"
                                </div>
                                <input
                                    v-else
                                    v-model="layer.editText"
                                    @blur="onTextBlur(layer)"
                                    @keydown.enter.prevent="finishEditingText(layer)"
                                    @keydown.esc.prevent="cancelEditingText(layer)"
                                    :ref="'textInput-' + layer.name"
                                    class="form-control form-control-sm"
                                    style="font-size: 12px; padding: 2px 4px; height: auto;"
                                />
                            </div>
                        </div>
                    </div>

                    <div class="layer-controls d-flex">
                        <!-- Group management buttons for individual items -->
                        <div v-if="!layer.isGroup" class="group-management mr-2">
                            <button
                                type="button"
                                class="btn btn-xs btn-success"
                                @click="$emit('create-group-from-layer', layer)"
                                :title="'Create group from this layer'"
                                style="padding: 2px 6px; font-size: 11px;"
                            >
                                <i class="fa fa-object-group"></i>
                            </button>
                            <button
                                v-if="availableGroups.length > 0"
                                type="button"
                                class="btn btn-xs btn-primary ml-1"
                                @click="$emit('show-move-to-group-modal', layer)"
                                :title="'Move to group'"
                                style="padding: 2px 6px; font-size: 11px;"
                            >
                                <i class="fa fa-arrow-right"></i>
                            </button>
                        </div>

                        <!-- Ungroup button for groups -->
                        <button
                            v-if="layer.isGroup"
                            type="button"
                            class="btn btn-xs btn-warning mr-2"
                            @click="$emit('ungroup-layer', layer)"
                            :title="'Ungroup this layer'"
                            style="padding: 2px 6px; font-size: 11px;"
                        >
                            <i class="fa fa-object-ungroup"></i>
                        </button>

                        <!-- Delete button for groups -->
                        <button
                            v-if="layer.isGroup"
                            type="button"
                            class="btn btn-xs btn-danger mr-2"
                            @click="$emit('delete-layer-item', layer)"
                            title="Delete group"
                        >
                            <i class="fa fa-trash" aria-hidden="true"></i>
                        </button>

                        <!-- Standard controls -->
                        <button
                            type="button"
                            class="btn btn-xs btn-secondary mr-1"
                            @click="$emit('toggle-layer-visibility', layer)"
                            :title="layer.visible ? 'Hide item' : 'Show item'"
                        >
                            <i :class="layer.visible ? 'fa fa-eye text-success' : 'fa fa-eye-slash text-muted'"></i>
                        </button>
                        <button
                            type="button"
                            class="btn btn-xs btn-warning mr-1"
                            @click="$emit('toggle-layer-lock', layer)"
                            :disabled="layer.layerName === 'main'"
                            :title="layer.locked ? 'Unlock item' : 'Lock item'"
                        >
                            <i :class="layer.locked ? 'fa fa-lock text-warning' : 'fa fa-unlock text-muted'"></i>
                        </button>
                        <button
                            v-if="!layer.isGroup"
                            type="button"
                            class="btn btn-xs btn-danger"
                            @click="$emit('delete-layer-item', layer)"
                            title="Delete item"
                        >
                            <i class="fa fa-trash" aria-hidden="true"></i>
                        </button>
                    </div>
                </div>

                <!-- Sub-layers for groups -->
                <div v-if="layer.isGroup && layer.expanded" class="group-children">
                    <div v-for="child in layer.children" :key="child.name" class="layer-item d-flex align-items-center justify-content-between p-2 border-bottom" style="padding-left: 60px; background-color: rgb(240 240 240);">
                        <div class="d-flex align-items-center flex-grow-1">
                            <i class="fa fa-file-o mr-2" style="color: #6c757d;"></i>
                            <div v-if="!child.isText" class="layer-name flex-grow-1">
                                <span
                                    @click="startEditingChildName(layer, child, $event)"
                                    :contenteditable="child.isEditing"
                                    @blur="finishEditingChildName(layer, child, $event)"
                                    @keydown.enter.prevent="finishEditingChildName(layer, child, $event)"
                                    @keydown.esc.prevent="cancelEditingChildName(child)"
                                    style="cursor: pointer; outline: none; min-width: 100px;"
                                >
                                    {{ child.displayName || child.name }}
                                </span>
                            </div>
                            <div v-else class="layer-name flex-grow-1">
                                <div class="d-flex flex-column">
                                    <small class="text-muted" style="font-size: 10px; line-height: 1;">
                                        {{ child.displayName || child.name }}
                                    </small>
                                    <div v-if="!child.isEditing" @click="startEditingChildText(layer, child)" style="cursor: pointer; font-size: 12px;">
                                        "{{ child.textContent || 'Empty text' }}"
                                    </div>
                                    <input
                                        v-else
                                        v-model="child.editText"
                                        @blur="onChildTextBlur(layer, child)"
                                        @keydown.enter.prevent="finishEditingChildText(layer, child)"
                                        @keydown.esc.prevent="cancelEditingChildText(child)"
                                        :ref="'childTextInput-' + child.name"
                                        class="form-control form-control-sm"
                                        style="font-size: 12px; padding: 2px 4px; height: auto;"
                                    />
                                </div>
                            </div>
                        </div>
                        <div class="layer-controls d-flex">
                            <button
                                type="button"
                                class="btn btn-xs btn-primary mr-2"
                                @click="$emit('move-child-out-of-group', layer, child)"
                                :title="'Move out of group'"
                                style="padding: 2px 6px; font-size: 11px;"
                            >
                                <i class="fa fa-arrow-left"></i>
                            </button>
                            <button
                                type="button"
                                class="btn btn-xs btn-secondary mr-1"
                                @click="$emit('toggle-child-visibility', layer, child)"
                                :title="child.visible ? 'Hide item' : 'Show item'"
                            >
                                <i :class="child.visible ? 'fa fa-eye text-success' : 'fa fa-eye-slash text-muted'"></i>
                            </button>
                            <button
                                type="button"
                                class="btn btn-xs btn-warning mr-1"
                                @click="$emit('toggle-child-lock', layer, child)"
                                :title="child.locked ? 'Unlock item' : 'Lock item'"
                            >
                                <i :class="child.locked ? 'fa fa-lock text-warning' : 'fa fa-unlock text-muted'"></i>
                            </button>
                            <button
                                type="button"
                                class="btn btn-xs btn-danger"
                                @click="$emit('delete-child-item', layer, child)"
                                :title="'Delete item'"
                            >
                                <i class="fa fa-trash" aria-hidden="true"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <div v-if="layers.length === 0" class="text-center text-muted p-3">
                <i class="fa fa-info-circle mr-2"></i>No items drawn yet
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
            default: false
        }
    },
    data() {
        return {
            // Local state for group expansion
        }
    },
    computed: {
        availableGroups() {
            return this.layers.filter(layer => layer.isGroup);
        }
    },
    methods: {
        toggleLayersTable() {
            this.$emit('toggle-layers-table');
        },

        startEditingLayerName(layer, event) {
            // Stop editing any other layer first
            this.layers.forEach(l => {
                if (l.isEditing) {
                    this.cancelEditingLayerName(l);
                }
            });
            this.$set(layer, 'isEditing', true);
            this.$set(layer, 'originalName', layer.name);
            this.$nextTick(() => {
                if (event && event.target) {
                    event.target.focus();
                    // Select all text
                    const range = document.createRange();
                    range.selectNodeContents(event.target);
                    const selection = window.getSelection();
                    selection.removeAllRanges();
                    selection.addRange(range);
                }
            });
        },

        finishEditingLayerName(layer, event) {
            const newName = event.target.textContent.trim();
            if (newName && newName !== layer.originalName) {
                this.$emit('finish-editing-layer-name', layer, newName);
            } else {
                // Revert to original name if no change or empty
                layer.name = layer.originalName;
            }
            this.$set(layer, 'isEditing', false);
            delete layer.originalName;
        },

        cancelEditingLayerName(layer) {
            this.$set(layer, 'isEditing', false);
            delete layer.originalName;
            this.$emit('cancel-editing-layer-name');
        },

        startEditingChildName(layer, child, event) {
            // Stop editing any other child first
            this.layers.forEach(l => {
                if (l.children) {
                    l.children.forEach(c => {
                        if (c.isEditing) {
                            this.cancelEditingChildName(c);
                        }
                    });
                }
            });
            this.$set(child, 'isEditing', true);
            this.$set(child, 'originalName', child.name);
            this.$nextTick(() => {
                if (event && event.target) {
                    event.target.focus();
                    // Select all text
                    const range = document.createRange();
                    range.selectNodeContents(event.target);
                    const selection = window.getSelection();
                    selection.removeAllRanges();
                    selection.addRange(range);
                }
            });
        },

        finishEditingChildName(layer, child, event) {
            const newName = event.target.textContent.trim();
            if (newName && newName !== child.originalName) {
                this.$emit('finish-editing-child-name', layer, child, newName);
            } else {
                // Revert to original name if no change or empty
                child.name = child.originalName;
            }
            this.$set(child, 'isEditing', false);
            delete child.originalName;
        },

        cancelEditingChildName(child) {
            this.$set(child, 'isEditing', false);
            delete child.originalName;
            this.$emit('cancel-editing-child-name');
        },

        // Text editing methods
        startEditingText(layer) {
            // Stop editing any other layer first
            this.layers.forEach(l => {
                if (l.isEditing) {
                    this.cancelEditingText(l);
                }
            });
            this.$set(layer, 'isEditing', true);
            this.$set(layer, 'originalText', layer.textContent);
            this.$set(layer, 'editText', layer.textContent || '');
            this.$nextTick(() => {
                const inputRef = this.$refs['textInput-' + layer.name];
                if (inputRef && inputRef.length > 0) {
                    inputRef[0].focus();
                    inputRef[0].select();
                }
            });
        },

        onTextBlur(layer) {
            // Delay to allow for potential click events
            setTimeout(() => {
                this.finishEditingText(layer);
            }, 100);
        },

        finishEditingText(layer) {
            const newText = layer.editText.trim();
            if (newText !== layer.originalText) {
                this.$emit('finish-editing-text', layer, newText);
            } else {
                // Revert to original text if no change
                layer.textContent = layer.originalText;
            }
            this.$set(layer, 'isEditing', false);
            delete layer.originalText;
            delete layer.editText;
        },

        cancelEditingText(layer) {
            this.$set(layer, 'isEditing', false);
            delete layer.originalText;
            delete layer.editText;
            this.$emit('cancel-editing-text');
        },

        startEditingChildText(layer, child) {
            // Stop editing any other child first
            this.layers.forEach(l => {
                if (l.children) {
                    l.children.forEach(c => {
                        if (c.isEditing) {
                            this.cancelEditingChildText(c);
                        }
                    });
                }
            });
            this.$set(child, 'isEditing', true);
            this.$set(child, 'originalText', child.textContent);
            this.$set(child, 'editText', child.textContent || '');
            this.$nextTick(() => {
                const inputRef = this.$refs['childTextInput-' + child.name];
                if (inputRef && inputRef.length > 0) {
                    inputRef[0].focus();
                    inputRef[0].select();
                }
            });
        },

        onChildTextBlur(layer, child) {
            // Delay to allow for potential click events
            setTimeout(() => {
                this.finishEditingChildText(layer, child);
            }, 100);
        },

        finishEditingChildText(layer, child) {
            const newText = child.editText.trim();
            if (newText !== child.originalText) {
                this.$emit('finish-editing-child-text', layer, child, newText);
            } else {
                // Revert to original text if no change
                child.textContent = child.originalText;
            }
            this.$set(child, 'isEditing', false);
            delete child.originalText;
            delete child.editText;
        },

        cancelEditingChildText(child) {
            this.$set(child, 'isEditing', false);
            delete child.originalText;
            delete child.editText;
            this.$emit('cancel-editing-child-text');
        }
    }
}
</script>
