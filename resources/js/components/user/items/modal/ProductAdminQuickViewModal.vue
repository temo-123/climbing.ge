<template>
    <stack-modal
        :show="is_modal_show"
        :title="modal_title"
        size="lg"
        @close="close_modal"
        :saveButton="{ visible: false }"
        :cancelButton="{ visible: false }"
    >
        <div v-if="loading" class="text-center py-5">
            <i class="fa fa-spinner fa-spin fa-2x"></i>
        </div>

        <div v-else-if="error" class="alert alert-danger">
            {{ $t('admin.shop.quick_view.failed_load_product') }}
        </div>

        <div v-else-if="product" class="qv-layout">
            <!-- Image -->
            <div class="qv-image-wrap">
                <div class="qv-image-slider" v-if="product.product_images && product.product_images.length > 0">
                    <button v-if="product.product_images.length > 1" class="qv-nav qv-nav-prev" @click="prev_image">&#8249;</button>
                    <img
                        :src="'/public/images/product_option_img/' + product.product_images[current_index]"
                        class="qv-main-img"
                        :alt="product.locale_product ? product.locale_product.title : ''"
                    />
                    <button v-if="product.product_images.length > 1" class="qv-nav qv-nav-next" @click="next_image">&#8250;</button>
                </div>
                <img
                    v-else
                    src="/public/images/site_img/shop_demo.jpg"
                    class="qv-main-img"
                    :alt="$t('admin.shop.quick_view.no_image_alt')"
                />
                <div v-if="product.product_images && product.product_images.length > 1" class="qv-dots">
                    <button
                        v-for="(img, i) in product.product_images"
                        :key="i"
                        class="qv-dot"
                        :class="{ active: i === current_index }"
                        @click="current_index = i"
                    ></button>
                </div>
            </div>

            <!-- Info -->
            <div class="qv-info">
                <h4 class="qv-title">{{ product.locale_product ? product.locale_product.title : '—' }}</h4>

                <div class="qv-price">
                    <span v-if="product.min_price != product.max_price">
                        {{ product.min_price }} ₾ — {{ product.max_price }} ₾
                    </span>
                    <span v-else>{{ product.max_price }} ₾</span>
                    <span v-if="product.global_product && product.global_product.discount" class="qv-discount">
                        -{{ product.global_product.discount }}%
                    </span>
                </div>

                <table class="table table-sm table-bordered mt-2">
                    <tbody>
                        <tr>
                            <th>{{ $t('admin.common.published') }}</th>
                            <td>
                                <span v-if="product.global_product && product.global_product.published" class="badge badge-success">{{ $t('admin.export.yes_label') }}</span>
                                <span v-else class="badge badge-secondary">{{ $t('admin.site_info.no_label') }}</span>
                            </td>
                        </tr>
                        <tr v-if="product.global_product && product.global_product.made_in_georgia">
                            <th>{{ $t('admin.shop.quick_view.made_in_georgia_label') }}</th>
                            <td><span class="badge badge-info">{{ $t('admin.export.yes_label') }}</span></td>
                        </tr>
                        <tr v-if="product.global_product && product.global_product.is_sale_product">
                            <th>{{ $t('admin.shop.quick_view.sale_product_label') }}</th>
                            <td><span class="badge badge-warning">{{ $t('admin.export.yes_label') }}</span></td>
                        </tr>
                        <tr v-if="product.global_product && product.global_product.sale_type === 'donation'">
                            <th>{{ $t('admin.shop.col_donation') }}</th>
                            <td><span class="badge badge-warning">{{ $t('admin.export.yes_label') }}</span></td>
                        </tr>
                        <tr v-if="product.global_product && product.global_product.sale_type === 'outlet'">
                            <th>{{ $t('admin.shop.col_outlet') }}</th>
                            <td><span class="badge badge-warning">{{ $t('admin.export.yes_label') }}</span></td>
                        </tr>
                    </tbody>
                </table>

                <div
                    v-if="product.locale_product && product.locale_product.text"
                    class="qv-description"
                    v-html="product.locale_product.text"
                ></div>
            </div>
        </div>

        <template #footer>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" @click="close_modal">{{ $t('common.close') }}</button>
                <a
                    v-if="product && product.global_product"
                    :href="shop_base + '/product/' + product.global_product.url_title"
                    target="_blank"
                    class="btn btn-primary"
                >
                    <i class="fa fa-external-link"></i> {{ $t('admin.shop.quick_view.show_product_page_btn') }}
                </a>
            </div>
        </template>
    </stack-modal>
