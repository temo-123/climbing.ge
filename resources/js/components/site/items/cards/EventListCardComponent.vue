<template>
    <li>
        <div class="direction-r" v-if="event.global_event.category == 'event'">
            <div class="flag-wrapper">
                <span class="hexa"></span>
                <span class="flag">Event / festival.</span>
                <span class="time-wrapper"
                    v-if="
                        new Date().getFullYear() == this.end_year &&
                        new Date().getDate() >= this.start_day &&
                        new Date().getDate() <= this.end_day &&
                        this.start_time_for_check <= this.start_time
                    ">
                    <span class="time naw_time">Now</span>
                    <span class="time naw_time">Finish at - {{this.end_day}} {{this.end_month}}</span>
                </span>
                <span class="time-wrapper"
                    v-else-if="
                        new Date().getFullYear() == this.end_year &&
                        new Date().getDate() < this.end_day &&
                        new Date().getDate() == this.end_day
                    ">
                    <span class="time finished_time">Finished</span>
                </span>
                <span class="time-wrapper" v-else>
                    <span class="time" v-if="new Date().getFullYear() != this.start_year">{{this.start_day}} {{this.start_month}} {{this.start_year}}</span>
                    <span class="time" v-else>{{this.start_day}} {{this.start_month}}</span>

                    <span class="time">{{this.start_time}}</span>
                </span>

                <!-- <span class="time-wrapper">
                    <span class="time naw_time">Now</span>
                    <span class="time naw_time">Finish at - {{this.end_day}} {{this.end_month}} </span>
                </span> -->
            </div>

            <div class="desc">
                <router-link :to="'event/'+event.global_event.url_title">
                    <site-img v-if="event.global_event.image != null" :src="'/public/images/event_img/'+event.global_event.image" :img_class="'img-responsive'" :alt='event.locale_event.title'/>
                    <site-img v-else :src="'/public/images/site_img/image.png'" :img_class="'img-responsive'" :alt='event.locale_event.title'/>
                </router-link>

                <h3 class="event_timeline_title">
                    <router-link :to="'event/'+event.global_event.url_title">
                        {{ event.locale_event.title }}
                    </router-link>
                </h3>

                <span v-html="event.locale_event.short_description"></span>
            </div>
        </div>
        <div class="direction-l" v-else-if="event.global_event.category == 'competition'">
            <div class="flag-wrapper">
                <span class="hexa"></span>
                <span class="flag">Competition.</span>
                <span class="time-wrapper">
                    <span class="time" v-if="new Date().getFullYear() != this.start_year">{{this.start_day}} {{this.start_month}} {{this.start_year}}</span>
                    <span class="time" v-else>{{this.start_day}} {{this.start_month}}</span>

                    <span class="time">{{this.start_time}}</span>
                </span>
            </div>
            <div class="desc">
                <router-link :to="'event/'+event.global_event.url_title">
                    <site-img v-if="event.global_event.image != null" :src="'/public/images/event_img/'+event.global_event.image" :img_class="'img-responsive'" :alt='event.locale_event.title'/>
                    <site-img v-else :src="'/public/images/site_img/image.png'" :img_class="'img-responsive'" :alt='event.locale_event.title'/>
                </router-link>

                <h3 class="event_timeline_title">
                    <router-link :to="'event/'+event.global_event.url_title">
                        {{ event.locale_event.title }}
                    </router-link>
                </h3>

                <span v-html="event.locale_event.short_description"></span>
            </div>
        </div>
    </li>
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
                time: ''
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
            this.end_time_for_check = moment(this.event.global_event.end_data).format( "H:MM")

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
    .finished_time{
        background: #f30000 !important;
    }
    .naw_time{
        background: #e700f3 !important;
    }
</style>