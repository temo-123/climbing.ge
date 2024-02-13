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
                        <div class="col" >
                            <input type="radio" id="task_2" :value="2" v-model="task_tab_num">
                            
                            <label for="task_2" >Tasks type</label>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row" v-if="task_tab_num == 1">

                <div class="col-md-12 mb-2">
                    <button class="btn btn-primary pull-left" @click="show_add_task_modal">Create new tsk</button>
                    <button class="btn btn-success pull-right" @click="get_all_tasks">Refresh</button>
                </div>
                
                <div class="col-md-12" v-for="task in tasks" :kay="task.id">
                    <div class="alert alert-danger" role="alert">
                        <div class="row">
                            <div class="col-md-12">
                                <strong>Danger!</strong> task 1.
                            </div>
                            <div class="col-md-12">
                                <button class="btn btn-success float-right" @click="show_task_modal">Show detals</button>
                                <button class="btn btn-primary float-right" @click="show_edit_task_modal">Edit</button>
                                <button class="btn btn-danger float-right" @click="del_task">Del</button>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            <div class="row" v-if="task_tab_num == 2">
                <div class="col-md-12 mb-2">
                    <button class="btn btn-primary pull-left" @click="show_add_task_category_modal">Create new tsk type</button>
                    <button class="btn btn-success pull-right" @click="get_all_tasks_category">Refresh</button>
                </div>
                <div class="col-md-12 mb-2">
                    <table class="table table-hover" id="dev-table" >
                        <thead>
                            <tr>
                                <th style="text-align: center">
                                    <input type="checkbox" class="all" />
                                </th>
                                <th>|</th>

                                <th>ID</th>
                                <th>|</th>

                                <th>Title / Name</th>
                                <th>|</th>

                                <th>Edit</th>
                                <th>|</th>

                                <th>Del</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="task_type in task_types" :kay="task_type.id">
                                <td :style='"text-align: center;"'>
                                    <input type="checkbox">
                                </td>
                                <td>|</td>
                                <td>{{ task_type.id }}</td>
                                <td>|</td>
                                <td class="cursor_pointer" @click="show_task_category_modal">{{ task_type.title }}</td>
                                <td>|</td>
                                <td>
                                    <button type="submit" class="btn btn-primary" @click="show_edit_task_category_modal(task_type.id)"  v-if="$can('del', 'shiping_country')"><i class="fa fa-pencil" aria-hidden="true"></i></button>
                                </td>
                                <td>|</td>
                                <td>
                                    <button class="btn btn-danger" type="submit" @click="del_task_category(task_type.id)" v-if="$can('edit', 'shiping_country')"><i class="fa fa-trash" aria-hidden="true"></i></button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <addTaskModal ref="show_add_task_modal" @restart="get_all_tasks"/>
            <editTaskModal ref="show_edit_task_modal" @restart="get_all_tasks"/>
            <taskProcesModal ref="show_task_modal" />

            <addTaskCategoryModal ref="show_add_task_category_modal" @restart="get_all_tasks_category"/>
            <editTaskCategoryModal ref="show_edit_task_category_modal" @restart="get_all_tasks_category"/>
            <showTaskCategoryModal ref="show_task_category_modal" />
        </div>
    </div>
</template>
<script>
    import addTaskModal from '../../items/modal/task/task/AddTaskModalComponent.vue'
    import editTaskModal from '../../items/modal/task/task/EditTaskModalComponent.vue'
    import taskProcesModal from '../../items/modal/task/task/TaskProcesModalComponent.vue'

    import addTaskCategoryModal from '../../items/modal/task/task_category/AddTaskCategoryModalComponent.vue'
    import editTaskCategoryModal from '../../items/modal/task/task_category/EditTaskCategoryModalComponent.vue'
    import showTaskCategoryModal from '../../items/modal/task/task_category/ShowTaskCategoryModalComponent.vue'

    export default {
        components: {
            addTaskModal,
            editTaskModal,
            taskProcesModal,

            addTaskCategoryModal,
            editTaskCategoryModal,
            showTaskCategoryModal,
        },
        data(){
            return{
                task_tab_num: 1,

                tasks: [],
                task_types: [],
                tasks_categories: [],
            }
        },
        mounted(){
            this.get_all_tasks()
            this.get_all_tasks_category()
        },
        methods: {
            show_add_task_modal(){
                this.$refs.show_add_task_modal.show_modal()
            },
            show_edit_task_modal(){
                this.$refs.show_edit_task_modal.show_modal()
            },
            show_task_modal(){
                this.$refs.show_task_modal.show_modal()
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
                .get("/task/get_all_tasks/")
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
                    .post('../../api/event/del_event/'+id, {
                        _method: 'DELETE'
                    })
                    .then(Response => {
                        this.$emit('restart')
                    })
                    .catch(error => console.log(error))
                }
            },         

            get_all_tasks_category(){
                axios
                .get("/task/task_category/get_all_task_categories/")
                .then(response => {
                    this.task_types = response.data
                })
                .catch(
                    error => console.log(error)
                );
            }, 

            del_task_category(category_id){
                if(confirm('Are you sure, you want delite it?')){
                    axios
                    .post('/task/task_category/del_task_category/'+category_id, {
                        _method: 'DELETE'
                    })
                    .then(Response => {
                        // this.$emit('restart')
                        this.get_all_tasks_category()
                    })
                    .catch(error => console.log(error))
                }
            },   
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