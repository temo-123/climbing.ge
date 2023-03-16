<template>
    <div>
        <div class="container">

            <h1  class='serch_title'>{{ $t('shop.title.search') }}</h1>

            <div class="row">
                <div class="col-md-12">
                    <h2 class="text-center"> The your search query is:"<b> {{ this.$route.query.query }} </b>". Please use only english text for your query!!!</h2>
                    
                    <div v-if="product_search_loading">
                        <content-loader
                            viewBox="0 0"
                            primaryColor="#f3f3f3"
                            secondaryColor="#27bb7d8c"
                        />
                    </div>
                    <div class="col-sm-12" v-else>
                        <hr>
                        <span v-if="products.length == 0">
                            <div class="alert alert-danger" role="alert">
                                Nothing found! Try again! :)
                            </div>
                        </span>

                        <span v-else>
                            <searchCard
                                v-for="product in products"
                                :key='product.id'
                                :product="product" 

                                @quick_view="quick_view_model"
                            />
                        </span>
                    </div>

                </div>
            </div>

        </div>

        <stack-modal
            :show="product_modal"
            title="Product"
            @close="product_modal=false"
            :modal-class="{ [modalClass]: true }"
            :saveButton="{ visible: true, title: 'Save', btnClass: { 'btn btn-primary': true } }"
            :cancelButton="{ visible: false, title: 'Close', btnClass: { 'btn btn-danger': true } }"
        >
            <pre class="language-vue">
                <div v-for="q_product in quick_product" :key='q_product.id'>
                    <!-- {{ q_product }} -->

                    <h1>{{ q_product.locale_product.title }}</h1>

                    <!-- <site-img :src="'../../../public/images/product_img/'+q_product.product_images[0]" :img_class="''" :alt='q_product.locale_product.title'/> -->

                    <site-img v-if="q_product.product_images.length > 0" :src="'../../../public/images/product_img/'+q_product.product_images[0]" :alt='q_product.locale_product.title'/>
                    <site-img v-if="q_product.product_images.length == 0" :src="'../../../public/images/site_img/shop_demo.jpg'" :alt="q_product.locale_product.title" />


                    {{ q_product.locale_product.text }}
                </div>
            </pre>
            <div slot="modal-footer">
                <div class="modal-footer">
                    
                </div>
            </div>
        </stack-modal>

        <metaData 
            :title = "'Search product. Query is - ' + this.$route.query.query"
            :description = "'Search product. All products made in Georgia.'"
            :image = "'../../../../public/images/meta_img/shop.jpg'"
        />
    </div>
</template>

<script>
    import searchCard from '../items/SearchCardComponent'
    // import catalogItem from '../items/CatalogItemComponent'
    import { ContentLoader } from 'vue-content-loader'
    import metaData from '../items/MetaDataComponent'

    import { SlickList, SlickItem } from 'vue-slicksort'; //https://github.com/Jexordexan/vue-slicksort
    import StackModal from '@innologica/vue-stackable-modal'  //https://innologica.github.io/vue-stackable-modal/#sample-css

    export default {
        components: {
            metaData,
            searchCard,
            // catalogItem,
            ContentLoader,

            StackModal,
            SlickItem,
            SlickList,
        },
        data: function () {
            return {
                products: [],
                product_search_loading: true,

                product_modal: false,
                modalClass: '',

                quick_product: [],
            };
        },
        mounted() {
            this.get_serching_data();
        },
        watch: {
            '$route' (to, from) {
                this.get_serching_data();
                window.scrollTo(0,0);
            }
        },
        methods: {
            get_serching_data() {
                axios
                .post('../../api/productSearch/' + this.$route.query.query)
                .then(response => {
                    // console.log('test');
                    // console.log(response.data);
                    this.products = response.data
                })
                .catch(error =>{
                })
                .finally(() => this.product_search_loading = false)
            },


            quick_view_model(event){
                // alert(event)

                this.quick_product = []

                axios
                .get('../api/get_quick_product/'+localStorage.getItem('lang')+'/'+event)
                .then(response => {
                    this.quick_product = response.data
                    this.product_modal = true
                })
                .catch(error =>{
                })
            },
        }
    }
</script>
