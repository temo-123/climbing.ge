<template>
    <div>
        <h3 style="text-align: center; ">{{ $t('global.page is empty') }}</h3>

        <img v-if="num == 1" class="article_404" :src=" this.publicPath + '/public/images/404/empty_page/1.jpg'">
        <img v-else-if="num == 2" class="article_404" :src=" this.publicPath + '/public/images/404/empty_page/2.jpg'">
        <img v-else-if="num == 3" class="article_404" :src=" this.publicPath + '/public/images/404/empty_page/3.jpg'">
        <img v-else-if="num == 4" class="article_404" :src=" this.publicPath + '/public/images/404/empty_page/4.jpg'">
        <img v-else class="article_404" :src=" this.publicPath + '/public/images/404/empty_page/3.jpg'">
    </div>
</template>

<script>
    export default {
        data: function () {
            return {
                num: 0,

                publicPath: window.location.protocol + '//' + window.location.hostname
            };
        },
        components: {
        },

        mounted() {
            this.select_image()
        },
        watch: {
            '$route' (to, from) {
                this.select_image()
            }
        },
        methods: {
            select_image(){
                this.num = Math.floor(Math.random() * 4);
            },
        }
    }
</script>

<style>

</style>