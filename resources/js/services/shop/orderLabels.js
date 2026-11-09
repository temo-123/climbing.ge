// Human labels for the order codes OrderService sends (order_type,
// shipping_method, payment_method). Shared by the admin orders table, the
// shop dashboard list, the customer purchases table and the order modals.
// `t` is the component's this.$t.

export function money(v, currency = '₾') {
    const n = Number(v)
    if (!Number.isFinite(n)) return '—'
    return n.toFixed(2) + ' ' + currency
}

export function order_type_label(t, type) {
    return type === 'manual' ? t('user.order_view.type_manual') : t('user.order_view.type_website')
}

export function shipping_method_label(t, method) {
    return method === 'self_delivery' ? t('user.order_view.method_self_delivery') : t('user.order_view.method_delivery')
}

// "Delivery · 10.00 ₾" / "Delivery · Free" / "Self pickup"
export function shipping_cell(t, method, cost) {
    if (method === 'self_delivery') return shipping_method_label(t, method)
    const price = Number(cost) > 0 ? money(cost) : t('user.order_view.free')
    return shipping_method_label(t, method) + ' · ' + price
}

export function payment_method_label(t, method) {
    const map = {
        cash_on_delivery: 'user.order_view.pay_cash_on_delivery',
        money_transfer: 'user.order_view.pay_money_transfer',
        online: 'user.order_view.pay_online',
    }
    return map[method] ? t(map[method]) : (method || '—')
}

// Adds *_label columns to a list row from get_all_orders / get_user_purchules,
// so data tables can show them as plain text cells.
export function with_order_labels(t, row) {
    return {
        ...row,
        order_type_label: order_type_label(t, row.order_type),
        payment_label: payment_method_label(t, row.payment_method),
        shipping_label: shipping_cell(t, row.shipping_method, row.shipping_cost),
        total_label: money(row.order_total),
    }
}
