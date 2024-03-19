<template>
    <div class="col-md-12">
        <span role="alert" v-for="(error, big_name) in errors_prop" :key="big_name">
            <!-- {{ error }} -->
            <div class="alert alert-danger" v-for="(breed, name) in error" :key="name">
                {{ big_name }} -> {{ name }} -> {{ breed[0] }}
            </div>
        </span>
    </div>
</template>

<script>
    // import { editor_config } from '../../../../../mixins/editor/editor_config_mixin.js'
    export default {
        // mixins: [
        //     editor_config
        // ],
        props: [
            'errors_prop',
        ],
        data(){
            return {
                myModal: false,
            }
        },
        mounted() {
            // this.get_product_category_data()
        },
        methods: {
            showModal(){
                this.myModal = !this.myModal
            },
        }
    }
</script>