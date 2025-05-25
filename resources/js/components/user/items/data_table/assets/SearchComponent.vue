<template>
    <div class="col-md-12">
        <div class="cms_filters">
            <div class="input-group mb-3">
                <input
                    type="text"
                    class="form-control"
                    placeholder="Recipient's username"
                    aria-label="Recipient's username"
                    aria-describedby="basic-addon2"
                />
                <div class="input-group-append">
                    <button
                        class="btn btn-primary"
                        style="height: auto"
                        type="button"
                    >
                        Search
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
// import starsReiting from "../../../../global_components/StarReitingShowComponent.vue";

export default {
    components: {
        // starsReiting,
    },
    props: [
        "body_data_prop"
    ],
    data(){
        return{
            // tab_data: this.body_data_prop,
        }
    },
    watch: {
        // body_data_prop: function(){
        //     this.tab_data = this.body_data_prop
        // },
    },
    mounted() {
        // this.tab_data = this.body_data_prop
    },
    methods: {
        // send_action_to_tab_with_option(emit_fun) {
        //     this.$emit('action_for_perent_component_with_option', [emit_fun, this.filter_id]);
        // },

        //https://stackoverflow.com/questions/69843212/how-i-make-filter-search-in-vue-js-with-javascript
        filteredProL(){
            if(this.search === '') return this.proL; // If search input is empty then display all items
            else return this.proL.filter(e => e.toLowerCase().indexOf(this.search) > -1) // Working logic
        }
    }
}
</script>
