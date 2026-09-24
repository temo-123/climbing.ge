<template>
    <div class="local_bisnes_block" v-if="local_businesses.length > 0">
        <h2 class="navbar_title local_bisnes_title">{{ $t('guide.article_right_nabar.recomended_services') }}</h2>

        <!-- arrows centred on the 190px card photo (8px track padding + 95px) -->
        <card-slider
            :items="local_businesses"
            item-key="global_data.id"
            :desktop="2"
            :mobile="1"
            :gap="12"
            arrows="inside"
            arrows-top="103px"
        >
            <template #default="{ item: bisnes }">
                <div class="bisnes-card" @click="openModal(bisnes.global_data.url_title)">
                    <img
                        v-if="bisnes.image.length > 0"
                        class="bisnes-card-img"
                        :src="'../../../images/suport_local_bisnes_img/' + bisnes.image"
                        :alt="bisnes.local_data.title"
                    />
                    <img
                        v-else
                        class="bisnes-card-img"
                        :src="'/../public/images/site_img/image.png'"
                        :alt="bisnes.local_data.title"
                    />
                    <div class="bisnes-card-body">
                        <h3 class="bisnes-card-title">{{ bisnes.local_data.title }}</h3>
                        <div class="bisnes-card-desc" v-html="bisnes.local_data.short_description"></div>
                    </div>
                </div>
            </template>
        </card-slider>

        <LocalBisnesModal
            v-if="activeUrlTitle"
            :url-title="activeUrlTitle"
            v-model="showModal"
        />
    </div>
</template>

<script>
    import LocalBisnesModal from './modals/LocalBisnesModalComponent.vue'

    export default {
        components: {
            LocalBisnesModal
        },
        data() {
            return {
                local_businesses: [],
                showModal: false,
                activeUrlTitle: null,
            }
        },
        mounted() {
            this.get_local_bisnes_for_article()
        },
        methods: {
            get_local_bisnes_for_article() {
                const lang = localStorage.getItem('lang') || 'en'
                axios
                    .get('/get_bisnes/get_local_bisnes_for_article/' + this.$route.params.url_title + '/' + lang)
                    .then(response => {
                        this.local_businesses = Array.isArray(response.data) ? response.data : []
                    })
                    .catch(() => {
                        this.local_businesses = []
                    })
            },
            openModal(urlTitle) {
                this.showModal = false
                this.activeUrlTitle = urlTitle
                this.$nextTick(() => {
                    this.showModal = true
                })
            },
        }
    }
</script>

<style scoped>
.local_bisnes_block {
    margin: 10px 0 24px;
}

.local_bisnes_title {
    margin: 0 0 10px;
}

.bisnes-card {
    height: 100%;
    border: 1px solid #e6e6e6;
    border-radius: 10px;
    background: #fff;
    overflow: hidden;
    cursor: pointer;
    transition: box-shadow 0.2s ease, transform 0.2s ease;
}
@media (hover: hover) {
    .bisnes-card:hover {
        box-shadow: 0 4px 16px rgba(0, 0, 0, 0.15);
        transform: translateY(-2px);
    }
}
.bisnes-card-img {
    display: block;
    width: 100%;
    height: 190px;
    object-fit: cover;
}
.bisnes-card-body {
    padding: 12px 14px;
}
.bisnes-card-title {
    margin: 0 0 6px;
    font-size: 1.15rem;
    font-weight: 600;
    color: #333;
}
.bisnes-card-desc {
    color: #555;
    font-size: 0.95rem;
    overflow-wrap: break-word;
}
</style>
