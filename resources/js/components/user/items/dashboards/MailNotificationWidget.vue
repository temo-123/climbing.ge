<template>
    <div class="card">
        <div class="card-body text-center">
            <div v-if="loading" class="py-3">
                <i class="fa fa-spinner fa-spin fa-2x text-muted"></i>
            </div>

            <div v-else-if="error" class="alert alert-warning mb-0">
                <i class="fa fa-exclamation-triangle"></i>
                {{ error }}
            </div>

            <div v-else>
                <div class="mail-icon-wrap mb-2">
                    <i class="fa fa-envelope-o fa-3x" :class="unseen > 0 ? 'text-primary' : 'text-muted'"></i>
                    <span v-if="unseen > 0" class="mail-badge">{{ unseen > 99 ? '99+' : unseen }}</span>
                </div>

                <p class="mb-3" style="font-size: 15px">
                    <strong v-if="unseen > 0">
                        You have <span class="text-primary">{{ unseen }}</span> unread email{{ unseen !== 1 ? 's' : '' }}
                    </strong>
                    <span v-else class="text-muted">No new emails</span>
                </p>

                <a v-if="webmailUrl" :href="webmailUrl" target="_blank" rel="noopener" class="btn btn-primary btn-sm">
                    <i class="fa fa-external-link"></i> Open Webmail
                </a>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: 'MailNotificationWidget',
    data() {
        return {
            loading: true,
            unseen: 0,
            webmailUrl: '',
            error: '',
        }
    },
    mounted() {
        this.fetchUnread()
    },
    methods: {
        fetchUnread() {
            this.loading = true
            this.error = ''
            axios.get('mail/unread_count')
                .then(r => {
                    this.unseen = r.data.unseen || 0
                    this.webmailUrl = r.data.webmail_url || ''
                })
                .catch(e => {
                    const msg = e.response && e.response.data && e.response.data.error
                        ? e.response.data.error
                        : 'Could not reach mail server'
                    this.error = msg
                })
                .finally(() => { this.loading = false })
        }
    }
}
</script>

<style scoped>
.mail-icon-wrap {
    position: relative;
    display: inline-block;
}
.mail-badge {
    position: absolute;
    top: -6px;
    right: -10px;
    background: #e74c3c;
    color: #fff;
    border-radius: 50%;
    font-size: 0.6em;
    min-width: 18px;
    height: 18px;
    line-height: 18px;
    text-align: center;
    padding: 0 3px;
    font-weight: bold;
}
</style>
