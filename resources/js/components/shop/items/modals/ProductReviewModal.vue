<template>
    <stack-modal
            :show="is_show_modal"
            title="Route detals"
            @close="close_route_review_modal(route_id)"
            :modal-class="{ [ModalClass]: true }"
            :saveButton="{ visible: true }"
            :cancelButton="{
                title: 'Close',
                btnClass: { 'btn btn-primary': true },
            }"
        >
            <div class="model-body">
                <div class="container">
                    <div class="row">
                        <h2>{{ $t("guide.route.route_rewiev") }}</h2>
                         
                        <div class="row justify-content-center" v-show="is_loading">
                            <div class="col-md-4">
                                <img :src="'../../../../../../public/images/site_img/loading.gif'" alt="loading">
                            </div>
                        </div>

                        <form method="POST" id="route_review_form" v-on:submit.prevent="add_route_review" v-show="!is_loading">
                            Did you climb this route?
                            <input type="checkbox" v-model="data.climbed" name="scales" placeholder="Did you climb this route?" title="Did you climb this route?">

                            <div>
                                <span v-if="data.climbed">Insert climb data</span>
                                <input type="datetime-local" class="form-control" v-model="data.climbed_data" name="climbed_data" placeholder="Climbed data" title="Climbed data" v-if="data.climbed">
                            </div>

                            <div>
                                <span>How did you like this route?</span>
                                <span v-if="data.stars > 0"></span>
                                <div class="row">
                                    <div class="col-md-12">
                                        <button type="button" class="btnrating btn-lg star_review_button" @click="actyvate_star(1)" data-attr="1" id="rating-star-1" @mouseover="actyvate_temperary_actyve_star(1)" @mouseleave="disactyvate_temperary_actyve_star()">
                                            <i class="fa fa-star actyve_star" v-if="stars.st_1" aria-hidden="true"></i>
                                            <i class="fa fa-star" aria-hidden="true" v-else></i>
                                        </button>
                                        <button type="button" class="btnrating btn-lg star_review_button" @click="actyvate_star(2)" data-attr="2" id="rating-star-2" @mouseover="actyvate_temperary_actyve_star(2)" @mouseleave="disactyvate_temperary_actyve_star()">
                                            <i class="fa fa-star actyve_star" 
                                                v-if="stars.st_1 && stars.st_2" 
                                            aria-hidden="true"></i>
                                            <i class="fa fa-star" aria-hidden="true" v-else></i>
                                        </button>
                                        <button type="button" class="btnrating btn-lg star_review_button" @click="actyvate_star(3)" data-attr="3" id="rating-star-3" @mouseover="actyvate_temperary_actyve_star(3)" @mouseleave="disactyvate_temperary_actyve_star()">
                                            <i class="fa fa-star actyve_star" 
                                                v-if="stars.st_1 && stars.st_2 && stars.st_3" 
                                            aria-hidden="true"></i>
                                            <i class="fa fa-star" aria-hidden="true" v-else></i>
                                        </button>
                                        <button type="button" class="btnrating btn-lg star_review_button" @click="actyvate_star(4)" data-attr="4" id="rating-star-4" @mouseover="actyvate_temperary_actyve_star(4)" @mouseleave="disactyvate_temperary_actyve_star()">
                                            <i class="fa fa-star actyve_star" 
                                                v-if="stars.st_1 && stars.st_2 && stars.st_3 && stars.st_4" 
                                            aria-hidden="true"></i>
                                            <i class="fa fa-star" aria-hidden="true" v-else></i>
                                        </button>
                                        <button type="button" class="btnrating btn-lg star_review_button" @click="actyvate_star(5)" data-attr="5" id="rating-star-5" @mouseover="actyvate_temperary_actyve_star(5)" @mouseleave="disactyvate_temperary_actyve_star()">
                                            <i class="fa fa-star actyve_star" 
                                                v-if="stars.st_1 && stars.st_2 && stars.st_3 && stars.st_4 && stars.st_5" 
                                            aria-hidden="true"></i>
                                            <i class="fa fa-star" aria-hidden="true" v-else></i>
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <div>
                                <div class="col-md-8">
                                    <form @submit.prevent="add_comment" id="js_form" class="contact-form" method="POST" enctype="multipart/form-data">
                                                        
                                        <div class="row">
                                            <div class="col-md-12">
                                                <h2 id='comments'>{{ $t('guide.article.title.comments')}}</h2>
                                            </div>
                                        </div>

                                        <div v-if="user.length == 0">
                                            <div class="row" >
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <input type="text" name="name" v-model="name" class="form-control textarea" placeholder="Name"><br>
                                                        <div class="alert alert-danger" role="alert" v-if="errors.name">
                                                            Name is validation
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-8">
                                                    <div class="form-group">
                                                        <input type="text" name="surname" v-model="surname" class="form-control textarea" placeholder="Surname"><br>
                                                        <div class="alert alert-danger" role="alert" v-if="errors.surname">
                                                            Surname is validation
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <input type="email" name="email" v-model="email" class="form-control textarea" placeholder="E_mail"><br>
                                                        <div class="alert alert-danger" role="alert" v-if="errors.email">
                                                            Email is validation
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div v-else>
                                            <div class="alert alert-danger" role="alert" v-if="errors.name">
                                                Name is validation
                                            </div>
                                            <div class="alert alert-danger" role="alert" v-if="errors.surname">
                                                Surname is validation
                                            </div>
                                            <div class="alert alert-danger" role="alert" v-if="errors.email">
                                                Email is validation
                                            </div>
                                            <div class="alert alert-danger" role="alert" v-if="errors.email || errors.surname || errors.name">
                                                If you use automatically data pres reload page and try again!
                                            </div>
                                        </div>
                                        

                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <textarea rows="6" name="text" v-model="text" id="text" maxlength="500" placeholder="Your comment (Write comments only in English, no more than 500 characters!)" class="form-control textarea"></textarea>
                                                    <div class="alert alert-danger" role="alert" v-if="errors.text">
                                                        Comment text is validation
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- <div class="row">
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
                                            <div class="col-md-6">
                                                <div class="row">
                                                    <div class="col-xs-6 col-md-6" v-if="is_verify_isset == false">
                                                        <button class="btn btn-primary" disabled>Add comment</button>
                                                    </div>
                                                    <div class="col-xs-6 col-md-6" v-else>
                                                        <button class="btn btn-primary" >Add comment</button>
                                                    </div>
                                                    <div class="col-xs-6 col-md-6">
                                                        <button @click="get_comments" class="btn btn-success pull-right" v-if="!is_refresh">Refresh ({{refresh_id}})</button>
                                                        <span class="badge badge-primare mb-1 pull-right" v-if="is_refresh">Updating...</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div> -->
                                    </form>
                                </div>
                            </div>

                            <vue-recaptcha 
                                :sitekey="MIX_GOOGLE_CAPTCHA_SITE_KEY" 
                                :loadRecaptchaScript="true"
                                ref="recaptcha"
                                type="invisible"
                                @verify="onCaptchaVerified"
                                @expired="onCaptchaExpired"
                            >
                            </vue-recaptcha>
                        </form>

                    </div>
                </div>
            </div>
            <div slot="modal-footer">
                <div class="modal-footer">
                    <div class="form-group"  v-if="is_verify_isset == false">
                        <button type="submit" class="btn btn-success float-right" disabled>{{ $t("global.message.save") }}</button>
                    </div>
                    <div class="form-group"  v-else>
                        <button type="submit" form="route_review_form" class="btn btn-success float-right">{{ $t("global.message.save") }}</button>
                    </div>
                </div>
            </div>
        </stack-modal>
