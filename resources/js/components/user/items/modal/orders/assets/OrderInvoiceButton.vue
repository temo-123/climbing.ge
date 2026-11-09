<template>
    <div class="order-invoice">
        <div class="btn-group">
            <button type="button" class="btn btn-outline-primary" :disabled="loading" @click="download">
                <span v-if="loading" class="spinner-border spinner-border-sm"></span>
                <i v-else class="fa fa-file-pdf-o"></i>
                {{ loading ? $t('user.order_view.invoice_generating') : $t('user.order_view.invoice_btn') }}
            </button>
            <select v-model="locale" class="form-select order-invoice__lang" :aria-label="$t('user.order_view.invoice_lang')" :disabled="loading">
                <option value="ka">KA</option>
                <option value="en">EN</option>
            </select>
        </div>
        <div v-if="error" class="text-danger small mt-1">{{ error }}</div>
    </div>
</template>

<script>
// Downloads the order invoice PDF (OrderService::invoice_data).
// mine=true uses the buyer-only endpoint (own orders); otherwise the admin one.
export default {
    props: {
        order_id: { type: [Number, String], required: true },
        mine: { type: Boolean, default: false },
    },
    data() {
        return {
            loading: false,
            error: null,
            locale: this.$i18n?.locale === 'en' || this.$i18n?.locale === 'us' ? 'en' : 'ka',
        }
    },
    methods: {
        download() {
            this.loading = true
            this.error = null
            const url = (this.mine ? 'get_order/my_invoice_pdf/' : 'get_order/invoice_pdf/') + this.order_id
            axios.get(url, { params: { locale: this.locale }, responseType: 'blob' })
                .then(response => {
                    const cd = response.headers['content-disposition'] || ''
                    const match = cd.match(/filename="?([^";]+)"?/)
                    const filename = match ? match[1] : 'invoice-' + this.order_id + '.pdf'
                    const href = window.URL.createObjectURL(new Blob([response.data], { type: 'application/pdf' }))
                    const link = document.createElement('a')
                    link.href = href
                    link.setAttribute('download', filename)
                    document.body.appendChild(link)
                    link.click()
                    document.body.removeChild(link)
                    window.URL.revokeObjectURL(href)
                })
                .catch(() => { this.error = this.$t('user.order_view.invoice_failed') })
                .finally(() => { this.loading = false })
        },
    },
}
</script>

<style scoped>
.order-invoice__lang {
    width: auto;
    flex: 0 0 auto;
    border-top-left-radius: 0;
    border-bottom-left-radius: 0;
}
</style>
