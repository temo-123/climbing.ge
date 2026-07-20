<template>
    <div class="container user-alerts">
        <div class="row">
            <div class="col-md-12">
                <div class="form-groupe d-flex justify-content-between align-items-center mb-2">
                    <h5 class="mb-0"><i class="fa fa-bell" aria-hidden="true"></i> {{ $t('user.notifications.title') }}</h5>
                    <button @click="refresh()" class="btn btn-success btn-sm" v-if="!is_admin_panel_refresh">{{ $t('user.notifications.refresh') }} ({{admin_refresh_id}})</button>
                    <span class="badge badge-primare mb-1" v-if="is_admin_panel_refresh">{{ $t('common.updating') }}</span>
                </div>
            </div>
        </div>

        <div class="row" v-if="this.user != []">
            <div class="col-md-12">
                <div class="alert alert-success user-alert-empty" role="alert" v-if="!has_any_alert">
                    <i class="fa fa-check-circle" aria-hidden="true"></i> {{ $t('user.notifications.all_good') }}
                </div>

                <div class="user-alert-card user-alert-card--danger" v-if="!this.user['name'] || !this.user['surname'] || !this.user['country'] || !this.user['city'] || !this.user['email']">
                    <span class="user-alert-card__icon"><i class="fa fa-id-card" aria-hidden="true"></i></span>
                    <span class="user-alert-card__message">
                        <strong>{{ $t('user.notifications.danger') }}</strong> {{ $t('user.notifications.missing_data') }}
                    </span>
                </div>

                <div class="user-alert-card user-alert-card--danger" v-if="!this.user['email_verified_at']">
                    <span class="user-alert-card__icon"><i class="fa fa-envelope" aria-hidden="true"></i></span>
                    <span class="user-alert-card__message" v-if="!is_email_sending_loader">
                        <strong>{{ $t('user.notifications.danger') }}</strong> {{ $t('user.notifications.email_not_verified') }}
                        <a href="#" class="cursor_pointer" @click.prevent="send_mail_confirm_notificatione()">{{ $t('user.notifications.click_here') }}</a>.
                    </span>
                    <span class="user-alert-card__message" v-else>
                        <div class="row justify-content-center" >
                            <div class="col-md-3">
                                <img :src="'/images/site_img/loading.gif'" alt="loading">
                                <p class="text-center">{{ $t('user.notifications.please_wait') }}</p>
                            </div>
                        </div>
                    </span>
                </div>

                <div class="user-alert-card user-alert-card--warning" v-if="!this.user['image']">
                    <span class="user-alert-card__icon"><i class="fa fa-user-circle" aria-hidden="true"></i></span>
                    <span class="user-alert-card__message">
                        <strong>{{ $t('user.notifications.warning') }}</strong> {{ $t('user.notifications.missing_image') }}
                    </span>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    // import { SlickList, SlickItem } from 'vue-slicksort'; //https://github.com/Jexordexan/vue-slicksort
    // import StackModal from '@innologica/vue-stackable-modal'  //https://innologica.github.io/vue-stackable-modal/#sample-css
    export default {
        components: {
            // StackModal,
            // SlickItem,
            // SlickList,
        },
        data(){
            return{
                user: [],
                
                complaint_loader: false,

                is_admin_panel_refresh: false,
                admin_refresh_id: 0,
                is_email_sending_loader: false,
            }
        },
        computed: {
            has_any_alert() {
                return !this.user['name'] || !this.user['surname'] || !this.user['country'] || !this.user['city'] || !this.user['email']
                    || !this.user['email_verified_at']
                    || !this.user['image']
            },
        },
        mounted(){
            this.refresh()
        },
        methods: {
            get_user_data(){
                axios
                .get('/auth_user')
                .then((response)=>{
                    this.user = response.data
                    // this.get_user_queries(this.user.id)
                })
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

                this.get_user_data()
            },
        }
    }
</script>

<style>
.user-alerts .user-alert-card {
    display: flex;
    align-items: center;
    gap: 8px;
    border-radius: 6px;
    border-left: 4px solid #ccc;
    background: #f8f9fa;
    padding: 10px 14px;
    margin-bottom: 8px;
}
.user-alerts .user-alert-card--danger {
    border-left-color: #dc3545;
    background: #fdf2f3;
}
.user-alerts .user-alert-card--warning {
    border-left-color: #ffc107;
    background: #fffaf0;
}
.user-alerts .user-alert-card__icon {
    font-size: 1.1rem;
    opacity: .75;
}
.user-alerts .user-alert-card__message {
    flex: 1 1 auto;
}
.user-alerts .user-alert-empty {
    text-align: center;
}
</style>