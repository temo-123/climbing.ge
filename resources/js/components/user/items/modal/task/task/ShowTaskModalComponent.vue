<template>
    <stack-modal
        :show="is_modal"
        title="Task details"
        @close="close_modal()"
        :saveButton="{ visible: false }"
        :cancelButton="{ visible: true, title: 'Close', btnClass: { 'btn btn-secondary': true } }"
    >
        <div class="container">
            <div class="text-center py-4" v-if="is_loading">
                <div class="spinner-border text-primary" role="status"></div>
                <p class="mt-2">Loading...</p>
            </div>

            <div v-else>
                <h5 class="mb-1">{{ task.title }}</h5>
                <p class="text-muted mb-2">
                    <strong>Status:</strong>
                    <span :class="'badge ' + status_badge(task.status)">{{ task.status }}</span>
                </p>
                <p class="mb-2"><strong>Deadline:</strong> {{ task.deadline }}</p>
                <p class="mb-3" v-if="task.text">{{ task.text }}</p>
                <hr>
                <p class="mb-1 text-muted"><small>Category ID: {{ task.category_id }} &nbsp;|&nbsp; Assigned user ID: {{ task.user_id }}</small></p>
                <p class="mb-0 text-muted" v-if="task.worker_comment"><small><strong>Comment:</strong> {{ task.worker_comment }}</small></p>
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
            status_badge(status) {
                const map = {
                    in_process: 'badge-warning',
                    finished: 'badge-success',
                    confirmation_completion: 'badge-info',
                    problem: 'badge-danger',
                }
                return map[status] || 'badge-secondary'
            }
        }
    }
</script>
