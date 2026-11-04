<template>
    <StackModal
        v-model="is_open"
        :title="$t('admin.users.manage_user_title')"
        size="xl"
        :saveButton="{ visible: false }"
        :cancelButton="{ visible: true, title: $t('common.close'), btnClass: { 'btn btn-secondary': true } }"
        @close="close()"
    >
        <div v-if="!user" class="text-center py-4 text-muted">
            <span class="spinner-border spinner-border-sm text-primary" role="status"></span>
            {{ $t('admin.export.loading_ellipsis') }}
        </div>

        <div v-else class="user-manage">
            <!-- Summary header -->
            <div class="user-manage__head">
                <img
                    v-if="user.image"
                    :src="'/public/images/user_profil_img/' + user.image"
                    class="user-manage__avatar"
                    alt=""
                    loading="lazy"
                    decoding="async"
                >
                <div v-else class="user-manage__avatar user-manage__avatar--empty">
                    {{ initials }}
                </div>

                <div class="user-manage__who">
                    <div class="user-manage__name">
                        {{ user.name }} {{ user.surname }}
                        <span class="text-muted small fw-normal">#{{ user.id }}</span>
                    </div>
                    <div class="small text-muted text-break">
                        {{ user.email }}<span v-if="user.phone_number"> · {{ user.phone_number }}</span>
                    </div>
                    <div class="user-manage__badges">
                        <span v-if="user.email_verified_at" class="badge text-bg-success">
                            <i class="fa fa-check"></i> {{ $t('admin.users.col_verified') }}
                        </span>
                        <span v-else class="badge text-bg-secondary">
                            <i class="fa fa-times"></i> {{ $t('admin.users.not_verified_badge') }}
                        </span>
                        <span v-if="row.is_banned" class="badge text-bg-danger">
                            <i class="fa fa-ban"></i> {{ $t('admin.users.col_banned') }}
                        </span>
                        <span v-if="user.is_team_member" class="badge text-bg-info">{{ $t('admin.users.col_team') }}</span>
                        <span v-if="row.role_name" class="badge text-bg-primary">{{ row.role_name }}</span>
                        <span v-if="row.partner_organization_name" class="badge text-bg-warning">
                            <i class="fa fa-handshake-o"></i> {{ row.partner_organization_name }}
                        </span>
                    </div>
                </div>

                <div class="user-manage__stats d-none d-md-flex">
                    <div>
                        <div class="small text-muted">{{ $t('admin.users.col_points') }}</div>
                        <div class="fw-semibold">{{ row.points_total ?? '—' }}</div>
                    </div>
                    <div>
                        <div class="small text-muted">{{ $t('admin.users.col_last_seen') }}</div>
                        <div class="fw-semibold">{{ format_date(user.last_seen_at) }}</div>
                    </div>
                </div>
            </div>

            <!-- Tabs -->
            <ul class="nav nav-tabs user-manage__tabs" role="tablist">
                <li v-for="tab in visible_tabs" :key="tab.key" class="nav-item" role="presentation">
                    <button
                        type="button"
                        class="nav-link"
                        :class="{ active: active_tab === tab.key }"
                        role="tab"
                        :aria-selected="active_tab === tab.key"
                        @click="active_tab = tab.key"
                    >
                        <i :class="'fa ' + tab.icon" aria-hidden="true"></i> {{ tab.title }}
                    </button>
                </li>
            </ul>

            <div class="user-manage__body">
                <ProfileTab
                    v-if="active_tab === 'profile'"
                    :user="user"
                    @saved="on_profile_saved"
                />
                <RoleTab
                    v-else-if="active_tab === 'role'"
                    :user_id="user.id"
                    @saved="changed"
                />
                <WarehouseTab
                    v-else-if="active_tab === 'warehouse'"
                    :user_id="user.id"
                />
                <OrganizationTab
                    v-else-if="active_tab === 'organization'"
                    :user_id="user.id"
                    @saved="changed"
                />
                <SecurityTab
                    v-else-if="active_tab === 'security'"
                    :user_id="user.id"
                    @saved="changed"
                />
            </div>
        </div>
    </StackModal>
