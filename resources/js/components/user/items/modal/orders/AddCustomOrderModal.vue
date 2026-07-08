<template>
    <StackModal
        v-model="showModal"
        :title="$t('admin.orders.add_custom_order_title')"
        @close="closeModal"
        @shown="() => { if (!submitted) this.$nextTick(() => this.$el.querySelector('input').focus()); }"
    >
        <form @submit.prevent="submitOrder" v-if="!submitted">

            <!-- Buyer info -->
            <h6 class="text-muted mb-2">{{ $t('admin.orders.buyer_information_title') }}</h6>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label>{{ $t('admin.orders.name_label') }} <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" v-model="form.name" :placeholder="$t('admin.orders.first_name_placeholder')" required>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label>{{ $t('admin.orders.surname_label') }} <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" v-model="form.surname" :placeholder="$t('admin.orders.last_name_placeholder')" required>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label>{{ $t('common.email') }} <small class="text-muted">{{ $t('admin.orders.email_optional_hint') }}</small></label>
                        <input type="email" class="form-control" v-model="form.email" :placeholder="$t('admin.orders.buyer_email_placeholder')">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label>{{ $t('admin.orders.phone_label') }} <small class="text-muted">{{ $t('admin.orders.phone_optional_hint') }}</small></label>
                        <input type="tel" class="form-control" v-model="form.phone" :placeholder="$t('admin.orders.phone_placeholder')">
                    </div>
                </div>
            </div>

            <hr>

            <!-- Delivery & payment -->
            <h6 class="text-muted mb-2">{{ $t('admin.orders.order_details_title') }}</h6>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label>{{ $t('admin.orders.shipping_method_label') }} <span class="text-danger">*</span></label>
                        <select class="form-control" v-model="form.delivery_type">
                            <option value="self_delivery">{{ $t('admin.orders.self_delivery_option') }}</option>
                            <option value="delivery">{{ $t('admin.orders.delivery_option') }}</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label>{{ $t('admin.orders.payment_method_label') }} <span class="text-danger">*</span></label>
                        <select class="form-control" v-model="form.payment_type" required>
                            <option value="deliverd_payment">{{ $t('admin.orders.payment_on_delivery_option') }}</option>
                            <option value="mony_transfer">{{ $t('admin.orders.money_transfer_option') }}</option>
                            <option value="online_payment" disabled>{{ $t('admin.orders.online_payment_coming_soon_option') }}</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="row" v-if="form.delivery_type === 'delivery'">
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

            <div v-if="form.order_product_list.length === 0" class="text-center text-muted p-3 border mb-2">
                {{ $t('admin.orders.no_products_added_msg') }}
            </div>
            <div v-for="(item, index) in form.order_product_list" :key="index" class="border rounded p-3 mb-2 bg-light">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <strong>{{ $t('admin.orders.product_number_prefix') }} {{ index + 1 }}</strong>
                    <button type="button" class="btn btn-danger btn-sm" @click="removeProduct(index)">
                        <i class="fa fa-trash"></i>
                    </button>
                </div>
                <div class="row">
                    <div class="col-md-4">
                        <label>{{ $t('admin.orders.product_label') }}</label>
                        <select class="form-control" v-model="item.product_id" @change="loadOptions(item)" required>
                            <option value="">{{ $t('admin.orders.select_product_placeholder') }}</option>
                            <option v-for="p in products" :key="p.id" :value="p.id">{{ p.title }}</option>
                        </select>
                    </div>
                    <div class="col-md-4" v-if="item.product_id">
                        <label>{{ $t('admin.orders.option_label') }}</label>
                        <select class="form-control" v-model="item.product_option_id" @change="onOptionChange(item)" required>
                            <option value="">{{ $t('admin.orders.select_option_placeholder') }}</option>
                            <option
                                v-for="opt in item.options"
                                :key="opt.id"
                                :value="opt.id"
                                :disabled="!create_production_task && opt.quantity <= 0"
                            >
                                {{ opt.name }} ({{ opt.price }} GEL) — {{ $t('admin.orders.stock_suffix') }} {{ opt.quantity }}
                            </option>
                        </select>
                    </div>
                    <div class="col-md-2" v-if="item.product_option_id">
                        <label>{{ $t('admin.orders.qty_label') }}</label>
                        <input type="number" class="form-control" v-model.number="item.quantity" min="1" :max="item.max_quantity" required>
                        <small class="text-muted">{{ $t('admin.orders.max_prefix') }} {{ item.max_quantity }}</small>
                    </div>
                    <div class="col-md-2 d-flex align-items-center" v-if="item.option_image">
                        <img :src="'/public/images/product_option_img/' + item.option_image" class="img-thumbnail" style="max-width:70px;max-height:70px;">
                    </div>
                </div>
            </div>
            <button type="button" class="btn btn-success btn-sm mb-3" @click="addProduct">
                <i class="fa fa-plus"></i> {{ $t('admin.orders.add_product_btn') }}
            </button>
            <button type="button" class="btn btn-outline-secondary btn-sm mb-3 ml-2" @click="exportInvoice" :disabled="exportingInvoice || form.order_product_list.length === 0">
                <span v-if="exportingInvoice" class="spinner-border spinner-border-sm"></span>
                <i v-else class="fa fa-file-pdf-o"></i> {{ $t('admin.orders.export_invoice_btn') }}
            </button>
            <select class="form-control form-control-sm d-inline-block ml-2 mb-3" style="width:auto;" v-model="invoiceLocale">
                <option value="ka">ქართული</option>
                <option value="en">English</option>
            </select>
            <div v-if="exportError" class="text-danger small mb-2">{{ exportError }}</div>
        </form>

        <!-- Success state -->
        <div v-if="submitted" class="text-center p-4">
            <i class="fa fa-check-circle text-success" style="font-size:2.5em;"></i>
            <h5 class="mt-2">{{ $t('admin.orders.order_created_msg', { id: createdOrderId }) }}</h5>
            <div v-if="matchedUsers.length > 0" class="alert alert-info mt-3 text-left">
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
        </div>

        <!-- <template #footer>
            <div class="d-flex gap-2 justify-content-end p-3">
                <button v-if="!submitted" type="button" class="btn btn-primary" @click="submitOrder" :disabled="submitting">
                    {{ submitting ? 'Saving...' : 'Create Order' }}
                </button>
                <button type="button" class="btn btn-secondary" @click="closeModal">
                    {{ submitted ? 'Close' : 'Cancel' }}
                </button>
            </div>
        </template> -->
    </StackModal>
