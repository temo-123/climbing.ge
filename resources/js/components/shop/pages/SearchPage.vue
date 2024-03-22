<template>
    <div>
        <div class="container">

            <h1  class='serch_title'>{{ $t('shop.title.search') }}</h1>

            <div class="row">
                <div class="col-md-12">
                    <h2 class="text-center"> The your search query is:"<b> {{ this.$route.query.query }} </b>". Please use only english text for your query!!!</h2>
                    
                    <div v-if="is_loading">
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
                            <catalogItem
                                v-for="product in products"
                                :key='product.id'
                                :product_data="product"

                                @quick_view="quick_view_model"
                            />
                        </span>
                    </div>

                </div>
            </div>

        </div>

        <metaData 
            :title = " $t('shop.meta.search') "
            :description = "'Search product. All products made in Georgia.'"
            :image = "'/public/images/meta_img/shop.jpg'"
        />
    </div>
</template>

<script>
    import { ContentLoader } from 'vue-content-loader'
    import metaData from '../items/MetaDataComponent'
    import catalogItem from '../items/cards/CatalogItemComponent'
    export default {
        components: {
            metaData,
            catalogItem,
            ContentLoader,
        },
        data: function () {
            return {
                products: [],
                is_loading: true,
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
                .post('/productSearch/' + this.$route.query.query)
                .then(response => {
                    this.products = response.data
                })
                .catch(error =>{
                })
                .finally(() => this.is_loading = false)
            },
        }
    }
</script>
