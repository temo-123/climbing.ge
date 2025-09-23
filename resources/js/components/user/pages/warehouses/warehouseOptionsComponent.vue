<template>
    <div class="row">
        <div class="col-md-12">
            <!-- <div class="row">
                <div class="col-md-6"> -->
                    <div class="form-groupe">
                        <button class="btn btn-success float-right" @click="go_back()">Go back</button>
                    </div>
                <!-- </div>
            </div> -->
        </div>

        <div class="col-md-12">
        options
        </div>
    </div>
</template>

<script>
    // import { SlickList, SlickItem } from 'vue-slicksort'; //https://github.com/Jexordexan/vue-slicksort
    import StackModal from '@innologica/vue-stackable-modal'  //https://innologica.github.io/vue-stackable-modal/#sample-css

    export default {
        components: {
            StackModal,
            // SlickItem,
            // SlickList,
        },
        props: [
            // 'status',
        ],
        data(){
            return {
                product_options: [],
            }
        },

        mounted() {
            // this.get_activ_product_options()
            
            document.querySelector('body').style.marginLeft = '0';
            document.querySelector('.admin_page_header_navbar').style.marginLeft = '0';
        },

        methods: {
            go_back(){
                if (window.confirm('Added information will be deleted!!! Are you sure, you want go back?')) {
                    this.$router.go(-1)
                }
            }
        }

}
</script>
