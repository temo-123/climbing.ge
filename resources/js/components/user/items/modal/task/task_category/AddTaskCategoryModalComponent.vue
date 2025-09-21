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
                <input type="text" v-model="data.title" title="dedline" class="form-control" placeholder="Title / Name" required> 
            
                <textarea rows="6" name="text" v-model="data.short_description" id="text" maxlength="500" placeholder="Description" class="form-control textarea"></textarea>
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