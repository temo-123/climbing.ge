<template>
    <div class="tabs">
        <div class="row">
            <div class="col-md-12">
                <div class="row">
                    <div
                        class="col"
                        v-for="(
                            tab_action_data, tab_action_data_id
                        ) in table_data"
                        :key="tab_action_data_id"
                    >
                        <input
                            type="radio"
                            :id="tab_action_data.id"
                            :value="tab_action_data.id"
                            v-model="tab_num"
                        />
                        <label :for="tab_action_data.id">
                            {{ tab_action_data.table_name }}
                        </label>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <div class="search-container">
                            <SearchComponent @search="onSearch" />
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div
                        class="col-md-12"
                        v-for="(action_data, action_data_id) in filteredTableData"
                        :key="action_data_id"
                        v-if="tab_num == action_data.id"
                    >
                        <div class="row mb-2" v-if="action_data.list_page">
                            <div class="col-md-12">
                                <a
                                    class="btn btn-primary pull-left"
                                    :href="action_data.list_page"
                                    target="_blank"
                                >
                                    Go to {{ action_data.table_name }} list
                                </a>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div
                                    class="add_buttom float-left"
                                    v-if="action_data.add_action"
                                >
                                    <router-link
                                        v-if="action_data.add_action.action == 'route'"
                                        :to="{name: action_data.add_action.link,}"
                                        :class="action_data.add_action.class"
                                    >
                                        <span v-if="action_data.add_action.btn_title">{{ action_data.add_action.btn_title }}</span>
                                        <span v-else>Add New</span>
                                    </router-link>
                                    <a
                                        v-else-if="action_data.add_action.action == 'url' "
                                        :href="action_data.add_action.link"
                                        :class="action_data.add_action.class"
                                        @click="$emit(action_data.add_action.link)"
                                    >
                                        <span v-if="action_data.add_action.btn_title">{{ action_data.add_action.btn_title }}</span>
                                        <span v-else>Add New</span>
                                    </a>
                                    <button
                                        v-else-if="action_data.add_action.action == 'fun' || action_data.add_action.action == 'function'"
                                        :class="action_data.add_action.class"
                                        @click="$emit(action_data.add_action.link)"
                                    >
                                        <span v-if="action_data.add_action.btn_title">{{ action_data.add_action.btn_title }}</span>
                                        <span v-else>Add New</span>
                                    </button>
                                </div>

                                <div class="form-groupe float-right">
                                    <button
                                        class="btn btn-success"
                                        @click="update()"
                                    >
                                        Refresh
                                    </button>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-groupe float-right">
                                    <button class="btn btn-danger" :disabled="selectedItems.length === 0" @click="deleteSelected">
                                        Del selected items
                                    </button>
                                </div>
                            </div>
                        </div>

                        <div
                            class="row"
                            v-if="
                                action_data.filter_data &&
                                action_data.filter_data.data &&
                                !Array.isArray(action_data.filter_data)
                            "
                        >
                            <FilterComponent 
                                :filtr_data_prop="action_data"

                                @send_filter_to_tab_with_id="filter_data_with_id"
                                @send_filter_to_tab="filter_data"
                            />
                        </div>

                        <!-- Multiple filters -->
                        <div
                            class="multi-filter-wrapper"
                            style="width: 100%;"
                            v-if="
                                action_data.filter_data &&
                                Array.isArray(action_data.filter_data)
                            "
                        >
                            <FilterComponent 
                                :filtr_data_prop="action_data"

                                @send_filter_to_tab_with_multi_id="filter_data_with_multi_id"
                                @send_filter_to_tab_with_id="filter_data_with_id"
                                @send_filter_to_tab="filter_data"
                            />
                        </div>

                        <PaginationComponent
                            :current-page="currentPage"
                            :total-pages="getMaxPage()"
                            :itemsPerPage="itemsPerPage"
                            :itemsPerPageOptions="itemsPerPageOptions"
                            @next_page_pagination = next_page_pagination
                            @privies_page_pagination = privies_page_pagination
                            @nomber_page_pagination = nomber_page_pagination
                            @update:itemsPerPage="updateItemsPerPage"
                        />
                    </div>
                </div>
                
                <div class="row mb-2">
                    <div class="col-md-12">
                        <div
                            class="tab-item-count"
                            v-if="tab_num !== null"
                        >
                            Total items: {{ getTabFilteredCount(filteredTableData.find(t => t.id === tab_num)) }}
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div
                        class="col-md-12 data_tab"
                        v-for="(tab_data, tab_data_id) in paginatedTableData"
                        :key="tab_data_id"
                        v-if="tab_num == tab_data.id"
                    >

                        <table class="table table-hover" id="dev-table">
                            <TabHeaderComponent :head_data_prop="tab_data.tab_data" :selected-items="selectedItems" :total-items="tab_data.tab_data.data.length" @toggle-select-all="toggleSelectAll"/>
                            
                            <TabBodyComponent
                                :body_data_prop="tab_data.tab_data"
                                :selected-items.sync="selectedItems"

                                @action_for_perent_component_with_option="action_for_perent_component_with_option"
                                @action_for_perent_component="action_for_perent_component"
                            />
                        </table>
                    </div>
                </div>

                <PaginationComponent
                    :current-page="currentPage"
                    :total-pages="getMaxPage()"
                    :itemsPerPage="itemsPerPage"
                    :itemsPerPageOptions="itemsPerPageOptions"
                    @next_page_pagination = next_page_pagination
                    @privies_page_pagination = privies_page_pagination
                    @nomber_page_pagination = nomber_page_pagination
                    @update:itemsPerPage="updateItemsPerPage"
                />
            </div>
        </div>
    </div>
