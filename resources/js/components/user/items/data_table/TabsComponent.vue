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
                    <SearchComponent />
                </div>

                <div class="row">
                    <div
                        class="col-md-12"
                        v-for="(action_data, action_data_id) in table_data"
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
                                    <button class="btn btn-danger" disabled>
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

                        <div class="row">
                            <PaginationComponent 
                                @next_page_pagination = next_page_pagination
                                @privies_page_pagination = privies_page_pagination
                                @nomber_page_pagination = nomber_page_pagination
                            />
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div
                        class="col-md-12 data_tab"
                        v-for="(tab_data, tab_data_id) in table_data"
                        :key="tab_data_id"
                        v-if="tab_num == tab_data.id"
                    >
                        <table class="table table-hover" id="dev-table">
                            <TabHeaderComponent :head_data_prop="tab_data.tab_data"/>
                            
                            <TabBodyComponent 
                                :body_data_prop="tab_data.tab_data"

                                @action_for_perent_component_with_option="action_for_perent_component_with_option"
                                @action_for_perent_component="action_for_perent_component"
                            />
                        </table>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <PaginationComponent 
                            @next_page_pagination = next_page_pagination
                            @privies_page_pagination = privies_page_pagination
                            @nomber_page_pagination = nomber_page_pagination
                        />
                    </div>
                </div>
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
        };
    },

    mounted() {
        // this.tab_num = 1;
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

        // next_page_pagination(){
        //     alert('next_page')
        //     // this.$emit('pagechanged', this.totalPages);
        // },
        // privies_page_pagination(){
        //     alert('provies_page')
        // },
        // nomber_page_pagination(page_id){
        //     alert(page_id + '_page')
        // },

        privies_page_pagination(){
            alert('provies_page')
            this.$emit('pagechanged', this.currentPage - 1);
        },
        nomber_page_pagination(page_id){
            alert(page_id + '_page')
            this.$emit('pagechanged', page_id);
        }, 
        next_page_pagination(){
            alert('next_page')
            this.$emit('pagechanged', this.currentPage + 1);
        },
    },
};
</script>

<style scoped>
.data_tab {
    overflow-x: scroll;
}
</style>
