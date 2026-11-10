<template>
    <div class="tabs">
        <div v-if="loading" class="tabs-loading">
            <div class="tabs-loading-spinner"></div>
            <span>{{ $t('admin.table.loading') }}</span>
        </div>
        <div v-else>
        <div class="row">
            <div class="col-md-12">
                <div class="cg-dt-tabs" v-if="safeTableData.length > 0">
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
                        <p>{{ $t('admin.table.no_tabs') }}</p>
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
                <!-- Toolbar: list / add actions on the left, bulk actions + refresh on the right -->
                <div class="cg-dt-toolbar">
                    <div class="cg-dt-toolbar__group">
                        <a
                            v-if="currentTabData.list_page"
                            class="btn btn-outline-secondary"
                            :href="currentTabData.list_page"
                            target="_blank"
                        >
                            <i class="fa fa-external-link" aria-hidden="true"></i>
                            {{ $t('admin.table.go_to_list', { name: currentTabData.table_name }) }}
                        </a>
                        <template v-if="currentTabData.add_action">
                            <router-link
                                v-if="currentTabData.add_action.action == 'route'"
                                :to="{name: currentTabData.add_action.link, params: currentTabData.add_action.params || {}}"
                                :class="currentTabData.add_action.class"
                            >
                                <span v-if="currentTabData.add_action.btn_title">{{ currentTabData.add_action.btn_title }}</span>
                                <span v-else>{{ $t('admin.table.add_new') }}</span>
                            </router-link>
                            <a
                                v-else-if="currentTabData.add_action.action == 'url' "
                                :href="currentTabData.add_action.link"
                                :class="currentTabData.add_action.class"
                                @click="$emit(currentTabData.add_action.link)"
                            >
                                <span v-if="currentTabData.add_action.btn_title">{{ currentTabData.add_action.btn_title }}</span>
                                <span v-else>{{ $t('admin.table.add_new') }}</span>
                            </a>
                            <button
                                v-else-if="currentTabData.add_action.action == 'fun' || currentTabData.add_action.action == 'function'"
                                :class="currentTabData.add_action.class"
                                @click="$emit(currentTabData.add_action.link)"
                            >
                                <span v-if="currentTabData.add_action.btn_title">{{ currentTabData.add_action.btn_title }}</span>
                                <span v-else>{{ $t('admin.table.add_new') }}</span>
                            </button>
                        </template>
                    </div>

                    <div class="cg-dt-toolbar__group">
                        <div class="input-group w-auto bulk-actions-group" v-if="selection_functions">
                            <select
                                class="form-select"
                                v-model="bulkAction"
                                :disabled="selectedItems.length === 0"
                            >
                                <option value="delete">{{ $t('admin.table.delete_selected') }}</option>
                                <template v-if="currentTabData && currentTabData.has_published">
                                    <option value="publish">{{ $t('admin.table.publish_selected') }}</option>
                                    <option value="unpublish">{{ $t('admin.table.unpublish_selected') }}</option>
                                </template>
                            </select>
                            <button class="btn btn-danger" :disabled="selectedItems.length === 0" @click="applyBulkAction">
                                {{ $t('admin.table.apply_bulk_action') }}
                            </button>
                        </div>
                        <button
                            class="btn btn-success"
                            @click="update()"
                        >
                            <i class="fa fa-refresh" aria-hidden="true"></i>
                            {{ $t('common.refresh') }}
                        </button>
                    </div>
                </div>

                <QuickFilterComponent
                    v-if="quickFilterColumns.length"
                    :filters="quickFilterColumns"
                    v-model="quickFilters"
                />

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
                    {{ $t('admin.table.total_items', { count: getTabFilteredCount(currentTabData) }) }}
                </div>
                <!-- legend for the tab's row_marks (only rules that have a label) -->
                <span
                    v-for="mark in rowMarkLegend"
                    :key="mark.class"
                    class="cg-row-mark-chip"
                    :class="mark.class"
                >
                    <i class="cg-row-mark-chip__swatch" aria-hidden="true"></i>
                    {{ mark.label }} <strong>{{ mark.count }}</strong>
                </span>
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
                        :show-selection="selection_functions"
                        @toggle-select-all="toggleSelectAll"
                        @sort="onSort"
                    />
                    <TabBodyComponent
                        :body_data_prop="paginatedTableData[0] ? paginatedTableData[0].tab_data : currentTabData.tab_data"
                        v-model:selected-items="selectedItems"
                        :show-selection="selection_functions"
                        @action_for_perent_component_with_option="action_for_perent_component_with_option"
                        @action_for_perent_component="action_for_perent_component"
                    />
                </table>
            </div>
        </div>

        <div v-else class="row">
            <div class="col-md-12">
                <p>{{ $t('admin.table.no_data') }}</p>
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
import QuickFilterComponent from "./assets/QuickFilterComponent.vue";
import SearchComponent from "./assets/SearchComponent.vue";
import TabBodyComponent from"./assets/TabBodyComponent.vue";
import TabHeaderComponent from"./assets/TabHeaderComponent.vue";

