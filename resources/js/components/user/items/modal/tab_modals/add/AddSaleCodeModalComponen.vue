<template>
    <stack-modal
            :show="is_order_sale_code_add_model"
            :title="$t('admin.shop.add_sale_code_title')"
            @close="close_modal"
            :saveButton="{ visible: true, title: $t('common.save'), btnClass: { 'btn btn-primary': true } }"
            :cancelButton="{ visible: false, title: $t('common.close'), btnClass: { 'btn btn-danger': true } }"
        >
        <div>
            <h1>{{ $t('admin.shop.add_sale_code_title') }}</h1>
            <form v-on:submit.prevent="add_sale_code" id="add_sale_code">
                <input type="text" class="form-control" v-model="data.code" name="sale code" :placeholder="$t('admin.shop.code_label')" :title="$t('admin.shop.code_label')" required>

                <button
                    type="botton"
                    :class="{'btn btn-primary': true}"
                    @click="generate_code()"
                >
                {{ $t('admin.shop.generate_random_code_btn') }}
                </button>

                <input type="number" max="100" min="1" class="form-control" v-model="data.discount" name="discount" :placeholder="$t('admin.shop.discount_label')" :title="$t('admin.shop.discount_label')" required> %
                <input type="checkbox" id="scales" v-model="data.one_time_code" name="scales" :placeholder="$t('admin.shop.one_time_code')" :title="$t('admin.shop.one_time_code')"> {{ $t('admin.shop.one_time_code') }}
                <input type="datetime-local" class="form-control" v-model="data.action_data" name="action_data" :placeholder="$t('admin.shop.action_data')" :title="$t('admin.shop.action_data')" v-if="!data.one_time_code">
            </form>
        </div>
        <div slot="modal-footer">
            <div class="modal-footer">
                <button
                    type="submit"
                    form="add_sale_code"
                    :class="{'btn btn-primary': true}"
                >
                {{ $t('admin.shop.add_sale_code_btn') }}
                </button>
            </div>
        </div>
    </stack-modal>
</template>

<script>
    import { SlickList, SlickItem } from 'vue-slicksort'; //https://github.com/Jexordexan/vue-slicksort
    // import StackModal from '@innologica/vue-stackable-modal'  // Global now
    export default {
        emits: ['update'],
    components: {
            // StackModal,
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
                    .post('/set_product/set_sale_code/add_sale_code/', {
                        data: this.data,

                        _method: 'Post'
                    })
                    .then(Response => {
                        alert(this.$t('admin.shop.code_added_successfully'))
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
                if(confirm(this.$t('admin.live_camera.confirm_close_form_discard'))){
                    this.is_order_sale_code_add_model = false
                    this.clear_form()
                }
            }
        }
    }
</script>

<style>

</style>