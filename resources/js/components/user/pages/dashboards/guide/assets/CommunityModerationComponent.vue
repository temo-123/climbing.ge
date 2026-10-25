<template>
    <!-- Guide dashboard → Community: climbers' condition reports and partner
         posts, with hide / show (admin_hidden). Needs `comment › show`;
         hiding needs `comment › del` (enforced by the API). -->
    <div class="cm">
        <div class="cg-segmented mb-3" role="tablist">
            <button type="button" role="tab" :class="{ active: tab === 'reports' }" @click="tab = 'reports'">
                <i class="fa fa-thermometer-half" aria-hidden="true"></i> {{ $t('admin.community.reports') }} ({{ reports.length }})
            </button>
            <button type="button" role="tab" :class="{ active: tab === 'posts' }" @click="tab = 'posts'">
                <i class="fa fa-handshake-o" aria-hidden="true"></i> {{ $t('admin.community.posts') }} ({{ posts.length }})
            </button>
        </div>

        <div v-if="loading" class="text-center py-4"><div class="spinner-border text-primary" role="status"></div></div>

        <div v-else-if="tab === 'reports'" class="table-responsive">
            <p v-if="!reports.length" class="text-muted">{{ $t('admin.community.empty') }}</p>
            <table v-else class="table table-hover align-middle">
                <thead><tr><th>{{ $t('common.date') }}</th><th>{{ $t('admin.community.area') }}</th><th>{{ $t('admin.community.condition') }}</th><th>{{ $t('admin.community.note') }}</th><th>{{ $t('admin.community.author') }}</th><th class="text-end"></th></tr></thead>
                <tbody>
                    <tr v-for="r in reports" :key="r.id" :class="{ 'cm-hidden': r.admin_hidden }">
                        <td class="text-nowrap" :title="$formatDate(r.created_at)">{{ $timeAgo(r.created_at) }}</td>
                        <td><a v-if="r.article" :href="guide_url(r.article)" target="_blank" rel="noopener">{{ r.article.url_title }}</a></td>
                        <td><span class="cm-cond" :class="'is-' + r.condition">{{ $t('guide.conditions.types.' + r.condition) }}</span></td>
                        <td class="cm-note">{{ r.note }}</td>
                        <td>{{ r.user ? r.user.name + ' ' + (r.user.surname || '') : '—' }}</td>
                        <td class="text-end">
                            <button type="button" class="btn btn-sm" :class="r.admin_hidden ? 'btn-outline-success' : 'btn-outline-danger'" @click="toggle('report', r)">
                                <i class="fa" :class="r.admin_hidden ? 'fa-eye' : 'fa-eye-slash'" aria-hidden="true"></i> {{ r.admin_hidden ? $t('admin.community.show') : $t('admin.community.hide') }}
                            </button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div v-else class="table-responsive">
            <p v-if="!posts.length" class="text-muted">{{ $t('admin.community.empty') }}</p>
            <table v-else class="table table-hover align-middle">
                <thead><tr><th>{{ $t('guide.partners.date') }}</th><th>{{ $t('admin.community.area') }}</th><th>{{ $t('guide.partners.discipline') }}</th><th>{{ $t('admin.community.note') }}</th><th>{{ $t('admin.community.author') }}</th><th class="text-end"></th></tr></thead>
                <tbody>
                    <tr v-for="p in posts" :key="p.id" :class="{ 'cm-hidden': p.admin_hidden }">
                        <td class="text-nowrap">{{ $formatDate(p.climb_date) }}</td>
                        <td><a v-if="p.article" :href="guide_url(p.article)" target="_blank" rel="noopener">{{ p.article.url_title }}</a><span v-else>{{ p.place }}</span></td>
                        <td>{{ $t('guide.partners.disciplines.' + p.discipline) }}</td>
                        <td class="cm-note">{{ p.note }}<small v-if="p.contact" class="d-block text-muted">{{ p.contact }}</small></td>
                        <td>{{ p.user ? p.user.name + ' ' + (p.user.surname || '') : '—' }}<small v-if="p.user" class="d-block text-muted">{{ p.user.email }}</small></td>
                        <td class="text-end">
                            <button type="button" class="btn btn-sm" :class="p.admin_hidden ? 'btn-outline-success' : 'btn-outline-danger'" @click="toggle('post', p)">
                                <i class="fa" :class="p.admin_hidden ? 'fa-eye' : 'fa-eye-slash'" aria-hidden="true"></i> {{ p.admin_hidden ? $t('admin.community.show') : $t('admin.community.hide') }}
                            </button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</template>

<script>
    const PATHS = { outdoor: '/outdoor/', ice: '/ice/', indoor: '/indoor/', mount_route: '/mountaineering/' };

    export default {
        name: 'CommunityModeration',
        data() {
            return { tab: 'reports', reports: [], posts: [], loading: true };
        },
        mounted() {
            Promise.all([
                axios.get('/set_area_report/get_area_reports_admin').then(r => { this.reports = r.data || []; }).catch(() => {}),
                axios.get('/set_partner_post/get_partner_posts_admin').then(r => { this.posts = r.data || []; }).catch(() => {}),
            ]).finally(() => { this.loading = false; });
        },
        methods: {
            guide_url(article) {
                return (process.env.MIX_APP_SSH || 'http://') + process.env.MIX_SITE_URL + (PATHS[article.category] || '/outdoor/') + article.url_title;
            },
            toggle(kind, item) {
                const url = kind === 'report'
                    ? '/set_area_report/hide_area_report_admin/' + item.id
                    : '/set_partner_post/hide_partner_post_admin/' + item.id;
                axios.post(url)
                    .then(r => { item.admin_hidden = r.data.admin_hidden; })
                    .catch(() => this.$bus.$emit('toast', { type: 'danger', message: this.$t('admin.community.error') }));
            },
        },
    };
</script>

<style scoped>
    .cm-hidden td { opacity: .5; }
    .cm-note { max-width: 22rem; white-space: normal; }
    .cm-cond { display: inline-block; padding: .1rem .55rem; border-radius: 1rem; background: var(--cg-chalk-2); font-weight: 700; font-size: .8rem; }
    .cm-cond.is-hazard { background: #c43c3c; color: #fff; }
    .cm-cond.is-closure { background: #343a40; color: #fff; }
</style>
