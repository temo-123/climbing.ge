<template>
    <div class="row view_controls_bar">
        <!-- Left: View Mode Toggle -->
        <div class="col-md-6 text-left" :class="{'pull-left': showGroupMode}">
            <div class="btn-group" :class="{'pull-left': !showGroupMode}" role="group" aria-label="View Mode">
                <button 
                    type="button" 
                    class="btn btn-sm"
                    :class="{'btn-success': viewMode === 'grid', 'btn-secondary': viewMode !== 'grid'}"
                    @click="$emit('update:viewMode', 'grid')"
                >
                    <i class="fa fa-th-large"></i> {{ $t('guide.view.grid') || 'Grid' }}
                </button>
                <button 
                    type="button" 
                    class="btn btn-sm"
                    :class="{'btn-success': viewMode === 'list', 'btn-secondary': viewMode !== 'list'}"
                    @click="$emit('update:viewMode', 'list')"
                >
                    <i class="fa fa-list-ul"></i> {{ $t('guide.view.list') || 'List' }}
                </button>
            </div>
        </div>
        
        <!-- Right: Grouping Toggle (only shown when filter is 'All') -->
        <div v-if="showGroupMode" class="col-md-6 text-right pull-right">
            <div class="btn-group pull-right" role="group" aria-label="Group Mode">
                <button 
                    type="button" 
                    class="btn btn-sm"
                    :class="{'btn-success': groupMode === 'grouped', 'btn-secondary': groupMode !== 'grouped'}"
                    @click="$emit('update:groupMode', 'grouped')"
                >
                    <i class="fa fa-folder"></i> {{ $t('guide.group.by_region') || 'By Region' }}
                </button>
                <button 
                    type="button" 
                    class="btn btn-sm"
                    :class="{'btn-success': groupMode === 'flat', 'btn-secondary': groupMode !== 'flat'}"
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
                return String(this.filterSpot) === 'All';
            }
        }
    }
</script>

<style scoped>
.view_controls_bar {
  margin-bottom: 1.5rem;
  display: flex;
  align-items: center;
  flex-wrap: wrap;
  gap: 8px;
}

.btn-group {
  display: flex;
}

.btn-group .btn {
  border-radius: 0;
  border: 1px solid #dee2e6;
  font-size: 0.8rem;
  padding: 6px 12px;
  white-space: nowrap;
}

.btn-group .btn:first-child {
  border-radius: 4px 0 0 4px;
}

.btn-group .btn:last-child {
  border-radius: 0 4px 4px 0;
}

.btn-group .btn:not(:first-child) {
  border-left: none;
}

.btn-success {
  background-color: #28a745;
  border-color: #28a745;
  color: white;

  font-size: 1.2rem !important;
}

.btn-success:hover {
  background-color: #2abc4c;
  border-color: #2bb84c;
}

.btn-secondary {
  background-color: #6c757d;
  border-color: #6c757d;
  color: white;

  font-size: 1.2rem !important;
}

.btn-secondary:hover {
  background-color: #7c8791;
  border-color: #86929c;
} 


.col-md-6.text-left,
.col-md-6.text-right {
  flex: 1;
  padding: 0;
}

.pull-left  { float: left !important; }
.pull-right { float: right !important; }

@media (max-width: 768px) {
  .view_controls_bar {
    justify-content: center;
  }

  .col-md-6.text-left,
  .col-md-6.text-right {
    flex: none;
    float: none !important;
    text-align: center !important;
  }

  .btn-group .btn {
    font-size: 0.75rem;
    padding: 5px 10px;
  }
}
</style>

