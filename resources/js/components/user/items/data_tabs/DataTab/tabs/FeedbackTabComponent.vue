<template>
    <tr :class="danger_color">
        <td :style='"text-align: center;"'>
            <input type="checkbox">
        </td>
        <td>|</td>

        <td>{{table_info.id}}</td>
        <td>|</td>
        
        <th @click="quick_wiev_action(table_info.id)" :style="'cursor: zoom-in'">{{table_info.name}} {{ table_info.surname }} (show feedback)</th>
        <th>|</th>

        <th>{{table_info.email}}</th>
        <th>|</th>

        <th>{{table_info.stars}}</th>
        <th>|</th>

        <td>
            <button type="submit" class="btn btn-danger" @click="del_my_feedback(table_info.id)">Delete</button>
        </td>

        <td v-if="table_name == 'Product feedbacks'">|</td>
        <td v-if="table_name == 'Product feedbacks'">
            <button type="submit" class="btn btn-warning" @click="user_coment_del_modal(table_info.id)">Hide</button>
        </td>

        <stack-modal
                :show="is_coment_model"
                title="Show coment"
                @close="is_coment_model=false"
                :saveButton="{ visible: true, title: 'Save', btnClass: { 'btn btn-primary': true } }"
                :cancelButton="{ visible: false, title: 'Close', btnClass: { 'btn btn-danger': true } }"
            >
            <pre class="language-vue">
                <h1>Show feedback</h1>

                <span v-if="feedbacks_tab_name == 'feedbacks'">
                    Comentator - {{ quick_feedback.name }} {{ quick_feedback.suenmae }}
                    Email - {{ quick_feedback.email }}
                    Data to feedback - {{ quick_feedback.created_at }}
                </span>

                {{ quick_feedback.text }}


                <span v-if="table_info.feedback_deleted_reason != null">
                    <div class="alert alert-danger" role="alert">
                        This feedback was deleted!!!
                        <strong>Reason for deletion </strong>
                        We have calculated that your feedback is - {{ table_info.feedback_deleted_reason }}
                    </div>
                </span>
            </pre>
            <div slot="modal-footer">
                <div class="modal-footer" v-if="feedbacks_tab_name == 'My feedbacks' && table_info.feedback_deleted_reason == null">
                    <button
                        type="button"
                        :class="{'btn btn-primary': true}"
                        @click="edit_coment(editing_image.id)"
                    >
                    Save
                    </button>
                </div>
            </div>
        </stack-modal>

        <stack-modal
                :show="is_user_feedback_delite_model"
                title="Please select a reason for deleting the feedback"
                @close="is_user_feedback_delite_model=false"
                :saveButton="{ visible: true, title: 'Save', btnClass: { 'btn btn-primary': true } }"
                :cancelButton="{ visible: false, title: 'Close', btnClass: { 'btn btn-danger': true } }"
            >
            <pre class="language-vue">
                <span v-if="feedbacks_tab_name == 'feedbacks'">
                    Comentator - {{ quick_feedback.name }} {{ quick_feedback.suenmae }}
                    Email - {{ quick_feedback.email }}
                    Data to feedback - {{ quick_feedback.created_at }}
                </span>

                {{ quick_feedback.text }}

                <p :style="'font-size: 200%;'">Please select a reason for deleting the feedback!!!</p>
                
                <select class="form-control" v-model="feedback_delete_cause" name="feedback delete cause" > 
                    <option value="Hostile remarks">Hostile remarks</option>
                    <option value="Does not match the theme of the site">Does not match the theme of the site</option>
                    <option value="Spam">Spam</option>
                    <option value="Sexual content">Sexual content</option>
                    <option value="Expression of anger">Expression of anger</option>
                    <option value="Conflict with other members of the site">Conflict with other members of the site</option>
                    <option value="The language of the feedbacks does not match the requirements of the site">The language of the feedbacks does not match the requirements of the site</option>
                </select> 
            </pre>
            <div slot="modal-footer">
                <div class="modal-footer">
                    <button
                        type="button"
                        :class="{'btn btn-primary': true}"
                        @click="del_user_feedback(feedback_delete_cause, quick_feedback.id)"
                    >
                    Delete this feedback
                    </button>
                </div>
            </div>
        </stack-modal>
    </tr>
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
        props: [
            'table_info',
            'feedbacks_tab_name'
        ],
        data(){
            return {
                is_coment_model: false,
                is_user_feedback_delite_model: false,
                feedback_delete_cause: '',
                quick_feedback: [],

                user_feedback_id: 0,

                danger_color: ''
            }
        },
        mounted(){
            // console.log(table_info)
            if(this.table_info.deleted_reason != null){
                this.danger_color = 'row_deanger'
            }
            else{
                this.danger_color = ''
            }
        },
        methods: {
            del_my_feedback(id){
                if(confirm('Are you sure, you want delite it?')){
                    axios
                    .post('../../api/del_my_feedback/'+id, {
                        _method: 'DELETE'
                    })
                    .then(Response => {
                        this.$emit('restart')
                    })
                    .catch(error => console.log(error))
                }
            },
            del_user_feedback(cause, id){
                if(confirm('Are you sure, you want delite this feedback from page content?')){
                    axios
                    .post('../../api/del_user_feedback/'+id, {
                        cause,
                        _method: 'post'
                    })
                    .then(Response => {
                        this.is_user_feedback_delite_model = false
                        this.$emit('restart')
                    })
                    .catch(error => console.log(error))
                }
            },
            quick_wiev_action(feedback_id){
                this.is_coment_model = true
                this.get_action_feedback(feedback_id)
            },
            user_coment_del_modal(feedback_id){
                this.is_user_feedback_delite_model = true
                this.get_action_feedback(feedback_id)
            },

            get_action_feedback(feedback_id){
                this.quick_feedback = []

                axios
                .get("../api/get_quick_feedback/"+feedback_id)
                .then(response => {
                    this.quick_feedback = response.data
                })
                .catch(
                    error => console.log(error)
                );

            },
            edit_coment(feedback_id){
                alert('Edit feedback ( article ID - '+feedback_id+')')
            }
        }
    }
</script>

<style>
    .row_deanger td{
        background-color: #df8d8d;
    }
    .row_worning td {
        background-color: #dfad8d;
    }
</style>