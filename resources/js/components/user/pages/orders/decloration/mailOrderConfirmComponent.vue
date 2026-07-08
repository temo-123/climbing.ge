<template>
    <div class="container">
        <div class="row">
            <div class="col-sm-12">

            <div class="row justify-content-center" v-if="create_order_loading">
                <div class="col-md-4">
                    <img :src="'/images/site_img/loading.gif'" alt="loading">
                </div>
            </div>

            <div class="col-md-12 confirm_page_text" v-else-if="error_status != ''">
                <h1>{{ error_status }}</h1>
                <router-link :to="{name: 'userOrders'}" exact  v-if="is_already_confirmed">
                    <button class="btn btn-primary " >{{ $t('user.checkout.my_orders') }}</button>
                </router-link>
            </div>

            <div class="col-md-12" v-else-if="!create_order_loading && error_status == ''">
                <div class="confirm_page_text">
                    <h1>{{ $t('user.checkout.order_completed') }}</h1>
                </div>

                <div class="confirm_page_text">
                    <router-link :to="{name: 'userOrders'}" exact>
                        <button class="btn btn-primary " >{{ $t('user.checkout.my_orders') }}</button>
                    </router-link>
                </div>
            </div>
        </div>
    </div>
    </div>
</template>

<script>
export default {
    components: {
    },
    mounted() {
        // this.order_id = window.location.pathname.split("/")[3]
        this.check_user_authing()

        document.querySelector('body').style.marginLeft = '0';
        document.querySelector('.admin_page_header_navbar').style.marginLeft = '0';
    },

    data(){
        return{
            create_order_loading: true,
            error_status: '',
            is_already_confirmed: false,
            order_id: this.$route.params.order_id,
            token: this.$route.params.token,
        }
    },

    methods: {
        confirm_order() {
            axios
            .post('/set_order/order_is_confirm/' + this.order_id, { token: this.token })
            .then(response => {
                //
            })
            .catch(error => {
                this.error_status = error.response?.data?.error || this.$t('user.checkout.undefined_error')
            })
            .finally(() => this.create_order_loading = false);
        },
        check_order_confirm() {
            axios
            .get('/set_order/is_order_confirm/' + this.order_id)
            .then(response => {
                if(response.data){
                    this.confirm_order()
                }
                else {
                    this.create_order_loading = false
                    this.is_already_confirmed = true
                    this.error_status = this.$t('user.checkout.order_already_confirmed')
                }
            })
            .catch(error => {
                this.create_order_loading = false
                this.error_status = this.$t('user.checkout.undefined_order_error')
            })
        },
        check_user_authing() {
            axios
            .get('/auth_user')
            .then(response => {
                this.check_order_confirm()
            })
            .catch(error => {
                this.create_order_loading = false
                this.error_status = this.$t('user.checkout.please_login_confirm')
            })
        },
    }
}
</script>

<style>
    .confirm_page_text{
        text-align: center;
    }
</style>