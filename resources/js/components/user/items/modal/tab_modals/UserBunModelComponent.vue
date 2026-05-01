<template>
    <StackModal
        v-model="is_ban_modal"
        :title="modalTitle"
        :saveButton="{ visible: false }"
        :cancelButton="{ visible: true, title: 'Close', btnClass: { 'btn btn-secondary': true } }"
        @close="close_modal()"
    >
        <div v-if="loading" class="text-center py-4">
            <i class="fa fa-spinner fa-spin"></i> Loading...
        </div>

        <div v-else>
            <div class="alert alert-warning" v-if="is_banned">
                This user is currently <strong>banned</strong>.
            </div>
            <div class="alert alert-success" v-else>
                This user is currently <strong>active</strong>.
            </div>

            <div v-if="!is_banned" class="form-group mt-3">
                <label>Ban Duration</label>
                <select class="form-control" v-model="selected_duration">
                    <option value="" disabled>Select duration</option>
                    <option value="0.5 year">0.5 year</option>
                    <option value="1 year">1 year</option>
                    <option value="2 year">2 year</option>
                    <option value="permanent">Permanent</option>
                </select>
            </div>

            <div class="mt-4 d-flex gap-2">
                <button
                    v-if="!is_banned"
                    class="btn btn-warning"
                    :disabled="!selected_duration || saving"
                    @click="create_ban()"
                >
                    <i class="fa fa-ban"></i> {{ saving ? 'Banning...' : 'Ban User' }}
                </button>
                <button
                    v-else
                    class="btn btn-success"
                    :disabled="saving"
                    @click="remove_ban()"
                >
                    <i class="fa fa-check"></i> {{ saving ? 'Unbanning...' : 'Unban User' }}
                </button>
            </div>
        </div>
    </StackModal>
</template>

<script>
export default {
    emits: ['update'],
    data() {
        return {
            is_ban_modal: false,
            loading: false,
            saving: false,
            user_id: null,
            user_name: '',
            is_banned: false,
            selected_duration: '',
        };
    },
    computed: {
        modalTitle() {
            return this.user_name ? `Ban: ${this.user_name}` : 'User Ban';
        }
    },
    methods: {
        show_modal(user_id, user_name = '') {
            this.user_id = user_id;
            this.user_name = user_name;
            this.selected_duration = '';
            this.is_ban_modal = true;
            this.load_ban_status();
        },

        close_modal() {
            this.is_ban_modal = false;
        },

        load_ban_status() {
            this.loading = true;
            axios.get('/set_user/get_ban_status/' + this.user_id)
                .then(response => {
                    this.is_banned = response.data.is_banned;
                    if (!response.data.ban_role_exists) {
                        alert('Warning: "ban" role does not exist in the database. Please create a role with slug "ban" first.');
                    }
                })
                .catch(() => {})
                .finally(() => this.loading = false);
        },

        create_ban() {
            if (!this.selected_duration) return;
            this.saving = true;
            axios.post('/set_user/create_ban/' + this.user_id, { duration: this.selected_duration })
                .then(response => {
                    this.is_banned = true;
                    this.$emit('update');
                    alert(response.data.message);
                })
                .catch(error => {
                    if (error.response?.data?.message) {
                        alert(error.response.data.message);
                    }
                })
                .finally(() => this.saving = false);
        },

        remove_ban() {
            this.saving = true;
            axios.delete('/set_user/remove_ban/' + this.user_id)
                .then(response => {
                    this.is_banned = false;
                    this.$emit('update');
                    alert(response.data.message);
                })
                .catch(error => {
                    if (error.response?.data?.message) {
                        alert(error.response.data.message);
                    }
                })
                .finally(() => this.saving = false);
        },
    }
}
</script>
