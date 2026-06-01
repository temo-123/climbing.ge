<template>
    <div class="container" v-if="local_businesses.length > 0">
        <div class="row local_bisnes">
            <h2 class="navbar_title">{{ $t('guide.article_right_nabar.recomended_services') }}</h2>

            <div
                class="col-xs-6 col-sm-6 col-md-6 loc_bisnes_card"
                v-for="bisnes in local_businesses"
                :key="bisnes.global_data.id"
                @click="openModal(bisnes.global_data.url_title)"
            >
                <div class="thumbnail bisnes-card">
                    <img
                        v-if="bisnes.image.length > 0"
                        :src="'../../../images/suport_local_bisnes_img/' + bisnes.image"
                        :alt="bisnes.local_data.title"
                    />
                    <img
                        v-else
                        :src="'/../public/images/site_img/image.png'"
                        :alt="bisnes.local_data.title"
                    />
                    <div class="caption">
                        <h3>{{ bisnes.local_data.title }}</h3>
                    </div>
                    <div class="caption">
                        <span v-html="bisnes.local_data.short_description"></span>
                    </div>
                </div>
            </div>
        </div>

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
            }
        }
    }
</script>

<style scoped>
.loc_bisnes_card {
    margin: 0 !important;
    padding: 0 2px 0 0 !important;
}

.bisnes-card {
    cursor: pointer;
    transition: box-shadow 0.2s ease, transform 0.2s ease;
}

.bisnes-card:hover {
    box-shadow: 0 4px 16px rgba(0, 0, 0, 0.15);
    transform: translateY(-2px);
}
</style>
