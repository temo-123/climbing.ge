<template>
    
    <stack-modal
                :show="is_order_sale_code_add_model"
                :title="$t('admin.shop.edit_sale_code_title')"
                @close="close_modal()"
                :saveButton="{ visible: true, title: $t('common.save'), btnClass: { 'btn btn-primary': true } }"
                :cancelButton="{ visible: false, title: $t('common.close'), btnClass: { 'btn btn-danger': true } }"
            >
            <div>
                <p>{{ $t('admin.shop.discount_label') }}</p>
                <input type="number" class="form-control" v-model="form_data.discount" name="Discount" id="Discount" :placeholder="$t('admin.shop.enter_discount_placeholder')" :title="$t('admin.shop.enter_discount_placeholder')" min="1" max="50">
                <p>{{ $t('admin.shop.sale_code_label') }}</p>
                <input type="text" class="form-control" v-model="form_data.sale_code" name="Sale code" id="Sale code" :placeholder="$t('admin.shop.enter_sale_code_placeholder')" :title="$t('admin.shop.enter_sale_code_placeholder')">

                <button
                    type="botton"
                    :class="{'btn btn-primary': true}"
                    @click="generate_code()"
                >
                {{ $t('admin.shop.generate_random_code_btn') }}
                </button>

                <p>{{ $t('admin.shop.code_validity_date_label') }}</p>
                <input type="datetime-local" class="form-control" v-model="form_data.validity_date" name="Validity date" value="2017-06-01T08:30" />

                <p>
                    {{ $t('admin.shop.one_time_code') }}
                    <input type="checkbox" v-model="form_data.one_time_code" name="One time code" value="One time code">
                </p>
            </div>
            <div slot="modal-footer">
                <div class="modal-footer">
                    <button
                        type="button"
                        :class="{'btn btn-primary': true}"
                        @click="edit_sale_code(form_data.id)"
                    >
                    {{ $t('admin.task.update_btn') }}
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
                // is_order_sale_code_edit_model: false,
                is_order_sale_code_add_model: false,

                form_data: {
                    id: '',
                    code: '',
                    discount: '',
                    one_time_code: null,
                    action_data: ''
                },
                auto_generation: false
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

                this.form_data.sale_code = code
            },
            close_modal(){
                this.is_order_sale_code_add_model = false

                this.form_data = {
                    discount: "",
                    sale_code: "",
                    validity_date: "",
                    one_time_code: "",
                }

                this.auto_generation = false
            },
            show_modal(id){
                this.get_editing_sale_code_data(id)
            },

            edit_sale_code(sale_code_id){
                axios
                .post('/set_product/set_sale_code/edit_sale_code/' + sale_code_id, {        
                    editing_data: this.form_data,
                    _method: 'POST'
                })
                .then(response => {
                    this.close_modal()
                    this.$emit('update')
                })
                .catch(
                    error => console.log(error)
                );
            },

            get_editing_sale_code_data(sale_code_id){
                axios
                .get("/set_product/set_sale_code/get_editing_sale_code/" + sale_code_id)
                .then(response => {
                    // this.quick_comment = response.data
                    this.form_data = {
                        id: sale_code_id,
                        discount: response.data.discount,
                        sale_code: response.data.code,
                        validity_date: response.data.action_data,
                        one_time_code: response.data.one_time_code,
                    }
                    this.auto_generation = false
                    this.is_order_sale_code_add_model = true
                })
                .catch(
                    error => console.log(error)
                );
            },
        }
    }
</script>

<style>

</style>