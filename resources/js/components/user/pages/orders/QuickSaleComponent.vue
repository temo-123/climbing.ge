<template>
    <div class="row">
        <left-menu />
        <div class="col-sm-12">
            <div class="row">
                <div class="col-md-12">
                    <breadcrumb />
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12">
                    <p class="text-muted">{{ $t('admin.orders.quick_sale_hint') }}</p>
                    <button class="btn btn-primary" @click="new_sale">
                        <i class="fa fa-plus"></i> {{ $t('admin.orders.add_custom_order_btn') }}
                    </button>
                </div>
            </div>
        </div>

        <addCustomOrderModal ref="addCustomOrderModal" />
    </div>
</template>

<script>
    import breadcrumb from '../../items/BreadcrumbComponent.vue'
    import addCustomOrderModal from '../../items/modal/orders/AddCustomOrderModal.vue'

    export default {
        components: {
            breadcrumb,
            addCustomOrderModal,
        },
        methods: {
            new_sale(){
                this.$refs.addCustomOrderModal.show_modal()
            },
        }
    }
</script>
