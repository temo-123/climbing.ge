<template>
    <tr>
        <td :style='"text-align: center;"'>
            <input type="checkbox">
        </td>
        <td>|</td>
        <td>{{table_info.id}}</td>

        <td>|</td>
        <td>{{ table_info.code }}</td>

        <td>|</td>
        <td>{{ table_info.action_data }}</td>
        
        <td>|</td>
        <td>
            <button type="submit" class="btn btn-primary" @click="sale_code_control_modal('edit', table_info.id)">Edit</button>
        </td>
        
        <td>|</td>
        <td>
            <button type="submit" class="btn btn-danger" @click="del_sale_code(table_info.id)">Delete</button>
        </td>

        <stack-modal
                :show="is_sale_code_control_modal"
                :title="action_title"
                @close="close_modal()"
                :saveButton="{ visible: true, title: 'Save', btnClass: { 'btn btn-primary': true } }"
                :cancelButton="{ visible: false, title: 'Close', btnClass: { 'btn btn-danger': true } }"
            >
            <pre class="language-vue">
                <h1>{{ action_title }}</h1>

                <p>Discount</p>
                <input type="number" class="form-control" v-model="form_data.discount" name="Discount" id="Discount" placeholder="Enter Discount" title="Enter Discount" min="1" max="50">
                <p>Sale code</p>
                <input type="text" class="form-control" v-model="form_data.sale_code" name="Sale code" id="Sale code" placeholder="Enter Sale code" title="Enter Sale code">
                <p>
                    Generatid code automaticly
                    <input type="checkbox" name="Generatid code automaticly" v-model="auto_generation"  @click="generatid_code">
                </p>
                
                <p>Code validity date</p>
                <input type="datetime-local" class="form-control" v-model="form_data.validity_date" name="Validity date" value="2017-06-01T08:30" /> 

                <p>
                    One time code
                    <input type="checkbox" v-model="form_data.one_time_code" name="One time code" value="One time code">
                </p>
            </pre>
            <div slot="modal-footer">
                <div class="modal-footer" v-if="action_title == 'Add sale_code'">
                    <button
                        type="button"
                        :class="{'btn btn-primary': true}"
                        @click="add_sale_code()"
                    >
                    Add
                    </button>
                </div>
                <div class="modal-footer" v-if="action_title == 'Edit sale_code'">
                    <button
                        type="button"
                        :class="{'btn btn-primary': true}"
                        @click="edit_sale_code(table_info.id)"
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
                is_sale_code_control_modal: false,

                action_title: '',
                
                auto_generation: false,

                form_data: {
                    discount: "",
                    sale_code: "",
                    validity_date: "",
                    one_time_code: "",
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
            sale_code_control_modal(action, id = 0){
                this.is_sale_code_control_modal = true

                if(action == 'edit'){
                    this.action_title = 'Edit sale_code'
                    this.get_editing_sale_code_data(id)
                }
                if(action == 'add'){
                    this.action_title = 'Add sale_code'
                }
            },
            close_modal(){
                this.is_sale_code_control_modal = false
                this.action_title = ''

                this.form_data = {
                    discount: "",
                    sale_code: "",
                    validity_date: "",
                    one_time_code: "",
                }
                this.auto_generation = false
            },
            del_sale_code(id){
                if(confirm('Are you sure, you want delite it?')){
                    axios
                    .post('../../api/sale_code/'+id, {
                        _method: 'DELETE'
                    })
                    .then(Response => {
                        this.$emit('restart')
                    })
                    .catch(error => console.log(error))
                }
            },

            add_sale_code(){
                axios
                .post('../../api/sale_code/', {
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

            edit_sale_code(sale_code_id){
                axios
                .post('../../../api/sale_code/' + sale_code_id, {        
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

            get_editing_sale_code_data(sale_code_id){
                axios
                .get("../api/sale_code/"+sale_code_id)
                .then(response => {
                    // this.quick_comment = response.data
                    this.form_data = {
                        discount: response.data.discount,
                        sale_code: response.data.code,
                        validity_date: response.data.action_data,
                        one_time_code: response.data.one_time_code,
                    }
                    this.auto_generation = false
                })
                .catch(
                    error => console.log(error)
                );
            },
            generatid_code(){
                if(!this.auto_generation){
                    const characters ='ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';

                    let result = ' ';
                    const charactersLength = characters.length;
                    for ( let i = 0; i < 7; i++ ) {
                        result += characters.charAt(Math.floor(Math.random() * charactersLength));
                    }

                    this.form_data.sale_code = result
                }
                else{
                    this.form_data.sale_code = ''
                }
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