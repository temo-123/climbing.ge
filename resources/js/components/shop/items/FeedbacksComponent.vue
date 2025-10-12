<template>
    <div class="container">

        <div class="modal-section">
            <div class="row">
                <div class="col-xs-12 col-md-12 text-center">
                    <div class="wrap">
                        <h2>Feedbacks</h2>
                        <span v-if="reviews_count_prop > 0">Average rating: {{reviews_stars_prop}} / 5 ({{reviews_count_prop}} reviews)</span>
                        <span v-else>No feedbacks yet</span>
                    </div>
                </div>
            </div>
            
            <starsReiting
                :reviews_count_prop = reviews_count_prop
                :reviews_stars_prop = reviews_stars_prop
            />

            <div class="row">
                <div class="col-md-6">
                    <button @click="show_feadbacks_action" v-if="show_feadbacks" class="btn btn-default btn-send main-btn">Close feedbacks list</button>
                    <button @click="show_feadbacks_action" v-if="!show_feadbacks" class="btn btn-default btn-send main-btn">Show feedbacks list</button>
                </div>
            </div>
        </div>

        <div class="modal-section" v-if="show_feadbacks">
            <div class="row">
                <div class="col-xs-6 col-md-6">
                    <button @click="show_feedback_modal()" class="btn btn-default btn-send main-btn">Create feedbacks</button>
                </div>
                <div class="col-xs-6 col-md-6">
                    <button @click="get_feedbacks" class="btn btn-success pull-right" v-if="!is_refresh">Refresh ({{refresh_id}})</button>
                    <span class="badge badge-primary mb-1 pull-right" v-if="is_refresh">Updating...</span>
                </div>
            </div>
        </div>

        <div class="modal-section" v-if="show_feadbacks">
            <div class="row">
                <div class="col-xs-12 col-md-8">
                    <div class="wrap">
                        <ul>
                            <li v-for="feedback in this.feedbacks" :key="feedback.feedback.id" class="feedback-item">
                                <div class="row">
                                    <span v-if="user.length != 0">
                                        <span @click="show_complaint_modal(feedback.feedback.id)" v-if="!feedback.user || feedback.user.id != user.id" >
                                            <i class="fa fa-ellipsis-v complaint_icon" aria-hidden="true"></i>
                                        </span>
                                        <button @click="del_feedback(feedback.feedback.id)" v-else onclick="return confirm('Are you sure? Do you want to delete this feedback?')" class="btn btn-danger pull-right">
                                            del
                                        </button>
                                    </span>

                                    <div class="col-xs-2 col-md-2">
                                        <img :src="feedback.user && feedback.user.image ? '/public/images/user_profil_img/' + feedback.user.image : '/public/images/site_img/demo_imgs/user_demo_img.gif'" @click="showUserModal(feedback)" />
                                    </div>

                                    <div class="col-xs-10 col-md-10">
                                        <div class="row">
                                            <h3 class="comentator_name"><strong>{{feedback.feedback.name}} {{feedback.feedback.surname}}</strong> </h3>

                                            <starsReiting
                                                :reviews_count_prop = 1
                                                :reviews_stars_prop = feedback.feedback.stars
                                                :rewiew_count_text_prop = false
                                            />
                                        </div>

                                        <div class="row">
                                            <p>{{feedback.feedback.text}}</p>
                                        </div>
                                    </div>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <feedbackComplaintModal ref="feedback_complaint_modal" @restart="get_feedbacks()"/>
        <feadbackModal ref="feadback_modal" @restart="get_feedbacks()"/>
        <UserModalComponent ref="user_modal" />

    </div>
</template>

<script>
    //http://www.blog.tonyswierz.com/javascript/add-and-use-google-recaptcha-in-a-vuejs-laravel-project/
    import VueRecaptcha from 'vue-recaptcha'; //https://www.npmjs.com/package/vue-recaptcha

    import feedbackComplaintModal from './modals/feedbacks/FeedbackComplaintModal';
    import feadbackModal from './modals/feedbacks/FeedbackModal';
    import starsReiting from '../../global_components/StarReitingShowComponent.vue'
    import UserModalComponent from '../../global_components/modals/UserModalComponent.vue'
    
    export default {
        components: {
            feedbackComplaintModal,
            VueRecaptcha,
            feadbackModal,
            starsReiting,
            UserModalComponent
        },
        props: {
            product_id_prop: {
                type: Number,
            },
            reviews_stars_prop: {
                type: Number,
                default: 0
            },
            reviews_count_prop: {
                type: Number,
                default: 0
            }
        },
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

                id: this.product_id_prop
            }
        },
        watch: {
            product_id_prop: function(){
                this.id = this.product_id_prop
                this.get_feedbacks()
            },
        },
        mounted() {
            this.get_feedbacks()
            this.get_user_info()

            // this.$refs.stars_reiting_modal.colculate_stars(4.6, 6)
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

            showUserModal(feedback){
                if (feedback.user) {
                    this.$refs.user_modal.show_modal(feedback.user.id);
                } else {
                    alert('This user is not registered on our website');
                }
            },

            colculate_feedack_stars(stars){
                this.$refs.feedack_stars.colculate_stars(stars, 1, false)
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
    .comentator_name{
        margin: 0px;
        /* margin-left: 18%; */
        float: left;
        color: #000;
    }
    .feedback-item {
        background-color: #f8f9fa;
        border: 1px solid #e9ecef;
        border-radius: 8px;
        padding: 1rem;
        margin-bottom: 1rem;
        position: relative;
        transition: box-shadow 0.3s ease;
    }
    .feedback-item:hover {
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }
    .feedback-item img {
        cursor: pointer;
        border-radius: 50%;
        width: 50px;
        height: 50px;
        object-fit: cover;
    }
    .complaint_icon {
        position: absolute;
        top: 10px;
        right: 10px;
        cursor: pointer;
    }
</style>
