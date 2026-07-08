<template>
    <div class="row">
        <div class="row justify-content-center" v-show="is_loading">
            <div class="col-md-4">
                <img :src="'/images/site_img/loading.gif'" alt="loading">
            </div>
        </div>

        <div class="col-md-12" v-show="!is_loading">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-groupe">
                        <button type='submit' form="myForm" class="btn btn-primary">{{ $t('common.save') }}</button>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-groupe">
                        <button class="btn btn-success float-right" @click="go_back()">{{ $t('common.back') }}</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-12" v-show="!is_loading">
            <form ref="myForm" id="myForm" enctype="multipart/form-data" v-on:submit.prevent="update">
                <div class="form-group clearfix row">
                    <label for="name" class='col-md-2 control-label'> {{ $t('common.title') }} </label>
                    <div class="col-md-10">
                        <input type="text" name="name" v-model="data.title" class="form-control" required>
                    </div>
                </div>

                <div class="form-group clearfix row">
                    <single_image_edit
                        :title_prop="$t('admin.articles.sector_local_image_title')"
                        :existing_image_url_prop="data.image ? '/public/images/sector_local_img/'+data.image : ''"
                        @update_single_image="image = $event"
                    />
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
                        <button class="btn btn-primary" @click="$refs.myChild.add_new_sector_model()">{{ $t('admin.articles.add_new_sector_btn') }}</button>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-groupe">
                        <button @click="get_editing_sectors" class="btn btn-success float-right" v-if="!sectors_refresh">{{ $t('common.refresh') }}</button>
                        <span class="badge badge-primare mb-1 float-right" v-if="sectors_refresh">{{ $t('admin.articles.updating_ellipsis') }}</span>
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

                                <th>{{ $t('admin.articles.col_sector_id') }}</th>
                                <th>|</th>

                                <th>{{ $t('admin.users.sector_name_col') }}</th>
                                <th>|</th>

                                <th>{{ $t('common.delete') }}</th>
                            </tr>
                        </thead>

                        <th v-if="old_image_sectors.length">{{ $t('admin.articles.previously_added_label') }}</th>

                        <tbody v-if="old_image_sectors != []">
                            <tr v-for="old_image_sector in old_image_sectors" :key="old_image_sector.id">
                                <!-- <td>{{ old_image_sector.id }}</td>
                                <td>|</td> -->

                                <td>{{ old_image_sector.id }}</td>
                                <td>|</td>

                                <td>{{ old_image_sector.name }} </td>
                                <td>|</td>
                                <td>
                                    <button type="submit" class="btn btn-danger" @click="del_image_sector_from_db(old_image_sector.id)">{{ $t('common.delete') }}</button>
                                </td>
                            </tr>
                        </tbody>

                        <th v-if="new_image_sectors.length">{{ $t('admin.articles.added_now_label') }}</th>

                        <tbody v-if="new_image_sectors != []">
                            <tr v-for="new_image_sector in new_image_sectors" :key="new_image_sector.id">
                                <!-- <td>{{ new_image_sector.id }}</td>
                                <td>|</td> -->

                                <td>{{ new_image_sector.id }}</td>
                                <td>|</td>

                                <td>{{ new_image_sector.name }} </td>
                                <td>|</td>
                                <td>
                                    <button type="submit" class="btn btn-danger" @click="del_sector(new_image_sector.id)">{{ $t('common.delete') }}</button>
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
    import single_image_edit from '../../items/single_image/singleImageEditComponent.vue'

    export default {
        components: {
            addSectorModal,
            single_image_edit
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
                .get('/set_sector/set_sector_local_images/get_editing_locale_image/'+this.$route.params.id)
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
                .get('/set_sector/set_sector_local_images/get_editing_sectors/'+this.$route.params.id)
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
                if(confirm(this.$t('admin.common.confirm_delete'))){
                    axios
                    .post('/set_sector/set_sector_local_images/del_image_sector_from_db/' + this.$route.params.id + '/' + sector_id,{
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
            update(){
                this.is_loading = true
                const config = {
                    headers: { 'content-type': 'multipart/form-data' }
                }
    
                let formData = new FormData();
                formData.append('image', this.image);
                formData.append('image_sectors', JSON.stringify(this.new_image_sectors))
                formData.append('title', this.data.title)
   
                axios.post('/set_sector/set_sector_local_images/update_image/'+this.$route.params.id, 
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
                if (window.confirm(this.$t('common.confirm_leave_unsaved'))) {
                    this.$router.go(-1)
                }
            }
        }

}
</script>
