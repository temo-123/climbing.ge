<template>
    <div class="col-md-12">
        <div class="row justify-content-center" v-if="is_loading">
            <div class="col-md-4">
                <img :src="'/images/site_img/loading.gif'" alt="loading">
            </div>
        </div>

        <div class="row" v-if="!is_loading">
            <div class="form-group">
                <button
                    type="submit"
                    class="btn btn-primary"
                    v-on:click="go_back(false)"
                >
                    Beck
                </button>
            </div>
        </div>
        <div class="row" v-if="!is_loading">
            <div class="form-group">
                <button
                    type="submit"
                    class="btn btn-primary"
                    form="sector_editing_form"
                >
                    Save
                </button>
            </div>
        </div>
        <div v-if="!is_loading">
            <form
                @submit.prevent="edit_sector"
                id="sector_editing_form"
                class="contact-form"
                method="POST"
                enctype="multipart/form-data"
            >

                <div class="form-group clearfix row">
                    <label for="published" class='col-md-2 control-label '> Publish </label>
                    <div class="col-md-10">
                        <select class="form-control" v-model="data.published" name="published" required> 
                            <option :value="0">Not public</option> 
                            <option :value="1">Public</option> 
                        </select> 
                    </div>
                </div>
                <div class="form-group clearfix row">
                    <label for="name" class="col-md-2 control-label">
                        Sector name
                    </label>
                    <div class="col-md-10">
                        <input
                            type="text"
                            v-model="data.name"
                            name="name"
                            placeholder="Name"
                            class="form-control textarea"
                            required
                        />
                        <div
                            class="alert alert-danger"
                            role="alert"
                            v-if="errors.name"
                        >
                            {{ errors.name[0] }}
                        </div>
                    </div>
                </div>

                <div class="form-group clearfix row">
                    <label class="col-md-2 control-label"> Region </label>
                    <div class="col-md-10">
                        <select
                            class="form-control"
                            name="article_id"
                            v-model="data.article_id"

                            required
                        >
                            <option
                                v-bind:value="''"
                                disabled
                            >
                                Select outdoor area
                            </option>
                            <option
                                v-for="region in regions"
                                :key="region"
                                v-bind:value="region.id"
                            >
                                {{ region.url_title }}
                            </option>
                        </select>
                        <div
                            class="alert alert-danger"
                            role="alert"
                            v-if="errors.article_id"
                        >
                            {{ errors.article_id[0] }}
                        </div>
                    </div>
                </div>

                <!-- <div class="form-group clearfix row">
                    <label class="col-md-2 control-label"> Text </label>
                    <div class="col-md-10">
                        <big_editor v-model="data.text" />
                    </div>
                </div> -->

                <text_block_localization
                    v-model:en_value="data.us_description"
                    v-model:ka_value="data.ka_description"
                />

                <hr />

                <div class="form-group clearfix row">
                    <label for="name" class="col-md-2 control-label">
                        wolking_time
                    </label>
                    <div class="col-md-10">
                        <input
                            type="text"
                            v-model="data.wolking_time"
                            wolking_time="wolking_time"
                            placeholder="Wolking time"
                            class="form-control textarea"
                        />
                        <div
                            class="alert alert-danger"
                            role="alert"
                            v-if="errors.wolking_time"
                        >
                            {{ errors.wolking_time[0] }}
                        </div>
                    </div>
                </div>

                <hr />

                <div class="form-group clearfix row">
                    <label class="col-md-2 control-label"> Shadow time: </label>
                    <div class="col-md-10">
                        <div class="row">
                            <div class="col-md-4">
                                <label for="vehicle1" class="sector_checbox_title">
                                    all_day_in_shade</label
                                ><br />
                                <img
                                    class="sun_svg"
                                    :src="'../../images/svg/in the shade whole day.svg'"
                                    alt="In the shade whole day"
                                    title="In the shade whole day"
                                />
                                <input
                                    type="checkbox"
                                    v-model="data.all_day_in_shade"
                                    name="all_day"
                                    value="1"
                                    class="largerCheckbox"
                                />
                            </div>
                            <div class="col-md-4">
                                <label for="vehicle1"> all_day_in_sun</label><br />
                                <img
                                    class="sun_svg"
                                    :src="'../../images/svg/in the sun the whole day.svg'"
                                    alt="In the sun the whole day"
                                    title="In the sun the whole day"
                                />
                                <input
                                    type="checkbox"
                                    v-model="data.all_day_in_sun"
                                    name="no_sun"
                                    value="1"
                                    class="largerCheckbox"
                                />
                            </div>
                            <div class="col-md-4">
                                <label for="vehicle1"> in_the_shade_afternoon</label
                                ><br />
                                <img
                                    class="sun_svg"
                                    :src="'../../images/svg/in shade in the afternoon.svg'"
                                    alt="In shade in the afternoon"
                                    title="In shade in the afternoon"
                                />
                                <input
                                    type="checkbox"
                                    v-model="data.in_the_shade_afternoon"
                                    name="afternoon"
                                    value="1"
                                    class="largerCheckbox"
                                />
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <label for="vehicle1"> in_the_shade_befornoon</label
                                ><br />
                                <img
                                    class="sun_svg"
                                    :src="'../../images/svg/in shade befor noon.svg'"
                                    alt="In shade befor noon"
                                    title="In shade befor noon"
                                />
                                <input
                                    type="checkbox"
                                    v-model="data.in_the_shade_befornoon"
                                    name="beforenoon"
                                    value="1"
                                    class="largerCheckbox"
                                />
                            </div>
                            <div class="col-md-4">
                                <label for="vehicle1"> in_shade_after_10</label
                                ><br />
                                <img
                                    class="sun_svg"
                                    :src="'../../images/svg/in shade after 10.00 am.svg'"
                                    alt="In shade after 10.00 am"
                                    title="In shade after 10.00 am"
                                />
                                <input
                                    type="checkbox"
                                    v-model="data.in_shade_after_10"
                                    name="beforenoon"
                                    value="1"
                                    class="largerCheckbox"
                                />
                            </div>
                            <div class="col-md-4">
                                <label for="vehicle1"> in_shade_after_15</label
                                ><br />
                                <img
                                    class="sun_svg"
                                    :src="'../../images/svg/in shade after 15.00 pm.svg'"
                                    alt="In shade after 15.00 pm"
                                    title="In shade after 15.00 pm"
                                />
                                <input
                                    type="checkbox"
                                    v-model="data.in_shade_after_15"
                                    name="beforenoon"
                                    value="1"
                                    class="largerCheckbox"
                                />
                            </div>
                        </div>
                    </div>
                </div>

                <hr />

                <div class="form-group clearfix row">
                    <label class="col-md-2 control-label"> Sector relief: </label>
                    <div class="col-md-10 row">
                        <div class="col-md-3">
                            <label for="vehicle1"> slabby</label><br />
                            <img
                                class="sun_svg"
                                :src="'../../images/svg/slabby.svg'"
                                alt="Slabby"
                                title="Slabby"
                            />
                            <input
                                type="checkbox"
                                v-model="data.slabby"
                                name="no_sun"
                                value="1"
                                class="largerCheckbox"
                            />
                        </div>
                        <div class="col-md-3">
                            <label for="vehicle1"> vertical</label><br />
                            <img
                                class="sun_svg"
                                :src="'../../images/svg/vertical.svg'"
                                alt="Vertical"
                                title="Vertical"
                            />
                            <input
                                type="checkbox"
                                v-model="data.vertical"
                                name="afternoon"
                                value="1"
                                class="largerCheckbox"
                            />
                        </div>
                        <div class="col-md-3">
                            <label for="vehicle1"> overhang</label><br />
                            <img
                                class="sun_svg"
                                :src="'../../images/svg/overhang.svg'"
                                alt="Overhang"
                                title="Overhang"
                            />
                            <input
                                type="checkbox"
                                v-model="data.overhang"
                                name="beforenoon"
                                value="1"
                                class="largerCheckbox"
                            />
                        </div>
                        <div class="col-md-3">
                            <label for="vehicle1"> roof</label><br />
                            <img
                                class="sun_svg"
                                :src="'../../images/svg/roof.svg'"
                                alt="Overhang"
                                title="Overhang"
                            />
                            <input
                                type="checkbox"
                                v-model="data.roof"
                                name="beforenoon"
                                value="1"
                                class="largerCheckbox"
                            />
                        </div>
                    </div>
                </div>

                <hr />

                <div class="form-group clearfix row">
                    <label class="col-md-2 control-label"> Kids: </label>
                    <div class="col-md-10 row">
                        <div class="col-md-6">
                            <label for="vehicle1">
                                Femily
                                <strong v-if="!data.for_family">is not friendly</strong>
                                <strong v-else>friendly</strong>
                            </label>
                            <br />
                            <img
                                class="sun_svg"
                                :src="'../../images/svg/for family.svg'"
                                alt="Slabby"
                                title="Slabby"
                            />
                            <input
                                type="checkbox"
                                v-model="data.for_family"
                                name="no_sun"
                                value="1"
                                class="largerCheckbox"
                            />
                        </div>
                        <div class="col-md-6">
                            <label for="vehicle1">
                                Kids climbing
                                <strong v-if="!data.for_kids">is not friendly</strong>
                                <strong v-else>friendly</strong>
                            </label>
                            <br />
                            <img
                                class="sun_svg"
                                :src="'../../images/svg/for kids.svg'"
                                alt="Vertical"
                                title="Vertical"
                            />
                            <input
                                type="checkbox"
                                v-model="data.for_kids"
                                name="afternoon"
                                value="1"
                                class="largerCheckbox"
                            />
                        </div>
                    </div>
                </div>

                <hr />
                
                <div class="form-group clearfix row">
                    <label class="col-md-2 control-label"> Helmet required: </label>
                    <div class="col-md-10 row">
                        <div class="col-md-6">
                            <label for="vehicle1"> Is helmet required</label><br />
                            <img
                                class="sun_svg"
                                :src="'../../images/svg/climbing-helmet.jpg'"
                                alt="Vertical"
                                title="Vertical"
                            />
                            <input
                                type="checkbox"
                                v-model="data.is_helmet"
                                name="helmet_required"
                                value="1"
                                class="largerCheckbox"
                            />
                        </div>                    
                    </div>
                </div>

                <hr />
            </form>
                            
            <div class="col-md-12 mb-3">
                <h4>Already added images</h4>
                <table class="table table-sm sector-img-table">
                    <thead>
                        <tr>
                            <td>ID</td>
                            <td>Num</td>
                            <td>Canvas Drawing <small class="text-muted">(JSON data)</small></td>
                            <td>Saved Image <small class="text-muted">(on disk)</small></td>
                            <td>Actions</td>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="image in sector_old_images" :key="image.id">
                            <td>{{ image.id }}</td>
                            <td>{{ image.num }}</td>

                            <!-- Canvas preview -->
                            <td class="img-cell">
                                <canvas-json-show
                                    :fetch_url="'get_route/get_route_jsons_for_sector_image'"
                                    :fetch_id="image.id"
                                    :image_src="image.has_original
                                        ? '/public/images/sector_img/origin_img/' + image.image
                                        : null"
                                    :preview_all="true"
                                    refresh_event="route-drawing-updated"
                                />
                            </td>

                            <!-- Saved composite image -->
                            <td class="img-cell">
                                <img
                                    class="saved-img"
                                    :src="'/public/images/sector_img/' + image.image + '?v=' + (img_cache_bust[image.id] || 0)"
                                    :alt="image.image"
                                />
                            </td>

                            <!-- Actions -->
                            <td class="actions-cell">

                                <!-- Delete entire image -->
                                <button
                                    class="btn btn-danger btn-sm mb-2 d-block w-100"
                                    @click="del_sector_image_from_db(image.id)"
                                >
                                    <i class="fa fa-trash"></i> Delete Image
                                </button>

                                <!-- Del Canvas: inline two-step confirm -->
                                <div class="mb-2">
                                    <template v-if="canvas_confirm_pending === image.id">
                                        <span class="text-warning small me-1">Sure?</span>
                                        <button
                                            class="btn btn-danger btn-sm me-1"
                                            :disabled="canvas_deleting === image.id"
                                            @click="del_sector_canvas(image.id)"
                                        >Yes</button>
                                        <button
                                            class="btn btn-secondary btn-sm"
                                            @click="canvas_confirm_pending = null"
                                        >No</button>
                                    </template>
                                    <template v-else>
                                        <button
                                            class="btn btn-warning btn-sm"
                                            :disabled="canvas_deleting === image.id"
                                            @click="canvas_confirm_pending = image.id"
                                        >
                                            <i class="fa fa-eraser"></i>
                                            {{ canvas_deleting === image.id ? 'Deleting...' : 'Del Canvas' }}
                                        </button>
                                    </template>
                                    <span
                                        v-if="canvas_del_status[image.id]"
                                        class="ms-1 small"
                                        :class="canvas_del_status[image.id] === 'ok' ? 'text-success' : 'text-danger'"
                                    >
                                        {{ canvas_del_status[image.id] === 'ok' ? '✓ Cleared' : '✗ Error' }}
                                    </span>
                                </div>

                                <!-- Replace image file -->
                                <div>
                                    <label class="small text-muted mb-0">Replace image:</label>
                                    <input
                                        type="file"
                                        accept="image/*"
                                        class="form-control form-control-sm mt-1"
                                        :disabled="img_uploading === image.id"
                                        @change="uploadSectorImage(image.id, $event)"
                                    />
                                    <span
                                        v-if="img_upload_status[image.id]"
                                        class="small"
                                        :class="img_upload_status[image.id] === 'ok' ? 'text-success' : 'text-danger'"
                                    >
                                        {{ img_upload_status[image.id] === 'ok' ? '✓ Uploaded' : '✗ Upload failed' }}
                                    </span>
                                    <span v-if="img_uploading === image.id" class="small text-muted">Uploading...</span>
                                </div>

                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <gallery_images_add
                title_prop="Add New Sector Images"
                @update_gallery_images="sector_new_images = $event"
            />
        </div>
    </div>
