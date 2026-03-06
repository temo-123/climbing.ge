<template>
    <div class="col-md-12">
        <div class="tabs task_tub"> 
            <div class="row">
                <div class="col-md-12">
                    <div class="row">
                        <div class="col" >
                            <input type="radio" id="task_1" :value="1" v-model="task_tab_num">
                            
                            <label for="task_1" >Tasks</label>
                        </div>
                        
                    </div>
                </div>
            </div>

            <div class="row" v-if="task_tab_num == 1">

                <div class="col-md-12 mb-2">
                    <button v-if="$can('add', 'task')" class="btn btn-primary pull-left" @click="show_add_task_modal">Create new tsk</button>
                    <button class="btn btn-success pull-right" @click="get_all_tasks">Refresh</button>
                </div>
                
                <div class="col-md-12" v-for="task in tasks" :kay="task.id">
                    <div :class="'alert ' + task_status_color(task.status)" role="alert">
                        <div class="row">
                            <div class="col-md-12">
                                <strong>{{ task.status }}</strong> {{ task.title }}
                                <p>{{ task.deadline }}</p>
                            </div>
                            <div class="col-md-12">
                                <button class="btn btn-success float-right" @click="show_task_modal(task.id)">Show detals</button>
                                <button  v-if="$can('edit_status', 'task') && task.status != 'confirmation_completion'" class="btn btn-primary float-right" @click="show_task_status_model(task.id)">Update status</button>
                                <button v-if="$can('edit', 'task') && task.status != 'confirmation_completion'" class="btn btn-primary float-right" @click="show_edit_task_modal(task.id)">Edit</button>
                                <button v-if="$can('del', 'task')" class="btn btn-danger float-right" @click="del_task(task.id)">Del</button>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            <addTaskModal ref="show_add_task_modal" @restart="get_all_tasks"/>
            <editTaskModal ref="show_edit_task_modal" @restart="get_all_tasks"/>
            <showTaskModal ref="show_task_modal" />
            <adminTaskStatusModal ref="task_status_modal" @restart="get_all_tasks"/>

            <!-- <addTaskCategoryModal ref="show_add_task_category_modal" @restart="get_all_tasks_category"/>
            <editTaskCategoryModal ref="show_edit_task_category_modal" @restart="get_all_tasks_category"/>
            <showTaskCategoryModal ref="show_task_category_modal" /> -->
        </div>
    </div>
</template>
<script>
    import addTaskModal from '../../../../items/modal/task/task/AddTaskModalComponent.vue'
    import editTaskModal from '../../../../items/modal/task/task/EditTaskModalComponent.vue'
    import showTaskModal from '../../../../items/modal/task/task/ShowTaskModalComponent.vue'
    import adminTaskStatusModal from '../../../../items/modal/task/task/AdminTaskStatusModalComponent.vue'

    // import addTaskCategoryModal from '../../../items/modal/task/task_category/AddTaskCategoryModalComponent.vue'
    // import editTaskCategoryModal from '../../../items/modal/task/task_category/EditTaskCategoryModalComponent.vue'
    // import showTaskCategoryModal from '../../../items/modal/task/task_category/ShowTaskCategoryModalComponent.vue'

    export default {
        components: {
            addTaskModal,
            editTaskModal,
            showTaskModal,
            adminTaskStatusModal,

            // addTaskCategoryModal,
            // editTaskCategoryModal,
            // showTaskCategoryModal,
        },
        data(){
            return{
                task_tab_num: 1,

                tasks: [],
                task_categorys: [],
                tasks_categories: [],
            }
        },
        mounted(){
            this.get_all_tasks()
            this.get_all_tasks_category()
        },
        methods: {
            show_task_status_model(task_id){
                this.$refs.task_status_modal.show_modal(task_id)
            },  
            show_add_task_modal(){
                this.$refs.show_add_task_modal.show_modal()
            },
            show_edit_task_modal(task_id){
                this.$refs.show_edit_task_modal.show_modal(task_id)
            },
            show_task_modal(task_id){
                this.$refs.show_task_modal.show_modal(task_id)
            },

            show_add_task_category_modal(){
                this.$refs.show_add_task_category_modal.show_modal()
            },
            show_edit_task_category_modal(category_id){
                this.$refs.show_edit_task_category_modal.show_modal(category_id)
            },
            show_task_category_modal(category_id){
                this.$refs.show_task_category_modal.show_modal(category_id)
            },       

            get_all_tasks(){
                axios
                .get("/get_task/get_all_tasks/")
                .then(response => {
                    this.tasks = response.data
                })
                .catch(
                    error => console.log(error)
                );
            },  

            del_task(task_id){
                if(confirm('Are you sure, you want delite it?')){
                    axios
                    .post('/set_task/del_task/'+task_id, {
                        _method: 'DELETE'
                    })
                    .then(Response => {
                        this.get_all_tasks()
                    })
                    .catch(error => console.log(error))
                }
            },         

            get_all_tasks_category(){
                axios
                .get("/get_task/task_category/get_all_task_categories/")
                .then(response => {
                    this.task_categorys = response.data
                })
                .catch(
                    error => console.log(error)
                );
            }, 

            del_task_category(category_id){
                if(confirm('Are you sure, you want delite it?')){
                    axios
                    .post('/set_task/task_category/del_task_category/'+category_id, {
                        _method: 'DELETE'
                    })
                    .then(Response => {
                        // this.$emit('restart')
                        this.get_all_tasks_category()
                    })
                    .catch(error => console.log(error))
                }
            },  
            task_status_color(status){
                if(status == 'in_process'){
                    return 'alert-warning'
                }
                else if(status == 'finished'){
                    return 'alert-success'
                }
                else if(status == 'confirmation_completion'){
                    return 'alert-info'
                }
                else{
                    return 'alert-danger'
                } 
            } 
        }
    }
</script>

<style scoped>
.task_tub{
    border-style: solid;
    border-width: 1px;
    border-color: #7427bb69;
    border-radius: 1px 1px 5px 5px;
    background-color: #eaeaea;
}
</style>