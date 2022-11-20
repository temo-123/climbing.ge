<template>
    <tr>
        <td :style='"text-align: center;"'>
            <input type="checkbox">
        </td>
        <td>|</td>
        <td>{{table_info.id}}</td>

        <td>|</td>
        <td>{{ table_info.us_name }}</td>
        
        <td>|</td>
        <td>
            <button type="submit" class="btn btn-primary" @click="tag_control_modal('edit', table_info.id)">Edit</button>
        </td>
        
        <td>|</td>
        <td>
            <button type="submit" class="btn btn-danger" @click="del_ted(table_info.id)">Delete</button>
        </td>

        <stack-modal
                :show="is_tag_control_modal"
                :title="action_title"
                @close="close_modal()"
                :saveButton="{ visible: true, title: 'Save', btnClass: { 'btn btn-primary': true } }"
                :cancelButton="{ visible: false, title: 'Close', btnClass: { 'btn btn-danger': true } }"
            >
            <pre class="language-vue">
                <h1>{{ action_title }}</h1>


                <input type="text" class="form-control" v-model="form_data.us_name" name="English name" id="English name" placeholder="Enter English name" title="Enter English name">
                <input type="text" class="form-control" v-model="form_data.ka_name" name="Georgian name" id="Georgian name" placeholder="Enter Georgian name" title="Enter Georgian name">
                <input type="text" class="form-control" v-model="form_data.ru_name" name="Russian name" id="Russian name" placeholder="Enter Russian name" title="Enter Russian name">                
            </pre>
            <div slot="modal-footer">
                <div class="modal-footer" v-if="action_title == 'Add tag'">
                    <button
                        type="button"
                        :class="{'btn btn-primary': true}"
                        @click="add_tag()"
                    >
                    Add
                    </button>
                </div>
                <div class="modal-footer" v-if="action_title == 'Edit tag'">
                    <button
                        type="button"
                        :class="{'btn btn-primary': true}"
                        @click="edit_tag(table_info.id)"
                    >
                    Update
                    </button>
                </div>
            </div>
        </stack-modal>
    </tr>
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
                is_tag_control_modal: false,

                action_title: '',

                form_data: {
                    us_name: "",
                    ka_name: "",
                    ru_name: "",
                }
            }
        },
        props: [
            'table_info',
        ],
        mounted(){
            // console.log(table_info)
            // if(this.table_info.deleted_reason != null){
            //     this.danger_color = 'row_deanger'
            // }
            // else{
            //     this.danger_color = ''
            // }
        },
        methods: {
            tag_control_modal(action, id = 0){
                this.is_tag_control_modal = true

                if(action == 'edit'){
                    this.action_title = 'Edit tag'
                    this.get_editing_tag_data(id)
                }
                if(action == 'add'){
                    this.action_title = 'Add tag'
                }
            },
            close_modal(){
                this.is_tag_control_modal = false
                this.action_title = ''

                this.form_data = {
                    us_name: "",
                    ka_name: "",
                    ru_name: "",
                }
            },
            del_ted(id){
                if(confirm('Are you sure, you want delite it?')){
                    axios
                    .post('../../api/film_tags/'+id, {
                        _method: 'DELETE'
                    })
                    .then(Response => {
                    })
                    .catch(error => console.log(error))
                }
            },

            add_tag(){
                axios
                .post('../../api/film_tags/', {
                    data: this.form_data,
                    _method: 'POST'
                })
                .then(response => {
                    this.close_modal()
                })
                .catch(
                    //
                );
            },

            edit_tag(tag_id){
                axios
                .post('../../../api/film_tags/' + tag_id, {        
                    editing_data: this.form_data,
                    _method: 'PATCH'
                })
                .then(response => {
                    this.close_modal()
                })
                .catch(
                    error => console.log(error)
                );
            },

            get_editing_tag_data(tag_id){
                axios
                .get("../api/film_tags/"+tag_id)
                .then(response => {
                    // this.quick_comment = response.data
                    this.form_data = {
                        us_name: response.data.us_name,
                        ka_name: response.data.ka_name,
                        ru_name: response.data.ru_name,
                    }
                })
                .catch(
                    error => console.log(error)
                );
            }
        }
    }
</script>

<style>
    .row_deanger td{
        background-color: #df8d8d;
    }
    .row_worning td {
        background-color: #dfad8d;
    }
</style>