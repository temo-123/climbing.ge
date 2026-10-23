<template>
    <div class="col-md-12">
        <!-- Single filter (backward compatibility) -->
        <div 
            v-if="action_data?.filter_data && !Array.isArray(action_data.filter_data)" 
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
                    <option :value="0">{{ $t("all") }}</option>
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
                    <option :value="0">{{ $t("all") }}</option>
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
        <template v-else-if="action_data?.filter_data">
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
                            <option :value="0">{{ $t("all") }}</option>
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
                            <option :value="0">{{ $t("all") }}</option>
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
    props: {
        filtr_data_prop: {
            type: Object,
            default: () => ({ filter_data: null })
        }
    },
    data(){
        return{
            action_data: this.filtr_data_prop,
            filter_id: 0,
            current_filter_id: 0,
            filter_ids: [], // Array to store multiple filter values
            active_tab_id: this.filtr_data_prop?.id,
        }
    },
    watch: {
        filtr_data_prop: {
            deep: true,
            handler(newVal) {
                this.action_data = newVal || { filter_data: null };

                // filtr_data_prop is the whole tab object, including tab_data.data - the
                // rows being displayed. Applying a filter mutates that same array in
                // place, which re-fires this deep watcher even though the tab itself
                // hasn't changed. Only reset the selected filter value(s) when the user
                // has actually switched to a different tab, otherwise every filter
                // selection immediately snaps back to "All".
                if (newVal?.id === this.active_tab_id) return;
                this.active_tab_id = newVal?.id;

                if (this.action_data?.filter_data && Array.isArray(this.action_data.filter_data)) {
                    this.filter_ids = this.action_data.filter_data.map(() => 0);
                }
                this.filter_id = 0;
                this.current_filter_id = 0;
            }
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
    gap: .75rem;
    background-color: #fff;
    border: 1px solid var(--cg-line);
    border-radius: var(--cg-r);
    margin-bottom: 10px;
    padding: .6rem 1rem;
    box-sizing: border-box;
}
.cms_filters strong {
    font-size: .85rem;
    color: var(--cg-stone);
}
/* Multiple filters: an even grid that always fits the content width */
.multi-filter-container {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(15rem, 1fr));
    gap: .75rem;
    width: 100%;
}
.multi-filter-container .cms_filters,
.multi-filter-container .cms_filters[class*="col-md-"] {
    flex: initial;
    width: auto;
    max-width: none;
    margin: 0;
    padding: .6rem 1rem;
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
</style>

