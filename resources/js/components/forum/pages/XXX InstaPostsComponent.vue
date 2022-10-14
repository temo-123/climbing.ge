<template>
    <div>
        <main role="main" class="container">

        <div class="row">
            <div class="col-md-8 blog-main">
                insta posts
            </div>

            <rightMenu />

        </div>
        </main>
    </div>
</template>

<script>
    // import instaPost from '../items/InstaPostsComponent.vue'
    import rightMenu from '../items/RightMenuComponent.vue'
    export default {
        components: {
            rightMenu,
            // instaPost
        }
    }
</script>

<style>

</style>