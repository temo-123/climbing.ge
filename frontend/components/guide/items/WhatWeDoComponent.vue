<template>
    <div class="services">
        <div class="container">

            <h2 class='index_h2'>{{ $t('guide.title.what we do') }}</h2>

            <div class="bar"><i class="fa fa-book"></i></div>

            <h3 class="article_list_short_description">
                {{ this.$siteData.data.what_we_do_description }}
            </h3>
                
            <div class="row">
                <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
                    <ul class="right-text">
                        <li class="friendly-item" @click="$router.push({name: 'outdoors'})" style="cursor: pointer;">
                            <img alt='Outdoor climbing in Georgia' class="index_category_right what_we_do_title_image" src="/images/site_img/outdoor.png">

                            <h4 class="what_we_do_title">
                                <strong>{{ $t('guide.title.outdoor climbing')}}</strong>
                            </h4>

                            <div class="content-text what_we_do_text_description">
                                <span v-html="this.$siteData.data.outdoor_description"></span>
                            </div>
                        </li>
                        
                        <!-- <div class="clearfix"></div> -->

                        <li class="friendly-item" @click="$router.push({name: 'indoors'})" style="cursor: pointer;">
                            <img alt='Indoor climbing' class="index_category_right what_we_do_title_image" src="/images/site_img/indoor.png">

                            <h4 class="what_we_do_title">
                                <strong>{{ $t('guide.title.indoor climbing')}}</strong>
                            </h4>

                            <div class="content-text what_we_do_text_description">
                                <span v-html="this.$siteData.data.indoor_description"></span>
                            </div>
                        </li>
                    </ul>
                </div>
                <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
                    <ul class="left-text">
                        <li class="friendly-item" @click="$router.push({name: 'ices'})" style="cursor: pointer;">
                            <img alt='Mountaineering in Georgia' class="index_category_left what_we_do_title_image" src="/images/site_img/ice.png">

                            <h4 class="what_we_do_title">
                                <strong>{{ $t('guide.title.ice climbing')}}</strong>
                            </h4>

                            <div class="content-text what_we_do_text_description">
                                <span v-html="this.$siteData.data.ice_description"></span>
                            </div>
                        </li>

                        <!-- <div class="clearfix"></div> -->

                        <li class="friendly-item" @click="$router.push({name: 'mountaineerings'})" style="cursor: pointer;">
                            <img alt='Ice climbing in Georgia' class="index_category_left what_we_do_title_image" src="/images/site_img/mount.png">

                            <h4 class="what_we_do_title">
                                <strong>{{ $t('guide.title.mountaineering')}}</strong>
                            </h4>

                            <div class="content-text what_we_do_text_description">
                                <span v-html="this.$siteData.data.mount_description"></span>
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
        line-height: 60px !important;
        text-align: center;
    }

    ul.left-text {
        padding: 0;
        text-align: left;
        width: 100%;
        height: auto;
    }

    .left-text h4 {
        color: #4b4b4b;
        line-height: 60px !important;
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
        line-height: 60px !important;
        text-align: center;
        transition: all 0.4s ease 0s;
    }

    .what_we_do_title {
        font-size: 1.5em;
    }

    .friendly-item {
        background: #f9f9f9;
        padding: 15px;
        border-radius: 8px;
        margin-bottom: 20px;
        min-height: 120px;
        transition: background 0.3s ease, box-shadow 0.3s ease;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
    }

    .friendly-item:hover {
        background: #f0f0f0;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }

    .what_we_do_title a {
        text-decoration: none;
        color: inherit;
        transition: color 0.3s ease;
    }

    .what_we_do_title a:hover {
        color: #279fbb;
    }

    @media (max-width: 765px) {
        .what_we_do_title {
            text-align: center;
            float: none;
        }
        .what_we_do_title_image {
            float: none !important;
            margin-left: 40% !important;
            margin-right: 40% !important;
        }
        .what_we_do_text_description {
            text-align: center;
            margin: 0;
        }
        .friendly-item {
            padding: 10px;
        }
    }

    .index_category_right {
        width: 20%;
        float: right;
        margin: 5px;
        border-radius: 5px;
    }
    .index_category_left {
        width: 20%;
        float: left;
        margin: 5px;
        border-radius: 5px;
    }
</style>
