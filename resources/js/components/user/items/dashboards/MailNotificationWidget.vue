<template>
    <div class="card">
        <div class="card-body" :class="{ 'text-center': compact }">
            <div v-if="loading" class="py-3 text-center">
                <i class="fa fa-spinner fa-spin fa-2x text-muted"></i>
            </div>

            <div v-else-if="error" class="alert alert-warning mb-0">
                <i class="fa fa-exclamation-triangle"></i>
                {{ error }}
            </div>

            <div v-else>
                <div class="d-flex align-items-center" :class="compact ? 'flex-column' : 'flex-wrap'" style="gap: 12px;">
                    <div class="mail-icon-wrap">
                        <i class="fa fa-envelope-o fa-3x" :class="unseen > 0 ? 'text-primary' : 'text-muted'"></i>
                        <span v-if="unseen > 0" class="mail-badge">{{ unseen > 99 ? '99+' : unseen }}</span>
                    </div>

                    <p class="mb-0" style="font-size: 15px">
                        <strong v-if="unseen > 0">
                            {{ $t('admin.dashboards.you_have_prefix') }} <span class="text-primary">{{ unseen }}</span> {{ unseen !== 1 ? $t('admin.dashboards.unread_email_plural') : $t('admin.dashboards.unread_email_singular') }}
                        </strong>
                        <span v-else class="text-muted">{{ $t('admin.dashboards.no_new_emails') }}</span>
                    </p>

                    <a v-if="webmailUrl" :href="webmailUrl" target="_blank" rel="noopener" class="btn btn-primary btn-sm" :class="{ 'ms-auto': !compact }">
                        <i class="fa fa-external-link"></i> {{ $t('admin.dashboards.open_webmail_btn') }}
                    </a>
                </div>

                <div v-if="!compact">
                    <ul class="nav nav-tabs mt-3">
                        <li class="nav-item" v-for="f in folders" :key="f.path">
                            <a class="nav-link" href="#" :class="{ active: selected_folder === f.path }" @click.prevent="select_folder(f.path)">
                                {{ f.label }}
                            </a>
                        </li>
                    </ul>

                    <tabsComponent
                        :table_data="mail_tab_data"
                        :loading="messages_loading"
                        @update="fetchRecentMessages"
                        @show_mail_detail="show_mail_detail"
                    />
                </div>
            </div>
        </div>

        <MailMessageDetailModal ref="mail_detail_modal" />
    </div>
</template>

<script>
import tabsComponent from '../data_table/TabsComponent.vue'
import MailMessageDetailModal from '../modal/MailMessageDetailModal.vue'

export default {
    name: 'MailNotificationWidget',
    components: { tabsComponent, MailMessageDetailModal },
    props: {
        compact: { type: Boolean, default: false },
    },
    data() {
        return {
            loading: true,
            unseen: 0,
            webmailUrl: '',
            error: '',
            folders: [],
            selected_folder: 'INBOX',
            messages: [],
            messages_loading: false,
        }
    },
    computed: {
        mail_tab_data() {
            return [{
                id: 1,
                table_name: this.$t('admin.dashboards.col_subject'),
                tab_data: {
                    data: this.messages,
                    tab: {
                        head: [
                            this.$t('admin.dashboards.col_subject'),
                            this.$t('admin.dashboards.col_from'),
                            this.$t('admin.dashboards.col_date'),
                            this.$t('admin.dashboards.col_status'),
                            '',
                        ],
                        body: [
                            ['data', ['subject']],
                            ['data', ['from']],
                            ['data', ['date']],
                            ['data', ['seen'], 'bool'],
                            ['action_fun_id', 'show_mail_detail', 'btn btn-info btn-sm', '<i class="fa fa-eye"></i> ' + this.$t('admin.dashboards.view_message_btn')],
                        ],
                        perm: [['no'], ['no'], ['no'], ['no'], ['no']],
                    }
                }
            }]
        },
    },
    mounted() {
        this.fetchUnread()
        if (!this.compact) {
            this.fetchFolders()
            this.fetchRecentMessages()
        }
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
                        : this.$t('admin.dashboards.could_not_reach_mail_server')
                    this.error = msg
                })
                .finally(() => { this.loading = false })
        },
        fetchFolders() {
            axios.get('mail/folders')
                .then(r => { this.folders = r.data || [] })
                .catch(() => {})
        },
        select_folder(path) {
            if (this.selected_folder === path) return
            this.selected_folder = path
            this.fetchRecentMessages()
        },
        fetchRecentMessages() {
            this.messages_loading = true
            axios.get('mail/recent_messages', { params: { folder: this.selected_folder } })
                .then(r => { this.messages = r.data || [] })
                .catch(() => {})
                .finally(() => { this.messages_loading = false })
        },
        show_mail_detail(uid) {
            this.$refs.mail_detail_modal.show_modal(this.selected_folder, uid)
        },
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
