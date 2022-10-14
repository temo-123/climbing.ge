<template>
    <div class="col-md-12">
        <div class="row" >
            <div class="col-md-4 col-md-offset-4 text-center add_button">
                <div class="row service_icon" @click="open_post_modal()">
                    <i class="fa fa-plus m-2" style="font-size: 400%" aria-hidden="true"></i>
                    <h2 class="m-0">Add post</h2>
                    Add your ascent post
                </div>
            </div>
        </div>

        {{ get_auth_user }}

        <hr style="margin-top: 5%">

        <stack-modal
                :show="add_post_modal"
                title="Add post"
                @close="add_post_modal=false"
                :modal-class="{ [modalClass]: true }"
                :saveButton="{ visible: false, title: 'Post', btnClass: { 'btn btn-primary': true } }"
                :cancelButton="{ visible: false, title: 'Close', btnClass: { 'btn btn-danger': true } }"
            >
            <div class="model-body">
                <div class="row">
                    <form @submit.prevent="add_post" id="js_form" class="contact-form" method="POST" enctype="multipart/form-data">
                        
                        <div class="row" v-if="this.topics.length > 0">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <select class="form-control" v-model="selected_topic" @click="topic_status(selected_topic)">
                                        <option>Select Topic</option> 
                                        <option v-for="topic in topics" :key="topic.id" :value="topic.id">{{ topic.name }}</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row" v-if="this.outdoors.length > 0">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <select class="form-control" v-model="selected_outdoor" @click="get_sectors(selected_outdoor)">
                                        <option>Select Outdoor climbing area</option> 
                                        <option v-for="outdoor in outdoors" :key="outdoor.id">{{outdoor.area[0][0].title}}</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row" v-if="this.sectors.length > 0">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <select class="form-control" v-model="selected_sector">
                                        <option>Select climbing sector</option> 
                                        <option v-for="sector in sectors" :key="sector.id">{{sector.name}}</option> 
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="row" v-if="this.selected_sector">
                            <div class="col-md-6">
                                <input type="radio" id="route" name="fav_language" value="route"  v-model="route_type" @click="get_routes(selected_sector)">
                                <label for="route">Sport routes</label><br>
                            </div>
                            <div class="col-md-6">
                                <input type="radio" id="mtp" name="fav_language" value="mtp"  v-model="route_type" @click="get_mtps(selected_sector)">
                                <label for="mtp">Multy pitchs</label><br>
                            </div>
                        </div>
                        
                        <div class="row" v-if="this.routes.length > 0 && route_type == 'route'">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <select class="form-control" v-model="selected_rotes">
                                        <option>Select climbing route</option> 
                                        <option v-for="route in routes" :key="route.id">{{route.name}}</option> 
                                    </select>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row" v-if="this.mtps.length > 0 && route_type == 'mtp'">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <select class="form-control" v-model="selected_rotes">
                                        <option>Select climbing mtps</option> 
                                        <option v-for="mtp in mtps" :key="mtp.id">{{mtp.name}}</option> 
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <textarea rows="6" name="text" v-model="text" id="text" maxlength="500" placeholder="Your comment (Write comments only in English, no more than 500 characters!)" class="form-control textarea"></textarea>
                                    <div class="alert alert-danger" role="alert" v-if="errors.text">
                                        {{ errors.text[0] }}
                                    </div>
                                </div>
                            </div>
                        </div>
 
                        <form @submit="add_post_image()"  ref="myForm">
                          <div class="form-group clearfix">
                            <input type="file" name="profile_pic" id="profile_pic" >
                          </div>
                        </form>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <div class="form-group form_left">

                                        <vue-recaptcha
                                            :sitekey="MIX_GOOGLE_CAPTCHA_SITE_KEY"
                                            :loadRecaptchaScript="true"
                                            ref="recaptcha"
                                            type="invisible"
                                            @verify="onCaptchaVerified"
                                            @expired="onCaptchaExpired"
                                        >
                                        </vue-recaptcha>

                                    </div>
                                </div>
                            </div>
                        </div>

                        <button  v-if="is_verify_isset == false" class="btn btn btn-primary" disabled>Add comment</button>
                        <button v-else class="btn btn btn-primary" >Add comment</button>
                    </form>
                </div>
            </div>
        </stack-modal>
    </div>
</template>

