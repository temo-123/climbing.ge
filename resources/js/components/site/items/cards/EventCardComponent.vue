<template>
    <div class="col-sm-4 eav">
        <div class="panel-primary event-primary">
            <div class="panel-heading">
                <h2>
                    <router-link :to="'event/'+event.url_title">
                        {{ event[0][0].title }}
                    </router-link>
                </h2>
            </div>
            <div class="panel-body nopadding">
                <router-link :to="'event/'+event.url_title">
                    <img :src="'images/event_img/'+event.image" class="img-responsive" :alt='event[0][0].title' />
                </router-link>
                <div class="row nopadding">
                    <div class="col-sm-6 col-xs-6 nopadding">
                        <time class="end green">
                            Start 
                            <span class="day"><!--{{ event.start_data_day }}-->{{this.start_day}}</span>
                            <span class="month"><!--{{ event.start_data_month }}-->{{this.start_month}}</span>
                            <span class="month">{{this.start_time}}</span>
                        </time>
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 nopadding">
                        <time class="end blue_green">
                            End 
                            <span class="day"><!--{{ event.end_data_day }}-->{{this.end_day}}</span>
                            <span class="month"><!--{{ event.end_data_month }}-->{{this.end_month}}</span>
                            <span class="month">{{this.end_time}}</span>
                        </time>
                    </div>
                </div>
            </div>
            <div class="panel-footer panel-primary">
                <div class="event_size">
                    <span v-html="event[0][0].short_description"></span>
                </div>
                
                <router-link :to="'event/'+event.url_title" class="btn btn-success">
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
                end_month: 0,
                start_time: 0,
                end_time: 0,
            };
        },
        mounted() {
            this.start_day = moment(this.event.start_data).format("D")
            this.end_day = moment(this.event.end_data).format("D")
            this.start_month = moment(this.event.start_data).format("MMM")
            this.end_month = moment(this.event.end_data).format("MMM")
            this.start_time = moment(this.event.start_data).format("H:MM A")
            this.end_time = moment(this.event.end_data).format( "H:MM A")
        },
        methods: {
            //
        }
    }
</script>

<style>
    .time{
        
    }
</style>