<template>
    <div class="row">
        <!-- <div class="col-sm-3"> -->
            <left-menu />
        <!-- </div> -->

        <div class="col-sm-12">

            <div class="row justify-content-center">
                <div class="col-md-12">
                    <div class="alert alert-success" role="alert">
                        <h1>Hi {{ this.user['name'] }}</h1>
                    </div>
                </div>
            </div>

            <div class="row justify-content-center">
                <div class="col-md-12">
                    <div class="alert alert-secondary" role="alert">
                        <h1>Hi {{ this.user['name'] }}. Let's start work. Check task panel!</h1>

                        <img src="/public/images/site_img/worker.png" alt="Baned"/>
                    </div>    
                </div>
            </div>

            <div class="row justify-content-center">
                <div class="col-md-12">
                    <div class="alert alert-secondary" role="alert">
                        <h1>Hi {{ this.user['name'] }}. Let's start work with our team.</h1>

                        <img src="/public/images/site_img/team staff.png" alt="Baned"/>
                    </div>    
                </div>
            </div>

            <div class="row justify-content-center">
                <div class="col-md-12">
                    <div class="alert alert-secondary" role="alert">
                        <h1>Hi {{ this.user['name'] }}. let's start doing projects together.</h1>

                        <img src="/public/images/site_img/team logo.png" alt="Baned"/>
                    </div>    
                </div>
            </div>

            <div class="row justify-content-center">
                <div class="col-md-12">
                    <div class="alert alert-danger" role="alert">
                        <h1>Hi {{ this.user['name'] }}. Your account has been baned! You will no longer be able to use the features of the Service. You will also be able to register soon.</h1>

                        <img src="/public/images/site_img/Banned.png" alt="Baned"/>
                    </div>    
                </div>
            </div>

            <div class="row justify-content-center">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">Sites index pages</div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-sm-12 col-md-4 text-center">
                                    <button class="btn btn_service_guid" @click="go_to_service('guid')">Climbing Guidbook</button>
                                </div>
                                <div class="col-sm-12 col-md-4 text-center">
                                    <button class="btn btn_service_shop" @click="go_to_service('shop')">Climbing Shop</button>
                                </div>
                                <div class="col-sm-12 col-md-4 text-center">
                                    <button class="btn btn_service_films" @click="go_to_service('films')">Climbing Films</button>
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
    export default {
        components: {
            user_notifications_list,
        },
        data(){
            return{
                user: [],
                MIX_SITE_URL: process.env.MIX_SITE_URL,
                MIX_APP_SSH: process.env.MIX_APP_SSH,
            }
        },
        mounted(){
            // 
        },
        methods: {
            get_user_data(){
                axios
                .get('/api/auth_user')
                .then((response)=>{
                    this.user = response.data
                    this.get_user_queries(this.user.id)
                })
            },
            go_to_service(service){
                if(service == "shop"){
                    window.open(this.MIX_APP_SSH + 'shop.' + this.MIX_SITE_URL + '')
                }
                else if(service == "guid"){
                    window.open(this.MIX_APP_SSH  + this.MIX_SITE_URL + '/')
                }
                else if(service == "films"){
                    window.open(this.MIX_APP_SSH  + 'films.' + this.MIX_SITE_URL + '/')
                }
            },
        }
    }
</script>

<style>
.btn_service_guid, .btn_service_shop, .btn_service_films{
    margin: 2%;
}
.btn_service_guid{
    background-color: #279fbb;
}
.btn_service_shop{
    background-color: #27bb7d;
}
.btn_service_films{
    background-color: #777777;
}
</style>