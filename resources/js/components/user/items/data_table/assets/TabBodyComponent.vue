<template>
    <tbody v-if="tab_data && tab_data.data">
        
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
            danger_color: "",
        }
    },
    watch: {
        body_data_prop: function(){
            this.tab_data = this.body_data_prop
        },
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
        },
        toggleSelection(id) {
            let newSelected = [...this.selectedItems];
            const index = newSelected.indexOf(id);
            if (index > -1) {
                newSelected.splice(index, 1);
            } else {
                newSelected.push(id);
            }
            this.$emit('update:selectedItems', newSelected);
        },

        // https://medium.com/@obapelumi/pagination-with-vuejs-1f505ce8d15b
        setPages () {
            let numberOfPages = Math.ceil(this.posts.length / this.perPage);
            for (let index = 1; index <= numberOfPages; index++) {
                this.pages.push(index);
            }
        },
        paginate (posts) {
            let page = this.page;
            let perPage = this.perPage;
            let from = (page * perPage) - perPage;
            let to = (page * perPage);
            return  posts.slice(from, to);
        }
    }
}
</script>
