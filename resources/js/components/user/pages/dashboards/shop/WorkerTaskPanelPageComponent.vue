<template>
    <div class="row">

        <left-menu />
            
        <div class="col-sm-12">
            <div class="row">
                <div class="col-md-12">
                    <breadcrumb />
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">

                    <div class="tabs"> 
                        <div class="row">
                            <div class="col-md-12">
                                <div class="row">
                                    <div class="col" >
                                        <input type="radio" id="1" :value="1" v-model="tab_num">
                                        
                                        <label for="1" >Tasks</label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row" v-if="tab_num == 1">
                            <div class="col-md-12 mb-2">
                                <button class="btn btn-success pull-right" @click="get_user_tasks">Refresh</button>
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
                                            <button class="btn btn-primary float-right" v-if="task.status != 'confirmation_completion' && task.status != 'finished'" @click="show_task_status_model(task.id)">Update status</button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>

                    </div>

                </div>
            </div>
        </div>

        <taskStatusModal ref="task_status_modal" @restart="get_user_tasks"/>
        <showTaskModal ref="show_task_modal" />
    </div>
</template>

<script>
    import breadcrumb from '../../../items/BreadcrumbComponent.vue'

    import taskStatusModal from '../../../items/modal/task/task/TaskStatusModalComponent.vue'
    import showTaskModal from '../../../items/modal/task/task/ShowTaskModalComponent.vue'

    export default {
        components: {
            breadcrumb,

            showTaskModal,
            taskStatusModal
        },
        data(){
            return{
                tab_num: 1,
                tasks: []
            }
        },
        mounted(){
            this.get_user_tasks()
        },
        methods: {
            show_task_status_model(task_id){
                this.$refs.task_status_modal.show_modal(task_id)
            },  
            show_task_modal(task_id){
                this.$refs.show_task_modal.show_modal(task_id)
            },

            get_user_tasks(){
                axios
                .get("/task/get_user_tasks/")
                .then(response => {
                    this.tasks = response.data
                })
                .catch(
                    error => console.log(error)
                );
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

</style>