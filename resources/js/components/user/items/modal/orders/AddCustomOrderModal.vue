<template>
    <StackModal
        v-model="showModal"
        :title="$t('admin.orders.add_custom_order_title')"
        :size="'xxl'"
        @close="closeModal"
        @shown="() => { if (!submitted) this.$nextTick(() => this.$el.querySelector('input').focus()); }"
    >
        <form ref="orderForm" @submit.prevent="submitOrder" v-if="!submitted">

            <div v-if="formErrors.length > 0" class="alert alert-danger co-errors" role="alert">
                <strong>{{ $t('admin.orders.fix_problems_title', { count: formErrors.length }) }}</strong>
                <ul class="mb-0 ps-3 mt-1">
                    <li v-for="e in errorList" :key="e.key">
                        <a v-if="e.field" href="#" class="alert-link" @click.prevent="focusField(e.key)">{{ e.msg }}</a>
                        <template v-else>{{ e.msg }}</template>
                    </li>
                </ul>
            </div>

            <!-- Buyer info -->
            <h6 class="text-muted mb-2">{{ $t('admin.orders.buyer_information_title') }}</h6>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label>{{ $t('admin.orders.name_label') }} <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" :class="fieldClass('name')" data-field="name" v-model="form.name" :placeholder="$t('admin.orders.first_name_placeholder')" required>
                        <div class="invalid-feedback">{{ err('name') }}</div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label>{{ $t('admin.orders.surname_label') }} <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" :class="fieldClass('surname')" data-field="surname" v-model="form.surname" :placeholder="$t('admin.orders.last_name_placeholder')" required>
                        <div class="invalid-feedback">{{ err('surname') }}</div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label>{{ $t('common.email') }} <small class="text-muted">{{ $t('admin.orders.email_optional_hint') }}</small></label>
                        <input type="email" class="form-control" :class="fieldClass('email', true)" data-field="email" v-model.trim="form.email" :placeholder="$t('admin.orders.buyer_email_placeholder')">
                        <div class="invalid-feedback">{{ err('email') }}</div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label>{{ $t('admin.orders.phone_label') }} <small class="text-muted">{{ $t('admin.orders.phone_optional_hint') }}</small></label>
                        <input type="tel" class="form-control" v-model="form.phone" :placeholder="$t('admin.orders.phone_placeholder')">
                    </div>
                </div>
            </div>

            <!-- Existing accounts matching what's typed; picked one is linked to the order -->
            <BuyerAccountSearch
                :name="form.name"
                :surname="form.surname"
                :email="form.email"
                :phone="form.phone"
                :linked="linkedUser"
                @link="linkUser"
                @unlink="unlinkUser"
            />

            <div v-if="partnerInfo.discount > 0" class="alert alert-success">
                <i class="fa fa-check-circle"></i>
                {{ $t('admin.orders.partner_discount_detected', { org: partnerInfo.organization_name, discount: partnerInfo.discount }) }}
            </div>

            <hr>

            <!-- Delivery & payment -->
            <h6 class="text-muted mb-2">{{ $t('admin.orders.order_details_title') }}</h6>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label>{{ $t('admin.orders.shipping_method_label') }} <span class="text-danger">*</span></label>
                        <select class="form-control" v-model="form.delivery_type" :disabled="form.payment_type === 'online_payment'">
                            <option value="self_delivery">{{ $t('admin.orders.self_delivery_option') }}</option>
                            <option value="delivery">{{ $t('admin.orders.delivery_option') }}</option>
                        </select>
                        <small v-if="form.payment_type === 'online_payment'" class="text-muted">{{ $t('admin.orders.pay_now_forces_self_delivery_hint') }}</small>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label>{{ $t('admin.orders.payment_method_label') }} <span class="text-danger">*</span></label>
                        <select class="form-control" v-model="form.payment_type" required>
                            <option value="deliverd_payment">{{ $t('admin.orders.payment_on_delivery_option') }}</option>
                            <option value="mony_transfer">{{ $t('admin.orders.money_transfer_option') }}</option>
                            <option value="online_payment">{{ $t('admin.orders.pay_now_option') }}</option>
                        </select>
                        <small v-if="form.payment_type === 'online_payment'" class="text-muted">{{ $t('admin.orders.pay_now_hint') }}</small>
                    </div>
                </div>
            </div>

            <div class="row" v-if="needsAddress">
                <div class="col-md-6">
                    <div class="form-group">
                        <label>{{ $t('admin.orders.address_label') }}</label>
                        <input type="text" class="form-control" v-model="form.address" :placeholder="$t('admin.orders.street_address_placeholder')">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label>{{ $t('admin.orders.city_label') }}</label>
                        <input type="text" class="form-control" v-model="form.city" :placeholder="$t('admin.orders.city_placeholder')">
                    </div>
                </div>
            </div>
            <div class="row" v-if="needsAddress">
                <div class="col-md-6">
                    <div class="form-group">
                        <label>{{ $t('admin.orders.region_label') }} <span class="text-danger">*</span></label>
                        <select class="form-control" :class="fieldClass('region')" data-field="region" v-model="form.region_id" @change="onRegionChange">
                            <option value="">{{ $t('admin.orders.select_region_placeholder') }}</option>
                            <option v-for="r in regions" :key="r.id" :value="r.id">{{ r.region }}</option>
                        </select>
                        <div class="invalid-feedback">{{ err('region') }}</div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label>{{ $t('admin.orders.delivery_price_label') }}</label>
                        <input
                            type="number"
                            class="form-control"
                            :value="deliveryPriceDisplay"
                            @input="onDeliveryPriceInput($event.target.value)"
                            min="0"
                            step="0.01"
                            :disabled="!form.region_id"
                        >
                        <div class="small mt-1">
                            <template v-if="form.delivery_price_manual">
                                <span class="text-muted">{{ $t('admin.orders.delivery_price_manual_hint', { auto: regionRule.cost.toFixed(2) }) }}</span>
                                <button type="button" class="btn btn-link btn-sm p-0 ms-1 align-baseline" @click="resetDeliveryPrice">
                                    {{ $t('admin.orders.delivery_price_reset') }}
                                </button>
                            </template>
                            <span v-else-if="form.region_id" class="text-muted">{{ $t('admin.orders.delivery_price_auto_hint') }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Region rule: minimum order / free shipping (same as website checkout) -->
            <template v-if="needsAddress && selectedRegion">
                <div v-if="regionRule.below_min" class="alert alert-danger py-2">
                    <i class="fa fa-exclamation-triangle"></i>
                    {{ $t('admin.orders.region_min_not_reached', { region: selectedRegion.region, min: regionRule.min_order.toFixed(2), missing: (regionRule.min_order - subtotal).toFixed(2) }) }}
                </div>
                <div v-else-if="regionRule.is_free" class="alert alert-success py-2">
                    <i class="fa fa-gift"></i>
                    {{ $t('admin.orders.region_free_applied', { amount: regionRule.free_after.toFixed(2) }) }}
                </div>
                <div v-else-if="regionRule.free_after && regionRule.base_price > 0" class="alert alert-info py-2">
                    <i class="fa fa-truck"></i>
                    {{ $t('admin.orders.region_free_from', { amount: regionRule.free_after.toFixed(2), missing: (regionRule.free_after - subtotal).toFixed(2) }) }}
                </div>
            </template>

            <div class="form-group">
                <label>
                    <input type="checkbox" v-model="create_production_task">
                    {{ $t('admin.orders.allow_out_of_stock_label') }}
                </label>
            </div>

            <hr>

            <!-- Products -->
            <h6 class="text-muted mb-2">{{ $t('admin.orders.products_title') }} <span class="text-danger">*</span></h6>

            <!-- Barcode scan panel -->
            <div class="mb-2">
                <button type="button" class="btn btn-warning btn-sm mb-2" @click="toggleScan()">
                    {{ scanOpen ? $t('admin.orders.close_scanner_btn') : $t('admin.orders.scan_barcode_btn') }}
                </button>
                <div v-if="scanOpen" class="card border-warning mb-2">
                    <div class="card-body py-2 px-3">
                        <div class="d-flex align-items-center" style="gap:8px;">
                            <input
                                ref="scanInput"
                                type="text"
                                v-model="scanBarcode"
                                @keydown.enter.prevent="scanProduct()"
                                class="form-control form-control-sm"
                                :placeholder="$t('admin.orders.scan_barcode_placeholder')"
                                autocomplete="off"
                                style="max-width:320px;"
                            />
                            <button type="button" class="btn btn-primary btn-sm" @click="scanProduct()" :disabled="scanLoading || !scanBarcode.trim()">
                                <span v-if="scanLoading" class="spinner-border spinner-border-sm"></span>
                                {{ scanLoading ? '...' : $t('admin.orders.add_btn') }}
                            </button>
                        </div>
                        <div v-if="scanError" class="text-danger mt-1 small">{{ scanError }}</div>
                        <div v-if="scanSuccess" class="text-success mt-1 small">{{ scanSuccess }}</div>
                    </div>
                </div>
            </div>

            <div v-if="form.order_product_list.length === 0" class="text-center p-3 border mb-2" data-field="products"
                 :class="err('products') ? 'border-danger text-danger rounded' : 'text-muted'">
                {{ $t('admin.orders.no_products_added_msg') }}
            </div>
            <div v-for="(item, index) in form.order_product_list" :key="index" class="border rounded p-3 mb-2 bg-light"
                 :class="{ 'border-danger': rowHasError(index), 'border-success': attempted && !rowHasError(index) && item.product_option_id }">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <strong>{{ $t('admin.orders.product_number_prefix') }} {{ index + 1 }}</strong>
                    <button type="button" class="btn btn-danger btn-sm" @click="removeProduct(index)">
                        <i class="fa fa-trash"></i>
                    </button>
                </div>
                <div class="row">
                    <div class="col-md-4">
                        <label>{{ $t('admin.orders.product_label') }}</label>
                        <select class="form-control" :class="fieldClass('item.' + index + '.product')" :data-field="'item.' + index + '.product'" v-model="item.product_id" @change="loadOptions(item)" required>
                            <option value="">{{ $t('admin.orders.select_product_placeholder') }}</option>
                            <option v-for="p in products" :key="p.id" :value="p.id">{{ p.title }}</option>
                        </select>
                        <div class="invalid-feedback">{{ err('item.' + index + '.product') }}</div>
                    </div>
                    <div class="col-md-4" v-if="item.product_id">
                        <label>{{ $t('admin.orders.option_label') }}</label>
                        <select class="form-control" :class="fieldClass('item.' + index + '.option')" :data-field="'item.' + index + '.option'" v-model="item.product_option_id" @change="onOptionChange(item)" required>
                            <option value="">{{ $t('admin.orders.select_option_placeholder') }}</option>
                            <option
                                v-for="opt in item.options"
                                :key="opt.id"
                                :value="opt.id"
                                :disabled="!create_production_task && opt.quantity <= 0"
                            >
                                {{ opt.name }} ({{ opt.price }} GEL<template v-if="opt.discount > 0">, −{{ opt.discount }}% {{ $t('admin.orders.sale_from', { price: opt.list_price }) }}</template>) — {{ $t('admin.orders.stock_suffix') }} {{ opt.quantity }}
                            </option>
                        </select>
                        <div class="invalid-feedback">{{ err('item.' + index + '.option') }}</div>
                    </div>
                    <div class="col-md-2" v-if="item.product_option_id">
                        <label>{{ $t('admin.orders.qty_label') }}</label>
                        <input type="number" class="form-control" :class="fieldClass('item.' + index + '.qty')" :data-field="'item.' + index + '.qty'" v-model.number="item.quantity" min="1" :max="item.max_quantity" required>
                        <div class="invalid-feedback">{{ err('item.' + index + '.qty') }}</div>
                        <small class="text-muted">{{ $t('admin.orders.max_prefix') }} {{ item.max_quantity }}</small>
                    </div>
                    <div class="col-md-2 d-flex align-items-center" v-if="item.option_image">
                        <img loading="lazy" decoding="async" :src="'/public/images/product_option_img/' + item.option_image" class="img-thumbnail" style="max-width:70px;max-height:70px;">
                    </div>
                </div>
            </div>
            <button type="button" class="btn btn-success btn-sm mb-3" @click="addProduct">
                <i class="fa fa-plus"></i> {{ $t('admin.orders.add_product_btn') }}
            </button>
            <button type="button" class="btn btn-outline-secondary btn-sm mb-3 ms-2" @click="exportInvoice" :disabled="exportingInvoice || form.order_product_list.length === 0">
                <span v-if="exportingInvoice" class="spinner-border spinner-border-sm"></span>
                <i v-else class="fa fa-file-pdf-o"></i> {{ $t('admin.orders.export_invoice_btn') }}
            </button>
            <select class="form-control form-control-sm d-inline-block ms-2 mb-3" style="width:auto;" v-model="invoiceLocale">
                <option value="ka">ქართული</option>
                <option value="en">English</option>
            </select>
            <div v-if="exportError" class="text-danger small mb-2">{{ exportError }}</div>

            <hr>

            <!-- Price breakdown -->
            <h6 class="text-muted mb-2">{{ $t('admin.orders.price_breakdown_title') }}</h6>
            <div class="card">
                <div class="card-body py-2">
                    <div class="d-flex justify-content-between">
                        <span>{{ $t('admin.orders.subtotal_label') }}</span>
                        <span>{{ subtotal.toFixed(2) }} ₾</span>
                    </div>
                    <div v-if="discountPercent > 0" class="d-flex justify-content-between text-success">
                        <span>{{ $t('admin.orders.discount_label') }} ({{ partnerInfo.organization_name }}, -{{ discountPercent }}%)</span>
                        <span>-{{ discountAmount.toFixed(2) }} ₾</span>
                    </div>
                    <div v-if="needsAddress" class="d-flex justify-content-between">
                        <span>{{ $t('admin.orders.delivery_price_label') }}</span>
                        <span v-if="deliveryPriceDisplay === 0 && regionRule.is_free && !form.delivery_price_manual" class="text-success">{{ $t('user.order_view.free') }}</span>
                        <span v-else>{{ deliveryPriceDisplay.toFixed(2) }} ₾</span>
                    </div>
                    <hr class="my-2">
                    <div class="d-flex justify-content-between fw-bold" style="font-size:1.1em;">
                        <span>{{ $t('admin.orders.grand_total_label') }}</span>
                        <span>{{ grandTotal.toFixed(2) }} ₾</span>
                    </div>
                </div>
            </div>
            <!-- Same problems again at the bottom, next to where the order is submitted -->
            <div v-if="formErrors.length > 0" class="alert alert-danger co-errors mt-3 mb-0" role="alert">
                <strong>{{ $t('admin.orders.fix_problems_title', { count: formErrors.length }) }}</strong>
                <ul class="mb-0 ps-3 mt-1">
                    <li v-for="e in errorList" :key="e.key">
                        <a v-if="e.field" href="#" class="alert-link" @click.prevent="focusField(e.key)">{{ e.msg }}</a>
                        <template v-else>{{ e.msg }}</template>
                    </li>
                </ul>
            </div>
        </form>

        <!-- Success state -->
        <div v-if="submitted" class="text-center p-4">
            <i class="fa fa-check-circle text-success" style="font-size:2.5em;"></i>
            <h5 class="mt-2">{{ $t('admin.orders.order_created_msg', { id: createdOrderId }) }}</h5>
            <div v-if="matchedUsers.length > 0" class="alert alert-info mt-3 text-start">
                <strong>{{ $t('admin.orders.linked_to_existing_accounts_prefix', { plural: matchedUsers.length > 1 ? 's' : '' }) }}</strong>
                <ul class="mb-0 mt-1">
                    <li v-for="u in matchedUsers" :key="u.id">
                        {{ u.name }} {{ u.surname }} — {{ u.email }}
                    </li>
                </ul>
            </div>
            <div v-else class="alert alert-secondary mt-3">
                {{ $t('admin.orders.no_existing_account_matched') }}
            </div>
            <div v-if="appliedDiscount && appliedDiscount.discount > 0" class="alert alert-success mt-3">
                <i class="fa fa-check-circle"></i>
                {{ $t('admin.orders.partner_discount_applied', { org: appliedDiscount.organization_name, discount: appliedDiscount.discount }) }}
            </div>
        </div>

        <template #footer>
            <div class="d-flex gap-2 justify-content-end align-items-center p-3">
                <button v-if="!submitted && formErrors.length" type="button" class="btn btn-link text-danger me-auto p-0" @click="focusFirstError">
                    <i class="fa fa-exclamation-circle"></i> {{ $t('admin.orders.problems_footer', { count: formErrors.length }) }}
                </button>
                <span v-else-if="!submitted && attempted" class="text-success small me-auto">
                    <i class="fa fa-check-circle"></i> {{ $t('admin.orders.all_fields_valid') }}
                </span>
                <button v-if="!submitted" type="button" class="btn btn-primary" @click="submitOrder" :disabled="submitting">
                    {{ submitting ? $t('admin.orders.saving_btn') : $t('admin.orders.create_order_btn') }}
                </button>
                <button type="button" class="btn btn-secondary" @click="closeModal">
                    {{ submitted ? $t('common.close') : $t('common.cancel') }}
                </button>
            </div>
        </template>
    </StackModal>
</template>

<script>
import BuyerAccountSearch from './assets/BuyerAccountSearch.vue';

// Option price after its own sale discount — mirrors ProductService::option_sale_price().
function sale_price(opt) {
    const price = parseFloat(opt.price) || 0;
    const pct = Math.min(100, Math.max(0, parseFloat(opt.discount) || 0));
    return Math.round((price - price * pct / 100) * 100) / 100;
}

export default {
    emits: ['orderAdded'],
    components: { BuyerAccountSearch },
    data() {
        return {
            showModal: false,
            products: [],
            regions: [],
            form: {
                name: '',
                surname: '',
                email: '',
                phone: '',
                address: '',
                city: '',
                delivery_type: 'self_delivery',
                payment_type: 'deliverd_payment',
                region_id: '',
                delivery_price: null,
                delivery_price_manual: false,
                linked_user_id: null,
                order_product_list: [],
            },
            create_production_task: false,
            submitting: false,
            submitted: false,
            createdOrderId: null,
            matchedUsers: [],
            partnerInfo: { discount: 0, organization_name: null },
            appliedDiscount: null,
            partnerCheckTimeout: null,
            // Validation: `attempted` turns on live field checks after the first
            // Create / Export click; server errors are mapped onto fields too.
            attempted: false,
            validationMode: 'order',
            serverErrors: {},
            serverGeneral: [],
            scanOpen: false,
            scanBarcode: '',
            scanLoading: false,
            scanError: null,
            scanSuccess: null,
            exportingInvoice: false,
            exportError: null,
            invoiceLocale: 'ka',
            linkedUser: null,
        };
    },
    computed: {
        // field key → message, from the current form (live after the first attempt).
        validationErrors() {
            const e = {};
            const t = (k, p) => this.$t('admin.orders.' + k, p);
            if (!(this.form.name || '').trim()) e.name = t('err_name_required');
            if (!(this.form.surname || '').trim()) e.surname = t('err_surname_required');
            if (this.form.email && !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(this.form.email)) e.email = t('err_email_invalid');
            if (!this.form.order_product_list.length) e.products = t('please_add_product');
            this.form.order_product_list.forEach((item, i) => {
                if (!item.product_id) { e['item.' + i + '.product'] = t('err_select_product'); return; }
                if (!item.product_option_id) { e['item.' + i + '.option'] = t('err_select_option'); return; }
                const q = Number(item.quantity);
                if (!Number.isInteger(q) || q < 1) e['item.' + i + '.qty'] = t('err_qty_invalid');
                else if (!this.create_production_task && item.max_quantity && q > item.max_quantity) e['item.' + i + '.qty'] = t('err_qty_max', { max: item.max_quantity });
            });
            if (this.validationMode === 'order' && this.needsAddress) {
                if (!this.form.region_id) e.region = t('region_required_for_delivery');
                else if (this.regionRule.below_min) e.region = t('region_min_not_reached', {
                    region: this.selectedRegion.region,
                    min: this.regionRule.min_order.toFixed(2),
                    missing: (this.regionRule.min_order - this.subtotal).toFixed(2),
                });
            }
            return e;
        },
        errorMap() {
            return this.attempted ? { ...this.validationErrors, ...this.serverErrors } : { ...this.serverErrors };
        },
        // Shown at the top, at the bottom and counted in the footer.
        errorList() {
            return [
                ...Object.entries(this.errorMap).map(([key, msg]) => ({ key, msg, field: true })),
                ...this.serverGeneral.map((msg, i) => ({ key: 'general.' + i, msg, field: false })),
            ];
        },
        formErrors() {
            return this.errorList.map(e => e.msg);
        },
        // Pay Now orders skip address collection entirely — the buyer sorts
        // delivery out with the seller separately once they've paid.
        needsAddress() {
            return this.form.delivery_type === 'delivery' && this.form.payment_type !== 'online_payment';
        },
        subtotal() {
            return this.form.order_product_list.reduce((sum, item) => {
                const opt = item.options.find(o => o.id == item.product_option_id);
                const price = opt ? parseFloat(opt.price) || 0 : 0;
                return sum + price * (item.quantity || 0);
            }, 0);
        },
        discountPercent() {
            return this.partnerInfo.discount > 0 ? this.partnerInfo.discount : 0;
        },
        discountAmount() {
            return this.discountPercent > 0 ? this.subtotal * this.discountPercent / 100 : 0;
        },
        selectedRegion() {
            return this.regions.find(r => r.id == this.form.region_id) || null;
        },
        // Preview of OrderService::region_shipping() — the server re-checks it on save.
        // Subtotal is before the partner discount, like website checkout.
        regionRule() {
            const r = this.selectedRegion;
            const num = v => (v === null || v === undefined || v === '' ? null : parseFloat(v));
            const base = r ? (num(r.shiping_price) || 0) : 0;
            const min = r ? num(r.ship_min_price) : null;
            const freeAfter = r ? num(r.free_shiping_price_after) : null;
            const isFree = base > 0 && freeAfter !== null && freeAfter > 0 && this.subtotal >= freeAfter;
            return {
                base_price: base,
                cost: isFree ? 0 : base,
                is_free: isFree,
                below_min: !!min && this.subtotal < min,
                min_order: min,
                free_after: freeAfter && freeAfter > 0 ? freeAfter : null,
            };
        },
        deliveryPriceDisplay() {
            if (!this.needsAddress) return 0;
            if (this.form.delivery_price_manual) return parseFloat(this.form.delivery_price) || 0;
            return this.regionRule.cost;
        },
        grandTotal() {
            return this.subtotal - this.discountAmount + this.deliveryPriceDisplay;
        },
        // Anything typed or added that isn't saved yet - checked before a
        // close/cancel to decide whether to ask for confirmation.
        hasUnsavedChanges() {
            return !!(
                this.form.name || this.form.surname || this.form.email || this.form.phone ||
                this.form.address || this.form.city ||
                this.form.order_product_list.length > 0
            );
        },
    },
    mounted() {
        this.fetchProducts();
        this.fetchRegions();
    },
    watch: {
        // A server-side field error is about the old value — drop it once the form is edited.
        form: {
            deep: true,
            handler() { if (Object.keys(this.serverErrors).length) this.serverErrors = {}; },
        },
        'form.email'() { this.queuePartnerCheck(); },
        'form.name'() { this.queuePartnerCheck(); },
        'form.surname'() { this.queuePartnerCheck(); },
        'form.phone'() { this.queuePartnerCheck(); },
        'form.payment_type'(val) {
            // Pay Now is always picked up in person / arranged directly with
            // the seller - lock the shipping method to self-delivery so the
            // (now-hidden) delivery address fields can't be half-filled.
            if (val === 'online_payment') {
                this.form.delivery_type = 'self_delivery';
            }
        },
    },
    methods: {
        // Debounced so the lookup doesn't fire on every keystroke - only
        // once the admin pauses typing in the name/surname/email fields.
        queuePartnerCheck() {
            clearTimeout(this.partnerCheckTimeout);
            this.partnerCheckTimeout = setTimeout(() => this.checkPartnerDiscount(), 500);
        },
        checkPartnerDiscount() {
            if (!this.form.email && !this.form.phone && !(this.form.name && this.form.surname)) {
                this.partnerInfo = { discount: 0, organization_name: null };
                return;
            }
            axios.get('/custom_order/check_partner_discount', {
                params: {
                    email: this.form.email || null,
                    name: this.form.name || null,
                    surname: this.form.surname || null,
                    phone: this.form.phone || null,
                },
            })
                .then(r => {
                    this.partnerInfo = { discount: r.data.discount, organization_name: r.data.organization_name };
                    // Fill in whichever contact fields the admin hasn't typed
                    // yet from a matching account/partner record - never
                    // overwrite something they already entered themselves.
                    if (!this.form.email && r.data.email) this.form.email = r.data.email;
                    if (!this.form.name && r.data.name) this.form.name = r.data.name;
                    if (!this.form.surname && r.data.surname) this.form.surname = r.data.surname;
                    if (!this.form.phone && r.data.phone) this.form.phone = r.data.phone;
                })
                .catch(() => { this.partnerInfo = { discount: 0, organization_name: null }; });
        },
        fetchProducts() {
            axios.get('/custom_order/get_products')
                .then(r => { this.products = r.data; })
                .catch(() => {});
        },
        fetchRegions() {
            axios.get('/get_shiped_region/get_all_shiped_regions')
                .then(r => { this.regions = r.data; })
                .catch(() => {});
        },
        // A new region goes back to the automatic (rule-based) price.
        onRegionChange() {
            this.resetDeliveryPrice();
        },
        // Link an existing account; fill only the buyer fields still empty.
        linkUser(u) {
            this.linkedUser = u;
            this.form.linked_user_id = u.id;
            if (!this.form.name && u.name) this.form.name = u.name;
            if (!this.form.surname && u.surname) this.form.surname = u.surname;
            if (!this.form.email && u.email) this.form.email = u.email;
            if (!this.form.phone && u.phone_number) this.form.phone = u.phone_number;
            if (!this.form.city && u.city) this.form.city = u.city;
            this.checkPartnerDiscount();
        },
        unlinkUser() {
            this.linkedUser = null;
            this.form.linked_user_id = null;
        },
        onDeliveryPriceInput(value) {
            this.form.delivery_price = value === '' ? null : parseFloat(value);
            this.form.delivery_price_manual = true;
        },
        resetDeliveryPrice() {
            this.form.delivery_price = null;
            this.form.delivery_price_manual = false;
        },
        show_modal() {
            this.showModal = true;
            this.submitted = false;
            this.matchedUsers = [];
            this.createdOrderId = null;
            this.clearErrors();
        },
        closeModal() {
            if (!this.submitted && this.hasUnsavedChanges) {
                if (!confirm(this.$t('admin.orders.confirm_discard_unsaved'))) {
                    return;
                }
            }
            this.showModal = false;
            this.resetForm();
        },
        toggleScan() {
            this.scanOpen = !this.scanOpen;
            this.scanBarcode = '';
            this.scanError = null;
            this.scanSuccess = null;
            if (this.scanOpen) {
                this.$nextTick(() => {
                    this.$refs.scanInput && this.$refs.scanInput.focus();
                });
            }
        },

        scanProduct() {
            const barcode = this.scanBarcode.trim();
            if (!barcode) return;
            this.scanLoading = true;
            this.scanError = null;
            this.scanSuccess = null;
            axios.get(`/set_product/set_product_option/find_by_barcode/${encodeURIComponent(barcode)}`)
                .then(r => {
                    const data = r.data;
                    const opt = data.option;
                    const product = data.product;
                    const stock = data.quantity || 0;
                    const maxQty = this.create_production_task ? 9999 : (stock || 1);
                    this.form.order_product_list.push({
                        product_id: product.id,
                        product_option_id: opt.id,
                        quantity: 1,
                        max_quantity: maxQty,
                        option_image: data.image || null,
                        options: [{
                            id: opt.id,
                            name: opt.name,
                            // Raw option from the barcode lookup → apply its own sale
                            // discount (same rule as ProductService::option_sale_price).
                            price: sale_price(opt),
                            list_price: parseFloat(opt.price) || 0,
                            discount: parseFloat(opt.discount) || 0,
                            quantity: stock,
                        }],
                    });
                    this.scanSuccess = this.$t('admin.orders.added_product_stock_msg', { product: product.title, option: opt.name, stock: stock });
                    this.scanBarcode = '';
                    this.$nextTick(() => {
                        this.$refs.scanInput && this.$refs.scanInput.focus();
                    });
                })
                .catch(error => {
                    this.scanError = error.response?.data?.error || this.$t('admin.orders.barcode_not_found');
                    this.scanBarcode = '';
                    this.$nextTick(() => {
                        this.$refs.scanInput && this.$refs.scanInput.focus();
                    });
                })
                .finally(() => {
                    this.scanLoading = false;
                });
        },

        addProduct() {
            this.form.order_product_list.push({
                product_id: '',
                product_option_id: '',
                quantity: 1,
                max_quantity: 9999,
                option_image: null,
                options: [],
            });
        },
        removeProduct(index) {
            this.form.order_product_list.splice(index, 1);
        },
        loadOptions(item) {
            item.product_option_id = '';
            item.options = [];
            if (!item.product_id) return;
            axios.get(`/custom_order/get_product_options/${item.product_id}`)
                .then(r => { item.options = r.data; })
                .catch(() => {});
        },
        onOptionChange(item) {
            const opt = item.options.find(o => o.id == item.product_option_id);
            if (opt) {
                item.max_quantity = this.create_production_task ? 9999 : (opt.quantity || 1);
                item.option_image = opt.image || null;
                if (item.quantity > item.max_quantity) item.quantity = item.max_quantity;
            }
        },
        resetForm() {
            this.form = {
                name: '', surname: '', email: '', phone: '',
                address: '', city: '',
                delivery_type: 'self_delivery',
                payment_type: 'deliverd_payment',
                region_id: '',
                delivery_price: null,
                delivery_price_manual: false,
                linked_user_id: null,
                order_product_list: [],
            };
            this.create_production_task = false;
            this.submitted = false;
            this.submitting = false;
            this.matchedUsers = [];
            this.createdOrderId = null;
            this.partnerInfo = { discount: 0, organization_name: null };
            this.appliedDiscount = null;
            this.linkedUser = null;
            this.clearErrors();
            this.scanOpen = false;
            this.scanBarcode = '';
            this.scanError = null;
            this.scanSuccess = null;
            this.exportingInvoice = false;
            this.exportError = null;
            this.invoiceLocale = 'ka';
        },
        // Messages that don't belong to one field (server / network errors).
        showFormErrors(...errors) {
            this.serverGeneral = errors;
            this.$nextTick(() => this.focusFirstError());
        },
        clearErrors() {
            this.attempted = false;
            this.serverErrors = {};
            this.serverGeneral = [];
        },
        // Runs the live checks; returns true when the form is fine.
        validate(mode) {
            this.validationMode = mode;
            this.attempted = true;
            this.serverErrors = {};
            this.serverGeneral = [];
            const ok = Object.keys(this.validationErrors).length === 0;
            if (!ok) this.$nextTick(() => this.focusFirstError());
            return ok;
        },
        // CSS state for a field: red with a message, green once valid (optional
        // fields only turn green when filled in).
        fieldClass(key, optional = false) {
            if (this.errorMap[key]) return 'is-invalid';
            if (!this.attempted) return '';
            if (optional && !this.fieldValue(key)) return '';
            return 'is-valid';
        },
        err(key) {
            return this.errorMap[key] || '';
        },
        fieldValue(key) {
            if (key === 'email') return this.form.email;
            return true;
        },
        rowHasError(index) {
            const prefix = 'item.' + index + '.';
            return Object.keys(this.errorMap).some(k => k.startsWith(prefix));
        },
        focusField(key) {
            const root = this.$refs.orderForm;
            if (!root) return;
            const el = root.querySelector('[data-field="' + key + '"]');
            if (!el) return;
            el.scrollIntoView({ behavior: 'smooth', block: 'center' });
            if (typeof el.focus === 'function') el.focus({ preventScroll: true });
        },
        focusFirstError() {
            const first = this.errorList.find(e => e.field);
            if (first) this.focusField(first.key);
            else this.$refs.orderForm?.querySelector('.co-errors')?.scrollIntoView({ behavior: 'smooth', block: 'center' });
        },
        // Laravel validation keys → our field keys.
        mapServerErrors(fieldErrors) {
            const mapped = {}, general = [];
            Object.entries(fieldErrors).forEach(([key, msgs]) => {
                const msg = [].concat(msgs)[0];
                let m;
                if (key === 'name' || key === 'surname' || key === 'email') mapped[key] = msg;
                else if (key === 'region_id') mapped.region = msg;
                else if (key === 'order_product_list') mapped.products = msg;
                else if ((m = key.match(/^order_product_list\.(\d+)\.(quantity|product_option_id|product_id)$/))) {
                    mapped['item.' + m[1] + '.' + ({ quantity: 'qty', product_option_id: 'option', product_id: 'product' })[m[2]]] = msg;
                } else general.push(msg);
            });
            return { mapped, general };
        },
        exportInvoice() {
            if (!this.validate('invoice')) return;

            this.exportingInvoice = true;
            this.exportError = null;
            axios.post('/custom_order/export_invoice_pdf', {
                name: this.form.name,
                surname: this.form.surname,
                email: this.form.email,
                phone: this.form.phone,
                address: this.form.address,
                city: this.form.city,
                locale: this.invoiceLocale,
                order_product_list: this.form.order_product_list,
            }, { responseType: 'blob' })
            .then(response => {
                const contentDisposition = response.headers['content-disposition'];
                const filename = contentDisposition
                    ? contentDisposition.split('filename=')[1].replace(/"/g, '')
                    : 'invoice.pdf';
                const url = window.URL.createObjectURL(new Blob([response.data]));
                const link = document.createElement('a');
                link.href = url;
                link.setAttribute('download', filename);
                document.body.appendChild(link);
                link.click();
                document.body.removeChild(link);
                window.URL.revokeObjectURL(url);
            })
            .catch(() => {
                this.exportError = this.$t('admin.orders.failed_export_invoice');
            })
            .finally(() => {
                this.exportingInvoice = false;
            });
        },
        submitOrder() {
            if (!this.validate('order')) return;

            this.submitting = true;
            axios.post('/custom_order/store', {
                ...this.form,
                create_production_task: this.create_production_task,
            })
            .then(res => {
                this.createdOrderId = res.data.order_id;
                this.matchedUsers = res.data.matched_users || [];
                this.appliedDiscount = res.data.partner_discount || null;
                this.submitted = true;
                this.$emit('orderAdded');
            })
            .catch(error => {
                const data = error.response?.data || {};
                if (data.errors) {
                    const { mapped, general } = this.mapServerErrors(data.errors);
                    this.serverErrors = mapped;
                    this.showFormErrors(...general);
                } else if (data.code === 'below_min_order' || data.code === 'region_required') {
                    this.serverErrors = { region: data.error };
                    this.showFormErrors();
                } else {
                    this.showFormErrors(data.error || data.message || this.$t('admin.orders.error_creating_order'));
                }
            })
            .finally(() => {
                this.submitting = false;
            });
        },
    },
};
</script>
