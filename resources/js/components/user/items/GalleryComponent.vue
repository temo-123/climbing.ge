<template> 
    <div class="container">

        <div class="row">
            <div class="col-md-5">
                <select class="form-control" v-model="filter_images" @click="filtr_images()">
                    <option disabled>Select image category</option> 
                    <option>All</option>
                    <option v-for="article in articles" :key='article.id' :value="article.id">{{ article.us_name }}</option> 
                </select>
            </div>
            <div class="col-md-5">
                <select class="form-control" >
                    <option disabled>Select image type</option> 
                    <option>All</option>
                    <option>Article images</option> 
                    <option>Index gallery images</option> 
                    <option>Index head slider images</option> 
                </select>
            </div>
            <div class="col-md-2">
                <div class="form-groupe float-right">
                    <button class="btn btn-success"  @click="update(filtr_data.id)">refresh</button>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-4">
                <div class="thumbnail">
                    <img @click="add_image_modal()" alt="Add image" src="images/gallery_img/function_img/add_image.png">
                </div>
            </div>
            <div class="col-md-4 mt-3" v-for="image in images" :key="image.id">
                <div class="thumbnail">

                    <img @click="show_image_modal(image)" :alt="image.title" :src="'/images/gallery_img/'+image.image" >

                    <div class="col-md-12">
                        <button @click="edit_image_modal(image)" type="submit" class="btn btn-primary float-left">    
                            Edit
                        </button>
                        <button @click="del_image(image.id)" type="submit" class="btn btn-danger float-right">    
                            Del
                        </button>
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
                            <img :src="'/images/gallery_img/' + editing_image.image" :alt="editing_image.title">
                        </div>

                        <div class="form-group clearfix row">
                            <input type="file" name="image" id="image" value="image">
                        </div>

                        <div class="form-group clearfix row">
                            <div class="col-md-12 image_add_modal_form">
                                <select class="form-control" name="published" id="published" v-model="editing_image.published">
                                        <option value="0">Not public</option> 
                                        <option value="1">Public</option>
                                </select> 
                            </div>
                        </div>

                        <div class="form-group clearfix row">
                            <div class="col-md-12 image_add_modal_form">
                                <select class="form-control" name="category" id="category" v-model="editing_image.category">
                                        <option value="1">Index header image</option> 
                                        <option value="2">Gallery image</option>
                                </select> 
                            </div>
                        </div>
                        
                        <div class="form-group clearfix row">
                            <div class="col-md-12 image_add_modal_form">
                                <select class="form-control" name="article_id" v-model="editing_image.article_id"> 
                                        <option v-for="article in articles" :key="article.id" :value="article.id">{{ article.url_title }}</option> 
                                </select> 
                            </div>
                        </div>

                        <div class="form-group clearfix row">
                            <div class="col-md-12 image_add_modal_form">
                                <select class="form-control" name="filter" v-model="editing_image.filter">
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
                                <input type="text" name="title" class="form-control" placeholder="Title" v-model="editing_image.title">
                            </div>
                        </div>


                        <div class="form-group clearfix row">
                            <div class="col-md-12">
                                <textarea type="text" v-model="editing_image.text" name="text" rows="15" class="form-cotrol md-textarea form-control"></textarea>
                            </div>
                        </div>

                        <div class="form-group clearfix row">
                            <div class="col-md-12 image_add_modal_form">
                                <input type="text" name="link" class="form-control" placeholder="Article Link" v-model="editing_image.link">
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
            'status',
        ],
        data(){
            return {
                images: [],
                articles: [],
                editing_image: [],

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
                // editing_id: "",
            }
        },
        mounted() {
            this.get_gallery_data()
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

            del_image(image_id){
                if (window.confirm('Added information will be deleted!!! Are you sure, you want go back?')) {
                    alert('del image wyith nom '+image_id)
                }
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
                // this.modal_image = []
                // axios
                // .get("../api/get_image/"+image_id)
                // .then(response => {
                //     this.modal_image = response.data
                // })
                // .catch(
                //     error => console.log(error)
                // );
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

                this.get_articles()
                
                // axios
                // .get("/gallery/get_editing_image/"+image_id)
                // .then(response => {
                //     this.editing_images = response.data

                //     this.published = this.editing_images.published,
                //     this.category = this.editing_images.category,
                //     this.article_id = this.editing_images.article_id,
                //     this.filter = this.editing_images.filter,
                //     this.title = this.editing_images.title,
                //     this.text = this.editing_images.text,
                //     this.link = this.editing_images.link
                //     this.image = this.editing_images.image
                //     this.editing_id = this.editing_images.id
                // })
                // .catch(
                //     error => console.log(error)
                // );
            },
            edit_image_modal(editing_image_data){
                this.is_edit_image = true
                this.editing_image = editing_image_data
                // this.get_editing_data(image_id)
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
