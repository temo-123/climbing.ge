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

        <servicesListComponent />
        
        <messageComponent />

        <metaData 
            :title = "'Climbing films'"
            :description = "'Rock climbing, mountaineering and other outdoor actyvity films'"
            :image = "'../../../../public/images/meta_img/films.jpg'"
        />
    </div>
</template>

<script>
    import servicesListComponent from '../../global_components/ServicesListComponent.vue'
    import messageComponent from '../../global_components/MessageComponent.vue'
    import metaData from '../items/MetaDataComponent'
    export default {
        components: {
            metaData,
            messageComponent,
            servicesListComponent,
        },
        data: function () {
            return {
                cart_products: [],
                site_data: [],
            };
        },
        mounted() {
        },
        methods: {
        }
    }
</script>
