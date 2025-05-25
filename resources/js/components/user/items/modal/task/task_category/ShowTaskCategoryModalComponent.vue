<template>
    <stack-modal
        :show="is_modal"
        title="Task"
        @close="is_modal=false"
        :saveButton="{ visible: true, title: 'Sand', btnClass: { 'btn btn-primary': true } }"
        :cancelButton="{ visible: false, title: 'Close', btnClass: { 'btn btn-danger': true } }"
    >
        <pre class="language-vue">
            <div class="container">
                <div class="row"> 
                    <div class="col-xs-12 modal_text">
                        <p>{{ task_cat.title }}</p>
                        <p>{{ task_cat.text }}</p>
                    </div>
                </div>
            </div>
        </pre>
        <div slot="modal-footer">
            <!-- <div class="modal-footer">
                <button type="button" :class="{'btn btn-primary': true}" @click="finish_task">
                    Accepting the finished task
                </button>
            </div> -->
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

                task_cat: []
            }
        },
        mounted(){
            // 
        },
        methods: {
            show_modal(task_category_id){
                this.is_modal = true

                this.finish_task(task_category_id)
            },
            finish_task(task_category_id){
                axios
                .get("/task/task_category/get_task_category_data/"+task_category_id)
                .then(response => {
                    this.task_cat = response.data
                })
                .catch(
                    error => console.log(error)
                );
            }
        }
    }
</script>

<style scoped>

</style>