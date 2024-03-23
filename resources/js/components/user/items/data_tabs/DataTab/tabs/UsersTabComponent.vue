<template>
    <tr>
        <td :style="'text-align: center'">
            <input type="checkbox">
        </td>
        <td>|</td>
        <td>{{table_info.id}}</td>
        <td>|</td>

        <td>{{table_info.name}} {{table_info.surname}}</td>

        <td>|</td>
        <td>{{table_info.email}}</td>
        
        <td>|</td>
        <td>
            <!-- user role (beta) -->
            <button class="btn btn-primary" @click="open_role_editing_modal(table_info.id)" v-if="$can('edit_permissions', 'user')">Edit roles</button>
        </td>
        
        <!-- <td>|</td>
        <td>
            <button type="submit" class="btn btn-warning" @click="open_create_ban_modal()" v-if="$can('create_ban', 'user')">Create Ban (beta)</button>
        </td> -->
        
        <td>|</td>
        <td>
            <button type="submit" class="btn btn-danger" @click="del_user(table_info.id)" v-if="$can('del', 'user')"><i class="fa fa-trash" aria-hidden="true"></i></button>
        </td>

        <stack-modal
                :show="is_role_editing_modal"
                title="Edit role & permissions"
                @close="close_role_editing_modal()"
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
                    <h2>User role</h2>

                    <select class="form-control" v-model="user_role" v-if="!role_loading"> 
                        <option value="no_role" disabled>Select user role</option> 
                        <option v-for="role in roles" :key="role.id" :value="role.id">{{ role.name }}</option> 
                    </select>

                    <div class="row justify-content-center" v-if="role_loading">
                        <div class="col-md-4">
                            <img :src="'../../../public/images/site_img/loading.gif'" alt="loading">
                        </div>
                    </div>

                    <h2>Additional permissions</h2>

                    <h3 v-if="user_permissions.length != 0">Alredy addid</h3>
                    <table v-if="user_permissions.length != 0" class="table table-hover" id="dev-table">
                        <thead>
                            <tr>
                                <th>Image</th>
                                <th>|</th>
                                <th>Delite</th>
                            </tr>
                        </thead>

                        <tbody>
                            <tr v-for="permission in user_permissions" :key="permission.id">
                                <td>
                                    {{ permission.subject }} {{ permission.action }}
                                </td>
                                <td>|</td>
                                <td>
                                    <button type="button" class="btn btn-danger" @click="del_user_pemisino_from_db(permission.id)"><i class="fa fa-trash" aria-hidden="true"></i></button>
                                </td>
                            </tr>
                        </tbody>
                    </table>

                    <h3>Add More Permissions</h3>

                    <button type="button" class="btn btn-primary float-left" @click="add_permission_value()">Add new permission</button>

                    <table class="table table-hover" id="dev-table" v-if="!perm_loading">
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
                    <div class="row justify-content-center" v-if="perm_loading">
                        <div class="col-md-4">
                            <img :src="'../../../public/images/site_img/loading.gif'" alt="loading">
                        </div>
                    </div>
                </span>
            </pre>
            <div slot="modal-footer">
                <div class="modal-footer">
                    <button
                        v-show="!is_loading"
                        type="button"
                        :class="{'btn btn-primary': true}"
                        @click="edit_permissions(table_info.id)"
                    >
                    Save prermissions
                    </button>
                </div>
            </div>
        </stack-modal>

        <stack-modal
                :show="is_ban_modal"
                title="Create ban on this user"
                @close="close_create_ban_modal()"
                :saveButton="{ visible: true, title: 'Save', btnClass: { 'btn btn-primary': true } }"
                :cancelButton="{ visible: false, title: 'Close', btnClass: { 'btn btn-danger': true } }"
            >
            <pre class="language-vue">
                <select class="form-control" name="comment delete cause" > 
                    <option value="Ban time" disabled>Ban time</option>
                    <option value="0.5 year">0.5 year</option>
                    <option value="1 year">1 year</option>
                    <option value="2 year">2 year</option>
                </select> 
            </pre>
            <div slot="modal-footer">
                <div class="modal-footer">
                    <button
                        type="button"
                        :class="{'btn btn-primary': true}"
                        @click="edit_permissions(table_info.id)"
                    >
                    Create ban
                    </button>
                </div>
            </div>
        </stack-modal>
    </tr>
