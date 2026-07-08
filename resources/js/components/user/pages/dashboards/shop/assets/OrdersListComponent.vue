<template>
    <div class="orders-list-block">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h6 class="mb-0 font-weight-bold">{{ $t('admin.orders.recent_orders_title') }}</h6>
            <div>
                <button v-if="$can('add', 'order')" class="btn btn-sm btn-primary mr-2" @click="add_custom_order">
                    <i class="fa fa-plus"></i> {{ $t('admin.orders.custom_order_btn') }}
                </button>
                <button class="btn btn-sm btn-outline-secondary" @click="load" :disabled="loading">
                    <i class="fa fa-refresh"></i>
                </button>
            </div>
        </div>

        <div v-if="loading" class="text-center py-4 text-muted">
            <div class="spinner-border spinner-border-sm text-primary" role="status"></div> {{ $t('admin.export.loading_ellipsis') }}
        </div>

        <div v-else-if="orders.length === 0" class="text-center text-muted py-4">{{ $t('admin.orders.no_orders_found') }}</div>

        <div v-else class="table-responsive">
            <table class="table table-hover table-sm">
                <thead class="thead-light">
                    <tr>
                        <th>#</th>
                        <th>{{ $t('admin.orders.status_label') }}</th>
                        <th>{{ $t('admin.orders.payment_col') }}</th>
                        <th>{{ $t('admin.orders.shipping_col') }}</th>
                        <th>{{ $t('common.date') }}</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="order in orders" :key="order.id">
                        <td>{{ order.id }}</td>
                        <td>
                            <span class="badge" :class="status_badge(order.status)">{{ order.status }}</span>
                        </td>
                        <td>{{ order.payment }}</td>
                        <td>{{ order.shiping }}</td>
                        <td>{{ order.created_at ? order.created_at.substring(0,10) : '—' }}</td>
                        <td class="text-nowrap">
                            <button v-if="$can('edit_order_status', 'edit')" class="btn btn-xs btn-outline-primary mr-1" @click="show_edit_status(order.id)" :title="$t('admin.orders.edit_status_tooltip')">
                                <i class="fa fa-pencil"></i>
                            </button>
                            <button class="btn btn-xs btn-outline-info" @click="show_details(order.id)" :title="$t('admin.task.details_tooltip')">
                                <i class="fa fa-eye"></i>
                            </button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <orderDetalsModal ref="orderDetalsModal" @update="load" />
        <editOrderStatusModal ref="editOrderStatusModal" @update="load" />
        <addCustomOrderModal ref="addCustomOrderModal" @orderAdded="load" />
    </div>
</template>

<script>
    import orderDetalsModal from '../../../../items/modal/orders/orderDetalsModal.vue'
    import editOrderStatusModal from '../../../../items/modal/orders/editOrderStatusModal.vue'
    import addCustomOrderModal from '../../../../items/modal/orders/AddCustomOrderModal.vue'

    export default {
        components: { orderDetalsModal, editOrderStatusModal, addCustomOrderModal },
        data() {
            return {
                orders: [],
                loading: false,
            }
        },
        mounted() {
            this.load()
        },
        methods: {
            load() {
                this.loading = true
                axios.get('get_order/get_all_orders')
                    .then(r => { this.orders = r.data })
                    .catch(e => console.error(e))
                    .finally(() => { this.loading = false })
            },
            status_badge(status) {
                const map = {
                    new: 'badge-primary', pending: 'badge-warning', processing: 'badge-info',
                    shipped: 'badge-secondary', delivered: 'badge-success', cancelled: 'badge-danger',
                }
                return map[status] || 'badge-secondary'
            },
            show_details(id) { this.$refs.orderDetalsModal.show_modal(id) },
            show_edit_status(id) { this.$refs.editOrderStatusModal.show_modal(id) },
            add_custom_order() { this.$refs.addCustomOrderModal.show_modal() },
        },
    }
</script>

<style scoped>
.orders-list-block { width: 100%; }
.btn-xs { padding: 2px 6px; font-size: 0.75rem; }
</style>
