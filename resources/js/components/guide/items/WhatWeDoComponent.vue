<template>
    <div class="services">
        <div class="container">

            <h2 class='index_h2'>{{ $t('guide.title.what we do') }}</h2>

            <h3 class="article_list_short_description" v-if="!isLoading">
                {{ siteData.data.what_we_do_description || 'Explore climbing in Georgia - outdoor, indoor, ice & mountaineering adventures' }}
            </h3>
            <div v-else class="text-center py-3">
                <i class="fa fa-spinner fa-spin"></i>
            </div>

            <div class="cg-disciplines">
                <router-link
                    v-for="item in disciplines"
                    :key="item.route"
                    :to="{ name: item.route, params: { locale: $i18n.locale === 'ka' ? 'ka' : undefined } }"
                    class="cg-discipline"
                >
                    <span class="cg-discipline__icon">
                        <img :alt="$t(item.title)" :src="item.image">
                    </span>
                    <h4 class="cg-discipline__title">{{ $t(item.title) }}</h4>
                    <div class="cg-discipline__text" v-if="siteData.data">
                        <span v-html="siteData.data[item.description] || ''"></span>
                    </div>
                    <span class="cg-discipline__go">
                        {{ $t('global.footer.more') }} <i class="fa fa-long-arrow-right" aria-hidden="true"></i>
                    </span>
                </router-link>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    data() {
            return {
                siteData: { data: {}, loaded: false },
                globalSiteData: { data: {}, loaded: false },
                isLoading: true,
                disciplines: [
                    { route: 'outdoors', title: 'guide.title.outdoor climbing', description: 'outdoor_description', image: '/images/site_img/outdoor.png' },
                    { route: 'indoors', title: 'guide.title.indoor climbing', description: 'indoor_description', image: '/images/site_img/indoor.png' },
                    { route: 'ices', title: 'guide.title.ice climbing', description: 'ice_description', image: '/images/site_img/ice.png' },
                    { route: 'mountaineerings', title: 'guide.title.mountaineering', description: 'mount_description', image: '/images/site_img/mount.png' },
                ],
            }
        },
        mounted() {
            this.loadSiteData();
        },
        methods: {
            loadSiteData() {
                this.isLoading = true;
                const lang = localStorage.getItem('lang') || 'us';
                axios.get("/get_site_data/get_site_locale_data_for_site/" + lang).then((response) => {
                    this.siteData.data = response.data.locale_data || {};
                    this.siteData.loaded = true;
                    this.globalSiteData.data = response.data.global_data || {};
                    this.globalSiteData.loaded = true;
                    this.isLoading = false;
                }).catch((error) => {
                    this.isLoading = false;
                });
            }
        }
    }
</script>

<style>
    .cg-disciplines {
        display: grid;
        grid-template-columns: repeat(4, minmax(0, 1fr));
        gap: 1rem;
    }
    @media (max-width: 991.98px) {
        .cg-disciplines { grid-template-columns: repeat(2, minmax(0, 1fr)); }
    }
    @media (max-width: 479.98px) {
        .cg-disciplines { grid-template-columns: 1fr; }
    }
    .cg-discipline {
        position: relative;
        display: flex;
        flex-direction: column;
        padding: 1.4rem 1.3rem 1.2rem;
        overflow: hidden;
        background: #fff;
        border: 1px solid var(--cg-line);
        border-radius: var(--cg-r-lg);
        box-shadow: var(--cg-shadow-sm);
        color: var(--cg-ink-2);
        transition: transform .3s var(--cg-ease), box-shadow .3s ease, border-color .3s ease;
    }
    .cg-discipline::before {
        content: "";
        position: absolute;
        inset: 0 0 auto 0;
        height: 4px;
        background: linear-gradient(90deg, var(--cg-accent) 0 75%, var(--cg-hold) 75%);
        transform: scaleX(0);
        transform-origin: left;
        transition: transform .35s var(--cg-ease);
    }
    .cg-discipline:hover {
        transform: translateY(-4px);
        box-shadow: var(--cg-shadow);
        border-color: rgba(var(--cg-accent-rgb), .35);
        color: var(--cg-ink-2);
    }
    .cg-discipline:hover::before { transform: scaleX(1); }
    .cg-discipline__icon {
        display: grid;
        place-items: center;
        width: 72px;
        height: 72px;
        margin-bottom: 1rem;
        border-radius: 20px;
        background: var(--cg-accent-soft);
        background-image: var(--cg-topo-dark);
        background-size: 200px;
    }
    .cg-discipline__icon img {
        width: 48px;
        height: 48px;
        object-fit: contain;
    }
    .cg-discipline__title {
        margin: 0 0 .4rem;
        font-size: 1.4rem;
    }
    .cg-discipline__text {
        flex: 1;
        font-size: .92rem;
        line-height: 1.55;
        color: var(--cg-stone);
    }
    .cg-discipline__go {
        margin-top: 1rem;
        font-size: .85rem;
        font-weight: 800;
        color: var(--cg-accent);
    }
    .cg-discipline__go i { transition: transform .2s ease; }
    .cg-discipline:hover .cg-discipline__go i { transform: translateX(4px); }
</style>
