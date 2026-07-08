<template>
    <stack-modal
        :show="is_modal"
        :title="$t('admin.task.update_task_status_title')"
        @close="close_modal()"
        :saveButton="{ visible: false }"
        :cancelButton="{ visible: false }"
    >
        <div class="container">
            <div class="text-center py-4" v-if="is_loading">
                <div class="spinner-border text-primary" role="status"></div>
                <p class="mt-2">{{ $t('admin.task.please_wait') }}</p>
            </div>

            <div v-else>
                <p class="mb-3"><strong>{{ task.title }}</strong></p>

                <form id="edit_task_status" v-on:submit.prevent="edit_task_status">
                    <div class="form-group">
                        <label>{{ $t('admin.task.status_required_label') }}</label>
                        <select class="form-control" v-model="data.status" required>
                            <option value="" disabled>{{ $t('admin.task.select_status_placeholder') }}</option>
                            <option value="problem">{{ $t('admin.task.problem_occurred_option') }}</option>
                            <option value="finished">{{ $t('admin.task.status_finished') }}</option>
                            <option value="confirmation_completion">{{ $t('admin.task.confirmation_completion_option') }}</option>
                        </select>
                    </div>

                    <div class="form-group mt-2" v-if="data.status === 'problem'">
                        <label>{{ $t('admin.task.problem_description_label') }}</label>
                        <textarea rows="4" v-model="data.worker_comment" maxlength="500" :placeholder="$t('admin.task.describe_problem_placeholder')" class="form-control" required></textarea>
                    </div>
                    <div class="form-group mt-2" v-else>
                        <label>{{ $t('admin.task.comment_label') }}</label>
                        <textarea rows="4" v-model="data.worker_comment" maxlength="500" :placeholder="$t('admin.task.optional_comment_placeholder')" class="form-control"></textarea>
                    </div>
                </form>
            </div>
        </div>

        <template #footer>
            <div class="modal-footer">
                <button type="submit" form="edit_task_status" class="btn btn-primary" :disabled="is_loading">{{ $t('admin.task.update_btn') }}</button>
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
                task: {},
                data: {
                    status: '',
                    worker_comment: ''
                },
            }
        },
        methods: {
            show_modal(task_id) {
                this.is_modal = true
                this.task_id = task_id
                this.data = { status: '', worker_comment: '' }
                this.task = {}
                this.load_task(task_id)
            },
            close_modal() {
                this.is_modal = false
            },
            load_task(task_id) {
                axios.get('get_task/get_task_data/' + task_id)
                    .then(response => {
                        this.task = response.data
                        this.data.status = response.data.status || ''
                        this.data.worker_comment = response.data.worker_comment || ''
                    })
                    .catch(() => {});
            },
            edit_task_status() {
                this.is_loading = true
                axios.post('set_task/update_task_status/' + this.task_id, { data: this.data })
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
