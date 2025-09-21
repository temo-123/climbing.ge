<template>
    <stack-modal
        :show="is_modal"
        title="Task status"
        @close="close_modal()"
        :saveButton="{ visible: true, title: 'Sand', btnClass: { 'btn btn-primary': true } }"
        :cancelButton="{ visible: false, title: 'Close', btnClass: { 'btn btn-danger': true } }"
    >
        <pre class="language-vue">
            <div class="container" v-show="is_loading">
                <div class="row justify-content-center">
                    <div class="col-md-4">
                        <img :src="'../../../../../../public/images/site_img/loading.gif'" alt="loading">
                    </div>
                </div>
            </div>

            <div class="container" v-show="!is_loading">
               
                <h1>{{ task.title }}</h1>

                <form id="edit_task_status" class="modal_form" v-on:submit.prevent="edit_task_status" >
                    <select class="form-control" name="filter" v-model="data.status" required>
                        <option value="" disabled>Task status</option> 

                        <option value="in_process">In the process</option> 
                        <option value="problem">A problem has occurred</option> 
                        <option value="finished">Finished</option> 
                    </select> 
                
                    <textarea v-if="data.status == 'problem'" rows="6" name="worker_comment" v-model="data.worker_comment" id="worker_comment" maxlength="500" placeholder="Task" class="form-control textarea" required></textarea>

                    <textarea v-if="data.status != 'problem'" rows="6" name="worker_comment" v-model="data.worker_comment" id="worker_comment" maxlength="500" placeholder="Task" class="form-control textarea"></textarea>
                </form>
            </div>
        </pre>
        <div slot="modal-footer">
            <div class="modal-footer">
                <button
                    type="submit"
                    form="edit_task_status"
                    :class="{'btn btn-primary': true}"
                >
                Update
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
                    status: '',
                },

                task_id: 0,

                task: []
            }
        },
        mounted(){
            // 
        },
        methods: {
            show_modal(task_id){
                this.is_modal = true

                this.task_id = task_id

                this.show_task_detals(task_id)
            },
            close_modal(){
                this.is_modal = false
                this.task = []
            },
            show_task_detals(task_id){
                axios
                .get("/task/get_task_data/"+task_id)
                .then(response => {
                    this.data.status = response.data.status
                    this.data.worker_comment = response.data.worker_comment
                })
                .catch(
                    error => console.log(error)
                );
            },
            edit_task_status(){
                this.is_loading = true
                axios
                .post('/task/update_task_status/'+this.task_id, {
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
.control-label, .modal_text{
    float: left;
}
.modal_form{
    width: 100%;
}
</style>