</template>

<script>
import FilterComponent from"./assets/FilterComponent.vue";
import PaginationComponent from"./assets/PaginationComponent.vue";
import SearchComponent from "./assets/SearchComponent.vue";
import TabBodyComponent from"./assets/TabBodyComponent.vue";
import TabHeaderComponent from"./assets/TabHeaderComponent.vue";
export default {
    components: {
        FilterComponent,
        PaginationComponent,
        TabBodyComponent,
        TabHeaderComponent,
        SearchComponent,
    },

    props: [
        "table_data"
    ],

    data() {
        return {
            tab_num: null, // Will be restored from localStorage
            currentPage: 1,
            searchQuery: '',
            itemsPerPage: 10,
            itemsPerPageOptions: [10, 20, 30, 50, 100, 'All'],
            selectedItems: [],
            stateRestored: false, // Flag to track if state has been restored
            initialLoadComplete: false, // Track if we've completed at least one restore
            routeKey: '' // Unique key for this route
        };
    },
    
    // Use beforeRouteLeave to save state when leaving the route
    beforeRouteLeave(to, from, next) {
        // Save state before leaving
        this.saveState();
        next();
    },
    
    mounted() {
        // Generate a unique key for this route
        this.routeKey = this.$route.name || this.$route.path || 'default';
        
        // Restore the active tab and its saved page from localStorage
        // If table_data is not available yet, the watcher will handle it when it arrives
        if (this.table_data && this.table_data.length > 0) {
            this.restoreTabState();
            this.initialLoadComplete = true;
        }
    },
    
    watch: {
        table_data: {
            handler() {
                // When table_data changes (e.g., after data fetch), restore tab state
                // Check if we should try to restore using route-specific key
                const savedTabStr = localStorage.getItem(this.getRouteStorageKey('activeTab'));
                const savedTab = savedTabStr ? parseInt(savedTabStr, 10) : null;
                
                // Only reset and retry restore if:
                // 1. We haven't restored yet, OR
                // 2. Our current tab_num is invalid, OR
                // 3. We have a saved tab that exists in current data
                const shouldRestore = 
                    !this.stateRestored || 
                    !this.tabExistsInData(this.tab_num) ||
                    (savedTab && this.tabExistsInData(savedTab));
                
                if (shouldRestore && this.table_data && this.table_data.length > 0) {
                    this.$nextTick(() => {
                        this.restoreTabState();
                        this.initialLoadComplete = true;
                    });
                }
            },
            deep: true,
            immediate: true
        },
        tab_num(newTab, oldTab) {
            // Save the active tab to localStorage using route-specific key
            try {
                localStorage.setItem(this.getRouteStorageKey('activeTab'), newTab.toString());
            } catch (e) {
                console.warn('localStorage not available:', e);
            }
            // Save the current page for the old tab before switching
            if (oldTab) {
                this.savePage(oldTab, this.currentPage);
            }
            // Restore the saved page for the new tab, or default to page 1
            this.currentPage = this.getSavedPage(newTab);
            this.searchQuery = '';
        }
    },
    computed: {
        filteredTableData() {
            return this.table_data.map(tab => {
                const query = this.searchQuery.trim().toLowerCase();
                let filteredData = tab.tab_data.data;
                if (query !== '') {
                    filteredData = filteredData.filter(item => {
                        return Object.values(item).some(value => {
                            if (typeof value === 'string') {
                                return value.toLowerCase().includes(query);
                            }
                            return false;
                        });
                    });
                }
                return {
                    ...tab,
                    tab_data: {
                        ...tab.tab_data,
                        data: filteredData,
                        filteredLength: filteredData.length
                    }
                };
            });
        },
        paginatedTableData() {
            return this.filteredTableData.map(tab => {
                const start = (this.currentPage - 1) * this.itemsPerPage;
                const end = start + this.itemsPerPage;
                const paginatedData = tab.tab_data.data.slice(start, end);
                return {
                    ...tab,
                    tab_data: {
                        ...tab.tab_data,
                        data: paginatedData
                    }
                };
            });
        }
    },

    methods: {
        // Generate a unique key for localStorage based on route
        getRouteStorageKey(key) {
            return `dataTable_${this.routeKey}_${key}`;
        },
        
        // Save the current state (tab and page) to localStorage
        saveState() {
            try {
                if (this.tab_num) {
                    localStorage.setItem(this.getRouteStorageKey('activeTab'), this.tab_num.toString());
                }
                if (this.tab_num) {
                    localStorage.setItem(this.getRouteStorageKey(`page_${this.tab_num}`), this.currentPage.toString());
                }
            } catch (e) {
                console.warn('localStorage not available:', e);
            }
        },
        // Generate a unique key for localStorage based on tab ID
        getStorageKey(tabId) {
            return this.getRouteStorageKey(`page_${tabId}`);
        },
        // Save current page to localStorage
        savePage(tabId, page) {
            try {
                localStorage.setItem(this.getStorageKey(tabId), page.toString());
            } catch (e) {
                console.warn('localStorage not available:', e);
            }
        },
        // Get saved page from localStorage
        getSavedPage(tabId) {
            try {
                const savedPage = localStorage.getItem(this.getStorageKey(tabId));
                return savedPage ? parseInt(savedPage, 10) : 1;
            } catch (e) {
                console.warn('localStorage not available:', e);
                return 1;
            }
        },
        // Clear all saved state for this component
        clearSavedState() {
            try {
                // Clear active tab
                localStorage.removeItem(this.getRouteStorageKey('activeTab'));
                // Clear all tab pages for this route
                const prefix = `dataTable_${this.routeKey}_page_`;
                Object.keys(localStorage).forEach(key => {
                    if (key.startsWith(prefix)) {
                        localStorage.removeItem(key);
                    }
                });
            } catch (e) {
                console.warn('localStorage not available:', e);
            }
        },
        // Helper to check if a tab exists in current table_data
        tabExistsInData(tabId) {
            if (!this.table_data || this.table_data.length === 0) return false;
            return this.table_data.some(tab => tab.id === tabId);
        },
        
        // Restore tab state from localStorage
        restoreTabState() {
            // Check if we have data to work with
            if (!this.table_data || this.table_data.length === 0) return;
            
            // Get the saved tab from localStorage using route-specific key
            const savedTabStr = localStorage.getItem(this.getRouteStorageKey('activeTab'));
            const savedTab = savedTabStr ? parseInt(savedTabStr, 10) : null;
            
            // If we have a saved tab and it exists in current data, use it
            if (savedTab && this.tabExistsInData(savedTab)) {
                this.tab_num = savedTab;
                this.currentPage = this.getSavedPage(savedTab);
                this.stateRestored = true;
                return;
            }
            
            // If we have a saved tab but it doesn't exist in current data yet,
            // DON'T default to first tab - wait for more data to load
            // Only use first tab if there's no saved tab at all
            if (!savedTab && this.table_data.length > 0) {
                const firstTab = this.table_data[0];
                if (firstTab && firstTab.tab_data && firstTab.tab_data.data && firstTab.tab_data.data.length > 0) {
                    this.tab_num = firstTab.id;
                    this.currentPage = this.getSavedPage(firstTab.id);
                    this.stateRestored = true;
                }
            }
        },
        update() {
            this.$emit("update");
        },
        // Handle multiple filter IDs from multi-filter component
        filter_data_with_multi_id(emit_fun) {
            // emit_fun = [event_name, filter_value, filter_index]
            this.$emit(emit_fun[0], emit_fun[1], emit_fun[2]);
        },
        filter_data(emit_fun) {
            this.$emit(emit_fun[0]);
        },
        filter_data_with_id(emit_fun) {
            this.$emit(emit_fun[0], emit_fun[1]);
        },
        action_for_perent_component_with_option(event){            
            this.$emit(event[0], event[1])
        },
        action_for_perent_component(event){
            this.$emit(event[0])
        },
        toggleSelectAll() {
            const tab = this.filteredTableData.find(t => t.id === this.tab_num);
            if (!tab || !tab.tab_data.data) return;
            if (this.selectedItems.length === tab.tab_data.data.length) {
                this.selectedItems = [];
            } else {
                this.selectedItems = tab.tab_data.data.map(item => item.id);
            }
            this.$nextTick(() => {
                this.$emit('update-indeterminate');
            });
        },
        deleteSelected() {
            alert('Delete selected: ' + this.selectedItems.join(', ') + '. It is just a demo. No items were actually deleted.');
        },

        privies_page_pagination(){
            if(this.currentPage > 1){
                this.currentPage--;
            }
            this.savePage(this.tab_num || 1, this.currentPage);
        },
        nomber_page_pagination(page_id){
            this.currentPage = page_id;
            this.savePage(this.tab_num || 1, this.currentPage);
        },
        next_page_pagination(){
            const maxPage = this.getMaxPage();
            if(this.currentPage < maxPage){
                this.currentPage++;
            }
            this.savePage(this.tab_num || 1, this.currentPage);
        },
        onSearch(query) {
            this.searchQuery = query;
            this.currentPage = 1; // Reset to first page on search
        },
        updateItemsPerPage(newItemsPerPage) {
            if (newItemsPerPage === 'All') {
                this.itemsPerPage = 999999;
            } else {
                this.itemsPerPage = parseInt(newItemsPerPage);
            }
            this.currentPage = 1; // Reset to first page when changing items per page
        },
        getMaxPage() {
            if(this.table_data.length === 0) return 1;
            const tab = this.filteredTableData.find(t => t.id === this.tab_num);
            if(!tab || !tab.tab_data.filteredLength) return 1;
            if (this.itemsPerPage >= tab.tab_data.filteredLength) return 1;
            return Math.ceil(tab.tab_data.filteredLength / this.itemsPerPage);
        },
        getTabFilteredCount(tabData) {
            // Find the corresponding tab in filteredTableData to get the filtered length
            const filteredTab = this.filteredTableData.find(t => t.id === tabData.id);
            if (filteredTab && filteredTab.tab_data.filteredLength !== undefined) {
                return filteredTab.tab_data.filteredLength;
            }
            return tabData.tab_data.data.length;
        }
    },
};
</script>

<style>
.search-container .form-control {
    font-size: 16px;
    padding: 10px 15px;
    height: auto;
    border-radius: 5px;
}
.tab-item-count {
    margin: 0 0 15px 0;
    padding: 8px 15px;
    background-color: #d5d5d6;
    border-radius: 4px;
    font-size: 14px;
    color: #495057;
    font-weight: 500;
}
.multi-filter-wrapper {
    display: block;
    margin-bottom: 15px;
}
</style>

