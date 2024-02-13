<template>
    <stack-modal
        :show="is_modal"
        title="Task"
        @close="is_modal=false"
        :saveButton="{ visible: true, title: 'Sand', btnClass: { 'btn btn-primary': true } }"
        :cancelButton="{ visible: false, title: 'Close', btnClass: { 'btn btn-danger': true } }"
    >
        <pre class="language-vue">
            <div class="row">

            </div>
        </pre>
        <div slot="modal-footer">
            <div class="modal-footer">
                <button type="button" :class="{'btn btn-primary': true}" @click="finish_task">
                    Accepting the finished task
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
                is_modal: false
            }
        },
        mounted(){
            // 
        },
        methods: {
            show_modal(){
                this.is_modal = true
            },
            finish_task(){
                if(confirm('Are you sure, this task is fineshed?')){

                }
            }
        }
    }
</script>

<style scoped>

</style>