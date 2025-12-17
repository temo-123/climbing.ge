<template>
    <stack-modal
            :show="is_order_sale_code_add_model"
            title="Add order sale_code"
            @close="close_modal"
            :saveButton="{ visible: true, title: 'Save', btnClass: { 'btn btn-primary': true } }"
            :cancelButton="{ visible: false, title: 'Close', btnClass: { 'btn btn-danger': true } }"
        >
        <pre class="language-vue">
            <h1>Add shiped sale_code</h1>
            <form v-on:submit.prevent="add_sale_code" id="add_sale_code">
                <input type="text" class="form-control" v-model="data.code" name="sale code" placeholder="Code" title="Code" required>

                <button
                    type="botton"
                    :class="{'btn btn-primary': true}"
                    @click="generate_code()"
                >
                Generation Random code
                </button>

                <input type="number" max="100" min="1" class="form-control" v-model="data.discount" name="discount" placeholder="Discount" title="Discount" required> %
                <input type="checkbox" id="scales" v-model="data.one_time_code" name="scales" placeholder="One time code" title="One time code"> One time cde
                <input type="datetime-local" class="form-control" v-model="data.action_data" name="action_data" placeholder="Action_data" title="Action_data" v-if="!data.one_time_code">
            </form>
        </pre>
        <div slot="modal-footer">
            <div class="modal-footer">
                <button
                    type="submit"
                    form="add_sale_code"
                    :class="{'btn btn-primary': true}"
                >
                Add sale_code
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
        props: [
            'table_info',
        ],
        mountid(){
            // console.log(this.table_info)
        },
        data(){
            return{
                is_order_sale_code_edit_model: false,
                is_order_sale_code_add_model: false,

                data: {
                    code: '',
                    discount: '',
                    one_time_code: null,
                    action_data: ''
                }
            }
        },
        methods: {
            generate_code(){
                var code = '';
                var randomchar = function() {
                    var n = Math.floor(Math.random() * 62);
                    if (n < 10) return n; //1-10
                    if (n < 36) return String.fromCharCode(n + 55); //A-Z
                    return String.fromCharCode(n + 61); //a-z
                }
                while (code.length < 6){
                    code += randomchar();
                }

                this.data.code = code
            },
            add_sale_code(){
                    axios
                    .post('/set_sale_code/', {
                        data: this.data,

                        _method: 'Post'
                    })
                    .then(Response => {
                        alert('Code added successfully')
                        this.$emit('update')
                        this.is_order_sale_code_add_model = false
                        this.clear_form()
                    })
                    .catch(error => console.log(error))
            },
            clear_form(){
                this.data = {
                    code: '',
                    discount: '',
                    one_time_code: null,
                    action_data: ''
                }
            },
            show_modal(){
                this.is_order_sale_code_add_model = true
            },
            close_modal(){
                if(confirm('Are you sure, you want close form? All data whil deleted!')){
                    this.is_order_sale_code_add_model = false
                    this.clear_form()
                }
            }
        }
    }
</script>

<style>

</style>