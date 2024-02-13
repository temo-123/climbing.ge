<template>
    <stack-modal
        :show="is_modal"
        title="Create task for worker"
        @close="is_modal=false"
        :saveButton="{ visible: true, title: 'Sand', btnClass: { 'btn btn-primary': true } }"
        :cancelButton="{ visible: false, title: 'Close', btnClass: { 'btn btn-danger': true } }"
    >
        <pre class="language-vue">
            <form id="create_task" v-on:submit.prevent="create_task" >
                <div class="container">

                    <div class="form-group clearfix">
                        <label for="name" class='col-xs-12 control-label'> titlee </label>
                        <div class="col-xs-12">
                            <input type="text" v-model="data.title" name="deadline" class="form-control" required> 
                        </div>
                    </div>

                    <div class="form-group clearfix">
                        <label for="name" class='col-xs-12 control-label'> Task </label>
                        <div class="col-xs-12">
                            <textarea rows="6" name="text" v-model="data.text" id="text" maxlength="500" placeholder="Task" class="form-control textarea"></textarea>
                        </div>
                    </div>

                    <div class="form-group clearfix">
                        <label for="name" class='col-xs-12 control-label'> Task deadline </label>
                        <div class="col-xs-12">
                            <input type="date" v-model="data.deadline" name="deadline" class="form-control" required> 
                        </div>
                    </div>

                    <div class="form-group clearfix">
                        <label for="name" class='col-xs-12 control-label'> Task category </label>
                        <div class="col-md-12 image_add_modal_form">
                            <select class="form-control" name="filter" v-model="data.category_id" required>
                                <option value="0" disabled>Select category</option> 

                                <option v-for="tasks_category in tasks_categories" :kay="tasks_category.id" :value="tasks_category.id">{{ tasks_category.title }}</option>
                            </select> 
                        </div>
                    </div>

                    <div class="form-group clearfix">
                        <label for="name" class='col-xs-12 control-label'> User </label>
                        <div class="col-md-12 image_add_modal_form">
                            <select class="form-control" name="filter" v-model="data.user_id" required>
                                <option value="0" disabled>Select user</option> 

                                <option v-for="user in users" :kay="user.id" :value="user.id">{{ user.name }} {{ user.surname }}</option>
                            </select> 
                        </div>
                    </div>

                </div>
            </form>
        </pre>
        <div slot="modal-footer">
            <div class="modal-footer">
                <button
                    type="submit"
                    form="create_task"
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

                data: {
                    text: '',
                    deadline: '',
                    worker_id: 0
                },

                tasks_categories: [],
                users: [],
            }
        },
        mounted(){
            this.get_all_tasks_category()
            this.get_users()
        },
        methods: {
            show_modal(){
                this.is_modal = true
            },
            get_all_tasks_category(){
                axios
                .get("/task/task_category/get_all_task_categories/")
                .then(response => {
                    this.tasks_categories = response.data
                })
                .catch(
                    error => console.log(error)
                );
            }, 
            get_users(){
                axios
                .get("/user/get_worker_users/")
                .then(response => {
                    this.users = response.data
                })
                .catch(
                    error => console.log(error)
                );
            }, 
            create_task(){
                axios
                .post('/task/create_task/', {
                    data: this.data,

                    _method: 'Post'
                })
                .then(Response => {
                    this.$emit('restart')
                    this.close_modal()
                })
                .catch(error => console.log(error))
            }
        }
    }
</script>

<style scoped>
.control-label{
    float: left;
}
</style>