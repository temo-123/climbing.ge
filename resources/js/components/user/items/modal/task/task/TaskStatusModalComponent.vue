<template>
    <stack-modal
        :show="is_modal"
        title="Task status"
        @close="is_modal=false"
        :saveButton="{ visible: true, title: 'Sand', btnClass: { 'btn btn-primary': true } }"
        :cancelButton="{ visible: false, title: 'Close', btnClass: { 'btn btn-danger': true } }"
    >
        <pre class="language-vue">

            <div class="container">
                <div class="row">
                    <div class="col-xs-12 modal_text">
                        <p>
                            task textt
                        </p>
                        <p>
                            dedline
                        </p>
                    </div>
                </div>
                <div class="row">
                    <form id="edit_task_status" class="modal_form" v-on:submit.prevent="edit_task_status" >

                        <div class="form-group clearfix">
                            <label for="name" class='col-xs-12 control-label'> Task for worker </label>
                            <div class="col-md-12 image_add_modal_form">
                                <select class="form-control" name="filter" v-model="data.status" required>
                                    <option value="" disabled>Task status</option> 

                                    <option value="in_process">In the process</option> 
                                    <option value="problem">A problem has occurred</option> 
                                    <option value="finished">Finished</option> 
                                </select> 
                            </div>
                        </div>

                    </form>
            </div>
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

                data: {
                    status: '',
                    task_id: 0
                }
            }
        },
        mounted(){
            // 
        },
        methods: {
            show_modal(){
                this.is_modal = true
            },
            edit_task_status(){
                
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