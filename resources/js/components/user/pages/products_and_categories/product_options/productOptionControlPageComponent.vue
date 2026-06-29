<template>
    <div class="row">
        <div class="col-md-12">

            <div class="form-groupe float-right mb-2">
                <button class="btn btn-success" @click="go_back()">Go back</button>
            </div>

            <div class="tabs">
                <div class="row">
                    <div class="col">
                        <input type="radio" id="tab-options" value="options" v-model="activeTab">
                        <label for="tab-options">Options</label>
                    </div>
                    <div class="col">
                        <input type="radio" id="tab-images" value="images" v-model="activeTab">
                        <label for="tab-images">Gallery Images</label>
                    </div>
                    <div class="col">
                        <input type="radio" id="tab-combinations" value="combinations" v-model="activeTab">
                        <label for="tab-combinations">Combinate Product Options</label>
                    </div>
                </div>

                <div class="tab-content-area">
                    <product-options-component v-if="activeTab === 'options'"></product-options-component>
                    <product-images-component v-if="activeTab === 'images'"></product-images-component>
                    <product-combinations-component v-if="activeTab === 'combinations'"></product-combinations-component>
                </div>
            </div>

        </div>
    </div>
</template>

<script>
    import productOptionsComponent from './items/ProductOptionsComponent.vue'
    import productImagesComponent from '../product_images/ProductImagesComponent.vue'
    import productCombinationsComponent from './items/ProductCombinationsComponent.vue'

    export default {
        components: {
            productOptionsComponent,
            productImagesComponent,
            productCombinationsComponent,
        },
        data() {
            return {
                activeTab: 'options',
            }
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

<style scoped>
.tab-content-area {
    padding: 1rem;
    background: #fff;
    border: 1px solid #ccc;
    border-top: none;
}
</style>
