<template>
    <div class='row featurette' style='margin-bottom: 7%'>
        <div class='col-md-6 indoor_img'>
            <router-link :to="'indoor/'+indoor.global_data.url_title">
                <!-- <img :src="'images/indoor_img/'+indoor.image" alt="$indoor.locale_data -> title}}"> -->

                <site-img v-if="indoor.global_data.image != NULL" :src="'/images/indoor_img/'+indoor.global_data.image" :img_class="'m-image-hero--image'" :alt='indoor.locale_data.title'/>
                <site-img v-else :src="'/public/images/site_img/image.png'" :img_class="'m-image-hero--image'" :alt='indoor.locale_data.title'/>
            </router-link>
        </div>
        <div class='col-md-6 indoor_text indoot_text_for_mobile'>
            <div class="container">
                <div class="product-image" v-if="indoor.global_data.new_flag">
                    <div class="discount-percent-badge discount_percent_badge_for_indoor discount-badge-fourty">NEW</div>
                </div>
                <div class='row'>
                    <router-link :to="'indoor/'+indoor.global_data.url_title">
                    <!-- <a href=" route('indoor_page', array('title'=>$indoor['url_title'])) }}"> -->
                        <h2 style="margin: 1.5em 0;"> {{ indoor.locale_data.title }}</h2>
                    <!-- </a> -->
                    </router-link>
                    <span v-html="indoor.locale_data.short_description"></span>
                </div>
                <div class='row'>
                    <div class='col-sm-12 col-xs-6 col-md-6 col-lg-6 col-xl-6' v-if="indoor.global_data.price_from">
                        <b style="font-size: 120%;">{{ $t('guide.article.prices_from')}}</b>
                        <p style="font-size: 100%;"><strong>{{ indoor.global_data.price_from }} GEL </strong></p>
                    </div>
                    <div class='col-sm-12 col-xs-6 col-md-6 col-lg-6 col-xl-6'>
                        <b style="font-size: 120%;">{{ $t('guide.article.working_time')}}</b>
                        <span v-if="indoor.global_data.open_time && indoor.global_data.closed_time">
                            <span v-if="!indoor.global_data.closed">
                                <p style="font-size: 100%;"> {{ indoor.global_data.open_time }} - {{indoor.global_data.closed_time}}</p>
                                <p  class="deanger_text">{{ status(indoor.global_data.open_time, indoor.global_data.closed_time) }}</p>
                            </span>

                            <p class="deanger_text" v-if="indoor.global_data.closed">{{ $t('guide.article.closed')}}</p>
                        </span>
                        <span v-else>
                            <p class="deanger_text">{{ $t('guide.article.no_info_working_time')}}</p>
                        </span>
                    </div>
                </div>
                <!-- -- <div class='row'>
                    <div class='col-xs-12' style='text-align: center; margin-left: -10px;'>
                        @foreach($reviews as $review)
                        @if($review['article_title'] == $indoor.locale_data->url_title)
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
            // open(){
            //     return moment( "2013-2-1 14:00:00" ).format( "H:MM:mma")
            // },
            // close(){
            //     return moment( "2013-2-1 14:00:00" ).format( "dddd h:mma D MMM YYYY")
            // }

            status(open, close){
                var open_hourse = moment(open, 'HH:mm').format( "HH")
                var open_minits = moment(open, 'HH:mm').format( "mm")

                var close_hourse = moment(close, 'HH:mm').format( "HH")
                var close_minits = moment(close, 'HH:mm').format( "mm")

                var today = new Date();
                // var realy_time = today.getHours() + ":" + today.getMinutes() + ":" + today.getSeconds()

                if(open_hourse < today.getHours() && close_hourse > today.getHours()){
                    return 'Open at this moment'
                }
                else if(open_hourse > today.getHours() && close_hourse < today.getHours()){
                    return 'Closed at this moment'
                }
                else if(open_hourse + 1 == today.getHours()){
                    return 'Open in less than in hour'
                }
                else if(close_hourse -1 == today.getHours()){
                    return 'Closes in less than in hour'
                }

                // <p class="deanger_text" >closes soon</p>
                // <p class="deanger_text" >closes in less than an hour</p>
                // <p class="deanger_text" >closes in less than 30 minutes</p>
                // <p class="deanger_text" >now closed</p>
            }
        }
    }
</script>

<style>
    .deanger_text{
        font-size: 120%; 
        color: red;
    }
    .discount_percent_badge_for_indoor {
        margin: 5px 18px 0 0;
    }
</style>