<template>
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="form-groupe">
                    <button @click="refresh()" class="btn btn-success float-right" v-if="!is_admin_panel_refresh">Refresh notifications ({{admin_refresh_id}})</button>
                    <span class="badge badge-primare mb-1 float-right" v-if="is_admin_panel_refresh">Updating...</span>
                </div>
            </div>
        <!-- </div>

        <div class="row"> -->
            <div class="col-md-12">
                <span v-if="
                            $can('show', 'comments') ||
                            $can('del_comment', 'comments') 
                        " >
                    <div class="alert alert-warning" role="alert" v-for="com_complaint in comment_complaints" :key="com_complaint.id" >
                        <strong>Warning!</strong> One <i class="fa fa-book" aria-hidden="true"></i> articl comment are a complainted. Please check it and make decision!

                        <div class="row">
                            <div class="col-md-6">
                                <button class="btn btn-primary " @click="show_desidion_model(com_complaint.comment_id, com_complaint.id)">Check comment</button>.
                            </div>
                        </div>
                    </div>
                </span>
                <span v-if="
                            $can('show', 'comments') ||
                            $can('del_comment', 'comments') 
                        " >
                    <div class="alert alert-warning" role="alert" v-for="feed_complaint in feedback_complaints" :key="feed_complaint.id" >
                        <strong>Warning!</strong> One <i class="fa fa-shopping-bag" aria-hidden="true"></i> product feedback are a complainted. Please check it and make decision!

                        <div class="row">
                            <div class="col-md-6">
                                <button class="btn btn-primary " @click="show_desidion_model(feed_complaint.comment_id, feed_complaint.id)">Check comment</button>.
                            </div>
                        </div>
                    </div>
                </span>
                
                <span v-if="$can('edit', 'site_data')">
                    <div class="alert alert-danger" role="alert" v-if="
                            !this.$siteData.data.data['text'] ||
                            !this.$siteData.data.data['text_ru'] ||
                            !this.$siteData.data.data['text_ka'] ||
                            !this.$siteData.data.data['short_description_ru'] ||
                            !this.$siteData.data.data['short_description_ka'] ||
                            !this.$siteData.data.data['short_description']
                        ">
                        <strong>Danger!</strong> 
                        Web-site information is not fool. check page "
                        <router-link :to="{name: 'siteInfo'}" exact> 
                            About us
                        </router-link>
                        ", and add missing information.
                    </div>
                </span>
                            
            </div>
        </div>

        <stack-modal
                :show="is_desidion_model"
                title="Show coment"
                @close="clouse_model()"
                :saveButton="{ visible: true, title: 'Save', btnClass: { 'btn btn-primary': true } }"
                :cancelButton="{ visible: false, title: 'Close', btnClass: { 'btn btn-danger': true } }"
            >
            <pre class="language-vue">
                <div class="row justify-content-center" v-if="decision_loader">
                    <div class="col-md-4">
                        <img :src="'../public/images/site_img/loading.gif'" alt="loading">
                    </div>
                </div>

                <span v-if="!decision_loader">
                    <h1>Show Comment</h1>

                    Comentator - {{ actyve_comment.name }} {{ actyve_comment.suenmae }}
                    Email - {{ actyve_comment.email }}
                    Data to comment - {{ actyve_comment.created_at }}

                    {{ actyve_comment.text }}

                    <h1>Comment complaint</h1>
                    Email - {{ actyve_comment.email }}
                    Complaint status - {{ actyve_comment.complaint }}

                    <h1>Make decision</h1>
                    
                    <form v-on:submit.prevent="make_decision" id="make_decision" class="form">
                        <select class="form-control" v-model="comment_decision" name="comment delete cause" > 
                            <option value="select_decision" disabled>Select decision</option>
                            <option value="approve_request">Approve the request</option>
                            <option value="reject_request">Reject the request</option>
                        </select> 
                        <div class="alert alert-danger" role="alert" v-if="is_comment_decision_selected">Please select decision!!!</div>
                    </form>
                </span>
            </pre>
            <div slot="modal-footer">
                <div class="modal-footer" >
                    <button
                        type="submit"
                        :class="{'btn btn-primary': true}"
                        form="make_decision"
                    >
                    Make a decision
                    </button>
                </div>
            </div>
        </stack-modal>
    </div>
</template>

<script>
    import { SlickList, SlickItem } from 'vue-slicksort'; //https://github.com/Jexordexan/vue-slicksort
    import StackModal from '@innologica/vue-stackable-modal'  //https://innologica.github.io/vue-stackable-modal/#sample-css
    export default {
        components: {
            StackModal,
            SlickItem,
            SlickList,
        },
        data(){
            return{
                user: [],
                
                complaint_loader: false,

                comment_complaints: [],
                feedback_complaints: [],

                actyve_comment: [],
                comment_decision: 'select_decision',
                selected_comment_complaint: 'This is my comment',
                is_comment_decision_selected: false,

                is_desidion_model: false,
                action_comment_id: 0,
                is_admin_panel_refresh: false,
                admin_refresh_id: 0,
                decision_loader: false,
                // user_queries: [],
                complainter_email: '',

                is_coment_model: false,

                is_user_comment_complaint_model: false,

                is_email_sending_loader: false,

                complaint_comment_id: 0,
                complaint_query_id: 0,
            }
        },
        mounted(){
            this.refresh()
        },
        methods: {
            show_desidion_model(comment_id, complaint_id){
                this.is_desidion_model = true

                this.get_action_comment(comment_id)

                this.action_comment_id = comment_id
                this.complaint_id = complaint_id
            },

            clouse_model(){
                this.comment_decision = 'select_decision',
                this.is_desidion_model = false
            },

            get_article_comments_complaints(){
                axios
                .get('/guide_comment/get_comments_complaints')
                .then(response => {
                    this.comment_complaints = response.data
                })
            },

            get_product_feedback_complaints(){
                axios
                .get('/product_feedback/get_feedbacks_complaints')
                .then(response => {
                    this.feedback_complaints = response.data
                })
            },

            refresh(){
                this.admin_refresh_id++

                this.get_article_comments_complaints()
                this.get_product_feedback_complaints()
            },

            make_decision(){
                if(this.comment_decision == 'select_decision'){
                    this.is_comment_decision_selected = true
                }
                else{
                    this.decision_loader = true
                    axios
                    .post('/guide_comment/make_decision',{
                        decision: this.comment_decision,
                        comment_id: this.action_comment_id,
                        complaint_id: this.complaint_id,
                    })
                    .then(response => {
                        this.is_desidion_model = false
                        this.refresh()
                    })
                    .finally(() => this.decision_loader = false);
                }
            },
            get_action_comment(comment_id){
                this.actyve_comment = []
                axios
                .get("/guide_comment/get_actyve_comment/"+comment_id)
                .then(response => {
                    this.actyve_comment = response.data
                })
                .catch(
                    error => console.log(error)
                );
            },       
        }
    }
</script>

<style>

</style>