<template>
    <div>
        <img v-if="num == 1" class="article_404" :src="'./images/404/empty_page/1.jpg'">
        <img v-else-if="num == 2" class="article_404" :src="'./images/404/empty_page/2.jpg'">
        <img v-else-if="num == 3" class="article_404" :src="'./images/404/empty_page/3.webp'">
        <img v-else-if="num == 4" class="article_404" :src="'./images/404/empty_page/4.jpg'">
        <img v-else-if="num == 5" class="article_404" :src="'./images/404/empty_page/5.jpg'">
        <img v-else-if="num == 6" class="article_404" :src="'./images/404/empty_page/6.jpg'">
        <img v-else-if="num == 7" class="article_404" :src="'./images/404/empty_page/7.jpg'">
        <img v-else-if="num == 8" class="article_404" :src="'./images/404/empty_page/8.jpg'">
        <img v-else-if="num == 9" class="article_404" :src="'./images/404/empty_page/9.jpg'">
        <img v-else-if="num == 10" class="article_404" :src="'./images/404/empty_page/10.jpg'">
        <p v-else>page is empty</p>

        <p style="text-align: center; ">empty page text</p>
    </div>
</template>

<script>
    export default {
        data: function () {
            return {
                num: 0
            };
        },
        components: {
        },
        mounted() {
            this.num = Math.floor(Math.random() * 11);
        },
        methods: {
        }
    }
</script>

<style>

</style>