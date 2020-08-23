<template>
    <div class="container">
        <div class="row">
            <div class="col-md-8">
                <div class="col-md-12">
                    <form @submit.prevent="add_comment" id="js_form" class="contact-form" method="POST" enctype="multipart/form-data">
                        <div class="container">
                            <div class="row">
                                <div class='col-md-12'>
                                    
                                    <div class="row">
                                        <div class="col-md-12">
                                            <h2 id='comments'>Comments</h2>
                                            <!-- <p>Incorrect comments or comments containing obscene language will be deleted.</p> -->
                                        </div>
                                    </div>
                                    
                                    <div style='display: none;' v-if="this.is_login == true">
                                        <input type="name" :value="name" name="name" autocomplete="off" id="name" placeholder="Name">
                                        <input type="surname" :value="surname" name="surname" autocomplete="off" id="surname" placeholder="Surname">
                                        <input type="email" :value="email" name="email" autocomplete="off" id="email" placeholder="E-mail">
                                    </div>

                                    <div v-else>
                                    <!-- <div> -->
                                        <div class="row" >
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <input type="text" name="name" v-model="name" class="form-control textarea" placeholder="Name"><br>
                                                </div>
                                            </div>
                                            <div class="col-md-8">
                                                <div class="form-group">
                                                    <input type="text" name="surname" v-model="surname" class="form-control textarea" placeholder="Surname"><br>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <input type="email" name="email" v-model="email" class="form-control textarea" placeholder="E_mail"><br>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <textarea rows="6" name="text" v-model="text" id="text" maxlength="500" placeholder="Your comment (Write comments only in English, no more than 500 characters!)" class="form-control textarea"></textarea>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <div class="form-group form_left">
                                                    <div class="g-recaptcha" data-sitekey="6LfV980UAAAAAFSMmbkzVw1J_-Q2cDroTJoBD9k1"></div>
                                                </div>
                                            </div>
                                        </div>
                                            <div class="col-md-6">
                                        <div class="form-group">
                                                <button type="submit" class="btn main-btn pull-right">Send Comment</button>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class='row'>
                                        <div class="col-md-12">
                                        </div>
                                    </div>
                                
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-8">
                <comment-component :article_id_for_filtr=this.article_id></comment-component>
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
            "article_id"
        ],
        data() {
            return {
                name: "",
                surname: "",
                email: "",
                text: "",
            }
        },
        mounted() {
            console.log(this.is_login)
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
                    alert("Tenk you for your coment"),
                    this.comment.name = '',
                    update,
                    console.log(response)
                })
                .catch(error => console.log(error))
            }
        }
    }
</script>
