
<template>
    <stack-modal
            :show="is_edit_image"
            title="Edit image"
            @close="close_edit_image_modal()"
            :saveButton="{ visible: true, title: 'Save', btnClass: { 'btn btn-primary': true } }"
            :cancelButton="{ visible: false, title: 'Close', btnClass: { 'btn btn-danger': true } }"
        >
        <pre class="language-vue">
            <span v-show="loading_editing_data">
                <img :src="'../../../public/images/site_img/loading.gif'" alt="loading">
            </span>
            <span v-show="!loading_editing_data">
                <form ref="editingForm" id="slider_iamge_edit_form" v-on:submit.prevent="edit_image(editing_image.id)">
                    <div class="container">
                        
                        <div class="row">
                            <img :src="'/images/slider_img/' + editing_data.image" :alt="editing_data.title">
                        </div>
                        
                        <div class="form-group clearfix row">
                            <input type="file" name="image" id="image" value="image" v-on:change="onEditImageChange">
                        </div>

                        <div class="row">
                            <div class="col-md-12" v-if="error.length != 0">
                                <div class="alert alert-danger" role="alert" v-if="error.image">
                                    {{ error.image[0] }}
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
    // import breadcrumb from '../../items/BreadcrumbComponent.vue'
    // import { SlickList, SlickItem } from 'vue-slicksort'; //https://github.com/Jexordexan/vue-slicksort
    import StackModal from '@innologica/vue-stackable-modal'  //https://innologica.github.io/vue-stackable-modal/#sample-css

    export default {
        components: {
            StackModal,
            // SlickItem,
            // SlickList,
        },
        data(){
            return{
                user: [],
                MIX_SITE_URL: process.env.MIX_SITE_URL,
                MIX_APP_SSH: process.env.MIX_APP_SSH,
            }
        },
        mounted(){
            // 
        },
        methods: {
            // get_user_data(){
            //     axios
            //     .get('/api/auth_user')
            //     .then((response)=>{
            //         this.user = response.data
            //         this.get_user_queries(this.user.id)
            //     })
            // },
        }
    }
</script>