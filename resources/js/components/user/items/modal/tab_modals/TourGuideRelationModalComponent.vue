<template>
    <stack-modal
        :show="is_modal_show"
        title="Tour Guides"
        size="md"
        @close="close_modal"
        :saveButton="{ visible: false }"
        :cancelButton="{ visible: false }"
    >
        <div>
            <!-- Current guides table -->
            <div class="mb-3">
                <h6 class="text-muted mb-2">
                    Current guides
                    <span class="badge" :class="current_guides.length >= 4 ? 'badge-danger' : 'badge-secondary'">
                        {{ current_guides.length }} / 4
                    </span>
                </h6>

                <div v-if="loading" class="text-center py-3">
                    <i class="fa fa-spinner fa-spin"></i> Loading...
                </div>

                <div v-else-if="current_guides.length === 0" class="alert alert-info py-2 mb-0">
                    No guides assigned to this tour yet.
                </div>

                <table v-else class="table table-sm table-bordered mb-0">
                    <thead class="thead-light">
                        <tr>
                            <th style="width:44px"></th>
                            <th>Name</th>
                            <th style="width:80px"></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="rel in current_guides" :key="rel.relation_id">
                            <td class="text-center align-middle p-1">
                                <div
                                    class="guide-avatar"
                                    :style="rel.user.image
                                        ? 'background-image:url(/public/images/user_profil_img/' + rel.user.image + ')'
                                        : 'background-image:url(/public/images/site_img/demo_imgs/user_demo_img.gif)'"
                                ></div>
                            </td>
                            <td class="align-middle">
                                {{ rel.user.name }} {{ rel.user.surname }}
                                <small class="text-muted">#{{ rel.user.id }}</small>
                            </td>
                            <td class="text-center align-middle">
                                <button
                                    class="btn btn-danger btn-xs"
                                    @click="remove_guide(rel.user.id)"
                                    :disabled="removing === rel.user.id"
                                >
                                    <i class="fa fa-times"></i>
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Add guide -->
            <div class="border-top pt-3">
                <h6 class="text-muted mb-2">Add guide</h6>
                <div v-if="current_guides.length >= 4" class="alert alert-warning py-2">
                    <i class="fa fa-exclamation-triangle"></i> Maximum 4 guides reached.
                </div>
                <div v-else class="input-group">
                    <select class="form-control" v-model="selected_user_id">
                        <option value="">— Select user —</option>
                        <option
                            v-for="user in available_users"
                            :key="user.id"
                            :value="user.id"
                        >
                            {{ user.name }} {{ user.surname }}
                        </option>
                    </select>
                    <div class="input-group-append">
                        <button
                            class="btn btn-primary"
                            @click="add_guide"
                            :disabled="!selected_user_id || adding"
                        >
                            <span v-if="adding"><i class="fa fa-spinner fa-spin"></i></span>
                            <span v-else><i class="fa fa-plus"></i> Add</span>
                        </button>
                    </div>
                </div>
                <div v-if="error_msg" class="alert alert-danger py-1 mt-2 mb-0">
                    {{ error_msg }}
                </div>
            </div>
        </div>

        <template #footer>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" @click="close_modal">
                    Close
                </button>
            </div>
        </template>
    </stack-modal>
</template>

<script>
export default {
    name: 'TourGuideRelationModalComponent',
    emits: ['updated'],
    data() {
        return {
            is_modal_show: false,
            tour_id: null,
            current_guides: [],
            all_users: [],
            selected_user_id: '',
            loading: false,
            adding: false,
            removing: null,
            error_msg: '',
        }
    },
    computed: {
        available_users() {
            const assigned_ids = new Set(this.current_guides.map(r => r.user.id))
            return this.all_users.filter(u => !assigned_ids.has(u.id))
        }
    },
    methods: {
        show_modal(tour_id) {
            this.tour_id = tour_id
            this.error_msg = ''
            this.selected_user_id = ''
            this.is_modal_show = true
            this.load_data()
        },
        close_modal() {
            this.is_modal_show = false
            this.tour_id = null
            this.current_guides = []
            this.all_users = []
            this.selected_user_id = ''
            this.error_msg = ''
        },
        load_data() {
            this.loading = true
            Promise.all([
                axios.get('/set_tour/get_tour_users/' + this.tour_id),
                axios.get('/get_user/get_all_users/'),
            ])
                .then(([guidesRes, usersRes]) => {
                    this.current_guides = guidesRes.data
                    this.all_users = usersRes.data
                })
                .catch(() => {
                    this.error_msg = 'Failed to load data.'
                })
                .finally(() => { this.loading = false })
        },
        add_guide() {
            if (!this.selected_user_id) return
            this.error_msg = ''
            this.adding = true
            axios.post('/set_tour/add_tour_user/' + this.tour_id, { user_id: this.selected_user_id })
                .then(() => {
                    this.selected_user_id = ''
                    this.load_data()
                    this.$emit('updated')
                })
                .catch(err => {
                    this.error_msg = err.response?.data?.error || 'Failed to add guide.'
                })
                .finally(() => { this.adding = false })
        },
        remove_guide(user_id) {
            this.error_msg = ''
            this.removing = user_id
            axios.delete('/set_tour/remove_tour_user/' + this.tour_id + '/' + user_id)
                .then(() => {
                    this.current_guides = this.current_guides.filter(r => r.user.id !== user_id)
                    this.$emit('updated')
                })
                .catch(() => { this.error_msg = 'Failed to remove guide.' })
                .finally(() => { this.removing = null })
        },
    }
}
</script>

<style scoped>
.guide-avatar {
    width: 32px;
    height: 32px;
    border-radius: 50%;
    background-size: cover;
    background-position: center;
    display: inline-block;
}
.btn-xs {
    padding: 2px 8px;
    font-size: 12px;
}
</style>
