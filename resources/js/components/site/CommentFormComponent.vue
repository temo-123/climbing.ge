<template>
    <div class="container">
        <div class="row">
            <div class="col-md-8">
                <form @submit.prevent="add_comment" id="js_form" class="contact-form" method="POST" enctype="multipart/form-data">
                                    
                    <div class="row">
                        <div class="col-md-12">
                            <h2 id='comments'>Comments</h2>
                        </div>
                    </div>
                    
                    <div style='display: none;' v-if="this.is_login == true">
                        <input type="name" :value="name" name="name" autocomplete="off" id="name" placeholder="Name">
                        <input type="surname" :value="surname" name="surname" autocomplete="off" id="surname" placeholder="Surname">
                        <input type="email" :value="email" name="email" autocomplete="off" id="email" placeholder="E-mail">
                    </div>

                    <div v-else>
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

                    <div class="row">
                        <div class="col-md-7">
                            <div class="form-group">
                                <div class="form-group form_left">
                                    <div class="g-recaptcha" data-sitekey="6LfV980UAAAAAFSMmbkzVw1J_-Q2cDroTJoBD9k1"></div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-groupe">
                                <button @click="update" class="btn main-btn pull-right" v-if="!is_refresh">Refresh ({{id}})</button>
                                <span class="badge badge-primare mb-1" v-if="is_refresh">Updating...</span>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <button class="btn main-btn pull-right">Send</button>
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
                                <div class="col-md-2"><img :src="'/public/images/user_img/user_demo_img/user_demo_img.gif'" /></div>
                                <div class="col-md-10">
                                    <h2><strong>{{comment.name}} {{comment.surname}}</strong> [ {{comment.email}} ]</h2>
                                    <div>
                                        <p>{{comment.text}}</p>
                                    </div>
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
    export default {
        props: [
            "article_id",
            "user_name",
            "user_surname",
            "user_email",
            "is_login",
        ],
        data() {
            return {
                name: "",
                surname: "",
                email: "",
                text: "",

                test:"test",

                comments: [],
                is_refresh: false,
                id: 0,

                errors: []
            }
        },
        mounted() {
            this.update()
        },
        methods: {
            add_comment() {
                axios
                .post('/add_comment', {
                    name: this.name,
                    surname: this.surname,
                    email: this.email,
                    text: this.text,
                    article_id: this.article_id
                })
                .then(Response => {
                    alert("Tenk you for your coment " + this.name + ".")
                    // this.update()
                    // console.log(response)
                })
                .catch(error =>{
                    if (error.response.status == 422) {
                        this.errors = error.response.data.errors
                    }
                })
            },

            update: function(){
                this.is_refresh = true
                axios
                .get('/get_comments/' + this.article_id)
                .then(response => {
                    article_id: this.article_id,
                    console.log("updating")
                    console.log(response.data)
                    this.comments = response.data
                    this.is_refresh = false
                    this.id++ 
                })
                .then(Response => {
                    // console.log(response)
                })
                .catch(
                    // error => console.log(error)
                );
                    
            },
        }
    }
</script>