function deepSearchValue(val, query) {
    if (val === null || val === undefined) return false;
    if (typeof val === 'string') return val.toLowerCase().includes(query);
    if (typeof val === 'number') return String(val).includes(query);
    if (typeof val === 'object') return Object.values(val).some(v => deepSearchValue(v, query));
    return false;
}

function getByPath(obj, path) {
    if (!Array.isArray(path)) return obj?.[path];
    return path.reduce((acc, key) => (acc != null ? acc[key] : undefined), obj);
}

// Same truthiness DataComponent uses for its check / cross icons
function isTruthyFlag(val) {
    return !(val === false || val === null || val === undefined || val === 0 || val === '0' || val === '');
}

// "2026-05-01 10:00:00" → Date (Safari can't parse the space form); null when empty / invalid
function toDate(val) {
    if (val === null || val === undefined || val === '') return null;
    const d = new Date(typeof val === 'string' ? val.replace(' ', 'T') : val);
    return isNaN(d.getTime()) ? null : d;
}

// One row_marks rule → does it apply to this row?
// when: 'past' | 'future' (date field vs now), 'true' | 'false' (flag field),
//       'empty' | 'filled', or a function(row) → boolean
function rowMarkMatches(rule, row) {
    if (!rule) return false;
    if (typeof rule.when === 'function') return !!rule.when(row);
    const val = getByPath(row, rule.field);
    switch (rule.when) {
        case 'past': { const d = toDate(val); return !!d && d < new Date(); }
        case 'future': { const d = toDate(val); return !!d && d >= new Date(); }
        case 'true': return isTruthyFlag(val);
        case 'false': return !isTruthyFlag(val);
        case 'empty': return val === null || val === undefined || val === '';
        case 'filled': return !(val === null || val === undefined || val === '');
    }
    return false;
}

// classes of every matching rule, plus a class the page set itself
function rowMarkClass(rules, row) {
    const classes = rules.filter(rule => rowMarkMatches(rule, row)).map(rule => rule.class || 'cg-row-muted');
    if (row && row._row_class) classes.unshift(row._row_class);
    return classes.join(' ');
}

