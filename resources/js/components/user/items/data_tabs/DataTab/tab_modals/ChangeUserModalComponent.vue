<template>
    <stack-modal
            :show="is_modal_show"
            title="Change user"
            @close="close_modal"
            :modal-class="{ [modalClass]: true }"
            :saveButton="{ visible: true, title: 'Save', btnClass: { 'btn btn-primary': true } }"
            :cancelButton="{ visible: false, title: 'Close', btnClass: { 'btn btn-danger': true } }"
        >
        <pre class="language-vue">
            <!-- {{ active_user_prop[0] }} -->
            <div v-if='active_user_prop.length > []'>
                <div class="alert alert-warning" role="alert">
                    Actyve user - {{ active_user_prop[0].name }} {{ active_user_prop[0].surname }} (#{{ active_user_prop[0].id }})
                </div>
            </div>
            <div v-if='active_user_prop.length == []'>
                <div class="alert alert-danger" role="alert">
                    This product don`t have user relation
                </div>
            </div>
            <form v-on:submit.prevent="update_relation" id="change_user_form" class="form">
                <select class="form-control" v-model="selected_user_id" name="comment delete cause" required> 
                    <option value="" disabled>Select user</option>
                    <option v-for="user in users" :key="user.id" :value="user.id">{{ user.name }} {{ user.surname }}</option>
                </select>
            </form>
        </pre>
        <div slot="modal-footer">
            <div class="modal-footer">
                <button
                    type="submit"
                    form="change_user_form"
                    :class="{'btn btn-primary': true}"
                >
                Save
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
        props: [
            'active_user_prop',
        ],
        data(){
            return {
                selected_user_id: '',
                users: [],
                modalClass: '',
                is_modal_show: false,
            }
        },
        mounted() {
            // 
        },
        methods: {
            update_relation(){
                this.$emit('update_relation', this.selected_user_id)
            },
            grt_all_users(){
                axios
                .get("/users/")
                .then(response => {
                    this.users = response.data
                })
                .catch(
                    error => console.log(error)
                );
            },
            show_modal() {
                this.grt_all_users() 
                if(this.active_user_prop.length != 0){
                    this.selected_user_id = this.active_user_prop[0].id
                }
                this.is_modal_show = true
            },
            close_modal() {
                this.selected_user_id = '',
                this.is_modal_show = false
            },
        }
    }
</script>

<style>

</style>