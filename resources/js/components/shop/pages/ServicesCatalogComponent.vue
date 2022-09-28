<template>
    <div class="col-md-12">
        <h1 class="page_title">Services</h1>

        <div class="bar"><i class="fa fa-exclamation-triangle"></i></div>
        
        <h2 style="text-align: center;">
            <span v-html="this.$siteData.services_description"></span>
        </h2>

        <div class="row">
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
        data: function () {
            return {
                services: [],
                site_data: [],
            };
        },
        components: {
            ServiceItem,
        },
        mounted() {
            this.get_services()
        },
        methods: {
            get_services(){
                axios
                .get('../api/services/'+localStorage.getItem('lang'))
                .then(response => {
                    this.services = response.data
                })
                .catch(error =>{
                })
            },
        }
    }
</script>
