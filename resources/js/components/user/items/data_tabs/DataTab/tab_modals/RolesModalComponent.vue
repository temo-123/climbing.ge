<template>
    <stack-modal
            :show="roles_modal"
            title="Edit roles"
            @close="roles_modal=false"
            :modal-class="{ [modalClass]: true }"
            :saveButton="{ visible: true, title: 'Save', btnClass: { 'btn btn-primary': true } }"
            :cancelButton="{ visible: false, title: 'Close', btnClass: { 'btn btn-danger': true } }"
        >
        <pre class="language-vue">
            <form>
                <select class="form-control" v-model="user_new_parmission"> 
                    <option value="user">User</option> 
                    <option value="ru_menager">English contrnt menager</option> 
                    <option value="ru_menager">Russian contrnt menager</option> 
                    <option value="ka_menager">Georgian contrnt menager</option> 
                    <option value="manager">Content manager</option>
                    <option value="seller">Seller</option>  
                    <option value="admin">Admin</option> 
                </select>
                <!-- <div class="alert alert-danger" role="alert" v-if="is_parmision_error"> -->
                <p class="alert alert-danger" role="alert" v-if="is_parmision_error">
                    {{ parmision_error.parmission[0] }}
                </p>
            </form>
        </pre>
        <div slot="modal-footer">
            <div class="modal-footer">
                <button
                        type="button"
                        :class="{'btn btn-primary': true}"
                        @click="edit_permission(user_id_for_rditing_parmission)"
                    >
                Save
                </button>
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
                roles_modal: false,
                user_new_parmission: '',
                is_parmision_error: false,
                parmision_error: [],
                modalClass: '',
            }
        },

        mounted() {
            
        },

        methods: {
            // roles_modal(sector_id){
            //     this.roles_modal=true
            // },

            edit_permission(id) {
                axios
                .post('users/edit_user_permission/' + id, {
                    parmission: this.user_new_parmission,
                })
                .then((response)=> { 
                    this.roles_modal = false
                })
                .catch(error =>{
                    if (error.response.status == 422) {
                        this.parmision_error = error.response.data.errors
                    }
                    this.is_parmision_error = true
                })
            },
        }
    }
</script>

<style>

</style>