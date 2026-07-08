<template>
    <div>
        <div v-if="sectors_and_images.local_images && sectors_and_images.local_images.length > 0">
            <div
                v-for="spot_image in sectors_and_images.local_images"
                :key="spot_image && spot_image.id ? spot_image.id : 'img-' + spot_image.image"
                :class="'sector_images sector_images_' + sectors_and_images.local_images.length"
            >
                <h3>{{ spot_image && spot_image.title ? spot_image.title : $t('guide.untitled_image') }}</h3>

                <SectorLocalImageCanvas
                    v-if="spot_image && spot_image.image"
                    :image_src="'/public/images/sector_local_img/' + spot_image.image"
                    :layouts="imageLayouts[spot_image.id] || []"
                />
            </div>
        </div>

        <div v-if="sectors_and_images.sectors && sectors_and_images.sectors.length > 0">
            <div
                v-for="sector in sectors_and_images.sectors"
                :key="sector && sector.sector && sector.sector.id ? sector.sector.id : 'sector-' + Math.random()"
            >
                <sector :sector="sector"/>
            </div>
        </div>
    </div>
</template>

<script>
import sector from "./SectorComponent.vue";
import SectorLocalImageCanvas from "./SectorLocalImageCanvasComponent.vue";

export default {
    components: {
        sector,
        SectorLocalImageCanvas,
    },
    props: ["sectors_and_images"],
    data() {
        return {
            imageLayouts: {}, // keyed by image id
        };
    },
    watch: {
        'sectors_and_images.local_images': {
            immediate: true,
            handler(images) {
                if (images && images.length) this.fetchAllLayouts(images);
            },
        },
    },
    methods: {
        fetchAllLayouts(images) {
            images.forEach(img => {
                if (!img || !img.id) return;
                axios.get('/get_sector/get_sector_local_images/get_layouts/' + img.id)
                    .then(res => {
                        const layouts = (res.data && res.data.layouts) ? res.data.layouts : [];
                        this.imageLayouts = { ...this.imageLayouts, [img.id]: layouts };
                    })
                    .catch(() => {});
            });
        },
    },
};
</script>

<style scoped>
.sector_images {
    border-radius: 8px;
    box-shadow: 0 2px 6px rgba(0,0,0,0.1);
    margin-bottom: 1.5rem;
    padding: 0.5rem;
    background-color: #f8f9fa;
    transition: transform 0.2s ease-in-out;
}

.sector_images:hover {
    box-shadow: 0 4px 12px rgba(0,0,0,0.2);
}

h3 {
    font-size: 1.4rem;
    font-weight: 600;
    color: #343a40;
    margin-bottom: 0.75rem;
}
</style>
