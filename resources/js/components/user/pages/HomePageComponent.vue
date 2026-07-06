<template>
    <div class="row">
        
        <left-menu />

        <div class="col-sm-12">

            <div class="row justify-content-center">
                <div class="col-md-12">
                    <div class="alert alert-success" role="alert">
                        <h1>Hi {{ this.user['name'] }}</h1>
                    </div>
                </div>
            </div>

            <!-- <servicesListComponent /> -->

            <div class="row justify-content-center">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">Sites index pages</div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-sm-6 col-md-6 col-lg-3 text-center">
                                    <button class="btn btn_service_guid" @click="go_to_service('guid')">Guidbook</button>
                                </div>
                                <div class="col-sm-6 col-md-6 col-lg-3 text-center">
                                    <button class="btn btn_service_shop" @click="go_to_service('shop')">Shop</button>
                                </div>
                                <div class="col-sm-6 col-md-6 col-lg-3 text-center">
                                    <button class="btn btn_service_blog" @click="go_to_service('blog')">Blog</button>
                                </div>
                                <div class="col-sm-6 col-md-6 col-lg-3 text-center">
                                    <button class="btn btn_service_summit" @click="go_to_service('summit')">Summits</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <user_notifications_list />

        </div>
    </div>
</template>

<script>
    import user_notifications_list from '../items/notificatione/UserNotificationsListComponent.vue'

    import servicesListComponent from '../../global_components/ServicesListComponent.vue'
    import go_to_service_mixin from '../../../mixins/go_to_service_mixin.js'

    export default {
        mixins: [go_to_service_mixin],
        components: {
            user_notifications_list,
            servicesListComponent
        },
        data(){
            return{
                user: {},
            }
        },
        mounted(){
            this.get_user_data();
        },
        methods: {
            get_user_data(){
                axios
                .get('/auth_user')
                .then((response)=>{
                    this.user = response.data
                })
            },
        }
    }
</script>

<style>
.btn_service_guid, .btn_service_shop, .btn_service_blog, .btn_service_summit{
    margin: 2%;
}
.btn_service_guid{
    background-color: #279fbb;
}
.btn_service_shop{
    background-color: #27bb7d;
}
.btn_service_blog{
    background-color: #777777;
}
.btn_service_summit{
    background-color: #ff6b6b;
}
</style>