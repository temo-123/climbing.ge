<template>
    <div class="article_card col-md-4">
        <div class="portfolio-img view view-first">
            <div class="product-image" v-if="article.area.global_data.new_flag">
                <div class="discount-percent-badge discount-badge-fourty">NEW</div>
            </div>

            <site-img v-if="article.area.global_data.image != NULL" :src=" '/public/' + image_dir+article.area.global_data.image" :img_class="'img-responsive'" :alt='article.area.locale_data.title'/>
            <site-img v-else :src="'/public/images/site_img/image.png'" :img_class="'img-responsive'" :alt='article.area.locale_data.title'/>

            <div class="mask">
                <router-link :to="'outdoor/'+article.area.global_data.url_title" class="info">
                    <i class="fa fa-arrow-right"></i>
                </router-link>
            </div>
            
            <div class="isotope-item-dtls">
                <h3>{{ article.area.locale_data.title }}</h3>
                <div class="article_size text-center">
                    <span v-html="article.area.locale_data.short_description"></span>
                    <div class="col text-center">
                        
                        <div class="row">
                            {{ article.route_quantyty.sectors }} Sectors
                            {{ article.route_quantyty.routes }} Routes
                        </div>

                        <div class="row" v-if="article.route_quantyty.mtps > 0">
                            {{ article.route_quantyty.mtps }} Multy pitch
                        </div>

                        <hr class="region_likes_line" v-if="!true">

                        <div class="row" v-if="!true">
                            <span > 
                                <i class="fa fa-heart-o " ></i> - 0
                            </span>
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
.region_likes_line{
    margin: 2%;
    border-color: #eee;
}
/* .article_card{
    display: flex;
    flex-flow: row wrap;
}    */
</style>