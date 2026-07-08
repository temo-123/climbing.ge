<template>
    <stack-modal
        :show="is_modal"
        :title="$t('admin.task.task_details_title')"
        @close="close_modal()"
        :saveButton="{ visible: false }"
        :cancelButton="{ visible: false }"
    >
        <div class="container">
            <div class="text-center py-4" v-if="is_loading">
                <div class="spinner-border text-primary" role="status"></div>
                <p class="mt-2">{{ $t('admin.export.loading_ellipsis') }}</p>
            </div>

            <div v-else-if="task.id">
                <div class="d-flex align-items-center mb-3">
                    <span class="task-status-dot mr-2" :class="statusDotClass(task.status)"></span>
                    <h5 class="mb-0">{{ task.title }}</h5>
                </div>

                <div class="row mb-2">
                    <div class="col-sm-6">
                        <small class="text-muted">{{ $t('admin.orders.status_label') }}</small>
                        <div><span class="badge" :class="statusBadgeClass(task.status)">{{ statusLabel(task.status) }}</span></div>
                    </div>
                    <div class="col-sm-6">
                        <small class="text-muted">{{ $t('admin.task.type_label') }}</small>
                        <div>{{ task.category || '—' }}</div>
                    </div>
                </div>

                <div class="row mb-2">
                    <div class="col-sm-6">
                        <small class="text-muted">{{ $t('admin.task.assigned_to_label') }}</small>
                        <div>{{ task.for_user ? task.for_user.name + ' ' + task.for_user.surname : '—' }}</div>
                    </div>
                    <div class="col-sm-6">
                        <small class="text-muted">{{ $t('admin.task.created_by_label') }}</small>
                        <div>{{ task.from_user ? task.from_user.name + ' ' + task.from_user.surname : '—' }}</div>
                    </div>
                </div>

                <div class="mb-2">
                    <small class="text-muted">{{ $t('admin.task.deadline_col_label') }}</small>
                    <div :class="isOverdue(task.deadline) ? 'text-danger font-weight-bold' : ''">
                        {{ task.deadline ? task.deadline.substring(0, 10) : '—' }}
                        <span v-if="isOverdue(task.deadline)"> {{ $t('admin.task.overdue_suffix') }}</span>
                    </div>
                </div>

                <div class="mb-2" v-if="task.text">
                    <small class="text-muted">{{ $t('admin.task.description_label') }}</small>
                    <p class="mb-0">{{ task.text }}</p>
                </div>

                <div class="alert alert-danger mt-3 mb-0" v-if="task.status === 'problem' && task.worker_comment">
                    <strong><i class="fa fa-exclamation-triangle"></i> {{ $t('admin.task.problem_prefix') }}</strong> {{ task.worker_comment }}
                </div>
                <div class="mb-0 mt-2" v-else-if="task.worker_comment">
                    <small class="text-muted">{{ $t('admin.task.comment_label') }}</small>
                    <p class="mb-0">{{ task.worker_comment }}</p>
                </div>
            </div>
        </div>

        <template #footer>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" @click="close_modal()">{{ $t('common.close') }}</button>
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
                task: {}
            }
        },
        methods: {
            show_modal(task_id) {
                this.is_modal = true
                this.task = {}
                this.load_task(task_id)
            },
            close_modal() {
                this.is_modal = false
                this.task = {}
            },
            load_task(task_id) {
                this.is_loading = true
                axios.get('get_task/get_task_data/' + task_id)
                    .then(response => { this.task = response.data })
                    .catch(() => {})
                    .finally(() => this.is_loading = false)
            },
            isOverdue(deadline) {
                return deadline && new Date(deadline) < new Date()
            },
            statusLabel(status) {
                const map = {
                    set_task: this.$t('admin.task.status_created'), in_process: this.$t('admin.task.status_in_progress'),
                    finished: this.$t('admin.task.status_finished'), confirmation_completion: this.$t('admin.task.status_awaiting_confirmation'), problem: this.$t('admin.task.status_problem')
                }
                return map[status] || status
            },
            statusDotClass(status) {
                const map = {
                    set_task: 'dot-yellow', in_process: 'dot-green',
                    finished: 'dot-gray', confirmation_completion: 'dot-blue', problem: 'dot-red'
                }
                return map[status] || 'dot-gray'
            },
            statusBadgeClass(status) {
                const map = {
                    set_task: 'badge-warning', in_process: 'badge-success',
                    finished: 'badge-secondary', confirmation_completion: 'badge-info', problem: 'badge-danger'
                }
                return map[status] || 'badge-secondary'
            }
        }
    }
</script>

<style scoped>
.task-status-dot {
    width: 12px; height: 12px; border-radius: 50%; flex-shrink: 0;
}
.dot-yellow { background: #ffc107; }
.dot-green  { background: #28a745; }
.dot-gray   { background: #6c757d; }
.dot-blue   { background: #17a2b8; }
.dot-red    { background: #dc3545; }
</style>
