<template>
    <div class="h-recent-work">
        <div class="container">

            <h1 class="index_h2">{{ $t('summit.menu.my_ascents') }}</h1>
            <div class="bar"><i class="fa fa-flag"></i></div>

            <div v-if="loading" class="text-center py-5">
                <i class="fa fa-spinner fa-spin fa-3x text-muted"></i>
            </div>

            <div v-else-if="!is_authenticated" class="text-center py-5">
                <i class="fa fa-lock fa-3x text-muted mb-3"></i>
                <p class="text-muted">{{ $t('summit.my_ascents.login_required') }}</p>
                <a :href="user_url" class="btn btn-primary">{{ $t('summit.my_ascents.go_to_login') }}</a>
            </div>

            <div v-else-if="ascents.length === 0" class="text-center py-5">
                <i class="fa fa-mountain fa-3x text-muted mb-3"></i>
                <p class="text-muted">{{ $t('summit.my_ascents.no_ascents') }}</p>
                <router-link to="/summits/list" class="btn btn-primary">
                    {{ $t('summit.menu.list') }}
                </router-link>
            </div>

            <div v-else>
                <p class="text-muted mb-3">
                    <strong>{{ ascents.length }}</strong> {{ $t('summit.my_ascents.total') }}
                </p>

                <div class="table-responsive">
                    <table class="table table-hover ascent-table">
                        <thead class="thead-dark">
                            <tr>
                                <th>#</th>
                                <th>{{ $t('summit.my_ascents.summit') }}</th>
                                <th>{{ $t('common.date') }}</th>
                                <th>{{ $t('common.route') }}</th>
                                <th>{{ $t('common.grade') }}</th>
                                <th>{{ $t('summit.my_ascents.gps') }}</th>
                                <th>{{ $t('summit.my_ascents.comment') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(ascent, index) in ascents" :key="ascent.id">
                                <td class="text-muted small">{{ index + 1 }}</td>
                                <td>
                                    <router-link v-if="ascent.summit_url" :to="'/summit/' + ascent.summit_url" class="summit-link">
                                        {{ ascent.summit_title }}
                                    </router-link>
                                    <span v-else>{{ ascent.summit_title }}</span>
                                </td>
                                <td>
                                    <span v-if="ascent.ascent_date">{{ formatDate(ascent.ascent_date) }}</span>
                                    <span v-else class="text-muted">—</span>
                                </td>
                                <td>
                                    <span v-if="ascent.route_name">{{ ascent.route_name }}</span>
                                    <span v-else class="text-muted">—</span>
                                </td>
                                <td>
                                    <span v-if="ascent.route_grade" class="badge badge-secondary">{{ ascent.route_grade }}</span>
                                    <span v-else class="text-muted">—</span>
                                </td>
                                <td>
                                    <span v-if="ascent.is_gps_validated" class="badge badge-success">
                                        <i class="fa fa-check"></i> GPS
                                    </span>
                                    <span v-else class="badge badge-light text-muted">—</span>
                                </td>
                                <td>
                                    <span v-if="ascent.comment" class="comment-cell" :title="ascent.comment">
                                        {{ truncate(ascent.comment, 60) }}
                                    </span>
                                    <span v-else class="text-muted">—</span>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="text-center mt-4">
                <router-link to="/summits/list" class="btn btn-default btn-send main-btn">
                    <i class="fa fa-list"></i> {{ $t('summit.menu.list') }}
                </router-link>
            </div>
        </div>

        <metaData
            :title="$t('summit.menu.my_ascents') + ' – Summit Climbing Georgia'"
            :description="$t('summit.my_ascents.no_ascents')"
            :image="'/public/images/meta_img/mountain.jpg'"
        />
    </div>
</template>

<script>
import metaData from '../items/MetaDataComponent.vue'

export default {
    components: { metaData },
    data() {
        return {
            ascents: [],
            loading: true,
            is_authenticated: false,
            user_url: (process.env.MIX_APP_SSH || '') + (process.env.MIX_USER_PAGE_URL || ''),
        }
    },
    mounted() {
        const token = localStorage.getItem('auth_token') || localStorage.getItem('x_xsrf_token')
        if (!token) {
            this.is_authenticated = false
            this.loading = false
            return
        }
        this.is_authenticated = true
        this.load_ascents()
    },
    methods: {
        load_ascents() {
            axios.get('summit/my_ascents')
                .then(response => { this.ascents = response.data })
                .catch(() => { this.is_authenticated = false })
                .finally(() => { this.loading = false })
        },
        formatDate(dateStr) {
            if (!dateStr) return ''
            const d = new Date(dateStr)
            return d.toLocaleDateString()
        },
        truncate(str, len) {
            if (!str) return ''
            return str.length > len ? str.substring(0, len) + '…' : str
        }
    }
}
</script>

<style scoped>
.ascent-table th {
    background-color: #343a40;
    color: #fff;
    font-weight: 600;
    white-space: nowrap;
}
.ascent-table td {
    vertical-align: middle;
}
.summit-link {
    color: #007bff;
    text-decoration: none;
    font-weight: 600;
}
.summit-link:hover {
    text-decoration: underline;
}
.comment-cell {
    font-size: 0.88rem;
    color: #555;
}
</style>
