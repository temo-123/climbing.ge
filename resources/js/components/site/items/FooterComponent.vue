<template>
    <div class="footer">
        <div class="container">
            <footerAd />
            <div class="row">
                <div class="col-md-4 display-none-720px">
                    <div class="f-about container">
                        <h4 class="footer_title">{{ $t("about us") }}</h4>

                        <!-- {!!$site -> short_description!!} -->
                        <!-- {{ this.$siteData.guid_short_description }} -->
                        <div class="footer_description_block">
                            <span v-html="this.$siteData.guid_short_description"></span>
                        </div>

                        <li>
                            <router-link
                                :to="{
                                    name: 'about_us',
                                    params: { lang: this.$i18n.locale },
                                }"
                            >
                                {{ $t("more") }}
                            </router-link>
                        </li>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="f-links">
                        <h4 class="footer_title">{{ $t("links") }}</h4>
                        <ul>
                            <li>
                                <router-link
                                    :to="{
                                        name: 'indoors',
                                        params: { lang: this.$i18n.locale },
                                    }"
                                >
                                    {{ $t("menu indoor climbing") }}
                                </router-link>
                            </li>
                            <li>
                                <router-link
                                    :to="{
                                        name: 'outdoors',
                                        params: { lang: this.$i18n.locale },
                                    }"
                                >
                                    {{ $t("menu outdoor climbing") }}
                                </router-link>
                            </li>
                            <li>
                                <router-link
                                    :to="{
                                        name: 'mountaineerings',
                                        params: { lang: this.$i18n.locale },
                                    }"
                                >
                                    {{ $t("menu mountaineering") }}
                                </router-link>
                            </li>
                            <li>
                                <router-link
                                    :to="{
                                        name: 'ices',
                                        params: { lang: this.$i18n.locale },
                                    }"
                                >
                                    {{ $t("menu ice climbing") }}
                                </router-link>
                            </li>
                            <li>
                                <router-link
                                    :to="{
                                        name: 'others',
                                        params: { lang: this.$i18n.locale },
                                    }"
                                >
                                    {{ $t("menu other") }}
                                </router-link>
                            </li>
                            <li>
                                <router-link
                                    :to="{
                                        name: 'about_us',
                                        params: { lang: this.$i18n.locale },
                                    }"
                                >
                                    {{ $t("menu about us") }}
                                </router-link>
                            </li>
                        </ul>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="row f-links container">
                        <div class="flick">
                            <h4 class="footer_title">{{ $t("share") }}</h4>

                            <share />

                            <div class="row footer_qr">
                                <QRCanvas :width="200" :height="200" :options="options"></QRCanvas>
                            </div>
                        </div>
                    </div>
                    <followComponent />
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import followComponent from "../../global_components/FollowComponent.vue";
import share from "../../global_components/ShareComponent.vue";
const { QRCanvas } = require("qrcanvas-vue"); //  https://gera2ld.github.io/qrcanvas-vue/v2/#padding
export default {
    data: function () {
        return {
            num: 0,
            options: {
                cellSize: 8,
                // data: window.location.href,
                data: "",
                padding: 8,
                cellSize: 8,
                correctLevel: 'H',
            },
        };
    },
    components: {
        followComponent,
        share,
        QRCanvas,
    },

    watch: {
        '$route' (to, from) {
            this.generato_qr_data()
        }
    },
    mounted() {
        this.generato_qr_data()
    },
    methods: {
        generato_qr_data(){
            this.options.data = window.location.href
            const image = new Image();
            image.src = "./public/images/site_img/x.png";
            image.onload = () => {
                this.options = {
                    ...this.options,
                    logo: {
                        image,
                    },
                };
            };
        },
    }
};
</script>

<style scoped>
.footer_qr{
    width: 80%;
    margin-left: 10%;
}
</style>