<template> 
    <div class="col-md-12">

        <div class="cms_filters">
            <div class="row">
                <div class="col-md-12">
                    <h3>Filters</h3>
                </div>
            </div>
            <div class="row">
                <div class="col-md-4">
                    <select class="form-control" v-model="filter_articles" @click="filtr_images()"  v-if="filter_type == 'All' || filter_type == 'Select image type' || filter_type == 'Article images'">
                        <option disabled>Select article</option> 
                        <option>All</option>
                        <option v-for="article in articles" :key='article.id' :value="article.id">{{ article.url_title }}</option> 
                    </select>
                </div>
                <div class="col-md-4">
                    <select class="form-control" v-model="filter_category" @click="filtr_images()">
                        <option disabled>Select category</option> 
                        <option>All</option>
                        <option v-for="categorie in categories" :key='categorie.id' :value="categorie.id">{{ categorie.us_name }}</option> 
                    </select>
                </div>
                <div class="col-md-4" >
                    <select class="form-control" v-model="filter_type" @click="filtr_images()" v-if="filter_articles == 'All' || filter_articles == 'Select article' || (filter_type == 'Article images' && filter_articles != 'All' || filter_articles != 'Select article')">
                        <option disabled>Select image type</option> 
                        <option>All</option>
                        <option>Article images</option> 
                        <option>Index gallery images</option> 
                        <option>Index head slider images</option> 
                    </select>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="form-groupe float-right">
                    <button class="btn btn-success"  @click="get_gallery_data">refresh</button>
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

                    <img @click="show_image_modal(image)" :alt="image.title" :src="'/images/gallery_img/'+image.image" class="cursor_pointr">
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

        <stack-modal
            :show="is_show_image"
            title="Image"
            @close="is_show_image=false"
            :saveButton="{ visible: false, title: 'Save', btnClass: { 'btn btn-primary': false } }"
            :cancelButton="{ visible: false, title: 'Close', btnClass: { 'btn btn-danger': true } }"
        >
            <pre class="language-vue">
                <div class="container">
                    <div class="row">
                        <img :src="'/images/gallery_img/' + modal_image.image" :alt="modal_image.title">
                        <!-- {{ modal_image.image }} -->
                    </div>
                </div>
            </pre>
        </stack-modal>

        <stack-modal
            :show="is_add_image"
            title="Add image"
            @close="is_add_image=false"
            :saveButton="{ visible: true, title: 'Save', btnClass: { 'btn btn-primary': true } }"
            :cancelButton="{ visible: false, title: 'Close', btnClass: { 'btn btn-danger': true } }"
        >
            <pre class="language-vue">
                <form ref="gallery_image_add_form" id="gallery_image_add_form" v-on:submit.prevent="add_image">
                    <div class="container">

                        <div class="form-group clearfix row">
                            <input type="file" name="image" id="image" value="image" v-on:change="onAddImageChange">
                        </div>

                        <div class="form-group clearfix row">
                            <div class="col-md-12 image_add_modal_form">
                                <select class="form-control" name="published" id="published" v-model="form_data.published" >
                                        <option value="0">Not public</option> 
                                        <option value="1">Public</option>
                                </select> 
                            </div>
                        </div>

                        <div class="form-group clearfix row">
                            <div class="col-md-12 image_add_modal_form">
                                <select class="form-control" name="image_type" id="image_type" v-model="form_data.image_type">
                                    <option disabled>Select image type</option> 
                                    <option>Article image</option> 
                                    <option>Index gallery images</option> 
                                    <option>Index head slider images</option> 
                                </select> 
                            </div>
                        </div>
                        
                        <div class="form-group clearfix row" v-if="form_data.image_type == 'Article image'">
                            <div class="col-md-12 image_add_modal_form">
                                <select class="form-control" name="article_id"  v-model="form_data.article_id"> 
                                    <option disabled>Select article</option> 
                                    <option v-for="article in articles" :key='article.id' :value="article.id">{{ article.url_title }}</option> 
                                </select> 
                            </div>
                        </div>

                        <div class="form-group clearfix row">
                            <div class="col-md-12 image_add_modal_form">
                                <select class="form-control" name="filter"  v-model="form_data.category_id">
                                    <option disabled>Select image category</option> 
                                    <option v-for="categorie in categories" :key='categorie.id' :value="categorie.id">{{ categorie.us_name }}</option> 
                                </select> 
                            </div>
                        </div>

                        <div class="form-group clearfix row">
                            <div class="col-md-12 image_add_modal_form">
                                <input type="text" name="title" class="form-control" placeholder="Title"  v-model="form_data.title">
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
            </pre>
            <div slot="modal-footer">
                <div class="modal-footer">
                    <button
                        type="submit"
                        :class="{'btn btn-primary': true}"
                        form="gallery_image_add_form"
                    >
                    Save
                    </button>
                </div>
            </div>
        </stack-modal>

        <stack-modal
                :show="is_edit_image"
                title="Edit image"
                @close="close_edit_image_modal"
                :saveButton="{ visible: true, title: 'Save', btnClass: { 'btn btn-primary': true } }"
                :cancelButton="{ visible: false, title: 'Close', btnClass: { 'btn btn-danger': true } }"
            >
            <pre class="language-vue">
                <form ref="editingForm">
                    <div class="container">
                        
                        <div class="row">
                            <img :src="'/images/gallery_img/' + editing_data.image" :alt="editing_data.title">
                        </div>
                        
                        <div class="form-group clearfix row">
                            <input type="file" name="image" id="image" value="image" v-on:change="onEditImageChange">
                        </div>

                        <div class="form-group clearfix row">
                            <div class="col-md-12 image_add_modal_form">
                                <select class="form-control" name="published" id="published" v-model="editing_data.published">
                                        <option value="0">Not public</option> 
                                        <option value="1">Public</option>
                                </select> 
                            </div>
                        </div>

                        <div class="form-group clearfix row">
                            <div class="col-md-12 image_add_modal_form">
                                <select class="form-control" name="category" id="category" v-model="editing_data.image_type">
                                    <option disabled>Select image type</option> 
                                    <option>Article image</option> 
                                    <option>Index gallery images</option> 
                                    <option>Index head slider images</option> 
                                </select> 
                            </div>
                        </div>
                        
                        <div class="form-group clearfix row" v-if="editing_data.image_type == 'Article image'">
                            <div class="col-md-12 image_add_modal_form">
                                <select class="form-control" name="article_id" v-model="editing_data.article_id"> 
                                    <option disabled>Select article</option> 
                                    <option v-for="article in articles" :key='article.id' :value="article.id">{{ article.url_title }}</option> 
                                </select> 
                                <!-- <select class="form-control" name="article_id" v-model="editing_data.article[0].id"> 
                                    <option disabled>Select article</option> 
                                    <option v-for="article in articles" :key='article.id' :value="article.id">{{ article.url_title }}</option> 
                                </select>  -->
                            </div>
                        </div>

                        <div class="form-group clearfix row">
                            <div class="col-md-12 image_add_modal_form">
                                <select class="form-control" name="filter" v-model="editing_data.category_id">
                                    <option disabled>Select image category</option> 
                                    <option v-for="categorie in categories" :key='categorie.id' :value="categorie.id">{{ categorie.us_name }}</option> 
                                </select> 
                            </div>
                        </div>

                        <div class="form-group clearfix row">
                            <div class="col-md-12 image_add_modal_form">
                                <input type="text" name="title" class="form-control" placeholder="Title" v-model="editing_data.title">
                            </div>
                        </div>


                        <div class="form-group clearfix row">
                            <div class="col-md-12">
                                <textarea type="text" v-model="editing_data.text" name="text" rows="15" class="form-cotrol md-textarea form-control"></textarea>
                            </div>
                        </div>

                        <div class="form-group clearfix row">
                            <div class="col-md-12 image_add_modal_form">
                                <input type="text" name="link" class="form-control" placeholder="Article Link" v-model="editing_data.link">
                            </div>
                        </div>
                    </div>
                </form>
            </pre>
            <div slot="modal-footer">
                <div class="modal-footer">
                    <button
                        type="button"
                        :class="{'btn btn-primary': true}"
                        @click="edit_image(editing_image.id)"
                    >
                    Save
                    </button>
                </div>
            </div>
        </stack-modal>
    </div>
