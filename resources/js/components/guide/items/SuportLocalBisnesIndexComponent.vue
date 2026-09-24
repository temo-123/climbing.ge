<template>
    <div class="h-recent-work local-bisnes-index" v-if="businesses.length > 0">
        <div class="container">
            <h2 class="index_h2">{{ $t('guide.title.local_businesses') }}</h2>

            <div class="bar"><i class="fa fa-map-marker"></i></div>

            <h3 class="article_list_short_description"> <span v-html="this.$siteData.data.local_bisnes_index_description"></span> </h3>

            <card-slider :items="businesses" item-key="global_data.id" :desktop="3" :mobile="1" :autoplay="5000">
                <template #default="{ item }">
                    <suportLocalBisnesIndexCard
                        :bisnes="item"
                        @open="openModal"
                    />
                </template>
            </card-slider>
        </div>

        <LocalBisnesModal
            v-if="activeUrlTitle"
            :url-title="activeUrlTitle"
            v-model="showModal"
        />
    </div>
</template>

<script>
    import suportLocalBisnesIndexCard from './cards/SuportLocalBisnesIndexCardComponent.vue'
    import LocalBisnesModal from './modals/LocalBisnesModalComponent.vue'

    export default {
        name: 'SuportLocalBisnesIndexComponent',
        components: {
            suportLocalBisnesIndexCard,
            LocalBisnesModal,
        },
        data() {
            return {
                businesses: [],
                showModal: false,
                activeUrlTitle: null,
            }
        },
        mounted() {
            this.get_index_local_bisneses()
        },
        methods: {
            get_index_local_bisneses() {
                const lang = localStorage.getItem('lang') || 'en'
                return axios
                    .get('/get_bisnes/get_index_local_bisneses/' + lang)
                    .then(response => {
                        this.businesses = Array.isArray(response.data) ? response.data : []
                    })
                    .catch(() => {
                        this.businesses = []
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
