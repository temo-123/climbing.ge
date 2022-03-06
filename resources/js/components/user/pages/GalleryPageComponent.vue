<template>
    <div class="panel panel-default target">
        <div class="panel-body">
            
            <galleryComponent/>
            
        </div>
    </div>
</template>

<script>
    import galleryComponent from '../items/GalleryComponent.vue';
    export default {
        data: function () {
            return {
                // 
            };
        },
        components: {
            galleryComponent,
        },
        mounted() {
        }
    }
</script>