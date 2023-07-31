<template>
    <stack-modal
            :show="user_modal"
            title="User info"
            @close="user_modal=false"
            :modal-class="{ [modalClass]: true }"
            :saveButton="{ visible: true, title: 'Save', btnClass: { 'btn btn-primary': true } }"
            :cancelButton="{ visible: false, title: 'Close', btnClass: { 'btn btn-danger': true } }"
        >
        <pre class="language-vue">
            <div>user info</div>
        </pre>
        <div slot="modal-footer">
            <div class="modal-footer">
                <!-- <button
                        type="button"
                        :class="{'btn btn-primary': true}"
                        @click="edit_permission(user_id_for_rditing_parmission)"
                    >
                Save
                </button> -->
            </div>
        </div>
    </stack-modal>
</template>

<script>
    import { SlickList, SlickItem } from 'vue-slicksort'; //https://github.com/Jexordexan/vue-slicksort
    import StackModal from '@innologica/vue-stackable-modal'  //https://innologica.github.io/vue-stackable-modal/#sample-css
    export default {
        components: {
            StackModal,
            SlickItem,
            SlickList,
        },
        data(){
            return {
                user_modal: false,
                user_new_parmission: '',
                is_parmision_error: false,
                parmision_error: [],
                modalClass: '',
            }
        },

        mounted() {
            
        },

        methods: {
            // user_modal(sector_id){
            //     this.user_modal=true
            // },

            get_user_info(id) {
               
            },
        }
    }
</script>

<style>

</style>