</template>

<script>
import StackModal from '@innologica/vue-stackable-modal'  //https://innologica.github.io/vue-stackable-modal/#sample-css
import VueRecaptcha from 'vue-recaptcha'; //https://www.npmjs.com/package/vue-recaptcha

export default {
    components: { 
        VueRecaptcha,
        StackModal,
    },
    props: [
        // "sector",
    ],
    data: function () {
        return {
            is_show_modal: false,

            route_id: 0,

            data: {
                stars: 0,
                text: '',
                climbed_data: '',
            },
            // hover: false,

            is_loading: false,

            stars: {
                st_1: false,
                st_2: false,
                st_3: false,
                st_4: false,
                st_5: false,
            },

            is_verify_isset: false,
            is_mail_sending: false,

            MIX_GOOGLE_CAPTCHA_SITE_KEY: process.env.MIX_GOOGLE_CAPTCHA_SITE_KEY,
        };
    },
    mounted() {
        //
    },
    methods: {
        show_route_review_modal(route_id){
            this.is_show_modal = true;
            this.route_id = route_id
        },
        close_route_review_modal(route_id){
            this.is_show_modal = false;
            this.back_to_route_madal(route_id)
            this.clear_data()
        },

        back_to_route_madal(route_id){
            this.$emit('back_route_modal', route_id)
        },

        clear_data(){
            this.data = {
                stars: 0,
                text: '',
                climbed_data: '',
            },
            
            this.is_verify_isset = false,

            this.stars = {
                st_1: false,
                st_2: false,
                st_3: false,
                st_4: false,
                st_5: false,
            }
        },

        actyvate_star(st_num){
            this.data.stars = st_num

            for (let i = 1; i < st_num + 1; i++) {
                let actyve_prefix = "st_" + i
                this.stars[actyve_prefix] = true
            }
            for (let j = st_num + 1; j < 5 + 1; j++) {
                let actyve_prefix = "st_" + j
                this.stars[actyve_prefix] = false
            }
        },

        actyvate_temperary_actyve_star(st_num){
            for (let i = 1; i < st_num + 1; i++) {
                let actyve_prefix = "st_" + i
                this.stars[actyve_prefix] = true
            }
        },

        disactyvate_temperary_actyve_star(){
            if(this.data.stars == 0){
                for (let j = 1; j < 5; j++) {
                    let actyve_prefix = "st_" + j
                    this.stars[actyve_prefix] = false
                }
            }
        },

        onCaptchaVerified() {
            this.is_verify_isset = true
        },
        onCaptchaExpired(){
            this.is_verify_isset = false
        },

        add_route_review() {
            this.is_loading = true
            axios
                .post("/route_review/create_route_review/" + this.route_id, this.data)
                .then((response) => {
                    this.close_route_review_modal(this.route_id)
                })
                .catch((error) => {
                    // 
                })
                .finally(() => this.is_loading = false);
        },
    }
}
</script>

