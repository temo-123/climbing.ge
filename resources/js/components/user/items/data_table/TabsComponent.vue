<template>
    <div class="tabs">
        <div class="row">
            <div class="col-md-12">
                <div class="row">
                    <div
                        class="col"
                        v-for="(tab_action_data, tab_action_data_id) in table_data"
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
                        <div class="cms_filters">
                            <div class="input-group mb-3">
                                <input type="text" class="form-control" placeholder="Recipient's username" aria-label="Recipient's username" aria-describedby="basic-addon2">
                                <div class="input-group-append">
                                    <button class="btn btn-primary" style="height: auto;" type="button">Search</button>
                                </div>
                            </div>
                        </div>
                    </div>
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
                                <a class="btn btn-primary pull-left" :href="action_data.list_page" target="_blank">
                                    Go to {{ action_data.table_name }} list
                                </a>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="add_buttom float-left">
                                    <router-link v-if="action_data.add_action.action == 'route'" :to="{ name: action_data.add_action.link }" class="btn btn-primary pull-left">Add New</router-link>
                                    <a v-else-if="action_data.add_action.action == 'url'" :href="action_data.add_action.link" class="btn btn-primary pull-left">Add New</a>
                                    <button v-else-if="action_data.add_action.action == 'fun'" class="btn btn-primary pull-left">Add New</button>
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
                        <div
                            class="row"
                        >
                            <div class="col-md-12">
                                <div class="form-groupe float-right">
                                    <button class="btn btn-danger" disabled>
                                        Del selected items
                                    </button>
                                </div>
                            </div>
                        </div>

                        <div class="row" v-if="action_data.filter_data && action_data.filter_data.data">
                            <div class="col-md-12">
                                <div class="cms_filters">
                                    <div class="col-md-7">
                                        <h3>{{ action_data.filter_data.title }}</h3>
                                    </div>
                                    <div class="col-md-5">
                                        <select v-if="action_data.filter_data.action_fun" v-model="filter_id" @click="filter_data(action_data.filter_data.action_fun)">
                                            <option :value="0">All</option>
                                            <option v-for="(filter_data, filter_data_key) in action_data.filter_data.data" :key="filter_data_key" :value="filter_data.id">
                                                {{ filter_data[action_data.filter_data.array_key] }}
                                            </option>
                                        </select>
                                        <select v-if="action_data.filter_data.action_fun_id" v-model="filter_id" @click="filter_data_with_id(action_data.filter_data.action_fun_id)">
                                            <option :value="0">All</option>
                                            <option v-for="(filter_data, filter_data_id_key) in action_data.filter_data.data" :key="filter_data_id_key" :value="filter_data.id">
                                                {{ filter_data[action_data.filter_data.array_key] }}
                                            </option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div
                        class="col-md-12 data_tab"
                        v-for="(tab_data, tab_data_id) in table_data" :key="tab_data_id"
                        v-if="tab_num == tab_data.id"
                    >
                        <table
                            class="table table-hover"
                            id="dev-table"
                        >
                            <thead>
                                <tr>
                                    <th style="text-align: center">
                                        <input type="checkbox" class="all" />
                                    </th>

                                    <th v-for="(h, h_key) in tab_data.tab_data.tab.head" :key="h_key" 
                                                v-if="
                                                    tab_data.tab_data.tab.perm[h_key][0] == 'no' || 
                                                    $can(tab_data.tab_data.tab.perm[h_key][1],  tab_data.tab_data.tab.perm[h_key][0])
                                                ">
                                        {{ h }}
                                    </th>
                                </tr>
                            </thead>
                            <tbody>   
                                <tr :class="danger_color" v-for="(datas, datas_key) in tab_data.tab_data.data" :key="datas_key">
                                    <td style='text-align: center;'>
                                        <input type="checkbox">
                                    </td>

                                    <td v-for="(b, b_key) in tab_data.tab_data.tab.body" :key="b_key" 
                                                v-if="
                                                    tab_data.tab_data.tab.perm[b_key][0] == 'no' || 
                                                    $can(tab_data.tab_data.tab.perm[b_key][1], tab_data.tab_data.tab.perm[b_key][0])
                                                ">
                                        <span v-if="b[0] == 'data'">
                                            {{ datas[b[1]] }}
                                        </span> 
                                        <a v-else-if="b[0] == 'action_url'" :href="b[1]" :class="b[2]">
                                            {{ b[3] }}
                                        </a>
                                        <router-link v-else-if="b[0] == 'action_router'" :class="b[2]" :to="{ name: b[1], params: { id: datas.id } }" >
                                            {{ b[3] }}
                                        </router-link>
                                        <button v-else-if="b[0] == 'action_fun'" type="button" @click="$emit(b[1])" :class="b[2]">
                                            {{ b[3] }}
                                        </button>
                                        <button v-else-if="b[0] == 'action_fun_id'" type="button" @click="$emit(b[1], datas.id)" :class="b[2]">
                                            {{ b[3] }}
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

    </div>
</template>

<script>

export default {
    components: {
        // tabHeader,
        // tabBody,
    },

    props: [
        "table_data"
    ],

    data() {
        return {
            tab_num: 1,
            filter_id: 0,
            danger_color: ''
        };
    },

    mounted() {
        // this.tab_num = 1;
    },

    methods: {
        update() {
            this.$emit("update");
        },
        filter_data(emit_fun){
            this.$emit(emit_fun)
        },
        filter_data_with_id(emit_fun){
            this.$emit(emit_fun, this.filter_id)
        }
    },
};
</script>

<style scoped>
    .data_tab{
        overflow-x: scroll;
    }
    .cms_filters {
        background-color: #c1c1c1;
        margin-bottom: 2%;
    }
</style>
