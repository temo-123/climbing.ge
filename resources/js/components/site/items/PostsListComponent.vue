<template>
    <div class="col-md-12">
        <div class="row">
            <div class="cardbox shadow-lg bg-white" v-for="post in posts" :key="post.id">
                <div class="cardbox-heading">
                    <div class="media m-0">

                        <div class="d-flex mr-3">
                            <router-link style="font-size: 1.5em;" :to="'user/'+post['user'].id" exact class="site_title">
                                <img class="img-fluid rounded-circle" v-if="post['user'].image" :src="'/public/images/user_img/user_demo_img.gif'" :alt="post['user'].name + ' ' + post['user'].surname">
                                <img class="img-fluid rounded-circle" v-else :src="'/public/images/site_img/user_demo_img.gif'" :alt="post['user'].name + ' ' + post['user'].surname">
                            </router-link>
                        </div>

                        <div class="media-body">
                            <div class="row m-0">
                                <router-link style="font-size: 1.5em;" :to="'user/'+post['user'].id" exact class="site_title">
                                <p class="m-0">{{post['user'].name + ' ' + post['user'].surname}}</p>
                                </router-link>
                            </div>
                            
                            <div class="row m-0">
                                <small><span v-if="post['post'].created_at" >{{ post['post'].created_at }}</span></small>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>

    export default {
        props: [
            'article_id',
        ],
        components: {
        },
        data: function () {
            return {
                posts: [],
            }
        },
        mounted() {
            this.get_posts()
        },
        methods: {
            get_posts(){
                axios
                .get("../api/posts/get_posts_for_outdoor_region/"+this.article.id)
                .then(response => {
                    this.posts = response.data
                })
                .catch(
                    error => console.log(error)
                );
            },
        }
    }
</script>

<style>

</style>