<template>
    <div class="container top_menu_margin h-recent-work">
        <h1 class="page_title">{{ active_service.title }}</h1>
        <div class="row" v-if="active_service.image">
            <img class="service_page_image" :src="'../../images/service_img/'+active_service.image" :alt="active_service.title">
        </div>
        <div class="row service_page_text">
            {{ active_service.text }}
        </div>

        <div class="row" v-if="this.services.length > 0">
            <hr>
            <h2 class="other_servces">other service</h2>
            <ServiceItem
                v-for="service in services"
                :key='service.id'
                :service_data="service">
            </ServiceItem>
        </div>

    </div>
</template>

<script>

    import ServiceItem from '../items/ServiceItemComponent'
    export default {
        props:[
            'data'
        ],
        data: function () {
            return {
                services: [],
                active_service: [],
            };
        },
        components: {
            ServiceItem,
        },
        watch: {
            '$route' (to, from) {
                // this.get_services()
                this.get_service()
                window.scrollTo(0,0);
            }
        },
        mounted() {
            this.get_service()
        },
        methods: {
            get_services(serv_id){
                axios
                .put('../api/services/'+serv_id)
                .then(response => {
                    this.services = response.data
                })
                .catch(error =>{
                })
            },
            get_service(){
                axios
                .get('../api/services/'+this.$route.params.url_title)
                .then(response => {
                    this.active_service = response.data[0]
                    this.get_services(this.active_service.id)
                })
                .catch(error =>{
                })
            }
        }
    }
</script>
