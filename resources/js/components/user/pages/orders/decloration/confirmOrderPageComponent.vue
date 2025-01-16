<template>
    <div class="col-md-12">

        <div class="row">
            <productDradcrumb :props_page="'confirm_order'"/>
        </div>

        <div class="confirm_page_text">
            <h1>Order is completed! </h1>
            <h2>Plis check your email ({{ user.email }}) for confirm order!</h2>
            <h3>Befor email confirming order is not socsesful!</h3>
        </div>

        <div class="confirm_page_back_button">
            <router-link :to="{name: 'myOrders'}" exact> 
                <button class="btn btn-primary " >My orders</button>
            </router-link>
        </div>

    </div>
</template>

<script>
    import productDradcrumb from './items/BreadcrumbComponent.vue'
    export default {
        components: {
            productDradcrumb
        },
        mounted() {
            if (this.$route.params.order_decloration) {
                // this.user_mail = this.$route.params.user_mail
                this.get_auth_user_data()
            } 
            else {
                this.$router.push({name: "myOrders"})
            }
            
            document.querySelector('body').style.marginLeft = '0';
            document.querySelector('.admin_page_header_navbar').style.marginLeft = '0';
        },

        data(){
            return{
                user: ''
            }
        },

        methods: {
            get_auth_user_data() {
                axios
                .get('/auth_user')
                .then(response => { this.user = response.data })
            },
        },

    }
</script>

<style>
    .confirm_page_text{
        text-align: center;
    }

    .confirm_page_back_button {
        text-align: center;
    }


    .purchase
{
    position: relative;
    background-color: #FFF;
    min-height: 680px;
    padding: 15px;
    font-family: Times New Roman;
}
.purchase header
{
    padding: 0px 0px 0px 0px;
    margin-bottom: 0px;
    border-bottom: 1px solid #3989c6;
}
.purchase header img
{
    max-width: 200px;
    margin-top: 0;
    margin-bottom: 0;
}
.purchase .company-details
{
    text-align: right;
    margin-top: 0;
    margin-bottom: 0;
}
.purchase main
{
    padding: 0px 0px;
    margin-bottom: 0px;
}
.purchase .to-details
{
    text-align: left;
}
.purchase .to-name
{
    font-weight: bold;
}
.purchase .to-name .to-address .to-city
{
    margin-top: 0;
    margin-bottom: 0;
}
.purchase .purchase-info
{
    text-align: right;
}
.purchase-info .info-code
{
    font-weight: bold;
}
.purchase-info .info-code .info-date
{
    margin-top: 0;
    margin-bottom: 0;
}






.purchase
{
    position: relative;
    background-color: #FFF;
    min-height: 680px;
    padding: 15px;
    font-family: Times New Roman;
}
.purchase header
{
    padding: 0px 0px 0px 0px;
    margin-bottom: 0px;
    border-bottom: 1px solid #3989c6;
}
.purchase header img
{
    max-width: 200px;
    margin-top: 0;
    margin-bottom: 0;
}
.purchase .company-details
{
    text-align: right;
    margin-top: 0;
    margin-bottom: 0;
}
.purchase main
{
    padding: 0px 0px;
    margin-bottom: 0px;
}
.purchase .to-details
{
    text-align: left;
}
.purchase .to-name
{
    font-weight: bold;
}
.purchase .to-name .to-address .to-city
{
    margin-top: 0;
    margin-bottom: 0;
}
.purchase .purchase-info
{
    text-align: right;
}
.purchase-info .info-code
{
    font-weight: bold;
}
.purchase-info .info-code .info-date
{
    margin-top: 0;
    margin-bottom: 0;
}


</style>