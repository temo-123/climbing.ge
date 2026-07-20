<template>
    <div class="row">
        <left-menu />
        <div class="col-sm-12">
            <div class="row">
                <div class="col-md-12">
                    <breadcrumb />
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-12 mb-3">
                    <mail_notification_widget />
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import breadcrumb from '../../items/BreadcrumbComponent.vue'
import mail_notification_widget from '../../items/dashboards/MailNotificationWidget.vue'

export default {
    components: { breadcrumb, mail_notification_widget },
}
</script>
