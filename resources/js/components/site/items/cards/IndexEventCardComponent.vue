<template>
    <div class="col-sm-4 eav">
        <div class="panel-primary event-primary">
            <div class="panel-heading">
                <span class="index_event_category_flag" v-if="event.global_event.category == 'event'">Event / Festival</span>
                <span class="index_event_category_flag" v-else-if="event.global_event.category == 'competition'">Competition</span>
                <h2 style="font-size: 1.8em">
                    <router-link :to="'event/'+event.global_event.url_title">
                        {{ event.locale_event.title }}
                    </router-link>
                </h2>
            </div>
            <div class="panel-body nopadding">
                <router-link :to="'event/'+event.global_event.url_title">

                    <site-img v-if="event.global_event.image != null" :src="'/public/images/event_img/'+event.global_event.image" :img_class="'img-responsive'" :alt='event.locale_event.title'/>
                    <site-img v-else :src="'/public/images/site_img/image.png'" :img_class="'img-responsive'" :alt='event.locale_event.title'/>

                </router-link>
                <span v-if="!(!end_day) && start_time_h > 0">
                    <div class="row nopadding"
                        v-if="
                                new Date().getFullYear() == this.end_year &&
                                new Date().getDate() >= this.start_day &&
                                new Date().getDate() <= this.end_day &&
                                this.start_time_for_check <= this.start_time
                            "
                    >
                        <div class="col-sm-12 col-xs-12 nopadding" >
                            <time class="end orange_red">
                                <span class="day underway_now">Underway now</span>
                                <span class="month">Finish at - {{this.end_day}} {{this.end_month}} {{this.end_time}}</span>
                                <span class="month"></span>
                                <span class="month"></span>
                            </time>
                        </div>
                    </div>
                    <div class="row nopadding"
                        v-else-if="
                                new Date().getFullYear() == this.end_year &&
                                new Date().getDate() < this.end_day &&
                                new Date().getDate() == this.end_day
                            "
                    >
                        <div class="col-sm-12 col-xs-12 nopadding" >
                            <time class="end orange_red">
                                <span class="day underway_now">Finished</span>
                            </time>
                        </div>
                    </div>

                    <div class="row nopadding" v-else>
                        <div class="col-sm-6 col-xs-6 nopadding">
                            <time class="end green">
                                Start 
                                <span class="day">{{this.start_day}}</span>
                                <span class="month">{{this.start_month}}</span>
                                <span class="month" v-if="new Date().getFullYear() != this.start_year">{{this.start_year}}</span>
                                <span class="month">{{this.start_time}}</span>
                            </time>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 nopadding">
                            <time class="end blue_green">
                                End 
                                <span class="day">{{this.end_day}}</span>
                                <span class="month">{{this.end_month}}</span>
                                <span class="month" v-if="new Date().getFullYear() != this.end_year">{{this.end_year}}</span>
                                <span class="month">{{this.end_time}}</span>
                            </time>
                        </div>

                    </div>
                </span>
                <span v-else>
                    <div class="row nopadding">
                        <div class="col-sm-12 col-xs-12 nopadding">
                            <time class="end green">
                                Start 
                                <!-- <span class="day">{{this.start_day}}</span> -->
                                <span class="day">{{this.start_month}}</span>
                                <span class="month">{{this.start_year}}</span>
                                <!-- <span class="month">{{this.start_time}}</span> -->
                            </time>
                        </div>
                    </div>
                </span>
            </div>
            <div class="panel-footer panel-primary">
                <div class="event_size" v-if="event.locale_event.short_description != null">
                    <span v-html="event.locale_event.short_description"></span>
                </div>
                
                <router-link :to="'event/'+event.global_event.url_title" class="btn btn-success">
                    Read more...
                </router-link>
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
            'event',
        ],

        data: function () {
            return {
                start_day: 0,
                end_day: 0,
                start_month: 0,
                start_year: 0,
                end_month: 0,
                end_year: 0,
                start_time: 0,
                end_time: 0,
                start_time_for_check: 0,
                end_time_for_check: 0,
                time: '',
                start_time_h: 0
            };
        },
        mounted() {
            this.start_day = moment(this.event.global_event.start_data).format("D")
            this.end_day = moment(this.event.global_event.end_data).format("D")

            this.start_month = moment(this.event.global_event.start_data).format("MMM")
            this.end_month = moment(this.event.global_event.end_data).format("MMM")

            this.start_year = moment(this.event.global_event.start_data).format("Y")
            this.end_year = moment(this.event.global_event.end_data).format("Y")

            this.start_time = moment(this.event.global_event.start_data).format("H:MM A")
            this.end_time = moment(this.event.global_event.end_data).format( "H:MM A")

            this.start_time_for_check = moment(this.event.global_event.start_data).format("H:MM")
            this.end_time_for_check = moment(this.event.global_event.end_data).format("H:MM")

            this.start_time_h = moment(this.event.global_event.end_data).format("H")

            this.realy_time()
        },
        methods: {
            realy_time(){
                var d = new Date();
                var s = d.getSeconds();
                var m = d.getMinutes();
                var h = d.getHours();
                this.time = h + ":" + m
            }
        }
    }
</script>

<style>
    .img-responsive{
        width: 100%;
    }
    .underway_now{
        font-size: 2em !important;
    }
    .index_event_category_flag{
        width: auto;
    }
</style>