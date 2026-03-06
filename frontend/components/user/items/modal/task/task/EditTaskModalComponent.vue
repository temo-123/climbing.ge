<template>
    <stack-modal
        :show="is_modal"
        title="Edit task for worker"
        @close="close_modal()"
        :saveButton="{ visible: true, title: 'Sand', btnClass: { 'btn btn-primary': true } }"
        :cancelButton="{ visible: false, title: 'Close', btnClass: { 'btn btn-danger': true } }"
    >
        <pre class="language-vue">
            <div class="container">
                <div class="row justify-content-center" v-show="is_loading">
                    <div class="col-md-4">
                        <img :src="'../../../../../../public/images/site_img/loading.gif'" alt="loading">
                    </div>
                </div>
            </div>
            <form id="update_task" v-on:submit.prevent="update_task" v-show="!is_loading">
                
                <input type="text" v-model="data.title" name="title" class="form-control" required> 
            
                <textarea rows="6" name="text" v-model="data.text" id="text" maxlength="500" placeholder="Task" class="form-control textarea"></textarea>
            
                <input type="date" v-model="data.deadline" name="deadline" class="form-control" required> 
            
                <select class="form-control" name="filter" v-model="data.category_id" required>
                    <option value="0" disabled>Select category</option> 

                    <option v-for="tasks_category in tasks_categories" :kay="tasks_category.id" :value="tasks_category.id">{{ tasks_category.title }}</option>
                </select> 
            
                <select class="form-control" name="filter" v-model="data.user_id" required>
                    <option value="0" disabled>Select user</option> 

                    <option v-for="user in users" :kay="user.id" :value="user.id">{{ user.name }} {{ user.surname }}</option>
                </select> 
                        
            </form>
        </pre>
        <div slot="modal-footer">
            <div class="modal-footer">
                <button
                    type="submit"
                    form="update_task"
                    :class="{'btn btn-primary': true}"
                >
                Send
                </button>
            </div>
        </div>
    </stack-modal>
</template>

<script>
    import StackModal from '@innologica/vue-stackable-modal'  //https://innologica.github.io/vue-stackable-modal/#sample-css

    export default {
        components: {
            StackModal,
        },
        data(){
            return{
                is_modal: false,
                is_loading: false,

                data: {
                    title: '',
                    text: '',
                    deadline: '',
                    worker_id: 0
                },

                tsk_id: 0,
                tasks_categories: [],
                users: [],
            }
        },
        mounted(){
            //
        },
        methods: {
            show_modal(task_id){
                this.is_modal = true
                this.task_id = task_id

                this.data = {
                    title: '',
                    text: '',
                    deadline: '',
                    worker_id: 0
                },

                this.get_all_tasks_category()
                this.get_users()

                this.show_task_detals(task_id)
            },
            close_modal(){
                this.is_modal = false
            },
            get_all_tasks_category(){
                axios
                .get("/get_task/task_category/get_all_task_categories/")
                .then(response => {
                    this.tasks_categories = response.data
                })
                .catch(
                    error => console.log(error)
                );
            }, 
            get_users(){
                axios
                .get("/get_user/get_worker_users/")
                .then(response => {
                    this.users = response.data
                })
                .catch(
                    error => console.log(error)
                );
            }, 
            show_task_detals(task_id){
                axios
                .get("/get_task/get_task_data/"+task_id)
                .then(response => {
                    this.data = response.data
                })
                .catch(
                    error => console.log(error)
                );
            },
            update_task(){
                this.is_loading = true
                axios
                .post('/set_task/update_task/'+this.task_id, {
                    data: this.data,

                    _method: 'Post'
                })
                .then(Response => {
                    this.$emit('restart')
                    this.close_modal()
                })
                .catch(error => console.log(error))
                .finally(() => this.is_loading = false);
            }
        }
    }
</script>

<style scoped>
.control-label{
    float: left;
}
</style>