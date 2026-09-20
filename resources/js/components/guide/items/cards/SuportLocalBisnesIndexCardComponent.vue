<template>
    <div class="bisnes-index-card" @click="$emit('open', bisnes.global_data.url_title)">
        <div class="bisnes-index-card-img">
            <img
                v-if="bisnes.image"
                :src="'/public/images/suport_local_bisnes_img/' + bisnes.image"
                :alt="bisnes.local_data.title"
            />
            <img
                v-else
                :src="'/public/images/site_img/image.png'"
                :alt="bisnes.local_data.title"
            />
        </div>
        <div class="bisnes-index-card-body">
            <h4 class="bisnes-index-card-title">{{ bisnes.local_data.title }}</h4>
            <p class="bisnes-index-card-desc" v-html="bisnes.local_data.short_description"></p>
        </div>
    </div>
</template>

<script>
    export default {
        name: 'SuportLocalBisnesIndexCardComponent',
        props: {
            bisnes: {
                type: Object,
                required: true,
            },
        },
        emits: ['open'],
    }
</script>

<style scoped>
    .bisnes-index-card {
        background: #fff;
        border-radius: 12px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
        overflow: hidden;
        cursor: pointer;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        height: 100%;
        box-sizing: border-box;
        display: flex;
        flex-direction: column;
    }

    .bisnes-index-card:hover {
        transform: translateY(-6px);
        box-shadow: 0 10px 24px rgba(0, 0, 0, 0.14);
    }

    .bisnes-index-card-img {
        width: 100%;
        aspect-ratio: 16 / 9;
        overflow: hidden;
        background: #f4f4f4;
    }

    .bisnes-index-card-img img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .bisnes-index-card-body {
        padding: 16px;
        flex: 1;
        display: flex;
        flex-direction: column;
    }

    .bisnes-index-card-title {
        font-size: 1.1em;
        font-weight: 600;
        color: #2b2b3c;
        margin: 0 0 8px;
    }

    .bisnes-index-card-desc {
        font-size: 0.9em;
        color: #8a8a99;
        display: -webkit-box;
        -webkit-line-clamp: 3;
        -webkit-box-orient: vertical;
        overflow: hidden;
        margin: 0;
    }
</style>
