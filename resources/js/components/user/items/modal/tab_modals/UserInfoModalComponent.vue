<template>
    <stack-modal
            :show="user_modal"
            :title="$t('admin.tour.user_info_title')"
            @close="user_modal=false"
            @save="save"
            :modal-class="{ 'modal-lg': true }"
            :saveButton="{ visible: $can('edit', 'role') || $can('edit', 'team'), title: $t('common.save'), btnClass: { 'btn btn-primary': true } }"
            :cancelButton="{ visible: false, title: $t('common.close'), btnClass: { 'btn btn-danger': true } }"
        >
        <div>
            <h1>{{ user.name }} {{ user.surname }}</h1>

            <h4>{{ user.country }}, {{ user.city }}</h4>
            <h4>{{ user.phone_number }}</h4>
            <h4>{{ user.email }}</h4>

            <h4 v-if="user.email_verified_at != null" style="color:green;">{{ $t('admin.tour.verification_completed') }}</h4>
            <h4 v-else style="color:red;">
                {{ $t('admin.tour.verification_failed') }}
                <button
                    v-if="$can('edit', 'user')"
                    class="btn btn-sm btn-outline-success ml-2"
                    :disabled="verifying"
                    @click="verify"
                >
                    {{ $t('admin.notification_analytics.verify_user_btn') }}
                </button>
            </h4>

            <img v-if="user.image != null" :src="'/public/images/user_profil_img/' + user.image" class="rounded mx-auto d-block"/>

            <div v-if="$can('edit', 'role')" class="form-group mt-3">
                <label>{{ $t('admin.users.select_user_role_title') }}</label>
                <select class="form-control" v-model="user_role" v-if="!role_loading">
                    <option value="no_role" disabled>{{ $t('admin.users.choose_role_placeholder') }}</option>
                    <option v-for="role in roles" :key="role.id" :value="role.id">{{ role.name }}</option>
                </select>
                <div v-else class="text-muted">{{ $t('admin.dashboards.loading_ellipsis_dots') }}</div>
            </div>

            <div v-if="$can('edit', 'team')" class="form-group mt-3">
                <hr>
                <input type="checkbox" id="user_info_is_team_member" v-model="user.is_team_member">
                <label for="user_info_is_team_member">{{ $t('admin.users.is_team_member_label') }}</label>

                <div v-if="user.is_team_member" class="form-group mt-2">
                    <label for="user_info_member_status">{{ $t('admin.users.member_status_label') }}</label>
                    <textarea v-model="user.member_status" class="form-control" :placeholder="$t('admin.users.member_status_placeholder')" id="user_info_member_status" rows="3"></textarea>
                </div>
            </div>
        </div>
    </stack-modal>
</template>

<script>
    export default {
        components: {
        },
        data(){
            return {
                user_modal: false,
                user: [],
                user_id: null,
                verifying: false,
                role_loading: false,
                roles: [],
                user_role: 'no_role',
            }
        },

        mounted() {

        },

        methods: {
            show_modal(id){
                this.user_id = id
                this.get_user(id)
                if (this.$can('edit', 'role')) {
                    this.get_roles()
                    this.get_current_role(id)
                }
            },

            get_user(user_id){
                axios
                .get("/get_options/get_selected_user_data/" + user_id)
                .then(response => {
                    this.user = response.data
                    this.user_modal = true
                })
                .catch(
                    error => console.log(error)
                );
            },

            get_roles(){
                this.role_loading = true
                axios
                .get("/set_role")
                .then(response => {
                    this.roles = response.data
                })
                .catch(error => console.log(error))
                .finally(() => this.role_loading = false)
            },

            get_current_role(user_id){
                axios
                .get("/set_role/get_user_permissions/" + user_id)
                .then(response => {
                    if (response.data.role && response.data.role.id) {
                        this.user_role = response.data.role.id
                    }
                })
                .catch(error => console.log(error))
            },

            save(){
                const requests = []

                if (this.$can('edit', 'role') && this.user_role !== 'no_role') {
                    requests.push(
                        axios.post("/set_role/edit_permissions_and_role/" + this.user_id, {
                            role: this.user_role,
                        })
                    )
                }

                if (this.$can('edit', 'team')) {
                    requests.push(
                        axios.post("/set_user/team/edit_member_status/" + this.user_id, {
                            is_team_member: this.user.is_team_member,
                            member_status: this.user.member_status,
                        })
                    )
                }

                Promise.all(requests)
                    .then(() => this.$emit('update'))
                    .catch(error => console.log(error))
            },

            verify(){
                this.verifying = true
                axios
                .post("/set_user/verify_user/" + this.user_id)
                .then(response => {
                    this.user.email_verified_at = response.data.email_verified_at
                })
                .catch(error => console.log(error))
                .finally(() => this.verifying = false)
            },
        }
    }
</script>

<style>

</style>
