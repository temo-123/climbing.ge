<template>
    <div class="container top_menu_margin">
        <!--About text-->
        <div class="aboutus-section">
            <div class="container">
                <!-- {{-- <div class="row">
                    <div class="col-md-12 col-sm-12 col-xs-12">
                        <div class="about_us_page_image">
                            @foreach($gallery_images as $image)
                                <img src="{{ asset('images/gallery_img/'.$image -> image) }}" alt="" style="rounded mx-auto d-block">
                            @endforeach
                        </div>
                    </div>
                </div> --}} -->
    
                <div class="row">
                    <div class="col-md-12 col-sm-12 col-xs-12">
                        <div class="aboutus">
                            <span v-html="this.$siteData.text"></span>
                        </div>
                    </div>

                    <!-- <div class="row">
                        <div class="col-md-12 col-sm-12 col-xs-12">
                            <hr>
                            <div class="about_us_page_shop_section">
                                <a href="route('shop_index') }}">
                                    <img :src="'../images/site_img/site_logo/climibng,ge shop (becground).jpg'" alt="climbing.ge shop" style="rounded mx-auto d-block">
                                </a>
                                
                                <a href="route('shop_index') }}">
                                    <h2><strong>climbing.ge shop</strong></h2>
                                </a>
                                <span v-html="this.$siteData.shop_description"></span>
                            </div>
                        </div>
                    </div> -->
                </div>
            </div>
        </div>
        
        <servicesListComponent />
        
        <messageComponent />


        <vue-glide>
            <vue-glide-slide>test 1</vue-glide-slide>
            <vue-glide-slide>test 2</vue-glide-slide>
            <vue-glide-slide>test 3</vue-glide-slide>
        </vue-glide>

    </div>
</template>

<script>
    import servicesListComponent from '../../global_components/ServicesListComponent.vue'
    import messageComponent from '../../global_components/MessageComponent'
    export default {
        data: function () {
            return {
            };
        },
        components: {
            messageComponent,
            servicesListComponent
        },
        mounted() {
        },
        methods: {
            // 
        }
    }
</script>

<style>

</style>