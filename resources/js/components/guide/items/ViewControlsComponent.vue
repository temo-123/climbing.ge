<template>
    <div class="row view_controls_bar">
        <!-- Left: View Mode Toggle -->
        <div class="col-md-6 text-left" :class="{'pull-left': showGroupMode}">
            <div class="btn-group" :class="{'pull-left': !showGroupMode}" role="group" aria-label="View Mode">
                <button 
                    type="button" 
                    class="btn btn-sm"
                    :class="{'btn-success': viewMode === 'grid', 'btn-secondary-custom': viewMode !== 'grid'}"
                    @click="$emit('update:viewMode', 'grid')"
                >
                    <i class="fa fa-th-large"></i> {{ $t('guide.view.grid') || 'Grid' }}
                </button>
                <button 
                    type="button" 
                    class="btn btn-sm"
                    :class="{'btn-success': viewMode === 'list', 'btn-secondary-custom': viewMode !== 'list'}"
                    @click="$emit('update:viewMode', 'list')"
                >
                    <i class="fa fa-list-ul"></i> {{ $t('guide.view.list') || 'List' }}
                </button>
            </div>
        </div>
        
        <!-- Right: Grouping Toggle (only shown when filter is 'All') -->
        <div v-if="showGroupMode" class="col-md-6 text-right pull-right">
            <div class="btn-group" role="group" aria-label="Group Mode">
                <button 
                    type="button" 
                    class="btn btn-sm"
                    :class="{'btn-success': groupMode === 'grouped', 'btn-secondary-custom': groupMode !== 'grouped'}"
                    @click="$emit('update:groupMode', 'grouped')"
                >
                    <i class="fa fa-folder"></i> {{ $t('guide.group.by_region') || 'By Region' }}
                </button>
                <button 
                    type="button" 
                    class="btn btn-sm"
                    :class="{'btn-success': groupMode === 'flat', 'btn-secondary-custom': groupMode !== 'flat'}"
                    @click="$emit('update:groupMode', 'flat')"
                >
                    <i class="fa fa-list"></i> {{ $t('guide.group.flat') || 'Flat List' }}
                </button>
            </div>
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
                return this.filterSpot === 'All' || this.filterSpot === 'all';
            }
        }
    }
</script>

