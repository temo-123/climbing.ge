<template>
    <div class="h-recent-work tours" id="tours" v-if="tours.length > 0">
        <div class="container">
            <h2 class="index_h2">{{ article ? $t('guide.tours.article_title') : $t('guide.tours.title') }}</h2>

            <div class="bar"><i class="fa fa-compass"></i></div>

            <h3 class="article_list_short_description">
                {{ article ? $t('guide.tours.article_subtitle') : $t('guide.tours.subtitle') }}
            </h3>

            <card-slider :items="tours" item-key="global_data.id" :desktop="3" :mobile="1" :autoplay="6000" slide-class="tour-slider-item">
                <template #default="{ item }">
                    <tourCard :tour_data="item" />
                </template>
            </card-slider>

            <div class="text-center mt-3">
                <a :href="tours_url" target="_blank" rel="noopener" class="cg-tours-all">{{ $t('guide.tours.all') }} <i class="fa fa-arrow-right" aria-hidden="true"></i></a>
            </div>
        </div>
    </div>
</template>

<script>
    import tourCard from './cards/TourCardComponent'

    export default {
        props: {
            // article pages get "climb it with a guide" wording instead of the index heading
            article: { type: Boolean, default: false },
        },
        components: {
            tourCard
        },
        data() {
            return {
                tours: [],
                MIX_SITE_URL: process.env.MIX_SITE_URL,
                MIX_APP_SSH: process.env.MIX_APP_SSH,
            }
        },
        computed: {
            tours_url() {
                return this.MIX_APP_SSH + 'shop.' + this.MIX_SITE_URL + (this.$i18n.locale === 'ka' ? '/ka' : '') + '/tours'
            },
        },
        mounted() {
            this.get_tours()
        },
        methods: {
            get_tours(){
                axios
                .get('/get_tour/get_tours_for_index/' + (localStorage.getItem('lang') || 'en'))
                .then(response => {
                    this.tours = response.data.slice(0, 9);
                })
                .catch(error =>{
                })
            },
        }
    }
</script>

<style scoped>
    .cg-tours-all {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: .55rem 1.4rem;
        border: 2px solid var(--cg-accent);
        border-radius: 2rem;
        color: var(--cg-accent);
        font-weight: 700;
        text-decoration: none;
        transition: background 0.2s ease, color 0.2s ease;
    }

    .cg-tours-all:hover {
        background: var(--cg-accent);
        color: #fff;
    }
</style>
