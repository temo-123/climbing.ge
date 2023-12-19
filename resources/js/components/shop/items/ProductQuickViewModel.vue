<template>
    <stack-modal
        :show="product_modal"
        title="Product"
        @close="close_product_modal()"
        :modal-class="{ [modalClass]: true }"
        :saveButton="{ visible: true, title: 'Save', btnClass: { 'btn btn-primary': true } }"
        :cancelButton="{ visible: false, title: 'Close', btnClass: { 'btn btn-danger': true } }"
    >
        <pre class="language-vue">
            <span v-for="q_product in quick_product" :key='q_product.id'>
                <h1 class="margin_bottom_top">{{ q_product.locale_product.title }}</h1>

                <site-img class="margin_bottom_top" v-if="q_product.product_images.length > 0" :src="'/public/images/product_option_img/'+q_product.product_images[0]" :alt='q_product.locale_product.title'/>
                <site-img class="margin_bottom_top" v-if="q_product.product_images.length == 0" :src="'/public/images/site_img/shop_demo.jpg'" :alt="q_product.locale_product.title" />

                <p class="margin_bottom_top model_price_text_size" v-if="q_product.min_price != q_product.max_price" >{{ q_product.min_price }} ₾ - {{ q_product.max_price }} ₾</p>
                <p class="margin_bottom_top model_price_text_size" v-if="q_product.min_price == q_product.max_price">{{ q_product.max_price }} ₾</p>

                <span class="margin_bottom_top" v-html="q_product.locale_product.text"></span>

                <router-link :to="'product/'+q_product.global_product.url_title" class="btn btn-primary margin_bottom_top" style="float: left;"> Open product</router-link>
            </span>
        </pre>
        <div slot="modal-footer">
            <div class="modal-footer">
                <!-- <router-link :to="'product/'+quick_product[0].global_product.url_title" class="btn btn-primary"> Open product page</router-link> -->
            </div>
        </div>
    </stack-modal>
</template>

<script>
    // import { SlickList, SlickItem } from 'vue-slicksort'; //https://github.com/Jexordexan/vue-slicksort
    import StackModal from '@innologica/vue-stackable-modal'  //https://innologica.github.io/vue-stackable-modal/#sample-css

    export default {
        components: {
            // SlickItem,
            // SlickList,
            StackModal
            // productQuickViewModel
        },
        // props:[
        //     'site_data',
        // ],
        data: function () {
            return {
                quick_product: [],
            };
        },
        mounted() {
            //
        },
        methods: {
            quick_view_model(product_id){
                // alert(product_id)

                this.quick_product = []

                axios
                .get('/get_quick_product/'+localStorage.getItem('lang')+'/'+product_id)
                .then(response => {
                    this.quick_product = response.data
                    this.product_modal = true
                })
                .catch(error =>{
                })
            },
            close_product_modal(){
                this.product_modal = false
                this.quick_product = []
            },
        }
    }
</script>

<style scoped>
    .modal-content {
        margin-top: 50% !important;
    }
</style>