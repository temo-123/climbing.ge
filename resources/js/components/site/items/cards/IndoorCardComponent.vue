<template>
    <div class='row'>
        <div class='col-md-6 indoor_img'>
            <router-link :to="'indoor/'+indoor.url_title">
                <!-- <img :src="'images/indoor_img/'+indoor.image" alt="$indoor[0][0] -> title}}"> -->

                <site-img v-if="indoor.image != NULL" :src="'images/indoor_img/'+indoor.image" :img_class="'m-image-hero--image'" :alt='indoor[0][0].title'/>
                <site-img v-else :src="'../../../public/images/site_img/image.png'" :img_class="'m-image-hero--image'" :alt='indoor[0][0].title'/>
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
                    <span v-if="indoor.open_time != NULL && indoor.closed_time != NULL">
                        <span v-if="!indoor.closed">
                            <p style="font-size: 100%;"> {{ indoor.open_time }} - {{indoor.closed_time}}</p>
                            <p>{{ open() }}</p>
                            <!-- <p class="deanger_text" >closes soon</p>
                            <p class="deanger_text" >closes in less than an hour</p>
                            <p class="deanger_text" >closes in less than 30 minutes</p>
                            <p class="deanger_text" >now closed</p> -->
                        </span>

                        <p class="deanger_text" v-if="indoor.closed">{{__ ('closed')}}</p>
                    </span>
                    <span v-else>
                        <p class="deanger_text">There is no information about the work of the gym!</p>
                    </span>
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
    import moment from "moment"; // https://www.npmjs.com/package/vue-moment
    export default {
        components: {
            moment
        },
        props: [
            'indoor',
        ],
        mounted() {
            // alert(moment( "2013-2-1 14:00:00" ).format( "dddd h:mma D MMM YYYY"));

        },
        methods: {
            open(){
                return moment( "2013-2-1 14:00:00" ).format( "H:MM:mma")
            },
            close(){
                return moment( "2013-2-1 14:00:00" ).format( "dddd h:mma D MMM YYYY")
            }
        }
    }
</script>

<style>
    .deanger_text{
        font-size: 120%; 
        color: red;
    }
</style>