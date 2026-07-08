<template>
    <stack-modal
        :show="is_modal"
        :title="$t('admin.task.edit_task_title')"
        @close="close_modal()"
        :saveButton="{ visible: false }"
        :cancelButton="{ visible: false }"
    >
        <div class="container">
            <div class="row justify-content-center" v-show="is_loading">
                <div class="col-md-4 text-center py-4">
                    <div class="spinner-border text-primary" role="status"></div>
                    <p class="mt-2">{{ $t('admin.task.please_wait') }}</p>
                </div>
            </div>

            <form id="update_task" v-on:submit.prevent="update_task" v-show="!is_loading">
                <div class="form-group mt-2">
                    <label>{{ $t('admin.task.task_title_label') }}</label>
                    <input type="text" v-model="data.title" class="form-control" :placeholder="$t('admin.task.task_title_placeholder')" required>
                </div>

                <div class="form-group mt-2">
                    <label>{{ $t('admin.task.description_label') }}</label>
                    <textarea rows="4" v-model="data.text" maxlength="500" :placeholder="$t('admin.task.task_description_placeholder')" class="form-control"></textarea>
                </div>

                <div class="form-group mt-2">
                    <label>{{ $t('admin.task.deadline_label') }}</label>
                    <input type="date" v-model="data.deadline" class="form-control" required>
                </div>

                <div class="form-group mt-2">
                    <label>{{ $t('admin.task.task_type_label') }}</label>
                    <select class="form-control" v-model="data.category" required>
                        <option value="" disabled>{{ $t('admin.task.select_type_placeholder') }}</option>
                        <option value="production">{{ $t('admin.task.production_option') }}</option>
                        <option value="delivery">{{ $t('admin.orders.delivery_option') }}</option>
                        <option value="other">{{ $t('admin.task.other_option') }}</option>
                    </select>
                </div>

                <div class="form-group mt-2">
                    <label>{{ $t('admin.task.assign_to_label') }}</label>
                    <select class="form-control" v-model="data.for_user_id" required>
                        <option :value="0" disabled>{{ $t('admin.task.select_worker_placeholder') }}</option>
                        <option v-for="user in users" :key="user.id" :value="user.id">{{ user.name }} {{ user.surname }}</option>
                    </select>
                </div>
            </form>
        </div>

        <template #footer>
            <div class="modal-footer">
                <button type="submit" form="update_task" class="btn btn-primary" :disabled="is_loading">{{ $t('common.save') }}</button>
                <button type="button" class="btn btn-secondary" @click="close_modal()">{{ $t('admin.comments.cancel_btn') }}</button>
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
