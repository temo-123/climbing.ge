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
                    <div class="image-container">
                        <site-img v-if="event.global_event.image != null" :src="'/public/images/event_img/'+event.global_event.image" :img_class="'img-responsive'" :alt='event.locale_event.title'/>
                        <site-img v-else :src="'/public/images/site_img/image.png'" :img_class="'img-responsive'" :alt='event.locale_event.title'/>
                        <div class="image-mask">
                            <i class="fa fa-eye"></i>
                        </div>
                    </div>
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
                                <i class="fa fa-play-circle"></i>
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
                                <i class="fa fa-check-circle"></i>
                                <span class="day underway_now">Finished</span>
                            </time>
                        </div>
                    </div>

                    <div class="row nopadding" v-else>
                        <div class="col-sm-6 col-xs-6 nopadding">
                            <time class="end green">
                                <i class="fa fa-calendar-plus"></i>
                                Start 
                                <span class="day">{{this.start_day}}</span>
                                <span class="month">{{this.start_month}}</span>
                                <span class="month" v-if="new Date().getFullYear() != this.start_year">{{this.start_year}}</span>
                                <span class="month">{{this.start_time}}</span>
                            </time>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 nopadding">
                            <time class="end rad_blue">
                                <i class="fa fa-calendar-times"></i>
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
                                <i class="fa fa-calendar-plus"></i>
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
                
                <!-- <router-link :to="'event/'+event.global_event.url_title" class="btn btn-success">
                    <i class="fa fa-arrow-right"></i> Read more...
                </router-link> -->
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

<style scoped>
    .panel {
        border-radius: 10px;
        box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        background: #f0f8ff;
        overflow: hidden;
        margin-bottom: 20px;
        border: none;
    }

    .panel-primary .panel-heading {
        /* background-color: #e6f3ff; */
        background-color: #5b6a6d;
        border-bottom: 0px solid #dee2e6;
        padding: 15px;
        border-radius: 10px 10px 0 0;
    }

    .panel-heading h2 {
        margin: 0;
        font-size: 1.5em;
        color: #333;
    }

    .image-container {
        position: relative;
        overflow: hidden;
    }

    .img-responsive {
        width: 100%;
        transition: transform 0.3s ease;
    }

    .image-container:hover .img-responsive {
        transform: scale(1.05);
    }

    .image-mask {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0,0,0,0.5);
        display: flex;
        align-items: center;
        justify-content: center;
        opacity: 0;
        transition: opacity 0.3s ease;
        color: #fff;
    }

    .image-container:hover .image-mask {
        opacity: 1;
    }

    .image-mask i {
        font-size: 24px;
    }

    .panel-footer {
        /* background: #f9f9f9; */
        background: #5b6a6d;
        padding: 15px;
        border-top: 0px solid #ddd;
    }

    .btn-success {
        background: linear-gradient(45deg, #bae1ff, #a8e6cf);
        border: none;
        border-radius: 5px;
        padding: 10px 20px;
        transition: transform 0.2s ease;
        color: #333;
    }

    .btn-success:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(186, 225, 255, 0.3);
    }

    .index_event_category_flag {
        background: #f15a68;
        color: #fff;
        padding: 5px 10px;
        border-radius: 15px;
        font-size: 12px;
        margin-bottom: 10px;
        display: inline-block;
    }

    .end {
        font-size: 14px;
        padding: 10px;
        border-radius: 5px;
    }

    .end.green {
        background: #d4edda;
        color: #155724;
    }

    .end.blue_green {
        background: #cce7ff;
        color: #004085;
    }

    .end.rad_blue {
        background: #ffccdd;
        color: #004085;
    }

    .end i {
        margin-right: 5px;
        color: #4a90e2;
    }

    .underway_now {
        font-size: 1.5em !important;
        color: #856404;
    }

    .event_size {
        font-size: 14px;
        color: #666;
        margin-bottom: 10px;
        line-height: 1.4;
    }
</style>
