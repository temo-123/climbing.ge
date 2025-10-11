<template>
    <div class="services">
        <div class="container">

            <h2 class='index_h2'>{{ $t('guide.title.what we do') }}</h2>

            <div class="bar"><i class="fa fa-book"></i></div>

            <h3 class="article_list_short_description">
                {{ this.$siteData.what_we_do_description }}
            </h3>
                
            <div class="row">
                <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
                    <ul class="right-text">
                        <li>
                            <img alt='Outdoor climbing in Georgia' class="index_category_right what_we_do_title_image" src="/images/site_img/outdoor.png">

                            <h4 class="what_we_do_title">
                                <router-link :to="{name: 'outdoors'}" exact>
                                    <strong>{{ $t('guide.title.outdoor climbing')}}</strong>
                                </router-link>
                            </h4>

                            <div class="content-text what_we_do_text_description">
                                <span v-html="this.$siteData.indoor_description"></span>
                            </div>
                        </li>
                        <div class="clearfix"></div>
                        <li>
                            <img alt='Indoor climbing' class="index_category_right what_we_do_title_image" src="/images/site_img/indoor.png">

                            <h4 class="what_we_do_title">
                                <router-link :to="{name: 'indoors'}" exact> 
                                    <strong>{{ $t('guide.title.indoor climbing')}}</strong>
                                </router-link>
                            </h4>

                            <div class="content-text what_we_do_text_description">
                                <span v-html="this.$siteData.indoor_description"></span>
                            </div>
                        </li>
                    </ul>
                </div>
                <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
                    <ul class="left-text">
                        <li>
                            <img alt='Mountaineering in Georgia' class="index_category_left what_we_do_title_image" src="/images/site_img/ice.png">

                            <h4 class="what_we_do_title">
                                <router-link :to="{name: 'ices'}" exact>
                                    <strong>{{ $t('guide.title.ice climbing')}}</strong>
                                </router-link>
                            </h4>

                            <div class="content-text what_we_do_text_description">
                                <span v-html="this.$siteData.ice_description"></span>
                            </div>
                        </li>
                        <div class="clearfix"></div>
                        <li>
                            <img alt='Ice climbing in Georgia' class="index_category_left what_we_do_title_image" src="/images/site_img/mount.png">

                            <h4 class="what_we_do_title">
                                <router-link :to="{name: 'mountaineerings'}" exact>
                                    <strong>{{ $t('guide.title.mountaineering')}}</strong>
                                </router-link>
                            </h4>

                            <div class="content-text what_we_do_text_description">
                                <span v-html="this.$siteData.mount_description"></span>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {

}
</script>

<style>
    ul.right-text {
        padding: 0;
        text-align: right;
        width: 100%;
        height: auto;
    }

    .right-text h4 {
        /* font-size: 18px; */
        /* font-size: 1.5em; */
        color: #4b4b4b;
        line-height: 60px !important;
    }

    .right-text h4 i {
        width: 60px;
        height: 60px;
        -webkit-border-radius: 50%;
        -moz-border-radius: 50%;
        border-radius: 50%;
        margin-top: 30px;
        float: right;
        margin-left: 20px;
        margin-right: 0;
        color: #279fbb;
        border: 1px solid #279fbb;
        /* font-size: 22px; */
        line-height: 60px !important;
        text-align: center;
    }

    /* .right-text {
        margin-top: -14px;
        margin-left: 0;
        margin-right: 80px;
    } */

    ul.left-text {
        padding: 0;
        text-align: left;
        width: 100%;
        height: auto;
    }

    .left-text h4 {
        /* font-size: 18px; */
        color: #4b4b4b;
        line-height: 60px  !important;
    }

    .left-text h4 i {
        width: 60px;
        height: 60px;
        -webkit-border-radius: 50%;
        -moz-border-radius: 50%;
        border-radius: 50%;
        margin-top: 30px;
        float: left;
        margin-right: 20px;
        margin-left: 0;
        color: #279fbb;
        border: 1px solid #279fbb;
        /* font-size: 22px; */
        line-height: 60px !important;
        text-align: center;
        transition: all 0.4s ease 0s;
    }

    /* .left-text .content-text {
        margin-top: -14px;
        margin-right: 0;
        margin-left: 80px;
    } */

    .what_we_do_title{
        font-size: 1.5em;
    }

    @media (max-width: 765px) {
        .what_we_do_title{
            text-align: center;
            float: none;
        }
        .what_we_do_title_image{
            float: none !important;
            margin-left: 40% !important;
            margin-right: 40% !important;
        }
        .what_we_do_text_description{
            text-align: center;
            margin: 0;
        }
    }


    .index_category_right {
        width: 20%;
        float: right;
        margin: 5px;
    }
    .index_category_left {
        width: 20%;
        float: left;
        margin: 5px;
    }
</style>