<script>
    import StackModal from '@innologica/vue-stackable-modal'  //https://innologica.github.io/vue-stackable-modal/#sample-css
    import { mapActions, mapGetters } from 'vuex'
    import VueRecaptcha from 'vue-recaptcha'; //https://www.npmjs.com/package/vue-recaptcha
    //http://www.blog.tonyswierz.com/javascript/add-and-use-google-recaptcha-in-a-vuejs-laravel-project/
    export default {
        components: { 
            StackModal,
            VueRecaptcha 
        },
        data(){
            return{
                name: "",
                surname: "",
                email: "",
                text: "",

                add_post_modal: false,
                modalClass: '',

                errors: [],
                topics: [],
                outdoors: [],
                sectors: [],
                routes: [],
                mtps: [],

                selected_topic: '',
                selected_outdoor: '',
                selected_sector: '',
                selected_route: '',
                selected_mtp: '',

                route_type: '',

                is_verify_isset: false,

                MIX_GOOGLE_CAPTCHA_SITE_KEY: process.env.MIX_GOOGLE_CAPTCHA_SITE_KEY,
            }
        },
        mounted() {
           this.get_topics()
        },
        computed: mapGetters(["get_auth_user"]),
        // methods: mapActions(['authing_user']),
        methods: {
            ...mapActions(['authing_user']),
            // auth_user(){
            //     this.update_user_data()
            // },
            // open_image_modal(){
            //     this.add_image_modal=true
            // },
            onCaptchaVerified() {
                this.is_verify_isset = true
            },
            onCaptchaExpired(){
                this.is_verify_isset = false
            },
            open_post_modal(){
                this.add_post_modal=true
            },
            get_topics(){
                axios
                .get('../api/posts_topic/list/'+localStorage.getItem('lang'))
                .then(response => {
                    this.topics = response.data
                })
                .catch(error =>{
                })
            },
            add_post(){
                axios
                .post('/api/posts/add_post/', {
                    is_verify_isset: this.is_verify_isset,
                    topic: this.selected_topic,
                    user_id: 0,
                    text: this.text,

                    outdoor_id_id: this.selected_outdoor,
                    sector_id: this.selected_sector,
                    route_id: this.selected_route,
                    mtp_id: this.selected_mtp
                })
                .then(response => {
                    this.errors = []
                    this.text = ""
                    this.add_post_modal=false
                    this.is_verify_isset = false
                    })
                .catch(error =>{
                    if (error.response.status == 422) {
                        this.errors = error.response.data.errors
                    }
                    else{
                        if (response.data.message) {
                            alert(response.data.message)
                        }
                    }
                })
            },
            topic_status(topic_id){
                if (topic_id == 1) {
                    this.get_outdoor()
                }
            },
            get_outdoor(){
                axios
                .get('../api/get_articles_for_forum/outdoor/'+localStorage.getItem('lang'))
                .then(response => {
                    this.outdoors = response.data
                    alert(this.outdoors.length)
                })
                .catch(error =>{
                })
            },
            get_sectors(selected_outdoor){
                axios
                .get('../api/get_sectors_for_forum/'+selected_outdoor)
                .then(response => {
                    this.sectors = response.data
                })
                .catch(error =>{
                })
            },
            get_routes(selected_sector){
                axios
                .get('../api/get_routes_for_forum/'+selected_sector)
                .then(response => {
                    this.routes = response.data
                })
                .catch(error =>{
                })

            },
            get_mtps(selected_sector){
                axios
                .get('../api/get_mtps_for_forum/'+selected_sector)
                .then(response => {
                    this.mtps = response.data
                })
                .catch(error =>{
                })

            },
            add_post_image(){

            }
        }
    }
</script>

<style>
    .fade {
        opacity: 1;
        background: #a2a2a2;
    }
    body.modal-open{
        margin-right: 0;
    }
    .modal-dialog {
        margin-top: 15em;
    }
    @media screen and (min-width: 768px){
        .modal-dialog {
            width: 30%;
        }
    }
    .modal-footer {
        text-align: left;
    }

    .service_icon i {
        font-size: 200%;
    }
    .add_button{
        border-style: groove;
        border-color: rgb(66, 66, 66);
        border-radius: 3em;
    }
    .add_button :hover{
        border-radius: 3em;
        background-color: #e2e2e2;
        cursor: pointer;
    }
</style>