<template>
    <stack-modal
        :show="is_modal"
        title="Create task for worker"
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

            <form id="create_task" v-on:submit.prevent="create_task" v-show="!is_loading">
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
                    <label>Category *</label>
                    <select class="form-control" v-model="data.category_id" required>
                        <option :value="0" disabled>Select category</option>
                        <option v-for="cat in tasks_categories" :key="cat.id" :value="cat.id">{{ cat.title }}</option>
                    </select>
                </div>

                <div class="form-group mt-2">
                    <label>Assign to *</label>
                    <select class="form-control" v-model="data.user_id" required>
                        <option :value="0" disabled>Select user</option>
                        <option v-for="user in users" :key="user.id" :value="user.id">{{ user.name }} {{ user.surname }}</option>
                    </select>
                </div>
            </form>
        </div>

        <div slot="modal-footer">
            <div class="modal-footer">
                <button type="submit" form="create_task" class="btn btn-primary" :disabled="is_loading">
                    Save
                </button>
                <button type="button" class="btn btn-secondary" @click="close_modal()">
                    Cancel
                </button>
            </div>
        </div>
    </stack-modal>
</template>

<script>
    export default {
        data() {
            return {
                is_modal: false,
                is_loading: false,
                data: {
                    title: '',
                    text: '',
                    deadline: '',
                    category_id: 0,
                    user_id: 0,
                },
                tasks_categories: [],
                users: [],
            }
        },
        methods: {
            show_modal() {
                this.is_modal = true
                this.data = {
                    title: '',
                    text: '',
                    deadline: '',
                    category_id: 0,
                    user_id: 0,
                }
                this.get_all_tasks_category()
                this.get_users()
            },
            close_modal() {
                this.is_modal = false
            },
            get_all_tasks_category() {
                axios.get('get_task/task_category/get_all_task_categories/')
                    .then(response => { this.tasks_categories = response.data })
                    .catch(() => {});
            },
            get_users() {
                axios.get('get_user/get_worker_users/')
                    .then(response => { this.users = response.data })
                    .catch(() => {});
            },
            create_task() {
                this.is_loading = true
                axios.post('set_task/create_task/', { data: this.data })
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

<style scoped>
.form-group label {
    font-weight: 500;
}
</style>
