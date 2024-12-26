<template>
    <div class="tabs">
        <div class="row">
            <div class="col-md-12">
                <div class="row">
                    <div
                        class="col"
                        v-for="tab_action_data in table_data"
                        :key="tab_action_data.id"
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
                    <div
                        class="col-md-12"
                        v-for="first_action_data in table_data"
                        :key="first_action_data.id"

                        v-if="tab_num == first_action_data.id"
                    >
                        <div class="row mb-2" v-if="first_action_data.list_page">
                            <div class="col-md-12">
                                <a class="btn btn-primary pull-left" :href="first_action_data.list_page" target="_blank">
                                    Go to {{ first_action_data.table_name }} list
                                </a>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="add_buttom float-left">
                                    <router-link v-if="first_action_data.add_action.action == 'route'" :to="{ name: first_action_data.add_action.link }" class="btn btn-primary pull-left">Add New</router-link>
                                    <a v-else-if="first_action_data.add_action.action == 'url'" :href="first_action_data.add_action.link" class="btn btn-primary pull-left">Add New</a>
                                    <button v-else-if="first_action_data.add_action.action == 'fun'" class="btn btn-primary pull-left">Add New</button>
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
                        v-for="tab_data in table_data" :key="tab_data.id"
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

                                    <th v-for="h in tab_data.tab_data.tab.head" :key="h"> {{ h }} </th>
                                </tr>
                            </thead>
                            <tbody>   
                                <tr :class="danger_color" v-for="datas in tab_data.tab_data.data" :key="datas">
                                    <td style='text-align: center;'>
                                        <input type="checkbox">
                                    </td>
                                    
                                    <td v-for="b in tab_data.tab_data.tab.body" :key="b" >
                                        <span v-if="b[0] == 'data'">{{ datas[b[1]] }}</span> 
                                        <a v-else-if="b[0] == 'action_url'" :href="b[1]" :class="b[2]">{{ b[3] }}</a>
                                        <router-link v-else-if="b[0] == 'action_router'" :class="b[2]" :to="{ name: b[1], params: { id: datas.id } }" >{{ b[3] }}</router-link>
                                        <button v-else-if="b[0] == 'action_fun'"type="button" @click="$emit(b[1])" :class="b[2]">{{ b[3] }}</button>
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
            // show_sector_modal: false,
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
    },
};
</script>

<style scoped>
.data_tab{
    overflow-x: scroll;
}
</style>
