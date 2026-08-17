<template>
    <div class="row">
        <left-menu />
        <div class="col-sm-12">
            <div class="row">
                <div class="col-md-12">
                    <breadcrumb :title="$t('user.menu.my trainings')" />
                </div>
            </div>
            <div class="row">
                <!-- <trainingSummary /> -->
                <h1 class="text-center mt-5">Coming soon</h1>
            </div>
        </div>
    </div>
</template>

<script>
    import breadcrumb from '../items/BreadcrumbComponent.vue'
    import trainingSummary from '../items/user_options/UserTrainingSummaryComponent.vue'

    export default {
        components: {
            breadcrumb,
            trainingSummary,
        },
    }
</script>
