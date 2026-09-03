<template>
    <StackModal
        v-model="is_show_modal"
        :title="$t('admin.articles.spot_rock_drawing_table_title')"
        @close="close_modal()"
        :saveButton="{ visible: false }"
        :cancelButton="{ title: $t('common.close'), btnClass: { 'btn btn-primary': true } }">
        <div class="root">
            <div class="col-md-12">
                <div class="row">
                    <table style="width: 100%" v-if="images.length > 0">
                        <thead>
                            <tr>
                                <td>{{ $t('common.id') }}</td>
                                <td></td>
                                <td>{{ $t('admin.routes_sectors.col_routes_drawing') }}</td>
                                <td>{{ $t('common.edit') }}</td>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="image in images" :key="image.id">
                                <td>{{ image.id }}</td>
                                <td><img :src="'/public/images/spot_rocks_img/'+image.image" style="max-width:60px;max-height:60px;object-fit:cover;" /></td>
                                <td>
                                    <span v-if="image.jsons && image.jsons.length > 0" class="badge bg-success">{{ image.jsons.length }} {{ $t('admin.articles.sector_local_image_editor.has_drawing_badge') }}</span>
                                </td>
                                <td>
                                    <router-link :to="{ name: 'spotRockImageEditor', params: { id: image.id } }" class="btn btn-sm btn-info" @click="close_modal">
                                        <i class="fa fa-pencil-square-o" aria-hidden="true"></i>
                                    </router-link>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <p v-else class="text-muted mb-0">{{ $t('admin.articles.no_area_images_yet') }}</p>
                </div>
            </div>
        </div>
    </StackModal>
</template>

<script>
    export default {
        data(){
            return {
                is_show_modal: false,
                images: [],
            }
        },

        methods: {
            show_spot_rock_images_modal(article_id){
                axios
                .get('/get_sector/get_spot_rock_images/get_spot_rock_images/' + article_id)
                .then(response => {
                    this.images = response.data || []
                    this.is_show_modal = true
                })
                .catch(error => console.log(error));
            },

            close_modal(){
                this.is_show_modal = false
                this.images = []
            },
        }
    }
</script>
