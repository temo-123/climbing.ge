<template>
    <StackModal
        v-model="is_order_detals_model"
        :title="$t('admin.orders.order_details_modal_title')"
        @close="is_order_detals_model=false"
        :saveButton="{ visible: false }"
        :cancelButton="{ visible: true, title: $t('common.close'), btnClass: { 'btn btn-secondary': true } }"
    >
        <div v-if="order">

            <!-- Order type badge -->
            <div class="mb-3">
                <span v-if="order.is_custom" class="badge badge-warning mr-2">{{ $t('admin.orders.custom_order_badge') }}</span>
                <span v-else class="badge badge-primary mr-2">{{ $t('admin.orders.cart_order_badge') }}</span>
                <span class="badge badge-info">
                    <i class="fa fa-clock-o"></i> {{ $t('admin.orders.delivery_days_prefix', { days: delivery_days }) }}
                </span>
            </div>

            <!-- Delivery address (regular orders) -->
            <template v-if="!order.is_custom && buyer_address">
                <h6 class="text-muted mb-2">{{ $t('user.orders.delivery_address') }}</h6>
                <div class="alert alert-secondary py-2 mb-3">
                    <strong>{{ buyer_address.name }}</strong>
                    <p class="mb-0 small">
                        {{ buyer_address.city }}<span v-if="buyer_address.strit">, {{ buyer_address.strit }} {{ buyer_address.number }}</span>
                        <span v-if="buyer_address.floor">, {{ $t('user.orders.floor', { floor: buyer_address.floor }) }}</span>
                        <span v-if="buyer_address.flat">, {{ $t('user.orders.flat', { flat: buyer_address.flat }) }}</span>
                    </p>
                    <p class="mb-0 small" v-if="buyer_address.zip_code">{{ $t('user.orders.zip', { zip: buyer_address.zip_code }) }}</p>
                    <p class="mb-0 small" v-if="buyer_address.map">
                        <a :href="buyer_address.map" target="_blank" rel="noopener">
                            <i class="fa fa-map-o"></i> {{ $t('user.orders.view_on_map') }}
                        </a>
                    </p>
                </div>
                <hr>
            </template>

            <!-- Buyer info (custom orders only) -->
            <template v-if="order.is_custom && buyer_address">
                <h6 class="text-muted mb-2">{{ $t('admin.orders.buyer_information_title') }}</h6>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>{{ $t('admin.orders.name_label') }}</label>
                            <input type="text" class="form-control" :value="buyer_address.name" readonly>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>{{ $t('admin.orders.surname_label') }}</label>
                            <input type="text" class="form-control" :value="buyer_address.surname" readonly>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>{{ $t('common.email') }}</label>
                            <input type="text" class="form-control" :value="buyer_address.email || '—'" readonly>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>{{ $t('admin.orders.phone_label') }}</label>
                            <input type="text" class="form-control" :value="buyer_address.phone || '—'" readonly>
                        </div>
                    </div>
                </div>

                <!-- Linked users from DB -->
                <template v-if="related_users && related_users.length">
                    <h6 class="text-muted mb-2">{{ $t('admin.orders.linked_user_accounts_title') }}</h6>
                    <div v-for="u in related_users" :key="u.id" class="alert alert-success py-2">
                        <i class="fa fa-user"></i>
                        <strong>{{ u.name }} {{ u.surname }}</strong>
                        <span class="text-muted ml-2">{{ u.email }}</span>
                        <span class="badge badge-secondary ml-2">{{ $t('admin.orders.id_hash_prefix') }}{{ u.id }}</span>
                    </div>
                </template>
                <div v-else-if="order.is_custom" class="alert alert-secondary py-2">
                    <i class="fa fa-user-times"></i> {{ $t('admin.orders.no_registered_user_found') }}
                </div>

                <hr>
            </template>

            <!-- Order Details -->
            <h6 class="text-muted mb-2">{{ $t('admin.orders.order_details_title') }}</h6>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label>{{ $t('admin.orders.shipping_method_label') }}</label>
                        <input type="text" class="form-control" :value="shippingLabel" readonly>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label>{{ $t('admin.orders.payment_method_label') }}</label>
                        <input type="text" class="form-control" :value="paymentLabel" readonly>
                    </div>
                </div>
            </div>

            <template v-if="order.is_custom && buyer_address && order.shiping === 'delivery'">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>{{ $t('admin.orders.address_label') }}</label>
                            <input type="text" class="form-control" :value="buyer_address.address || '—'" readonly>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>{{ $t('admin.orders.city_label') }}</label>
                            <input type="text" class="form-control" :value="buyer_address.city || '—'" readonly>
                        </div>
                    </div>
                </div>
            </template>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label>{{ $t('admin.orders.status_label') }}</label>
                        <input type="text" class="form-control" :value="order.status" readonly>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label>{{ $t('admin.orders.status_updated_label') }}</label>
                        <input type="text" class="form-control" :value="order.status_updating_data || '—'" readonly>
                    </div>
                </div>
            </div>

            <hr>

            <!-- Products -->
            <h6 class="text-muted mb-2">{{ $t('admin.orders.products_title') }}</h6>
            <div v-for="(item, index) in order_product_items" :key="item.id" class="border rounded p-3 mb-2 bg-light">
                <div class="row align-items-center">
                    <div class="col-md-5">
                        <label class="text-muted d-block mb-1" v-if="index === 0">{{ $t('admin.orders.product_label') }}</label>
                        <input type="text" class="form-control" :value="item.product.url_title" readonly>
                    </div>
                    <div class="col-md-3">
                        <label class="text-muted d-block mb-1" v-if="index === 0">{{ $t('admin.orders.option_label') }}</label>
                        <input type="text" class="form-control" :value="item.option ? item.option.name : '—'" readonly>
                    </div>
                    <div class="col-md-2">
                        <label class="text-muted d-block mb-1" v-if="index === 0">{{ $t('admin.orders.qty_label') }}</label>
                        <input type="number" class="form-control" :value="item.quantity" readonly>
                    </div>
                    <div class="col-md-2">
                        <label class="text-muted d-block mb-1" v-if="index === 0">{{ $t('common.price') }}</label>
                        <input type="text" class="form-control" :value="(item.option ? item.option.price * item.quantity : 0) + ' ₾'" readonly>
                    </div>
                </div>
            </div>

            <div class="d-flex justify-content-end mt-2">
                <strong>{{ $t('admin.orders.total_prefix') }} {{ total_price }} ₾</strong>
            </div>
        </div>
    </StackModal>