<style>
.rating-header {
    margin-top: -10px;
    margin-bottom: 10px;
}
.star_review_button{
    border: none;
    background: none;
    cursor: pointer;
    margin: 0;
    padding: 0;
    width: 19%;

    color: #51acd7;
    display: inline-block;
    font-weight: 500;
    height: 36px;
    line-height: 33px;
    text-transform: uppercase;
    transition: background 0.2s ease 0s, opacity 0.2s ease 0s;
}
.star_review_button i{
    font-size: 45px;
}
.actyve_star {
    color: rgb(245, 115, 115) !important;
}

.star_review_button:hover{
    background-color: #fff;
    color: rgb(245, 115, 115) !important;
}
.star_review_button :active {     
    background-color:rgb(255, 255, 255); 
    color: rgb(245, 115, 115) !important;   
}

.st_1_actyve {     
    background-color:rgb(255, 255, 255); 
    color: rgb(245, 115, 115) !important;   
}
.st_2_actyve {     
    background-color:rgb(255, 255, 255); 
    color: rgb(245, 115, 115) !important;   
}
.st_3_actyve {     
    background-color:rgb(255, 255, 255); 
    color: rgb(245, 115, 115) !important;   
}
.st_4_actyve {     
    background-color:rgb(255, 255, 255); 
    color: rgb(245, 115, 115) !important;   
}
.st_5_actyve {     
    background-color:rgb(255, 255, 255); 
    color: rgb(245, 115, 115) !important;   
}
</style>