<template>
    <div class="container">
    	    
        	<h2 class="block_title">Send message</h2>
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
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <input type="text" class="form-control" v-model="surname" name="surname"  id="surname" placeholder="Surname*">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <input type="email" name="email" v-model="email" class="form-control textarea" placeholder="E_mail">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <input type="text" class="form-control" v-model="num"  name="num"  id="num" placeholder="Phone number">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group form_left">
                                    <input type="text" class="form-control" v-model="country"  name="country"  id="country" placeholder="Yout country">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group form_left">
                                    <div class="g-recaptcha" data-sitekey="6LfV980UAAAAAFSMmbkzVw1J_-Q2cDroTJoBD9k1"></div>
                                </div>
                            </div>
                        </div>

                    </div>
                    
                    <div class="col-xs-6">
                        <div class="form-group">
                            <textarea rows="15" name="msg" id="msg" v-model="msg" placeholder="Your message" class="form-control textarea">
                            </textarea>
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
    export default {
        data() {
            return {
                name: "",
                surname: "",
                email: "",
                num: "",
                country: "",
                msg: "",
            }
        },
        props: [
            "article_id"
        ],
        mounted() {

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
            }
        }
    }
</script>
