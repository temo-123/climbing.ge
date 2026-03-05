<template>
    <div class="food col-md-4">
        <div class="portfolio-img view view-first">

            <!-- <img :src="image_dir+article.image" class="img-responsive" :alt='article[0][0].title' /> -->

            <site-img v-if="article.global_data.image" :src="image_dir+article.global_data.image" :img_class="'img-responsive'" :alt='article.locale_data.title'/>
            <site-img v-else :src="'/public/images/site_img/image.png'" :img_class="'img-responsive'" :alt='article.locale_data.title'/>

            <div class="mask">

                <router-link :to="path + '/' + article.global_data.url_title" class="info">
                    <i class="fa fa-arrow-right"></i>
                </router-link>
                <!-- <a :href="category + '/' + article.global_data.url_title" class="info"><i class="fa fa-arrow-right"></i></a> -->
            </div>
            
            <div class="isotope-item-dtls">
                <h3>{{ article.locale_data.title }}</h3>
                <div class="article_size">
                    <span v-html="article.locale_data.short_description"></span>
                </div>
            </div>
        </div>
    </div>  
</template>

<script>
    export default {
        props: [
            'article',
            // 'image_dir'
        ],
        data: function () {
            return {
                image_dir: '',
                path: '',
            };
        },
        components: {
        },
        mounted() {
            if (this.article.global_data.category === 'indoor') {
                this.image_dir = '/public/images/indoor_img/'
                this.path = 'indoor'
            }
            else if (this.article.global_data.category === 'outdoor') {
                this.image_dir = '/public/images/outdoor_img/'
                this.path = 'outdoor'
            }
            else if (this.article.global_data.category === 'mount_route') {
                this.image_dir = '/public/images/mount_route_img/'
                this.path = 'mountaineering'
            }
            // else if (this.article.global_data.category === 'other') {
            //     this.image_dir = 'public/images/other_img/'
            //     this.path = 'other'
            // }
            else if (this.article.global_data.category === 'ice') {
                this.image_dir = '/public/images/ice_img/'
                this.path = 'ice'
            }
        },
        methods: {
        }
    }
</script>