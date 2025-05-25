<template>
    <stack-modal
        :show="is_modal"
        title="Create task category"
        @close="is_modal=false"
        :saveButton="{ visible: true, title: 'Sand', btnClass: { 'btn btn-primary': true } }"
        :cancelButton="{ visible: false, title: 'Close', btnClass: { 'btn btn-danger': true } }"
    >
        <pre class="language-vue">
            <form id="create_task_category" v-on:submit.prevent="create_task_category" >
                <div class="container">

                    <div class="form-group clearfix">
                        <label for="title" class='col-xs-12 control-label'> Title </label>
                        <div class="col-xs-12">
                            <input type="text" v-model="data.title" title="dedline" class="form-control" required> 
                        </div>
                    </div>

                    <div class="form-group clearfix">
                        <label for="name" class='col-xs-12 control-label'> Short description </label>
                        <div class="col-xs-12">
                            <textarea rows="6" name="text" v-model="data.short_description" id="text" maxlength="500" placeholder="Task" class="form-control textarea"></textarea>
                        </div>
                    </div>

                </div>
            </form>
        </pre>
        <div slot="modal-footer">
            <div class="modal-footer">
                <button
                    type="submit"
                    form="create_task_category"
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
                    title: '',
                    short_description: '',
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
            close_modal(){
                this.is_modal = false
            },
            create_task_category(){
                axios
                    .post('/task/task_category/create_task_category/', {
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