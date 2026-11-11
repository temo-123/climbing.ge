<template>
    <!-- same width and heading as the other home-page sections (no .container / rows) -->
    <section v-if="!only_alerts || has_any_alert" class="user-alerts" :class="{ 'user-alerts--compact': only_alerts }">
        <div v-if="!only_alerts" class="user-alerts__head">
            <h2 class="cg-section-title cg-section-title--left cg-dashboard__title mb-0">
                <i class="fa fa-bell" aria-hidden="true"></i> {{ $t('user.notifications.title') }}
            </h2>
            <button type="button" class="btn btn-sm btn-outline-secondary" :disabled="is_admin_panel_refresh" @click="refresh()" :title="$t('user.notifications.refresh')" :aria-label="$t('user.notifications.refresh')">
                <i class="fa fa-refresh" :class="{ 'fa-spin': is_admin_panel_refresh }" aria-hidden="true"></i>
            </button>
        </div>

        <div v-if="user && user.id" class="user-alerts__list">
            <div class="user-alert-card user-alert-card--ok" role="status" v-if="!has_any_alert && !only_alerts">
                <span class="user-alert-card__icon"><i class="fa fa-check-circle" aria-hidden="true"></i></span>
                <span class="user-alert-card__message">{{ $t('user.notifications.all_good') }}</span>
            </div>

            <div class="user-alert-card user-alert-card--danger" v-if="!user['name'] || !user['surname'] || !user['country'] || !user['city'] || !user['email']">
                <span class="user-alert-card__icon"><i class="fa fa-id-card" aria-hidden="true"></i></span>
                <span class="user-alert-card__message">
                    <strong>{{ $t('user.notifications.danger') }}</strong> {{ $t('user.notifications.missing_data') }}
                </span>
            </div>

            <div class="user-alert-card user-alert-card--danger" v-if="!user['email_verified_at']">
                <span class="user-alert-card__icon"><i class="fa fa-envelope" aria-hidden="true"></i></span>
                <span class="user-alert-card__message">
                    <strong>{{ $t('user.notifications.danger') }}</strong> {{ $t('user.notifications.email_not_verified') }}
                    <span v-if="is_email_sending_loader" class="text-muted">
                        <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> {{ $t('user.notifications.please_wait') }}
                    </span>
                    <a v-else href="#" @click.prevent="send_mail_confirm_notificatione()">{{ $t('user.notifications.click_here') }}</a>.
                </span>
            </div>

            <div class="user-alert-card user-alert-card--warning" v-if="!user['image']">
                <span class="user-alert-card__icon"><i class="fa fa-user-circle" aria-hidden="true"></i></span>
                <span class="user-alert-card__message">
                    <strong>{{ $t('user.notifications.warning') }}</strong> {{ $t('user.notifications.missing_image') }}
                </span>
            </div>
        </div>
    </section>
</template>

<script>
    import { get_auth_user } from '../../../../services/authUser.js'
    // import { SlickList, SlickItem } from 'vue-slicksort'; //https://github.com/Jexordexan/vue-slicksort
    // import StackModal from '@innologica/vue-stackable-modal'  //https://innologica.github.io/vue-stackable-modal/#sample-css
    export default {
        props: {
            // home page top: only the alert cards, and nothing when there is none
            only_alerts: { type: Boolean, default: false },
        },
        components: {
            // StackModal,
            // SlickItem,
            // SlickList,
        },
        data(){
            return{
                user: {},
                
                complaint_loader: false,

                is_admin_panel_refresh: false,
                admin_refresh_id: 0,
                is_email_sending_loader: false,
            }
        },
        computed: {
            has_any_alert() {
                if (!this.user || !this.user.id) return false
                return !this.user['name'] || !this.user['surname'] || !this.user['country'] || !this.user['city'] || !this.user['email']
                    || !this.user['email_verified_at']
                    || !this.user['image']
            },
        },
        mounted(){
            this.refresh()
        },
        methods: {
            get_user_data(force = false){
                this.is_admin_panel_refresh = true
                get_auth_user({ force })
                .then((response)=>{
                    this.user = response.data
                })
                .catch(() => {})
                .finally(() => { this.is_admin_panel_refresh = false })
            },
            send_mail_confirm_notificatione(){
                this.is_email_sending_loader = true
                axios
                .get('/email/resend')
                .then((response)=>{
                    alert(this.$t('user.notifications.resend_success'))
                })
                .catch((error) => {
                    if(error.response.status === 429) {
                        // alert('The page has expired or you clicked this button too many times! Please try again later or contact support!')
                    }
                    else{
                        alert(this.$t('user.notifications.resend_error'))
                    }
                })
                .finally(() => this.is_email_sending_loader = false);
            },
            refresh(){
                this.admin_refresh_id++

                // first load shares the page's request; the Refresh button fetches fresh
                this.get_user_data(this.admin_refresh_id > 1)
            },
        }
    }
</script>

<style>
.user-alerts { margin-bottom: 1.5rem; }
.user-alerts--compact { margin: 0 0 1.5rem; }
.user-alerts__head {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: .75rem;
    margin: 2rem 0 1rem;
}
.user-alerts__head .cg-dashboard__title { margin: 0; }
.user-alerts__list { display: grid; gap: .5rem; }
.user-alerts .user-alert-card {
    display: flex;
    align-items: center;
    gap: .75rem;
    border-radius: var(--cg-r-lg);
    border: 1px solid var(--cg-line);
    border-left: 4px solid var(--cg-line);
    background: #fff;
    padding: .75rem 1rem;
    box-shadow: var(--cg-shadow-sm);
}
.user-alerts .user-alert-card--danger { border-left-color: #dc3545; background: #fdf2f3; }
.user-alerts .user-alert-card--warning { border-left-color: #ffc107; background: #fffaf0; }
.user-alerts .user-alert-card--ok { border-left-color: #2fa36b; }
.user-alerts .user-alert-card--ok .user-alert-card__icon { color: #2fa36b; opacity: 1; }
.user-alerts .user-alert-card__icon {
    flex: 0 0 auto;
    font-size: 1.15rem;
    opacity: .75;
}
.user-alerts .user-alert-card__message {
    flex: 1 1 auto;
    min-width: 0;
}
</style>