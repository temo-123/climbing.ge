<template>
    <stack-modal
            :show="is_role_edit_modal"
            title="Edit role permissions"
            @close="close_role_edit_modal()"
            :saveButton="{ visible: true, title: 'Save', btnClass: { 'btn btn-primary': true } }"
            :cancelButton="{ visible: false, title: 'Close', btnClass: { 'btn btn-danger': true } }"
        >
        <pre class="language-vue">
            <span v-show="is_loading">
                <div class="col-md-4">
                    <img :src="'../../../../../../public/images/site_img/loading.gif'" alt="loading">
                </div>
            </span>
            <span v-show="!is_loading">
                <form v-on:submit.prevent="edit_role" id="edit_role_form">
                    <input
                        type="text"
                        class="form-control"
                        id="name"
                        v-model="role_data.name"
                        placeholder="Enter demo name"
                        required
                    />
                    
                    <input
                        type="text"
                        class="form-control"
                        id="short description"
                        v-model="role_data.description"
                        placeholder="Enter short description"
                        required
                    />
                </form>

                <table class="table table-hover" id="dev-table">
                    <thead>
                        <tr>
                            <th>Subject</th>
                            <th>|</th>
                            <th>Action</th>
                            <th>|</th>
                            <th>Delite</th>
                        </tr>
                    </thead>

                    <tbody>
                        <tr v-for="permission in role_permissions" :key="permission.id">
                            <td>
                                {{ permission.subject }}
                            </td>
                            <td>|</td>
                            <td>
                                {{ permission.action }}
                            </td>
                            <td>|</td>
                            <td>
                                <button type="button" class="btn btn-danger" @click="del_role_permission_from_db(permission.id)"><i class="fa fa-trash" aria-hidden="true"></i></button>
                            </td>
                        </tr>
                    </tbody>
                </table>

                <button type="button" class="btn btn-primary float-left" @click="edit_permission_value()">Add new permission</button>

                <table class="table table-hover" id="dev-table">
                    <thead>
                        <tr>
                            <th>Image</th>
                            <th>|</th>
                            <th>Delite</th>
                        </tr>
                    </thead>

                    <tbody>
                        <tr v-for="permission in permissions_array" :key="permission.id">
                            <td>
                                <form ref="myForm">
                                    <select class="form-control" v-on:change="onFileChange($event, permission.id)">> 
                                        <option disabled selected>Select permission</option> 
                                        <option v-for="permission in permissions" :key="permission.id" :value="permission.id">{{ permission.subject }} {{ permission.action }}</option> 
                                    </select>
                                </form> 
                            </td>
                            <td>|</td>
                            <td>
                                <button type="button" class="btn btn-danger" @click="del_bisnes_value(permission.id)"><i class="fa fa-trash" aria-hidden="true"></i></button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </span>
        </pre>
        <div slot="modal-footer">
            <div class="modal-footer">
                <button
                    type="button"
                    :class="{'btn btn-primary': true}"
                    @click="edit_role()"
                >
                Save updated role
                </button>
            </div>
        </div>
    </stack-modal>
</template>


<script>
    // import tabsComponent  from '../items/data_table/TabsComponent.vue'

    // import breadcrumb from '../items/BreadcrumbComponent.vue'
    import StackModal from '@innologica/vue-stackable-modal'  //https://innologica.github.io/vue-stackable-modal/#sample-css
    export default {
        components: {
            StackModal,
            // tabsComponent ,
            // breadcrumb,
        },
        data(){
            return {
                is_ban_modal: false,
                is_role_edit_modal: false,
                is_loading: false,

                role: '',
                role_permissions: [],

                role_data: {
                    name: '',
                    description: ''
                },

                roles: [],
                permissions: [],

                permissions_array: [],

                action_role: '',

                role_id: 0,
            }
        },
        mounted() {
            // this.get_users()
        },
        watch: {
            // '$route' (to, from) {
            //     this.data_for_tab = [],
            //     this.get_users()
            //     window.scrollTo(0,0)
            // }
        },
        methods: {
            // show_modal(id){

            // }

            show_modal(role_id){
                // alert(role_id)
                this.is_role_edit_modal = true
                this.action_role = role_id
                this.get_editing_role(role_id)
                this.get_editing_role_permissions(role_id)
                this.get_permissions(role_id)
                this.role_id = role_id
                // this.get_user_permissions_and_roles(user_id)
            },
            close_role_edit_modal(){
                this.is_role_edit_modal = false
                this.action_role = ''
                this.roles = []
                this.permissions_array = []
                this.role = ''
            },


            edit_role(){
                this.is_loading = true
                axios
                .post("/role/edit_role/" + this.role_id, {
                    new_permissions: this.permissions_array,
                    role: this.role_data,
                })
                .then(response => {
                    this.close_role_edit_modal()
                })
                .catch(
                    error => console.log(error)
                )
                .finally(() => this.is_loading = false);
            },

            get_editing_role(role_id){
                axios
                .get("/role/get_editing_role/" + role_id)
                .then(response => {
                    this.role_data = {
                        name: response.data.name,
                        description: response.data.description
                    }
                })
                .catch(
                    error => console.log(error)
                );
            },

            get_editing_role_permissions(role_id){
                axios
                .get("/role/get_editing_role_permissions/" + role_id)
                .then(response => {
                    this.role_permissions = response.data
                })
                .catch(
                    error => console.log(error)
                );
            },

            get_permissions(role_id){
                axios
                .get("/permission/get_parmisions_for_role/" + role_id)
                .then(response => {
                    this.permissions = response.data
                })
                .catch(
                    error => console.log(error)
                );
            },

            del_role_permission_from_db(id){
                if(confirm('Are you sure, you want delite it?')){
                    axios
                    .post('../../api/role/del_role_permission/' + this.action_role + '/' + id, {
                        _method: 'DELETE'
                    })
                    .then(Response => {
                        this.get_editing_role_permissions(this.action_role)
                    })
                    .catch(error => console.log(error))
                }
            },


            onFileChange(event, item_id){
                // console.log("🚀 ~ file: UsersTabComponent.vue:269 ~ onFileChange ~ event", event.target.value)
                let permisson = event.target.value
                let id = item_id - 1 
                this.permissions_array[id]['permission_id'] = permisson
            },
            edit_permission_value(){
                var new_item_id = this.permissions_array.length+1
                this.permissions_array.push(
                    {
                        id: new_item_id,
                        permission_id: ''
                    }
                );
            },

            del_bisnes_value(id){
                this.removeObjectWithId(this.permissions_array, id);
            },
            removeObjectWithId(arr, id) {
                const objWithIdIndex = arr.findIndex((obj) => obj.id === id);
                arr.splice(objWithIdIndex, 1);

                return arr;
            },
        }
    }
</script>