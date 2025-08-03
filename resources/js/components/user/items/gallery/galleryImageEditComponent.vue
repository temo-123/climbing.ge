<template>
    <div class="width_100">
        <hr>
        <div class="col-md-12">
            <div class="row">
                <h2>Gallery images</h2>
            </div>
        </div>

        <div class="col-md-12">
            <div class="row">
                <p>Olredy added</p>
            </div>
        </div>

        <div class="col-md-12">
            <div class="row">
                <div class="col-md-12">
                    <table class="table table-hover" id="dev-table">
                        <thead>
                            <tr>
                                <th>Image</th>
                                <th>|</th>
                                <th>Delite</th>
                            </tr>
                        </thead>

                        <tbody>
                            <tr v-for="old_image in old_images" :key="old_image.id">
                                <td>
                                    <img class="img-responsive" :src="'../../../../' + image_path_prop + old_image.image" :alt="old_image.title" style="max-height: 250px;">
                                </td>
                                <td>|</td>
                                <td>
                                    <button class="btn btn-danger" @click="del_image_from_db(old_image.id)">Delete</button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-md-12">
            <div class="row">
                <p>Add new gallery inmages</p>
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

            <div class="row">
                <div class="col-md-12">
                    <table class="table table-hover" id="dev-table">
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
            'image_del_route_prop',
            'get_images_route_prop',
        ],
        data(){
            return {
                new_images: [],
                old_images: [],
            }
        },
        mounted() {
            // this.old_images = old_images_prop
            this.get_editing_images()
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
            get_editing_images(){
                // this.is_loading = true
                axios
                .get(this.get_images_route_prop+this.$route.params.id)
                .then(response => {
                    this.old_images = response.data
                })
                .catch(
                //     error => console.log(error)
                )
                .finally(() => this.is_loading = false);
            },
            del_image_from_db(image_id){
                if(confirm('Are you sure, you want delite this image?')){
                    axios
                    .delete(this.image_del_route_prop + image_id)
                    .then(response => {
                        this.get_editing_images()
                    })
                    .catch(
                        error => console.log(error)
                    );
                }
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