</template>

<script>
    import { SlickList, SlickItem } from 'vue-slicksort'; //https://github.com/Jexordexan/vue-slicksort
    import StackModal from '@innologica/vue-stackable-modal'  //https://innologica.github.io/vue-stackable-modal/#sample-css

    export default {
        components: {
            StackModal,
            SlickItem,
            SlickList,
        },
        props: [
            'categories',
        ],
        data(){
            return {
                images: [],
                articles: [],

                is_show_image: false,
                is_add_image: false,
                is_edit_image: false,

                modal_image: '',

                filter_category: 'Select category',
                filter_type: 'Select image type',
                filter_articles: 'Select article',

                reset_id: 0,
                is_refresh: false,

                form_data: {
                    published: 0,
                    category_id: "Select image category",
                    article_id: "Select article",
                    title: "",
                    text: "",
                    image_type: 'Select image type',
                    link: "",
                },
                image: "",

                editing_data: {
                    published: 0,
                    category_id: 'Select image category',
                    article_id: 'Select article',
                    title: "",
                    text: '',
                    image_type: 'Select image type',
                    link: '',
                },
                editing_image: "",
            }
        },
        mounted() {
            this.get_gallery_data()
            this.get_articles()
        },
        methods: {
            get_gallery_data: function(){
                axios
                .post("../api/gallery_image/")
                .then(response => {
                    this.images = response.data
                    this.is_refresh = false
                    this.reset_id++
                })
                .catch(
                    error => console.log(error)
                );
            },

            get_articles(){
                axios
                .get("../api/article/")
                .then(response => {
                    this.articles = response.data
                })
                .catch(
                    error => console.log(error)
                );
            },

            show_image_modal(active_img){
                this.is_show_image = true
                this.modal_image = active_img
            },

            add_image_modal(){
                this.clear_input_data()

                this.is_add_image = true
            },

            onAddImageChange(e){
                this.image = e.target.files[0];
            },

            add_image(){
                // let formData = new FormData(this.$refs.myForm);
                let formData = new FormData();
                formData.append('image', this.image);
                formData.append('data', JSON.stringify(this.form_data))
                console.log(formData);

                axios
                .post('./api/gallery_image_add', 
                    formData,
                )
                .then(response => {
                    this.is_add_image = false
                    this.get_gallery_data()
                    this.clear_input_data()
                })
                .catch(err => {
                    console.log(err);
                })
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
                this.editing_image = ''
            },



            onEditImageChange(e){
                this.editing_image = e.target.files[0];
            },
            get_editing_image_data(image_id){
                axios
                .get("./api/get_editing_image/"+image_id)
                .then(response => {
                    this.editing_data = response.data
                    this.editing_data.article_id = response.data.article[0].id
                })
                .catch(
                    error => console.log(error)
                );
            },
            edit_image_modal(editing_image_id){
                this.is_edit_image = true
                this.get_editing_image_data(editing_image_id)
            },
            close_edit_image_modal(){
                this.is_edit_image = false
                this.clear_input_data()
            },
            edit_image(){
                let formData = new FormData();
                formData.append('image', this.editing_image);
                formData.append('data', JSON.stringify(this.editing_data))
                // console.log(formData);

                axios
                .post('./api/gallery_image_edit/'+this.editing_data.id, 
                    formData,
                )
                .then(response => {
                    this.is_add_image = false
                    this.get_gallery_data()
                    this.close_edit_image_modal()

                    this.editing_image = []
                })
                .catch(err => {
                    console.log(err);
                })
            },

            del_image(image_id) {
                if (window.confirm('Added information will be deleted!!! Are you sure, you want go back?')) {
                    axios
                    .delete('../api/gallery_image/' + image_id, {
                        id: image_id,
                    })
                    .then(Response => {
                        this.get_gallery_data()
                    })
                    .catch(error => console.log(error))
                }
            },


            filtr_images(){
                // alert('test')
            },
        }
    }
</script>


<style scoped>
    .image_add_modal_form{
        height: 0;
    }
</style>
