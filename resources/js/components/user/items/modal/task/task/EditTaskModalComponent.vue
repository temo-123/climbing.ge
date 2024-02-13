<template>
    <stack-modal
        :show="is_modal"
        title="Edit worker task"
        @close="is_modal=false"
        :saveButton="{ visible: true, title: 'Sand', btnClass: { 'btn btn-primary': true } }"
        :cancelButton="{ visible: false, title: 'Close', btnClass: { 'btn btn-danger': true } }"
    >
        <pre class="language-vue">
            <form id="create_task" v-on:submit.prevent="edit_task" >
                <div class="container">

                    <div class="form-group clearfix">
                        <label for="name" class='col-xs-12 control-label'> Task </label>
                        <div class="col-xs-12">
                            <!-- <input type="text" v-model="data.task" name="Task" class="form-control" required>  -->
                            <textarea rows="6" name="text" v-model="data.task" id="text" maxlength="500" placeholder="Task" class="form-control textarea"></textarea>
                            <!-- <div class="alert alert-danger" role="alert" v-if="errors.ka_name">
                                {{ errors.ka_name[0] }}
                            </div> -->
                        </div>
                    </div>

                    <div class="form-group clearfix">
                        <label for="name" class='col-xs-12 control-label'> Task deadline </label>
                        <div class="col-xs-12">
                            <input type="date" v-model="data.deadline" name="deadline" class="form-control" required> 
                            <!-- <div class="alert alert-danger" role="alert" v-if="errors.ka_name">
                                {{ errors.ka_name[0] }}
                            </div> -->
                        </div>
                    </div>

                    <div class="form-group clearfix">
                        <label for="name" class='col-xs-12 control-label'> Task for worker </label>
                        <div class="col-md-12 image_add_modal_form">
                            <select class="form-control" name="filter" v-model="data.worker_id" required>
                                <option value="0" disabled>Select worker</option> 

                                <option value="favorite_outdoor">favorite_outdoor</option>

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
                    task: '',
                    deadline: '',
                    worker_id: 0
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
            edit_task(){

                // this.$emit('restart')
            }
        }
    }
</script>

<style scoped>
.control-label{
    float: left;
}
</style>