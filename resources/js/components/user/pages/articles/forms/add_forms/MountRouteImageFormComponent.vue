<template>
    <div class="row">
        <div class="container" v-if="this.category == 'mount_route'">
            <label for="email" class='col-md-12 control-label'>Upload outdoor climbing area mount image:</label>

            <div class="container">
                <div class="alert alert-danger" role="alert" v-if="image_errors.image">
                    {{ image_errors.image[0] }}
                </div>
                <div class="row">
                    <div class="form-groupe">
                        <button class="btn btn-primary mb-4"  @click="add_image_modal()">Add image</button>
                    </div>
                    <div class="form-groupe">
                        <button @click="get_spost_mounts_image(temporary_spost_mounts_id)" class="btn main-btn float-right" v-if="!image_is_refresh">Refresh ({{image_reset_id}})</button>
                        <span class="badge badge-primare mb-1 float-right" v-if="image_is_refresh">Updating...</span>
                    </div>
                </div>
                <div class="row">
                    <button class="btn btn-secondary float-left" @click="save_spost_mounts_images_sequence()">Save spost_mounts images sequence</button>
                </div>
            </div>
            
        </div>

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
        
    </div>
</template>

<script>
    import { SlickList, SlickItem } from 'vue-slicksort';
    import StackModal from '@innologica/vue-stackable-modal'  //https://innologica.github.io/vue-stackable-modal/#sample-css

    export default {
        components: {
            StackModal,
            SlickItem,
            SlickList,
        },
        props: [
            'category'
        ],
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
        }
    }
</script>

<style>

</style>