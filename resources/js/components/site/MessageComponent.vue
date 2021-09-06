<template>
    <div class="container">
    	    
        	<h2 class="block_title">{{ form_title }}</h2>
        	<!-- <p>You do not need to provide a phone number, but if you specify this, it will help us to meet you. Other parameters are required.</p> -->
        	
            <!-- @if (count($errors) > 0)
              <div class="alert alert-danger">
                <ul>
                  @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                  @endforeach
                </ul>
              </div>
            @endif -->
            
            <form method="POST"  @submit.prevent="send_message"  id="js_form">
                <div class='row'>
                    <div class="col-xs-6">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <input type="text" class="form-control" v-model="name" name="name"  id="name" placeholder="Name*">
                                    <div class="alert alert-danger" role="alert" v-if="errors.name">
                                        {{ errors.name[0] }}
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <input type="text" class="form-control" v-model="surname" name="surname"  id="surname" placeholder="Surname*">
                                    <div class="alert alert-danger" role="alert" v-if="errors.surname">
                                        {{ errors.surname[0] }}
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <input type="email" name="email" v-model="email" class="form-control textarea" placeholder="E_mail">
                                    <div class="alert alert-danger" role="alert" v-if="errors.email">
                                        {{ errors.email[0] }}
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <input type="text" class="form-control" v-model="num"  name="num"  id="num" placeholder="Phone number">
                                    <div class="alert alert-danger" role="alert" v-if="errors.num">
                                        {{ errors.num[0] }}
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group form_left">
                                    <input type="text" class="form-control" v-model="country"  name="country"  id="country" placeholder="Yout country">
                                    <div class="alert alert-danger" role="alert" v-if="errors.country">
                                        {{ errors.country[0] }}
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group form_left">
                                    <vue-recaptcha 
                                        :sitekey="'6LfDFkMcAAAAAFh9-1TUlmGPx83715KTD79j0iwF'" 
                                        :loadRecaptchaScript="true"
                                        @verify="verifyMethod"
                                    >
                                    </vue-recaptcha>
                                </div>
                            </div>
                        </div>

                    </div>
                    
                    <div class="col-xs-6">
                        <div class="form-group">
                            <textarea rows="15" name="msg" id="msg" v-model="msg" placeholder="Your message" class="form-control textarea"></textarea>
                            <div class="alert alert-danger" role="alert" v-if="errors.msg">
                                {{ errors.msg[0] }}
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <button type="submit" class="btn btn-default btn-send main-btn">Send</button>
                        </div>
                    </div>
                </div>
            </form>
    </div>
</template>

<script>
    import VueRecaptcha from 'vue-recaptcha'; //https://www.npmjs.com/package/vue-recaptcha
    export default {
        components: { 
            VueRecaptcha 
        },
        props: [
            'form_title'
        ],
        data() {
            return {
                name: "",
                surname: "",
                email: "",
                num: "",
                country: "",
                msg: "",

                is_verify_isset: false,

                errors: []
            }
        },
        mounted() {
            this.form_title
        },
        methods: {
            send_message() {
                axios
                .post('/send_message', {
                    name: this.name,
                    surname: this.surname,
                    email: this.email,
                    msg: this.msg,
                    num: this.num,
                    country: this.country,
                })
                .then(Response => {
                    alert("Tenk you for your message " + this.name + ".")
                })
                .catch(error =>{
                    if (error.response.status == 422) {
                        this.errors = error.response.data.errors
                    }
                })
            }
        }
    }
</script>
