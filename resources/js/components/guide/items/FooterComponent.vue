<template>
    <div class="footer">
        <div class="container">
            <footerLogo />
            <div class="row">

                <div class="col-md-4 display-none-720px">
                    <div class="f-about container">
                        <h4 class="footer_title">{{ $t("global.footer.about us") }}</h4>

                        <div class="footer_description_block">
                            <span v-html="this.$siteData.data.guid_short_description"></span>
                            <router-link
                                :to="{
                                    name: 'about_us',
                                    params: { locale: this.$i18n.locale === 'ka' ? 'ka' : undefined },
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
                                        params: { locale: this.$i18n.locale === 'ka' ? 'ka' : undefined },
                                    }"
                                >
                                    {{ $t("guide.menu.indoor climbing") }}
                                </router-link>
                            </li>
                            <li>
                                <router-link
                                    :to="{
                                        name: 'outdoors',
                                        params: { locale: this.$i18n.locale === 'ka' ? 'ka' : undefined },
                                    }"
                                >
                                    {{ $t("guide.menu.outdoor climbing") }}
                                </router-link>
                            </li>
                            <li>
                                <router-link
                                    :to="{
                                        name: 'mountaineerings',
                                        params: { locale: this.$i18n.locale === 'ka' ? 'ka' : undefined },
                                    }"
                                >
                                    {{ $t("guide.menu.mountaineering") }}
                                </router-link>
                            </li>
                            <li>
                                <router-link
                                    :to="{
                                        name: 'ices',
                                        params: { locale: this.$i18n.locale === 'ka' ? 'ka' : undefined },
                                    }"
                                >
                                    {{ $t("guide.menu.ice climbing") }}
                                </router-link>
                            </li>
                            <li>
                                <router-link
                                    :to="{
                                        name: 'others',
                                        params: { locale: this.$i18n.locale === 'ka' ? 'ka' : undefined },
                                    }"
                                >
                                    {{ $t("guide.menu.other") }}
                                </router-link>
                            </li>
                            <li>
                                <router-link
                                    :to="{
                                        name: 'about_us',
                                        params: { locale: this.$i18n.locale === 'ka' ? 'ka' : undefined },
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
                            <h4 class="footer_title">{{ $t('guide.donation.support_title') }}</h4>
                            <Donation />
                        </div>
                    </div>
                    <div class="row f-links container">
                        <div class="flick">
                            <h4 class="footer_title">{{ $t("global.footer.qr") }}</h4>
                            <div class="footer_qr_code_block">
                                <qrcode-vue :value="qrValue" :options="options"></qrcode-vue>
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
    import Donation from "../items/DonationComponent.vue";
    import QrcodeVue from 'qrcode.vue'

    export default {
        data: function () {
            return {
                qrValue: window.location.href,
                options: {
                    size: 100,
                    level: 'H',
                },
            };
        },
        components: {
            followComponent,
            footerLogo,
            share,
            footerText,
            QrcodeVue,
            Donation
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
                this.qrValue = window.location.href
            },
        }
    };
</script>