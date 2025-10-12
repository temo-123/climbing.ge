<template>
    <div class="h-recent-work products" v-if="products.length > 0">
        <div class="container">
            <h2 class="index_h2">{{ $t('shop.title.products') }}</h2>

            <div class="bar"><i class="fa fa-exclamation-triangle"></i></div>

            <h3 class="article_list_short_description">
                <span v-html="this.$siteData.data.shop_short_description"></span>
            </h3>

            <div class="products-slider-container">
                <div class="previes_products_bottom" v-if="products.length > visibleCount && slider_index > 0" @click="previous">
                    <i class="fa fa-chevron-left" aria-hidden="true"></i>
                </div>

                <div class="products-slider-wrapper">
                    <div class="products-slider" :style="{ display: 'flex', gap: '1%', width: (products.length * 20) + '%', transform: 'translateX(' + (-slider_index * 20) + '%)', transition: 'transform 0.5s ease' }">
                        <catalogItem
                            v-for="product in products"
                            :key='product.id'
                            :product_data="product"
                            @quick_view="$emit('quick_view', $event)"
                        />
                    </div>
                </div>

                <div class="next_products_bottom" v-if="products.length > visibleCount && slider_index < products.length - visibleCount" @click="next">
                    <i class="fa fa-chevron-right" aria-hidden="true"></i>
                </div>
            </div>
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
                slider_index: 0,
                visibleCount: 5,
                products: [],
                autoScrollInterval: null
            };
        },
        components: {
            catalogItem
        },
        mounted() {
            this.get_products().then(() => {
                this.autoScrollInterval = setInterval(() => {
                    this.next();
                }, 5000);
            });
        },
        beforeDestroy() {
            if (this.autoScrollInterval) {
                clearInterval(this.autoScrollInterval);
            }
        },
        methods: {
            next(){
                clearInterval(this.autoScrollInterval);
                if (this.slider_index < this.products.length - this.visibleCount) {
                    this.slider_index += 1;
                } else {
                    this.slider_index = 0;
                }
                this.autoScrollInterval = setInterval(() => {
                    this.next();
                }, 3000);
            },

            previous(){
                clearInterval(this.autoScrollInterval);
                if (this.slider_index > 0) {
                    this.slider_index -= 1;
                }
                this.autoScrollInterval = setInterval(() => {
                    this.next();
                }, 3000);
            },

            get_products(){
                return axios
                .get('/products/'+localStorage.getItem('lang'))
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
    .products-slider-container {
        display: flex;
        align-items: center;
    }

    .products-slider-wrapper {
        flex: 1;
        overflow: hidden;
    }

    .products-slider {
        display: flex;
        gap: 2%;
    }

    .products-slider .grid-tile {
        flex: 0 0 25em;
        max-height: 25%;
    }

    .previes_products_bottom, .next_products_bottom {
        font-size: 150%;
        cursor: pointer;
        padding: 10px 15px;
        background-color: rgba(255, 255, 255, 0.8);
        border: 1px solid #dee2e6;
        border-radius: 50%;
        transition: all 0.3s ease;
        color: #6c757d;
        margin: 0 10px;
    }

    .previes_products_bottom:hover, .next_products_bottom:hover {
        background-color: #7c7cfd;
        color: white;
        border-color: #7c7cfd;
    }
</style>
