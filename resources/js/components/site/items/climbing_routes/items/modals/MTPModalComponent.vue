<template>
    <stack-modal
            :show="is_show_mtp_modal"
            title="Molty pitch"
            @close="is_show_mtp_modal = false"
            :modal-class="{ [modalClass]: true }"
            :saveButton="{
                visible: true,
                title: 'Save',
                btnClass: { 'btn btn-primary': true },
            }"
            :cancelButton="{
                visible: false,
                title: 'Close',
                btnClass: { 'btn btn-danger': true },
            }"
        >
            <div class="model-body">
                <div class="container">
                    <div class="row">
                        <h2>Multy-pitch detals</h2>
                        <span
                            class="routes_detals"
                        >
                            <p class="route_detal">Name - {{ mtp_detals.mtp.name }}</p>
                            <p class="route_detal">Height - {{ mtp_detals.mtp.height }}</p>
                        </span>
                    </div>

                    <table class="table col-md-12 table table-hover">
                        <tbody>
                            <tr>
                                <td>N</td>
                                <td>{{ $t("route_tab name") }}</td>
                                <td>{{ $t("route_tab height") }}</td>
                                <td>{{ $t("route_tab bolts") }}</td>
                                <td>{{ $t("route_tab grade fr") }}</td>
                                <td class="display-none-720px">
                                    {{ $t("route_tab grade yds") }}
                                </td>
                            </tr>
                        </tbody>
                        <tbody>
                            <tr
                                v-for="pitch in mtp_detals.mtp_pitchs"
                                :key="pitch.pitch_id"
                            >
                                <td>{{ pitch.num }}</td>
                                <td>{{ pitch.name }}</td>
                                <td>{{ pitch.height }}</td>
                                <td>{{ pitch.bolts }}</td>

                                <td v-if="pitch.or_grade != NULL">
                                    {{ pitch.grade }} /
                                    {{ pitch.or_grade }}
                                </td>
                                <td v-else>{{ pitch.grade }}</td>

                                <td v-if="pitch.or_grade != NULL">
                                    {{ lead_grade_chart(pitch.grade) }} /
                                    {{ lead_grade_chart(pitch.or_grade) }}
                                </td>
                                <td v-else>
                                    {{ lead_grade_chart(pitch.grade) }}
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <!-- <div class="row" v-if="this.mtp_posts.length > 0">
                        <div class="row">
                            <div class="col-md-6">
                                <h2 @click="mtp_post_list = !mtp_post_list">
                                    User posts and rewies
                                </h2>
                            </div>
                            <div class="col-md-6">
                                <p
                                    style="float: right"
                                    v-if="mtp_post_list"
                                    @click="mtp_post_list = !mtp_post_list"
                                >
                                    Close
                                </p>
                                <p
                                    style="float: right"
                                    v-if="!mtp_post_list"
                                    @click="mtp_post_list = !mtp_post_list"
                                >
                                    Open
                                </p>
                            </div>
                        </div>
                        <div v-show="mtp_post_list">
                            <div class="row">
                                <div
                                    class="cardbox shadow-lg bg-white"
                                    v-for="mtp_post in mtp_posts"
                                    :key="mtp_post.id"
                                >
                                    <div class="cardbox-heading">
                                        <div class="media m-0">
                                            <div class="d-flex mr-3">
                                                <img
                                                    class="img-fluid rounded-circle"
                                                    v-if="
                                                        mtp_post['user'].image
                                                    "
                                                    :src="'/public/images/user_img/user_demo_img.gif'"
                                                    :alt="
                                                        mtp_post['user'].name +
                                                        ' ' +
                                                        mtp_post['user'].surname
                                                    "
                                                />
                                                <img
                                                    class="img-fluid rounded-circle"
                                                    v-else
                                                    :src="'/public/images/site_img/user_demo_img.gif'"
                                                    :alt="
                                                        mtp_post['user'].name +
                                                        ' ' +
                                                        mtp_post['user'].surname
                                                    "
                                                />
                                            </div>
                                            <div class="media-body">
                                                <div class="row m-0">
                                                    <p class="m-0">
                                                        {{
                                                            mtp_post["user"]
                                                                .name +
                                                            " " +
                                                            mtp_post["user"]
                                                                .surname
                                                        }}
                                                    </p>
                                                </div>
                                                <div class="row m-0">
                                                    <small
                                                        ><span
                                                            v-if="
                                                                mtp_post['post']
                                                                    .created_at
                                                            "
                                                            >{{
                                                                mtp_post["post"]
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
                                        v-if="mtp_post['post'].text"
                                    >
                                        <p
                                            style="
                                                background-color: #eee;
                                                text-align: center;
                                                font-size: 1.5em;
                                            "
                                        >
                                            {{ mtp_post["post"].text }}
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

            // show_route_modal: false,
            is_show_mtp_modal: false,
            // modalClass: [],

            // route_detals: [],

            mtp_detals: [],
            // id: this.article_id
        };
    },
    mounted() {
        // this.get_spot_rocks_images();
    },
    methods: {
        lead_grade_chart(grade_fr) {
            var grad = "";
            if (localStorage.getItem("grade") == "yds") {
                if (grade_fr == "4") grad = "5.6";
                else if (grade_fr == "5a" || grade_fr == "5a+") grad = "5.7";
                else if (grade_fr == "5b" || grade_fr == "5b+") grad = "5.8";
                else if (grade_fr == "5c" || grade_fr == "5c+") grad = "5.9";
                else if (grade_fr == "6a") grad = "5.10a";
                else if (grade_fr == "6a+") grad = "5.10b";
                else if (grade_fr == "6b") grad = "5.10c";
                else if (grade_fr == "6b+") grad = "5.10d";
                else if (grade_fr == "6c") grad = "5.11a/5.11b";
                else if (grade_fr == "6c+") grad = "5.11c";
                else if (grade_fr == "7a") grad = "5.11d";
                else if (grade_fr == "7a+") grad = "5.12a";
                else if (grade_fr == "7b") grad = "5.12b";
                else if (grade_fr == "7b+") grad = "5.12c";
                else if (grade_fr == "7c") grad = "5.12d";
                else if (grade_fr == "7c+") grad = "5.13a";
                else if (grade_fr == "8a") grad = "5.13b";
                else if (grade_fr == "8a+") grad = "5.13c";
                else if (grade_fr == "8b") grad = "5.13d";
                else if (grade_fr == "8b+") grad = "5.14a";
                else if (grade_fr == "8c") grad = "5.14b";
                else if (grade_fr == "8c+") grad = "5.14c";
                else if (grade_fr == "9a") grad = "5.14d";
                else if (grade_fr == "9a+") grad = "5.15a";
                else if (grade_fr == "9b") grad = "5.15b";
                else if (grade_fr == "9b+") grad = "5.15c";
                else if (grade_fr == "9c") grad = "5.15d";
                else if (grade_fr == "9c+") grad = "5.16a";
                else if (grade_fr == "Project") grad = "Project";
                else grad = "?";
            } else if (localStorage.getItem("grade") == "UIAA") {
                // console.log(localStorage.getItem("grade"))
                if (grade_fr == "4") grad = "IV";
                else if (grade_fr == "5a" || grade_fr == "5a+") grad = "V+";
                else if (grade_fr == "5b" || grade_fr == "5b+") grad = "VI-";
                else if (grade_fr == "5c" || grade_fr == "5c+") grad = "VI";
                else if (grade_fr == "6a") grad = "VI+";
                else if (grade_fr == "6a+") grad = "VII-";
                else if (grade_fr == "6b") grad = "VII";
                else if (grade_fr == "6b+") grad = "VII+";
                else if (grade_fr == "6c") grad = "VII+/VIII-";
                else if (grade_fr == "6c+") grad = "VIII-";
                else if (grade_fr == "7a") grad = "VIII";
                else if (grade_fr == "7a+") grad = "VIII+";
                else if (grade_fr == "7b") grad = "VIII+/IX-";
                else if (grade_fr == "7b+") grad = "IX-";
                else if (grade_fr == "7c") grad = "IX";
                else if (grade_fr == "7c+") grad = "IX+";
                else if (grade_fr == "8a") grad = "IX+/X-";
                else if (grade_fr == "8a+") grad = "X-";
                else if (grade_fr == "8b") grad = "X";
                else if (grade_fr == "8b+") grad = "X+";
                else if (grade_fr == "8c") grad = "XI-";
                else if (grade_fr == "8c+") grad = "XI";
                else if (grade_fr == "9a") grad = "XI+";
                else if (grade_fr == "9a+") grad = "XII-";
                else if (grade_fr == "9b") grad = "XII";
                else if (grade_fr == "9b+") grad = "XII+";
                else if (grade_fr == "9c") grad = "XIII";
                else if (grade_fr == "Project") grad = "Project";
                else grad = "?";
            } else {
                grad = "Error";
            }
            return grad;
        },
        show_mtp_modal(id) {
            this.is_show_mtp_modal = true;
            this.mtp_detals = [];

            axios
                .get("../../api/mtp/get_mtp_for_modal/" + id)
                .then((response) => {
                    this.mtp_detals = response.data;
                    // this.mtp_post_list = true;
                })
                .catch((error) => {});
        },
    }
}
</script>

<style>

</style>