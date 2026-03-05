<template>
    <stack-modal
        :show="is_add_image_modal"
        title="Add image"
        @close="close_add_image_modal()"
        :saveButton="{ visible: true, title: 'Save', btnClass: { 'btn btn-primary': true } }"
        :cancelButton="{ visible: false, title: 'Close', btnClass: { 'btn btn-danger': true } }"
    >
        <pre class="language-vue">
            <span v-show="is_loading">
                <img :src="'../../../public/images/site_img/loading.gif'" alt="loading">
            </span>
            <span v-show="!is_loading">
                <form ref="slider_image_add_form" id="slider_image_add_form" v-on:submit.prevent="add_image">
                    <div class="container">

                        <div class="form-group clearfix row">
                            <div class="col-md-12 image_add_modal_form">
                                <input type="text" name="title" class="form-control" placeholder="us name"  v-model="data.us_name" required>
                            </div>
                        </div>

                        <div class="form-group clearfix row">
                            <div class="col-md-12 image_add_modal_form">
                                <input type="text" name="title" class="form-control" placeholder="ka name"  v-model="data.ka_name" required>
                            </div>
                        </div>

                    </div>
                </form>
            </span>
        </pre>
        <div slot="modal-footer">
            <div class="modal-footer">
                <button
                    type="submit"
                    :class="{'btn btn-primary': true}"
                    form="slider_image_add_form"
                >
                Save
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
            'category_id_prop'
        ],
        data(){
            return{
                user: [],
                MIX_SITE_URL: process.env.MIX_SITE_URL,
                MIX_APP_SSH: process.env.MIX_APP_SSH,

                is_add_image_modal: false,
                errors: [],
                is_loading: false,

                category_id: this.category_id_prop,

                data: {
                    us_name: '',
                    ka_name: ''
                }
            }
        },
        watch: {
            category_id_prop: function(){
                this.category_id = this.category_id_prop
            },
        },
        mounted(){
            // this.data.category = this.category_prop
        },
        methods: {
            close_add_image_modal(action = false){
                if(!action){
                    if (window.confirm('Added information will be deleted!!! Are you sure, you want close modal?')) {
                        this.is_add_image_modal = false
                        this.clear_input_data()
                    }
                }
                else{
                    this.is_add_image_modal = false
                    this.clear_input_data()
                }
            },
            
            show_modal(){
                this.clear_input_data()
                this.is_add_image_modal = true
            },

            clear_input_data(){
                this.errors = []

                this.data = {

                    us_name: '',
                    ka_name: '',
                }
            },

            onAddImageChange(e){
                this.image = e.target.files[0];
            },

            add_image(){
                this.is_loading = true
                axios
                .post('/set_product/set_product_category/set_subcategory/add_subcategory/' + this.category_id, {        
                    data: this.data,
                })
                .then(response => {
                    this.$emit('update_list')

                    this.is_add_image_modal = false
                })
                .catch(error => {
                    if (error.response.status == 422) {
                        this.errors = error.response.data
                    }
                })
                .finally(() =>
                    this.is_loading = false
                )
            },
        }
    }
</script>
