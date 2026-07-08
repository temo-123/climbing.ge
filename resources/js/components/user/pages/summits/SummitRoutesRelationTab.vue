<template>
    <div class="summit-routes-relation">

        <!-- Summit selector (hidden when presetSummitId is given) -->
        <div v-if="!presetSummitId" class="row mb-3">
            <div class="col-md-5">
                <label class="font-weight-bold">{{ $t('admin.summits.select_summit_label') }}</label>
                <select class="form-control" v-model="selected_summit_id" @change="load_routes">
                    <option :value="null">{{ $t('admin.summits.choose_summit_placeholder') }}</option>
                    <option v-for="s in summits" :key="s.id" :value="s.id">
                        {{ s.title }}
                    </option>
                </select>
            </div>
        </div>

        <div v-if="selected_summit_id">

            <!-- Current routes -->
            <div class="card mb-3">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <strong>{{ $t('admin.summits.linked_mount_routes_title') }}</strong>
                    <span class="badge badge-secondary">{{ linked_routes.length }}</span>
                </div>
                <div class="card-body p-0">
                    <div v-if="routes_loading" class="text-center py-3">
                        <i class="fa fa-spinner fa-spin"></i> {{ $t('admin.summits.loading_dots') }}
                    </div>
                    <div v-else-if="!linked_routes.length" class="text-muted text-center py-3">
                        {{ $t('admin.summits.no_mount_routes_linked_yet') }}
                    </div>
                    <table v-else class="table table-sm table-hover mb-0">
                        <thead class="thead-light">
                            <tr>
                                <th>#</th>
                                <th>{{ $t('admin.summits.col_route_name') }}</th>
                                <th style="width:80px"></th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="route in linked_routes" :key="route.id">
                                <td>{{ route.article_id }}</td>
                                <td>{{ route.name }}</td>
                                <td>
                                    <button
                                        class="btn btn-sm btn-danger"
                                        :disabled="removing_id === route.id"
                                        @click="remove_route(route.id)"
                                    >
                                        <i class="fa fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Add route -->
            <div class="card">
                <div class="card-header"><strong>{{ $t('admin.summits.add_mount_route_title') }}</strong></div>
                <div class="card-body">
                    <div v-if="add_error" class="alert alert-danger py-2">{{ add_error }}</div>
                    <div class="form-row align-items-end">
                        <div class="col-md-7">
                            <!-- Mount massive filter -->
                            <label>{{ $t('admin.articles.filter_by_mount_masive') }}</label>
                            <select class="form-control mb-2" v-model="filter_mount_id" @change="selected_article_id = null">
                                <option :value="null">{{ $t('admin.summits.all_massives_option') }}</option>
                                <option v-for="m in available_massives" :key="m.id" :value="m.id">
                                    {{ m.name }}
                                </option>
                            </select>

                            <label>{{ $t('admin.summits.search_select_route_label') }}</label>
                            <input
                                type="text"
                                class="form-control mb-1"
                                v-model="search_query"
                                :placeholder="$t('admin.summits.filter_available_routes_placeholder')"
                            />
                            <select class="form-control" v-model="selected_article_id" size="6">
                                <option :value="null" disabled>{{ $t('admin.summits.select_route_placeholder') }}</option>
                                <option
                                    v-for="r in filtered_available"
                                    :key="r.id"
                                    :value="r.id"
                                    :class="{ 'text-warning': r.summit_id && r.summit_id !== selected_summit_id }"
                                >
                                    {{ r.name }}
                                    <span v-if="!r.published" class="text-muted"> {{ $t('admin.summits.unpublished_suffix') }}</span>
                                    <template v-if="r.summit_id && r.summit_id !== selected_summit_id"> {{ $t('admin.summits.other_summit_suffix') }}</template>
                                </option>
                            </select>
                        </div>
                        <div class="col-md-5">
                            <div v-if="selected_route_info" class="alert alert-info py-2 small mb-2">
                                <strong>{{ selected_route_info.name }}</strong><br>
                                <span v-if="selected_route_info.summit_id && selected_route_info.summit_id !== selected_summit_id" class="text-warning">
                                    {{ $t('admin.summits.currently_linked_other_summit') }}
                                </span>
                                <span v-else-if="is_already_linked" class="text-danger">
                                    {{ $t('admin.summits.already_linked_summit') }}
                                </span>
                            </div>
                            <button
                                class="btn btn-success"
                                :disabled="!selected_article_id || adding || is_already_linked"
                                @click="add_route"
                            >
                                <i class="fa fa-plus"></i>
                                <span v-if="adding"> {{ $t('admin.summits.adding_ellipsis') }}</span>
                                <span v-else> {{ $t('admin.summits.add_relation_btn') }}</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</template>

