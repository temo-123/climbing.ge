<template>
    <StackModal
        v-model="is_ban_modal"
        :title="modalTitle"
        :saveButton="{ visible: false }"
        :cancelButton="{ visible: true, title: $t('common.close'), btnClass: { 'btn btn-secondary': true } }"
        @close="close_modal()"
    >
        <div v-if="loading" class="text-center py-4">
            <i class="fa fa-spinner fa-spin"></i> {{ $t('admin.export.loading_ellipsis') }}
        </div>

        <div v-else>
            <div class="alert alert-warning" v-if="is_banned">
                {{ $t('admin.tour.user_currently_prefix') }} <strong>{{ $t('admin.tour.banned_label') }}</strong>.
            </div>
            <div class="alert alert-success" v-else>
                {{ $t('admin.tour.user_currently_prefix') }} <strong>{{ $t('admin.tour.active_label') }}</strong>.
            </div>

            <div v-if="!is_banned" class="form-group mt-3">
                <label>{{ $t('admin.tour.ban_duration_label') }}</label>
                <select class="form-control" v-model="selected_duration">
                    <option value="" disabled>{{ $t('admin.tour.select_duration_placeholder') }}</option>
                    <option value="0.5 year">{{ $t('admin.tour.half_year_option') }}</option>
                    <option value="1 year">{{ $t('admin.tour.one_year_option') }}</option>
                    <option value="2 year">{{ $t('admin.tour.two_year_option') }}</option>
                    <option value="permanent">{{ $t('admin.tour.permanent_option') }}</option>
                </select>
            </div>

            <div class="mt-4 d-flex gap-2">
                <button
                    v-if="!is_banned"
                    class="btn btn-warning"
                    :disabled="!selected_duration || saving"
                    @click="create_ban()"
                >
                    <i class="fa fa-ban"></i> {{ saving ? $t('admin.tour.banning_ellipsis') : $t('admin.tour.ban_user_btn') }}
                </button>
                <button
                    v-else
                    class="btn btn-success"
                    :disabled="saving"
                    @click="remove_ban()"
                >
                    <i class="fa fa-check"></i> {{ saving ? $t('admin.tour.unbanning_ellipsis') : $t('admin.tour.unban_user_btn') }}
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
            return this.user_name ? this.$t('admin.tour.ban_prefix', { name: this.user_name }) : this.$t('admin.tour.user_ban_title');
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
                        alert(this.$t('admin.tour.ban_role_missing_warning'));
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
