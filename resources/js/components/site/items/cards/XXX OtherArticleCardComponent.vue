<template>
    <div class="col-md-2">
        <div class="many_articles_img">
            <router-link :to="article.url_title">
                <!-- <img :src="'../../'+this.image_dir+this.article.image" :alt="this.article[0][0].title"> -->
                                
                <site-img v-if="article.image != NULL" :src="'/public/'+this.image_dir+this.article.image" :img_class="'m-image-hero--image'" :alt='article[0][0].title'/>
                <site-img v-else :src="'/public/images/site_img/image.png'" :img_class="'m-image-hero--image'" :alt='article[0][0].title'/>
            </router-link>
        </div>
        <div class="many_articles_title">
            <router-link :to="article.url_title">
                <h3>{{ this.article[0][0].title }}</h3>
            </router-link>
        </div>
    </div>
</template>

<script>
    export default {
        props: [
            'article',
            'route',
            'image_dir'
        ],
    }
</script>

<style>

</style>