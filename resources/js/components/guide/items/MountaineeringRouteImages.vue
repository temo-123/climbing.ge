<template>
    <div class="sector-images-wrap" v-if="mount_route_images.length > 0">
        <openImg
            v-for="(image, index) in mount_route_images"
            :key="image.id"
            :img="'/public/images/mount_route_description_img/' + image.image"
            :img_alt="image.image"
            :img_class="'sector_images sector_images_' + mount_route_images.length"
            :gallery="gallery"
            :gallery_index="index"
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
        computed: {
            // Every route image as one list, so the lightbox can page through them
            // instead of forcing a close/reopen per photo — same pattern as
            // SectorComponent's sector_gallery.
            gallery() {
                return this.mount_route_images.map(image => ({
                    src: '/public/images/mount_route_description_img/' + image.image,
                    alt: image.image,
                }));
            },
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
