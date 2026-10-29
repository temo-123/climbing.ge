<template>
    <!-- One image viewer for photos that aren't rendered by a component —
         images put in texts with the editor. services/fun/watermark.js
         dispatches "cg-open-image" ({ src, alt, gallery, index }) on click. -->
    <openImg
        ref="viewer"
        no_thumb
        :img="src"
        :img_alt="alt"
        :gallery="gallery"
        :gallery_index="index"
    />
</template>

<script>
    import openImg from '../guide/items/ImageOpenComponent.vue'

    export default {
        name: 'GlobalImageViewer',
        components: { openImg },
        data() {
            return { src: '', alt: '', gallery: null, index: 0 };
        },
        mounted() {
            this.onOpen = (e) => {
                const d = e.detail || {};
                this.src = d.src || '';
                this.alt = d.alt || '';
                this.gallery = d.gallery && d.gallery.length > 1 ? d.gallery : null;
                this.index = d.index || 0;
                this.$nextTick(() => this.$refs.viewer.open_image());
            };
            window.addEventListener('cg-open-image', this.onOpen);
        },
        beforeUnmount() {
            window.removeEventListener('cg-open-image', this.onOpen);
        },
    };
</script>
