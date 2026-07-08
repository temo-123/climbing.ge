<template>
    <span>
        <div class="row">
            <div class="col-md-12">
                <div class="form-groupe">
                    <button @click="refresh()" class="btn btn-success float-right" v-if="!is_admin_panel_refresh">{{ $t('user.notifications.refresh') }} ({{admin_refresh_id}})</button>
                    <span class="badge badge-primare mb-1 float-right" v-if="is_admin_panel_refresh">{{ $t('common.updating') }}</span>
                </div>
            </div>
        </div>

        <div class="row" v-if="this.user != []">
            <div class="col-md-12">
                <span v-if="!this.user['name'] || !this.user['surname'] || !this.user['country'] || !this.user['city'] || !this.user['email']">
                    <div class="alert alert-danger" role="alert">
                        <strong>{{ $t('user.notifications.danger') }}</strong> {{ $t('user.notifications.missing_data') }}
                    </div>
                </span>
                <span v-if="!this.user['email_verified_at']">
                    <div class="alert alert-danger" role="alert">
                        <span v-if="!is_email_sending_loader" class="cursor_pointer" @click="send_mail_confirm_notificatione()">
                            <strong>{{ $t('user.notifications.danger') }}</strong> {{ $t('user.notifications.email_not_verified') }} <span class="cursor_pointer" @click="send_mail_confirm_notificatione()">{{ $t('user.notifications.click_here') }}</span>.
                        </span>
                        <span v-else-if="is_email_sending_loader">
                            <div class="row justify-content-center" >
                                <div class="col-md-3">
                                    <img :src="'/images/site_img/loading.gif'" alt="loading">
                                    <p class="text-center">{{ $t('user.notifications.please_wait') }}</p>
                                </div>
                            </div>
                        </span>
                    </div>
                </span>

                <span v-if="!this.user['image']">
                    <div class="alert alert-warning" role="alert">
                        <strong>{{ $t('user.notifications.warning') }}</strong> {{ $t('user.notifications.missing_image') }}
                    </div>
                </span>
            </div>
        </div>
    </span>
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

</style>