</template>

<script>
export default {
    emits: ['orderAdded'],
    data() {
        return {
            showModal: false,
            products: [],
            form: {
                name: '',
                surname: '',
                email: '',
                phone: '',
                address: '',
                city: '',
                delivery_type: 'self_delivery',
                payment_type: 'deliverd_payment',
                order_product_list: [],
            },
            create_production_task: false,
            submitting: false,
            submitted: false,
            createdOrderId: null,
            matchedUsers: [],
            scanOpen: false,
            scanBarcode: '',
            scanLoading: false,
            scanError: null,
            scanSuccess: null,
            exportingInvoice: false,
            exportError: null,
            invoiceLocale: 'ka',
        };
    },
    mounted() {
        this.fetchProducts();
    },
    methods: {
        fetchProducts() {
            axios.get('/get_product/get_current_products')
                .then(r => { this.products = r.data; })
                .catch(() => {});
        },
        show_modal() {
            this.showModal = true;
            this.submitted = false;
            this.matchedUsers = [];
            this.createdOrderId = null;
        },
        closeModal() {
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
                            price: opt.price,
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
            axios.get(`/get_product/get_product_options/${item.product_id}`)
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
                order_product_list: [],
            };
            this.create_production_task = false;
            this.submitted = false;
            this.submitting = false;
            this.matchedUsers = [];
            this.createdOrderId = null;
            this.scanOpen = false;
            this.scanBarcode = '';
            this.scanError = null;
            this.scanSuccess = null;
            this.exportingInvoice = false;
            this.exportError = null;
            this.invoiceLocale = 'ka';
        },
        exportInvoice() {
            if (!this.form.name || !this.form.surname) {
                alert(this.$t('admin.orders.name_surname_required'));
                return;
            }
            if (this.form.order_product_list.length === 0) {
                alert(this.$t('admin.orders.please_add_product'));
                return;
            }
            const hasIncomplete = this.form.order_product_list.some(
                i => !i.product_id || !i.product_option_id || !i.quantity
            );
            if (hasIncomplete) {
                alert(this.$t('admin.orders.please_complete_product_selections'));
                return;
            }

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
            if (!this.form.name || !this.form.surname) {
                alert(this.$t('admin.orders.name_surname_required'));
                return;
            }
            if (this.form.order_product_list.length === 0) {
                alert(this.$t('admin.orders.please_add_product'));
                return;
            }
            const hasIncomplete = this.form.order_product_list.some(
                i => !i.product_id || !i.product_option_id || !i.quantity
            );
            if (hasIncomplete) {
                alert(this.$t('admin.orders.please_complete_product_selections'));
                return;
            }

            this.submitting = true;
            axios.post('/custom_order/store', {
                ...this.form,
                create_production_task: this.create_production_task,
            })
            .then(res => {
                this.createdOrderId = res.data.order_id;
                this.matchedUsers = res.data.matched_users || [];
                this.submitted = true;
                this.$emit('orderAdded');
            })
            .catch(error => {
                const msg = error.response?.data?.error || error.response?.data?.message || this.$t('admin.orders.error_creating_order');
                alert(msg);
            })
            .finally(() => {
                this.submitting = false;
            });
        },
    },
};
</script>
