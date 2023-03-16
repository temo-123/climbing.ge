<template>
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="error-template">
                    <h1>Oops! Error 404! Page Not Found</h1>
                    <div class="error-details">
                        Sorry, an error has occured, Requested page not found!
                    </div>
                    <div>
                        <img v-if="num == 1" class="article_404" :src=" this.publicPath + '../../../public/images/404/404_page/1.jpg'">
                        <img v-else-if="num == 2" class="article_404" :src=" this.publicPath + '../../../public/images/404/404_page/2.jpg'">
                        <img v-else-if="num == 0" class="article_404" :src=" this.publicPath + '../../../public/images/404/404_page/3.jpg'">
                        <p v-else></p>
                    </div>
                    <div class="error-actions">
                        <a @click="go_back()" class="btn btn-primary btn-lg">
                            <span class="glyphicon glyphicon-home"></span>
                            Go Beck
                        </a>
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
            this.num = Math.floor(Math.random() * 3);
        },
        methods: {
            go_back(){
                history.back()
            }
        }
    }
</script>

<style>

</style>