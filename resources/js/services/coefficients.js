// Admin-editable coefficients (Site Options -> Coefficients). The server prints
// the full slug -> value map into every page as window.__COEFFICIENTS__
// (resources/views/partials/coefficients.blade.php), so reading one is
// synchronous and never costs an API call. Defaults for every slug live in
// config/coefficients.php, so the map is always complete; `fallback` only
// matters if the page was somehow rendered without the partial.

export function coef(slug, fallback = 0) {
    const all = (typeof window !== 'undefined' && window.__COEFFICIENTS__) || {}
    const value = parseFloat(all[slug])
    return Number.isFinite(value) ? value : fallback
}

// Delivery period in business days as "min-max", for an order/cart that
// does or doesn't contain a made-to-order product.
export function deliveryDays(hasProducedByOrder) {
    const type = hasProducedByOrder ? 'produced_by_order' : 'online_order'
    const fallback = hasProducedByOrder ? [5, 9] : [2, 4]
    return coef(`delivery_${type}_min_days`, fallback[0]) + '-' + coef(`delivery_${type}_max_days`, fallback[1])
}
