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
            <form ref="myForm" id="myForm" enctype="multipart/form-data" v-on:submit.prevent="update">
                <div class="form-group clearfix row">
                    <label for="name" class='col-md-2 control-label'> Title </label>
                    <div class="col-md-10">
                        <input type="text" name="name" v-model="data.title" class="form-control" required>
                    </div>
                </div>

                <div class="form-group clearfix row">
                    <label for="email" class='col-md-2 control-label'>Active image:</label>
                    <div class="col-md-5">
                        <img :src="'/public/images/sector_local_img/'+data.image" alt="Locale sectors image">
                    </div>
                </div>

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

                <div class="form-group clearfix row">
                    <label for="email" class='col-md-2 control-label'>Upload new image:</label>
                    <div class="col-md-10">
                        <input type="file" name="image" id="image" v-on:change="onFileChange">
                    </div>
                </div>
            </form>


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
                <div class="col-md-6">
                    <div class="form-groupe">
                        <button @click="get_editing_sectors" class="btn btn-success float-right" v-if="!sectors_refresh">Refresh</button>
                        <span class="badge badge-primare mb-1 float-right" v-if="sectors_refresh">Updating...</span>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <table class="table table-hover" id="dev-table">
                        <thead>
                            <tr>
                                <!-- <th>ID</th>
                                <th>|</th> -->

                                <th>Sector id</th>
                                <th>|</th>

                                <th>Sector name</th>
                                <th>|</th>

                                <th>Delite</th>
                            </tr>
                        </thead>

                        <th v-if="old_image_sectors.length">Previously adde</th>

                        <tbody v-if="old_image_sectors != []">
                            <tr v-for="old_image_sector in old_image_sectors" :key="old_image_sector.id">
                                <!-- <td>{{ old_image_sector.id }}</td>
                                <td>|</td> -->

                                <td>{{ old_image_sector.id }}</td>
                                <td>|</td>

                                <td>{{ old_image_sector.name }} </td>
                                <td>|</td>
                                <td>
                                    <button type="submit" class="btn btn-danger" @click="del_image_sector_from_db(old_image_sector.id)">Delete</button>
                                </td>
                            </tr>
                        </tbody>

                        <th v-if="new_image_sectors.length">Added naw</th>

                        <tbody v-if="new_image_sectors != []">
                            <tr v-for="new_image_sector in new_image_sectors" :key="new_image_sector.id">
                                <!-- <td>{{ new_image_sector.id }}</td>
                                <td>|</td> -->

                                <td>{{ new_image_sector.id }}</td>
                                <td>|</td>

                                <td>{{ new_image_sector.name }} </td>
                                <td>|</td>
                                <td>
                                    <button type="submit" class="btn btn-danger" @click="del_sector(new_image_sector.id)">Delete</button>
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
    import Editor from '../../items/canvas/EditorComponent.vue'
    import addSectorModal from './addSectorModalComponent.vue'

    export default {
        components: {
            Editor,
            addSectorModal
        },
        props: [
            // 'status',
        ],
        data(){
            return {
                old_image_sectors: [],
                new_image_sectors: [],

                data: {
                    title: '',
                    image: ''
                },
                image: '',

                is_loading: false,

                sectors_refresh: false,
            }
        },

        mounted() {
            // this.get_all_sectors()
            this.get_editing_image()

            document.querySelector('body').style.marginLeft = '0';
            document.querySelector('.admin_page_header_navbar').style.marginLeft = '0';
        },

        methods: {
            get_editing_image(){
                this.is_loading = true
                axios
                .get('/sector_local_images/'+this.$route.params.id)
                .then(response => {
                    this.data.title = response.data.sector_local_image.title
                    this.data.image = response.data.sector_local_image.image

                    this.old_image_sectors = response.data.sector_local_image_sectors
                })
                .catch(
                    //
                )
                .finally(()=>{this.is_loading = false});
            },
            get_editing_sectors(){
                axios
                .get('/get_editing_sectors/'+this.$route.params.id)
                .then(response => {
                    this.old_image_sectors = response.data
                })
                .catch(
                    //
                );
            },
            add_new_sector(e){
                if(e != 'Select sector'){
                    var new_item_id = this.new_image_sectors.length+1
                    this.new_image_sectors.push(
                        {
                            id: e.id,
                            name: e.name,
                        }
                    );

                    this.close_add_sector_modal()
                }
            },
            del_image_sector_from_db(sector_id){
                if(confirm('Are you sure, you want delite itt?')){
                    axios
                    .post('/del_image_sector_from_db/' + this.$route.params.id + '/' + sector_id,{
                        _method: 'delete',
                    })
                    .then(response => {
                        // alert(response.data)
                        this.get_editing_sectors()
                    })
                    .catch(
                        //
                    );
                }
            },
            del_sector(id){
                this.removeObjectWithId(this.old_image_sectors, id);
            },
            removeObjectWithId(arr, id) {
                const objWithIdIndex = arr.findIndex((obj) => obj.id === id);
                arr.splice(objWithIdIndex, 1);

                return arr;
            },
            onFileChange(e){
                this.image = e.target.files[0];
            },
            update(){
                this.is_loading = true
                const config = {
                    headers: { 'content-type': 'multipart/form-data' }
                }
    
                let formData = new FormData();
                formData.append('image', this.image);
                formData.append('image_sectors', JSON.stringify(this.new_image_sectors))
                formData.append('title', this.data.title)
   
                axios.post('/sector_local_images/update_image/'+this.$route.params.id, 
                // axios.put('/sector_local_images/'+this.$route.params.id, 
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
