<template>
    <stack-modal
        :show="is_modal"
        title="Task status"
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
            <div class="container" v-show="!is_loading">
                <div class="row">
                    <div class="col-xs-12 modal_text">
                        <p>{{ task.title }}</p>
                        <p>{{ task.deadline }}</p>
                        <p>{{ task.text }}</p>
                        <p>{{ task.status }}</p>
                        <hr>
                        <p>category - {{ task.category_id }}</p>
                        <p>user - {{ task.user_id }}</p>
                    </div>
                </div>
            </div>
        </pre>
        <div slot="modal-footer">
            <!-- <div class="modal-footer">
                <button
                    type="submit"
                    form="edit_task_status"
                    :class="{'btn btn-primary': true}"
                >
                Update
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
                is_loading: false,

                task: []
            }
        },
        mounted(){
            // 
        },
        methods: {
            show_modal(task_id){
                this.is_modal = true

                this.show_task_detals(task_id)
            },
            close_modal(){
                this.is_modal = false
                this.task = []
            },
            show_task_detals(task_id){
                this.is_loading = true
                axios
                .get("/task/get_task_data/"+task_id)
                .then(response => {
                    this.task = response.data
                })
                .catch(
                    error => console.log(error)
                )
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