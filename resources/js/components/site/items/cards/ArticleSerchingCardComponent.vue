<template>
    <div class="food col-md-4">
        <div class="portfolio-img view view-first">

            <img :src="image_dir+article.image" class="img-responsive" :alt='article[0][0].title' />

            <div class="mask">
                <a :href="catedory + '/' + article.url_title" class="info"><i class="fa fa-arrow-right"></i></a>
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
            if (this.article.category === 'indoor') {
                this.image_dir = '../images/indoor_img/'
                this.catedory = 'indoor'
            }
            if (this.article.category === 'outdoor') {
                this.image_dir = '../images/outdoor_img/'
                this.catedory = 'outdoor'
            }
            if (this.article.category === 'mount_route') {
                this.image_dir = '../images/mount_route_img/'
                this.catedory = 'mount_route'
            }
            if (this.article.category === 'other') {
                this.image_dir = '../images/other_img/'
                this.catedory = 'other'
            }
            if (this.article.category === 'ice') {
                this.image_dir = '../images/ice_img/'
                this.catedory = 'ice'
            }
        },
        methods: {
        }
    }
</script>