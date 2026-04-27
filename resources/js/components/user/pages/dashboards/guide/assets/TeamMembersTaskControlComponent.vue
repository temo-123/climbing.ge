<template>
    <div class="col-md-12">
        <div class="tabs task_tub">
            <div class="row">
                <div class="col-md-12">
                    <div class="row">
                        <div class="col">
                            <input type="radio" id="task_1" :value="1" v-model="task_tab_num">
                            <label for="task_1">Tasks</label>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row" v-if="task_tab_num == 1">

                <div class="col-md-12 mb-2">
                    <button v-if="$can('add', 'task')" class="btn btn-primary pull-left" @click="show_add_task_modal">
                        Create new task
                    </button>
                    <button class="btn btn-success pull-right" @click="get_all_tasks">Refresh</button>
                </div>

                <div class="col-md-12 text-center py-3" v-if="is_loading">
                    <div class="spinner-border text-primary" role="status"></div>
                    <p class="mt-2 text-muted">Loading tasks...</p>
                </div>

                <div class="col-md-12" v-else-if="tasks.length === 0">
                    <p class="text-muted text-center py-3">No tasks found.</p>
                </div>

                <div class="col-md-12" v-for="task in tasks" :key="task.id" v-else>
                    <div :class="'alert ' + task_status_color(task.status)" role="alert">
                        <div class="row">
                            <div class="col-md-12">
                                <strong>{{ task.status }}</strong> &mdash; {{ task.title }}
                                <p class="mb-0 mt-1 text-muted"><small>Deadline: {{ task.deadline }}</small></p>
                            </div>
                            <div class="col-md-12 mt-2">
                                <button class="btn btn-success btn-sm float-right ml-1" @click="show_task_modal(task.id)">
                                    Show details
                                </button>
                                <button v-if="$can('edit_status', 'task') && task.status !== 'confirmation_completion'"
                                    class="btn btn-primary btn-sm float-right ml-1"
                                    @click="show_task_status_model(task.id)">
                                    Update status
                                </button>
                                <button v-if="$can('edit', 'task') && task.status !== 'confirmation_completion'"
                                    class="btn btn-warning btn-sm float-right ml-1"
                                    @click="show_edit_task_modal(task.id)">
                                    Edit
                                </button>
                                <button v-if="$can('del', 'task')"
                                    class="btn btn-danger btn-sm float-right ml-1"
                                    @click="del_task(task.id)">
                                    Delete
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            <addTaskModal ref="show_add_task_modal" globalCategory="guide" @restart="get_all_tasks" />
            <editTaskModal ref="show_edit_task_modal" @restart="get_all_tasks" />
            <showTaskModal ref="show_task_modal" />
            <adminTaskStatusModal ref="task_status_modal" @restart="get_all_tasks" />
        </div>
    </div>
</template>

<script>
    import addTaskModal from '../../../../items/modal/task/task/AddTaskModalComponent.vue'
    import editTaskModal from '../../../../items/modal/task/task/EditTaskModalComponent.vue'
    import showTaskModal from '../../../../items/modal/task/task/ShowTaskModalComponent.vue'
    import adminTaskStatusModal from '../../../../items/modal/task/task/AdminTaskStatusModalComponent.vue'

    export default {
        components: {
            addTaskModal,
            editTaskModal,
            showTaskModal,
            adminTaskStatusModal,
        },
        data() {
            return {
                task_tab_num: 1,
                is_loading: false,
                tasks: [],
                task_categorys: [],
            }
        },
        mounted() {
            this.get_all_tasks()
            this.get_all_tasks_category()
        },
        methods: {
            show_task_status_model(task_id) {
                this.$refs.task_status_modal.show_modal(task_id)
            },
            show_add_task_modal() {
                this.$refs.show_add_task_modal.show_modal()
            },
            show_edit_task_modal(task_id) {
                this.$refs.show_edit_task_modal.show_modal(task_id)
            },
            show_task_modal(task_id) {
                this.$refs.show_task_modal.show_modal(task_id)
            },

            get_all_tasks() {
                this.is_loading = true
                axios.get('get_task/get_all_tasks/', { params: { global_category: 'guide' } })
                    .then(response => { this.tasks = response.data })
                    .catch(() => {})
                    .finally(() => this.is_loading = false)
            },

            del_task(task_id) {
                if (confirm('Are you sure you want to delete this task?')) {
                    axios.post('set_task/del_task/' + task_id, { _method: 'DELETE' })
                        .then(() => { this.get_all_tasks() })
                        .catch(() => {})
                }
            },

            get_all_tasks_category() {
                axios.get('get_task/task_category/get_all_task_categories/')
                    .then(response => { this.task_categorys = response.data })
                    .catch(() => {})
            },

            task_status_color(status) {
                const map = {
                    in_process: 'alert-warning',
                    finished: 'alert-success',
                    confirmation_completion: 'alert-info',
                }
                return map[status] || 'alert-danger'
            }
        }
    }
</script>

<style scoped>
.task_tub {
    border: 1px solid #7427bb69;
    border-radius: 1px 1px 5px 5px;
    background-color: #eaeaea;
    padding: 10px;
}
.ml-1 {
    margin-left: 4px;
}
</style>
