<template>
    <article class="cg-tour-card" role="article">
        <a :href="tour_url" target="_blank" rel="noopener" class="cg-tour-card__link" :aria-label="$t('guide.view_details_for_prefix') + ' ' + tour_data.locale_data.title">
            <div class="cg-tour-card__pic cg-wm">
                <site-img v-if="tour_data.image && tour_data.image.length > 0" :src="'/public/images/tour_img/' + tour_data.image" :alt="tour_data.locale_data.title" :width="400" :height="300" />
                <site-img v-else :src="'/public/images/site_img/demo_imgs/tour_demo.jpg'" :alt="tour_data.locale_data.title" :width="400" :height="300" />

                <span class="cg-tour-card__price" v-if="tour_data.global_data.min_price">
                    {{ $t('guide.tours.from', { price: tour_data.global_data.min_price }) }}
                </span>
            </div>

            <div class="cg-tour-card__body">
                <h3 class="cg-tour-card__title">{{ tour_data.locale_data.title }}</h3>

                <div class="cg-tour-card__meta">
                    <span v-if="tour_data.locale_data.location">
                        <i class="fa fa-map-marker" aria-hidden="true"></i> {{ tour_data.locale_data.location }}
                    </span>
                    <span v-if="tour_data.locale_data.duration">
                        <i class="fa fa-clock-o" aria-hidden="true"></i> {{ tour_data.locale_data.duration }}
                    </span>
                </div>

                <span class="cg-tour-card__btn">{{ $t('guide.tours.view') }}</span>
            </div>
        </a>
    </article>
</template>

<script>
    export default {
        props: [
            'tour_data'
        ],
        data: function () {
            return {
                MIX_SITE_URL: process.env.MIX_SITE_URL,
                MIX_APP_SSH: process.env.MIX_APP_SSH,
            };
        },
        computed: {
            // plain href (not window.open) so crawlers follow guide → shop tour links
            tour_url() {
                return this.MIX_APP_SSH + 'shop.' + this.MIX_SITE_URL
                    + (this.$i18n.locale === 'ka' ? '/ka' : '')
                    + '/tour/' + this.tour_data.global_data.url_title
            },
        },
    }
</script>

<style scoped>
    .cg-tour-card {
        height: 100%;
        background: #fff;
        border: 1px solid var(--cg-line);
        border-radius: var(--cg-r-lg);
        box-shadow: var(--cg-shadow-sm);
        overflow: hidden;
        transition: box-shadow 0.3s ease, transform 0.3s ease;
    }

    .cg-tour-card:hover {
        box-shadow: 0 6px 20px rgba(0, 0, 0, 0.18);
        transform: translateY(-4px);
    }

    .cg-tour-card__link {
        display: flex;
        flex-direction: column;
        height: 100%;
        color: inherit;
        text-decoration: none;
    }

    .cg-tour-card__pic {
        position: relative;
        aspect-ratio: 4 / 3;
        overflow: hidden;
        background: var(--cg-line);
    }

    .cg-tour-card__pic :deep(img) {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.3s ease;
    }

    .cg-tour-card:hover .cg-tour-card__pic :deep(img) {
        transform: scale(1.04);
    }

    .cg-tour-card__price {
        position: absolute;
        left: 12px;
        bottom: 12px;
        padding: 4px 10px;
        border-radius: 4px;
        background: var(--cg-accent);
        color: #fff;
        font-size: 0.85rem;
        font-weight: 700;
    }

    .cg-tour-card__body {
        flex: 1;
        display: flex;
        flex-direction: column;
        gap: 8px;
        padding: 14px 16px 16px;
        text-align: left;
    }

    .cg-tour-card__title {
        margin: 0;
        font-size: 1rem;
        font-weight: 600;
        line-height: 1.4;
        color: var(--cg-ink-2);
    }

    .cg-tour-card__meta {
        display: flex;
        flex-wrap: wrap;
        gap: 4px 14px;
        font-size: 0.85rem;
        color: var(--cg-stone);
    }

    .cg-tour-card__btn {
        align-self: flex-start;
        margin-top: auto;
        padding: .45rem 1.1rem;
        border-radius: 2rem;
        background: var(--cg-accent);
        color: #fff;
        font-size: .85rem;
        font-weight: 700;
    }

    .cg-tour-card:hover .cg-tour-card__btn {
        background: var(--cg-accent-dark);
    }
</style>
