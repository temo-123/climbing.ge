<template>
    <span>
        <div class="row">
            <div class="col-md-12">
                <div class="form-groupe">
                    <button @click="refresh()" class="btn btn-success float-right" v-if="!is_admin_panel_refresh">Refresh notifications ({{admin_refresh_id}})</button>
                    <span class="badge badge-primare mb-1 float-right" v-if="is_admin_panel_refresh">Updating...</span>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <span v-if="!this.user['name'] || !this.user['surname'] || !this.user['country'] || !this.user['city'] || !this.user['email']">
                    <div class="alert alert-danger" role="alert">
                        <strong>Danger!</strong> Your personal data is missing, this can lead to rolling problems. check page "option", and add missing information.
                    </div>
                </span>
                <span v-if="!this.user['email_verified_at']">
                    <div class="alert alert-danger" role="alert">
                        <span v-if="!is_email_sending_loader">
                            <strong>Danger!</strong> We sent you an email for verification, please check your email and confirm it. If you don't got this email you can demand new message. For new message -> <span class="cursor_pointer" @click="send_mail_confirm_notificatione()">Click here</span>.
                        </span>
                        <span v-else-if="is_email_sending_loader">
                            <div class="row justify-content-center" >
                                <div class="col-md-3">
                                    <img :src="'../../../../../../public/images/site_img/loading.gif'" alt="loading">
                                    <p class="text-center">Pless wait!</p>
                                </div>
                            </div>
                        </span>
                    </div>
                </span>

                <span v-if="!this.user['image']">
                    <div class="alert alert-warning" role="alert">
                        <strong>Warning!</strong> Add your profil image and piple faind you isier
                    </div>
                </span>

                <span   v-if="
                            $can('show', 'comments') ||
                            $can('del_comment', 'comments') 
                        " >
                    <div class="alert alert-warning" role="alert" v-for="complaint in complaints" :key="complaint.id" >
                        <strong>Warning!</strong> one comment are a complainted. Please check it and make decision!

                        <div class="row">
                            <div class="col-md-6">
                                <button class="btn btn-primary " @click="quick_wiev_action(complaint.comment_id, complaint.id)">Check comment</button>.
                            </div>
                        </div>
                    </div>
                </span>

                <span v-for="query in user_queries" :key="query.id">
                    <div class="alert alert-warning" role="alert">
                        <strong>Warning!</strong> We fined yor email in one of the article comment. Maybe it's your comment. Plees check it end click to ansver. Is it your comment?
                        
                        <div class="row">
                            <div class="col-md-6">
                                <button class="btn btn-success " @click="coment_model(query.comment_id, query.id)">Check comment!</button>.
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <button class="btn btn-primary" @click="query_response(true, query.id, query.comment_id)">Yes, it is my comment!</button>.
                            </div>
                            <div class="col-md-6">
                                <button class="btn btn-danger float-right" @click="query_response(false, query.id, query.comment_id)">No, it is not my comment!</button>.
                            </div>
                        </div>
                    </div>
                </span>
                
                <span v-if="$can('edit', 'site_data')">
                    <div class="alert alert-danger" role="alert" v-if="
                            !this.$siteData['text'] ||
                            !this.$siteData['text_ru'] ||
                            !this.$siteData['text_ka'] ||
                            !this.$siteData['short_description_ru'] ||
                            !this.$siteData['short_description_ka'] ||
                            !this.$siteData['short_description']
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
                :show="is_coment_complaint_model"
                title="Show coment"
                @close="is_coment_complaint_model=false"
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

                    Comentator - {{ quick_comment.name }} {{ quick_comment.suenmae }}
                    Email - {{ quick_comment.email }}
                    Data to comment - {{ quick_comment.created_at }}

                    {{ quick_comment.text }}

                    <h1>Comment complaint</h1>
                    Email - {{ quick_comment.email }}
                    Complaint status - {{ quick_comment.complaint }}

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
                            <option value="This is my comment">This is my comment</option>
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

        <stack-modal
                :show="is_coment_model"
                title="Show coment"
                @close="is_coment_model=false"
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

                    Comentator - {{ quick_comment.name }} {{ quick_comment.suenmae }}
                    Email - {{ quick_comment.email }}
                    Data to comment - {{ quick_comment.created_at }}

                    {{ quick_comment.text }}

                    <button class="btn btn-primary" @click="query_response(true, complaint_query_id, quick_comment.id)">Yes, it is my comment!</button>
                    <button class="btn btn-danger" @click="query_response(false, complaint_query_id, quick_comment.id)">No, it is not my comment!</button>

                </span>
            </pre>
            <div slot="modal-footer">
                <div class="modal-footer" >
                    <button
                        type="submit"
                        :class="{'btn btn-primary': true}"
                        @click="show_complaint_modal(quick_comment.id)"
                    >
                    Make file a complaint
                    </button>
                </div>
            </div>
        </stack-modal>
    </span>
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

                complaints: [],
                quick_comment: [],
                comment_decision: 'select_decision',
                selected_comment_complaint: 'This is my comment',
                is_comment_decision_selected: false,

                is_coment_complaint_model: false,
                action_comment_id: 0,
                is_admin_panel_refresh: false,
                admin_refresh_id: 0,
                decision_loader: false,
                user_queries: [],

                is_coment_model: false,

                is_user_comment_complaint_model: false,

                is_email_sending_loader: false,

                complaint_comment_id: 0,
                complaint_query_id: 0,
            }
        },
        mounted(){
            this.refresh()
            this.get_user_data()
        },
        methods: {
            get_user_data(){
                axios
                .get('/api/auth_user')
                .then((response)=>{
                    this.user = response.data
                    this.get_user_queries(this.user.id)
                })
            },
            send_mail_confirm_notificatione(){
                this.is_email_sending_loader = true
                axios
                .get('/api/email/resend')
                .then((response)=>{
                    alert('New verification message is sended. Please check your email for verification!')
                })
                .catch((error) => {
                    if(error.response.status === 429) {
                        alert('The page has expired or you clicked this button too many times! Please try again later or contact support!')
                    }
                    else{
                        alert('Something went wrong! Please try again later, if you encounter this problem again, contact support!')
                    }
                })
                .finally(() => this.is_email_sending_loader = false);
            },
            refresh(){
                this.admin_refresh_id++

                this.get_user_data()
                this.get_comments_complaints()
            },
            get_user_queries(user_id){
                axios
                .get('../api/get_user_queries/'+user_id)
                .then((response)=>{
                    this.user_queries = response.data
                })
            },
            make_complaint(){
                this.complaint_loader = true
                axios
                .post('../api/add_comment_complaint/',{
                    comment_id: this.complaint_comment_id,
                    comment_complaint: this.selected_comment_complaint,
                    email: this.complainter_email,
                })
                .then(response => {
                    this.is_user_comment_complaint_model = false
                    this.selected_comment_complaint = 'Hostile remarks'
                    this.refresh()
                })
                .catch()
                .finally(() => this.complaint_loader = false);
            },
            query_response(response, query_id, comment_id){
                axios
                .post('../api/query_response/', {
                    query_id, 
                    comment_id, 
                    response
                })
                .then((response)=>{
                    this.refresh

                    if(this.is_coment_model == true){
                        this.is_coment_model = false
                    }
                })
            },
            show_complaint_modal(comment_id){
                this.is_coment_model = false
                this.complaint_comment_id = comment_id
                this.is_user_comment_complaint_model = true
            },
            coment_model(comment_id, query_id){
                this.get_action_comment(comment_id)
                this.complaint_query_id = query_id
                this.is_coment_model = true
            },
            quick_wiev_action(comment_id, complaint_id){
                this.is_coment_complaint_model = true

                this.get_action_comment(comment_id)

                this.action_comment_id = comment_id
                this.complaint_id = complaint_id
            },
            get_action_comment(comment_id){
                this.quick_comment = []
                axios
                .get("../api/get_quick_comment/"+comment_id)
                .then(response => {
                    this.quick_comment = response.data
                })
                .catch(
                    error => console.log(error)
                );

            },
            get_comments_complaints(){
                axios
                .get('../api/get_comments_complaints')
                .then(response => {
                    this.complaints = response.data
                })
            },
            make_decision(){
                if(this.comment_decision == 'select_decision'){
                    this.is_comment_decision_selected = true
                }
                else{
                    this.decision_loader = true
                    axios
                    .post('./api/make_decision',{
                        decision: this.comment_decision,
                        comment_id: this.action_comment_id,
                        complaint_id: this.complaint_id,
                    })
                    .then(response => {
                        this.is_coment_complaint_model = false
                        this.refresh()
                    })
                    .finally(() => this.decision_loader = false);
                }
            },
        }
    }
</script>

<style>

</style>