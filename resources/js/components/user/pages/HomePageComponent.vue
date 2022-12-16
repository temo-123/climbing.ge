<template>
    <div class="row">
        <div class="col-sm-3">
            <left-menu />
        </div>
        <div class="col-sm-9">
            <button type="button" class="btn btn-info btn-lg" data-toggle="modal" data-target="#myModal">Open Modal</button>
            <!-- Modal -->
            <div class="modal fade" id="myModal" role="dialog">
                <div class="modal-dialog">
                    <!-- Modal content-->
                    <div class="modal-content">
                        <div class="modal-header">
                            <button
                                type="button"
                                class="close"
                                data-dismiss="modal"
                            >
                                &times;
                            </button>
                            <h4 class="modal-title">Modal Header</h4>
                        </div>
                        <div class="modal-body">
                            <p>Some text in the modal.</p>
                        </div>
                        <div class="modal-footer">
                            <button
                                type="button"
                                class="btn btn-default"
                                data-dismiss="modal"
                            >
                                Close
                            </button>
                        </div>
                    </div>
                </div>
            </div>







            <div class="row justify-content-center">
                <div class="col-md-12">
                    <!-- <div class="card"> -->
                        <!-- <div class="card-header"> __('Dashboard') }}</div> -->

                        <!-- <div class="card-body"> -->
                            <div class="alert alert-success" role="alert">
                                <h1>Hi {{ this.user['name'] }}</h1>
                            </div>
                        <!-- </div> -->
                    <!-- </div> -->
                </div>
            </div>

            <div class="row justify-content-center">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">Sites index pages</div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-sm-12 col-md-4">
                                    <button class="btn btn_service_guid" @click="go_to_service('guid')">Climbing Guidbook</button>
                                </div>
                                <div class="col-sm-12 col-md-4 text-center">
                                    <button class="btn btn_service_shop" @click="go_to_service('shop')">Climbing Shop</button>
                                </div>
                                <div class="col-sm-12 col-md-4">
                                    <button class="btn btn_service_films float-right" @click="go_to_service('films')">Climbing Films</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

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
                            <strong>Danger!</strong> We sent you an email for verification, please check your email and confirm it.
                        </div>
                    </span>

                    <span v-if="!this.user['image']">
                        <div class="alert alert-warning" role="alert">
                            <strong>Warning!</strong> Add your profil image and piple faind you isier
                        </div>
                    </span>

                    <span v-for="complaint in complaints" :key="complaint.id">
                        <div class="alert alert-warning" role="alert">
                            <strong>Warning!</strong> one comment are a complainted. Please check it and make decision!

                            <div class="row">
                                <div class="col-md-6">
                                    <button class="btn btn-primary " @click="quick_wiev_action(complaint.comment_id, complaint.id)">Check comment</button>.
                                </div>
                            </div>
                        </div>
                    </span>

                    <!-- <span>
                        <div class="alert alert-warning" role="alert">
                            <strong>Warning!</strong> One or more comments have been reported. Check <router-link :to="{name: 'comentsList'}" >comments list</router-link> page!
                        </div>
                    </span> -->

                    <!-- <span>
                        <div class="alert alert-warning" role="alert">
                            <strong>Warning!</strong> One or more of your comments was delited by admin. Your comment will not be visible on the article page. You can see the reason for deletion on <router-link :to="{name: 'myComentsList'}" > comment list page </router-link>!
                        </div>
                    </span> -->

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

                </div>
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
                user: null,
                MIX_SITE_URL: process.env.MIX_SITE_URL,
                MIX_APP_SSH: process.env.MIX_APP_SSH,
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

                complaint_comment_id: 0,
                complaint_query_id: 0,
            }
        },
        mounted(){
            this.refresh()
        },
        methods: {
            refresh(){
                this.admin_refresh_id++

                this.get_user_data()
                this.get_comments_complaints()
            },
            get_user_data(){
                axios
                .get('/api/auth_user')
                .then((response)=>{
                    this.user = response.data
                    this.get_user_queries(this.user.id)
                })
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
                .get('./api/get_comments_complaints')
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
            go_to_service(service){
                if(service == "shop"){
                    window.open(this.MIX_APP_SSH + 'shop.' + this.MIX_SITE_URL + '')
                }
                else if(service == "guid"){
                    window.open(this.MIX_APP_SSH  + this.MIX_SITE_URL + '/')
                }
                else if(service == "films"){
                    window.open(this.MIX_APP_SSH  + 'films.' + this.MIX_SITE_URL + '/')
                }
            },
        }
    }
</script>

<style>
.btn_service_guid{
    background-color: #279fbb;
}
.btn_service_shop{
    background-color: #27bb7d;
}
.btn_service_films{
    background-color: #777777;
}
</style>