</template>
<script>
    import Uploader from "vux-uploader-component";
    import { SlickList, SlickItem } from "vue-slicksort";
    import gallery_images_add from '../../../items/gallery/galleryImageAddComponent.vue'
    import text_block_localization from '../../../items/form/parts/TextBlockLocalithationComponent.vue'

    export default {
        mixins: [],
        components: {
            Uploader,
            SlickItem,
            SlickList,
            gallery_images_add,
            text_block_localization,
        },
        data() {
            return {
                ka_description_editor: {},
                us_description_editor: {},
                
                fileList: [], //https://github.com/eJayYoung/vux-uploader-component
                regions: "",

                is_loading: false,

                editorConfig: {},

                errors: [],
                // image_errors: [],

                data: {
                    published: 0,
                    name: "",
                    image: "",
                    success: "",

                    article_id: "",
                    name: "",
                    // text: "",
                    ka_description: "",
                    us_description: "",

                    all_day_in_shade: null,
                    all_day_in_sun: null,
                    in_the_shade_afternoon: null,
                    in_the_shade_befornoon: null,
                    in_shade_after_10: null,
                    in_shade_after_15: null,

                    slabby: null,
                    vertical: null,
                    overhang: null,
                    roof: null,

                    for_family: null,
                    for_kids: null,
                    wolking_time: null,

                    is_helmet: null,
                },

                // temporary_sector_id: 0,

                is_back_action_query: true,
                // image_reset_id: 0,

                // sector_new_images: ['Item 1', 'Item 2', 'Item 3', 'Item 4', 'Item 5', 'Item 6', 'Item 7', 'Item 10'],
                sector_new_images:      [],
                sector_old_images:      [],
                canvas_confirm_pending: null,
                canvas_deleting:        null,
                canvas_del_status:      {},
                img_uploading:          null,
                img_upload_status:      {},
                img_cache_bust:         {},
            };
        },
        mounted() {
            this.get_editing_sector_data();
        
            document.querySelector('body').style.marginLeft = '0';
            document.querySelector('.admin_page_header_navbar').style.marginLeft = '0';
        },
        // beforeRouteLeave (to, from, next) {
        //     this.go_back()
        // },
        beforeRouteLeave (to, from, next) {
            if(this.is_back_action_query == true){
                if (window.confirm('Added information will be deleted!!! Are you sure, you want go back?')) {
                    this.is_back_action_query = false;
                    next()
                } else {
                    next(false)
                }
            }else {
                next()
            }
        },
        methods: {
            async del_sector_canvas(image_id) {
                this.canvas_confirm_pending = null;
                this.canvas_deleting = image_id;
                this.canvas_del_status = { ...this.canvas_del_status, [image_id]: null };
                try {
                    await axios.delete('/set_route/del_sector_image_drawing/' + image_id);
                    this.canvas_del_status = { ...this.canvas_del_status, [image_id]: 'ok' };
                    const img = this.sector_old_images.find(i => i.id === image_id);
                    if (img) img.has_original = false;
                    this.$bus.$emit('route-drawing-updated', { sector_image_id: image_id });
                    setTimeout(() => {
                        this.canvas_del_status = { ...this.canvas_del_status, [image_id]: null };
                    }, 3000);
                } catch (e) {
                    this.canvas_del_status = { ...this.canvas_del_status, [image_id]: 'error' };
                } finally {
                    this.canvas_deleting = null;
                }
            },

            async uploadSectorImage(image_id, event) {
                const file = event.target.files[0];
                if (!file) return;
                this.img_uploading = image_id;
                this.img_upload_status = { ...this.img_upload_status, [image_id]: null };
                const formData = new FormData();
                formData.append('image', file);
                try {
                    const res = await axios.post('/set_sector/replace_sector_image/' + image_id, formData, {
                        headers: { 'Content-Type': 'multipart/form-data' },
                    });
                    if (res.data && res.data.image) {
                        const img = this.sector_old_images.find(i => i.id === image_id);
                        if (img) { img.image = res.data.image; img.has_original = false; }
                        this.img_cache_bust = { ...this.img_cache_bust, [image_id]: Date.now() };
                    }
                    this.img_upload_status = { ...this.img_upload_status, [image_id]: 'ok' };
                    setTimeout(() => {
                        this.img_upload_status = { ...this.img_upload_status, [image_id]: null };
                    }, 4000);
                } catch (e) {
                    this.img_upload_status = { ...this.img_upload_status, [image_id]: 'error' };
                } finally {
                    this.img_uploading = null;
                    event.target.value = '';
                }
            },

            del_sector_image_from_db(image_id) {
                if(confirm('Are you sure, you want delite this image?')){
                    axios
                    .delete("/set_sector/del_sector_image_from_db/"+image_id)
                    .then(response => {
                        this.get_sector_images()
                    })
                    .catch(
                        error => console.log(error)
                    );
                }
            },

            get_region_data: function (category) {
                axios
                .get("/get_article/get_category_articles/" + category)
                .then(response => {
                    this.regions = response.data
                })
                .catch(error =>{
                })
                // .finally(() => this.oudoor_loading = false)
            },

            get_editing_sector_data: function(){
                this.is_loading = true
                axios
                .get("/set_sector/get_sector_editing_data/"+this.$route.params.id)
                .then(response => {
                    this.data = response.data.sector
                    // console.log(response.data.article.category);
                    this.get_region_data(response.data.article.category);
                    this.get_sector_images()
                    // this.sector_old_images = response.data.images
                })
                .catch(
                error => console.log(error)
                )
                .finally(() => this.is_loading = false);
            },

            get_sector_images: function(){
                axios
                .get("/get_sector/get_sector_images/"+this.$route.params.id)
                .then(response => {
                    this.sector_old_images = response.data
                })
                .catch(
                error => console.log(error)
                );
            },

            edit_sector: function () {
                this.is_loading = true
                let formData = new FormData();

                var loop_num = 0
                this.sector_new_images.forEach(image => {
                    formData.append('sector_new_images['+loop_num+']', image.image)
                    loop_num++
                });
                loop_num = 0

                formData.append('data', JSON.stringify(this.data))

                axios
                .post("/set_sector/edit_sector/"+this.$route.params.id, formData)
                .then(response => {
                    this.go_back(true)
                })
                .catch((error) => {
                    // console.log(error);
                    if (error.response.status == 422) {
                        this.errors = error.response.data.errors;
                    }
                })
                .finally(() => this.is_loading = false);
            },

            go_back(back_action = false) {
                this.is_back_action_query = this.$going.back(this, back_action)
            },
        },
    }
</script>

<style scoped>
.sector-img-table .img-cell {
    width: 280px;
    min-width: 180px;
    vertical-align: top;
}
.sector-img-table .actions-cell {
    min-width: 200px;
    vertical-align: top;
    padding: 8px;
}
.saved-img {
    width: 100%;
    height: auto;
    display: block;
    border-radius: 4px;
}
</style>