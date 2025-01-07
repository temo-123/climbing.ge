<template>
    <span>
        <span
            v-if="
                typeof data_item_prop[1] == 'object' &&
                typeof data_item_prop[1][0] == 'object' &&
                typeof data_item_prop[1][1] == 'object'
            "
        >
            <!-- ['data', [['item', key], ['item_2', key]]] -->
            {{data_prop[data_item_prop[1][0][0]][data_item_prop[1][0][1]]}}
            {{data_prop[data_item_prop[1][1][0]][data_item_prop[1][1][1]]}}
        </span>
        <span
            v-else-if="
                typeof data_item_prop[1] == 'object' &&
                typeof data_item_prop[1][0] == 'string' &&
                typeof data_item_prop[1][1] == 'string'
            "
        >
            <!-- ['data', ['item', key]] -->
            {{ data_prop[data_item_prop[1][0]][data_item_prop[1][1]] }}
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
            <!-- ['data', ['item']] -->
            {{ data_prop[data_item_prop[1][0]] }}
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
    background-color: #c1c1c1;
    margin-bottom: 2%;
}
</style>
