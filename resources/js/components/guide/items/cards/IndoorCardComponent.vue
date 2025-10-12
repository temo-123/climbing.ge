<template>
    <div class='row featurette indoor-card'>
        <div class='col-md-6 indoor_img'>
            <div class="product-image" v-if="indoor.global_data.new_flag">
                <div class="discount-percent-badge discount_percent_badge_for_indoor discount-badge-fourty">NEW</div>
            </div>
            <router-link :to="'indoor/'+indoor.global_data.url_title">
                <!-- <img :src="'images/indoor_img/'+indoor.image" alt="$indoor.locale_data -> title}}"> -->

                <site-img v-if="indoor.global_data.image != NULL" :src="'/images/indoor_img/'+indoor.global_data.image" :img_class="'m-image-hero--image'" :alt='indoor.locale_data.title'/>
                <site-img v-else :src="'/public/images/site_img/image.png'" :img_class="'m-image-hero--image'" :alt='indoor.locale_data.title'/>
            </router-link>
        </div>
        <div class='col-md-6 indoor_text indoot_text_for_mobile'>
            <div class="container">
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

<style scoped>
    .indoor-card {
        background: #fff;
        border-radius: 8px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        overflow: hidden;
        transition: all 0.3s ease;
        margin-bottom: 7%;
        padding: 20px;
    }

    .indoor-card:hover {
        box-shadow: 0 4px 16px rgba(0,0,0,0.2);
        transform: translateY(-5px);
    }

    .indoor_img {
        position: relative;
        border-radius: 8px;
        overflow: hidden;
    }

    .indoor_img img {
        width: 100%;
        height: auto;
        border-radius: 8px;
        transition: all 0.3s ease;
    }

    .indoor_img:hover img {
        box-shadow: 0 4px 16px rgba(0,0,0,0.2);
        filter: brightness(1.05);
    }

    .product-image {
        position: relative;
    }

    .discount_percent_badge_for_indoor {
        position: absolute;
        top: 10px;
        right: 10px;
        margin: 0;
        background: #279fbb;
        color: #fff;
        padding: 5px 10px;
        border-radius: 4px;
        font-size: 12px;
        font-weight: bold;
        z-index: 10;
    }

    .indoor_text {
        padding-left: 20px;
        display: flex;
        flex-direction: column;
        justify-content: center;
    }

    .indoor_text h2 {
        font-size: 1.8em;
        margin: 1.5em 0 1em 0;
        color: #333;
        font-weight: bold;
    }

    .indoor_text span {
        font-size: 1em;
        line-height: 1.6;
        color: #666;
        margin-bottom: 1em;
    }

    .indoor_text b {
        font-size: 1.2em;
        color: #279fbb;
        margin-bottom: 0.5em;
    }

    .indoor_text p {
        font-size: 1em;
        color: #555;
        margin: 0.5em 0;
    }

    .deanger_text {
        font-size: 1.2em;
        color: #e74c3c;
        font-weight: bold;
    }

    .discount_percent_badge_for_indoor {
        margin: 5px 18px 0 0;
        background: #279fbb;
        color: #fff;
        padding: 5px 10px;
        border-radius: 4px;
        font-size: 12px;
        font-weight: bold;
    }

    /* Mobile responsiveness */
    @media (max-width: 768px) {
        .indoor-card {
            flex-direction: column;
            padding: 15px;
        }

        .indoor_img {
            margin-bottom: 20px;
        }

        .indoor_text {
            padding-left: 0;
        }

        .indoor_text h2 {
            font-size: 1.5em;
            margin: 1em 0;
        }

        .indoor_text b {
            font-size: 1.1em;
        }

        .indoor_text p {
            font-size: 0.9em;
        }
    }
</style>
