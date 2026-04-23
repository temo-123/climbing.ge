<template>
    <div class="col-md-12">
        <div class="task-panel">
            <div class="task-panel-toolbar mb-3">
                <button v-if="$can('add', 'task')" class="btn btn-primary btn-sm" @click="show_add_task_modal">
                    <i class="fa fa-plus"></i> New Task
                </button>
                <button class="btn btn-outline-secondary btn-sm ml-2" @click="load_tasks">
                    <i class="fa fa-refresh"></i> Refresh
                </button>
                <div class="task-legend ml-auto">
                    <span class="legend-dot dot-yellow"></span> Created
                    <span class="legend-dot dot-green ml-2"></span> In Progress
                    <span class="legend-dot dot-gray ml-2"></span> Finished
                    <span class="legend-dot dot-red ml-2"></span> Problem
                </div>
            </div>

            <div v-if="tasks.length === 0" class="text-center text-muted p-4">
                No tasks found.
            </div>

            <div v-for="task in tasks" :key="task.id" class="task-card" :class="task_card_class(task.status)">
                <div class="task-card-left" :class="task_bar_class(task.status)"></div>
                <div class="task-card-body">
                    <div class="task-card-top">
                        <div>
                            <span class="task-title">{{ task.title }}</span>
                            <span class="task-badge ml-2" :class="task_badge_class(task.status)">{{ status_label(task.status) }}</span>
                            <span v-if="task.category" class="badge badge-light ml-1">{{ task.category }}</span>
                        </div>
                        <div class="task-card-actions">
                            <button class="btn btn-sm btn-outline-info" @click="show_task_modal(task.id)" title="Details">
                                <i class="fa fa-eye"></i>
                            </button>
                            <button v-if="$can('edit', 'task')" class="btn btn-sm btn-outline-primary ml-1" @click="show_edit_task_modal(task.id)" title="Edit">
                                <i class="fa fa-pencil"></i>
                            </button>
                            <button v-if="$can('edit_status', 'task')" class="btn btn-sm btn-outline-warning ml-1" @click="show_status_modal(task.id)" title="Update status">
                                <i class="fa fa-exchange"></i>
                            </button>
                            <button v-if="$can('del', 'task')" class="btn btn-sm btn-outline-danger ml-1" @click="del_task(task.id)" title="Delete">
                                <i class="fa fa-trash"></i>
                            </button>
                        </div>
                    </div>
                    <div class="task-card-meta">
                        <span class="text-muted"><i class="fa fa-user"></i>
                            {{ task.for_user ? task.for_user.name + ' ' + task.for_user.surname : '—' }}
                        </span>
                        <span class="text-muted ml-3" :class="is_overdue(task.deadline) ? 'text-danger' : ''">
                            <i class="fa fa-calendar"></i> {{ format_date(task.deadline) }}
                            <span v-if="is_overdue(task.deadline)"> !</span>
                        </span>
                    </div>
                    <div v-if="task.status === 'problem' && task.worker_comment" class="task-problem-note mt-1">
                        <i class="fa fa-exclamation-triangle text-danger"></i>
                        <span>{{ task.worker_comment }}</span>
                    </div>
                </div>
            </div>
        </div>

        <addTaskModal ref="add_task_modal" :globalCategory="globalCategory" @restart="load_tasks"/>
        <editTaskModal ref="edit_task_modal" @restart="load_tasks"/>
        <showTaskModal ref="show_task_modal"/>
        <adminTaskStatusModal ref="status_modal" @restart="load_tasks"/>
    </div>
</template>

