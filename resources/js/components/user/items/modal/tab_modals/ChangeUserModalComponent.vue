<template>
    <stack-modal
            :show="is_modal_show"
            :title="$t('admin.users.change_user_title')"
            @close="close_modal"
            :modal-class="{ [modalClass]: true }"
            :saveButton="{ visible: relation_status === 'single_relation', title: $t('common.save'), btnClass: { 'btn btn-primary': true } }"
            :cancelButton="{ visible: true, title: $t('common.close'), btnClass: { 'btn btn-danger': true } }"
        >
        <div>
            <!-- Single user relation - Show active user info -->
            <div v-if='relation_status === "single_relation" && actyve_user && Object.keys(actyve_user).length > 0'>
                <div class="alert alert-warning" role="alert">
                    {{ $t('admin.users.active_user_prefix') }} {{ actyve_user.name }} {{ actyve_user.surname }} (#{{ actyve_user.id }})
                </div>
            </div>

            <!-- No user relation - Show message -->
            <div v-if='relation_status === "no_relation"'>
                <div class="alert alert-danger" role="alert">
                    {{ $t('admin.users.no_user_relation_msg') }}
                </div>
            </div>

            <!-- Multiple user relations - Show error with fix button -->
            <div v-if='relation_status === "multiple_relations"'>
                <div class="alert alert-danger" role="alert">
                    <strong>{{ $t('admin.users.error_prefix') }}</strong> {{ $t('admin.users.multiple_relations_error_msg', { count: duplicate_users.length }) }}<br>
                    {{ $t('admin.users.please_fix_to_continue') }}
                </div>
                <div class="alert alert-info" role="alert">
                    <strong>{{ $t('admin.users.users_with_relations_prefix') }}</strong>
                    <ul>
                        <li v-for="(user, index) in duplicate_users" :key="user.id">
                            {{ user.name }} {{ user.surname }} (#{{ user.id }})
                        </li>
                    </ul>
                </div>
            </div>

            <form v-on:submit.prevent="update_relation" id="change_user_form" class="form">
                <select class="form-control" v-model="selected_user_id" name="comment delete cause" required>
                    <option value="" disabled>{{ $t('admin.users.select_user_placeholder') }}</option>
                    <option v-for="user in users" :key="user.id" :value="user.id">{{ user.name }} {{ user.surname }}</option>
                </select>
            </form>
        </div>
        <div slot="modal-footer">
            <div class="modal-footer">
                <!-- Fix button for multiple relations -->
                <button
                    v-if="relation_status === 'multiple_relations'"
                    type="button"
                    @click="fix_user_relation"
                    class="btn btn-warning"
                >
                    <i class="fa fa-wrench" aria-hidden="true"></i> {{ $t('admin.users.fix_it_btn') }}
                </button>
                <!-- Save button for single relation -->
                <button
                    v-if="relation_status === 'single_relation'"
                    type="submit"
                    form="change_user_form"
                    :class="{'btn btn-primary': true}"
                >
                    {{ $t('common.save') }}
                </button>
                <!-- Close button -->
                <button
                    type="button"
                    @click="close_modal"
                    :class="{'btn btn-secondary': true}"
                >
                    {{ $t('common.close') }}
                </button>
            </div>
        </div>
    </stack-modal>
</template>

<script>
    // import StackModal from '@innologica/vue-stackable-modal'  // Global now
    export default {
        emits: ['update'],
        components: {
            // StackModal,
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
                if(confirm(this.$t('admin.users.confirm_fix_user_relations'))){
                    axios
                    .post('/set_product/fix_product_user_relation/' + this.actyve_product_id)
                    .then(response => {
                        const data = response.data;
                        if(data.status === 'success'){
                            alert(this.$t('admin.users.user_relations_fixed_success'));
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
                if(confirm(this.$t('admin.users.confirm_change_user'))){
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