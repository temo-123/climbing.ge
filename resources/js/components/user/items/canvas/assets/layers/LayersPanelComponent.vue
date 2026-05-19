<template>
    <div class="mt-3">
        <!-- Header bar -->
        <div class="d-flex justify-content-between align-items-center mb-1">
            <button type="button" class="btn btn-sm btn-secondary" @click="toggleLayersTable">
                <i :class="showLayersTable ? 'fa fa-chevron-up' : 'fa fa-chevron-down'" class="me-1"></i>
                Layers ({{ layers.length }})
            </button>
            <div v-if="showLayersTable" class="d-flex gap-1">
                <button type="button" class="btn btn-sm btn-secondary" @click="$emit('refresh-layers')" title="Refresh list">
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
        <div v-if="showLayersTable" class="border rounded" style="max-height:280px; overflow-y:auto;">

            <div v-if="layers.length === 0" class="text-center text-muted py-3 small">
                <i class="fa fa-paint-brush me-1"></i>No items drawn yet
            </div>

            <div v-for="(layer, index) in layers" :key="layer.name + index">

                <!-- Layer row -->
                <div class="d-flex align-items-center px-2 py-1 border-bottom"
                     :class="layer.isGroup ? 'bg-primary bg-opacity-10 border-start border-3 border-primary fw-semibold' : 'bg-white'">

                    <!-- Reorder -->
                    <div class="d-flex flex-column me-1" style="gap:2px; flex-shrink:0;">
                        <button type="button" class="btn btn-sm btn-light border-0 p-0 lh-1"
                                style="width:18px; height:14px; font-size:9px;"
                                @click="$emit('move-layer-up', index)"
                                :disabled="index === 0 || layer.layerName !== layers[index-1].layerName">
                            <i class="fa fa-caret-up"></i>
                        </button>
                        <button type="button" class="btn btn-sm btn-light border-0 p-0 lh-1"
                                style="width:18px; height:14px; font-size:9px;"
                                @click="$emit('move-layer-down', index)"
                                :disabled="index === layers.length-1 || layer.layerName !== layers[index+1].layerName">
                            <i class="fa fa-caret-down"></i>
                        </button>
                    </div>

                    <!-- Expand/icon -->
                    <button v-if="layer.isGroup" type="button" class="btn btn-sm btn-link p-0 me-1 text-primary"
                            style="font-size:13px; width:18px;"
                            @click="$emit('toggle-group-expansion', layer)">
                        <i :class="layer.expanded ? 'fa fa-folder-open' : 'fa fa-folder'"></i>
                    </button>
                    <i v-else class="fa fa-file-o text-muted me-1" style="font-size:12px; width:18px; text-align:center;"></i>

                    <!-- Name -->
                    <div class="flex-grow-1 overflow-hidden me-2 small">
                        <template v-if="!layer.isText">
                            <span :contenteditable="layer.isEditing"
                                  @click="startEditingLayerName(layer, $event)"
                                  @blur="finishEditingLayerName(layer, $event)"
                                  @keydown.enter.prevent="finishEditingLayerName(layer, $event)"
                                  @keydown.esc.prevent="cancelEditingLayerName(layer)"
                                  style="cursor:pointer; outline:none;">
                                {{ layer.displayName || layer.name }}
                            </span>
                        </template>
                        <template v-else>
                            <div class="text-muted" style="font-size:10px;">{{ layer.displayName || layer.name }}</div>
                            <span v-if="!layer.isEditing"
                                  @click="startEditingText(layer)"
                                  class="text-truncate d-block"
                                  style="cursor:pointer; color:#555; font-size:11px;">
                                "{{ layer.textContent || 'Empty' }}"
                            </span>
                            <input v-else v-model="layer.editText"
                                   class="form-control form-control-sm py-0"
                                   @blur="onTextBlur(layer)"
                                   @keydown.enter.prevent="finishEditingText(layer)"
                                   @keydown.esc.prevent="cancelEditingText(layer)"
                                   :ref="'textInput-' + layer.name" />
                        </template>
                    </div>

                    <!-- Action buttons -->
                    <div class="d-flex gap-1" style="flex-shrink:0;">
                        <!-- Individual item: group actions -->
                        <template v-if="!layer.isGroup">
                            <button type="button" class="btn btn-sm btn-secondary"
                                    @click="$emit('create-group-from-layer', layer)" title="Create group">
                                <i class="fa fa-object-group" style="font-size:11px;"></i>
                            </button>
                            <button v-if="availableGroups.length > 0"
                                    type="button" class="btn btn-sm btn-secondary"
                                    @click="$emit('show-move-to-group-modal', layer)" title="Move to group">
                                <i class="fa fa-arrow-right" style="font-size:11px;"></i>
                            </button>
                        </template>

                        <!-- Group: ungroup -->
                        <button v-if="layer.isGroup"
                                type="button" class="btn btn-sm btn-warning"
                                @click="$emit('ungroup-layer', layer)" title="Ungroup">
                            <i class="fa fa-object-ungroup" style="font-size:11px;"></i>
                        </button>

                        <!-- Visibility -->
                        <button type="button" class="btn btn-sm btn-secondary"
                                @click="$emit('toggle-layer-visibility', layer)"
                                :title="layer.visible ? 'Hide' : 'Show'">
                            <i :class="layer.visible ? 'fa fa-eye text-success' : 'fa fa-eye-slash text-secondary'"
                               style="font-size:11px;"></i>
                        </button>

                        <!-- Lock -->
                        <button type="button" class="btn btn-sm btn-secondary"
                                @click="$emit('toggle-layer-lock', layer)"
                                :title="layer.locked ? 'Unlock' : 'Lock'">
                            <i :class="layer.locked ? 'fa fa-lock text-warning' : 'fa fa-unlock text-secondary'"
                               style="font-size:11px;"></i>
                        </button>

                        <!-- Delete -->
                        <button type="button" class="btn btn-sm btn-danger"
                                @click="$emit('delete-layer-item', layer)" title="Delete">
                            <i class="fa fa-trash" style="font-size:11px;"></i>
                        </button>
                    </div>
                </div>

                <!-- Group children -->
                <template v-if="layer.isGroup && layer.expanded">
                    <div v-for="child in layer.children" :key="child.name"
                         class="d-flex align-items-center px-2 py-1 border-bottom bg-white border-start border-3 border-primary border-opacity-25"
                         style="padding-left:2.5rem !important;">

                        <i class="fa fa-file-o text-muted me-1" style="font-size:12px; width:18px; text-align:center;"></i>

                        <div class="flex-grow-1 overflow-hidden me-2 small">
                            <template v-if="!child.isText">
                                <span :contenteditable="child.isEditing"
                                      @click="startEditingChildName(layer, child, $event)"
                                      @blur="finishEditingChildName(layer, child, $event)"
                                      @keydown.enter.prevent="finishEditingChildName(layer, child, $event)"
                                      @keydown.esc.prevent="cancelEditingChildName(child)"
                                      style="cursor:pointer; outline:none;">
                                    {{ child.displayName || child.name }}
                                </span>
                            </template>
                            <template v-else>
                                <div class="text-muted" style="font-size:10px;">{{ child.displayName || child.name }}</div>
                                <span v-if="!child.isEditing"
                                      @click="startEditingChildText(layer, child)"
                                      class="text-truncate d-block"
                                      style="cursor:pointer; color:#555; font-size:11px;">
                                    "{{ child.textContent || 'Empty' }}"
                                </span>
                                <input v-else v-model="child.editText"
                                       class="form-control form-control-sm py-0"
                                       @blur="onChildTextBlur(layer, child)"
                                       @keydown.enter.prevent="finishEditingChildText(layer, child)"
                                       @keydown.esc.prevent="cancelEditingChildText(child)"
                                       :ref="'childTextInput-' + child.name" />
                            </template>
                        </div>

                        <div class="d-flex gap-1" style="flex-shrink:0;">
                            <button type="button" class="btn btn-sm btn-secondary"
                                    @click="$emit('move-child-out-of-group', layer, child)" title="Move out of group">
                                <i class="fa fa-arrow-left" style="font-size:11px;"></i>
                            </button>
                            <button type="button" class="btn btn-sm btn-secondary"
                                    @click="$emit('toggle-child-visibility', layer, child)"
                                    :title="child.visible ? 'Hide' : 'Show'">
                                <i :class="child.visible ? 'fa fa-eye text-success' : 'fa fa-eye-slash text-secondary'"
                                   style="font-size:11px;"></i>
                            </button>
                            <button type="button" class="btn btn-sm btn-secondary"
                                    @click="$emit('toggle-child-lock', layer, child)"
                                    :title="child.locked ? 'Unlock' : 'Lock'">
                                <i :class="child.locked ? 'fa fa-lock text-warning' : 'fa fa-unlock text-secondary'"
                                   style="font-size:11px;"></i>
                            </button>
                            <button type="button" class="btn btn-sm btn-danger"
                                    @click="$emit('delete-child-item', layer, child)" title="Delete">
                                <i class="fa fa-trash" style="font-size:11px;"></i>
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
            default: false
        }
    },
    computed: {
        availableGroups() {
            return this.layers.filter(l => l.isGroup);
        }
    },
    methods: {
        toggleLayersTable() {
            this.$emit('toggle-layers-table');
        },

        startEditingLayerName(layer, event) {
            this.layers.forEach(l => { if (l.isEditing) this.cancelEditingLayerName(l); });
            layer.isEditing = true;
            layer.originalName = layer.name;
            this.$nextTick(() => {
                if (event && event.target) {
                    event.target.focus();
                    const range = document.createRange();
                    range.selectNodeContents(event.target);
                    const sel = window.getSelection();
                    sel.removeAllRanges();
                    sel.addRange(range);
                }
            });
        },

        finishEditingLayerName(layer, event) {
            const newName = event.target.textContent.trim();
            if (newName && newName !== layer.originalName) {
                this.$emit('finish-editing-layer-name', layer, newName);
            } else {
                layer.name = layer.originalName;
            }
            layer.isEditing = false;
            delete layer.originalName;
        },

        cancelEditingLayerName(layer) {
            layer.isEditing = false;
            delete layer.originalName;
            this.$emit('cancel-editing-layer-name');
        },

        startEditingChildName(layer, child, event) {
            this.layers.forEach(l => {
                if (l.children) l.children.forEach(c => { if (c.isEditing) this.cancelEditingChildName(c); });
            });
            child.isEditing = true;
            child.originalName = child.name;
            this.$nextTick(() => {
                if (event && event.target) {
                    event.target.focus();
                    const range = document.createRange();
                    range.selectNodeContents(event.target);
                    const sel = window.getSelection();
                    sel.removeAllRanges();
                    sel.addRange(range);
                }
            });
        },

        finishEditingChildName(layer, child, event) {
            const newName = event.target.textContent.trim();
            if (newName && newName !== child.originalName) {
                this.$emit('finish-editing-child-name', layer, child, newName);
            } else {
                child.name = child.originalName;
            }
            child.isEditing = false;
            delete child.originalName;
        },

        cancelEditingChildName(child) {
            child.isEditing = false;
            delete child.originalName;
            this.$emit('cancel-editing-child-name');
        },

        startEditingText(layer) {
            this.layers.forEach(l => { if (l.isEditing) this.cancelEditingText(l); });
            layer.isEditing = true;
            layer.originalText = layer.textContent;
            layer.editText = layer.textContent || '';
            this.$nextTick(() => {
                const ref = this.$refs['textInput-' + layer.name];
                const el = Array.isArray(ref) ? ref[0] : ref;
                if (el) { el.focus(); el.select(); }
            });
        },

        onTextBlur(layer) {
            setTimeout(() => this.finishEditingText(layer), 100);
        },

        finishEditingText(layer) {
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
                const ref = this.$refs['childTextInput-' + child.name];
                const el = Array.isArray(ref) ? ref[0] : ref;
                if (el) { el.focus(); el.select(); }
            });
        },

        onChildTextBlur(layer, child) {
            setTimeout(() => this.finishEditingChildText(layer, child), 100);
        },

        finishEditingChildText(layer, child) {
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
