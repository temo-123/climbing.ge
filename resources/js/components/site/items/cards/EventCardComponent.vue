<template>
    <div class="col-sm-4 eav">
        <div class="panel-primary event-primary">
            <div class="panel-heading">
                <h2>
                    <!-- <a href="#"> -->
                    <router-link :to="'event/'+event.url_title" class="btn btn-success">
                        {{ event[0][0].title }}
                    </router-link>
                    <!-- </a> -->
                </h2>
            </div>
            <div class="panel-body nopadding">
                <img :src="'images/event_img/'+event.image" class="img-responsive" :alt='event[0][0].title' />
                <div class="row nopadding">
                    <div class="col-sm-6 col-xs-6 nopadding">
                        <time class="end blue_green_2">
                            Start 
                            <span class="day">{{ event.start_data_day }}</span>
                            <span class="month">{{ event.start_data_month }}</span>
                        </time>
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 nopadding">
                        <time class="end blue_green_2">
                            End 
                            <span class="day">{{ event.end_data_day }}</span>
                            <span class ="month">{{ event.end_data_month }}</span>
                        </time>
                    </div>
                </div>
            </div>
            <div class="panel-footer panel-primary">

                <div class="event_size">
                    <span v-html="event[0][0].short_description"></span>
                </div>
                
                <router-link :to="'event/'+event.url_title" class="btn btn-success">
                <!-- <a href="#" class="btn btn-success"> -->
                    Read more...
                <!-- </a> -->
                </router-link>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    props: [
        'event',
    ],
}
</script>

<style>

</style>