<template>
    <stack-modal
            :show="is_member_editing_modal"
            :title="$t('admin.users.manage_team_member_status_title')"
            @close="close_modal()"
            :saveButton="{ visible: true, title: $t('admin.users.save_changes_btn'), btnClass: { 'btn btn-primary': true } }"
            :cancelButton="{ visible: false, title: $t('admin.comments.cancel_btn'), btnClass: { 'btn btn-secondary': true } }"
        >
        <div>
            <span v-show="is_loading">
                <div class="col-md-4">
                    <img :src="'/images/site_img/loading.gif'" alt="loading">
                </div>
            </span>
            <form v-show="!is_loading">
                <div class="form-group">
                    <input type="checkbox" id="is_team_member" name="is_team_member" v-model="data.is_team_member">
                    <label for="is_team_member">{{ $t('admin.users.is_team_member_label') }}</label>
                    <small class="form-text text-muted">{{ $t('admin.users.is_team_member_hint') }}</small>
                </div>

                <div v-if="data.is_team_member" class="form-group">
                    <label for="member_status">{{ $t('admin.users.member_status_label') }}</label>
                    <textarea v-model="data.member_status" class="form-control" :placeholder="$t('admin.users.member_status_placeholder')" id="member_status" rows="3"></textarea>
                    <small class="form-text text-muted">{{ $t('admin.users.member_status_hint') }}</small>
                </div>
            </form>
        </div>
        <div slot="modal-footer">
            <div class="modal-footer">
                <button
                    type="button"
                    :class="'btn btn-primary'"
                    @click="edit_team_member()"
                >
                {{ $t('admin.users.save_and_close_btn') }}
                </button>
                <button
                    v-show="!is_loading"
                    type="button"
                    :class="'btn btn-success'"
                    @click="edit_team_member(true)"
                >
                {{ $t('admin.users.save_and_return_permissions_btn') }}
                </button>
                <button
                    v-show="!is_loading"
                    type="button"
                    :class="'btn btn-secondary'"
                    @click="go_back()"
                >
                {{ $t('admin.users.back_to_permissions_btn') }}
                </button>
            </div>
        </div>
    </stack-modal>
</template>

<script>
// import StackModal from '@innologica/vue-stackable-modal'  // Global now
import { get } from 'jquery'
export default {
    components: {
        // StackModal,
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
            .get("/get_user/get_team/get_member_status/" + this.user_id)
            .then(response => {
                this.is_member_editing_modal = true

                this.data = response.data;
            })
            .catch(error => console.log('Error fetching team member status:', error))
            .finally(() => this.is_loading = false)
        },
        edit_team_member(is_back = false){
            this.is_loading = true
            axios
                .post("/set_user/team/edit_member_status/" + this.user_id, {
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
                .catch(error => console.log('Error saving team member status:', error))
                .finally(() => this.is_loading = false)
        },
    }
}
</script>