</template>

<script>
export default {
    name: 'ProductAdminQuickViewModal',
    data() {
        return {
            is_modal_show: false,
            loading: false,
            error: false,
            product: null,
            current_index: 0,
        }
    },
    computed: {
        modal_title() {
            return this.product && this.product.locale_product
                ? this.product.locale_product.title
                : this.$t('admin.shop.quick_view.product_quick_view_title')
        },
        shop_base() {
            const ssh = (process.env.MIX_APP_SSH || '').replace(/\/$/, '')
            const shop = (process.env.MIX_SHOP_URL || '').replace(/^\/|\/$/g, '')
            return ssh + '/' + shop
        },
    },
    methods: {
        show_modal(product_id) {
            this.is_modal_show = true
            this.loading = true
            this.error = false
            this.product = null
            this.current_index = 0
            const lang = localStorage.getItem('lang') || 'us'
            axios.get('/get_product/get_quick_product/' + lang + '/' + product_id)
                .then(response => {
                    this.product = response.data[0] || null
                })
                .catch(() => { this.error = true })
                .finally(() => { this.loading = false })
        },
        close_modal() {
            this.is_modal_show = false
            this.product = null
            this.error = false
            this.current_index = 0
        },
        prev_image() {
            const len = this.product.product_images.length
            this.current_index = this.current_index > 0 ? this.current_index - 1 : len - 1
        },
        next_image() {
            const len = this.product.product_images.length
            this.current_index = this.current_index < len - 1 ? this.current_index + 1 : 0
        },
    }
}
</script>

<style scoped>
.qv-layout {
    display: flex;
    gap: 24px;
    align-items: flex-start;
}
.qv-image-wrap {
    flex: 0 0 300px;
}
.qv-image-slider {
    position: relative;
}
.qv-main-img {
    width: 100%;
    height: 300px;
    object-fit: cover;
    border-radius: 6px;
    display: block;
}
.qv-nav {
    position: absolute;
    top: 50%;
    transform: translateY(-50%);
    background: rgba(0,0,0,0.45);
    color: #fff;
    border: none;
    border-radius: 50%;
    width: 36px;
    height: 36px;
    font-size: 22px;
    cursor: pointer;
    z-index: 2;
    line-height: 1;
}
.qv-nav-prev { left: 6px; }
.qv-nav-next { right: 6px; }
.qv-dots {
    display: flex;
    justify-content: center;
    gap: 6px;
    margin-top: 8px;
}
.qv-dot {
    width: 9px;
    height: 9px;
    border-radius: 50%;
    border: none;
    background: #ccc;
    cursor: pointer;
}
.qv-dot.active { background: #007bff; }
.qv-info {
    flex: 1;
}
.qv-title {
    font-weight: 700;
    margin-bottom: 10px;
}
.qv-price {
    font-size: 1.3em;
    font-weight: 600;
    color: #007bff;
    margin-bottom: 12px;
}
.qv-discount {
    font-size: 0.75em;
    background: #f54d5c;
    color: #fff;
    padding: 2px 7px;
    border-radius: 4px;
    margin-left: 8px;
    vertical-align: middle;
}
.qv-description {
    margin-top: 12px;
    max-height: 200px;
    overflow-y: auto;
    color: #555;
    line-height: 1.6;
}
@media (max-width: 600px) {
    .qv-layout { flex-direction: column; }
    .qv-image-wrap { flex: none; width: 100%; }
}
</style>
