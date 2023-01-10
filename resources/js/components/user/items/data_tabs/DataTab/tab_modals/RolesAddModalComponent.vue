<template>
    <stack-modal
            :show="is_role_add_modal"
            title="Add new role"
            @close="close_role_add_modal()"
            :saveButton="{ visible: true, title: 'Save', btnClass: { 'btn btn-primary': true } }"
            :cancelButton="{ visible: false, title: 'Close', btnClass: { 'btn btn-danger': true } }"
        >
        <pre class="language-vue">
            <form v-on:submit.prevent="add_role" id="add_role_form">
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

            <button type="button" class="btn btn-primary float-left" @click="add_permission_value()">Add new permission</button>

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
                                    <option v-for="permission in permissions" :key="permission.id" :value="permission.id">{{ permission.name }}</option> 
                                </select>
                            </form> 
                        </td>
                        <td>|</td>
                        <td>
                            <button type="button" class="btn btn-danger" @click="del_bisnes_value(permission.id)">Delete</button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </pre>
        <div slot="modal-footer">
            <div class="modal-footer">
                <button
                    type="submit"
                    :class="{'btn btn-primary': true}"
                    form="add_role_form"
                >
                Save role
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
            // 'table_info',
        ],
        data(){
            return{
                is_role_add_modal: false,

                permissions: [],
                permissions_array: [],

                role_data: {
                    name: '',
                    description: ''
                },
            }
        },
        mountid(){
        },
        methods: {
            open_role_add_modal(user_id){
                this.is_role_add_modal = true
                this.action_user = user_id
                this.get_permissions()
            },
            close_role_add_modal(){
                this.is_role_add_modal = false
                this.action_user = ''
                this.roles = []
                this.permissions_array = []
                this.user_role = ''
            },

            add_role(){
                axios
                .post("../api/role/create_role/", {
                    role_data: this.role_data,
                    new_permissions: this.permissions_array,
                })
                .then(response => {
                    this.close_role_add_modal()
                })
                .catch(
                    error => console.log(error)
                );
            },

            get_permissions(){
                axios
                .get("../api/parmisions_list/")
                .then(response => {
                    this.permissions = response.data
                })
                .catch(
                    error => console.log(error)
                );
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