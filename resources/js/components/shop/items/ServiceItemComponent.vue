<template>
    <div class="col-md-3 col-sm-6">
        <div class="service-box ">
            
            <div v-if="service_data.image.length > 0" class="service-icon red services_bacground_image" :style="'background-image: url(../../images/service_img/'+service_data.image+');'">
                <div class="front-content">
                    <h3 class='services-title'>{{service_data[0][0].title}}</h3>
                </div>
            </div>
            
            <div v-else class="service-icon red services_bacground_image">
                <div class="front-content">
                    <h3 class='services-title'>{{ service_data[0][0].title }}</h3>
                </div>
            </div>
                
            <div class="service-content">
                <router-link :to="'../service/' + service_data.url_title">
                    <h3>{{ service_data[0][0].title }}</h3>
                </router-link>
                <p>{{ service_data[0][0].short_description }}</p>
            </div>

        </div>
    </div>
</template>

<script>
    export default {
        props:[
            'service_data'
        ],
        data: function () {
            return {
                
            };
        },
        components: {
            
        },
        mounted() {

        },
        methods: {
            
        }
    }
</script>
