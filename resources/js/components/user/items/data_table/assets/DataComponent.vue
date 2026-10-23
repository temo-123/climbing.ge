<template>
    <span v-if="data_prop">
        <span
            v-if="
                typeof data_item_prop[1] == 'object' &&
                typeof data_item_prop[1][0] == 'object' &&
                typeof data_item_prop[1][1] == 'object'
            "
        >
            <!-- ['data', [['item', key], ['item_2', key]]] -->
            {{data_prop[data_item_prop[1][0][0]] && data_prop[data_item_prop[1][0][0]][data_item_prop[1][0][1]]}}
            {{data_prop[data_item_prop[1][1][0]] && data_prop[data_item_prop[1][1][0]][data_item_prop[1][1][1]]}}
        </span>
        <span
            v-else-if="
                typeof data_item_prop[1] == 'object' &&
                typeof data_item_prop[1][0] == 'string' &&
                typeof data_item_prop[1][1] == 'string'
            "
        >
            <!-- ['data', ['item', key]] -->
            <span v-if="typeof (data_prop[data_item_prop[1][0]] && data_prop[data_item_prop[1][0]][data_item_prop[1][1]]) == 'boolean' || data_item_prop[2] == 'bool'">
                <span v-if="(data_prop[data_item_prop[1][0]] && data_prop[data_item_prop[1][0]][data_item_prop[1][1]]) == false || (data_prop[data_item_prop[1][0]] && data_prop[data_item_prop[1][0]][data_item_prop[1][1]]) == 0">
                    <i class="fa fa-times fa_times_color" aria-hidden="true"></i>
                </span>
                <span v-else-if="(data_prop[data_item_prop[1][0]] && data_prop[data_item_prop[1][0]][data_item_prop[1][1]]) == true || (data_prop[data_item_prop[1][0]] && data_prop[data_item_prop[1][0]][data_item_prop[1][1]]) == 1">
                    <i class="fa fa-check fa_check_color" aria-hidden="true"></i>
                </span>
                <span v-else>
                    <i class="fa fa-exclamation fa_exclamation_color" aria-hidden="true"></i>
                </span>
            </span>
            <span v-else>
                {{ data_prop[data_item_prop[1][0]] && data_prop[data_item_prop[1][0]][data_item_prop[1][1]] }}
            </span>
        </span>
        <span
            v-else-if="
                typeof data_item_prop[1] == 'object' &&
                typeof data_item_prop[2] == 'object'
            "
        >
            <!-- ['data', ['item'], ['item_2']] -->
                {{ data_prop[data_item_prop[1][0]] }}
                {{ data_prop[data_item_prop[2][0]] }}
        </span>
        <span v-else>
            <!-- ['data', ['item'], 'bool||boolean / bool_2||boolean_2'] -->
            <span v-if="typeof data_prop[data_item_prop[1][0]] == 'boolean' || data_item_prop[2] == 'bool'">
                <span v-if="data_prop[data_item_prop[1][0]] == false || data_prop[data_item_prop[1][0]] == null || data_prop[data_item_prop[1][0]] == 0">
                    <i class="fa fa-times fa_times_color" aria-hidden="true"></i>
                </span>
                <span v-else-if="data_prop[data_item_prop[1][0]] == true || data_prop[data_item_prop[1][0]] == 1">
                    <i class="fa fa-check fa_check_color" aria-hidden="true"></i>
                </span>
                <span v-else>
                    <i class="fa fa-exclamation fa_exclamation_color" aria-hidden="true"></i>
                </span>
            </span>
            <span v-else-if="typeof data_prop[data_item_prop[1][0]] == 'boolean_2' || data_item_prop[2] == 'bool_2'">
                <span v-if="data_prop[data_item_prop[1][0]] == false || data_prop[data_item_prop[1][0]] == null || data_prop[data_item_prop[1][0]] == 0">
                    <i class="fa fa-times fa_times_color" aria-hidden="true"></i>
                </span>
                <span v-else>
                    <i class="fa fa-check fa_check_color" aria-hidden="true"></i>
                </span>
            </span>
            <span v-else>
                {{ data_prop[data_item_prop[1][0]] }}
            </span>
        </span>
    </span>
</template>

<script>
// import starsReiting from "../../../global_components/StarReitingShowComponent.vue";

export default {
    components: {
        // starsReiting,
    },

    props: [
        "data_item_prop",
        "data_prop"
    ],

    data() {
        return {
            // tab_num: 1,
        };
    },

    mounted() {
        // this.tab_num = 1;
    },

    methods: {
        //
    },
    created() {
        //
    },
};
</script>

<style scoped>
.data_tab {
    overflow-x: scroll;
}
.cms_filters {
    background-color: #fff;
    margin-bottom: 1rem;
}
.fa_check_color{
    color: #1f9d63;
    font-size: 150%;
}
.fa_times_color{
    color: #e04848;
    font-size: 150%;
}
.fa_exclamation_color{
    color: #f2a516;
    font-size: 150%;
}
</style>
