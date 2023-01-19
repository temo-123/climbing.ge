<template>
    <stack-modal
            :show="is_show_route_modal"
            title="Route detals"
            @close="is_show_route_modal = false"
            :modal-class="{ [ModalClass]: true }"
            :saveButton="{ visible: true }"
            :cancelButton="{
                title: 'Close',
                btnClass: { 'btn btn-primary': true },
            }"
        >
            <div class="model-body">
                <div class="container">
                    <div class="row">
                        <h2>Routes detals</h2>
                        <span
                            v-for="route in route_detals"
                            :key="route.id"
                            class="routes_detals"
                        >
                            <p class="route_detal">Name - {{ route.name }}</p>
                            <p class="route_detal">
                                Height - {{ route.height }}
                            </p>
                            <p>Bolts - {{ route.bolts }}</p>

                            <p class="route_detal" v-if="route.bolter">
                                Bolter - {{ route.bolter }}
                            </p>
                            <p class="route_detal" v-if="route.first_ascent">
                                First ascent - {{ route.first_ascent }}
                            </p>
                        </span>
                    </div>
                    <!-- <div class="row" v-if="this.route_posts.length > 0">
                        <div class="row">
                            <div class="col-md-6">
                                <h2 @click="route_post_list = !route_post_list">
                                    User posts and rewies
                                </h2>
                            </div>
                            <div class="col-md-6">
                                <p
                                    style="float: right"
                                    v-if="route_post_list"
                                    @click="route_post_list = !route_post_list"
                                >
                                    Close
                                </p>
                                <p
                                    style="float: right"
                                    v-if="!route_post_list"
                                    @click="route_post_list = !route_post_list"
                                >
                                    Open
                                </p>
                            </div>
                        </div>
                        <div v-show="route_post_list">
                            <div class="row">
                                <div
                                    class="cardbox shadow-lg bg-white"
                                    v-for="post in route_posts"
                                    :key="post.id"
                                >
                                    <div class="cardbox-heading">
                                        <div class="media m-0">
                                            <div class="d-flex mr-3">
                                                <img
                                                    class="img-fluid rounded-circle"
                                                    v-if="post['user'].image"
                                                    :src="'/public/images/user_img/user_demo_img.gif'"
                                                    :alt="
                                                        post['user'].name +
                                                        ' ' +
                                                        post['user'].surname
                                                    "
                                                />
                                                <img
                                                    class="img-fluid rounded-circle"
                                                    v-else
                                                    :src="'/public/images/site_img/user_demo_img.gif'"
                                                    :alt="
                                                        post['user'].name +
                                                        ' ' +
                                                        post['user'].surname
                                                    "
                                                />
                                            </div>
                                            <div class="media-body">
                                                <div class="row m-0">
                                                    <p class="m-0">
                                                        {{
                                                            post["user"].name +
                                                            " " +
                                                            post["user"].surname
                                                        }}
                                                    </p>
                                                </div>
                                                <div class="row m-0">
                                                    <small
                                                        ><span
                                                            v-if="
                                                                post['post']
                                                                    .created_at
                                                            "
                                                            >{{
                                                                post["post"]
                                                                    .created_at
                                                            }}</span
                                                        ></small
                                                    >
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div
                                        class="cardbox-item container"
                                        v-if="post['post'].text"
                                    >
                                        <p
                                            style="
                                                background-color: #eee;
                                                text-align: center;
                                                font-size: 1.5em;
                                            "
                                        >
                                            {{ post["post"].text }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                For more posts visit forum site
                            </div>
                        </div>
                    </div> -->
                </div>
            </div>
            <div slot="modal-footer">
                <div class="modal-footer">
                    <!-- footer -->
                </div>
            </div>
        </stack-modal>
</template>

<script>
import StackModal from '@innologica/vue-stackable-modal'  //https://innologica.github.io/vue-stackable-modal/#sample-css
    
export default {
    components: {
        StackModal,
        // openImg,
        // route_modal,
        // mtp_model,
    },
    props: [
        // "sector",
    ],
    data: function () {
        return {
            // climbing_sector: [],
            // spot_images: [],

            is_show_route_modal: false,
            // show_mtp_modal: false,
            // modalClass: [],

            route_detals: [],

            // mtp_detals: [],
            // id: this.article_id
        };
    },
    mounted() {
        // this.get_spot_rocks_images();
    },
    methods: {


        show_route_modal(id) {
            this.is_show_route_modal = true;
            this.route_detals = [];

            axios
                .get("../../api/route/get_route_for_modal/" + id)
                .then((response) => {
                    this.route_detals = response.data;
                    // this.route_post_list = true;
                })
                .catch((error) => {});
        },
        // show_mtp_madel(id) {
        //     this.show_mtp_modal = true;
        //     this.mtp_detals = [];

        //     axios
        //         .get("../../api/MTP/" + id)
        //         .then((response) => {
        //             this.mtp_detals = response.data;
        //             // this.mtp_post_list = true;
        //         })
        //         .catch((error) => {});
        // },
    }
}
</script>

<style>

</style>