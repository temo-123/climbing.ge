<template>
    <stack-modal
            :show="is_show_add_modal"
            :title="'Add product brand'"
            @close="close_modal()"
            :saveButton="{ visible: true, title: 'Save', btnClass: { 'btn btn-primary': true } }"
            :cancelButton="{ visible: false, title: 'Close', btnClass: { 'btn btn-danger': true } }"
        >
        <pre class="language-vue">
            <h1>Add product brand'</h1>

            <div class="row justify-content-center" v-show="is_loading">
                <div class="col-md-4">
                    <img :src="'../../../../../../public/images/site_img/loading.gif'" alt="loading">
                </div>
            </div>

            <div class="roe" v-show="!is_loading">
                <div class="tabs"> 

                    <div class="container">
                        <div class="row">
                            <div class="col">
                                <input type="radio" id="M1" :value="1" v-model="modal_tab_num">
                                
                                <label for="M1" >English</label>
                            </div>
                            <div class="col">
                                <input type="radio" id="M2" :value="2" v-model="modal_tab_num">
                                
                                <label for="M2" >Georgian</label>
                            </div>
                        </div>

                        <div class="row" v-if="modal_tab_num == 1">
                            <input type="text" class="form-control" v-model="data.us_brand.title" name="English name" id="English name" placeholder="Enter English name" title="Enter English name">
                            <textarea name='text' class="form-control mt-1 textarea" v-model="data.us_brand.short_description"></textarea>
                        </div>

                        <div class="row" v-if="modal_tab_num == 2">
                            <input type="text" class="form-control" v-model="data.ka_brand.title" name="Georgian name" id="Georgian name" placeholder="Enter Georgian name" title="Enter English name">
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
        </pre>
        <div slot="modal-footer">
            <div class="modal-footer">
                <button
                    type="button"
                    :class="{'btn btn-primary': true}"
                    @click="add_product_brand()"
                >
                Create
                </button>
            </div>
        </div>
    </stack-modal>
</template>

<script>
    import StackModal from '@innologica/vue-stackable-modal'  //https://innologica.github.io/vue-stackable-modal/#sample-css

    import validator_alerts_component from '../../../validator_alerts_component.vue'
    export default {
        components: {
            StackModal,
            validator_alerts_component
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
                is_show_add_modal: false,
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
                    if (window.confirm('Added information will be deleted!!! Are you sure, you want go back?')) {
                        this.is_back_action_query = false;
                        this.clear_form_data()
                    }
                }else {
                    this.is_show_add_modal = false
                    this.clear_form_data()
                }
                
            },
            show_modal(){
                this.clear_form_data()

                this.is_show_add_modal = true
            },
            clear_form_data(){
                this.data = {
                    global_brand: {},
                    us_brand: {
                        title: '',
                        short_description: ''
                    },
                    ka_brand: {
                        title: '',
                        short_description: ''
                    }
                }
            },
            add_product_brand: function(){
                this.is_loading = true,

                axios
                .post("/brand/create_brand/", {
                    data: this.data,

                    _method: 'Post'
                })
                .then(response => {
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