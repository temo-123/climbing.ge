<template>
    <aside class="col-md-4 blog-sidebar">

        <div class="p-3">
            <h4 class="font-italic">Filters</h4>
            <ol class="list-unstyled mb-0" >
                <li v-for="topic in topics" :key="topic.id"><a href="#">{{ topic.name_us }}</a></li>
            </ol>
            <ol class="list-unstyled mb-0">
                <li><a href="#">All users</a></li>
                <li><a href="#">Only folowing users</a></li>
            </ol>
            <ol class="list-unstyled mb-0">
                <li><a href="#">All users</a></li>
                <li><a href="#">Only folowing users</a></li>
            </ol>
        </div>

        <div class="p-3">
            <h4 class="font-italic">Archives</h4>
            <ol class="list-unstyled mb-0">
                <li><a href="#">March 2014</a></li>
                <li><a href="#">February 2014</a></li>
                <li><a href="#">January 2014</a></li>
                <li><a href="#">December 2013</a></li>
            </ol>
        </div>

        <div class="p-3">
            <h4 class="font-italic">Elsewhere</h4>
            <ol class="list-unstyled">
                <li><a href="#">Users</a></li>
                <li><a href="#">Events</a></li>
                <li><a href="#">Instagram Posts</a></li>
                <li><a href="#">About Us</a></li>
            </ol>
        </div>

        <div class="p-3">
            <ol class="list-unstyled">
                <li><a href="#">Login</a></li>
            </ol>
        </div>

        <!-- <div class="p-3">
            <ol class="list-unstyled">
                <li>
                    <img :src="'images/site_img/place-your-ads-here.jpg'" alt="place-your-ads-here" style="width: 60%">
                </li>
                <li v-else>
                    {{this.$siteData.title}}
                </li>
            </ol>
        </div> -->

        <rightAd />

    </aside>
</template>

<script>
    export default {
        components: {
            // tabsComponent ,
            // leftMenu,
        },
        data(){
            return {
                topics: [],
            }
        },
        mounted() {
            this.get_topics()
        },
        methods: {
            get_topics(){
                axios
                .get("../api/posts_topic/")
                .then(response => {
                    this.topics = response.data
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