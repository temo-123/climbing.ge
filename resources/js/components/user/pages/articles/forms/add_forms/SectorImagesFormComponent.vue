<template>
    <div class="row">
        <div class="container" v-if="this.category == 'outdoor'">
            <label for="email" class='col-md-12 control-label'>Upload outdoor climbing area sector image:</label>

            <div class="container">
                <div class="alert alert-danger" role="alert" v-if="image_errors.image">
                    {{ image_errors.image[0] }}
                </div>
                <div class="row">
                    <div class="form-groupe">
                        <button class="btn btn-primary mb-4"  @click="add_image_modal()">Add image</button>
                    </div>
                    <div class="form-groupe">
                        <button @click="get_spost_sectors_image(temporary_spost_sectors_id)" class="btn main-btn float-right" v-if="!image_is_refresh">Refresh ({{image_reset_id}})</button>
                        <span class="badge badge-primare mb-1 float-right" v-if="image_is_refresh">Updating...</span>
                    </div>
                </div>
                <div class="row">
                    <button class="btn btn-secondary float-left" @click="save_spost_sectors_images_sequence()">Save spost_sectors images sequence</button>
                </div>
            </div>
            
        </div>

        <!-- <div class="container">
            <div class="root">
            <SlickList lockAxis="y" v-model="images" tag="table" style="width: 100%">
                <tr>
                    <td>ID</td>
                    <td>Number</td>
                    <td>Image</td>
                    <td>Delete</td>
                </tr>
                <SlickItem v-for="(image, index) in images" :index='index' :key="index" tag="tr">
                    <td>{{ image.id }}</td>
                    <td v-if="image.num == NULL">0</td>
                    <td v-else>{{ image.num }}</td>
                    <td><img :alt="'Spost sectors image - (' + image.image + ')'" class="spost_sectors_image_in_table" :src="'/public/images/spost_sectors_img/' + image.image"></td>
                    <td><button class="btn btn-danger" @click="del_spost_sectors_image(image.id)">Del</button></td>
                </SlickItem>
            </SlickList>
            </div>
        </div> -->


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
                        <div class="form-group col-md-12">
                            <div class="row">
                                <label for="image">Add Spot Image:</label>
                            </div>
                            <div class="row">
                                <input type="file" name="image" id="image" value="image">
                            </div>
                        </div>
                    </div>
                </form>

                <!-- <form @submit="checkForm" ref="myForm">
                    <div class="form-group">
                        <label for="email">Upload Profile Pic:</label>
                        <input type="file" name="profile_pic" id="profile_pic">
                    </div>
                </form> -->
            </pre>
            <div slot="modal-footer">
                <div class="modal-footer">
                    <button
                        type="button"
                        :class="{'btn btn-primary': true}"
                        @click="add_spot_rock_image()"
                    >
                    Save
                    </button>
                </div>
            </div>
        </stack-modal>

        <!-- <stack-modal
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
        </stack-modal> -->
        
    </div>
</template>

<script>
    import { SlickList, SlickItem } from 'vue-slicksort';
    import StackModal from '@innologica/vue-stackable-modal'  //https://innologica.github.io/vue-stackable-modal/#sample-css

    export default {
        props: [
            'category'
        ],
        components: {
            StackModal,
            SlickItem,
            SlickList,
        },
        data(){
            return {
                myModal: false,
                // errors: [],
                image_errors: [],
                without_info: false,

                is_add_image: false,

                category: this.$route.params.article_category,

                image_is_refresh: false,
                image_reset_id: 0,
            }
        },
        mounted() {
        },
        methods: {
            add_image_modal(){
                this.is_add_image = true
            },

            // add_image(){
            //     var myFormData = new FormData(this.$refs.myForm)
            //     axios({
            //         method: 'post',
            //         url: '/gallery/add/',
            //         data: myFormData,
            //         config: { 
            //             headers: {'Content-Type': 'multipart/form-data' },
            //         },
            //     })
            //     .then((response)=>  {
            //         this.is_add_image = false
            //         // this.get_gallery_data()
            //     });
            // },

            add_spot_rock_image: function () {
                var myFormData = new FormData(this.$refs.myForm)
                axios({
                    method: 'post',
                    url: '../../api/articles/upload_image/',
                    data: myFormData,
                    config: { 
                        headers: {'Content-Type': 'multipart/form-data' },
                    },
                })
                .then((response)=>  {
                    
                });
            },
        }
    }
</script>

<style>

</style>