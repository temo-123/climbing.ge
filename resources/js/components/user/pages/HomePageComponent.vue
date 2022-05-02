<template>
    <div class="row">
        <div class="col-sm-3">
            <left-menu />
        </div>
        <div class="col-sm-9">
            <div class="row justify-content-center">
                <div class="col-md-12">
                    <div class="card">
                        <!-- <div class="card-header"> __('Dashboard') }}</div> -->

                        <div class="card-body">
                            <div class="alert alert-success" role="alert">
                                <h1>Hi {{ this.user['name'] }}</h1>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- <div class="row justify-content-center">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">Sites index pages</div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-sm-6 col-md-6 col-lg-6">
                                    <a href=' route("index") }}' target="_blank">climbing.ge</a>
                                </div>
                                <div class="col-sm-6 col-md-6 col-lg-6">
                                    <a href=' route("shop_index") }}' target="_blank">shop.climibng.ge</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div> -->

            <div class="row">
                <div class="col-md-12">
                    <span v-if="!this.user['name'] || !this.user['surname'] || !this.user['country'] || !this.user['city'] || !this.user['email']">
                        <div class="alert alert-danger" role="alert">
                            <strong>Danger!</strong> Your personal data is missing, this can lead to rolling problems. check page "option", and add missing information.
                        </div>
                    </span>
                    <span v-if="!this.user['email_verified_at']">
                        <div class="alert alert-danger" role="alert">
                            <strong>Danger!</strong> We sent you an email for verification, please check your email and confirm it.
                        </div>
                    </span>

                    <span v-if="!this.user['image']">
                        <div class="alert alert-danger" role="alert">
                            <strong>Warning!</strong> Add your profil image and piple faind you isier
                        </div>
                    </span>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    // import Editor from '../items/canvas/EditorComponent.vue'
    export default {
        // components: {
        //     Editor,
        // },
        data(){
            return{
                user: null,
            }
        },
        mounted(){
            axios
            .get('/api/auth_user')
            .then((response)=>{
                this.user = response.data
                // console.log(this.user);
            })
        },
        methods: {
            // logout(){
            //     axios
            //     .post('/api/logout')
            //     .then(()=>{
            //         window.location.href = "http://climibng.loc";
            //         // this.$router.push({name: "login"})
            //     })
            // }
        }
    }
</script>

<style>

</style>