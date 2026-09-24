<template>
    <div class="h-recent-work products" v-if="products.length > 0">
        <div class="container">
            <h2 class="index_h2">{{ $t('guide.title.donation_products') }}</h2>

            <div class="bar"><i class="fa fa-exclamation-triangle"></i></div>

            <h3 class="article_list_short_description">
                <span v-html="this.$siteData.data.donation_product_short_description"></span>
            </h3>

            <card-slider :items="products" item-key="id" :desktop="3" :mobile="1" :autoplay="5000" slide-class="product-slide-item">
                <template #default="{ item }">
                    <catalogItem
                        :product_data="item"
                        @quick_view="$emit('quick_view', $event)"
                    />
                </template>
            </card-slider>
        </div>
    </div>
</template>

<script>
    import catalogItem from './cards/CatalogItemComponent'

    export default {
        props: [
            // 'products',
        ],
        data: function () {
            return {
                products: [],
            };
        },
        components: {
            catalogItem
        },
        mounted() {
            this.get_products()
        },
        methods: {
            get_products(){
                return axios
                .get('/get_product/get_donation_products/'+localStorage.getItem('lang'))
                .then(response => {
                    this.products = response.data.slice(0, 12);

                })
                .catch(error =>{
                })
                // .finally(() => this.products_loading = false);
            },
        }
    }
</script>

<style scoped>
    :deep(.product-slide-item .grid-tile) {
        width: 100%;
        max-width: 100%;
    }
</style>
