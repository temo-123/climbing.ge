<template>
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="error-template">
                    <h1> {{ $t("error.404.title") }} </h1>
                    <div class="error-details">
                        {{ $t("error.404.description") }}
                    </div>
                    <div>
                        <img v-if="num == 1" class="article_404" :src=" this.publicPath + '/public/images/404/404_page/1.jpg'">
                        <img v-else-if="num == 2" class="article_404" :src=" this.publicPath + '/public/images/404/404_page/2.jpg'">
                        <img v-else-if="num == 0" class="article_404" :src=" this.publicPath + '/public/images/404/404_page/3.jpg'">
                        <img v-else class="article_404" :src=" this.publicPath + '/public/images/404/404_page/3.jpg'">
                    </div>
                    <div class="error-actions">
                        <router-link :to="'/'" exact class="btn btn-primary btn-lg">
                            <span class="glyphicon glyphicon-home"></span>
                            {{ $t("error.404.go_home") }}
                        </router-link>
                    </div>
                    <h2>By Climbing.ge</h2>
                </div>
            </div>
        </div>
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
            // go_back(){
            //     history.back()
            // }
        }
    }
</script>

<style>

</style>