<template>
    <div class="row">
        
        <left-menu />
            
        <div class="col-sm-12">
            <div class="row">
                <div class="col-md-12">
                    <breadcrumb />
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="tabs">
                        <div class="row">
                            <div class="col">
                                <input type="radio" id="1" :value="1" v-model="tab_num" />

                                <label for="1">Guide header images</label>
                            </div>
                            <div class="col">
                                <input type="radio" id="2" :value="2" v-model="tab_num" />

                                <label for="2">Shop header images</label>
                            </div>
                        </div>

                        <div class="row" v-show="tab_num == 1">
                            <div class="col-md-12">
                                Guide header images

                                <images_tab 
                                    :image_path_prop="'/images/head_slider_img/guide/'"
                                    :category_prop="'guide'"
                                    ref="guide_slides" 
                                    @get_all_images="get_header_images()" 
                                />
                            </div>
                        </div>

                        <div class="row" v-show="tab_num == 2">
                            <div class="col-md-12">
                                Shop header images

                                <images_tab 
                                    :image_path_prop="'/images/head_slider_img/shop/'"
                                    :category_prop="'shop'"
                                    ref="shop_slides" 
                                    @get_all_images="get_header_images()" 
                                />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import breadcrumb from '../../items/BreadcrumbComponent.vue'
    import images_tab from './items/ImagesTab.vue';
    export default {
        data(){
            return {
                // headSliderImages: [],
                tab_num: 1
            }
        },
        components: {
            images_tab,
            breadcrumb
        },
        mounted() {
            this.get_header_images()
        },
        watch: {
            '$route' (to, from) {
                window.scrollTo(0,0)
            }
        },
        methods: {
            get_header_images(){
                axios
                .get("/get_head_slider/get_all_slides/")
                .then(response => {
                    this.$refs.guide_slides.update_images(response.data.guide_slides)
                    this.$refs.shop_slides.update_images(response.data.shop_slides)
                })
                .catch(
                    error => console.log(error)
                );
            },
        }
    }
</script>
