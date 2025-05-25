<template>
    <div class="col-md-12">
        <div class="cms_filters">
            <div class="col-md-7">
                <h3>
                    {{ action_data.filter_data.title }}
                </h3>
            </div>
            <div class="col-md-5">
                <select
                    v-if="
                        action_data.filter_data
                            .action_fun
                    "
                    v-model="filter_id"
                    @click="
                        send_filter_to_tab(
                            action_data.filter_data
                                .action_fun
                        )
                    "
                >
                    <option :value="0">All</option>
                    <option
                        v-for="(
                            filter_data, filter_data_key
                        ) in action_data.filter_data
                            .data"
                        :key="filter_data_key"
                        :value="filter_data.id"
                    >
                        {{
                            filter_data[
                                action_data.filter_data
                                    .array_key
                            ]
                        }}
                    </option>
                </select>
                <select
                    v-if="
                        action_data.filter_data
                            .action_fun_id
                    "
                    v-model="filter_id"
                    @click="
                        send_filter_to_tab_with_id(
                            action_data.filter_data
                                .action_fun_id
                        )
                    "
                >
                    <option :value="0">All</option>
                    <option
                        v-for="(filter_data, filter_data_id_key) in action_data.filter_data.data"

                        :key="filter_data_id_key"
                        :value="filter_data.id"
                    >
                        {{
                            filter_data[
                                action_data.filter_data
                                    .array_key
                            ]
                        }}
                    </option>
                </select>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    props: [
        "filtr_data_prop"
    ],
    data(){
        return{
            action_data: this.filtr_data_prop,
            filter_id: 0,
        }
    },
    watch: {
        filtr_data_prop: function(){
            this.action_data = this.filtr_data_prop
        },
    },
    mounted() {
        this.action_data = this.filtr_data_prop
    },
    methods: {
        send_filter_to_tab_with_id(option_fun) {
            this.$emit('send_filter_to_tab_with_id', [option_fun, this.filter_id]);
        },
        send_filter_to_tab(option_fun) {
            this.$emit('send_filter_to_tab', [option_fun]);
        },
    }
}
</script>

<style>
.cms_filters {
    background-color: #c1c1c1;
    margin-bottom: 2%;
}
</style>