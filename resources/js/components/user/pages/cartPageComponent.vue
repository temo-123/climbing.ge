<template>
    <div class="row">

        <left-menu />

        <div class="col-sm-12">
            <div class="row">
                <div class="col-md-12">
                    <breadcrumb />
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="card shopping-cart">
                        <div class="card-header bg-dark text-light">
                            <i class="fa fa-shopping-cart" aria-hidden="true"></i>
                            Shopping Cart
                            <button class="btn btn-primary float-right" @click="goToShop()">Go to shop</button>
                        </div>
                        <div class="card-body">
                            <div v-if="!cart_items.length" class="text-center text-muted py-4">
                                <i class="fa fa-shopping-cart fa-2x" aria-hidden="true"></i>
                                <p class="mt-2">Your cart is empty.</p>
                                <button class="btn btn-primary" @click="goToShop()">Browse products</button>
                            </div>

                            <div v-for="product in cart_items" :key="product.id" class="cart-item">
                                <div class="row align-items-center">
                                    <div class="col-sm-12 col-md-2 text-center">
                                        <img
                                            v-if="product.product_image"
                                            class="img-responsive cart-thumb"
                                            :src="'/public/images/product_option_img/'+product.product_image"
                                            :alt="product.product && product.product.url_title || ''"
                                        >
                                        <img
                                            v-else
                                            class="img-responsive cart-thumb"
                                            :src="'/public/images/site_img/shop_demo.jpg'"
                                            :alt="product.product && product.product.url_title || ''"
                                        >
                                    </div>

                                    <div class="col-sm-12 col-md-4 text-md-left">
                                        <a
                                            v-if="product.product && product.product.url_title"
                                            @click.prevent="openProduct(product.product.url_title)"
                                            href="#"
                                            class="cart-product-name"
                                        >
                                            {{ product.product.url_title }}
                                        </a>
                                        <span v-else class="cart-product-name text-muted">Unknown product</span>

                                        <div class="cart-option-name text-muted" v-if="product.option && product.option.name">
                                            <small><i class="fa fa-tag" aria-hidden="true"></i> {{ product.option.name }}</small>
                                        </div>

                                        <div v-if="product.stock_quantity > 0 && product.stock_quantity <= 5" class="text-warning mt-1">
                                            <small><i class="fa fa-exclamation-triangle" aria-hidden="true"></i> Only {{ product.stock_quantity }} left in stock</small>
                                        </div>
                                    </div>

                                    <div class="col-sm-12 col-md-4">
                                        <div class="d-flex align-items-center justify-content-md-end">
                                            <div class="cart-price mr-3">
                                                <div class="text-muted"><small>{{ product.option && product.option.price }} ₾ each</small></div>
                                                <strong>{{ colculat_items_price(product.option && product.option.price, product.quantity) }} ₾</strong>
                                            </div>
                                            <div class="quantity-wrap">
                                                <span v-if="is_quantity_updating" class="text-muted">Updating...</span>
                                                <div v-else class="d-flex align-items-center">
                                                    <button class="qty-btn" @click="changeQty(product, -1)" :disabled="product.quantity <= 1">−</button>
                                                    <input
                                                        type="number"
                                                        class="qty-input"
                                                        v-model.number="product.quantity"
                                                        min="1"
                                                        :max="product.stock_quantity || 999"
                                                        @change="onQtyChange(product)"
                                                    >
                                                    <button class="qty-btn" @click="changeQty(product, 1)" :disabled="product.quantity >= (product.stock_quantity || 999)">+</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-sm-12 col-md-2 text-right">
                                        <button @click="del_from_cart(product.id)" class="btn btn-outline-danger btn-sm">
                                            <i class="fa fa-trash" aria-hidden="true"></i>
                                        </button>
                                    </div>
                                </div>
                                <hr>
                            </div>

                            <div class="text-right mt-2" v-if="cart_items.length">
                                <button type="button" @click="get_products_cart" class="btn btn-outline-secondary btn-sm" v-if="!is_products_refresh">
                                    <i class="fa fa-refresh" aria-hidden="true"></i> Refresh ({{ products_reset_id }})
                                </button>
                                <span class="badge badge-secondary" v-if="is_products_refresh">Updating...</span>
                            </div>
                        </div>

                        <div class="card-footer" v-if="cart_items.length">
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="total-price">
                                    Total: <strong>{{ total_price }} ₾</strong>
                                </div>
                                <router-link :to="{name: 'orderPayment'}">
                                    <button type="button" class="btn btn-success">
                                        <i class="fa fa-credit-card" aria-hidden="true"></i> Checkout
                                    </button>
                                </router-link>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import breadcrumb from '../items/BreadcrumbComponent.vue'
    export default {
        components: {
            breadcrumb,
        },
        data(){
            return {
                cart_items: [],
                is_products_refresh: false,
                products_reset_id: 0,
                total_price: 0,
                user_id: 0,
                MIX_SHOP_URL: process.env.MIX_SHOP_URL,
                MIX_APP_SSH: process.env.MIX_APP_SSH,
                is_quantity_updating: false,
            }
        },
        mounted() {
            this.get_products_cart()
        },
        methods: {
            goToShop(){
                window.open(this.MIX_APP_SSH + this.MIX_SHOP_URL)
            },
            openProduct(url_title){
                window.open(this.MIX_APP_SSH + this.MIX_SHOP_URL + '/product/' + url_title)
            },
            get_products_cart() {
                axios.get("cart").then(response => {
                    this.cart_items = Array.isArray(response.data) ? response.data : []
                    if (this.cart_items.length > 0) {
                        this.user_id = this.cart_items[0]['user_id']
                    }
                    this.products_reset_id++
                    this.colculat_total_price()
                }).catch(error => console.log(error))
            },
            colculat_total_price() {
                if (!Array.isArray(this.cart_items)) return
                this.total_price = this.cart_items.reduce((sum, p) => {
                    const price = p.option && p.option.price ? parseFloat(p.option.price) : 0
                    return sum + price * (p.quantity || 1)
                }, 0)
            },
            colculat_items_price(price, quantity) {
                return price ? (parseFloat(price) * quantity).toFixed(2) : 0
            },
            changeQty(product, delta) {
                const max = product.stock_quantity || 999
                const newQty = product.quantity + delta
                if (newQty < 1 || newQty > max) return
                product.quantity = newQty
                this.update_quantity(product.id, product.quantity)
            },
            onQtyChange(product) {
                const max = product.stock_quantity || 999
                if (product.quantity < 1) product.quantity = 1
                if (product.quantity > max) product.quantity = max
                this.update_quantity(product.id, product.quantity)
                this.colculat_total_price()
            },
            update_quantity(item_id, quantity) {
                this.is_quantity_updating = true
                axios.post("cart/update_quantity/" + item_id, { quantity })
                    .then(() => { this.get_products_cart(); this.$bus.$emit('cart-updated') })
                    .catch(error => console.log(error))
                    .finally(() => this.is_quantity_updating = false)
            },
            del_from_cart(item_id) {
                if (confirm('Remove this item from cart?')) {
                    axios.delete("cart/" + item_id)
                        .then(() => { this.get_products_cart(); this.$bus.$emit('cart-updated') })
                        .catch(error => console.log(error))
                }
            },
        }
    }
</script>

<style scoped>
.cart-item { padding: 12px 0; }
.cart-thumb { max-width: 80px; max-height: 80px; object-fit: cover; border-radius: 4px; }
.cart-product-name { font-size: 0.95rem; font-weight: 600; color: #333; text-decoration: none; }
.cart-product-name:hover { color: #007bff; text-decoration: underline; cursor: pointer; }
.cart-option-name { font-size: 0.82rem; color: #777; margin-top: 2px; }
.cart-price { text-align: right; min-width: 80px; }
.quantity-wrap { display: flex; align-items: center; }
.qty-btn {
    width: 28px; height: 28px; border: 1px solid #ccc; background: #f8f9fa;
    border-radius: 4px; cursor: pointer; font-size: 1rem; line-height: 1;
}
.qty-btn:disabled { opacity: 0.4; cursor: default; }
.qty-input {
    width: 48px; text-align: center; border: 1px solid #ccc;
    border-radius: 4px; margin: 0 4px; height: 28px; padding: 0 4px;
}
.total-price { font-size: 1.1rem; }
</style>
