<template>
    <div class="food col-md-4">
        <div class="portfolio-img view view-first">

            <!-- <img :src="image_dir+article.image" class="img-responsive" :alt='article[0][0].title' /> -->

            <site-img v-if="article.global_article.image" :src="image_dir+article.global_article.image" :img_class="'img-responsive'" :alt='article.us_article.title'/>
            <site-img v-else :src="'/public/images/site_img/image.png'" :img_class="'img-responsive'" :alt='article.us_article.title'/>

            <div class="mask">

                <router-link :to="category + '/' + article.global_article.url_title" class="info">
                    <i class="fa fa-arrow-right"></i>
                </router-link>
                <!-- <a :href="category + '/' + article.global_article.url_title" class="info"><i class="fa fa-arrow-right"></i></a> -->
            </div>
            
            <div class="isotope-item-dtls">
                <h3>{{ article.us_article.title }}</h3>
                <div class="article_size">
                    <span v-html="article.us_article.short_description"></span>
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
                category: '',
            };
        },
        components: {
        },
        mounted() {
            if (this.article.global_article.category === 'indoor') {
                this.image_dir = '/public/images/indoor_img/'
                this.category = 'indoor'
            }
            else if (this.article.global_article.category === 'outdoor') {
                this.image_dir = '/public/images/outdoor_img/'
                this.category = 'outdoor'
            }
            else if (this.article.global_article.category === 'mount_route') {
                this.image_dir = '/public/images/mount_route_img/'
                this.category = 'mount_route'
            }
            // else if (this.article.global_article.category === 'other') {
            //     this.image_dir = 'public/images/other_img/'
            //     this.category = 'other'
            // }
            else if (this.article.global_article.category === 'ice') {
                this.image_dir = '/public/images/ice_img/'
                this.category = 'ice'
            }
        },
        methods: {
        }
    }
</script>