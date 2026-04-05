<template>
<tbody v-if="tab_data && tab_data.data">
        <!-- Checkbox column -->
        <tr v-for="(row, rowIndex) in tab_data.data" :key="row.id || rowIndex">
            <td style="text-align: center">
                <input 
                    type="checkbox" 
                    :value="row.id" 
                    v-model="localSelectedItems" 
                />
            </td>
            <td v-for="(cellConfig, colIndex) in tab_data.tab.body" :key="colIndex">
                <!-- Data cell -->
                <DataComponent 
                    v-if="cellConfig[0] === 'data'" 
                    :data_item_prop="cellConfig" 
                    :data_prop="row"
                />
                <!-- Action function cell -->
                <button 
                    v-else-if="cellConfig[0] === 'action_fun_id'" 
                    :class="cellConfig[2] || 'btn btn-primary'"
                    @click="$emit('action_for_perent_component', [cellConfig[1], row.id])"
                >
                    <span v-html="cellConfig[3]"></span>
                </button>
                <!-- Data action ID cell (modal trigger) -->
                <span 
                    v-else-if="cellConfig[0] === 'data_action_id'" 
                    @click="$emit('action_for_perent_component', [cellConfig[2], row.id])"
                    class="cursor-pointer hover:underline"
                >
                    <DataComponent :data_item_prop="[cellConfig[0], cellConfig[1]]" :data_prop="row" />
                </span>
                <!-- Router link cell -->
                <a 
                    v-else-if="cellConfig[0] === 'action_router'" 
                    :href="`/${cellConfig[1]}/${row.id}`" 
                    :class="cellConfig[2] || 'btn btn-primary'"
                    class="no-vue-link"
                >
                    <span v-html="cellConfig[3]"></span>
                </a>
                <!-- Default text -->
                <span v-else>
                    {{ row[cellConfig[1]] || '' }}
                </span>
            </td>
        </tr>
    </tbody>
</template>

<script>
import StarReitingShowComponent from "../../../../global_components/StarReitingShowComponent.vue";
import DataComponent from "./DataComponent.vue";

export default {
    components: {
        StarReitingShowComponent,
        DataComponent,
    },

    props: {
        body_data_prop: {
            type: Object,
            default: () => ({ data: [] })
        },
        selectedItems: {
            type: Array,
            default: () => []
        }
    },
    data(){
        return{
            tab_data: this.body_data_prop,
            localSelectedItems: [],
            danger_color: "",
        }
    },
    watch: {
        body_data_prop: function(){
            this.tab_data = this.body_data_prop
        },
        localSelectedItems(newVal) {
            this.$emit('update:selectedItems', newVal);
        },
        selectedItems(newVal) {
            this.localSelectedItems = newVal;
        }
    },
    mounted() {
        this.tab_data = this.body_data_prop
    },
    methods: {
        send_action_to_tab_with_option(emit_fun, sending_id) {
            this.$emit('action_for_perent_component_with_option', [emit_fun, sending_id]);
        },
        send_action_to_tab(emit_fun) {
            this.$emit('action_for_perent_component', [emit_fun]);
        }
    }
}
</script>

<style scoped>
.data_tab {
    overflow-x: scroll;
}
.no-vue-link {
    text-decoration: none;
    color: inherit;
}
.cursor-pointer {
    cursor: pointer;
}
.cms_filters {
    background-color: #c1c1c1;
    margin-bottom: 2%;
}
.fa_check_color{
    color: green;
    font-size: 250%;
}
.fa_times_color{
    color: red;
    font-size: 250%;
}
.fa_exclamation_color{
    color: orange;
    font-size: 250%;
}
</style>
