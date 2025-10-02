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
                                action_data.filter_data.data
                            "
                        >
                            <FilterComponent 
                                :filtr_data_prop="action_data"

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

                <div class="row">
                    <div
                        class="col-md-12 data_tab"
                        v-for="(tab_data, tab_data_id) in filteredTableData"
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
            tab_num: 1,
            searchQuery: '',
            currentPage: 1,
            itemsPerPage: 10,
            itemsPerPageOptions: [10, 20, 30, 50, 100, 'All'],
            selectedItems: []
        };
    },

    mounted() {
        // this.tab_num = 1;
    },
    watch: {
        tab_num() {
            this.currentPage = 1;
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
                    // Pagination logic
                    const start = (this.currentPage - 1) * this.itemsPerPage;
                    const end = start + this.itemsPerPage;
                    const paginatedData = filteredData.slice(start, end);
                    return {
                        ...tab,
                        tab_data: {
                            ...tab.tab_data,
                            data: paginatedData,
                            totalItems: filteredData.length
                        }
                    };
                });
            }
        },

    methods: {
        update() {
            this.$emit("update");
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
            if(!tab || !tab.tab_data.totalItems) return 1;
            if (this.itemsPerPage >= tab.tab_data.totalItems) return 1;
            return Math.ceil(tab.tab_data.totalItems / this.itemsPerPage);
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
</style>
