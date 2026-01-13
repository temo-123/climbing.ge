<template>
    <stack-modal
            :show="is_modal_show"
            title="Change user"
            @close="close_modal"
            :modal-class="{ [modalClass]: true }"
            :saveButton="{ visible: relation_status === 'single_relation', title: 'Save', btnClass: { 'btn btn-primary': true } }"
            :cancelButton="{ visible: true, title: 'Close', btnClass: { 'btn btn-danger': true } }"
        >
        <pre class="language-vue">
            <!-- Single user relation - Show active user info -->
            <div v-if='relation_status === "single_relation" && actyve_user && Object.keys(actyve_user).length > 0'>
                <div class="alert alert-warning" role="alert">
                    Active user - {{ actyve_user.name }} {{ actyve_user.surname }} (#{{ actyve_user.id }})
                </div>
            </div>
            
            <!-- No user relation - Show message -->
            <div v-if='relation_status === "no_relation"'>
                <div class="alert alert-danger" role="alert">
                    This product doesn`t have user relation
                </div>
            </div>
            
            <!-- Multiple user relations - Show error with fix button -->
            <div v-if='relation_status === "multiple_relations"'>
                <div class="alert alert-danger" role="alert">
                    <strong>Error:</strong> This product has multiple user relations ({{ duplicate_users.length }} users found).<br>
                    Please fix this to continue.
                </div>
                <div class="alert alert-info" role="alert">
                    <strong>Users with relations:</strong>
                    <ul>
                        <li v-for="(user, index) in duplicate_users" :key="user.id">
                            {{ user.name }} {{ user.surname }} (#{{ user.id }})
                        </li>
                    </ul>
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
                <!-- Fix button for multiple relations -->
                <button
                    v-if="relation_status === 'multiple_relations'"
                    type="button"
                    @click="fix_user_relation"
                    class="btn btn-warning"
                >
                    <i class="fa fa-wrench" aria-hidden="true"></i> Fix it
                </button>
                <!-- Save button for single relation -->
                <button
                    v-if="relation_status === 'single_relation'"
                    type="submit"
                    form="change_user_form"
                    :class="{'btn btn-primary': true}"
                >
                    Save
                </button>
                <!-- Close button -->
                <button
                    type="button"
                    @click="close_modal"
                    :class="{'btn btn-secondary': true}"
                >
                    Close
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
                actyve_product_id: 0,
                relation_status: '', // 'no_relation', 'single_relation', 'multiple_relations'
                duplicate_users: []
            }
        },
        mounted() {
            // 
        },
        methods: {
            grt_all_users(){
                axios
                .get("/get_user/get_all_users/")
                .then(response => {
                    this.users = response.data
                })
                .catch(
                    error => console.log(error)
                );
            },
            
            /**
             * Get user relation for a specific product
             * Handles three cases:
             * - no_relation: Product has no user relations
             * - single_relation: Product has one user (normal case)
             * - multiple_relations: Product has duplicate relations (error case)
             */
            get_product_user_relation(product_id){
                axios
                .get("/set_product/get_product_user_relation/" + product_id)
                .then(response => {
                    const data = response.data;
                    this.relation_status = data.status;
                    
                    if(data.status === 'no_relation'){
                        this.actyve_user = '';
                        this.selected_user_id = '';
                        this.duplicate_users = [];
                    }
                    else if(data.status === 'single_relation'){
                        this.actyve_user = data.data;
                        this.selected_user_id = data.data.id;
                        this.duplicate_users = [];
                    }
                    else if(data.status === 'multiple_relations'){
                        this.actyve_user = '';
                        this.selected_user_id = '';
                        this.duplicate_users = data.data;
                    }
                })
                .catch(
                    error => console.log(error)
                );
            },
            
            /**
             * Fix duplicate user relations by keeping only the first one
             */
            fix_user_relation(){
                if(confirm('Are you sure you want to fix the duplicate user relations? This will keep only the first user and remove the others.')){
                    axios
                    .post('/set_product/fix_product_user_relation/' + this.actyve_product_id)
                    .then(response => {
                        const data = response.data;
                        if(data.status === 'success'){
                            alert('User relations fixed successfully!');
                            // Refresh the relation data
                            this.get_product_user_relation(this.actyve_product_id);
                            // Emit update event to refresh parent component
                            this.$emit('update');
                        }
                    })
                    .catch(error => console.log(error))
                }
            },
            
            show_modal(event) {
                this.grt_all_users();
                
                // Handle both array format [user_id, product_id] and direct product_id
                let product_id;
                if(Array.isArray(event)){
                    product_id = event[1] || event[0];
                } else {
                    product_id = event;
                }
                
                if(product_id && product_id != 0){
                    this.actyve_product_id = product_id;
                    this.get_product_user_relation(product_id);
                    this.is_modal_show = true;
                }
            },
            close_modal() {
                this.selected_user_id = 0;
                this.actyve_product_id = 0;
                this.actyve_user = '';
                this.relation_status = '';
                this.duplicate_users = [];
                this.is_modal_show = false;
            },

            update_relation(){
                if(confirm('Are you sure, you want change user?')){
                    let data;
                    if(this.actyve_user && Object.keys(this.actyve_user).length > 0){
                        data = {
                            "product_id": this.actyve_product_id,
                            "new_user_id": this.selected_user_id,
                            "old_user_id": this.actyve_user.id
                        };
                    }
                    else{
                        data = {
                            "product_id": this.actyve_product_id,
                            "new_user_id": this.selected_user_id,
                            "old_user_id": null
                        };
                    }
                    
                    axios
                    .post('/set_product/change_user_relation/',{
                        data: data
                    })
                    .then(Response => {
                        this.$emit('update');
                        this.close_modal();
                    })
                    .catch(error => console.log(error));
                }
            },
        }
    }
</script>

<style>

</style>