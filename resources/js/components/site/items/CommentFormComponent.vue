<template>
    <div>
        <div class="row">
            <div class="col-md-8">
                <form @submit.prevent="add_comment" id="js_form" class="contact-form" method="POST" enctype="multipart/form-data">
                                    
                    <div class="row">
                        <div class="col-md-12">
                            <h2 id='comments'>{{ $t('comments')}}</h2>
                        </div>
                    </div>
                    
                    <!-- <div style='display: none;' v-if="user.user_status == 'user'">
                        <input type="name" :value="name" name="name" autocomplete="off" id="name" placeholder="Name">
                        <input type="surname" :value="surname" name="surname" autocomplete="off" id="surname" placeholder="Surname">
                        <input type="email" :value="email" name="email" autocomplete="off" id="email" placeholder="E-mail">
                    </div>

                    <div v-if="user.user_status == 'gest'"> -->
                        <div class="row" >
                            <div class="col-md-4">
                                <div class="form-group">
                                    <input type="text" name="name" v-model="name" class="form-control textarea" placeholder="Name"><br>
                                    <div class="alert alert-danger" role="alert" v-if="errors.name">
                                        {{ errors.name[0] }}
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-8">
                                <div class="form-group">
                                    <input type="text" name="surname" v-model="surname" class="form-control textarea" placeholder="Surname"><br>
                                    <div class="alert alert-danger" role="alert" v-if="errors.surname">
                                        {{ errors.surname[0] }}
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <input type="email" name="email" v-model="email" class="form-control textarea" placeholder="E_mail"><br>
                                    <div class="alert alert-danger" role="alert" v-if="errors.email">
                                        {{ errors.email[0] }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    <!-- </div> -->

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

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <div class="form-group form_left">
                                    <!-- <div class="g-recaptcha" :data-sitekey="'6LfDFkMcAAAAAFh9-1TUlmGPx83715KTD79j0iwF'"></div> -->
                                    <!-- <vue-recaptcha 
                                        :sitekey="'6LfDFkMcAAAAAFh9-1TUlmGPx83715KTD79j0iwF'" 
                                        :loadRecaptchaScript="true"
                                        :size="'100%'"
                                        @verify="verifyMethod"
                                    >
                                    </vue-recaptcha> -->

                                    <vue-recaptcha
                                        :sitekey="MIX_GOOGLE_CAPTCHA_SITE_KEY"
                                        :loadRecaptchaScript="true"
                                        ref="recaptcha"
                                        type="invisible"
                                        @verify="onCaptchaVerified"
                                        @expired="onCaptchaExpired"
                                    >
                                    </vue-recaptcha>
                                    <!-- <div class="alert alert-danger" role="alert" v-if="errors.is_verify_isset">
                                        {{ errors.is_verify_isset[0] }}
                                    </div> -->
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="row">
                                <div class="col-md-6">
                                    <button @click="update" class="btn main-btn pull-right" v-if="!is_refresh">Refresh ({{id}})</button>
                                    <span class="badge badge-primare mb-1" v-if="is_refresh">Updating...</span>
                                </div>
                                <div class="col-md-6" v-if="is_verify_isset == false">
                                    <button class="btn main-btn pull-right" disabled>Add comment</button>
                                </div>
                                <div class="col-md-6" v-else>
                                    <button class="btn main-btn pull-right" >Add comment</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div class="row">
            <div class="col-md-8">
                <div class="wrap">
                    <ul>
                        <li v-for="comment in this.comments" :key="comment.id">
                            <div class="row">
                                <hr>
                                <div class="col-md-2">
                                    <img :src="'/public/images/user_img/user_demo_img/user_demo_img.gif'" />
                                </div>
                                <div class="col-md-10">
                                    <h4><strong>{{comment.name}} {{comment.surname}}</strong> [ {{comment.email}} ]</h4>
                                    <div class="row">
                                        <p>{{comment.text}}</p>
                                    </div>
                                    <button @click="del_comment(comment.id)" v-if="comment.user_id == user.user_id" onclick="return confirm('Are you sure? Do you want to delete this comment?')" class="btn btn-danger pull-right">
                                        del
                                    </button>
                                </div>
                            </div>
                        </li>
                    </ul>
                </div>

            </div>
        </div>
    </div>
</template>

<script>
    import VueRecaptcha from 'vue-recaptcha'; //https://www.npmjs.com/package/vue-recaptcha
    //http://www.blog.tonyswierz.com/javascript/add-and-use-google-recaptcha-in-a-vuejs-laravel-project/
    export default {
        components: { 
            VueRecaptcha 
        },
        props: [
            "article_id",
        ],
        data() {
            return {
                name: "",
                surname: "",
                email: "",
                text: "",

                is_verify_isset: false,

                comments: [],
                is_refresh: false,
                id: 0,
                loadRecaptchaScript: false,

                errors: [],
                user: [],

                MIX_GOOGLE_CAPTCHA_SITE_KEY: process.env.MIX_GOOGLE_CAPTCHA_SITE_KEY,
            }
        },
        mounted() {
            this.update()
            // this.get_user_info()
        },
        methods: {
            onCaptchaVerified() {
                this.is_verify_isset = true
            },
            onCaptchaExpired(){
                this.is_verify_isset = false
            },

            add_comment() {
                axios
                .put('../api/comment/' + this.article_id, {
                    name: this.name,
                    is_verify_isset: this.is_verify_isset,
                    surname: this.surname,
                    email: this.email,
                    text: this.text,
                    article_id: this.article_id
                })
                .then(response => {
                    this.update()
                    alert(response.data['message'])
                    this.errors = []

                    this.name = "",
                    this.surname = "",
                    this.email = "",
                    this.text = "",

                    this.is_verify_isset = false
                })
                .catch(function(error) {
                    console.log(error);
                })

    //  .catch(e => {
    //       alert(e);
    //     })
                // .catch(function (error) {
                //     alert(error);
                //     // when you throw error this will also fetch error.
                //     throw error;
                // });
                // .catch((error) => console.log(error))
                // .catch(error => {
                //     alert(error)
                //     console.log(error.errors)

                //     if (error.response.status == 422) {
                //         this.errors = error.response.data.errors
                //     }
                //     else{
                //         if (response.data.message) {
                //             alert(response.data.message)
                //         }
                //     }
                // })
                // .finally(() => this.loading = false)
            },

            // get_user_info() {
            //     axios
            //     .get('../api/comment/')
            //     .then(response => {
            //         this.user = response.data

            //         if(this.user.user_status == 'user'){
            //             this.name = this.user.user_name
            //             this.surname = this.user.surname
            //             this.email = this.user.email
            //         }
            //     })
            //     .catch()
            // },

            del_comment(id) {
                axios
                .delete('../api/comment/'+ id, {
                    id: id,
                })
                .then(Response => {
                    this.update()
                })
                .catch()
            },

            update: function(){
                this.is_refresh = true
                axios
                .get('../api/comment/' + this.article_id)
                .then(response => {
                    this.comments = response.data
                    this.is_refresh = false
                    this.id++ 
                })
                .catch(
                );
            },
        }
    }
</script>
