<template>
    <div class="row">
        <div class="col-md-12">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-groupe">
                        <!-- <button type='submit' form="myForm" class="btn btn-primary">Save</button> -->
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-groupe">
                        <button class="btn btn-success float-right" @click="go_back()">Go back</button>
                    </div>
                </div>
            </div>
        </div>
        
        <product-options-component></product-options-component>

    </div>
</template>

<script>
    // import StackModal from '@innologica/vue-stackable-modal'
    import productOptionsComponent from './items/ProductOptionsComponent.vue'
    
    export default {
        components: {
            // StackModal,
            productOptionsComponent
        },
        created() {
            document.querySelector('body').style.marginLeft = '0';
            document.querySelector('.admin_page_header_navbar').style.marginLeft = '0';
        },
        methods: {
            go_back() {
                this.$router.go(-1);
            }
        }
    }
</script>

