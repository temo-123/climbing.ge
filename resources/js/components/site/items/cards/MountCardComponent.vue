<template>
  <div class="food col-md-4">
        <div class="portfolio-img view view-first">

            <!-- <img :src="image_dir+mount.image" class="img-responsive" :alt='mount[0][0].title' /> -->
                                
            <site-img v-if="mount.image != NULL" :src="image_dir+mount.image" :img_class="'img-responsive'" :alt='mount[0][0].title'/>
            <site-img v-else :src="'../../../public/images/site_img/image.png'" :img_class="'img-responsive'" :alt='mount[0][0].title'/>

            <div class="mask">
                <!-- <a href="#" class="info"><i class="fa fa-arrow-right"></i></a> -->
                <router-link :to="this.route" class="info"><i class="fa fa-arrow-right"></i></router-link>
            </div>
            
            <div class="isotope-item-dtls">
                <h3>{{ mount[0][0].title }}</h3>
                <div class="mount_size">

                    <!-- <p>{{ mount[0][0].short_description }}</p> -->
                    <span v-html="mount.short_description"></span>

                    <div class="col text-center">
                        <div class="row">
                            Mount system - 
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
            'image_dir',
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