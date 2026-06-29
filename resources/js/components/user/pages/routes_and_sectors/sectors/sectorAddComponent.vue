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
                    v-on:click="go_back()"
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
                    form="sector_adding_form"
                >
                    Save
                </button>
            </div>
        </div>
        <form
            @submit.prevent="save()"
            id="sector_adding_form"
            class="contact-form"
            method="POST"
            enctype="multipart/form-data"
            v-if="!is_loading"
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
                <label class="col-md-2 control-label"> Kids </label>
                <div class="col-md-10 row">
                    <div class="col-md-6">
                        <label for="vehicle1">
                            Femily
                            <strong v-if="!data.for_family">is not friendly</strong>
                            <strong v-else>friendly</strong>
                        </label>
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
        
        <gallery_images_add
            title_prop="Sector Images"
            @update_gallery_images="sector_images = $event"
        />
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

                // myModal: false,

                editorConfig: {},

                errors: [],
                is_loading: false,

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
                    helmet_required: null,

                    is_helmet: null,
                },

                is_back_action_query: true,

                sector_images: [],
            };
        },
        mounted() {
            this.get_region_data();
        
            document.querySelector('body').style.marginLeft = '0';
            document.querySelector('.admin_page_header_navbar').style.marginLeft = '0';
        },
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
            get_region_data: function () {
                axios
                .get("/get_article/get_category_articles/" + this.$route.params.category)
                .then(response => {
                    this.regions = response.data
                })
                .catch(error =>{
                })
            },

            save: function () {
                let formData = new FormData();

                var loop_num = 0
                this.sector_images.forEach(image => {
                    formData.append('sector_images['+loop_num+']', image.image)
                    loop_num++
                });
                loop_num = 0

                formData.append('data', JSON.stringify(this.data))

                axios
                    .post("/set_sector/add_sector/", formData)
                    .then(response => {
                        // if(confirm('Do you want send notification about editing sector?')){
                        //     this.sand_notification()
                        // }
                        // else{
                            this.go_back(true)
                        // }
                    })
                    .catch((error) => {
                        if (error.response.status == 422) {
                            this.errors = error.response.data.errors;
                        }
                    })
                    .finally(() => this.is_loading = false);
            },

            // sand_notification() {
            //     this.is_mail_sending_procesing = true

            //     axios
            //     .post('/user/notifications/send_sector_adding_notification')
            //     .then(response => {
            //         this.go_back(true)
            //     })
            //     .catch(err => {
            //         console.log(err);
            //     })
            //     .finally(() => this.is_mail_sending_procesing = false);
            // },

            go_back(back_action = false) {
                this.is_back_action_query = this.$going.back(this, back_action)
            },
        },
    };
</script>

<style scoped>
.sector_img {
    border-radius: 5%;
    border: 3px solid #a7a7a7;
}
.sector_image_in_table {
    height: 10em;
}
</style>
