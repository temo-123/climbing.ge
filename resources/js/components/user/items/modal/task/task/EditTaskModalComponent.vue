<template>
    <stack-modal
        :show="is_modal"
        title="Edit task"
        @close="close_modal()"
        :saveButton="{ visible: false }"
        :cancelButton="{ visible: false }"
    >
        <div class="container">
            <div class="row justify-content-center" v-show="is_loading">
                <div class="col-md-4 text-center py-4">
                    <div class="spinner-border text-primary" role="status"></div>
                    <p class="mt-2">Please wait...</p>
                </div>
            </div>

            <form id="update_task" v-on:submit.prevent="update_task" v-show="!is_loading">
                <div class="form-group mt-2">
                    <label>Task title *</label>
                    <input type="text" v-model="data.title" class="form-control" placeholder="Task title" required>
                </div>

                <div class="form-group mt-2">
                    <label>Description</label>
                    <textarea rows="4" v-model="data.text" maxlength="500" placeholder="Task description" class="form-control"></textarea>
                </div>

                <div class="form-group mt-2">
                    <label>Deadline *</label>
                    <input type="date" v-model="data.deadline" class="form-control" required>
                </div>

                <div class="form-group mt-2">
                    <label>Task type *</label>
                    <select class="form-control" v-model="data.category" required>
                        <option value="" disabled>Select type</option>
                        <option value="production">Production</option>
                        <option value="delivery">Delivery</option>
                        <option value="other">Other</option>
                    </select>
                </div>

                <div class="form-group mt-2">
                    <label>Assign to *</label>
                    <select class="form-control" v-model="data.for_user_id" required>
                        <option :value="0" disabled>Select worker</option>
                        <option v-for="user in users" :key="user.id" :value="user.id">{{ user.name }} {{ user.surname }}</option>
                    </select>
                </div>
            </form>
        </div>

        <template #footer>
            <div class="modal-footer">
                <button type="submit" form="update_task" class="btn btn-primary" :disabled="is_loading">Save</button>
                <button type="button" class="btn btn-secondary" @click="close_modal()">Cancel</button>
            </div>
        </template>
    </stack-modal>
</template>

<script>
    export default {
        data() {
            return {
                is_modal: false,
                is_loading: false,
                task_id: 0,
                data: {
                    title: '',
                    text: '',
                    deadline: '',
                    category: '',
                    for_user_id: 0,
                },
                users: [],
            }
        },
        methods: {
            show_modal(task_id) {
                this.is_modal = true
                this.task_id = task_id
                this.data = { title: '', text: '', deadline: '', category: '', for_user_id: 0 }
                this.get_users()
                this.load_task(task_id)
            },
            close_modal() {
                this.is_modal = false
            },
            get_users() {
                axios.get('get_user/get_worker_users/')
                    .then(response => { this.users = response.data })
                    .catch(() => {});
            },
            load_task(task_id) {
                this.is_loading = true
                axios.get('get_task/get_task_data/' + task_id)
                    .then(response => {
                        const t = response.data
                        this.data = {
                            title: t.title || '',
                            text: t.text || '',
                            deadline: t.deadline ? t.deadline.substring(0, 10) : '',
                            category: t.category || '',
                            for_user_id: t.for_user_id || 0,
                        }
                    })
                    .catch(() => {})
                    .finally(() => this.is_loading = false)
            },
            update_task() {
                this.is_loading = true
                axios.post('set_task/update_task/' + this.task_id, { data: this.data })
                    .then(() => {
                        this.$emit('restart')
                        this.close_modal()
                    })
                    .catch(() => {})
                    .finally(() => this.is_loading = false)
            }
        }
    }
</script>
