<template>
    <stack-modal
            :show="is_member_editing_modal"
            title="Edit Team Member Status"
            @close="close_modal()"
            :saveButton="{ visible: true, title: 'Save', btnClass: { 'btn btn-primary': true } }"
            :cancelButton="{ visible: false, title: 'Close', btnClass: { 'btn btn-danger': true } }"
        >
        <pre class="language-vue">
            <span v-show="is_loading">
                <div class="col-md-4">
                    <img :src="'../../../../../../public/images/site_img/loading.gif'" alt="loading">
                </div>
            </span>
            <form v-show="!is_loading">
                <div class="form-group mt-3">
                    <input type="checkbox" id="is_team_member" name="is_team_member" v-model="data.is_team_member">
                    <label for="is_team_member">Is this user your team member?</label>
                </div>

                <textarea v-if="data.is_team_member" v-model="data.member_status" class="form-control" placeholder="Enter member status" id="member_status" rows="2"></textarea>
            </form>
        </pre>
        <div slot="modal-footer">
            <div class="modal-footer">
                <button
                    type="button"
                    :class="'btn btn-primary'"
                    @click="edit_team_member()"
                >
                Save And Close
                </button>
                <button
                    v-show="!is_loading"
                    type="button"
                    :class="'btn btn-success'"
                    @click="edit_team_member(true)"
                >
                Save And Go Back
                </button>
                <button
                    v-show="!is_loading"
                    type="button"
                    :class="'btn btn-danger'"
                    @click="go_back()"
                >
                Go Back
                </button>
            </div>
        </div>
    </stack-modal>
</template>

<script>
import StackModal from '@innologica/vue-stackable-modal'
import { get } from 'jquery'
export default {
    components: {
        StackModal,
    },
    data(){
        return {
            is_member_editing_modal: false,
            is_loading: false,
            user_id: '',
            data: {
                is_team_member: false,
                member_status: '',
            },
        }
    },
    methods: {
        show_modal(id){
            this.user_id = id

            this.get_team_member_status()
        },
        close_modal(){
            this.is_member_editing_modal = false
            this.member_status = 'member'
            this.user_id = ''
        },
        go_back(){
            this.$emit('go_back', this.user_id);
            this.close_modal()
        },
        get_team_member_status(){
            this.is_loading = true
            axios
            .get("/user/team/get_member_status/" + this.user_id)
            .then(response => {
                this.is_member_editing_modal = true

                this.data = response.data;
            })
            .catch(error => console.log(error))
            .finally(() => this.is_loading = false)
        },
        edit_team_member(is_back = false){
            this.is_loading = true
            axios
                .post("/user/team/edit_member_status/" + this.user_id, {
                    is_team_member: this.data.is_team_member,
                    member_status: this.data.member_status,
                })
                .then(response => {
                    this.$emit('update');

                    if(is_back){
                        this.go_back();
                    }
                    else if(!is_back){
                        this.close_modal()
                    }
                })
                .catch(error => console.log(error))
                .finally(() => this.is_loading = false)
        },
    }
}
</script>