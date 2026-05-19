<template>
    <div class="row">
        <div class="col-md-12">
            <div class="row mb-3">
                <div class="col-md-6">
                    <div class="btn-group" role="group">
                        <button
                            class="btn"
                            :class="activeTab === 'options' ? 'btn-primary' : 'btn-outline-secondary'"
                            @click="activeTab = 'options'"
                        >
                            <i class="fa fa-list"></i> Options
                        </button>
                        <button
                            class="btn"
                            :class="activeTab === 'images' ? 'btn-primary' : 'btn-outline-secondary'"
                            @click="activeTab = 'images'"
                        >
                            <i class="fa fa-image"></i> Gallery Images
                        </button>
                    </div>
                </div>
                <div class="col-md-6">
                    <button class="btn btn-success float-right" @click="go_back()">Go back</button>
                </div>
            </div>
        </div>

        <product-options-component v-if="activeTab === 'options'"></product-options-component>
        <product-images-component v-if="activeTab === 'images'"></product-images-component>
    </div>
</template>

<script>
    import productOptionsComponent from './items/ProductOptionsComponent.vue'
    import productImagesComponent from '../product_images/ProductImagesComponent.vue'

    export default {
        components: {
            productOptionsComponent,
            productImagesComponent,
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
