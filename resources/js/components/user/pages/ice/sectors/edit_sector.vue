<template>
    <div class="col-md-12">
        <div class="row justify-content-center" v-if="is_loading">
            <div class="col-md-4">
                <img :src="'../../../../../../public/images/site_img/loading.gif'" alt="loading">
            </div>
        </div>

        <div class="row" v-if="!is_loading">
            <div class="form-group">
                <button
                    type="submit"
                    class="btn btn-primary"
                    v-on:click="go_back(false)"
                >
                    Beck
                </button>
            </div>
        </div>
        <div class="row" v-if="!is_loading">
            <div class="form-group">
                <button
                    type="submit"
                    class="btn btn-primary"
                    form="sector_editing_form"
                >
                    Save
                </button>
            </div>
        </div>
        <div v-if="!is_loading">
            <form
                @submit.prevent="edit_ice_sector"
                id="sector_editing_form"
                class="contact-form"
                method="POST"
                enctype="multipart/form-data"
            >

                <div class="form-group clearfix row">
                    <label for="published" class='col-md-2 control-label '> Publish </label>
                    <div class="col-md-10">
                        <select class="form-control" v-model="data.published" name="published" required> 
                            <option :value="0">Not public</option> 
                            <option :value="1">Public</option> 
                        </select> 
                    </div>
                </div>
                <div class="form-group clearfix row">
                    <label for="name" class="col-md-2 control-label">
                        Sector name
                    </label>
                    <div class="col-md-10">
                        <input
                            type="text"
                            v-model="data.name"
                            name="name"
                            placeholder="Name"
                            class="form-control textarea"
                            required
                        />
                        <div
                            class="alert alert-danger"
                            role="alert"
                            v-if="errors.name"
                        >
                            {{ errors.name[0] }}
                        </div>
                    </div>
                </div>

                <div class="form-group clearfix row">
                    <label class="col-md-2 control-label"> Region </label>
                    <div class="col-md-10">
                        <select
                            class="form-control"
                            name="article_id"
                            v-model="data.article_id"

                            required
                        >
                            <option
                                v-bind:value="''"
                                disabled
                            >
                                Select outdoor area
                            </option>
                            <option
                                v-for="region in regions"
                                :key="region"
                                v-bind:value="region.id"
                            >
                                {{ region.url_title }}
                            </option>
                        </select>
                        <div
                            class="alert alert-danger"
                            role="alert"
                            v-if="errors.article_id"
                        >
                            {{ errors.article_id[0] }}
                        </div>
                    </div>
                </div>

                <div class="form-group clearfix row">
                    <label class="col-md-2 control-label"> Text </label>
                    <div class="col-md-10">
                        <ckeditor v-model="data.text" :config="description_editor"></ckeditor>
                    </div>
                </div>

                <hr />
            </form>
                            
            <div class="col-md-12">
                <div class="row">
                    Olredy added
                </div>
            </div>

            <div class="col-md-12">
                <div class="row">
                    <div class="col-md-12">
                        <table class="table table-hover" id="dev-table">
                            <thead>
                                <tr>
                                    <th>Image</th>
                                    <th>Delite</th>
                                </tr>
                            </thead>

                            <tbody>
                                <tr v-for="sector_old_image in sector_old_images" :key="sector_old_image.id">
                                    <td>
                                        <img class="img-responsive" :src="'../../../../images/ice_sector_img/'+sector_old_image.image" :alt="sector_old_image.image">
                                    </td>
                                    <td>
                                        <button class="btn btn-danger" @click="del_ice_sector_image_from_db(sector_old_image.id)">Delete</button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <div class="form-groupe">
                        <button class="btn btn-primary float-left" @click="add_ice_sector_new_image_value()">Add new sector image</button>
                    </div>
                </div>
            </div>

            <div class="container">
                <div class="root">
                    <table
                        tag="table"
                        :style="'width: 100%'"
                    >
                        <thead>
                            <tr>
                                <td>Image</td>
                                <td>Delete</td>
                            </tr>
                        </thead>
                        <tbody
                            v-for="(image, index) in sector_new_images"
                            :index="index"
                            :key="index"
                            tag="tr"
                        >
                            <td>
                                <form ref="myForm">
                                    <input type="file" name="image" id="image" v-on:change="onFileChange($event, image.id)">
                                </form> 
                            </td>
                            <td>
                                <button
                                    class="btn btn-danger"
                                    @click="del_ice_sector_image(image.id)"
                                >
                                    Del
                                </button>
                            </td>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
    import Uploader from "vux-uploader-component";
    import { SlickList, SlickItem } from "vue-slicksort"; //https://www.npmjs.com/package/vue-slicksort/v/2.0.0-alpha.2

    import { editor_config } from '../../../../../mixins/editor/editor_config_mixin.js'

    export default {
        mixins: [
            editor_config
        ],
        components: {
            Uploader,
            SlickItem,
            SlickList,
        },
        data() {
            return {
                description_editor: editor_config.get_small_editor_config(),
                fileList: [], //https://github.com/eJayYoung/vux-uploader-component
                regions: "",

                is_loading: false,

                editorConfig: {},

                errors: [],
                // image_errors: [],

                data: {
                    published: 0,
                    name: "",
                    image: "",

                    article_id: "",
                    name: "",
                    text: "",
                },

                // temporary_ice_sector_id: 0,

                is_back_action_query: true,
                // image_reset_id: 0,

                // sector_new_images: ['Item 1', 'Item 2', 'Item 3', 'Item 4', 'Item 5', 'Item 6', 'Item 7', 'Item 10'],
                sector_new_images: [],
                sector_old_images: [],
            };
        },
        mounted() {
            this.get_region_data();
            this.get_editing_ice_sector_data();
        
            document.querySelector('body').style.marginLeft = '0';
            document.querySelector('.admin_page_header_navbar').style.marginLeft = '0';
        },
        // beforeRouteLeave (to, from, next) {
        //     this.go_back()
        // },
        beforeRouteLeave (to, from, next) {
            if(this.is_back_action_query == true){
                if (window.confirm('Added information will be deleted!!! Are you sure, you want go back?')) {
                    this.is_back_action_query = false;
                    next()
                } else {
                    next(false)
                }
            }else {
                next()
            }
        },
        methods: {
            add_ice_sector_new_image_value(){
                var new_item_id = this.sector_new_images.length+1
                this.sector_new_images.push(
                    {
                        id: new_item_id,
                        image: '',
                    }
                );
            },
            onFileChange(event, item_id){
                let image = event.target.files[0]
                let id = item_id - 1 
                this.sector_new_images[id]['image'] = image
            },
            del_ice_sector_image(id){
                this.removeObjectWithId(this.sector_new_images, id);
            },
            removeObjectWithId(arr, id) {
                const objWithIdIndex = arr.findIndex((obj) => obj.id === id);
                arr.splice(objWithIdIndex, 1);

                return arr;
            },

            del_ice_sector_image_from_db(image_id) {
                if(confirm('Are you sure, you want delite this image?')){
                    axios
                    .delete("/ice_sector_images/del_sector_images/"+image_id)
                    .then(response => {
                        this.get_ice_sector_images()
                    })
                    .catch(
                        error => console.log(error)
                    );
                }
            },

            get_region_data: function () {
                axios
                    .get("/article/get_category_articles/ice")
                    .then(response => {
                        this.regions = response.data
                    })
                    .catch(error =>{
                    })
                    // .finally(() => this.oudoor_loading = false)
            },

            get_editing_ice_sector_data: function(){
                this.is_loading = true
                axios
                .get("/ice_sectors/get_sector_editing_data/"+this.$route.params.id)
                .then(response => {
                    this.data = response.data
                    this.get_ice_sector_images()
                })
                .catch(
                error => console.log(error)
                )
                .finally(() => this.is_loading = false);
            },

            get_ice_sector_images: function(){
                axios
                .get("/ice_sector_images/get_sector_images/"+this.$route.params.id)
                .then(response => {
                    this.sector_old_images = response.data
                })
                .catch(
                error => console.log(error)
                );
            },

            edit_ice_sector: function () {
                this.is_loading = true
                let formData = new FormData();

                var loop_num = 0
                this.sector_new_images.forEach(image => {
                    formData.append('sector_new_images['+loop_num+']', image.image)
                    loop_num++
                });
                loop_num = 0

                formData.append('data', JSON.stringify(this.data))

                axios
                .post("/ice_sectors/edit_sector/"+this.$route.params.id, formData)
                .then(response => {
                    this.go_back(true)
                })
                .catch((error) => {
                    // console.log(error);
                    if (error.response.status == 422) {
                        this.errors = error.response.data.errors;
                    }
                })
                .finally(() => this.is_loading = false);
            },

            go_back(back_action = false) {
                this.is_back_action_query = this.$going.back(this, back_action)
                // this.$going.back(this, back_action)
            },
        },
    }
</script>

<style scoped>
.sector_img{
  border-radius: 5%;
  border: 3px solid #a7a7a7;
}
.sector_image_in_table{
  height: 10em;
}
</style>