<template>
    <div class="food col-md-4">
        <div class="portfolio-img view view-first product-image">
            <div class="discount-percent-badge discount-badge-fourty">NEW</div>

            <!-- <img :src="image_dir+article.area.image" class="img-responsive" :alt='article.area[0][0].title' /> -->

            <img v-if="article.area[0][0].image" :src="image_dir+article.area.image" class="img-responsive" :alt='article.area[0][0].title' />
            <img v-else :src="'../../../../public/images/site_img/image.png'" class="img-responsive" :alt='article.area[0][0].title+"fffffffff"'>

            <div class="mask">
                <!-- <a href="#" class="info"> -->
                <router-link :to="'outdoor/'+article.area.url_title" class="info">
                    <i class="fa fa-arrow-right"></i>
                </router-link>
                <!-- </a> -->
            </div>
            <div class="isotope-item-dtls">
                <h3>{{ article.area[0][0].title }}</h3>
                <div class="article_size text-center">
                    <span v-html="article.area[0][0].short_description"></span>
                    <div class="col text-center">
                        
                        <div class="row">
                            {{ article.route_quantyty.sectors }} Sectors
                            {{ article.route_quantyty.routes }} Routes
                        </div>

                        <div class="row" v-if="article.route_quantyty.mtps > 0">
                            {{ article.route_quantyty.mtps }} Multy pitch
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
            'article',
            'image_dir'
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

<style>

</style>