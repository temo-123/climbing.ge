<template>
    <div class="col-md-12">
        <div class="row">
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
        <div class="row">
            <div class="form-group">
                <button
                    type="submit"
                    class="btn btn-primary"
                    v-on:click="save_all()"
                >
                    Save
                </button>
            </div>
        </div>
        <!-- <button type="button" class="btn btn-info btn-lg" data-toggle="modal" data-target="#myModal">Open Modal</button> -->
        <!-- Modal -->
        <div class="modal fade" id="myModal" role="dialog">
            <div class="modal-dialog">
                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header">
                        <button
                            type="button"
                            class="close"
                            data-dismiss="modal"
                        >
                            &times;
                        </button>
                        <h4 class="modal-title">Modal Header</h4>
                    </div>
                    <div class="modal-body">
                        <p>Some text in the modal.</p>
                    </div>
                    <div class="modal-footer">
                        <button
                            type="button"
                            class="btn btn-default"
                            data-dismiss="modal"
                        >
                            Close
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <form
            @submit.prevent="add_sector"
            id="js_form"
            class="contact-form"
            method="POST"
            enctype="multipart/form-data"
        >
            <div class="form-group clearfix row">
                <label for="name" class="col-md-2 control-label">
                    Sector name
                </label>
                <div class="col-md-8">
                    <input
                        type="text"
                        v-model="data.name"
                        name="name"
                        placeholder="Name"
                        class="form-control textarea"
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
                <div class="col-md-8">
                    <select
                        class="form-control"
                        name="article_id"
                        v-model="data.article_id"
                    >
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

            <div class="form-group clearfix row">
                <label class="col-md-2 control-label"> Text </label>
                <div class="col-md-8">
                    <ckeditor v-model="data.text" :config="editorConfig"></ckeditor>
                </div>
            </div>

            <hr />

            <div class="form-group clearfix row">
                <label class="col-md-2 control-label"> Shadow time: </label>
                <div class="col-md-8">
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
                <div class="col-md-8 row">
                    <div class="col-md-4">
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
                    <div class="col-md-4">
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
                    <div class="col-md-4">
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
                </div>
            </div>

            <hr />
        </form>
        <div class="container">
            <div class="row">
                <div
                    class="alert alert-danger"
                    role="alert"
                    v-if="image_errors.image"
                >
                    {{ image_errors.image[0] }}
                </div>
                <div class="card" v-if="myModal">
                    <div class="card-header">Add image</div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group clearfix">
                                    <button
                                        class="btn btn-primary"
                                        @click="
                                            add_sector_image(
                                                temporary_sector_id
                                            )
                                        "
                                    >
                                        seve image
                                    </button>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <form
                                    @submit="
                                        add_sector_image(temporary_sector_id)
                                    "
                                    ref="myForm"
                                >
                                    <div class="form-group clearfix">
                                        <input
                                            type="file"
                                            name="profile_pic"
                                            id="profile_pic"
                                        />
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- <div class="container">
                <div class="row">
                    <div class="form-groupe">
                        <button
                            class="btn btn-primary mb-4"
                            @click="showModal()"
                        >
                            Add image
                        </button>
                    </div>
                    <div class="form-groupe">
                        <button
                            @click="get_sector_image(temporary_sector_id)"
                            class="btn main-btn pull-right"
                            v-if="!image_is_refresh"
                        >
                            Refresh ({{ image_reset_id }})
                        </button>
                        <span
                            class="badge badge-primare mb-1 pull-right"
                            v-if="image_is_refresh"
                            >Updating...</span
                        >
                    </div>
                </div>
                <div class="row">
                    <button
                        class="btn btn-secondary pull-left"
                        @click="save_sector_images_sequence()"
                    >
                        Save sector images sequence
                    </button>
                </div>
            </div> -->
        </div>

        <div class="container">
            <!-- <div class="col-md-3">
          <div class="row">
            <div class="col-md-12">
              <img alt="300x200" @click="showModal()" src="/public/images/site_img/image.png">
            </div>
          </div>
        </div> -->
            <!-- <div class="col-md-3" v-for="image in images" :key="image.id">
          <div class="row">
            <div class="col-md-12">
              <img :alt="'sector image - (' + image.image + ')'" class="sector_img" :src="'/public/images/sector_img/' + image.image">
            </div>
          </div>
          <div class="row">
            <div class="col-md-6">
              <button class="btn btn-secondary pull-left" @click="edit_image()">Edit</button>
            </div>
            <div class="col-md-6">
              <button class="btn btn-danger pull-right" @click="del_sector_image(image.id)">Del</button>
            </div>
          </div>
        </div> -->
            <div class="root">
                <SlickList
                    lockAxis="y"
                    v-model="images"
                    tag="table"
                    style="width: 100%"
                >
                    <tr>
                        <td>ID</td>
                        <td>Number</td>
                        <td>Image</td>
                        <td>Delete</td>
                    </tr>
                    <SlickItem
                        v-for="(image, index) in images"
                        :index="index"
                        :key="index"
                        tag="tr"
                    >
                        <td>{{ image.id }}</td>
                        <td v-if="image.num == NULL">0</td>
                        <td v-else>{{ image.num }}</td>
                        <td>
                            <img
                                :alt="'sector image - (' + image.image + ')'"
                                class="sector_image_in_table"
                                :src="
                                    '/public/images/sector_img/' + image.image
                                "
                            />
                        </td>
                        <td>
                            <button
                                class="btn btn-danger"
                                @click="del_sector_image(image.id)"
                            >
                                Del
                            </button>
                        </td>
                    </SlickItem>
                </SlickList>
            </div>
        </div>
    </div>
