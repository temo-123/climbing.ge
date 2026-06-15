<template>
<tbody v-if="tab_data && tab_data.data">
        <!-- Checkbox column -->
        <tr v-for="(row, rowIndex) in tab_data.data" :key="row.id || rowIndex" :class="row._row_class || ''">
            <td style="text-align: center">
                <input
                    type="checkbox"
                    :value="row.id"
                    v-model="localSelectedItems"
                />
            </td>
                <td v-for="(cellConfig, colIndex) in (tab_data.tab?.body || [])" :key="colIndex">
                <template v-if="canShowCell(colIndex)">
                <!-- Data cell -->
                <DataComponent
                    v-if="cellConfig[0] === 'data'"
                    :data_item_prop="cellConfig"
                    :data_prop="row"
                />
                <!-- Stars cell -->
                <span v-else-if="cellConfig[0] === 'stars'">
                    <i v-for="n in 5" :key="n"
                       :class="n <= getNestedValue(row, cellConfig[1]) ? 'fa fa-star rating-color' : 'fa fa-star'"
                       aria-hidden="true"></i>
                </span>
                <!-- Action function cell -->
                <button
                    v-else-if="cellConfig[0] === 'action_fun_id'"
                    :class="cellConfig[2] || 'btn btn-primary'"
                    @click="$emit('action_for_perent_component', [cellConfig[1], row.id])"
                >
                    <span v-html="cellConfig[3]"></span>
                </button>
                <!-- Data action ID cell (clickable text with optional modal trigger) -->
                <span
                    v-else-if="cellConfig[0] === 'data_action_id'"
                    @click="onDataActionClick(cellConfig, row.id)"
                    :style="getDataActionName(cellConfig) ? 'cursor:pointer;text-decoration:underline' : ''"
                >
                    {{ getDataActionDisplay(row, cellConfig) }}
                </span>
                <!-- Router link cell -->
                <router-link
                    v-else-if="cellConfig[0] === 'action_router'"
                    :to="{ name: cellConfig[1], params: { id: row.id } }"
                    :class="cellConfig[2] || 'btn btn-primary'"
                    class="no-vue-link"
                >
                    <span v-html="cellConfig[3]"></span>
                </router-link>
                <!-- Default text -->
                <span v-else>
                    {{ row[cellConfig[1]] || '' }}
                </span>
                </template>
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
        canShowCell(colIndex) {
            const perm = this.tab_data?.tab?.perm?.[colIndex]
            if (!perm || perm[0] === 'no') return true
            if (perm.length >= 2) return this.$can(perm[1], perm[0])
            return true
        },
        getDataActionName(cellConfig) {
            // Action name is the last element if it's a string (and there's more than just the type + one field)
            // ['data_action_id', ['field']] → no action
            // ['data_action_id', ['field'], 'action'] → 'action'
            // ['data_action_id', ['field1'], ['field2'], 'action'] → 'action'
            const last = cellConfig[cellConfig.length - 1]
            return typeof last === 'string' && cellConfig.length > 2 ? last : null
        },
        getDataActionDisplay(row, cellConfig) {
            // Collect display values from all array elements in the config (skip the string action name)
            const parts = []
            for (let i = 1; i < cellConfig.length; i++) {
                if (Array.isArray(cellConfig[i])) {
                    const val = row[cellConfig[i][0]]
                    if (val != null && val !== '') parts.push(val)
                }
            }
            return parts.join(' ')
        },
        onDataActionClick(cellConfig, id) {
            const actionName = this.getDataActionName(cellConfig)
            if (actionName) {
                this.$emit('action_for_perent_component', [actionName, id])
            }
        },
        getNestedValue(row, path) {
            if (!Array.isArray(path)) return 0;
            return path.reduce((acc, key) => acc != null ? acc[key] : 0, row) || 0;
        },
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
.rating-color {
    color: #fbc634;
}
</style>
