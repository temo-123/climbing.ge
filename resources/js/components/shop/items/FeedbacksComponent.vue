<template>
    <div class="container">

        <starsReiting ref="stars_reiting_modal"/>
        
        <div class="row ">
            <div class="col-md-6">
                <button @click="show_feadbacks_action" v-if="show_feadbacks" class="btn btn-default btn-send main-btn">Close feadbacks list</button>
                <button @click="show_feadbacks_action" v-if="!show_feadbacks" class="btn btn-default btn-send main-btn">Show feadbacks list</button>
            </div>
        </div>

        <div class="row" v-if="show_feadbacks">
            <div class="col-xs-6 col-md-6">
                <button   @click="show_feedback_modal()" class="btn btn-default btn-send main-btn">Create feadbacks</button>
            </div>
            <div class="col-xs-6 col-md-6">
                <button @click="get_feedbacks" class="btn btn-success pull-right" v-if="!is_refresh">Refresh ({{refresh_id}})</button>
                <span class="badge badge-primare mb-1 pull-right" v-if="is_refresh">Updating...</span>
            </div>
        </div>

        <div class="row" v-if="show_feadbacks">
            <div class="col-xs-12 col-md-8">
                <div class="wrap">
                    <ul>
                        <li v-for="feedback in this.feedbacks" :key="feedback.id">
                            <div class="row">
                                <hr>
                                <span v-if="user.length != 0">
                                    <div @click="show_complaint_modal(feedback.id)" v-if="!feedback.user || feedback.user.id != user.id" >
                                        <i class="fa fa-ellipsis-v complaint_icon" aria-hidden="true"></i>
                                    </div>
                                    <button @click="del_feedback(feedback.id)" v-else onclick="return confirm('Are you sure? Do you want to delete this feedback?')" class="btn btn-danger pull-right">
                                        del
                                    </button>
                                </span>

                                <div class="row">
                                    <h3 class="comentator_name"><strong>{{feedback.name}} {{feedback.surname}}</strong> </h3>
                                </div>

                                <div class="col-xs-2 col-md-2">
                                    <img :src="'/public/images/site_img/user_demo_img.gif'" />
                                </div>

                                <div class="col-xs-10 col-md-10">
                                    <div class="row">
                                        <p>{{feedback.text}}</p>
                                    </div>
                                </div>
                            </div>
                        </li>
                    </ul>
                </div>

            </div>
        </div>

        <feedbackComplaintModal ref="feedback_complaint_modal" @restart="get_feedbacks()"/>
        <feadbackModal ref="feadback_modal" @restart="get_feedbacks()"/>

        <hr />
    </div>
</template>

<script>
    //http://www.blog.tonyswierz.com/javascript/add-and-use-google-recaptcha-in-a-vuejs-laravel-project/
    import VueRecaptcha from 'vue-recaptcha'; //https://www.npmjs.com/package/vue-recaptcha

    import feedbackComplaintModal from './modals/feedbacks/FeedbackComplaintModal';
    import feadbackModal from './modals/feedbacks/FeedbackReviewModal';
    import starsReiting from '../../global_components/StarReitingShowComponent.vue'
    
    export default {
        components: {
            feedbackComplaintModal,
            VueRecaptcha,
            feadbackModal,
            starsReiting
        },
        props: [
            "product_id",
        ],
        data() {
            return {
                name: "",
                surname: "",
                email: "",
                text: "",

                show_feadbacks: false,

                feedbacks: [],
                is_refresh: false,
                refresh_id: 0,
                id: 0,

                user: [],

                complainter_email: '',

                id: this.product_id
            }
        },
        watch: {
            product_id: function(){
                this.id = this.product_id
                this.get_feedbacks()
            },
        },
        mounted() {
            this.get_feedbacks()
            this.get_user_info()

            this.$refs.stars_reiting_modal.colculate_stars(4.6, 6)
        },
        methods: {
            show_feadbacks_action(){
                this.show_feadbacks = !this.show_feadbacks
            },

            show_feedback_modal(){
                this.$refs.feadback_modal.show_modal(this.id)
            },

            show_complaint_modal(feedback_id){
                this.$refs.feedback_complaint_modal.show_modal(feedback_id)
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

            del_feedback(id) {
                axios
                .delete('/product_feedback/del_feedback/'+ id, {
                    id: id,
                })
                .then(Response => {
                    this.get_feedbacks()
                })
                .catch()
            },

            get_feedbacks: function(){
                this.is_refresh = true
                axios
                .get('/product_feedback/get_product_feedbacks/' + this.id)
                .then(response => {
                    this.feedbacks = response.data
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