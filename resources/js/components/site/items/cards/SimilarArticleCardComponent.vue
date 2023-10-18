<template>
    <div class="col-md-3 col-xs-3">
        <div class="similar_articles_img">
            <router-link :to="this.route">
                <site-img v-if="article.image != null" :src="'/public/' + this.image_dir + '/' + article.image" :img_class="'img-responsive'" :alt='article[0][0].title'/>
                <site-img v-else :src="'/public/images/site_img/image.png'" :img_class="'img-responsive'" :alt='article[0][0].title'/>
            </router-link>
        </div>
        <div class="similar_article_data">
            <div class="similar_articles_title">
                <router-link :to="this.route">
                    <h3>{{ article[0][0].title }}</h3>
                </router-link>
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
            'category'
        ],
        mounted() {
            // console.log( 'article.area[0].url_title' );
            // console.log( article );
            // console.log( 'article.area[0].url_title' );
        },
    }
</script>

<style>
    
</style>