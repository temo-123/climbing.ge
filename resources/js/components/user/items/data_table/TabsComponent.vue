<template>
    <div class="tabs">
        <div class="row">
            <div class="col-md-12">
                <div class="row">
                    <div
                        class="col"
                        v-for="but_data in table_data"
                        :key="but_data.id"
                    >
                        <input
                            type="radio"
                            :id="but_data.id"
                            :value="but_data.id"
                            v-model="tab_num"
                        />

                        <label :for="but_data.id">{{
                            but_data.table_name
                        }}</label>
                    </div>
                </div>

                <div class="row">
                    <div
                        class="col-md-12"
                        v-for="filtr_data in table_data"
                        :key="filtr_data.id"

                        v-if="tab_num == filtr_data.id"
                    >
                        <div class="row">
                            <div class="col-md-12">
                                <!-- <tableFilter
                                    :table_name="filtr_data.table_name"
                                    @filtred="filtring"
                                /> -->
                            </div>
                        </div>

                        <!-- <div
                            class="row"
                        >
                            <div class="col-md-12">
                                <button class="btn btn-primary pull-left">
                                    Go to {{ filtr_data.table_name }} page
                                </button>
                            </div>
                        </div> -->

                        <div class="row"><hr /></div>

                        <div
                            class="row"
                        >
                            <div class="col-md-12">
                                <div class="add_buttom float-left">
                                    <button class="btn btn-primary pull-left">Add New</button>
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
                    </div>
                </div>

                <div class="row">
                    <div
                        class="col-md-12 data_tab"
                        v-for="t_data in table_data" :key="t_data.id"
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

                                    <th v-for="h in t_data.tab_data.tab.head" :key="h">{{ h }}</th>
                                </tr>
                            </thead>
<!-- {{ t_data.tab_data.data[0] }} -->
                            <tbody>   
                                <tr :class="danger_color" v-for="data in t_data.tab_data.data" :key="data">
                                    <td style='text-align: center;'>
                                        <input type="checkbox">
                                    </td>
                                    <!-- <td>|</td> -->

                                    <!-- b - {{ b }} -->
                                        <td
                                            v-for="data_key in Object.keys(data)" :key="data_key"
                                        >
                                    <span 
                                        v-for="b in t_data.tab_data.tab.body" :key="b"
                                            v-if="data_key == b"
                                    >
                                            <!-- data_key - {{data_key}} -->
                                            <!-- {{ b }} -->

                                            {{ data.b }}
                                        </span>
                                        </td>

                                    <!-- <td v-for="data in tab_data.data" :key="data">mmm</td> -->
                                    <!-- <td>|</td> -->

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
            // show_sector_modal: false,
            danger_color: ''
        };
    },

    mounted() {
        this.tab_num = 1;
    },

    methods: {
        
        update() {
            this.$emit("update-data");
        },
    },
};
</script>

<style scoped>
.data_tab{
    overflow-x: scroll;
}
</style>
