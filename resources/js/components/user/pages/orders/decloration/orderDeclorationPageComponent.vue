<template>
    <div class="container">
        <div class="row">
            <div class="col-sm-12">
                <div class="container purchase overflow-auto">

                    <div class="row mb-3">
                        <div class="col">
                            <button class="btn btn-outline-secondary" @click="go_back">
                                <i class="fa fa-arrow-left" aria-hidden="true"></i> Back
                            </button>
                        </div>
                        <div class="col text-right">
                            <button class="btn btn-success" @click="create_order()" :disabled="create_order_loading || !cart_items.length">
                                <i v-if="!create_order_loading" :class="selected_payment === 'online payment' ? 'fa fa-credit-card' : 'fa fa-check'" aria-hidden="true"></i>
                                <i v-else class="fa fa-spinner fa-spin" aria-hidden="true"></i>
                                {{ create_order_loading
                                    ? (selected_payment === 'online payment' ? 'Redirecting to payment...' : 'Placing order...')
                                    : (selected_payment === 'online payment' ? 'Place order & Pay online' : 'Place order') }}
                            </button>
                        </div>
                    </div>

                    <div class="row">
                        <productDradcrumb :props_page="'product_list'"/>
                    </div>

                    <div v-if="create_order_loading" class="row justify-content-center py-4">
                        <div class="col-md-4 text-center">
                            <i class="fa fa-spinner fa-spin fa-2x" aria-hidden="true"></i>
                            <p class="mt-2">
                                {{ selected_payment === 'online payment'
                                    ? 'Creating order and redirecting to TBC Bank...'
                                    : 'Placing your order...' }}
                            </p>
                        </div>
                    </div>

                    <div v-if="order_error" class="alert alert-danger">
                        <i class="fa fa-exclamation-triangle" aria-hidden="true"></i> {{ order_error }}
                    </div>

                    <div v-if="!create_order_loading && cart_items.length" class="row mb-3">
                        <div class="col-12">
                            <div class="alert alert-info">
                                <i class="fa fa-clock-o" aria-hidden="true"></i>
                                <strong> Estimated delivery: {{ delivery_days }} business days</strong>
                                <span v-if="has_produced_by_order" class="ml-2 text-muted">(includes made-to-order items)</span>
                            </div>
                        </div>
                    </div>

                    <div v-if="!create_order_loading" class="row mb-3">
                        <div class="col-md-6">
                            <div class="alert alert-secondary">
                                <h5><i class="fa fa-map-marker" aria-hidden="true"></i> Delivery address</h5>
                                <template v-if="activ_adres && activ_adres.name">
                                    <strong>{{ activ_adres.name }}</strong>
                                    <p class="mb-0 text-muted small">
                                        {{ activ_adres.city }}, {{ activ_adres.strit }} {{ activ_adres.number }}
                                        <span v-if="activ_adres.floor">, Floor {{ activ_adres.floor }}</span>
                                        <span v-if="activ_adres.flat">, Flat {{ activ_adres.flat }}</span>
                                    </p>
                                    <p class="mb-0 text-muted small" v-if="activ_adres.zip_code">Zip: {{ activ_adres.zip_code }}</p>
                                </template>
                                <span v-else class="text-muted">Loading address...</span>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="card border-0 shadow-sm">
                                <div class="card-body">
                                    <h5 class="card-title mb-3">
                                        <i class="fa fa-credit-card mr-2"></i> Payment method
                                    </h5>

                                    <div class="payment-options mb-3">
                                        <label class="payment-option" :class="{ active: selected_payment === 'deliverd payment' }">
                                            <input
                                                type="radio"
                                                value="deliverd payment"
                                                v-model="selected_payment"
                                                class="sr-only"
                                            />
                                            <i class="fa fa-truck mr-2 text-secondary"></i>
                                            <span class="font-weight-bold">Pay on delivery</span>
                                            <small class="d-block text-muted mt-1">Cash or card when you receive the order</small>
                                        </label>

                                        <label class="payment-option" :class="{ active: selected_payment === 'online payment' }">
                                            <input
                                                type="radio"
                                                value="online payment"
                                                v-model="selected_payment"
                                                class="sr-only"
                                            />
                                            <span class="tbc-logo mr-2">TBC</span>
                                            <span class="font-weight-bold">Pay online</span>
                                            <small class="d-block text-muted mt-1">Secure card payment via TBC Bank</small>
                                        </label>
                                    </div>

                                    <div v-if="selected_payment === 'online payment'" class="alert alert-info mb-0 py-2 small">
                                        <i class="fa fa-lock mr-1"></i>
                                        You will be redirected to TBC Bank secure payment page after clicking <strong>Place order</strong>.
                                    </div>
                                    <div v-else class="alert alert-warning mb-0 py-2 small">
                                        <i class="fa fa-info-circle mr-1"></i>
                                        Pay the courier when you receive your order.
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div v-if="!create_order_loading && cart_items.length" class="row">
                        <div class="col-sm-12">
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead class="thead-light">
                                        <tr>
                                            <th>#</th>
                                            <th>Product</th>
                                            <th>Option</th>
                                            <th class="text-center">Qty</th>
                                            <th class="text-right">Price</th>
                                            <th class="text-right">Total</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="(product, index) in cart_items" :key="product.id">
                                            <td>{{ index + 1 }}</td>
                                            <td>{{ product.product && product.product.url_title || '—' }}</td>
                                            <td class="text-muted">{{ product.option && product.option.name || '—' }}</td>
                                            <td class="text-center">{{ product.quantity }}</td>
                                            <td class="text-right">{{ product.option && product.option.price }} ₾</td>
                                            <td class="text-right">
                                                <strong>{{ colculat_items_price(product.option && product.option.price, product.quantity) }} ₾</strong>
                                            </td>
                                            <td class="text-right">
                                                <button @click="del_from_cart(product.id)" class="btn btn-outline-danger btn-sm">
                                                    <i class="fa fa-trash" aria-hidden="true"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <td colspan="5" class="text-right text-muted">Shipping:</td>
                                            <td class="text-right">{{ shiping }} ₾</td>
                                            <td></td>
                                        </tr>
                                        <tr v-if="$route.params.discount && $route.params.discount > 0">
                                            <td colspan="5" class="text-right text-success">Discount ({{ $route.params.discount }}%):</td>
                                            <td class="text-right text-success">−{{ discount_amount }} ₾</td>
                                            <td></td>
                                        </tr>
                                        <tr class="font-weight-bold">
                                            <td colspan="5" class="text-right">Total:</td>
                                            <td class="text-right">{{ total_price }} ₾</td>
                                            <td></td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>

                    <div v-if="!cart_items.length && !create_order_loading" class="alert alert-warning text-center">
                        <i class="fa fa-shopping-cart" aria-hidden="true"></i> Your cart is empty.
                    </div>

                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import productDradcrumb from './items/BreadcrumbComponent.vue'
    export default {
        components: { productDradcrumb },
        data() {
            return {
                cart_items: [],
                total_price: 0,
                shiping: 0,
                discount_amount: 0,
                user_id: 0,
                activ_adres: null,
                shiping_country: {},
                create_order_loading: false,
                order_error: null,
                selected_payment: 'deliverd payment',
            }
        },
        computed: {
            has_produced_by_order() {
                return this.cart_items.some(item => item.product && item.product.sale_type === 'produced_by_order')
            },
            delivery_days() {
                return this.has_produced_by_order ? '5-9' : '2-4'
            },
        },
        mounted() {
            if (!this.$route.params.adres) {
                this.go_back()
                return
            }
            if (this.$route.params.payment) {
                this.selected_payment = this.$route.params.payment
            }
            this.get_products_cart()
        },
        methods: {
            go_back() {
                this.$router.go(-1)
            },
            get_products_cart() {
                axios.get("cart").then(response => {
                    this.cart_items = Array.isArray(response.data) ? response.data : []
                    if (this.cart_items.length > 0) {
                        this.user_id = this.cart_items[0]['user_id']
                    }
                    this.get_activ_adres(this.$route.params.adres)
                }).catch(error => console.log(error))
            },
            colculat_total_price() {
                let subtotal = this.cart_items.reduce((sum, p) => {
                    const price = p.option && p.option.price ? parseFloat(p.option.price) : 0
                    return sum + price * (p.quantity || 1)
                }, 0)

                this.shiping = parseFloat(this.colculate_shiping_price(subtotal)) || 0

                if (this.$route.params.discount && this.$route.params.discount > 0) {
                    this.discount_amount = ((subtotal * this.$route.params.discount) / 100).toFixed(2)
                    this.total_price = (subtotal - parseFloat(this.discount_amount) + this.shiping).toFixed(2)
                } else {
                    this.discount_amount = 0
                    this.total_price = (subtotal + this.shiping).toFixed(2)
                }
            },
            colculate_shiping_price(subtotal) {
                if (!this.shiping_country || !this.shiping_country.shiping_price) return 0
                if (this.shiping_country.free_shiping_price_after && subtotal >= this.shiping_country.free_shiping_price_after) return 0
                return this.shiping_country.shiping_price
            },
            colculat_items_price(price, quantity) {
                return price ? (parseFloat(price) * quantity).toFixed(2) : 0
            },
            del_from_cart(item_id) {
                if (confirm('Remove this item from the order?')) {
                    axios.delete("cart/" + item_id)
                        .then(() => this.get_products_cart())
                        .catch(error => console.log(error))
                }
            },
            get_activ_adres(adres_id) {
                axios.get('get_activ_adres/' + adres_id)
                    .then(response => {
                        this.activ_adres = response.data
                        this.get_activ_region(response.data.region_id)
                    })
                    .catch(error => console.log(error))
            },
            get_activ_region(region_id) {
                axios.get('get_shiped_region/get_activ_region/' + region_id)
                    .then(response => {
                        this.shiping_country = response.data
                        this.colculat_total_price()
                    })
                    .catch(error => {
                        console.log(error)
                        this.colculat_total_price()
                    })
            },
            create_order() {
                this.order_error = null
                this.create_order_loading = true
                axios.post('set_order/create_order', {
                    order_product_list: this.cart_items,
                    payment_tupe: this.selected_payment,
                    adres: this.$route.params.adres,
                    shiping: this.shiping,
                }).then(response => {
                    const orderId = response.data && response.data.order_id
                    if (this.selected_payment === 'online payment' && orderId) {
                        return axios.post('payment/shop/initiate/' + orderId).then(payRes => {
                            window.location.href = payRes.data.payment_url
                        })
                    }
                    this.$router.push({ name: 'confirmOrder', params: { order_decloration: true } })
                }).catch(error => {
                    this.order_error = error.response && error.response.data && error.response.data.error
                        ? error.response.data.error
                        : 'Failed to place order. Please try again.'
                }).finally(() => this.create_order_loading = false)
            },
        }
    }
</script>

<style scoped>
.purchase { background-color: #fff; min-height: 500px; padding: 20px; }

.payment-options {
    display: flex;
    flex-direction: column;
    gap: 10px;
}

.payment-option {
    display: block;
    padding: 14px 16px;
    border: 2px solid #dee2e6;
    border-radius: 8px;
    cursor: pointer;
    margin-bottom: 0;
    transition: border-color 0.15s, background-color 0.15s;
}

.payment-option:hover {
    border-color: #6c757d;
    background-color: #f8f9fa;
}

.payment-option.active {
    border-color: #007bff;
    background-color: #e8f0fe;
}

.tbc-logo {
    display: inline-block;
    background: #00529b;
    color: #fff;
    font-weight: 800;
    font-size: 0.75rem;
    padding: 2px 6px;
    border-radius: 4px;
    letter-spacing: 1px;
}
</style>