</template>

<script>
import ProfileTab from './ProfileTab.vue'
import RoleTab from './RoleTab.vue'
import WarehouseTab from './WarehouseTab.vue'
import OrganizationTab from './OrganizationTab.vue'
import SecurityTab from './SecurityTab.vue'

// One modal for everything an admin does to a user (replaces the separate
// info / role / warehouse / ban / organization / password modals). Each tab
// loads its own data when opened and saves independently.
export default {
    emits: ['update'],
    components: { ProfileTab, RoleTab, WarehouseTab, OrganizationTab, SecurityTab },
    data() {
        return {
            is_open: false,
            user: null,
            row: {},
            active_tab: 'profile',
        }
    },
    computed: {
        visible_tabs() {
            return [
                { key: 'profile',      icon: 'fa-user',        title: this.$t('admin.users.tab_profile'),      show: true },
                { key: 'role',         icon: 'fa-shield',      title: this.$t('admin.users.tab_role'),         show: this.$can('edit', 'role') },
                { key: 'warehouse',    icon: 'fa-building',    title: this.$t('admin.users.tab_warehouse'),    show: this.$can('edit', 'warehouse') },
                { key: 'organization', icon: 'fa-handshake-o', title: this.$t('admin.users.tab_organization'), show: this.$can('add', 'partner_organization') },
                { key: 'security',     icon: 'fa-lock',        title: this.$t('admin.users.tab_security'),     show: this.$can('create_ban', 'user') || this.$can('edit', 'user') },
            ].filter(tab => tab.show)
        },
        initials() {
            return ((this.user?.name || '').charAt(0) + (this.user?.surname || '').charAt(0)).toUpperCase() || '?'
        },
    },
    methods: {
        // row: the users-table row (role_name, is_banned, points_total, partner org)
        show_modal(user_id, row = {}, tab = 'profile') {
            this.user = null
            this.row = { ...row }
            this.active_tab = this.visible_tabs.some(t => t.key === tab) ? tab : 'profile'
            this.is_open = true
            this.load_user(user_id)
        },
        close() {
            this.is_open = false
        },
        load_user(user_id) {
            axios.get('/set_user/get_user_data/' + user_id)
                .then(response => { this.user = response.data })
                .catch(error => {
                    console.log(error)
                    this.is_open = false
                })
        },
        on_profile_saved(user) {
            Object.assign(this.user, user)
            this.changed()
        },
        // Something changed — refresh the table and our header badges.
        changed() {
            this.$emit('update')
        },
        refresh_row(row) {
            if (row && this.user && row.id === this.user.id) this.row = { ...row }
        },
        format_date(value) {
            if (!value) return '—'
            return String(value).replace('T', ' ').substring(0, 16)
        },
    },
}
</script>

<style scoped>
.user-manage__head {
    display: flex;
    gap: 1rem;
    align-items: center;
    padding-bottom: 1rem;
    margin-bottom: 1rem;
    border-bottom: 1px solid var(--bs-border-color);
}
.user-manage__avatar {
    width: 64px;
    height: 64px;
    border-radius: 50%;
    object-fit: cover;
    flex-shrink: 0;
}
.user-manage__avatar--empty {
    display: flex;
    align-items: center;
    justify-content: center;
    background: var(--cg-accent);
    color: #fff;
    font-weight: 600;
    font-size: 1.25rem;
}
.user-manage__who {
    flex: 1;
    min-width: 0;
}
.user-manage__name {
    font-size: 1.15rem;
    font-weight: 600;
}
.user-manage__badges {
    display: flex;
    flex-wrap: wrap;
    gap: .35rem;
    margin-top: .4rem;
}
.user-manage__stats {
    gap: 1.5rem;
    text-align: right;
}
.user-manage__tabs {
    flex-wrap: nowrap;
    overflow-x: auto;
    overflow-y: hidden;
}
.user-manage__tabs .nav-link {
    white-space: nowrap;
}
.user-manage__body {
    padding-top: 1rem;
}
</style>
