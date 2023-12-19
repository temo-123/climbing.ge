<template>
  <div class="food col-md-4">
        <div class="portfolio-img view view-first">
            <div class="product-image" v-if="mount.global_data.new_flag">
                <div class="discount-percent-badge discount-badge-fourty">NEW</div>
            </div>
                                
            <site-img v-if="mount.global_data.image != NULL" :src="'/images/mount_route_img/'+mount.global_data.mage" :img_class="'img-responsive'" :alt='mount.locale_data.title'/>
            <site-img v-else :src="'/public/images/site_img/image.png'" :img_class="'img-responsive'" :alt='mount.locale_data.title'/>

            <div class="mask">
                <router-link :to="this.route" class="info"><i class="fa fa-arrow-right"></i></router-link>
            </div>
            
            <div class="isotope-item-dtls">
                <h3>{{ mount.locale_data.title }}</h3>
                <div class="mount_size">

                    <span v-html="mount.locale_data.short_description"></span>

                    <div class="col text-center" v-if="mount.mount_masive">
                        <div class="row">
                            Mount system - {{ mount.mount_masive }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
    </div> 
</template>

<script>
    export default {
        props: [
            'mount',
            'route',
        ],
        data: function () {
            return {
            };
        },
        components: {
        },
        mounted() {
        },
        methods: {
        }
    }
</script>