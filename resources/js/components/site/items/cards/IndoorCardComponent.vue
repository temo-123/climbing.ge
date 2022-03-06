<template>
    <div class='row'>
        <div class='col-md-6 indoor_img'>
            <router-link :to="'indoor/'+indoor.url_title">
            <!-- <a href=" route('indoor_page', array('title'=>$indoor['url_title'])) }}"> -->
                <img :src="'images/indoor_img/'+indoor.image" alt="$indoor[0][0] -> title}}">
            <!-- </a> -->
            </router-link>
        </div>
        <div class='col-md-6 indoor_text indoot_text_for_mobile'>
        <div class="container">
            <div class='row'>
                <router-link :to="'indoor/'+indoor.url_title">
                <!-- <a href=" route('indoor_page', array('title'=>$indoor['url_title'])) }}"> -->
                    <h2 style="margin: 2em; text-aline: center;"> {{ indoor[0][0].title }}</h2>
                <!-- </a> -->
                </router-link>
                <span v-html="indoor[0][0].short_description"></span>
            </div>
            <div class='row'>
                <div class='col-sm-12 col-xs-6 col-md-6 col-lg-6 col-xl-6'>
                    <b style="font-size: 120%;">{{__ ('prices from')}}</b>
                    <p style="font-size: 100%;"><strong>{{ indoor.price_from }} GEL </strong></p>
                </div>
                <div class='col-sm-12 col-xs-6 col-md-6 col-lg-6 col-xl-6'>
                    <b style="font-size: 120%;">{{__ ('working time')}}</b>
                    <p style="font-size: 100%;"> {{ indoor.working_time }}</p>

                    <b style="font-size: 120%; color: red;">{{__ ('closed')}}</b>
                </div>
            </div>
            <!-- -- <div class='row'>
                <div class='col-xs-12' style='text-align: center; margin-left: -10px;'>
                    @foreach($reviews as $review)
                    @if($review['article_title'] == $indoor[0][0]->url_title)
                    <input id="input-1" name="input-1" class="rating rating-loading" data-min="0" data-max="5" data-step="0.1" value="$review['review']}}">
                    @endif
                    @endforeach
                </div>
            </div> --}} -->
        </div>
        </div>
    </div>
</template>

<script>
export default {
    props: [
        'indoor',
    ],
}
</script>

<style>

</style>