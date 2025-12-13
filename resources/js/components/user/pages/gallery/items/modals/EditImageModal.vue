
<template>
    <stack-modal
            :show="is_edit_image_modal"
            title="Edit image"
            @close="close_edit_image_modal()"
            :saveButton="{ visible: true, title: 'Save', btnClass: { 'btn btn-primary': true } }"
            :cancelButton="{ visible: false, title: 'Close', btnClass: { 'btn btn-danger': true } }"
        >
        <pre class="language-vue">
            <span v-show="is_loading">
                <img :src="'../../../public/images/site_img/loading.gif'" alt="loading">
            </span>
            <span v-show="!is_loading">
                <form ref="editingForm" id="slider_iamge_edit_form" v-on:submit.prevent="edit_image(editing_data.id)">
                    <div class="container">
                        
                        <div class="row">
                            <img :src="image_path_prop + editing_data.image" :alt="editing_data.title">
                        </div>
                        
                        <div class="form-group clearfix row">
                            <input type="file" name="image" id="image" value="image" v-on:change="onEditImageChange">
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
                                <select class="form-control" name="published" id="published" v-model="editing_data.published" required>
                                        <option value="0">Not public</option> 
                                        <option value="1">Public</option>
                                </select> 
                            </div>
                        </div>

                        <div class="form-group clearfix row">
                            <div class="col-md-12 image_add_modal_form">
                                <input type="text" name="title" class="form-control" placeholder="Title" v-model="editing_data.title" required>
                            </div>
                        </div>


                        <div class="form-group clearfix row">
                            <div class="col-md-12">
                                <textarea type="text" v-model="editing_data.text" name="text" rows="15" class="form-cotrol md-textarea form-control" required></textarea>
                            </div>
                        </div>

                        <div class="form-group clearfix row">
                            <div class="col-md-12 image_add_modal_form">
                                <input type="text" name="link" class="form-control" placeholder="Article Link" v-model="editing_data.link">
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
                    form="slider_iamge_edit_form"
                    :class="{'btn btn-primary': true}"
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
            'image_path_prop',
        ],
        data(){
            return{
                editing_data: [],
                errors: [],
                is_edit_image_modal: false,
                editing_image: [],
                is_loading: false
            }
        },
        mounted(){
            // 
        },
        methods: {
            show_modal(image_id){
                this.is_edit_image_modal = true
                
                this.get_actyve_image(image_id)
            },
            get_actyve_image(id){
                this.is_loading = true
                axios
                .get("/head_slider/get_actyve_slide/"+id)
                .then(response => {
                    this.editing_data = response.data
                })
                .catch(
                    // error => console.log(error)
                )
                .finally(() =>
                    this.is_loading = false
                );
            },

            close_edit_image_modal(action = false){
                if(!action){
                    if (window.confirm('Added information will be deleted!!! Are you sure, you want close modal?')) {
                        this.is_edit_image_modal = false
                        this.clear_input_data()
                    }
                }
                else{
                    this.is_edit_image_modal = false
                    this.clear_input_data()
                }
            },

            onEditImageChange(e){
                if(e.target.files.length > 0){
                    this.editing_image = e.target.files[0];
                }
                else if(e.target.files.length == 0){
                    this.editing_image = [];
                }
            },

            edit_image(id){
                this.errors = []
                this.is_loading = true

                this.category_id = ''

                let formData = new FormData();
                if(this.editing_image != [] || this.editing_image != null){
                    formData.append('image', this.editing_image);
                }
                formData.append('data', JSON.stringify(this.editing_data))

                axios
                .post('/set_head_slider/edit_slide/' + id, 
                    formData,
                )
                .then(response => {
                    this.is_edit_image_modal = false

                    this.$emit("update");
                })
                .catch(error => {
                    if (error.response.status == 422) {
                        this.errors = error.response.data
                    }
                })
                .finally(() => this.is_loading = false);
            },
        }
    }
</script>