<script>
export default {
    name: 'SummitRoutesRelationTab',
    props: {
        summits: { type: Array, default: () => [] },
        presetSummitId: { type: Number, default: null },
    },
    data() {
        return {
            selected_summit_id: null,
            linked_routes: [],
            routes_loading: false,
            removing_id: null,

            all_routes: [],
            filter_mount_id: null,
            search_query: '',
            selected_article_id: null,
            adding: false,
            add_error: null,
        }
    },
    computed: {
        available_massives() {
            const seen = new Set()
            const massives = []
            for (const r of this.all_routes) {
                if (r.mount_id && !seen.has(r.mount_id)) {
                    seen.add(r.mount_id)
                    massives.push({ id: r.mount_id, name: r.mount_name || `${this.$t('admin.summits.massive_hash_prefix')}${r.mount_id}` })
                }
            }
            return massives.sort((a, b) => a.name.localeCompare(b.name))
        },
        filtered_available() {
            const q = this.search_query.toLowerCase()
            return this.all_routes.filter(r => {
                if (this.filter_mount_id && r.mount_id !== this.filter_mount_id) return false
                if (q && !r.name.toLowerCase().includes(q)) return false
                return true
            })
        },
        selected_route_info() {
            if (!this.selected_article_id) return null
            return this.all_routes.find(r => r.id === this.selected_article_id) || null
        },
        is_already_linked() {
            if (!this.selected_article_id) return false
            return this.linked_routes.some(r => r.article_id === this.selected_article_id)
        },
    },
    mounted() {
        this.load_all_routes()
        if (this.presetSummitId) {
            this.selected_summit_id = this.presetSummitId
            this.load_routes()
        }
    },
    methods: {
        load_all_routes() {
            axios.get('get_summit_admin/get_mount_routes')
                .then(r => { this.all_routes = r.data })
                .catch(() => {})
        },
        load_routes() {
            if (!this.selected_summit_id) return
            this.routes_loading = true
            this.linked_routes = []
            this.selected_article_id = null
            this.filter_mount_id = null
            this.search_query = ''
            this.add_error = null
            axios.get(`get_summit_admin/get_summit_mount_routes/${this.selected_summit_id}`)
                .then(r => { this.linked_routes = r.data.routes })
                .catch(() => {})
                .finally(() => { this.routes_loading = false })
        },
        add_route() {
            if (!this.selected_article_id || this.is_already_linked) return
            this.adding = true
            this.add_error = null
            axios.post('set_summit/add_mount_route_relation', {
                summit_id:  this.selected_summit_id,
                article_id: this.selected_article_id,
            })
                .then(r => {
                    this.linked_routes.push(r.data)
                    this.selected_article_id = null
                    this.search_query = ''
                    // refresh all_routes so summit_id badges update
                    this.load_all_routes()
                })
                .catch(err => {
                    this.add_error = err.response?.data?.message || this.$t('admin.summits.failed_add_relation')
                })
                .finally(() => { this.adding = false })
        },
        remove_route(relation_id) {
            this.removing_id = relation_id
            axios.delete(`set_summit/remove_mount_route_relation/${relation_id}`)
                .then(() => {
                    this.linked_routes = this.linked_routes.filter(r => r.id !== relation_id)
                    this.load_all_routes()
                })
                .catch(() => {})
                .finally(() => { this.removing_id = null })
        },
    },
}
</script>
