<template>
    <vue-instagram 
            token="IGQWRQTmZA5Y1plbzR6em0tTGMtRVUyaG55Um5vUDVOMU43WEdrblN6bVR4TkxQanZAoX0tIckF6aVc1dWdfTnNSWmZA5ZAU1Kb240Ym5xSGVxWDlSUjN2dkVpNm5WWElIYWJmNGpOTFlqald0aHVITF9RRzlld1JmWVkZD" 
            :tags="['girl']" 
            mediaType="image" 
            username="temo_samsonadze" 
            :count="8"
        >
        <template slot="feeds" slot-scope="props">
            <li class="fancy-list"> {{ props.feed.link }} </li>
        </template>
        <template slot="error" slot-scope="props">
            <div class="fancy-alert"> {{ props.error.error_message }} </div>
        </template>
        <!-- <template slot="feeds" scope="props">
            <li class="instagram-media fancy-list"> {{ props.feed.link }} </li>
            <img :src=" props.feed.images.standard_resolution.url " alt="">
        </template>
        <template slot="error" scope="props">
            <div class="fancy-alert"> {{ props.error.error_message }} </div>
        </template> -->
    </vue-instagram>
</template>

<script>
    import VueInstagram from 'vue-instagram' // https://www.npmjs.com/package/vue-instagram

    // token generation (2024) -> https://wpsocialninja.com/instagram-access-token/

    export default {
        components: {
            VueInstagram
        }
    }
</script>

<style>

</style>