// Shared data calls for the checkout steps (orderPaymentPageComponent →
// orderDeclorationPageComponent). Prices / shipping / discount always come
// from the server quote (POST set_order/checkout_quote → OrderPlacementService::
// quote_website), never computed in the browser.

export function load_cart() {
    return axios.get('cart').then(r => (Array.isArray(r.data) ? r.data : []))
}

export function fetch_quote(cart, { adres = null, sale_code = null, locale = 'en' } = {}) {
    return axios.post('set_order/checkout_quote', {
        order_product_list: cart.map(i => ({
            product: { id: i.product && i.product.id },
            option: { id: i.option && i.option.id },
            quantity: i.quantity,
        })),
        adres,
        sale_code,
        locale,
    }).then(r => r.data)
}

export function online_payment_enabled() {
    return axios.get('payment/status').then(r => !!r.data.shop_enabled).catch(() => false)
}

export function quote_locale(i18n) {
    return i18n && i18n.locale === 'ka' ? 'ka' : 'en'
}

// The checkout pages render without the panel's left menu.
export function full_width_layout() {
    document.body.style.marginLeft = '0'
    const nav = document.querySelector('.admin_page_header_navbar')
    if (nav) nav.style.marginLeft = '0'
}
