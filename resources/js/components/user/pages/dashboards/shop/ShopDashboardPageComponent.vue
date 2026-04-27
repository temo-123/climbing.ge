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
                <div class="col-md-12">
                    <order_diagram />
                </div>
            </div>
            <div class="row mt-4">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-body">
                            <orders_list />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import breadcrumb from '../../../items/BreadcrumbComponent.vue'
    import order_diagram from '../../../items/dashboards/OrderDiogramComponent.vue'
    import orders_list from './assets/OrdersListComponent.vue'

    export default {
        components: { breadcrumb, order_diagram, orders_list },
    }
</script>
