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
                            <input type="file" name="image" id="image" value="image" v-on:change="onAddImageChange" required>
                        </div>
                        <div class="row">
                            <div class="col-md-12" v-if="errors.length != 0">
                                <div class="alert alert-danger" role="alert" v-if="errors.image">
                                    {{ errors.image[0] }}
                                </div>
                            </div>
                        </div>

                        <div class="form-group clearfix row">
                            <div class="col-md-12 image_add_modal_form">
                                <select class="form-control" name="published" id="published" v-model="form_data.published"  required>
                                        <option value="0">Not public</option> 
                                        <option value="1">Public</option>
                                </select> 
                            </div>
                        </div>

                        <div class="form-group clearfix row">
                            <div class="col-md-12 image_add_modal_form">
                                <input type="text" name="title" class="form-control" placeholder="Title"  v-model="form_data.title" required>
                            </div>
                        </div>


                        <div class="form-group clearfix row">
                            <div class="col-md-12">
                                <textarea type="text"  name="text" rows="15" class="form-cotrol md-textarea form-control"  v-model="form_data.text"></textarea>
                            </div>
                        </div>

                        <div class="form-group clearfix row">
                            <div class="col-md-12 image_add_modal_form">
                                    <input type="text" name="link" class="form-control" placeholder="Article Link"  v-model="form_data.link">
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
        data(){
            return{
                user: [],
                MIX_SITE_URL: process.env.MIX_SITE_URL,
                MIX_APP_SSH: process.env.MIX_APP_SSH,

                is_add_image_modal: false,
                errors: [],
                is_loading: false,

                form_data: {
                    published: 0,
                    category: ''
                }
            }
        },
        mounted(){
            // 
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
            
            show_modal(category){
                this.clear_input_data()
                this.form_data.category = category
                this.is_add_image_modal = true
            },

            clear_input_data(){
                this.errors = []

                this.form_data = {
                    published: 0,
                    category: '',

                    title: '',
                    text: '',
                    link: '',
                }
            },

            onAddImageChange(e){
                this.image = e.target.files[0];
            },

            add_image(){
                this.is_loading = true
                this.errors = []

                let formData = new FormData();
                formData.append('image', this.image);
                formData.append('data', JSON.stringify(this.form_data))


                axios
                .post('/head_slider/add_slide/', 
                    formData,
                )
                .then(response => {
                    this.is_add_image_modal = false
                    this.$emit("update");
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
