<template>
    <div class="col-md-12">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb user_breadcrumb">
                <li class="breadcrumb-item">
                    Cart
                </li>
                <li class="breadcrumb-item" v-if="props_page == 'confirm_order' || props_page == 'product_list' || props_page == 'payment'">
                    Paymant
                </li>
                <li class="breadcrumb-item" v-if="props_page == 'confirm_order' || props_page == 'product_list'">
                    Product list
                </li>
                <li class="breadcrumb-item" v-if="props_page == 'confirm_order'">
                    Confirm order
                </li>
            </ol>
        </nav>
    </div>
</template>

<script>
    export default {
        props: [
            'props_page'
        ],
        components: {
            //
        },
        methods: {
            //
        },
        data(){
            return{
                general_pathname: window.location.pathname,

                path: '',
                page: '',

                path_url: '',
            }
        },
        mounted() {
            this.page = this.general_pathname.split("/").pop();

            var path = this.general_pathname.split("/")[1]
            this.path = path

            // if(this.path == 'news' || this.path == 'event' || this.path == 'tech_tip' || this.path == 'partner'){
            //     this.path_url = 'index'
            // }
            // else{
            //     this.path_url = path+'s'
            // }
        },
    }
</script>

<style>
.user_breadcrumb {
    background-color: #e9ecef !important;
}
</style>