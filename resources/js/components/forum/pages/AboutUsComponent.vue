<template>
    <div class="container top_menu_margin">
        <div class="aboutus-section">
            <div class="container">
                <div class="row">
                    <div class="col-md-12 col-sm-12 col-xs-12">
                        <div class="aboutus">
                            <h1 class="text-center">Georgian climbing forum</h1>

                            <span v-html="this.$siteData.forum_description_us"></span>
                            
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <servicesListComponent />
        
        <messageComponent />

    </div>
</template>

<script>
    import servicesListComponent from '../../global_components/ServicesListComponent.vue'
    import messageComponent from '../../global_components/MessageComponent.vue'
    export default {
        data: function () {
            return {
                cart_products: [],
                site_data: [],
            };
        },
        components: {
            messageComponent,
            servicesListComponent,
        },
        mounted() {
        },
        methods: {
        }
    }
</script>
