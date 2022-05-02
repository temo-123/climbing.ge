<template> 
        <div class="container">

            <div class="row">
                <div class="col-md-6">
                    <select class="form-control" v-model="filter_images" @click="filtr_images()">
                        <option>All</option>
                        <option v-for="article in articles" :key='article.id' :value="article.id">test</option> 
                    </select>
                </div>
                <div class="col-md-6">
                    <div class="form-groupe">
                        <button @click="get_gallery_data" class="btn main-btn pull-right" v-if="!is_refresh">Refresh ({{reset_id}})</button>
                        <span class="badge badge-primare mb-1 pull-right" v-if="is_refresh">Updating...</span>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-4">
                    <div class="thumbnail">
                        <img @click="add_image_modal()" alt="300x200" src="images/gallery_img/function_img/add_image.png">
                    </div>
                </div>
                <div class="col-md-4 mt-3" v-for="image in images" :key="image.id">
                    <div class="thumbnail">

                        <img @click="show_image_modal(image.id)" :alt="image.title" :src="'/images/gallery_img/'+image.image" style="height: 10em;">

                        <div class="col-md-12">
                                <button @click="edit_image_modal(image.id)" type="submit" class="btn btn-primary pull-left">    
                                    edit
                                </button>
                            <form method="post" @submit.prevent="delete_image(image.id)" >
                                <input type="hidden" name="_token" >
                                <div class="form-group">
                                    <button type="submit" class="btn btn-danger pull-right" onclick="return confirm('Are you sure you want to delete this item')">Del</button>
                                </div>
                            </form>
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
                <form ref="myForm">
                    <div class="container">

                        <div class="form-group clearfix row">
                            <input type="file" name="image" id="image" value="image">
                        </div>

                        <div class="form-group clearfix row">
                            <div class="col-md-12 image_add_modal_form">
                                <select class="form-control" name="published" id="published">
                                        <option value="0">Not public</option> 
                                        <option value="1">Public</option>
                                </select> 
                            </div>
                        </div>

                        <div class="form-group clearfix row">
                            <div class="col-md-12 image_add_modal_form">
                                <select class="form-control" name="category" id="category"  >
                                        <option value="1">Index header image</option> 
                                        <option value="2">Gallery image</option>
                                </select> 
                            </div>
                        </div>
                        
                        <div class="form-group clearfix row">
                            <div class="col-md-12 image_add_modal_form">
                                <select class="form-control" name="article_id" > 
                                        <option v-for="article in articles" :key="article.id" :value="article.id">{{ article.url_title }}</option> 
                                </select> 
                            </div>
                        </div>

                        <div class="form-group clearfix row">
                            <div class="col-md-12 image_add_modal_form">
                                <select class="form-control" name="filter" >
                                        <option value="climbing">climbing</option> 
                                        <option value="sleckline">sleckline</option> 
                                        <option value="zipline">zipline</option> 
                                        <option value="mountaineering">mountaineering</option> 
                                        <option value="sky">sky</option> 
                                </select> 
                            </div>
                        </div>

                        <div class="form-group clearfix row">
                            <div class="col-md-12 image_add_modal_form">
                                    <input type="text" name="title" class="form-control" placeholder="Title">
                            </div>
                        </div>


                        <div class="form-group clearfix row">
                            <div class="col-md-12">
                                <textarea type="text"  name="text" rows="15" class="form-cotrol md-textarea form-control"></textarea>
                            </div>
                        </div>

                        <div class="form-group clearfix row">
                            <div class="col-md-12 image_add_modal_form">
                                    <input type="text" name="link" class="form-control" placeholder="Article Link">
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
                        @click="add_image()"
                    >
                    Save
                    </button>
                </div>
            </div>
        </stack-modal>

        <stack-modal
                :show="is_edit_image"
                title="Edit image"
                @close="is_edit_image=false"
                :saveButton="{ visible: true, title: 'Save', btnClass: { 'btn btn-primary': true } }"
                :cancelButton="{ visible: false, title: 'Close', btnClass: { 'btn btn-danger': true } }"
            >
            <pre class="language-vue">
                <form ref="editingForm">
                    <div class="container">
                        
                        <div class="row">
                            <img :src="'/images/gallery_img/' + image" :alt="title">
                        </div>

                        <div class="form-group clearfix row">
                            <input type="file" name="image" id="image" value="image">
                        </div>

                        <div class="form-group clearfix row">
                            <div class="col-md-12 image_add_modal_form">
                                <select class="form-control" name="published" id="published" v-model="published">
                                        <option value="0">Not public</option> 
                                        <option value="1">Public</option>
                                </select> 
                            </div>
                        </div>

                        <div class="form-group clearfix row">
                            <div class="col-md-12 image_add_modal_form">
                                <select class="form-control" name="category" id="category" v-model="category">
                                        <option value="1">Index header image</option> 
                                        <option value="2">Gallery image</option>
                                </select> 
                            </div>
                        </div>
                        
                        <div class="form-group clearfix row">
                            <div class="col-md-12 image_add_modal_form">
                                <select class="form-control" name="article_id" v-model="article_id"> 
                                        <option v-for="article in articles" :key="article.id" :value="article.id">{{ article.url_title }}</option> 
                                </select> 
                            </div>
                        </div>

                        <div class="form-group clearfix row">
                            <div class="col-md-12 image_add_modal_form">
                                <select class="form-control" name="filter" v-model="filter">
                                    <option value="climbing">climbing</option> 
                                    <option value="sleckline">sleckline</option> 
                                    <option value="zipline">zipline</option> 
                                    <option value="mountaineering">mountaineering</option> 
                                    <option value="sky">sky</option> 
                                </select> 
                            </div>
                        </div>

                        <div class="form-group clearfix row">
                            <div class="col-md-12 image_add_modal_form">
                                <input type="text" name="title" class="form-control" placeholder="Title" v-model="title">
                            </div>
                        </div>


                        <div class="form-group clearfix row">
                            <div class="col-md-12">
                                <textarea type="text" v-model="text" name="text" rows="15" class="form-cotrol md-textarea form-control"></textarea>
                            </div>
                        </div>

                        <div class="form-group clearfix row">
                            <div class="col-md-12 image_add_modal_form">
                                <input type="text" name="link" class="form-control" placeholder="Article Link" v-model="link">
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
                        @click="edit_image(editing_id)"
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
            'status',
        ],
        data(){
            return {
                images: [],
                articles: [],
                editing_images: [],

                is_show_image: false,
                is_add_image: false,
                is_edit_image: false,

                modal_image: '',
                filter_images: 'All',

                reset_id: 0,
                is_refresh: false,

                published: "",
                category: "",
                article_id: "",
                filter: "",
                title: "",
                text: "",
                link: "",
                image: "",
                editing_id: "",
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

            show_image_modal(image_id){
                this.is_show_image = true
                axios
                .get("../gallery/get_image/"+image_id)
                .then(response => {
                    this.modal_image = response.data
                })
                .catch(
                    error => console.log(error)
                );
            },

            add_image_modal(){
                this.published = '',
                this.category = '',
                this.article_id = '',
                this.filter = '',
                this.title = '',
                this.text = '',
                this.link = '',
                this.image = '',
                this.editing_id = ''

                this.is_add_image = true
            },

            filtr_images(){
                // alert('test')
            },

            add_image(){
                var myFormData = new FormData(this.$refs.myForm)
                axios({
                    method: 'post',
                    url: '/gallery/add/',
                    data: myFormData,
                    config: { 
                        headers: {'Content-Type': 'multipart/form-data' },
                    },
                })
                .then((response)=>  {
                    this.is_add_image = false
                    this.get_gallery_data()
                });
            },


            get_editing_data(image_id){
                axios
                .get("/gallery/get_editing_image/"+image_id)
                .then(response => {
                    this.editing_images = response.data

                    this.published = this.editing_images.published,
                    this.category = this.editing_images.category,
                    this.article_id = this.editing_images.article_id,
                    this.filter = this.editing_images.filter,
                    this.title = this.editing_images.title,
                    this.text = this.editing_images.text,
                    this.link = this.editing_images.link
                    this.image = this.editing_images.image
                    this.editing_id = this.editing_images.id
                })
                .catch(
                    error => console.log(error)
                );
            },
            edit_image_modal(image_id){
                this.is_edit_image = true
                this.get_editing_data(image_id)
            },
            edit_image(editing_id){
                var editingFormData = new FormData(this.$refs.editingForm)
                axios({
                    method: 'post',
                    url: '/gallery/edit/' + editing_id,
                    data: editingFormData,
                    config: { 
                        headers: {'Content-Type': 'multipart/form-data' },
                    },
                })
                .then((response)=>  {
                    this.is_edit_image = false
                    this.get_gallery_data()

                    this.published = '',
                    this.category = '',
                    this.article_id = '',
                    this.filter = '',
                    this.title = '',
                    this.text = '',
                    this.link = '',
                    this.image = '',
                    this.editing_id = ''
                });
            },

            delete_image(itemId) {
                axios
                .delete('../api/gallery_image/' + itemId, {
                    id: itemId,
                })
                .then(Response => {
                    this.get_gallery_data()
                })
                .catch(error => console.log(error))
            }
        }
    }
</script>


<style scoped>
    .image_add_modal_form{
        height: 0;
    }
</style>
