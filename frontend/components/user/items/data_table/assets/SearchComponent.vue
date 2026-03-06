<template>
    <!-- <div class="col-md-12"> -->
        <div class="cms_filters">
            <div class="input-group mb-3">
                <input
                    type="text"
                    class="form-control"
                    placeholder="Search..."
                    aria-label="Search"
                    aria-describedby="basic-addon2"
                    v-model="search"
                    @input="onSearch"
                />
                <div class="input-group-append">
                    <button
                        class="btn btn-primary"
                        style="height: auto"
                        type="button"
                        @click="onSearch"
                    >
                        Search
                    </button>
                </div>
            </div>
        </div>
    <!-- </div> -->
</template>

<script>
export default {
    props: [
        "body_data_prop"
    ],
    data(){
        return{
            search: ''
        }
    },
    methods: {
        onSearch() {
            this.$emit('search', this.search);
        }
    }
}
</script>
