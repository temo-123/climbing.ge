<template>
    <div class="col-md-4 col-sm-6">
        <article class="service-box" role="article">
            <router-link :to="'../service/' + service_data.global_data.url_title" class="service-image-link" :aria-label="$t('guide.view_details_for_prefix') + ' ' + service_data.locale_data.title">
                <div v-if="service_data.image.length > 0" class="service-icon services-background-image" :style="'background-image: url(' + '/public/images/service_img/'+service_data.image+');'">
                    <div class="front-content">
                        <h3 class="services-title">{{ service_data.locale_data.title }}</h3>
                    </div>
                </div>

                <div v-else class="service-icon services-background-image">
                    <div class="front-content">
                        <h3 class="services-title">{{ service_data.locale_data.title }}</h3>
                    </div>
                </div>
            </router-link>

            <div class="service-content">
                <router-link :to="'../service/' + service_data.global_data.url_title" :aria-label="$t('guide.view_details_for_prefix') + ' ' + service_data.locale_data.title">
                    <h3>{{ service_data.locale_data.title }}</h3>
                </router-link>
                <div class="service-description" v-html="service_data.locale_data.short_description"></div>
            </div>
        </article>
    </div>
</template>

<script>
    export default {
        props:[
            'service_data'
        ],
    }
</script>

<style scoped>
    .service-box {
        background: #fff;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.12);
        border-radius: 10px;
        overflow: hidden;
        transition: box-shadow 0.3s ease, transform 0.3s ease;
        margin-bottom: 20px;
        cursor: pointer;
    }

    .service-box:hover {
        box-shadow: 0 6px 20px rgba(0, 0, 0, 0.18);
        transform: translateY(-4px);
    }

    .service-image-link {
        display: block;
    }

    /* Override global flip-card styles */
    .service-icon {
        position: relative !important;
        width: 100% !important;
        aspect-ratio: 16 / 9;
        height: auto !important;
        padding: 0 !important;
        background-size: cover !important;
        background-position: center !important;
        background-repeat: no-repeat !important;
        background-color: #ddd;
        display: flex !important;
        align-items: center !important;
        justify-content: center !important;
        transition: transform 0.3s ease !important;
        transform: none !important;
        opacity: 1 !important;
        overflow: hidden;
    }

    .service-box:hover .service-icon {
        transform: scale(1.04) !important;
        opacity: 1 !important;
    }

    .front-content {
        position: absolute !important;
        top: 0 !important;
        left: 0 !important;
        transform: none !important;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.38) !important;
        display: flex;
        align-items: flex-end;
        justify-content: center;
        padding: 16px !important;
        transition: background 0.3s ease;
    }

    .service-box:hover .front-content {
        background: rgba(0, 0, 0, 0.55) !important;
    }

    .services-title {
        color: #fff;
        font-size: 1rem;
        font-weight: 700;
        margin: 0;
        text-shadow: 0 1px 4px rgba(0,0,0,0.8);
        line-height: 1.3;
    }

    /* Override global: service-content is always visible below the image */
    .service-content {
        position: relative !important;
        opacity: 1 !important;
        transform: none !important;
        height: auto !important;
        background-color: #fff !important;
        padding: 14px 16px 16px !important;
        text-align: left;
    }

    .service-content h3 {
        font-size: 1rem;
        font-weight: 600;
        margin: 0 0 6px;
        color: #222 !important;
        line-height: 1.4;
    }

    .service-content a,
    .service-content h3 a {
        color: #00b9e7 !important;
        text-decoration: none;
        transition: color 0.2s ease;
    }

    .service-content a:hover,
    .service-content h3 a:hover {
        color: #009dc5 !important;
    }

    .service-description {
        font-size: 0.85rem;
        line-height: 1.5;
        color: #666;
        margin-top: 4px;
    }

    @media (max-width: 768px) {
        .service-box {
            margin-bottom: 12px;
        }

        .services-title {
            font-size: 0.9rem;
        }

        .service-content {
            padding: 10px 12px 14px !important;
        }

        .service-content h3 {
            font-size: 0.95rem;
        }
    }
</style>
