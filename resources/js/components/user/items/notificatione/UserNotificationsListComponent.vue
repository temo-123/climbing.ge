<template>
    <span>
        <div class="row">
            <div class="col-md-12">
                <div class="form-groupe">
                    <button @click="refresh()" class="btn btn-success float-right" v-if="!is_admin_panel_refresh">Refresh notifications ({{admin_refresh_id}})</button>
                    <span class="badge badge-primare mb-1 float-right" v-if="is_admin_panel_refresh">Updating...</span>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <span v-if="!this.user['name'] || !this.user['surname'] || !this.user['country'] || !this.user['city'] || !this.user['email']">
                    <div class="alert alert-danger" role="alert">
                        <strong>Danger!</strong> Your personal data is missing, this can lead to rolling problems. check page "option", and add missing information.
                    </div>
                </span>
                <span v-if="!this.user['email_verified_at']">
                    <div class="alert alert-danger" role="alert">
                        <span v-if="!is_email_sending_loader" class="cursor_pointer" @click="send_mail_confirm_notificatione()">
                            <strong>Danger!</strong> We sent you an email for verification, please check your email and confirm it. If you don't got this email you can demand new message. For new message -> <span class="cursor_pointer" @click="send_mail_confirm_notificatione()">Click here</span>.
                        </span>
                        <span v-else-if="is_email_sending_loader">
                            <div class="row justify-content-center" >
                                <div class="col-md-3">
                                    <img :src="'../../../../../../public/images/site_img/loading.gif'" alt="loading">
                                    <p class="text-center">Pless wait!</p>
                                </div>
                            </div>
                        </span>
                    </div>
                </span>

                <span v-if="!this.user['image']">
                    <div class="alert alert-warning" role="alert">
                        <strong>Warning!</strong> Add your profil image and piple faind you isier
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
                    alert('New verification message is sended. Please check your email for verification!')
                })
                .catch((error) => {
                    if(error.response.status === 429) {
                        // alert('The page has expired or you clicked this button too many times! Please try again later or contact support!')
                    }
                    else{
                        alert('Something went wrong! Please try again later, if you encounter this problem again, contact support!')
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