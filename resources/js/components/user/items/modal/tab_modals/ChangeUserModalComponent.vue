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
            <div v-if='actyve_user != []'>
                <div class="alert alert-warning" role="alert">
                    Actyve user - {{ actyve_user.name }} {{ actyve_user.surname }} (#{{ actyve_user.id }})
                </div>
            </div>
            <div v-if='actyve_user.length == []'>
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
            // 'actyve_user',
        ],
        data(){
            return {
                selected_user_id: '',
                users: [],
                actyve_user: '',
                modalClass: '',
                is_modal_show: false,
                actyve_product_id: 0
            }
        },
        mounted() {
            // 
        },
        methods: {
            grt_all_users(){
                axios
                .get("/user/get_all_users/")
                .then(response => {
                    this.users = response.data
                })
                .catch(
                    error => console.log(error)
                );
            },
            get_user_actyve_data(user_id){
                axios
                .get("/user/get_user_data/" + user_id)
                .then(response => {
                    this.actyve_user = response.data
                })
                .catch(
                    error => console.log(error)
                );
            },
            show_modal(event) {
                this.grt_all_users() 
                if(event[0] != 0){
                    this.get_user_actyve_data(event[0])
                    this.selected_user_id = event[0]
                    this.actyve_product_id = event[1]

                    this.is_modal_show = true
                }
            },
            close_modal() {
                this.selected_user_id = 0,
                this.is_modal_show = false
            },

            update_relation(){
                if(confirm('Are you sure, you want change user?')){
                    let data
                    if(this.actyve_user.length != 0){
                        data = {
                            "product_id": this.actyve_product_id,
                            "new_user_id": this.selected_user_id,
                            "old_user_id": this.actyve_user.id
                        }
                    }
                    else{
                        data = {
                            "product_id": this.actyve_product_id,
                            "new_user_id": this.selected_user_id,
                            "old_user_id": []
                        }
                    }
                    
                    axios
                    .post('/product/change_user_relation/',{
                        data: data
                    })
                    .then(Response => {
                        this.$emit('update')
                        this.close_modal()
                    })
                    .catch(error => console.log(error))
                }
            },
        }
    }
</script>

<style>

</style>