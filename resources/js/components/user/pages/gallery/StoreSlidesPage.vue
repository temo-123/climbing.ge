<template>
    <div class="row">
        
        <left-menu />
            
        <div class="col-sm-12">
            <div class="row">
                <div class="col-md-12">
                    <breadcrumb />
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12">
                    <div class="tabs">
                        <div class="col-md-12">
                            <div class="row">
                                <div class="col">
                                    <input type="radio" id="1" :value="1" v-model="tab_num" />

                                    <label for="1">Gallery images</label>
                                </div>
                            </div>
                        </div>

                        <div class="row" v-if="tab_num == 1">
                            <div class="col-md-12">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-groupe float-right">
                                            <button class="btn btn-success"  @click="get_slider_data">refresh</button>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="thumbnail">
                                            <img @click="add_image_modal()" alt="Add image" src="images/site_img/function_imgs/add_image.png">
                                        </div>
                                    </div>
                                    <div class="col-md-4 mt-3" v-for="image in images" :key="image.id">
                                        <div class="thumbnail">

                                            <img @click="show_image_modal(image)" :alt="image.title" :src="'/images/slider_img/'+image.image" class="cursor_pointr">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    {{ image.image_type }}
                                                </div>
                                                <div class="col-md-12" style='color: #f00' v-if="image.published == 0">
                                                    No public
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <button @click="edit_image_modal(image.id)" type="submit" class="btn btn-primary float-left">    
                                                        Edit
                                                    </button>
                                                </div>
                                                <div class="col-md-6">
                                                    <button @click="del_image(image.id)" type="submit" class="btn btn-danger float-right">    
                                                        Del
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- <stack-modal
                                    :show="is_show_image"
                                    title="Image"
                                    @close="is_show_image=false"
                                    :saveButton="{ visible: false, title: 'Save', btnClass: { 'btn btn-primary': false } }"
                                    :cancelButton="{ visible: false, title: 'Close', btnClass: { 'btn btn-danger': true } }"
                                >
                                    <pre class="language-vue">
                                        <div class="container">
                                            <div class="row">
                                                <img :src="'/images/slider_img/' + modal_image.image" :alt="modal_image.title">
                                            </div>
                                        </div>
                                    </pre>
                                </stack-modal> -->

                                <!-- <stack-modal
                                    :show="is_add_image"
                                    title="Add image"
                                    @close="close_add_image_modal()"
                                    :saveButton="{ visible: true, title: 'Save', btnClass: { 'btn btn-primary': true } }"
                                    :cancelButton="{ visible: false, title: 'Close', btnClass: { 'btn btn-danger': true } }"
                                >
                                    <pre class="language-vue">
                                        <span v-show="loading_addimg_data">
                                            <img :src="'../../../public/images/site_img/loading.gif'" alt="loading">
                                        </span>
                                        <span v-show="!loading_addimg_data">
                                            <form ref="slider_image_add_form" id="slider_image_add_form" v-on:submit.prevent="add_image">
                                                <div class="container">

                                                    <div class="form-group clearfix row">
                                                        <input type="file" name="image" id="image" value="image" v-on:change="onAddImageChange" required>
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
                                                            <textarea type="text"  name="text" rows="15" class="form-cotrol md-textarea form-control"  v-model="form_data.text" required></textarea>
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
                                </stack-modal> -->

                                <!-- <stack-modal
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
                                </stack-modal> -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import breadcrumb from '../../items/BreadcrumbComponent.vue'
    // import { SlickList, SlickItem } from 'vue-slicksort'; //https://github.com/Jexordexan/vue-slicksort
    import StackModal from '@innologica/vue-stackable-modal'  //https://innologica.github.io/vue-stackable-modal/#sample-css

    export default {
        components: {
            StackModal,
            // SlickItem,
            // SlickList,
        },
        props: [
            'categories',
        ],
        data(){
            return {
                images: [],
                tab_num: 1,

                is_show_image: false,
                is_add_image: false,
                is_edit_image: false,

                loading_editing_data: false,
                loading_addimg_data: false,

                modal_image: '',

                reset_id: 0,
                is_refresh: false,

                form_data: {
                    published: 0,
                    title: "",
                    text: "",
                    image_type: 'Select image type',
                    link: "",
                },
                image: "",

                editing_data: {
                    published: 0,
                    title: "",
                    text: '',
                    image_type: 'Select image type',
                    link: '',
                },
                editing_image: [],

                category_error: "",

                error: []
            }
        },
        mounted() {
            this.get_slider_data()
        },
        methods: {
            get_slider_data: function(){
                axios
                .get("/store_slides/get_all_slides/")
                .then(response => {
                    this.images = response.data
                    this.is_refresh = false
                    this.reset_id++
                })
                .catch(error => {
                    // alert(error)
                });
            },

            close_add_image_modal(action = false){
                if(!action){
                    if (window.confirm('Added information will be deleted!!! Are you sure, you want close modal?')) {
                        this.is_add_image = false
                        this.clear_input_data()
                    }
                }
                else{
                    this.is_add_image = false
                    this.clear_input_data()
                }
            },


            show_image_modal(active_img){
                this.is_show_image = true
                this.modal_image = active_img
            },

            add_image_modal(){
                this.error = []
                this.clear_input_data()
                this.is_add_image = true
            },

            onAddImageChange(e){
                this.image = e.target.files[0];
            },

            add_image(){
                this.category_error = ''

                let formData = new FormData();
                formData.append('image', this.image);
                formData.append('data', JSON.stringify(this.form_data))

                this.error = []

                this.loading_addimg_data = true

                if(this.form_data.category_id == 'Select image category'){
                    this.category_error = 'Select this category is inposeble!'
                }
                else{
                    axios
                    .post('/slider_image_add', 
                        formData,
                    )
                    .then(response => {
                        this.is_add_image = false
                        this.get_slider_data()
                        this.close_add_image_modal(true)
                    })
                    .catch(error => {
                        // alert(error)
                        if (error.response.status == 422) {
                            this.error = error.response.data.errors
                        }
                    })
                    .finally(() =>
                        this.loading_addimg_data = false
                    )
                }
            },

            clear_input_data(){
                this.form_data = {
                    published: 0,
                    category_id: 'Select image category',
                    article_id: 'Select article',
                    title: "",
                    text: '',
                    image_type: 'Select image type',
                    link: '',
                }
                this.image = ''

                this.editing_data = {
                    published: 0,
                    category_id: 'Select image category',
                    article_id: 'Select article',
                    title: "",
                    text: '',
                    image_type: 'Select image type',
                    link: '',
                }
                this.editing_image = []

                this.error = []
            },

            get_editing_image_data(image_id){
                this.loading_editing_data = true
                axios
                .get("/get_editing_image/"+image_id)
                .then(response => {
                    this.editing_data = response.data
                    // alert('/'+response.data.article+'/')
                    if(response.data.article != [] && response.data.article != ''){
                        this.editing_data.article_id = response.data.article[0].id
                    }
                    this.editing_image.id = response.data.id
                })
                .catch(error => {
                    alert("get_editing_image_data function error => " + error)
                })
                .finally(() => this.loading_editing_data = false);
            },

            edit_image_modal(editing_image_id){
                this.error = []
                this.is_edit_image = true
                this.get_editing_image_data(editing_image_id)
            },

            close_edit_image_modal(action = false){
                if(!action){
                    if (window.confirm('Added information will be deleted!!! Are you sure, you want close modal?')) {
                        this.is_edit_image = false
                        this.clear_input_data()
                    }
                }
                else{
                    this.is_edit_image = false
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

            edit_image(){
                this.error = []
                this.loading_editing_data = true

                this.category_id = ''

                let formData = new FormData();
                if(this.editing_image != [] || this.editing_image != null){
                    formData.append('image', this.editing_image);
                }
                formData.append('data', JSON.stringify(this.editing_data))

                if(this.editing_data.category_id == 'Select image category'){
                    this.category_error = 'Select this category is inposeble!'
                }
                else{
                    axios
                    .post('/slider_image_edit/'+this.editing_data.id, 
                        formData,
                    )
                    .then(response => {
                        this.is_add_image = false

                        this.get_slider_data()
                        this.close_edit_image_modal(true)

                        this.editing_image = []
                    })
                    .catch(error => {
                        if (error.response.status == 422) {
                            this.error = error.response.data.errors
                        }
                    })
                    .finally(() => this.loading_editing_data = false);
                }
            },

            del_image(image_id) {
                if (window.confirm('Added information will be deleted!!! Are you sure, you want go back?')) {
                    axios
                    .delete('/slider_image/' + image_id, {
                        id: image_id,
                    })
                    .then(Response => {
                        this.get_slider_data()
                    })
                    .catch(error => {
                        // alert(error)
                    });
                }
            },
        }
    }
</script>

