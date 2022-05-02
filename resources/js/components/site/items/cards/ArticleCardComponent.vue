<template>
    <div class="food col-md-4">
        <div class="portfolio-img view view-first">
            <div class="discount-percent-badge discount-badge-fourty">NEW</div>

            <img v-if="article[0][0].title != null" :src="image_dir+article.image" class="img-responsive" :alt='article[0][0].title' />
            <img v-else :src="'../../../../public/images/site_img/image.png'" class="img-responsive" :alt='article[0][0].title+"fffffffff"'>

            <div class="mask">
                <!-- <a href="#" class="info"><i class="fa fa-arrow-right"></i></a> -->
                <router-link :to="this.route" class="info">
                    <i class="fa fa-arrow-right"></i>
                </router-link>
            </div>
            
            <div class="isotope-item-dtls">
                <h3>{{ article[0][0].title }}</h3>
                <div class="article_size">
                    <span v-html="article[0][0].short_description"></span>
                </div>
            </div>
        </div>
        
    </div>  
</template>

<script>
    export default {
        props: [
            'article',
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