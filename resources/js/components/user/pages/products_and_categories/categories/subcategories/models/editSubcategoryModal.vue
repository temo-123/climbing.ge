
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
                <form ref="editingForm" id="slider_iamge_edit_form" v-on:submit.prevent="edit_image(data.id)">
                    <div class="container">
                        <div class="form-group clearfix row">
                            <div class="col-md-12 image_add_modal_form">
                                <input type="text" name="title" class="form-control" placeholder="Us name" v-model="data.us_name" required>
                            </div>
                        </div>
                        <div class="form-group clearfix row">
                            <div class="col-md-12 image_add_modal_form">
                                <input type="text" name="title" class="form-control" placeholder="Ka name" v-model="data.ka_name" required>
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
                data: [],
                errors: [],
                is_edit_image_modal: false,
                // editing_image: [],
                is_loading: false
            }
        },
        mounted(){
            // 
        },
        methods: {
            show_modal(image_id){
                this.is_edit_image_modal = true
                
                this.get_actyve_sucategory(image_id)
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

            get_actyve_sucategory(id){
                this.is_loading = true
                axios
                .get("/subcategory/get_subcategory/"+id)
                .then(response => {
                    this.data = response.data
                })
                .catch(
                    // error => console.log(error)
                )
                .finally(() =>
                    this.is_loading = false
                );
            },

            edit_image(id){
                this.errors = []
                this.is_loading = true

                axios
                .post('/subcategory/edit_subcategory/' + id, {
                    data: this.data,
                })
                .then(response => {
                    this.$emit('update_list')
                    this.close_edit_image_modal(true)
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