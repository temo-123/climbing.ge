<template>
    <div>
        <div class="h-recent-work">
            <div class="container">

                <h1 class="index_h2">{{ $t('summit.title.summit_list') }}</h1>
                <div class="bar"><i class="fa fa-flag-checkered"></i></div>
                <h3 class="article_list_short_description">
                    {{ $t('summit.summit_map_description') }}
                </h3>

                <div class="row" v-if="mounts.length > 0">
                    <div class="container articles_filter_bar">
                        <div class="col-md-6 col-sm-6">{{ $t('summit.mount_filtr') }}</div>
                        <div class="col-md-6 col-sm-6">
                            <select class="form-control" v-model="filter_mount">
                                <option value="All">{{ $t('all') }}</option>
                                <option v-for="mount in mounts" :key="mount.global_data.id" :value="mount.global_data.id">{{ mount.locale_data.title }}</option>
                            </select>
                        </div>
                    </div>
                </div>

                <region-list-header
                    v-if="filter_mount != 'All' && selected_mount_data?.locale_data?.title"
                    :title="selected_mount_data.locale_data.title"
                    :description="selected_mount_data.locale_data.short_description"
                >
                    <template #map-button>
                        <mount-summits-map-modal
                            v-if="selected_mount_data?.global_data?.map"
                            :mount-id="selected_mount_data.global_data.id"
                            :title="selected_mount_data.locale_data.title"
                            :map="selected_mount_data.global_data.map"
                        />
                    </template>
                </region-list-header>

                <view-controls-component
                    v-if="!loading && (summits.length > 0 || summits_by_mount.length > 0)"
                    :view-mode="viewMode"
                    @update:viewMode="viewMode = $event"
                    :group-mode="groupMode"
                    @update:groupMode="groupMode = $event"
                    :filter-spot="filter_mount"
                />

                <div v-if="loading" class="text-center py-5">
                    <i class="fa fa-spinner fa-spin fa-3x"></i>
                </div>

                <div v-else-if="summits.length === 0 && summits_by_mount.length === 0" class="text-center py-5">
                    <p class="text-muted">{{ $t('summit.no_summits') }}</p>
                </div>

                <div v-else>
                    <!-- Grouped View -->
                    <div v-if="groupMode === 'grouped'">
                        <div v-if="summits_by_mount.length > 0">
                            <div v-for="group in summits_by_mount" :key="group.mount ? group.mount.id : 'other'">
                                <region-list-header
                                    :title="group.mount ? group.mount.title : 'Other'"
                                    :description="group.mount ? group.mount.short_description : ''"
                                >
                                    <template #map-button>
                                        <mount-summits-map-modal
                                            v-if="group.mount?.map"
                                            :mount-id="group.mount.id"
                                            :title="group.mount.title"
                                            :map="group.mount.map"
                                        />
                                    </template>
                                </region-list-header>
                                <div class="row smt-grid" :class="{'list-view': viewMode === 'list'}">
                                    <div v-for="summit in group.summits" :key="summit.id" class="col-md-4 col-sm-6 smt-grid__col">
                                        <summit-card :summit="summit" />
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div v-else class="row smt-grid" :class="{'list-view': viewMode === 'list'}">
                            <div v-for="summit in summits" :key="summit.id" class="col-md-4 col-sm-6 smt-grid__col">
                                <summit-card :summit="summit" />
                            </div>
                        </div>
                    </div>

                    <!-- Flat View -->
                    <div v-else class="row smt-grid" :class="{'list-view': viewMode === 'list'}">
                        <div v-for="summit in (summits_by_mount.length > 0 ? getAllSummits(summits_by_mount) : summits)" :key="summit.id" class="col-md-4 col-sm-6 smt-grid__col">
                            <summit-card :summit="summit" />
                        </div>
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
import viewControlsComponent from '../../../guide/items/ViewControlsComponent.vue'
import regionListHeader from '../../../guide/items/RegionListHeaderComponent.vue'

export default {
    name: 'SummitListPage',
    components: { metaData, SummitCard, viewControlsComponent, regionListHeader },
    data() {
        return {
            summits: [],
            summits_by_mount: [],
            mounts: [],
            filter_mount: 'All',
            selected_mount_data: null,
            loading: false,

            viewMode: 'grid',
            groupMode: 'grouped',
        }
    },
    watch: {
        filter_mount() {
            this.loadSummits()
        },
        '$route'(to, from) {
            this.get_mounts()
            this.loadSummits()
            window.scrollTo(0, 0)
        },
    },
    mounted() {
        this.get_mounts()
        this.loadSummits()
    },
    methods: {
        loadSummits() {
            if (this.filter_mount === 'All') {
                this.get_unfiltered_summits()
            } else {
                this.get_filtered_summits(this.filter_mount)
                this.get_selected_mount_data(this.filter_mount)
            }
        },
        get_unfiltered_summits() {
            this.loading = true
            this.summits = []
            axios.get('summit/list_by_mount/' + (localStorage.getItem('lang') || 'us'))
                .then(r => { this.summits_by_mount = r.data })
                .catch(() => {})
                .finally(() => { this.loading = false })
        },
        get_filtered_summits(mount_id) {
            this.loading = true
            this.summits_by_mount = []
            axios.get('summit/list_filtered/' + mount_id)
                .then(r => { this.summits = r.data })
                .catch(() => {})
                .finally(() => { this.loading = false })
        },
        get_mounts() {
            axios.get('get_mount/get_locale_mounts/' + (localStorage.getItem('lang') || 'us'))
                .then(r => { this.mounts = r.data })
                .catch(() => {})
        },
        get_selected_mount_data(mount_id) {
            axios.get('get_mount/' + (localStorage.getItem('lang') || 'us') + '/' + mount_id)
                .then(r => { this.selected_mount_data = r.data[0] })
                .catch(() => {})
        },
        getAllSummits(summitsByMount) {
            let all = []
            summitsByMount.forEach(group => {
                if (group.summits && Array.isArray(group.summits)) {
                    all = all.concat(group.summits)
                }
            })
            return all
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
.list-view {
    display: block !important;
}
.list-view .smt-grid__col {
    width: 100% !important;
    margin-bottom: 1rem;
}
.list-view :deep(.smt-card) {
    flex-direction: row;
}
.list-view :deep(.smt-card__img-wrap) {
    width: 220px;
    flex-shrink: 0;
}
.list-view :deep(.smt-card__svg) {
    height: 100%;
}
.list-view :deep(.smt-card__body) {
    flex: 1;
}
</style>
