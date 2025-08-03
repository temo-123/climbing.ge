<template>
    <div class="col-md-12">
        <hr>
        <div class="row">
            <div class="col-md-12">
                <div class="row">
                    <h2>Sector images</h2>
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
                                <tr v-for="image in spot_rocks_old_images" :key="image.id">
                                    <td>
                                        <img class="img-responsive" :src="'../../../../images/spot_rocks_img/'+image.image" :alt="image.image" style="max-height: 250px;">
                                    </td>
                                    <td>|</td>
                                    <td>
                                        <button class="btn btn-danger" @click="del_old_sector_image_from_db(image.id)">Delete</button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-12">
            <div class="row">
                <p>Add new sectors inmages</p>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="form-groupe">
                    <button class="btn btn-primary float-left" @click="add_new_sector_image_value()">Add new sector image</button>
                </div>
            </div>
            <!-- <div class="col-md-6">
                <div class="form-groupe">
                    <button @click="get_adres" class="btn btn-success float-right" v-if="!sectors_refresh">Refresh ({{adres_reset_id}})</button>
                    <span class="badge badge-primare mb-1 float-right" v-if="sectors_refresh">Updating...</span>
                </div>
            </div> -->
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
                        <tr v-for="sector_image in sector_images" :key="sector_image.id">
                            <td>
                                <form ref="myForm">
                                    <!-- <input type="hidden" name="MAX_FILE_SIZE" value="2097152" /> -->
                                    <input type="file" name="image" id="image" v-on:change="onFileChange($event, sector_image.id)">
                                </form> 
                            </td>
                            <td>|</td>
                            <td>
                                <button class="btn btn-danger" @click="del_sector_image(sector_image.id)">Delete</button>
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
                sector_images: [],
                spot_rocks_old_images: [],
            }
        },
        mounted() {
            this.get_old_sector_images()
        },
        methods: {
            get_old_sector_images(){
                axios
                .get("/spot_rock_images/get_spot_rock_images/"+this.$route.params.id)
                .then(response => {
                    this.spot_rocks_old_images = response.data
                })
                .catch(
                    error => console.log(error)
                );
            },
            del_old_sector_image_from_db(image_id){
                if(confirm('Are you sure, you want delite this image?')){
                    axios
                    .delete("/spot_rock_images/del_spot_rock_image/"+image_id)
                    .then(response => {
                        this.get_old_sector_images()
                    })
                    .catch(
                        error => console.log(error)
                    );
                }
            },

            onFileChange(event, item_id){
                let image = event.target.files[0]
                let id = item_id - 1 
                this.sector_images[id]['image'] = image
                this.upload_img()
            },
            upload_img(event){
                this.$emit("area_images", this.sector_images);
            },
            add_new_sector_image_value(){
                var new_item_id = this.sector_images.length+1
                this.sector_images.push(
                    {
                        id: new_item_id,
                        image: '',
                    }
                );
            },
            del_sector_image(id){
                this.removeObjectWithId(this.sector_images, id);
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