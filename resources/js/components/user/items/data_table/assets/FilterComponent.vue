<template>
    <div class="col-md-12">
        <div class="cms_filters">
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
                    <option :value="0">All</option>
                    <option
                        v-for="(filter_data, key) in action_data.filter_data.data"
                        :key="key"
                        :value="filter_data.id"
                    >
                        {{ filter_data[action_data.filter_data.array_key] }}
                    </option>
                </select>
                <select
                    v-if="action_data.filter_data.action_fun_id"
                    v-model="filter_id"
                    @change="send_filter_to_tab_with_id(action_data.filter_data.action_fun_id)"
                    class="form-control"
                >
                    <option :value="0">All</option>
                    <option
                        v-for="(filter_data, key) in action_data.filter_data.data"
                        :key="key"
                        :value="filter_data.id"
                    >
                        {{ filter_data[action_data.filter_data.array_key] }}
                    </option>
                </select>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    props: ["filtr_data_prop"],
    data(){
        return{
            action_data: this.filtr_data_prop,
            filter_id: 0,
            current_filter_id: 0,
        }
    },
    watch: {
        filtr_data_prop: function(){
            this.action_data = this.filtr_data_prop
            this.filter_id = this.current_filter_id
        },
    },
    methods: {
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
    background-color: #c1c1c1;
    margin-bottom: 2%;
    padding: 10px 15px;
}
.cms_filters .col-md-3 {
    padding-right: 0;
}
.cms_filters .col-md-9 {
    padding-left: 0;
}
</style>

