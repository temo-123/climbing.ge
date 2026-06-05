<template>
    <div>
        <div class="h-recent-work">
            <div class="container">

                <h1 class="index_h2">{{ $t('summit.title.summit_list') }}</h1>
                <div class="bar"><i class="fa fa-flag-checkered"></i></div>
                <h3 class="article_list_short_description">
                    {{ $t('summit.summit_map_description') }}
                </h3>

                <div v-if="loading" class="text-center py-5">
                    <i class="fa fa-spinner fa-spin fa-3x"></i>
                </div>

                <div v-else-if="summits.length === 0" class="text-center py-5">
                    <p class="text-muted">{{ $t('summit.no_summits') }}</p>
                </div>

                <div v-else class="row smt-grid">
                    <div v-for="summit in summits" :key="summit.id" class="col-md-4 col-sm-6 smt-grid__col">
                        <summit-card :summit="summit" />
                    </div>
                </div>

            </div>
        </div>

        <metaData
            :title="$t('summit.title.summit_list') + ' – Summit Climbing Georgia'"
            :description="$t('summit.summit_map_description')"
            :image="'/public/images/meta_img/mountain.jpg'"
        />
    </div>
</template>

<script>
import metaData from '../../items/MetaDataComponent.vue'
import SummitCard from '../../items/cards/SummitCardComponent.vue'

export default {
    name: 'SummitListPage',
    components: { metaData, SummitCard },
    data() {
        return {
            summits: [],
            loading: false,
        }
    },
    mounted() {
        this.loadSummits()
    },
    methods: {
        loadSummits() {
            this.loading = true
            axios.get('summit/list')
                .then(r => { this.summits = r.data })
                .catch(() => {})
                .finally(() => { this.loading = false })
        },
    }
}
</script>

<style scoped>
.smt-grid {
    margin-top: 10px;
}
.smt-grid__col {
    margin-bottom: 24px;
    display: flex;
}
.smt-grid__col > * {
    width: 100%;
}
</style>
