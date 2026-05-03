<template>
    <div class="tabs">
        <div v-if="loading" class="tabs-loading">
            <div class="tabs-loading-spinner"></div>
            <span>Loading...</span>
        </div>
        <div v-else>
        <div class="row">
            <div class="col-md-12">
                <div class="row" v-if="safeTableData.length > 0">
                    <div
                        class="col"
                        v-for="(
                            tab_action_data, tab_action_data_id
                        ) in safeTableData"
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
                <div v-else class="row">
                    <div class="col-md-12">
                        <p>No tabs available.</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="search-container">
                    <SearchComponent @search="onSearch" />
                </div>
            </div>
        </div>

        <div class="row" v-if="currentTabData">
            <div class="col-md-12" :key="`tab-${currentTabData.id}`">
                <div class="row mb-2" v-if="currentTabData.list_page">
                    <div class="col-md-12">
                        <a
                            class="btn btn-primary pull-left"
                            :href="currentTabData.list_page"
                            target="_blank"
                        >
                            Go to {{ currentTabData.table_name }} list
                        </a>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <div
                            class="add_buttom float-left"
                            v-if="currentTabData.add_action"
                        >
                            <router-link
                                v-if="currentTabData.add_action.action == 'route'"
                                :to="{name: currentTabData.add_action.link, params: currentTabData.add_action.params || {}}"
                                :class="currentTabData.add_action.class"
                            >
                                <span v-if="currentTabData.add_action.btn_title">{{ currentTabData.add_action.btn_title }}</span>
                                <span v-else>Add New</span>
                            </router-link>
                            <a
                                v-else-if="currentTabData.add_action.action == 'url' "
                                :href="currentTabData.add_action.link"
                                :class="currentTabData.add_action.class"
                                @click="$emit(currentTabData.add_action.link)"
                            >
                                <span v-if="currentTabData.add_action.btn_title">{{ currentTabData.add_action.btn_title }}</span>
                                <span v-else>Add New</span>
                            </a>
                            <button
                                v-else-if="currentTabData.add_action.action == 'fun' || currentTabData.add_action.action == 'function'"
                                :class="currentTabData.add_action.class"
                                @click="$emit(currentTabData.add_action.link)"
                            >
                                <span v-if="currentTabData.add_action.btn_title">{{ currentTabData.add_action.btn_title }}</span>
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
                    v-if="currentTabData.filter_data && currentTabData.filter_data.data && !Array.isArray(currentTabData.filter_data) && currentTabData.filter_data.data.length > 0"
                >
                    <FilterComponent 
                        :filtr_data_prop="currentTabData"
                        @send_filter_to_tab_with_id="filter_data_with_id"
                        @send_filter_to_tab="filter_data"
                    />
                </div>

                <div
                    class="multi-filter-wrapper"
                    style="width: 100%;"
                    v-if="currentTabData.filter_data && Array.isArray(currentTabData.filter_data) && currentTabData.filter_data.length > 0"
                >
                    <FilterComponent 
                        :filtr_data_prop="currentTabData"
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
                    @next_page_pagination = "next_page_pagination"
                    @privies_page_pagination = "privies_page_pagination"
                    @nomber_page_pagination = "nomber_page_pagination"
                    @update:itemsPerPage="updateItemsPerPage"
                />
            </div>
        </div>

        <div class="row mb-2" v-if="currentTabData">
            <div class="col-md-12">
                <div class="tab-item-count">
                    Total items: {{ getTabFilteredCount(currentTabData) }}
                </div>
            </div>
        </div>

        <div class="row" v-if="currentTabData">
            <div
                class="col-md-12 data_tab"
                :key="`table-${currentTabData.id}-${currentPage}`"
            >
                <table class="table table-hover" id="dev-table">
                    <TabHeaderComponent
                        :head_data_prop="currentTabData.tab_data"
                        :selected-items="selectedItems"
                        :total-items="currentTabData.tab_data?.data?.length || 0"
                        :sort-col-index="sortColIndex"
                        :sort-dir="sortDir"
                        @toggle-select-all="toggleSelectAll"
                        @sort="onSort"
                    />
                    <TabBodyComponent
                        :body_data_prop="paginatedTableData[0] ? paginatedTableData[0].tab_data : currentTabData.tab_data"
                        v-model:selected-items="selectedItems"
                        @action_for_perent_component_with_option="action_for_perent_component_with_option"
                        @action_for_perent_component="action_for_perent_component"
                    />
                </table>
            </div>
        </div>

        <div v-else class="row">
            <div class="col-md-12">
                <p>No data to display.</p>
            </div>
        </div>

        <PaginationComponent
            v-if="currentTabData"
            :current-page="currentPage"
            :total-pages="getMaxPage()"
            :itemsPerPage="itemsPerPage"
            :itemsPerPageOptions="itemsPerPageOptions"
            @next_page_pagination = "next_page_pagination"
            @privies_page_pagination = "privies_page_pagination"
            @nomber_page_pagination = "nomber_page_pagination"
            @update:itemsPerPage="updateItemsPerPage"
        />
        </div><!-- end v-else -->
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

    props: {
        table_data: {
            type: Array,
            default: () => []
        },
        loading: {
            type: Boolean,
            default: false
        }
    },

    data() {
        return {
            tab_num: 1,
            currentPage: 1,
            searchQuery: '',
            itemsPerPage: 10,
            itemsPerPageOptions: [10, 20, 30, 50, 100, 'All'],
            selectedItems: [],
            sortColIndex: null,
            sortKey: null,
            sortDir: 'asc',
        };
    },

    mounted() {
        // console.log('TabsComponent mounted, table_data:', this.table_data);
    },

    watch: {
        table_data(newVal) {
            const safeData = this.safeTableData;
            if (safeData.length > 0 && !this.tab_num) {
                this.tab_num = safeData[0].id;
                this.currentPage = 1;
            }
        },
        tab_num(newTab) {
            this.currentPage = 1;
            this.searchQuery = '';
            this.sortColIndex = null;
            this.sortKey = null;
            this.sortDir = 'asc';
        }
    },

    computed: {
        effectiveItemsPerPage() {
            return this.itemsPerPage === 'All' ? Number.MAX_SAFE_INTEGER : parseInt(this.itemsPerPage)
        },
        safeTableData() {
            return (this.table_data || []).filter(tab => {
                if (!tab || typeof tab !== 'object' || tab.id === undefined) return false;
                if (!tab.tab_data || !Array.isArray(tab.tab_data.data)) return false;
                return tab.tab_data.data.every(item => item && item.id !== undefined);
            }).sort((a, b) => a.id - b.id);
        },
        filteredTableData() {
            const safeData = this.safeTableData;
            if (!safeData.length) return [];
            return safeData.map(tab => {
                if (!tab || !tab.tab_data || !Array.isArray(tab.tab_data.data)) {
                    return {
                        ...tab,
                        tab_data: {
                            ...tab.tab_data,
                            data: [],
                            filteredLength: 0
                        }
                    };
                }
                const query = this.searchQuery.trim().toLowerCase();
                let filteredData = tab.tab_data.data.filter(item => item && item.id !== undefined);
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
                if (this.sortKey && tab.id === this.tab_num) {
                    const key = this.sortKey;
                    const dir = this.sortDir;
                    filteredData = [...filteredData].sort((a, b) => {
                        const av = a[key];
                        const bv = b[key];
                        if (av == null && bv == null) return 0;
                        if (av == null) return 1;
                        if (bv == null) return -1;
                        if (typeof av === 'string' && typeof bv === 'string') {
                            return dir === 'asc' ? av.localeCompare(bv) : bv.localeCompare(av);
                        }
                        if (av < bv) return dir === 'asc' ? -1 : 1;
                        if (av > bv) return dir === 'asc' ? 1 : -1;
                        return 0;
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
            }).filter(tab => tab && tab.id !== undefined);
        },
        currentTabData() {
            if (!this.tab_num) return null;
            return this.filteredTableData.find(tab => tab.id === this.tab_num) || null;
        },
        paginatedTableData() {
            if (!this.currentTabData) return [];
            const perPage = this.effectiveItemsPerPage;
            const start = (this.currentPage - 1) * perPage;
            const end = start + perPage;
            const paginatedData = this.currentTabData.tab_data.data.slice(start, end);
            return [{
                ...this.currentTabData,
                tab_data: {
                    ...this.currentTabData.tab_data,
                    data: paginatedData
                }
            }];
        }
    },

    methods: {
        update() {
            this.$emit("update");
        },
        onSort(colIndex) {
            const currentTab = this.safeTableData.find(t => t.id === this.tab_num);
            if (!currentTab) return;
            const body = currentTab.tab_data?.tab?.body;
            if (!body) return;
            const config = body[colIndex];
            if (!config) return;
            if ((config[0] !== 'data' && config[0] !== 'data_action_id') || !Array.isArray(config[1]) || !config[1][0]) return;
            const field = config[1][0];
            if (this.sortColIndex === colIndex) {
                this.sortDir = this.sortDir === 'asc' ? 'desc' : 'asc';
            } else {
                this.sortColIndex = colIndex;
                this.sortKey = field;
                this.sortDir = 'asc';
            }
            this.currentPage = 1;
        },
        filter_data_with_multi_id(emit_fun) {
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
            this.$emit(event[0], event[1])
        },
        toggleSelectAll() {
            const tabData = this.currentTabData;
            if (!tabData) return;
            if (this.selectedItems.length === tabData.tab_data.filteredLength) {
                this.selectedItems = [];
            } else {
                this.selectedItems = tabData.tab_data.data.map(item => item.id).filter(Boolean);
            }
        },
        deleteSelected() {
            if (this.selectedItems.length === 0) return;
            this.$emit('delete_selected', this.selectedItems);
            this.selectedItems = [];
        },
        privies_page_pagination(){
            if(this.currentPage > 1){
                this.currentPage--;
            }
        },
        nomber_page_pagination(page_id){
            this.currentPage = page_id;
        },
        next_page_pagination(){
            const maxPage = this.getMaxPage();
            if(this.currentPage < maxPage){
                this.currentPage++;
            }
        },
        onSearch(query) {
            this.searchQuery = query;
            this.currentPage = 1;
        },
        updateItemsPerPage(newVal) {
            this.itemsPerPage = newVal === 'All' ? 'All' : parseInt(newVal)
            this.currentPage = 1;
        },
        getMaxPage() {
            if (!this.currentTabData) return 1;
            const filteredLength = this.currentTabData.tab_data.filteredLength || 0;
            const perPage = this.effectiveItemsPerPage;
            if (perPage >= filteredLength) return 1;
            return Math.ceil(filteredLength / perPage);
        },
        getTabFilteredCount(tabData) {
            return tabData?.tab_data?.filteredLength || 0;
        }
    },
};
</script>

<style>
.tabs-loading {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 30px 15px;
    color: #6c757d;
    font-size: 15px;
}
.tabs-loading-spinner {
    width: 24px;
    height: 24px;
    border: 3px solid #dee2e6;
    border-top-color: #7427bb;
    border-radius: 50%;
    animation: tabs-spin 0.8s linear infinite;
    flex-shrink: 0;
}
@keyframes tabs-spin {
    to { transform: rotate(360deg); }
}
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
