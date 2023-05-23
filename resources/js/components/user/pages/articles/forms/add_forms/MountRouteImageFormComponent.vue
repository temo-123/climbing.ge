<template>
    <div class="col-md-12">
        <div class="row">
            <div class="col-md-12">
                <div class="form-groupe">
                    <button class="btn btn-primary float-left" @click="add_new_mount_route_image_value()">Add new mount route image</button>
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
                        <tr v-for="mount_route_image in mount_route_images" :key="mount_route_image.id">
                            <td>
                                <form ref="myForm">
                                    <!-- <input type="hidden" name="MAX_FILE_SIZE" value="2097152" /> -->
                                    <input type="file" name="image" id="image" v-on:change="onFileChange($event, mount_route_image.id)">
                                </form> 
                            </td>
                            <td>|</td>
                            <td>
                                <button class="btn btn-danger" @click="del_mount_route_image(mount_route_image.id)">Delete</button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</template>

<script>
    // import { SlickList, SlickItem } from 'vue-slicksort';
    // import StackModal from '@innologica/vue-stackable-modal'  //https://innologica.github.io/vue-stackable-modal/#sample-css

    export default {
        props: [
            // 'category'
        ],
        components: {
            // StackModal,
            // SlickItem,
            // SlickList,
        },
        data(){
            return {
                mount_route_images: [],
            }
        },
        mounted() {
        },
        methods: {
            onFileChange(event, item_id){
                let image = event.target.files[0]
                let id = item_id - 1 
                this.mount_route_images[id]['image'] = image
                this.upload_img()
            },
            upload_img(){
            // upload_img(event){
                // this.$emit("upload_img", this.mount_route_images);
                this.$emit("mount_route_img", this.mount_route_images);
            },
            add_new_mount_route_image_value(){
                var new_item_id = this.mount_route_images.length+1
                this.mount_route_images.push(
                    {
                        id: new_item_id,
                        image: '',
                    }
                );
            },
            del_mount_route_image(id){
                this.removeObjectWithId(this.mount_route_images, id);
            },
            removeObjectWithId(arr, id) {
                const objWithIdIndex = arr.findIndex((obj) => obj.id === id);
                arr.splice(objWithIdIndex, 1);

                return arr;
            },
        }
    }
</script>

<style>

</style>