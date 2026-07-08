<template>
    <stack-modal
            :show="is_show_sector_local_image_modal"
            :title="$t('admin.users.sector_local_image_info_title')"
            @close="close_modal()"
            :modal-class="{ [MTPModalClass]: true }"
            :saveButton="{ visible: true }"
            :cancelButton="{ title: $t('common.close'), btnClass: { 'btn btn-primary': true } }">
            <div>
                <div class="root">
                    <div class="col-md-12">
                        <div class="row">
                            <img :src="'/public/images/sector_local_img/'+sector_sector_local_image_pitchs_for_modal.image.image" :alt="$t('admin.users.locale_sectors_image_alt')">
                            <!-- <div class="row"> -->
                                <div class="col-md-12">
                                    <table class="table table-hover" id="dev-table">
                                        <thead>
                                            <tr>
                                                <th>{{ $t('common.id') }}</th>
                                                <th>|</th>
                                                <th>{{ $t('admin.users.sector_name_col') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr v-for="sector in sector_sector_local_image_pitchs_for_modal.sectors" :key="sector.id">
                                                <td>{{sector.id}}</td>
                                                <td>|</td>
                                                <td>{{sector.name}}</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            <!-- </div> -->
                        </div>
                    </div>
                </div>
            </div>
            <div slot="modal-footer">
                <div class="modal-footer">
                    <router-link :to="{name: 'sectorLocalImagesListEdit', params: { id: sector_sector_local_image_pitchs_for_modal.image.id } }" v-if="$can('edit', 'sector_local_images')"> 
                        <button class="btn btn-primary " ><i class="fa fa-pencil" aria-hidden="true"></i></button>
                    </router-link>
                </div>
            </div>
        </stack-modal>
</template>

<script>
    // import { SlickList, SlickItem } from 'vue-slicksort'; //https://github.com/Jexordexan/vue-slicksort
    // import StackModal from '@innologica/vue-stackable-modal'  // Global now
    export default {
        components: {
            // StackModal,
            // SlickItem,
            // SlickList,
        },
        data(){
            return {
                is_show_sector_local_image_modal: false,
                sector_sector_local_image_pitchs_for_modal:[],
                MTPModalClass: ''
            }
        },

        mounted() {
            //
        },

        methods: {
            show_sector_local_image_modal(id){
                if (!this.is_show_sector_local_image_modal) {
                    axios
                    .get('/get_sector/get_sector_local_images/get_sector_local_img_for_modal/'+ id)
                    .then(response => {
                        this.sector_sector_local_image_pitchs_for_modal = response.data

                        this.is_show_sector_local_image_modal=true
                    })
                    .catch(
                        error => console.log(error)
                    );
                }
                else{
                    this.sector_sector_local_image_pitchs_for_modal = ""
                }
            },
            close_modal(){
                this.is_show_sector_local_image_modal=false
            }
        }
    }
</script>