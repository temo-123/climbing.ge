<template>
    <stack-modal
            :show="is_show_edit_modal"
            :title="$t('admin.shop.edit_product_brand_title')"
            @close="close_modal()"
            :saveButton="{ visible: true, title: $t('common.save'), btnClass: { 'btn btn-primary': true } }"
            :cancelButton="{ visible: false, title: $t('common.close'), btnClass: { 'btn btn-danger': true } }"
        >
        <div>
            <h1>{{ $t('admin.shop.edit_product_brand_title') }}</h1>

            <div class="row justify-content-center" v-show="is_loading">
                <div class="col-md-4">
                    <img :src="'/images/site_img/loading.gif'" alt="loading">
                </div>
            </div>

            <div class="roe" v-show="!is_loading">
                <div class="tabs">

                    <div class="container">
                        <div class="row">
                            <div class="col">
                                <input type="radio" id="M1" :value="1" v-model="modal_tab_num">

                                <label for="M1" >{{ $t('admin.shop.english_tab_label') }}</label>
                            </div>
                            <div class="col">
                                <input type="radio" id="M2" :value="2" v-model="modal_tab_num">

                                <label for="M2" >{{ $t('admin.shop.georgian_tab_label') }}</label>
                            </div>
                        </div>

                        <div class="row" v-if="modal_tab_num == 1">
                            <input type="text" class="form-control" v-model="data.us_brand.title" name="English name" id="English name" :placeholder="$t('admin.shop.enter_english_name_placeholder')" :title="$t('admin.shop.enter_english_name_placeholder')">
                            <textarea name='text' class="form-control mt-1 textarea" v-model="data.us_brand.short_description"></textarea>
                        </div>

                        <div class="row" v-if="modal_tab_num == 2">
                            <input type="text" class="form-control" v-model="data.ka_brand.title" name="Georgian name" id="Georgian name" :placeholder="$t('admin.shop.enter_georgian_name_placeholder')" :title="$t('admin.shop.enter_georgian_name_placeholder')">
                            <textarea name='text' class="form-control mt-1 textarea" v-model="data.ka_brand.short_description"></textarea>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row" v-show="!is_loading">
                <validator_alerts_component
                    :errors_prop="error"
                />
            </div>
        </div>
        <div slot="modal-footer">
            <div class="modal-footer">
                <button
                    type="button"
                    :class="{'btn btn-primary': true}"
                    @click="edit_product_brand()"
                >
                {{ $t('admin.shop.save_change_btn') }}
                </button>
            </div>
        </div>
    </stack-modal>
</template>

<script>
    // import StackModal from '@innologica/vue-stackable-modal'  // Global now

    // import validator_alerts_component from '../../../validator_alerts_component.vue'
    export default {
        emits: ['update'],
    components: {
            // StackModal,
            // validator_alerts_component
        },
    
        data() {
            return {
                data: {
                    global_brand: {},
                    us_brand: {
                        title: '',
                        short_description: ''
                    },
                    ka_brand: {
                        title: '',
                        short_description: ''
                    }
                },
                modal_tab_num: 1,
                actyve_id: 0,
                is_show_edit_modal: false,
                error: [],
                is_loading: false,
            }
        },

        mounted() {
            //
        },
         
        methods: {
            close_modal(is_back_action_query = false){
                if(is_back_action_query){
                    if (window.confirm(this.$t('admin.shop.confirm_close_edited_info'))) {
                        this.is_back_action_query = false;
                        this.clear_form_data()
                    }
                }else {
                    this.is_show_edit_modal = false
                    this.clear_form_data()
                }
                
            },
            show_modal(id){
                this.get_editing_brand_data(id)

                this.actyve_id = id

                this.is_show_edit_modal = true
            },
            // clear_form_data(){
            //     this.data = {
            //         global_brand: {},
            //         us_brand: {
            //             title: '',
            //             short_description: ''
            //         },
            //         ka_brand: {
            //             title: '',
            //             short_description: ''
            //         }
            //     }
            // },
            get_editing_brand_data(id){
                axios
                .get('/set_product/set_brand/get_editing_brand/' + id)
                .then(response => {                    
                    this.data = {
                        global_brand: {},
                        us_brand: {
                            title: response.data.us_brand.title,
                            short_description: response.data.us_brand.short_description
                        },
                        ka_brand: {
                            title: response.data.ka_brand.title,
                            short_description: response.data.ka_brand.short_description,
                        }
                    }
                })
                .catch(
                    // error => console.log(error)
                )
                .finally(() => this.is_loading = false);
            },
            edit_product_brand: function(){
                this.is_loading = true,

                axios
                .post("/set_product/set_brand/edit_brand/" + this.actyve_id, {
                    data: this.data,

                    _method: 'Post'
                })
                .then(response => {
                    alert(this.$t('admin.shop.edited_successfully'))
                    this.update()
                    this.close_modal()
                })
                .catch(error => {
                    if (error.response.status == 422) {
                        this.error = error.response.data.validation
                    }
                })
                .finally(
                    this.is_loading = false,
                )
            },
            update(){
                this.$emit('update')
            }
        }
    }
</script>