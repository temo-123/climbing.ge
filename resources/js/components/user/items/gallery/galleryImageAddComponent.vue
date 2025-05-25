<template>
    <div class="width_100">
        <hr>
        <div class="col-md-12">
            <div class="row">
                <h2>Add new gallery images</h2>
            </div>
        </div>

        <div class="col-md-12">
            <div class="row">
                <div class="col-md-12">
                    <div class="form-groupe">
                        <button class="btn btn-primary float-left" @click="add_new_image_value()" >Add new imagee</button>
                    </div>
                </div>
            </div>

            <div class="row width_100">
                <div class="col-md-12">
                    <table class="table table-hover " id="dev-table">
                        <thead>
                            <tr>
                                <th>Image</th>
                                <th>|</th>
                                <th>Delite</th>
                            </tr>
                        </thead>

                        <tbody>
                            <tr v-for="image in new_images" :key="image.id">
                                <td>
                                    <form ref="myForm">
                                        <input type="file" name="image" id="image" v-on:change="onFileChange($event, image.id)">
                                    </form> 
                                </td>
                                <td>|</td>
                                <td>
                                    <button class="btn btn-danger" @click="del_image(image.id)">Delete</button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <div class="form-groupe">
                        <button class="btn btn-primary float-left" @click="add_new_image_value()">Add new imagee</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>


<script>
    // import { editor_config } from '../../../../mixins/editor/editor_config_mixin.js'
    export default {
        mixins: [
            // editor_config
        ],
        props: [
            // 'old_images_prop',
            'image_path_prop',
        ],
        data(){
            return {
                new_images: [],
                old_images: [],
            }
        },
        mounted() {
            // this.old_images = old_images_prop
        },
        methods: {
            onFileChange(event, item_id){
                let image = event.target.files[0]
                let id = item_id - 1 
                this.new_images[id]['image'] = image

                this.update_perent_component_data()
            },
            add_new_image_value(){
                var new_item_id = this.new_images.length+1

                this.new_images.push(
                    {
                        id: new_item_id,
                        image: '',
                    }
                );
            },
            del_image(id){
                const objWithIdIndex = this.new_images.findIndex((obj) => obj.id === id);
                this.new_images.splice(objWithIdIndex, 1);

                this.update_perent_component_data()
            },
            update_perent_component_data(){
                this.$emit("update_gallery_images", this.new_images);
                
            }
        }
    }
</script>
