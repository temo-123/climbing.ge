<template>
    <div class="col-md-3">
        <div class="similar_articles_img">
            <!-- <router-link :to="'../outdoor/'+article.area.url_title" > -->
            <router-link :to="{name: 'outdoor', params: {url_title: article.area.url_title}}">
                <site-img v-if="article.area.image != null" :src="this.image_dir+article.area.image" :img_class="'img-responsive'" :alt='article.area[0][0].title'/>
                <site-img v-else :src="'../../../public/images/site_img/image.png'" :img_class="'img-responsive'" :alt='article.area[0][0].title'/>
            </router-link>
        </div>
        <div class="similar_article_data">
            <div class="similar_articles_title">
                <router-link :to="{name: 'outdoor', params: {url_title: article.area.url_title}}">
                <!-- <router-link :to="'../outdoor/'+article.area.url_title" > -->
                    <h3>{{ article.area[0][0].title }}</h3>
                </router-link>
            </div>
            <div class="col text-center similar_article_routes_quantity">
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
</template>

<script>
    export default {
        props: [
            'article',
            'image_dir',
            'route',
        ],
        mounted() {
            // console.log( 'article.area[0].url_title' );
            // console.log( article );
            // console.log( 'article.area[0].url_title' );
        },
    }
</script>

<style>
.similar_article_title{
    margin-bottom: 2%;
    font-size: 155%;
}
.similar_article_routes_quantity{
    color: #8b8b8b;
    margin-top: 5%;
    font-size: 90%;
}
.similar_articles{
    margin-top: 2%;
}
@media only screen and (max-width: 768px)
{
    .similar_articles{
        display: none;
    }
}
.similar_articles_img img{
    /* // margin-left: 10%; 
    // margin-right: 10%; 
    // width: 80%;  */
    border-radius: 1em;
}
.similar_articles_title{
    /* margin-top: -15%;  */
    text-align: center;
}
.similar_articles_title h3{
    margin: 0;
    font-size: 155%;
}
</style>