export default {
    components: {
        FilterComponent,
        PaginationComponent,
        QuickFilterComponent,
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
        },
        selection_functions: {
            type: Boolean,
            default: true
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
            bulkAction: 'delete',
            sortColIndex: null,
            sortKey: null,
            sortDir: 'asc',
            quickFilters: {},
        };
    },

    mounted() {
        const saved = this._getStorageKey() ? parseInt(localStorage.getItem(this._getStorageKey())) : null;
        if (saved) {
            const safeData = this.safeTableData;
            if (safeData.length > 0 && safeData.some(t => t.id === saved)) {
                this.tab_num = saved;
            } else {
                this._pendingSavedTab = saved;
            }
        }
    },

    watch: {
        table_data(newVal) {
            const safeData = this.safeTableData;
            if (safeData.length > 0) {
                if (this._pendingSavedTab && safeData.some(t => t.id === this._pendingSavedTab)) {
                    this.tab_num = this._pendingSavedTab;
                    this._pendingSavedTab = null;
                    this.currentPage = 1;
                } else if (!safeData.some(t => t.id === this.tab_num)) {
                    this.tab_num = safeData[0].id;
                    this.currentPage = 1;
                }
            }

            const maxPage = this.getMaxPage();
            if (this.currentPage > maxPage) {
                this.currentPage = maxPage;
            }

            const currentTab = safeData.find(t => t.id === this.tab_num);
            if (currentTab) {
                const validIds = new Set(currentTab.tab_data.data.map(item => item.id));
                this.selectedItems = this.selectedItems.filter(id => validIds.has(id));
            }
        },
        quickFilters() {
            this.currentPage = 1;
            this.selectedItems = [];
        },
        tab_num(newTab) {
            this.currentPage = 1;
            this.searchQuery = '';
            this.sortColIndex = null;
            this.sortKey = null;
            this.sortDir = 'asc';
            this.quickFilters = {};
            this.bulkAction = 'delete';
            this.selectedItems = [];
            const key = this._getStorageKey();
            if (key && newTab) {
                localStorage.setItem(key, newTab);
            }
        }
    },

    computed: {
        effectiveItemsPerPage() {
            return this.itemsPerPage === 'All' ? Number.MAX_SAFE_INTEGER : parseInt(this.itemsPerPage)
        },
        // Auto quick filters: one All / Yes / No toggle per visible `bool` column
        // (['data', [...], 'bool' | 'bool_2']). Tabs can opt out with `quick_filters: false`.
        quickFilterColumns() {
            const tab = this.safeTableData.find(t => t.id === this.tab_num);
            if (!tab || tab.quick_filters === false) return [];
            const { head = [], body = [], perm = [] } = tab.tab_data?.tab || {};
            const rows = tab.tab_data.data;
            if (!rows.length) return [];
            const filters = [];
            body.forEach((config, index) => {
                if (!Array.isArray(config) || config[0] !== 'data') return;
                if (config[2] !== 'bool' && config[2] !== 'bool_2') return;
                if (!Array.isArray(config[1]) || typeof config[1][0] !== 'string') return;
                const p = perm[index];
                if (p && p[0] !== 'no' && p.length >= 2 && !this.$can(p[1], p[0])) return;
                const path = config[1];
                const yes = rows.filter(row => isTruthyFlag(getByPath(row, path))).length;
                filters.push({
                    key: String(index),
                    path,
                    title: head[index] || path.join('.'),
                    yes,
                    no: rows.length - yes,
                });
            });
            return filters;
        },
        safeTableData() {
            return (this.table_data || []).filter(tab => {
                if (!tab || typeof tab !== 'object' || tab.id === undefined) return false;
                if (!tab.tab_data || !Array.isArray(tab.tab_data.data)) return false;
                return tab.tab_data.data.every(item => item && typeof item === 'object');
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
                let filteredData = tab.tab_data.data.filter(item => item && typeof item === 'object');
                if (query !== '') {
                    filteredData = filteredData.filter(item => {
                        return Object.values(item).some(value => deepSearchValue(value, query));
                    });
                }
                if (tab.id === this.tab_num) {
                    const active = this.quickFilterColumns.filter(f => this.quickFilters[f.key]);
                    if (active.length) {
                        filteredData = filteredData.filter(item => active.every(f =>
                            isTruthyFlag(getByPath(item, f.path)) === (this.quickFilters[f.key] === 'yes')
                        ));
                    }
                }
                if (this.sortKey && tab.id === this.tab_num) {
                    const keyPath = this.sortKey;
                    const dir = this.sortDir;
                    filteredData = [...filteredData].sort((a, b) => {
                        const av = getByPath(a, keyPath);
                        const bv = getByPath(b, keyPath);
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
        // the current tab's row_marks rules (tab.row_marks or tab_data.tab.row_marks)
        rowMarks() {
            const tab = this.currentTabData;
            if (!tab) return [];
            const rules = tab.row_marks || tab.tab_data?.tab?.row_marks || [];
            return Array.isArray(rules) ? rules : [];
        },
        rowMarkLegend() {
            const rows = this.currentTabData?.tab_data?.data || [];
            return this.rowMarks
                .filter(rule => rule.label)
                .map(rule => ({
                    class: rule.class || 'cg-row-muted',
                    label: rule.label,
                    count: rows.filter(row => rowMarkMatches(rule, row)).length,
                }))
                .filter(mark => mark.count > 0);
        },
        paginatedTableData() {
            if (!this.currentTabData) return [];
            const perPage = this.effectiveItemsPerPage;
            const start = (this.currentPage - 1) * perPage;
            const end = start + perPage;
            let paginatedData = this.currentTabData.tab_data.data.slice(start, end);
            const rules = this.rowMarks;
            if (rules.length) {
                paginatedData = paginatedData.map(row => ({ ...row, _row_class: rowMarkClass(rules, row) }));
            }
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
        _getStorageKey() {
            return this.$route?.name ? `tab_num_${this.$route.name}` : null;
        },
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
            const keyPath = config[1];
            if (this.sortColIndex === colIndex) {
                this.sortDir = this.sortDir === 'asc' ? 'desc' : 'asc';
            } else {
                this.sortColIndex = colIndex;
                this.sortKey = keyPath;
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
        applyBulkAction() {
            if (this.selectedItems.length === 0) return;
            const ids = [...this.selectedItems];
            if (this.bulkAction === 'publish') {
                if (!confirm(this.$t('admin.table.confirm_bulk_publish', { count: ids.length }))) return;
                this.$emit('publish_selected', ids);
            } else if (this.bulkAction === 'unpublish') {
                if (!confirm(this.$t('admin.table.confirm_bulk_unpublish', { count: ids.length }))) return;
                this.$emit('unpublish_selected', ids);
            } else {
                if (!confirm(this.$t('admin.table.confirm_bulk_delete', { count: ids.length }))) return;
                this.$emit('delete_selected', ids);
            }
            this.selectedItems = [];
            this.bulkAction = 'delete';
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
    color: var(--cg-stone);
    font-size: 15px;
}
.tabs-loading-spinner {
    width: 24px;
    height: 24px;
    border: 3px solid var(--cg-line);
    border-top-color: var(--cg-accent);
    border-radius: 50%;
    animation: tabs-spin 0.8s linear infinite;
    flex-shrink: 0;
}
@keyframes tabs-spin {
    to { transform: rotate(360deg); }
}
/* Table switcher — pill tabs */
.cg-dt-tabs {
    display: flex;
    flex-wrap: wrap;
    gap: 4px;
    width: fit-content;
    max-width: 100%;
    margin-bottom: 1rem;
    padding: 4px;
    border-radius: 2rem;
    background: var(--cg-chalk-2);
}
.cg-dt-tabs .col {
    flex: 0 0 auto;
    width: auto;
    padding: 0;
}
.cg-dt-tabs label {
    margin: 0;
    padding: .5rem 1.1rem;
    border-radius: 2rem;
    background: transparent;
    color: var(--cg-stone);
    font-size: .9rem;
    font-weight: 700;
    white-space: nowrap;
}
.cg-dt-tabs label:hover {
    color: var(--cg-ink);
}
.tabs .cg-dt-tabs input[type="radio"]:checked + label {
    background: #fff;
    color: var(--cg-accent-dark);
    border: 0 !important;
    box-shadow: var(--cg-shadow-sm);
}
.search-container {
    margin-bottom: 1rem;
}
.cg-dt-toolbar {
    display: flex;
    flex-wrap: wrap;
    align-items: center;
    justify-content: space-between;
    gap: .75rem;
    margin-bottom: 1rem;
}
.cg-dt-toolbar__group {
    display: flex;
    flex-wrap: wrap;
    align-items: center;
    gap: .5rem;
}
.cg-dt-toolbar__group:empty {
    display: none;
}
.cg-dt-toolbar .btn {
    gap: .4rem;
}
.cg-dt-toolbar .bulk-actions-group .form-select {
    flex: 0 1 auto;
    width: auto;
    min-width: 11rem;
}
.search-container .form-control {
    font-size: 15px;
    padding: .7rem 1rem .7rem 2.6rem;
    height: auto;
    border-radius: 2rem;
    background: #fff url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' viewBox='0 0 24 24' fill='none' stroke='%2387939b' stroke-width='2.4' stroke-linecap='round'%3E%3Ccircle cx='11' cy='11' r='7'/%3E%3Cpath d='m20 20-3.5-3.5'/%3E%3C/svg%3E") no-repeat 1rem center;
}
.tab-item-count {
    display: inline-flex;
    margin: 0 0 1rem 0;
    padding: .3rem .85rem;
    background-color: var(--cg-accent-soft);
    border-radius: 2rem;
    font-size: .82rem;
    color: var(--cg-accent-dark);
    font-weight: 800;
}
/* row_marks — Bootstrap 5 paints cells from --bs-table-bg, so set the variables */
.table > tbody > tr.cg-row-muted {
    --bs-table-bg: var(--cg-chalk-2);
    --bs-table-color: var(--cg-muted);
    --bs-table-hover-bg: var(--cg-chalk);
    --bs-table-hover-color: var(--cg-stone);
}
.table > tbody > tr.cg-row-muted > td:first-child {
    box-shadow: inset 4px 0 0 var(--cg-line), inset 0 0 0 9999px var(--bs-table-bg-state, var(--bs-table-bg-type, var(--bs-table-accent-bg)));
}
.table > tbody > tr.cg-row-accent {
    --bs-table-bg: var(--cg-accent-soft);
    --bs-table-hover-bg: var(--cg-accent-soft);
}
.table > tbody > tr.cg-row-accent > td:first-child {
    box-shadow: inset 4px 0 0 var(--cg-accent), inset 0 0 0 9999px var(--bs-table-bg-state, var(--bs-table-bg-type, var(--bs-table-accent-bg)));
}
.cg-row-mark-chip {
    display: inline-flex;
    align-items: center;
    gap: .4rem;
    margin: 0 0 1rem .5rem;
    padding: .3rem .85rem;
    border: 1px solid var(--cg-line);
    border-radius: 2rem;
    background: #fff;
    font-size: .82rem;
    font-weight: 700;
    color: var(--cg-stone);
}
.cg-row-mark-chip__swatch {
    width: .8rem;
    height: .8rem;
    border-radius: .2rem;
    background: var(--cg-chalk-2);
    box-shadow: inset 3px 0 0 var(--cg-line);
}
.cg-row-mark-chip.cg-row-accent .cg-row-mark-chip__swatch {
    background: var(--cg-accent-soft);
    box-shadow: inset 3px 0 0 var(--cg-accent);
}
.cg-row-mark-chip.table-warning .cg-row-mark-chip__swatch { background: var(--bs-warning-bg-subtle); box-shadow: none; }
.cg-row-mark-chip.table-danger .cg-row-mark-chip__swatch { background: var(--bs-danger-bg-subtle); box-shadow: none; }
.cg-row-mark-chip.table-success .cg-row-mark-chip__swatch { background: var(--bs-success-bg-subtle); box-shadow: none; }
.multi-filter-wrapper {
    display: block;
    margin-bottom: 15px;
}
</style>
