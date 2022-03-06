<template>
    <div class="container top_menu_margin">
        <div class="aboutus-section">
            <div class="container">
                <div class="row">
                    <div class="col-md-12 col-sm-12 col-xs-12">
                        <div class="aboutus">

                            <span v-html="this.$siteData.shop_description"></span>
                            
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <messageComponent />

    </div>
</template>

<script>
    import messageComponent from '../../site/items/MessageComponent.vue'
    export default {
        data: function () {
            return {
                cart_products: [],
                site_data: [],
            };
        },
        components: {
            messageComponent,
        },
        mounted() {
            // this.get_site_data()
        },
        methods: {
            // get_site_data() {
            //     axios
            //     .get('../get_site_data')
            //     .then(response => {
            //         this.site_data = response.data[0]
            //     })
            //     .catch(error =>{
            //     })
            // }
        }
    }
</script>
