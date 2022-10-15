<template>
    <div>
        <div class="container">

            <h1  class='serch_title'>Search page</h1>

            <div class="row">
                <div class="col-md-12">
                    <p> The Search results for your query is:<b> {{ this.$route.query.query }} </b></p>
                    <!-- <p>( {{ this.$route.params.query }} )</p> -->

                    <div class="col-sm-12">
                        <section class="inner">
                            <section class="portfolio" id="portfolio">

                                    <div class="layout">
                                        <section class="inner">
                                            <ul class="grid">
                                                <catalogItem
                                                    v-for="product in products"
                                                    :key='product.id'
                                                    :product_data="product"
                                                ></catalogItem>
                                            </ul>
                                        </section>
                                    </div>
                            </section>
                        </section>
                    </div>
                </div>
            </div>
        </div>

        <metaData 
            :title = "'Climbing shop'"
            :description = "'Climbing products. All products made in Georgia.'"
            :image = "'../../../../public/images/meta_img/shop.jpg'"
        />
    </div>
</template>

<script>
    import catalogItem from '../items/CatalogItemComponent'
    import metaData from '../items/MetaDataComponent'
    export default {
        components: {
            metaData,
            catalogItem,
        },
        data: function () {
            return {
                products: [],
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
                .get('../api/productSearch/' + this.$route.query.query)
                .then(response => {
                    // console.log('test');
                    // console.log(response.data);
                    this.products = response.data
                })
                .catch(error =>{
                })
            }
        }
    }
</script>
