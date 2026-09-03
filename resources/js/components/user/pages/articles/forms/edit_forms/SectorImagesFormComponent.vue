<template>
    <div>
        <gallery_images_edit
            :title_prop="$t('admin.articles.locale_form.sector_images_title')"
            image_path_prop="images/spot_rocks_img/"
            get_images_route_prop="/get_sector/get_spot_rock_images/get_spot_rock_images/"
            image_del_route_prop="/set_sector/set_spot_rock_images/del_spot_rock_image/"
            @update_gallery_images="$emit('area_images', $event)"
        />

        <!-- Route/approach-line drawing on top of an already-uploaded photo above —
             no new image entity, just a canvas layer on each existing spot_rocks_image.
             Newly picked (not-yet-saved) files above obviously can't be drawn on yet —
             this table only shows photos already saved to this article. -->
        <div class="mt-3" v-if="images.length > 0">
            <div class="d-flex align-items-center justify-content-between mb-1">
                <h6 class="mb-0">{{ $t('admin.articles.spot_rock_drawing_table_title') }}</h6>
                <button class="btn btn-sm btn-outline-secondary" @click="load_images">{{ $t('common.refresh') }}</button>
            </div>
            <table class="table table-hover table-sm">
                <thead>
                    <tr>
                        <th>{{ $t('common.id') }}</th>
                        <th></th>
                        <th>{{ $t('admin.routes_sectors.col_routes_drawing') }}</th>
                        <th>{{ $t('common.edit') }}</th>
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
                            <router-link :to="{ name: 'spotRockImageEditor', params: { id: image.id } }" class="btn btn-sm btn-info">
                                <i class="fa fa-pencil-square-o" aria-hidden="true"></i>
                            </router-link>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</template>

<script>
    import gallery_images_edit from '../../../../items/gallery/galleryImageEditComponent.vue'
    export default {
        components: { gallery_images_edit },
        data() {
            return {
                images: [],
            }
        },
        mounted() {
            this.load_images();
        },
        methods: {
            load_images() {
                const id = this.$route.params.id;
                if (!id) return;
                axios.get('/get_sector/get_spot_rock_images/get_spot_rock_images/' + id)
                    .then(response => { this.images = response.data || []; })
                    .catch(() => {});
            }
        }
    }
</script>
