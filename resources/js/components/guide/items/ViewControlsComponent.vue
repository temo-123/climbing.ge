<template>
    <div class="view_controls_bar cg-toolbar">
        <!-- View mode -->
        <div class="cg-segmented" role="group" aria-label="View Mode">
            <button type="button" :class="{ active: viewMode === 'grid' }" @click="$emit('update:viewMode', 'grid')">
                <i class="fa fa-th-large" aria-hidden="true"></i> {{ $t('guide.view.grid') || 'Grid' }}
            </button>
            <button type="button" :class="{ active: viewMode === 'list' }" @click="$emit('update:viewMode', 'list')">
                <i class="fa fa-list-ul" aria-hidden="true"></i> {{ $t('guide.view.list') || 'List' }}
            </button>
        </div>

        <!-- Grouping (only shown when filter is 'All') -->
        <div v-if="showGroupMode" class="cg-segmented ms-auto" role="group" aria-label="Group Mode">
            <button type="button" :class="{ active: groupMode === 'grouped' }" @click="$emit('update:groupMode', 'grouped')">
                <i class="fa fa-folder-o" aria-hidden="true"></i> {{ $t('guide.group.by_region') || 'By Region' }}
            </button>
            <button type="button" :class="{ active: groupMode === 'flat' }" @click="$emit('update:groupMode', 'flat')">
                <i class="fa fa-bars" aria-hidden="true"></i> {{ $t('guide.group.flat') || 'Flat List' }}
            </button>
        </div>
    </div>
</template>

<script>
    export default {
        name: 'ViewControlsComponent',
        props: {
            viewMode: {
                type: String,
                required: true,
                validator: (value) => ['grid', 'list'].includes(value)
            },
            groupMode: {
                type: String,
                required: true,
                validator: (value) => ['grouped', 'flat'].includes(value)
            },
            filterSpot: {
                type: [String, Number],
                required: true
            }
        },
        computed: {
            showGroupMode() {
                return String(this.filterSpot) === 'All';
            }
        }
    }
</script>
