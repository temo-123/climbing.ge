<template>
    <vue-instagram 
            token="eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJzdWIiOiIxMjM0NTY3ODkwIiwibmFtZSI6IkpvaG4gRG9lIiwiaWF0IjoxNTE2MjM5MDIyfQ.SflKxwRJSMeKKF2QT4fwpMeJf36POk6yJV_adQssw5c" 
            :tags="['girl']" 
            mediaType="image" 
            username="UsernameHere" 
            :count="3"
        >
        <template slot="feeds" scope="props">
            <li class="instagram-media fancy-list"> {{ props.feed.link }} </li>
            <img :src=" props.feed.images.standard_resolution.url " alt="">
        </template>
        <template slot="error" scope="props">
            <div class="fancy-alert"> {{ props.error.error_message }} </div>
        </template>
    </vue-instagram>
</template>

<script>
    import VueInstagram from 'vue-instagram'

    export default {
    name: 'App',

    components: {
        VueInstagram
    }
    }
</script>

<style>

</style>