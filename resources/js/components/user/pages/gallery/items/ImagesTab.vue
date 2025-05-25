<template>
    <div>
        <div class="row">
            <div class="col-md-12">
                <div class="form-groupe float-right">
                    <button class="btn btn-success"  @click="update_data()">refresh</button>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-4">
                <div class="thumbnail">
                    <img @click="show_add_image_modal()" alt="Add image" src="images/site_img/function_imgs/add_image.png">
                </div>
            </div>
            <div class="col-md-4 mt-3" v-for="image in headSliderImages" :key="image.id">
                <div class="thumbnail">

                    <img @click="show_image_modal(image.id)" :alt="image.title" :src="image_path_prop+image.image" class="cursor_pointer">
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
                            <button @click="show_edit_image_modal(image.id)" type="submit" class="btn btn-primary float-left">    
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

        <edit_image_modal ref="show_edit_image_modal" :image_path_prop="image_path_prop" @update="update_data()"/>
        <show_image_modal ref="show_image_modal" :image_path_prop="image_path_prop"/>

        <add_image_modal ref="show_add_image_modal" :category_prop="category_prop" @update="update_data()" />

    </div>
</template>


<script>
    import add_image_modal from './modals/AddImageModal.vue'
    import edit_image_modal from './modals/EditImageModal.vue'
    import show_image_modal from './modals/ShowImageModal.vue'
    export default {
        data(){
            return {
                headSliderImages: [],
                tab_num: 1
            }
        },
        components: {
            add_image_modal,
            edit_image_modal,
            show_image_modal,
        },
        props: [
            'image_path_prop',
            'category_prop'
        ],
        mounted() {
            // this.get_header_images()
        },
        watch: {
            '$route' (to, from) {
                window.scrollTo(0,0)
            },
            images_props: function(){
                this.images_props = this.headSliderImages
            },
        },
        methods: {
            show_add_image_modal(){
                this.$refs.show_add_image_modal.show_modal()
            },
            show_image_modal(id){
                this.$refs.show_image_modal.show_modal(id)
            },
            show_edit_image_modal(id){
                this.$refs.show_edit_image_modal.show_modal(id)
            },
            del_image(slide_id){
                if(confirm('Are you sure, you want delite it?')){
                    axios
                    .delete('/head_slider/del_slide/'+slide_id)
                    .then(Response => {
                        this.update_data();
                    })
                    .catch(error => console.log(error))
                }
            },
            update_images(data){
                this.headSliderImages = data
            },
            update_data(){
                this.$emit("get_all_images");
            }
        }
    }
</script>