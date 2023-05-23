<template>
    <div class="footer">
        <div class="container">
            <footerLogo />
            <div class="row">

                <div class="col-md-4 display-none-720px">
                    <div class="f-about container">
                        <h4 class="footer_title">{{ $t("global.footer.about us") }}</h4>

                        <div class="footer_description_block">
                            <span v-html="this.$siteData.guid_short_description"></span>
                            <router-link
                                :to="{
                                    name: 'about_us',
                                    params: { lang: this.$i18n.locale },
                                }"
                            >
                            {{ $t("global.footer.more") }}
                            </router-link>
                        </div>
                    </div>
                    <followComponent />
                </div>

                <div class="col-md-4">
                    <div class="f-links">
                        <h4 class="footer_title">{{ $t("global.footer.links") }}</h4>
                        <ul class="footer_links">
                            <li>
                                <router-link
                                    :to="{
                                        name: 'indoors',
                                        params: { lang: this.$i18n.locale },
                                    }"
                                >
                                    {{ $t("guide.menu.indoor climbing") }}
                                </router-link>
                            </li>
                            <li>
                                <router-link
                                    :to="{
                                        name: 'outdoors',
                                        params: { lang: this.$i18n.locale },
                                    }"
                                >
                                    {{ $t("guide.menu.outdoor climbing") }}
                                </router-link>
                            </li>
                            <li>
                                <router-link
                                    :to="{
                                        name: 'mountaineerings',
                                        params: { lang: this.$i18n.locale },
                                    }"
                                >
                                    {{ $t("guide.menu.mountaineering") }}
                                </router-link>
                            </li>
                            <li>
                                <router-link
                                    :to="{
                                        name: 'ices',
                                        params: { lang: this.$i18n.locale },
                                    }"
                                >
                                    {{ $t("guide.menu.ice climbing") }}
                                </router-link>
                            </li>
                            <li>
                                <router-link
                                    :to="{
                                        name: 'others',
                                        params: { lang: this.$i18n.locale },
                                    }"
                                >
                                    {{ $t("guide.menu.other") }}
                                </router-link>
                            </li>
                            <li>
                                <router-link
                                    :to="{
                                        name: 'about_us',
                                        params: { lang: this.$i18n.locale },
                                    }"
                                >
                                    {{ $t("guide.menu.about us") }}
                                </router-link>
                            </li>
                        </ul>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="row f-links container">
                        <div class="flick">
                            <h4 class="footer_title">{{ $t("global.footer.share") }}</h4>

                            <!-- <share /> -->
                        </div>
                    </div>
                    <div class="row f-links container">
                        <div class="flick">
                            <h4 class="footer_title">{{ $t("global.footer.qr") }}</h4>
                            <div class="row">
                                <QRCanvas :options="options" class="foo_qr"></QRCanvas>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
            <footerText />
        </div>
    </div>
</template>

<script>
    import followComponent from "../../global_components/FollowComponent.vue";
    import footerLogo from "../../global_components/FooterLogoBlockComponent.vue";
    import share from "../../global_components/ShareComponent.vue";
    import footerText from "../../global_components/FooterBottomTextComponent.vue";

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
                    correctLevel: 'H',
                },
            };
        },
        components: {
            followComponent,
            footerLogo,
            share,
            footerText,
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
                image.src = process.env.MIX_APP_SSH + process.env.MIX_USER_PAGE_URL + "/public/images/site_img/x.png";
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
/* .footer_qr{
    width: 80%;
    margin-left: 10%;
} */
.foo_qr{
    width: 50%;
    margin-left: 25%;
}
</style>