<template>
    <div class="row">
        <openImg
            v-for="image in mount_route_images"
            :key="image.id"
            :img="'/public/images/mount_route_description_img/' + image.image"
            :img_alt="image.image"
            :img_class="
                'sector_images sector_images_' +
                mount_route_images.length
            "
        />
    </div>
</template>

<script>
    import openImg from "./ImageOpenComponent.vue";

    export default {
        props: [
            'article_id_prop',
        ],
        data: function () {
            return {
                article_id: 0,
                mount_route_images: []
            };
        },
        components: {
            openImg,
        },
        
        mounted() {
            this.article_id = this.article_id_prop
            this.get_mount_route_images()
        },
        watch: {
            '$route' (to, from) {
                this.article_id = this.article_id_prop
                this.get_mount_route_images()
            },
            article_id_prop: function(){
                this.article_id = this.article_id_prop
                this.get_mount_route_images()
            },
        },
        methods: {
            get_mount_route_images(){
                if(this.article_id != 0)
                {
                    axios
                    .get("/get_mount_route/get_mount_routes_images/"+this.article_id)
                    .then(response => {
                        this.mount_route_images = response.data
                    })
                    .catch(
                        error => console.log(error)
                    );
                }
                else{
                    this.article_id = this.article_id_prop
                    this.get_mount_route_images()
                }
            },

            update(id){
                this.article_id = id
                this.get_mount_route_images()
            }
        }
    }
</script>

<style lang="scss" scoped>

</style>