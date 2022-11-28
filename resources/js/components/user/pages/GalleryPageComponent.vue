<template>
    <div class="row">
        <div class="col-sm-3">
            <left-menu />
        </div>
        <div class="col-sm-9">
            <div class="row">
                <div class="col-md-12">
                    <breadcrumb />
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12">
                    <galleryTab />
                </div>
            </div>
         </div>
    </div>
</template>

<script>
    import breadcrumb from '../items/BreadcrumbComponent.vue'
    import galleryTab from '../items/data_tabs/GalleryTabComponent.vue';
    export default {
    //     data(){
    //         return {
    //             categories: [],
    //             tab_num: 1
    //         }
    //     },
        components: {
            galleryTab,
            breadcrumb
        },
    //     mounted() {
    //         this.get_images_categories()
    //     },
    //     watch: {
    //         '$route' (to, from) {
    //             this.categories = [],
    //             this.get_images_categories()
    //             window.scrollTo(0,0)
    //         }
    //     },
    //     methods: {
    //         get_images_categories(){
    //             axios
    //             .get("../api/gallery_image_category/")
    //             .then(response => {
    //                 this.categories = response.data
    //             })
    //             .catch(
    //                 error => console.log(error)
    //             );
    //         },
    //     }
    }
</script>
