<template>
    <div class="row">
        <div class="col-sm-3">
            <left-menu />
        </div>
        <div class="col-sm-9">
            <div class="col-sm-12">
                <galleryTab 
                    
                />
            </div>
        </div>
    </div>
</template>

<script>
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
<!-- 
<style scoped>
    .tabs {
        /* display: flex; */
        flex-wrap: wrap;
    }
    .tabs label {
        order: 1;
        display: block;
        padding: 1rem 2rem;
        margin-right: 0.2rem;
        cursor: pointer;
        background: #ccced0;
        font-weight: bold;
        transition: background ease 0.2s;
    }

    .tabs input[type="radio"]:checked + label[data-v-5add7a37] {
    background: #fff;
    border: 1px solid #ccc !important;
    }
    .tabs .tab {
        order: 99;
        flex-grow: 1;
        width: 100%;
        display: none;
        padding: 1rem;
        background: #fff;
        /* border: 1px solid #ccc!important; */
    }
    .tabs input[type="radio"] {
        display: none;
    }
    .tabs input[type="radio"]:checked + label {
        background: #fff;
        border: 1px solid #ccc !important;
    }
    .tabs input[type="radio"]:checked + label + .tab {
        display: block;
    }

    @media (max-width: 45em) {
        .tabs .tab,
        .tabs label {
            order: initial;
        }
        .tabs label {
            width: 100%;
            margin-right: 0;
            margin-top: 0.2rem;
        }
    }
</style> -->