</template>

<script>
export default {
    data() {
        return {
            is_order_detals_model: false,
            order: null,
            buyer_address: null,
            order_product_items: [],
            related_users: [],
            total_price: 0,
        }
    },
    computed: {
        shippingLabel() {
            const map = { self_delivery: this.$t('admin.orders.self_delivery_label'), delivery: this.$t('admin.orders.delivery_option') }
            return map[this.order?.shiping] || this.order?.shiping || '—'
        },
        paymentLabel() {
            const map = {
                deliverd_payment: this.$t('admin.orders.payment_on_delivery_option'),
                mony_transfer: this.$t('admin.orders.money_transfer_option'),
                online_payment: this.$t('admin.orders.online_payment_label'),
            }
            return map[this.order?.payment] || this.order?.payment || '—'
        },
        delivery_days() {
            const has_produced = this.order_product_items.some(
                item => item.product && item.product.sale_type === 'produced_by_order'
            )
            return has_produced ? '5-9' : '2-4'
        },
    },
    methods: {
        show_modal(order_id) {
            this.get_order_detals(order_id)
        },
        get_order_detals(order_id) {
            axios.get('get_order/get_order_detals/' + order_id)
                .then(response => {
                    this.order = response.data.order
                    this.order_product_items = response.data.order_products
                    this.buyer_address = response.data.buyer_address || null
                    this.related_users = response.data.related_users || []
                    this.calculate_total()
                    this.is_order_detals_model = true
                })
                .catch(error => console.log(error))
        },
        calculate_total() {
            this.total_price = this.order_product_items.reduce((sum, item) => {
                return sum + (item.option ? item.option.price * item.quantity : 0)
            }, 0)
        },
    }
}
</script>
