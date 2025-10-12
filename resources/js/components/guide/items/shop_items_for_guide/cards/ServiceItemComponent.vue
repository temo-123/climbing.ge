<template>
    <div class="col-md-4 col-sm-6">
        <article class="service-box" role="article">
            <div v-if="service_data.image.length > 0" class="service-icon services-background-image" :style="'background-image: url(' + '/public/images/service_img/'+service_data.image+');'" :aria-label="'Service image for ' + service_data.locale_data.title">
                <div class="front-content">
                    <h3 class="services-title">{{ service_data.locale_data.title }}</h3>
                </div>
            </div>

            <div v-else class="service-icon services-background-image" :aria-label="'Default service image for ' + service_data.locale_data.title">
                <div class="front-content">
                    <h3 class="services-title">{{ service_data.locale_data.title }}</h3>
                </div>
            </div>

            <div class="service-content">
                <a class="cursor_pointer" @click="go_to_service('../service/' + service_data.global_data.url_title)" :aria-label="'View details for ' + service_data.locale_data.title">
                    <h3>{{ service_data.locale_data.title }}</h3>
                </a>
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
        data: function () {
            return {
                MIX_SITE_URL: process.env.MIX_SITE_URL,
                MIX_APP_SSH: process.env.MIX_APP_SSH,
            };
        },
        components: {
            
        },
        mounted() {

        },
        methods: {
            go_to_service(service){
                window.open(this.MIX_APP_SSH + 'shop.' + this.MIX_SITE_URL + '/' + service)
            },
        }
    }
</script>

<style scoped>
    .service-box {
        background: #fff;
        box-shadow: rgba(0, 0, 0, 0.884);
        border-radius: 8px;
        overflow: hidden;
        transition: all 0.3s ease;
        margin-bottom: 20px;
        cursor: pointer;
    }

    .service-box:hover {
        box-shadow: 0 4px 16px rgba(0,0,0,0.2);
        transform: translateY(-5px);
    }

    .service-icon {
        position: relative;
        aspect-ratio: 16 / 9;
        background-size: cover;
        background-position: center;
        background-repeat: no-repeat;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.3s ease;
    }

    .service-box:hover .service-icon {
        transform: scale(1.05);
    }

    .front-content {
        border-radius: 0 0 15px 15px;
        background: rgba(0, 0, 0, 0.825);
        width: 100%;
        height: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        text-align: center;
        padding: 20px;
        transition: background 0.3s ease;
    }

    .service-box:hover .front-content {
        background: rgba(0,0,0,0.8);
    }

    .services-title {
        color: #fff;
        font-size: 1.5em;
        font-weight: bold;
        margin: 0;
        text-shadow: 1px 1px 2px rgba(0,0,0,0.7);
    }

    .service-content {
        padding: 20px;
        text-align: center;
    }

    .service-content h3 {
        font-size: 1.2em;
        margin-bottom: 10px;
        color: #ffffffc1;
    }

    .service-content h3 a {
        color: #ffffffc1;
        text-decoration: none;
        transition: color 0.3s ease;
    }

    .service-content h3 a:hover {
        color: #007bff;
    }

    .service-description {
        font-size: 0.9em;
        line-height: 1.5;
        color: #666;
        margin-top: 10px;
    }

    /* Mobile responsiveness */
    @media (max-width: 768px) {
        .service-box {
            margin-bottom: 15px;
        }

        .service-icon {
            aspect-ratio: 16 / 9;
        }

        .services-title {
            font-size: 1.2em;
        }

        .service-content {
            padding: 15px;
        }

        .service-content h3 {
            font-size: 1.1em;
        }

        .service-description {
            font-size: 0.8em;
        }
    }
</style>
