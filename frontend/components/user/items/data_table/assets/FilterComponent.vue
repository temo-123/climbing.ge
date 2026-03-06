<template>
    <div class="col-md-12">
        <!-- Single filter (backward compatibility) -->
        <div 
            v-if="!Array.isArray(action_data.filter_data)" 
            class="cms_filters"
        >
            <div class="col-md-3">
                <strong>{{ action_data.filter_data.title }}</strong>
            </div>
            <div class="col-md-9">
                <select
                    v-if="action_data.filter_data.action_fun"
                    v-model="filter_id"
                    @change="send_filter_to_tab(action_data.filter_data.action_fun)"
                    class="form-control"
                >
                    <option :value="0">All</option>
                    <option
                        v-for="(filter_data, key) in action_data.filter_data.data"
                        :key="key"
                        :value="getNestedValue(filter_data, action_data.filter_data.id_key || 'id')"
                    >
                        {{ getNestedValue(filter_data, action_data.filter_data.array_key) }}
                    </option>
                </select>
                <select
                    v-if="action_data.filter_data.action_fun_id"
                    v-model="filter_id"
                    @change="send_filter_to_tab_with_id(action_data.filter_data.action_fun_id)"
                    class="form-control"
                >
                    <option :value="0">All</option>
                    <option
                        v-for="(filter_data, key) in action_data.filter_data.data"
                        :key="key"
                        :value="getNestedValue(filter_data, action_data.filter_data.id_key || 'id')"
                    >
                        {{ getNestedValue(filter_data, action_data.filter_data.array_key) }}
                    </option>
                </select>
            </div>
        </div>
        
        <!-- Multiple filters (new feature) - Responsive grid layout -->
        <template v-else>
            <div 
                class="multi-filter-container"
            >
                <div 
                    v-for="(filter, index) in action_data.filter_data" 
                    :key="index"
                    :class="'cms_filters ' + getFilterClass(index)"
                >
                    <div class="filter-title">
                        <strong>{{ filter.title }}</strong>
                    </div>
                    <div class="filter-select">
                        <select
                            v-if="filter.action_fun"
                            v-model="filter_ids[index]"
                            @change="send_filter_to_tab_with_multi_id(filter.action_fun, index)"
                            class="form-control"
                        >
                            <option :value="0">All</option>
                            <option
                                v-for="(filter_data, key) in filter.data"
                                :key="key"
                                :value="getNestedValue(filter_data, filter.id_key || 'id')"
                            >
                                {{ getNestedValue(filter_data, filter.array_key) }}
                            </option>
                        </select>
                        <select
                            v-if="filter.action_fun_id"
                            v-model="filter_ids[index]"
                            @change="send_filter_to_tab_with_multi_id(filter.action_fun_id, index)"
                            class="form-control"
                        >
                            <option :value="0">All</option>
                            <option
                                v-for="(filter_data, key) in filter.data"
                                :key="key"
                                :value="getNestedValue(filter_data, filter.id_key || 'id')"
                            >
                                {{ getNestedValue(filter_data, filter.array_key) }}
                            </option>
                        </select>
                    </div>
                </div>
            </div>
        </template>
    </div>
</template>

<script>
export default {
    props: ["filtr_data_prop"],
    data(){
        return{
            action_data: this.filtr_data_prop,
            filter_id: 0,
            current_filter_id: 0,
            filter_ids: [], // Array to store multiple filter values
        }
    },
    watch: {
        filtr_data_prop: function(){
            this.action_data = this.filtr_data_prop;
            // Initialize filter_ids array based on filter_data length
            if (Array.isArray(this.action_data.filter_data)) {
                this.filter_ids = this.action_data.filter_data.map(() => 0);
            }
            this.filter_id = this.current_filter_id;
        },
    },
    methods: {
        // Helper method to get nested value from object using dot notation
        // Supports both 'title' (simple) and 'locale_data.title' (nested) keys
        getNestedValue(obj, path) {
            if (!path || !obj) return '';
            if (path.includes('.')) {
                return path.split('.').reduce((acc, part) => acc && acc[part], obj);
            }
            return obj[path];
        },
        // Get CSS class based on number of filters
        getFilterClass(index) {
            if (!Array.isArray(this.action_data.filter_data)) {
                return 'col-md-12';
            }
            
            const totalFilters = this.action_data.filter_data.length;
            
            // Responsive classes based on filter count
            switch (totalFilters) {
                case 1:
                    return 'col-md-12';
                case 2:
                    return 'col-md-6';
                case 3:
                    return 'col-md-4';
                case 4:
                    return 'col-md-3';
                default:
                    // For 5+ filters, use col-md-3 as minimum
                    return totalFilters > 4 ? 'col-md-3' : 'col-md-12';
            }
        },
        send_filter_to_tab_with_multi_id(option_fun, filter_index) {
            // Emit with filter index so parent knows which filter triggered
            this.$emit('send_filter_to_tab_with_multi_id', [option_fun, this.filter_ids[filter_index], filter_index]);
        },
        send_filter_to_tab_with_id(option_fun) {
            this.current_filter_id = this.filter_id
            this.$emit('send_filter_to_tab_with_id', [option_fun, this.filter_id]);
        },
        send_filter_to_tab(option_fun) {
            this.current_filter_id = this.filter_id
            this.$emit('send_filter_to_tab', [option_fun]);
        },
    }
}
</script>

<style>
.cms_filters {
    display: flex;
    align-items: center;
    background-color: #c1c1c1;
    margin-bottom: 10px;
    padding: 10px 15px;
    box-sizing: border-box;
}
/* Container for multiple filters - forces inline layout */
.multi-filter-container {
    display: flex;
    flex-wrap: nowrap;
    width: 100%;
    gap: 10px;
}
.multi-filter-container .cms_filters {
    margin: 0;
    flex-shrink: 0;
}
/* Responsive grid layout for multiple filters */
.cms_filters.col-md-12 {
    flex: 0 0 100%;
    max-width: 100%;
}
.cms_filters.col-md-6 {
    flex: 0 0 50%;
    max-width: 50%;
}
.cms_filters.col-md-4 {
    flex: 0 0 33.333%;
    max-width: 33.333%;
}
.cms_filters.col-md-3 {
    flex: 0 0 25%;
    max-width: 25%;
}
.cms_filters .filter-title {
    flex: 0 0 30%;
    padding-right: 10px;
    box-sizing: border-box;
}
.cms_filters .filter-select {
    flex: 1;
    min-width: 0;
}
/* Single filter backward compatibility */
.cms_filters .col-md-3 {
    padding-right: 0;
}
.cms_filters .col-md-9 {
    padding-left: 0;
}
@media (max-width: 768px) {
    .multi-filter-container {
        flex-wrap: wrap;
    }
    .cms_filters.col-md-6,
    .cms_filters.col-md-4,
    .cms_filters.col-md-3 {
        flex: 0 0 100%;
        max-width: 100%;
    }
}
</style>