</template>
<script>
import Uploader from "vux-uploader-component";
import { SlickList, SlickItem } from "vue-slicksort";

export default {
    components: {
        Uploader,
        SlickItem,
        SlickList,
    },
    data() {
        return {
            fileList: [], //https://github.com/eJayYoung/vux-uploader-component
            regions: "",

            myModal: false,

            errors: [],
            image_errors: [],

            data: {
                name: "",
                image: "",
                success: "",

                article_id: "",
                name: "",
                text: "",

                all_day_in_shade: "",
                all_day_in_sun: "",
                in_the_shade_afternoon: "",
                in_the_shade_befornoon: "",
                in_shade_after_10: "",
                in_shade_after_15: "",

                slabby: "",
                vertical: "",
                overhang: "",
            },

            // temporary_sector_id: 0,

            image_is_refresh: false,
            image_reset_id: 0,
        };
    },
    mounted() {
        this.get_region_data();
        // this.create_temporary_sector();
    },
    beforeRouteLeave (to, from, next) {
        if(this.is_back_action = true){
            if (window.confirm('Added information will be deleted!!! Are you sure, you want go back?')) {
                this.is_back_action = false;
                next()
            } else {
                next(false)
            }
        }
    },
    methods: {
        // create_temporary_sector() {
        //     axios
        //         .post("/routes_and_sectors/create_temporary_sector/", {
        //             // ka_title: this.ka_title,
        //         })
        //         .then((response) => {
        //             this.get_temporary_sector_data();
        //             // console.log(response)
        //             // this.is_ka_sector_succes = 1
        //             // console.log('georgian sector upload successful');
        //         })
        //         .catch((error) => console.log(error));
        // },
        // del_temporary_sector(temporary_sector_id) {
        //     axios
        //         .post(
        //             "../../api/routes_and_sectors/delete_temporary_sector/" +
        //                 temporary_sector_id,
        //             {
        //                 // ka_title: this.ka_title,
        //             }
        //         )
        //         .then((response) => {
        //             // console.log(response)
        //             // this.is_ka_sector_succes = 1
        //             // console.log('georgian sector upload successful');
        //         })
        //         .catch((error) => console.log(error));
        // },

        showModal() {
            this.myModal = !this.myModal;
        },

        get_region_data: function () {
            axios
                .get("../api/article/")
                .then((response) => {
                    this.regions = response.data;
                })
                .catch((error) => console.log(error));
        },
        // get_temporary_sector_data: function () {
        //     axios
        //         .get("../../api/routes_and_sectors/get_temporary_sector_editing_data/")
        //         .then((response) => {
        //             this.editing_data = response.data;
        //             this.get_sector_image(
        //                 this.editing_data.last_temporary_sectore_id
        //             );
        //             this.temporary_sector_id =
        //                 this.editing_data.last_temporary_sectore_id;
        //         })
        //         .catch((error) => console.log(error));
        // },
        // get_sector_image: function (temporary_sector_id) {
        //     this.image_is_refresh = true;
        //     axios
        //         .get(
        //             "../../../api/routes_and_sectors/get_sector_image/" +
        //                 temporary_sector_id
        //         )
        //         .then((response) => {
        //             this.sector_images = response.data;
        //             this.images = this.sector_images.sector_images;

        //             this.image_is_refresh = false;
        //             this.image_reset_id++;
        //         })
        //         .catch((error) => console.log(error));
        // },

        add_sector: function () {
            axios
                .post("../../api/sector/", {
                    // article_id: this.article_id,
                    // name: this.name,
                    // text: this.text,
                    // all_day_in_shade: this.all_day_in_shade,
                    // all_day_in_sun: this.all_day_in_sun,
                    // in_the_shade_afternoon: this.in_the_shade_afternoon,
                    // in_the_shade_befornoon: this.in_the_shade_befornoon,
                    // in_shade_after_10: this.in_shade_after_10,
                    // in_shade_after_15: this.in_shade_after_15,
                    // slabby: this.slabby,
                    // vertical: this.vertical,
                    // overhang: this.overhang,
                    data: this.data,

                    _method: 'POST'
                })
                .then(function (response) {
                    // window.location.href = "../../api/routes_and_sectors";

                    this.go_back()
                })
                .catch((error) => {
                    console.log(error);
                    // if (error.response.status == 422) {
                    //     this.errors = error.response.data.errors;
                    // }
                });
        },
        // add_sector_image: function (temporary_sector_id) {
        //     var myFormData = new FormData(this.$refs.myForm);
        //     // console.log(temporary_sector_id);
        //     axios({
        //         method: "post",
        //         url:
        //             "../../api/routes_and_sectors/upload_sector_image/" +
        //             temporary_sector_id,
        //         data: myFormData,
        //         config: {
        //             headers: { "Content-Type": "multipart/form-data" },
        //         },
        //     })
        //         .then((response) => {
        //             // this.is_image_succes = 1;
        //             // alert(response.data.message);
        //         })
        //         .catch((error) => {
        //             if (error.response.status == 422) {
        //                 this.image_errors = error.response.data.errors;
        //             }
        //         });
        //     this.showModal();
        //     // e.preventDefault();
        // },
        // del_sector_image(imageId) {
        //     axios
        //         .post(
        //             "../../../api/routes_and_sectors/delete_sector_image/" + imageId,
        //             {
        //                 id: imageId,
        //             }
        //         )
        //         .then((Response) => {
        //             console.log(response);
        //             this.get_data_in_table_1();
        //         })
        //         .catch((error) => console.log(error));
        // },

        // back: function (temporary_sector_id) {
        //     // confirm("Are you sure, you want go back?");
        //     // this.del_temporary_sector(temporary_sector_id);
        //     window.location.href = "../../api/routes_and_sectors";
        // },

        go_back: function() {
            // this.is_back_action = true

            this.$router.go(-1)
        },

        // save_all: function () {
        //     this.add_sector();
        // },
        // save_sector_images_sequence() {
        //     axios
        //         .post("../../api/routes_and_sectors/save_sector_images_sequence/", {
        //             sector_images_sequence: this.images,
        //         })
        //         .then(function (response) {})
        //         .catch((error) => {
        //             if (error.response.status == 422) {
        //                 alert(error.response.data.errors);
        //             }
        //         });
        // },
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
