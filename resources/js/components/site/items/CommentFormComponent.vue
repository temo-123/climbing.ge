<template>
    <div>
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
                    </div>
                </form>
            </div>
        </div>

        <div class="row mt-1">
            <div class="col-xs-12 col-md-8">
                <div class="wrap">
                    <ul>
                        <li v-for="comment in this.comments" :key="comment.id">
                            <div class="row">
                                <hr>
                                <span v-if="user.length != 0">
                                    <div @click="show_complaint_modal(comment.comment.id)" v-if="!comment.user || comment.user.id != user.id" >
                                        <i class="fa fa-ellipsis-v complaint_icon" aria-hidden="true"></i>
                                    </div>
                                    <button @click="del_comment(comment.comment.id)" v-else onclick="return confirm('Are you sure? Do you want to delete this comment?')" class="btn btn-danger pull-right">
                                        del
                                    </button>
                                </span>

                                <div class="row">
                                    <h3 class="comentator_name"><strong>{{comment.comment.name}} {{comment.comment.surname}}</strong> </h3>
                                </div>

                                <div class="col-xs-2 col-md-2">
                                    <img :src="'/public/images/site_img/user_demo_img.gif'" />
                                </div>

                                <div class="col-xs-10 col-md-10">
                                    <div class="row">
                                        <p>{{comment.comment.text}}</p>
                                    </div>
                                </div>
                            </div>
                        </li>
                    </ul>
                </div>

            </div>
        </div>

        <stack-modal
                :show="is_user_comment_complaint_model"
                title="Please select a reason for deleting the comment"
                @close="is_user_comment_complaint_model=false"
                :saveButton="{ visible: true, title: 'Save', btnClass: { 'btn btn-primary': true } }"
                :cancelButton="{ visible: false, title: 'Close', btnClass: { 'btn btn-danger': true } }"
            >
            <pre class="language-vue">
                <div class="row justify-content-center" v-if="complaint_loader">
                    <div class="col-md-4">
                        <img :src="'../public/images/site_img/loading.gif'" alt="loading">
                    </div>
                </div>

                <span v-if="!complaint_loader">
                    <h1>You can file a complaint for this comment</h1>
                    <p>Please select a reason for deleting the comment!!!</p>
                    
                    <form v-on:submit.prevent="make_complaint" id="make_complaint" class="form">
                        <input v-if="user.length == 0" type="email" name="complainter email" v-model="complainter_email" class="form-control textarea" placeholder="Your email">

                        <select class="form-control" v-model="selected_comment_complaint" name="comment delete cause" > 
                            <option value="Hostile remarks">Hostile remarks</option>
                            <option value="Does not match the theme of the site">Does not match the theme of the site</option>
                            <option value="Spam">Spam</option>
                            <option value="Sexual content">Sexual content</option>
                            <option value="Expression of anger">Expression of anger</option>
                            <option value="Conflict with other members of the site">Conflict with other members of the site</option>
                            <option value="The language of the comments does not match the requirements of the site">The language of the comments does not match the requirements of the site</option>
                        </select>
                    </form>
                </span>
            </pre>
            <div slot="modal-footer">
                <div class="modal-footer">
                    <button
                        type="submit"
                        :class="{'btn btn-primary': true}"
                        form="make_complaint"
                    >
                    Submit
                    </button> 
                </div>
            </div>
        </stack-modal>
    </div>
</template>

<script>
    import VueRecaptcha from 'vue-recaptcha'; //https://www.npmjs.com/package/vue-recaptcha
    //http://www.blog.tonyswierz.com/javascript/add-and-use-google-recaptcha-in-a-vuejs-laravel-project/
    import { SlickList, SlickItem } from 'vue-slicksort'; //https://github.com/Jexordexan/vue-slicksort
    import StackModal from '@innologica/vue-stackable-modal'  //https://innologica.github.io/vue-stackable-modal/#sample-css
    export default {
        components: {
            StackModal,
            SlickItem,
            SlickList,
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
                is_complaint_verify_isset: false,

                comments: [],
                is_refresh: false,
                refresh_id: 0,
                id: 0,
                loadRecaptchaScript: false,
                loadComplaintRecaptchaScript: false,

                errors: [],
                user: [],
                user_id: 0,

                complaint_comment_id: 0,
                selected_comment_complaint: 'Hostile remarks',
                is_user_comment_complaint_model: false,

                MIX_GOOGLE_CAPTCHA_SITE_KEY: process.env.MIX_GOOGLE_CAPTCHA_SITE_KEY,
                complainter_email: '',
                complaint_loader: false,

                id: this.article_id
            }
        },
        watch: {
            article_id: function(){
                this.id = this.article_id
                this.get_comments()
            },
        },
        mounted() {
            this.get_comments()
            this.get_user_info()
        },
        methods: {
            update(id){
                this.id = id
                this.get_comments();
            },

            onCaptchaVerified() {
                this.is_verify_isset = true
            },
            onCaptchaExpired(){
                this.is_verify_isset = false
            },

            onComplaintCaptchaVerified() {
                this.is_complaint_verify_isset = true
            },
            onComplaintCaptchaExpired(){
                this.is_complaint_verify_isset = false
            },

            get_user_info() {
                this.user = []
                    
                this.name = '',
                this.surname = '',
                this.email = ''
                
                axios
                .get('/auth_user/')
                .then(response => {
                    this.user = response.data,
                    
                    this.name = this.user.name,
                    this.surname = this.user.surname,
                    this.email = this.user.email

                    this.complainter_email = this.user.email
                })
                .catch()
            },

            show_complaint_modal(comment_id){
                // alert('test')
                this.complaint_comment_id = comment_id
                this.is_user_comment_complaint_model = true
            },

            make_complaint(){
                this.complaint_loader = true
                axios
                .post('/add_comment_complaint/',{
                    comment_id: this.complaint_comment_id,
                    comment_complaint: this.selected_comment_complaint,
                    email: this.complainter_email,
                })
                .then(response => {
                    this.is_user_comment_complaint_model = false
                    this.selected_comment_complaint = 'Hostile remarks'
                    alert(response.data);
                })
                .catch()
                .finally(() => this.complaint_loader = false);
            },

            add_comment() {
                axios
                .put('/comment/' + this.id, {
                    name: this.name,
                    is_verify_isset: this.is_verify_isset,
                    surname: this.surname,
                    email: this.email,
                    text: this.text,
                    article_id: this.id
                })
                .then(response => {
                    this.get_comments()
                    // alert(response.data['message'])
                    this.errors = []

                    // this.name = "",
                    // this.surname = "",
                    // this.email = "",
                    this.text = ""

                    // this.is_verify_isset = false
                })
                .catch(error => {
                    if (error.response.status == 422) {
                        this.errors = error.response.data.errors
                    }
                })
            },

            del_comment(id) {
                axios
                .delete('/comment/'+ id, {
                    id: id,
                })
                .then(Response => {
                    this.get_comments()
                })
                .catch()
            },

            get_comments: function(){
                this.is_refresh = true
                axios
                .get('/comment/' + this.id)
                .then(response => {
                    this.comments = response.data
                    this.is_refresh = false
                    this.refresh_id++ 
                })
                .catch(
                );
            },
        }
    }
</script>

<style scoped>
    .complaint_icon{
        float: right;
        cursor: pointer;
        font-size: 130%;
    }
    .comentator_name{
        margin: 0px;
        margin-left: 18%;
        float: left;
        color: #000;
    }
</style>