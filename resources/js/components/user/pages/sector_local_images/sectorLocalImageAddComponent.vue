<template>
    <div class="row">
        <div class="row justify-content-center" v-show="is_loading">
            <div class="col-md-4">
                <img :src="'../../../../../../public/images/site_img/loading.gif'" alt="loading">
            </div>
        </div>

        <div class="col-md-12" v-show="!is_loading">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-groupe">
                        <button type='submit' form="myForm" class="btn btn-primary">Save</button>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-groupe">
                        <button class="btn btn-success float-right" @click="go_back()">Go back</button>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-12" v-show="!is_loading">
            <form ref="myForm" id="myForm" enctype="multipart/form-data" v-on:submit.prevent="save">
                <div class="form-group clearfix row">
                    <label for="name" class='col-md-2 control-label'> Title </label>
                    <div class="col-md-10">
                        <input type="text" name="name" v-model="data.title" class="form-control" required>
                    </div>
                </div>

                <div class="form-group clearfix row">
                    <label for="email" class='col-md-2 control-label'>Upload image:</label>
                    <div class="col-md-10">
                        <input type="file" name="image" id="image" required v-on:change="onFileChange">
                    </div>
                </div>
            </form>

            <div class="form-group clearfix row" v-if="sector_id != ''">
                <div class="col-md-12">
                    <div class="row">
                        <Editor 
                            ref="canvasEditor"
                            @canvas_data="update_canvas_data"
                        />
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <hr />
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-groupe">
                        <button class="btn btn-primary" @click="$refs.myChild.add_new_sector_model()">Add new sector</button>
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
                                <th>Sector id</th>
                                <th>|</th>

                                <th>Sector name</th>
                                <th>|</th>

                                <th>Delite</th>
                            </tr>
                        </thead>

                        <tbody v-if="image_sectors != []">
                            <tr v-for="image_sector in image_sectors" :key="image_sector.id">

                                <td>{{ image_sector.id }}</td>
                                <td>|</td>

                                <td>{{ image_sector.name }} </td>
                                <td>|</td>
                                <td>
                                    <button type="submit" class="btn btn-danger" @click="del_sector(image_sector.id)">Delete</button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <addSectorModal 
            ref="myChild"
            @sector="add_new_sector"
        />
    </div>
</template>

<script>
    import addSectorModal from './assets/addSectorModalComponent.vue'

    export default {
        components: {
            addSectorModal
        },
        props: [
            // 'status',
        ],
        data(){
            return {
                add_sector_modal: false,

                image_sectors: [],

                is_loading: false,

                data: {
                    title: '',
                    image: ''
                },
            }
        },

        mounted() {
            document.querySelector('body').style.marginLeft = '0';
            document.querySelector('.admin_page_header_navbar').style.marginLeft = '0';
        },

        methods: {
            add_new_sector(e){
                if(e != 'Select sector'){
                    var new_item_id = this.image_sectors.length+1
                    this.image_sectors.push(
                        {
                            id: e.id,
                            name: e.name,
                        }
                    );
                }
            },
            del_sector(id){
                this.removeObjectWithId(this.image_sectors, id);
            },
            removeObjectWithId(arr, id) {
                const objWithIdIndex = arr.findIndex((obj) => obj.id === id);
                arr.splice(objWithIdIndex, 1);

                return arr;
            },
            onFileChange(e){
                this.data.image = e.target.files[0];
            },
            save(){
                this.is_loading = true
                const config = {
                    headers: { 'content-type': 'multipart/form-data' }
                }
    
                let formData = new FormData();
                formData.append('image', this.data.image);
                formData.append('image_sectors', JSON.stringify(this.image_sectors))
                formData.append('title', this.data.title)
   
                axios.post('/set_sector_local_images/add_sector_local_image/', 
                    formData,
                    config
                )
                .then(response => {
                    this.$router.go(-1)
                })
                .catch(
                    //
                )
                .finally(()=>{this.is_loading = false});
            },
            go_back(){
                if (window.confirm('Added information will be deleted!!! Are you sure, you want go back?')) {
                    this.$router.go(-1)
                }
            }
        }

}
</script>