<script>
    import addTaskModal from '../../../../items/modal/task/task/AddTaskModalComponent.vue'
    import editTaskModal from '../../../../items/modal/task/task/EditTaskModalComponent.vue'
    import showTaskModal from '../../../../items/modal/task/task/ShowTaskModalComponent.vue'
    import adminTaskStatusModal from '../../../../items/modal/task/task/AdminTaskStatusModalComponent.vue'

    export default {
        components: { addTaskModal, editTaskModal, showTaskModal, adminTaskStatusModal },
        props: {
            globalCategory: { type: String, default: 'shop' },
            filterCategory: { type: String, default: null }
        },
        data() {
            return { tasks: [] }
        },
        mounted() {
            this.load_tasks()
        },
        methods: {
            load_tasks() {
                const params = { global_category: this.globalCategory }
                axios.get('/get_task/get_all_tasks', { params })
                    .then(r => {
                        this.tasks = this.filterCategory
                            ? r.data.filter(t => t.category === this.filterCategory)
                            : r.data
                    })
                    .catch(() => {})
            },
            show_add_task_modal() { this.$refs.add_task_modal.show_modal() },
            show_edit_task_modal(id) { this.$refs.edit_task_modal.show_modal(id) },
            show_task_modal(id) { this.$refs.show_task_modal.show_modal(id) },
            show_status_modal(id) { this.$refs.status_modal.show_modal(id) },
            del_task(id) {
                if (!confirm('Delete this task?')) return
                axios.post('/set_task/del_task/' + id, { _method: 'DELETE' })
                    .then(() => this.load_tasks())
                    .catch(() => {})
            },
            task_card_class(status) {
                const map = {
                    set_task: 'card-yellow', in_process: 'card-green',
                    finished: 'card-gray', confirmation_completion: 'card-blue', problem: 'card-red'
                }
                return map[status] || 'card-gray'
            },
            task_bar_class(status) {
                const map = {
                    set_task: 'bar-yellow', in_process: 'bar-green',
                    finished: 'bar-gray', confirmation_completion: 'bar-blue', problem: 'bar-red'
                }
                return map[status] || 'bar-gray'
            },
            task_badge_class(status) {
                const map = {
                    set_task: 'badge-warning', in_process: 'badge-success',
                    finished: 'badge-secondary', confirmation_completion: 'badge-info', problem: 'badge-danger'
                }
                return map[status] || 'badge-secondary'
            },
            status_label(status) {
                const map = {
                    set_task: 'Created', in_process: 'In Progress',
                    finished: 'Finished', confirmation_completion: 'Awaiting Confirm', problem: 'Problem'
                }
                return map[status] || status
            },
            is_overdue(deadline) {
                return deadline && new Date(deadline) < new Date()
            },
            format_date(d) {
                return d ? d.substring(0, 10) : '—'
            },
        }
    }
</script>

<style scoped>
.task-panel-toolbar { display: flex; align-items: center; flex-wrap: wrap; gap: 8px; }
.task-legend { display: flex; align-items: center; font-size: 0.8em; color: #666; }
.legend-dot { display: inline-block; width: 10px; height: 10px; border-radius: 50%; }

.task-card {
    display: flex; border-radius: 6px; margin-bottom: 10px;
    box-shadow: 0 1px 4px rgba(0,0,0,0.08); overflow: hidden; background: #fff;
}
.task-card-left { width: 5px; flex-shrink: 0; }
.task-card-body { flex: 1; padding: 10px 14px; }
.task-card-top { display: flex; justify-content: space-between; align-items: flex-start; }
.task-title { font-weight: 600; font-size: 0.95em; }
.task-card-meta { margin-top: 4px; font-size: 0.82em; }
.task-card-actions { display: flex; flex-shrink: 0; }
.task-problem-note { font-size: 0.85em; color: #c0392b; }

.card-yellow { border-left: none; }
.card-green  { border-left: none; }
.card-gray   { border-left: none; background: #f8f9fa; }
.card-blue   { border-left: none; }
.card-red    { border-left: none; background: #fff8f8; }

.bar-yellow { background: #ffc107; }
.bar-green  { background: #28a745; }
.bar-gray   { background: #6c757d; }
.bar-blue   { background: #17a2b8; }
.bar-red    { background: #dc3545; }

.dot-yellow { background: #ffc107; }
.dot-green  { background: #28a745; }
.dot-gray   { background: #6c757d; }
.dot-blue   { background: #17a2b8; }
.dot-red    { background: #dc3545; }
</style>