</template>

<script>
    import StackModal from '@innologica/vue-stackable-modal'  //https://innologica.github.io/vue-stackable-modal/#sample-css
    export default {
        components: {
            StackModal,
        },
        props: [
            'table_info',
        ],
        data(){
            return{
                is_ban_modal: false,
                is_role_editing_modal: false,

                perm_loading: false,
                role_loading: false,
                is_loading: false,

                user_role: 'no_role',
                user_permissions: [],

                roles: [],
                permissions: [],

                permissions_array: [],

                action_user: ''
            }
        },
        mountid(){
        },
        methods: {
            create_ban(id){
                if(confirm('Are you sure, you want delite it?')){
                    // axios
                    // .post('../../api/del_country/'+id, {
                    //     id: id,
                    //     _method: 'DELETE'
                    // })
                    // .then(Response => {
                    //     this.$emit('restart')
                    // })
                    // .catch(error => console.log(error))
                }
            },
            open_create_ban_modal(){
                this.is_ban_modal = true
            },
            close_create_ban_modal(){
                this.is_ban_modal = false
            },


            open_role_editing_modal(user_id){
                this.is_role_editing_modal = true
                this.action_user = user_id
                this.get_roles()
                this.get_permissions()
                this.get_user_permissions_and_roles(user_id)
            },
            close_role_editing_modal(){
                this.is_role_editing_modal = false
                this.action_user = ''
                this.roles = []
                this.permissions_array = []
                this.user_role = 'no_role'
            },

            del_user(user_id){
                if(confirm('Are you sure, you want delite it?')){
                    axios
                    .post('../../api/user/del_user/'+user_id, {
                        _method: 'DELETE'
                    })
                    .then(Response => {
                        if(Response.data != 'You don`t can delete yourself! :)'){
                            alert(Response.data);
                            this.$emit('restart')
                        }
                        else if(Response.data == 'You don`t can delete yourself! :)'){
                            alert(Response.data);
                        }
                    })
                    .catch(error => console.log(error))
                }
            },

            del_user_pemisino_from_db(id){
                if(confirm('Are you sure, you want delite it?')){
                    axios
                    .post('../../api/role/del_user_pemisino/'+id+'/'+this.action_user, {
                        _method: 'DELETE'
                    })
                    .then(Response => {
                        this.get_user_permissions_and_roles(this.action_user)
                    })
                    .catch(error => console.log(error))
                }
            },

            edit_permissions(){
                this.is_loading = true
                axios
                .post("/role/edit_permissions_and_role/"+this.action_user, {
                    new_permissions: this.permissions_array,
                    role: this.user_role,
                })
                .then(response => {
                    this.close_role_editing_modal()
                })
                .catch(
                    error => console.log(error)
                )
                .finally(() => this.is_loading = false);
            },

            get_user_permissions_and_roles(){
                axios
                .get("/role/get_user_permissions/"+this.action_user)
                .then(response => {
                    
                    if(response.data.role.length != 0){
                        this.user_role  = response.data.role.id
                    }

                    if(response.data.permissions.length != 0){
                        this.user_permissions = response.data.permissions
                    }
                })
                .catch(
                    error => console.log(error)
                );
            },
                
            get_roles(){
                this.role_loading = true

                axios
                .get("/role/")
                .then(response => {
                    this.roles = response.data
                })
                .catch(
                    error => console.log(error)
                )
                .finally(() => this.role_loading = false);
            },

            get_permissions(){
                this.perm_loading = true

                axios
                .get("/parmisions_list/")
                .then(response => {
                    this.permissions = response.data
                })
                .catch(
                    error => console.log(error)
                )
                .finally(() => this.perm_loading = false);
            },


            onFileChange(event, item_id){
                let permisson = event.target.value
                let id = item_id - 1 
                this.permissions_array[id]['permission_id'] = permisson
            },
            add_permission_value(){
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

<style>

</style>