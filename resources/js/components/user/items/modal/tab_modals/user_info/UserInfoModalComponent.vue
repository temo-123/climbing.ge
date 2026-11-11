<template>
    <StackModal
        v-model="is_open"
        :title="$t('admin.user_info.title')"
        size="xxl"
        @close="close()"
    >
        <div v-if="loading && !data" class="text-center py-4 text-muted">
            <span class="spinner-border spinner-border-sm text-primary" role="status"></span>
            {{ $t('admin.user_info.loading') }}
        </div>

        <div v-else-if="failed" class="alert alert-danger mb-0">
            {{ $t('admin.user_info.load_failed') }}
            <button type="button" class="btn btn-sm btn-outline-danger ms-2" @click="load()">{{ $t('common.retry') }}</button>
        </div>

        <div v-else-if="data" class="user-info" :class="{ 'user-info--reloading': loading }">
            <!-- Summary header -->
            <div class="user-info__head">
                <img
                    v-if="user.image"
                    :src="'/public/images/user_profil_img/' + user.image"
                    class="user-info__avatar"
                    alt=""
                    loading="lazy"
                    decoding="async"
                >
                <div v-else class="user-info__avatar user-info__avatar--empty">{{ initials }}</div>

                <div class="user-info__who">
                    <div class="user-info__name">
                        {{ user.name }} {{ user.surname }}
                        <span class="text-muted small fw-normal">#{{ user.id }}</span>
                    </div>
                    <div class="small text-muted text-break">
                        {{ user.email }}<span v-if="user.phone_number"> · {{ user.phone_number }}</span>
                    </div>
                    <div class="user-info__badges">
                        <span v-if="user.email_verified_at" class="badge text-bg-success">
                            <i class="fa fa-check"></i> {{ $t('admin.users.col_verified') }}
                        </span>
                        <template v-else>
                            <span class="badge text-bg-secondary">
                                <i class="fa fa-times"></i> {{ $t('admin.users.not_verified_badge') }}
                            </span>
                            <template v-if="$can('edit', 'user')">
                                <button type="button" class="btn btn-sm btn-outline-success py-0" :disabled="verifying" @click="verify_user">
                                    <i class="fa fa-check"></i> {{ $t('admin.notification_analytics.verify_user_btn') }}
                                </button>
                                <button type="button" class="btn btn-sm btn-outline-secondary py-0" :disabled="resending" @click="resend_verification">
                                    <i class="fa fa-paper-plane"></i> {{ $t('admin.users.resend_verification_btn') }}
                                </button>
                            </template>
                        </template>
                        <span v-if="user.is_banned" class="badge text-bg-danger">
                            <i class="fa fa-ban"></i> {{ $t('admin.users.col_banned') }}
                        </span>
                        <span v-if="user.is_team_member" class="badge text-bg-info">{{ $t('admin.users.col_team') }}</span>
                        <span v-for="role in account.roles || []" :key="role.id" class="badge text-bg-primary">{{ role.name }}</span>
                        <span v-if="account.organization" class="badge text-bg-warning">
                            <i class="fa fa-handshake-o"></i> {{ account.organization.name }}
                        </span>
                    </div>
                </div>

                <div class="user-info__stats d-none d-md-flex">
                    <div>
                        <div class="small text-muted">{{ $t('admin.users.col_points') }}</div>
                        <div class="fw-semibold">{{ data.points?.total ?? '—' }}</div>
                    </div>
                    <div>
                        <div class="small text-muted">{{ $t('admin.users.col_last_seen') }}</div>
                        <div class="fw-semibold">{{ format_date(user.last_seen_at) }}</div>
                    </div>
                    <div>
                        <div class="small text-muted">{{ $t('admin.user_info.f_registered') }}</div>
                        <div class="fw-semibold">{{ format_date(user.created_at) }}</div>
                    </div>
                </div>
            </div>

            <!-- Tabs -->
            <ul class="nav nav-tabs user-info__tabs" role="tablist">
                <li v-for="tab in tabs" :key="tab.key" class="nav-item" role="presentation">
                    <button
                        type="button"
                        class="nav-link"
                        :class="{ active: active_tab === tab.key }"
                        role="tab"
                        :aria-selected="active_tab === tab.key"
                        @click="active_tab = tab.key"
                    >
                        <i :class="'fa ' + tab.icon" aria-hidden="true"></i> {{ tab.title }}
                        <span v-if="tab.count" class="badge rounded-pill text-bg-light ms-1">{{ tab.count }}</span>
                    </button>
                </li>
            </ul>

            <div class="user-info__body">
                <!-- ───── Overview ───── -->
                <div v-if="active_tab === 'overview'">
                    <div class="user-info__counters">
                        <button
                            v-for="c in counters" :key="c.key"
                            type="button"
                            class="user-info__counter"
                            @click="active_tab = c.tab"
                        >
                            <span class="user-info__counter-num">{{ c.value }}</span>
                            <span class="user-info__counter-label">{{ c.label }}</span>
                        </button>
                    </div>

                    <div class="row g-3">
                        <div class="col-lg-6">
                            <div class="info-card">
                                <div class="info-card__title"><i class="fa fa-user"></i> {{ $t('admin.user_info.sec_profile') }}</div>
                                <dl class="info-dl">
                                    <template v-for="f in profile_fields" :key="f.key">
                                        <dt>{{ f.label }}</dt>
                                        <dd :class="{ 'info-dl__pre': f.key === 'my_bio' }">{{ f.value }}</dd>
                                    </template>
                                </dl>
                            </div>
                        </div>

                        <div class="col-lg-6">
                            <div class="info-card">
                                <div class="info-card__title"><i class="fa fa-shield"></i> {{ $t('admin.user_info.sec_account') }}</div>
                                <dl class="info-dl">
                                    <dt>{{ $t('admin.user_info.roles') }}</dt>
                                    <dd>{{ list_or_none((account.roles || []).map(r => r.name)) }}</dd>

                                    <dt>{{ $t('admin.user_info.extra_permissions') }}</dt>
                                    <dd>
                                        <span v-if="!(account.permissions || []).length">{{ $t('admin.user_info.none') }}</span>
                                        <span v-for="p in account.permissions || []" :key="p.id" class="badge text-bg-light me-1 mb-1">{{ p.subject }} › {{ p.action }}</span>
                                    </dd>

                                    <dt>{{ $t('admin.user_info.organization') }}</dt>
                                    <dd>
                                        <template v-if="account.organization">
                                            {{ account.organization.name }}
                                            <span v-if="account.organization.discount" class="text-muted">· {{ $t('admin.user_info.discount') }} {{ account.organization.discount }}%</span>
                                        </template>
                                        <template v-else>{{ $t('admin.user_info.none') }}</template>
                                    </dd>

                                    <dt>{{ $t('admin.user_info.warehouse') }}</dt>
                                    <dd>
                                        <template v-if="account.warehouse">
                                            {{ account.warehouse.name }}
                                            <span v-if="account.warehouse.is_sale_point" class="badge text-bg-light">{{ $t('admin.user_info.sale_point') }}</span>
                                        </template>
                                        <template v-else>{{ $t('admin.user_info.none') }}</template>
                                    </dd>

                                    <dt>{{ $t('admin.user_info.social_accounts') }}</dt>
                                    <dd>{{ list_or_none((account.social_accounts || []).map(a => a.provider)) }}</dd>

                                    <dt>{{ $t('admin.user_info.strava') }}</dt>
                                    <dd>
                                        <template v-if="account.strava">
                                            {{ account.strava.athlete_name || account.strava.athlete_id }}
                                            <span class="text-muted">· {{ format_date(account.strava.created_at) }}</span>
                                        </template>
                                        <template v-else>{{ $t('admin.user_info.not_connected') }}</template>
                                    </dd>

                                    <dt>{{ $t('admin.user_info.notif_prefs') }}</dt>
                                    <dd>
                                        <template v-if="account.notification_preferences?.total">
                                            {{ $t('admin.user_info.notif_prefs_value', account.notification_preferences) }}
                                        </template>
                                        <template v-else>{{ $t('admin.user_info.none') }}</template>
                                    </dd>
                                </dl>
                            </div>
                        </div>

                        <div class="col-lg-6">
                            <div class="info-card">
                                <div class="info-card__title"><i class="fa fa-trophy"></i> {{ $t('admin.user_info.sec_points') }}</div>
                                <table class="table table-sm mb-0">
                                    <thead>
                                        <tr>
                                            <th>{{ $t('admin.user_info.points_category') }}</th>
                                            <th class="text-end">{{ $t('admin.user_info.points_count') }}</th>
                                            <th class="text-end">×</th>
                                            <th class="text-end">{{ $t('admin.users.col_points') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="p in data.points?.breakdown || []" :key="p.key">
                                            <td>{{ $t('admin.user_info.p_' + p.key) }}</td>
                                            <td class="text-end">{{ p.count }}</td>
                                            <td class="text-end text-muted">{{ p.coefficient }}</td>
                                            <td class="text-end">{{ p.points }}</td>
                                        </tr>
                                        <tr class="fw-semibold">
                                            <td colspan="3">{{ $t('admin.user_info.points_total') }}</td>
                                            <td class="text-end">{{ data.points?.total ?? 0 }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <div class="col-lg-6">
                            <InfoBlock
                                :title="$t('admin.user_info.sessions')"
                                icon="fa-key"
                                :block="account.sessions"
                                :columns="[
                                    { key: 'name', label: $t('admin.user_info.c_device') },
                                    { key: 'last_used_at', label: $t('admin.user_info.c_last_used'), fmt: 'date' },
                                    { key: 'created_at', label: $t('admin.user_info.c_created'), fmt: 'date' },
                                ]"
                            />
                        </div>
                    </div>
                </div>

                <!-- ───── Activity ───── -->
                <div v-else-if="active_tab === 'activity'">
                    <InfoBlock :title="$t('admin.user_info.route_reviews')" icon="fa-star" :block="activity.route_reviews" :columns="[
                        { key: 'route_name', label: $t('admin.user_info.c_route'), value: i => (i.route_name || '—') + (i.route_grade ? ' (' + i.route_grade + ')' : '') },
                        { key: 'stars', label: $t('admin.user_info.c_stars'), fmt: 'stars' },
                        { key: 'ascent_style', label: $t('admin.user_info.c_style') },
                        { key: 'text', label: $t('admin.user_info.c_text'), fmt: 'text' },
                        { key: 'state', label: $t('admin.user_info.c_status'), fmt: 'state' },
                        { key: 'created_at', label: $t('admin.user_info.c_date'), fmt: 'date' },
                    ]"/>
                    <InfoBlock :title="$t('admin.user_info.mtp_reviews')" icon="fa-star-half-o" :block="activity.mtp_reviews" :columns="[
                        { key: 'mtp_name', label: $t('admin.user_info.c_route') },
                        { key: 'stars', label: $t('admin.user_info.c_stars'), fmt: 'stars' },
                        { key: 'text', label: $t('admin.user_info.c_text'), fmt: 'text' },
                        { key: 'state', label: $t('admin.user_info.c_status'), fmt: 'state' },
                        { key: 'created_at', label: $t('admin.user_info.c_date'), fmt: 'date' },
                    ]"/>
                    <InfoBlock :title="$t('admin.user_info.route_ascents')" icon="fa-check-square-o" :block="activity.route_ascents" :columns="[
                        { key: 'route_name', label: $t('admin.user_info.c_route'), value: i => (i.route_name || '—') + (i.route_grade ? ' (' + i.route_grade + ')' : '') },
                        { key: 'ascent_style', label: $t('admin.user_info.c_style') },
                        { key: 'attempts', label: $t('admin.user_info.c_attempts') },
                        { key: 'suggested_grade', label: $t('admin.user_info.c_suggested_grade') },
                        { key: 'ascent_date', label: $t('admin.user_info.c_date'), fmt: 'date' },
                    ]"/>
                    <InfoBlock :title="$t('admin.user_info.mtp_ascents')" icon="fa-check-square-o" :block="activity.mtp_ascents" :columns="[
                        { key: 'mtp_name', label: $t('admin.user_info.c_route') },
                        { key: 'ascent_style', label: $t('admin.user_info.c_style') },
                        { key: 'comment', label: $t('admin.user_info.c_text'), fmt: 'text' },
                        { key: 'ascent_date', label: $t('admin.user_info.c_date'), fmt: 'date' },
                    ]"/>
                    <InfoBlock :title="$t('admin.user_info.summit_ascents')" icon="fa-flag" :block="activity.summit_ascents" :columns="[
                        { key: 'summit_title', label: $t('common.summit') },
                        { key: 'is_gps_validated', label: $t('admin.user_info.c_gps'), fmt: 'bool' },
                        { key: 'is_archive', label: $t('admin.user_info.c_archive'), fmt: 'bool' },
                        { key: 'ascent_date', label: $t('admin.user_info.c_date'), fmt: 'date' },
                    ]"/>
                    <InfoBlock :title="$t('admin.user_info.comments')" icon="fa-comment" :block="activity.comments" :columns="[
                        { key: 'article_url_title', label: $t('admin.user_info.c_item') },
                        { key: 'text', label: $t('admin.user_info.c_text'), fmt: 'text' },
                        { key: 'state', label: $t('admin.user_info.c_status'), fmt: 'state' },
                        { key: 'created_at', label: $t('admin.user_info.c_date'), fmt: 'date' },
                    ]"/>
                    <InfoBlock :title="$t('admin.user_info.partner_posts')" icon="fa-users" :block="activity.partner_posts" :columns="[
                        { key: 'place', label: $t('admin.user_info.c_place'), value: i => i.place || i.article_url_title || '—' },
                        { key: 'discipline', label: $t('admin.user_info.c_type') },
                        { key: 'grade', label: $t('common.grade'), value: i => [i.grade_from, i.grade_to].filter(Boolean).join(' – ') || '—' },
                        { key: 'climb_date', label: $t('admin.user_info.c_date'), fmt: 'date' },
                        { key: 'admin_hidden', label: $t('admin.user_info.st_hidden'), fmt: 'bool' },
                    ]"/>
                    <InfoBlock :title="$t('admin.user_info.condition_reports')" icon="fa-cloud" :block="activity.condition_reports" :columns="[
                        { key: 'article_url_title', label: $t('admin.user_info.c_item') },
                        { key: 'condition', label: $t('admin.user_info.c_condition') },
                        { key: 'note', label: $t('admin.user_info.c_text'), fmt: 'text' },
                        { key: 'created_at', label: $t('admin.user_info.c_date'), fmt: 'date' },
                    ]"/>
                    <InfoBlock :title="$t('admin.user_info.film_ratings')" icon="fa-film" :block="activity.film_ratings" :columns="[
                        { key: 'film_url_title', label: $t('admin.user_info.c_item') },
                        { key: 'review_stars', label: $t('admin.user_info.c_stars'), fmt: 'stars' },
                        { key: 'created_at', label: $t('admin.user_info.c_date'), fmt: 'date' },
                    ]"/>
                    <InfoBlock :title="$t('admin.user_info.blog_posts')" icon="fa-newspaper-o" :block="activity.blog_posts" :columns="[
                        { key: 'url_title', label: $t('admin.user_info.c_item') },
                        { key: 'state', label: $t('admin.user_info.c_status'), fmt: 'state' },
                        { key: 'created_at', label: $t('admin.user_info.c_date'), fmt: 'date' },
                    ]"/>
                </div>

                <!-- ───── Shop ───── -->
                <div v-else-if="active_tab === 'shop'">
                    <div class="user-info__counters">
                        <div class="user-info__counter user-info__counter--static">
                            <span class="user-info__counter-num">{{ shop.orders?.count ?? 0 }}</span>
                            <span class="user-info__counter-label">{{ $t('admin.user_info.orders') }}</span>
                        </div>
                        <div class="user-info__counter user-info__counter--static">
                            <span class="user-info__counter-num">{{ money(shop.orders_total_amount) }}</span>
                            <span class="user-info__counter-label">{{ $t('admin.user_info.orders_total') }}</span>
                        </div>
                        <div class="user-info__counter user-info__counter--static">
                            <span class="user-info__counter-num">{{ shop.cart?.quantity ?? 0 }}</span>
                            <span class="user-info__counter-label">{{ $t('admin.user_info.cart') }}</span>
                        </div>
                    </div>
                    <InfoBlock :title="$t('admin.user_info.orders')" icon="fa-shopping-bag" :block="shop.orders" :columns="[
                        { key: 'id', label: '#' },
                        { key: 'status', label: $t('admin.user_info.c_status') },
                        { key: 'payment', label: $t('common.payment') },
                        { key: 'items_count', label: $t('admin.user_info.c_items') },
                        { key: 'payment_amount', label: $t('admin.user_info.c_amount'), value: i => money(i.payment_amount) },
                        { key: 'confirm', label: $t('admin.user_info.c_confirmed'), fmt: 'bool' },
                        { key: 'created_at', label: $t('admin.user_info.c_date'), fmt: 'date' },
                    ]"/>
                    <InfoBlock :title="$t('admin.user_info.addresses')" icon="fa-map-marker" :block="shop.addresses" :columns="[
                        { key: 'name', label: $t('common.name') },
                        { key: 'address', label: $t('admin.user_info.c_address'), value: address },
                        { key: 'region', label: $t('admin.user_info.c_region') },
                        { key: 'is_default', label: $t('admin.user_info.c_default'), fmt: 'bool' },
                    ]"/>
                    <InfoBlock :title="$t('admin.user_info.product_feedbacks')" icon="fa-comments" :block="shop.product_feedbacks" :columns="[
                        { key: 'product_url_title', label: $t('common.product') },
                        { key: 'stars', label: $t('admin.user_info.c_stars'), fmt: 'stars' },
                        { key: 'text', label: $t('admin.user_info.c_text'), fmt: 'text' },
                        { key: 'state', label: $t('admin.user_info.c_status'), fmt: 'state' },
                        { key: 'created_at', label: $t('admin.user_info.c_date'), fmt: 'date' },
                    ]"/>
                    <InfoBlock :title="$t('admin.user_info.tour_reservations')" icon="fa-calendar" :block="shop.tour_reservations" :columns="[
                        { key: 'tour_url_title', label: $t('admin.user_info.c_item') },
                        { key: 'check_in', label: $t('admin.user_info.c_check_in'), fmt: 'date' },
                        { key: 'persons', label: $t('admin.user_info.c_persons') },
                        { key: 'confirm', label: $t('admin.user_info.c_confirmed'), fmt: 'bool' },
                        { key: 'created_at', label: $t('admin.user_info.c_created'), fmt: 'date' },
                    ]"/>
                    <InfoBlock :title="$t('admin.user_info.gear_rentals')" icon="fa-handshake-o" :block="shop.gear_rentals" :columns="[
                        { key: 'gear', label: $t('admin.user_info.c_item') },
                        { key: 'start_at', label: $t('admin.gear_rental.start_label'), fmt: 'date' },
                        { key: 'days', label: $t('admin.gear_rental.days_label') },
                        { key: 'status', label: $t('admin.gear_rental.status_col') },
                        { key: 'total', label: $t('admin.gear_rental.total_col') },
                    ]"/>
                    <InfoBlock :title="$t('admin.user_info.stock_notifications')" icon="fa-bell" :block="shop.stock_notifications" :columns="[
                        { key: 'option_id', label: $t('admin.user_info.c_option') },
                        { key: 'locale', label: $t('admin.user_info.f_lang') },
                        { key: 'notified_at', label: $t('admin.user_info.c_notified'), fmt: 'date' },
                        { key: 'created_at', label: $t('admin.user_info.c_created'), fmt: 'date' },
                    ]"/>
                    <InfoBlock :title="$t('admin.user_info.owned_products')" icon="fa-cube" :block="shop.owned_products" :columns="[
                        { key: 'url_title', label: $t('common.product') },
                        { key: 'state', label: $t('admin.user_info.c_status'), fmt: 'state' },
                        { key: 'created_at', label: $t('admin.user_info.c_created'), fmt: 'date' },
                    ]"/>
                    <InfoBlock :title="$t('admin.user_info.owned_tours')" icon="fa-map" :block="shop.owned_tours" :columns="[
                        { key: 'url_title', label: $t('admin.user_info.c_item') },
                        { key: 'state', label: $t('admin.user_info.c_status'), fmt: 'state' },
                        { key: 'created_at', label: $t('admin.user_info.c_created'), fmt: 'date' },
                    ]"/>
                </div>

                <!-- ───── Social, favorites & donations ───── -->
                <div v-else-if="active_tab === 'social'">
                    <div class="row g-3">
                        <div class="col-lg-6">
                            <InfoBlock :title="$t('admin.user_info.followers')" icon="fa-user-plus" :block="social.followers" @open_user="open_user" :columns="[
                                { key: 'name', label: $t('common.name'), fmt: 'person' },
                                { key: 'created_at', label: $t('admin.user_info.c_since'), fmt: 'date' },
                            ]"/>
                        </div>
                        <div class="col-lg-6">
                            <InfoBlock :title="$t('admin.user_info.following')" icon="fa-user" :block="social.following" @open_user="open_user" :columns="[
                                { key: 'name', label: $t('common.name'), fmt: 'person' },
                                { key: 'created_at', label: $t('admin.user_info.c_since'), fmt: 'date' },
                            ]"/>
                        </div>
                    </div>
                    <InfoBlock :title="$t('admin.user_info.sites')" icon="fa-globe" :block="social.sites" :columns="[
                        { key: 'url', label: 'URL' },
                    ]"/>
                    <InfoBlock :title="$t('admin.user_info.fav_outdoors')" icon="fa-heart" :block="favorites.outdoors" :columns="[
                        { key: 'url_title', label: $t('admin.user_info.c_item') },
                        { key: 'category', label: $t('common.category') },
                        { key: 'created_at', label: $t('admin.user_info.c_date'), fmt: 'date' },
                    ]"/>
                    <InfoBlock :title="$t('admin.user_info.fav_products')" icon="fa-heart" :block="favorites.products" :columns="[
                        { key: 'url_title', label: $t('common.product') },
                        { key: 'created_at', label: $t('admin.user_info.c_date'), fmt: 'date' },
                    ]"/>
                    <InfoBlock :title="$t('admin.user_info.fav_films')" icon="fa-heart" :block="favorites.films" :columns="[
                        { key: 'url_title', label: $t('admin.user_info.c_item') },
                        { key: 'created_at', label: $t('admin.user_info.c_date'), fmt: 'date' },
                    ]"/>
                    <InfoBlock :title="$t('admin.user_info.fav_events')" icon="fa-calendar-check-o" :block="favorites.events" :columns="[
                        { key: 'url_title', label: $t('admin.user_info.c_item') },
                        { key: 'start_data', label: $t('admin.user_info.c_starts'), fmt: 'date' },
                        { key: 'created_at', label: $t('admin.user_info.c_date'), fmt: 'date' },
                    ]"/>
                    <InfoBlock :title="$t('admin.user_info.donations')" icon="fa-money" :block="data.donations" :columns="[
                        { key: 'amount', label: $t('admin.user_info.c_amount'), value: i => money(i.amount) + ' ' + (i.currency || '') },
                        { key: 'status', label: $t('admin.user_info.c_status') },
                        { key: 'comment', label: $t('admin.user_info.c_text'), fmt: 'text' },
                        { key: 'created_at', label: $t('admin.user_info.c_date'), fmt: 'date' },
                    ]"/>
                    <div v-if="(data.donations?.totals || []).length" class="small text-muted mt-n2 mb-3">
                        {{ $t('admin.user_info.donation_totals') }}:
                        <span v-for="t in data.donations.totals" :key="t.currency" class="me-2">{{ money(t.amount) }} {{ t.currency }} ({{ t.n }})</span>
                    </div>
                </div>

                <!-- ───── Training app ───── -->
                <div v-else-if="active_tab === 'training'">
                    <div class="user-info__counters">
                        <div class="user-info__counter user-info__counter--static">
                            <span class="user-info__counter-num">{{ training.board_routes ?? 0 }}</span>
                            <span class="user-info__counter-label">{{ $t('admin.user_info.board_routes') }}</span>
                        </div>
                        <div class="user-info__counter user-info__counter--static">
                            <span class="user-info__counter-num">{{ training.board_ascents ?? 0 }}</span>
                            <span class="user-info__counter-label">{{ $t('admin.user_info.board_ascents') }}</span>
                        </div>
                    </div>
                    <InfoBlock :title="$t('admin.user_info.plans')" icon="fa-calendar" :block="training.plans" :columns="[
                        { key: 'plan_name', label: $t('common.name'), value: i => ((i.emoji || '') + ' ' + (i.plan_name || '—')).trim() },
                        { key: 'is_active', label: $t('admin.user_info.c_active'), fmt: 'bool' },
                        { key: 'start_date', label: $t('admin.user_info.c_starts'), fmt: 'date' },
                        { key: 'activated_at', label: $t('admin.user_info.c_activated'), fmt: 'date' },
                    ]"/>
                    <InfoBlock :title="$t('admin.user_info.history')" icon="fa-history" :block="training.history" :columns="[
                        { key: 'workout_name', label: $t('common.name') },
                        { key: 'workout_type', label: $t('admin.user_info.c_type') },
                        { key: 'reps', label: $t('admin.user_info.c_reps_sets'), value: i => (i.reps_completed ?? '—') + ' / ' + (i.sets_completed ?? '—') },
                        { key: 'status', label: $t('admin.user_info.c_status') },
                        { key: 'date', label: $t('admin.user_info.c_date'), fmt: 'date' },
                    ]"/>
                    <InfoBlock :title="$t('admin.user_info.workouts')" icon="fa-heartbeat" :block="training.workouts" :columns="[
                        { key: 'name', label: $t('common.name') },
                        { key: 'type', label: $t('admin.user_info.c_type') },
                        { key: 'reps', label: $t('admin.user_info.c_reps_sets'), value: i => (i.reps ?? '—') + ' / ' + (i.sets ?? '—') },
                        { key: 'updated_at', label: $t('admin.user_info.c_date'), fmt: 'date' },
                    ]"/>
                    <InfoBlock :title="$t('admin.user_info.boards')" icon="fa-th" :block="training.boards" :columns="[
                        { key: 'name', label: $t('common.name') },
                        { key: 'degree', label: $t('admin.user_info.c_degree') },
                        { key: 'hold_set', label: $t('admin.user_info.c_hold_set') },
                        { key: 'created_at', label: $t('admin.user_info.c_created'), fmt: 'date' },
                    ]"/>
                </div>

                <!-- ───── Moderation & system ───── -->
                <div v-else-if="active_tab === 'system'">
                    <InfoBlock :title="$t('admin.user_info.complaints_received')" icon="fa-exclamation-triangle" :block="system.complaints_received" @open_user="open_user" :columns="complaint_columns"/>
                    <InfoBlock :title="$t('admin.user_info.complaints_filed')" icon="fa-flag-o" :block="system.complaints_filed" @open_user="open_user" :columns="complaint_columns"/>
                    <InfoBlock :title="$t('admin.user_info.tasks_for')" icon="fa-tasks" :block="system.tasks_for" :columns="task_columns"/>
                    <InfoBlock :title="$t('admin.user_info.tasks_from')" icon="fa-tasks" :block="system.tasks_from" :columns="task_columns"/>
                    <InfoBlock :title="$t('admin.user_info.requests')" icon="fa-envelope-o" :block="system.requests" :columns="[
                        { key: 'action', label: $t('admin.user_info.c_type') },
                        { key: 'created_at', label: $t('admin.user_info.c_date'), fmt: 'date' },
                    ]"/>
                    <InfoBlock :title="$t('admin.user_info.content_notifications')" icon="fa-bell-o" :block="system.content_notifications" :columns="[
                        { key: 'notification_key', label: $t('admin.user_info.c_type') },
                        { key: 'notifiable_type', label: $t('admin.user_info.c_item'), value: i => short_type(i.notifiable_type) + ' #' + i.notifiable_id },
                        { key: 'sent_at', label: $t('admin.user_info.c_date'), fmt: 'date' },
                    ]"/>
                </div>
            </div>
        </div>

        <template #footer>
            <div class="modal-footer">
                <a
                    v-if="user"
                    :href="public_profile_url"
                    target="_blank"
                    rel="noopener"
                    class="btn btn-outline-secondary me-auto"
                >
                    <i class="fa fa-external-link"></i> {{ $t('admin.user_info.public_profile_btn') }}
                </a>
                <button type="button" class="btn btn-secondary" @click="close()">{{ $t('common.close') }}</button>
                <button v-if="user" type="button" class="btn btn-primary" @click="$emit('manage', user.id)">
                    <i class="fa fa-pencil"></i> {{ $t('admin.user_info.edit_btn') }}
                </button>
            </div>
        </template>
    </StackModal>
</template>

<script>
import InfoBlock from './InfoBlock.vue'

// Read-only "everything about this user" modal opened by clicking a name in
// the admin Users list. Editing stays in UserManageModal — the Edit button
// emits `manage` and the page stacks that modal on top of this one.
// Data: GET set_user/get_user_overview/{id} (AdminUserOverviewService).
export default {
    emits: ['manage', 'verified'],
    components: { InfoBlock },
    data() {
        return {
            is_open: false,
            user_id: null,
            data: null,
            loading: false,
            failed: false,
            active_tab: 'overview',
            verifying: false,
            resending: false,
        }
    },
    computed: {
        user() { return this.data?.user || null },
        account() { return this.data?.account || {} },
        activity() { return this.data?.activity || {} },
        shop() { return this.data?.shop || {} },
        favorites() { return this.data?.favorites || {} },
        social() { return this.data?.social || {} },
        training() { return this.data?.training || {} },
        system() { return this.data?.system || {} },
        initials() {
            return ((this.user?.name || '').charAt(0) + (this.user?.surname || '').charAt(0)).toUpperCase() || '?'
        },
        public_profile_url() {
            return (process.env.MIX_APP_SSH || 'https://') + (process.env.MIX_GUIDBOOK_URL || '') + '/climber/' + this.user?.id
        },
        tabs() {
            const sum = (obj) => Object.values(obj || {}).reduce((n, b) => n + (b && typeof b === 'object' && !Array.isArray(b) ? (b.count || 0) : 0), 0)
            return [
                { key: 'overview', icon: 'fa-id-card-o',  title: this.$t('admin.user_info.tab_overview') },
                { key: 'activity', icon: 'fa-line-chart', title: this.$t('admin.user_info.tab_activity'), count: sum(this.activity) },
                { key: 'shop',     icon: 'fa-shopping-cart', title: this.$t('admin.user_info.tab_shop'), count: sum(this.shop) },
                { key: 'social',   icon: 'fa-users',      title: this.$t('admin.user_info.tab_social'),
                  count: sum(this.social) + sum(this.favorites) + (this.data?.donations?.count || 0) },
                { key: 'training', icon: 'fa-heartbeat',  title: this.$t('admin.user_info.tab_training'), count: sum(this.training) },
                { key: 'system',   icon: 'fa-cogs',       title: this.$t('admin.user_info.tab_system'), count: sum(this.system) },
            ]
        },
        counters() {
            const a = this.activity, s = this.shop, so = this.social
            const c = (b) => b?.count ?? 0
            return [
                { key: 'reviews',   tab: 'activity', value: c(a.route_reviews) + c(a.mtp_reviews), label: this.$t('admin.user_info.cnt_reviews') },
                { key: 'ascents',   tab: 'activity', value: c(a.route_ascents) + c(a.mtp_ascents) + c(a.summit_ascents), label: this.$t('admin.user_info.cnt_ascents') },
                { key: 'comments',  tab: 'activity', value: c(a.comments), label: this.$t('admin.user_info.comments') },
                { key: 'orders',    tab: 'shop',     value: c(s.orders), label: this.$t('admin.user_info.orders') },
                { key: 'followers', tab: 'social',   value: c(so.followers), label: this.$t('admin.user_info.followers') },
                { key: 'following', tab: 'social',   value: c(so.following), label: this.$t('admin.user_info.following') },
                { key: 'donations', tab: 'social',   value: c(this.data?.donations), label: this.$t('admin.user_info.donations') },
                { key: 'workouts',  tab: 'training', value: c(this.training.history), label: this.$t('admin.user_info.cnt_trainings') },
            ]
        },
        profile_fields() {
            const u = this.user || {}
            const yes_no = (v) => v ? this.$t('admin.user_info.yes') : this.$t('admin.user_info.no')
            return [
                { key: 'name',         label: this.$t('common.name'),                  value: [u.name, u.surname].filter(Boolean).join(' ') || '—' },
                { key: 'email',        label: this.$t('common.email'),                 value: u.email || '—' },
                { key: 'phone_number', label: this.$t('common.phone_number'),          value: u.phone_number || '—' },
                { key: 'location',     label: this.$t('admin.user_info.f_location'),   value: [u.city, u.country].filter(Boolean).join(', ') || '—' },
                { key: 'lang',         label: this.$t('admin.user_info.f_lang'),       value: u.lang || '—' },
                { key: 'grade',        label: this.$t('admin.user_info.f_grade'),      value: u.grade || '—' },
                { key: 'mount_grade',  label: this.$t('admin.user_info.f_mount_grade'), value: u.mount_grade || '—' },
                { key: 'member_status', label: this.$t('admin.user_info.f_member_status'), value: u.is_team_member ? (u.member_status || this.$t('admin.user_info.yes')) : this.$t('admin.user_info.no') },
                { key: 'verified',     label: this.$t('admin.user_info.f_verified_at'), value: this.format_date(u.email_verified_at) },
                { key: 'onboarded',    label: this.$t('admin.user_info.f_onboarded'),  value: this.format_date(u.onboarded_at) },
                { key: 'animations',   label: this.$t('admin.user_info.f_animations'), value: yes_no(u.animations) },
                { key: 'created_at',   label: this.$t('admin.user_info.f_registered'), value: this.format_date(u.created_at) },
                { key: 'updated_at',   label: this.$t('admin.user_info.f_updated'),    value: this.format_date(u.updated_at) },
                { key: 'my_bio',       label: this.$t('admin.user_info.f_bio'),        value: u.my_bio || '—' },
            ]
        },
        complaint_columns() {
            return [
                { key: 'name', label: this.$t('admin.user_info.c_user'), fmt: 'person' },
                { key: 'category', label: this.$t('common.category') },
                { key: 'comment', label: this.$t('admin.user_info.c_text'), fmt: 'text' },
                { key: 'decision', label: this.$t('admin.user_info.c_decision') },
                { key: 'created_at', label: this.$t('admin.user_info.c_date'), fmt: 'date' },
            ]
        },
        task_columns() {
            return [
                { key: 'title', label: this.$t('common.title') },
                { key: 'status', label: this.$t('admin.user_info.c_status') },
                { key: 'deadline', label: this.$t('admin.user_info.c_deadline'), fmt: 'date' },
                { key: 'created_at', label: this.$t('admin.user_info.c_created'), fmt: 'date' },
            ]
        },
    },
    methods: {
        show_modal(user_id) {
            this.user_id = user_id
            this.data = null
            this.active_tab = 'overview'
            this.is_open = true
            this.load()
        },
        close() {
            this.is_open = false
        },
        verify_user() {
            if (!confirm(this.$t('admin.users.verify_confirm'))) return
            this.verifying = true
            axios.post('/set_user/verify_user/' + this.user_id)
                .then(response => {
                    if (this.data?.user) this.data.user.email_verified_at = response.data.email_verified_at
                    this.$bus.$emit('toast', { type: 'success', message: this.$t('admin.users.verified_message') })
                    this.$emit('verified', this.user_id)
                })
                .catch(() => this.$bus.$emit('toast', { type: 'danger', message: this.$t('admin.users.verify_error') }))
                .finally(() => this.verifying = false)
        },
        resend_verification() {
            this.resending = true
            axios.post('/set_user/resend_verification/' + this.user_id)
                .then(response => {
                    if (response.data.email_verified_at) {
                        if (this.data?.user) this.data.user.email_verified_at = response.data.email_verified_at
                        return
                    }
                    this.$bus.$emit('toast', { type: 'success', message: this.$t('admin.users.resend_verification_sent') })
                })
                .catch(() => {})   // 500 / 429 toast globally
                .finally(() => this.resending = false)
        },
        // Also called by the page after the Manage modal saves, so this view stays live.
        reload() {
            if (this.is_open && this.user_id) this.load()
        },
        load() {
            this.loading = true
            this.failed = false
            const id = this.user_id
            axios.get('/set_user/get_user_overview/' + id)
                .then(response => { if (id === this.user_id) this.data = response.data })
                .catch(error => {
                    console.log(error)
                    if (id === this.user_id) this.failed = true
                })
                .finally(() => { if (id === this.user_id) this.loading = false })
        },
        // Follower / complaint rows jump to that user's info.
        open_user(id) {
            if (id && id !== this.user_id) this.show_modal(id)
        },
        format_date(value) {
            if (!value) return '—'
            return String(value).replace('T', ' ').substring(0, 16)
        },
        money(v) {
            const n = Number(v)
            return Number.isFinite(n) ? n.toLocaleString(undefined, { maximumFractionDigits: 2 }) : '—'
        },
        list_or_none(arr) {
            return arr.length ? arr.join(', ') : this.$t('admin.user_info.none')
        },
        address(a) {
            return [a.city, a.strit, a.number, a.entrance && ('ent. ' + a.entrance), a.floor && ('fl. ' + a.floor), a.flat && ('apt. ' + a.flat), a.zip_code]
                .filter(Boolean).join(', ') || '—'
        },
        short_type(t) {
            return String(t || '').split('\\').pop()
        },
    },
}
</script>

<style scoped>
.user-info--reloading {
    opacity: .6;
    transition: opacity .15s;
}
.user-info__head {
    display: flex;
    gap: 1rem;
    align-items: center;
    padding-bottom: 1rem;
    margin-bottom: 1rem;
    border-bottom: 1px solid var(--bs-border-color);
}
.user-info__avatar {
    width: 72px;
    height: 72px;
    border-radius: 50%;
    object-fit: cover;
    flex-shrink: 0;
}
.user-info__avatar--empty {
    display: flex;
    align-items: center;
    justify-content: center;
    background: var(--cg-accent);
    color: #fff;
    font-weight: 600;
    font-size: 1.35rem;
}
.user-info__who {
    flex: 1;
    min-width: 0;
}
.user-info__name {
    font-size: 1.2rem;
    font-weight: 600;
}
.user-info__badges {
    display: flex;
    flex-wrap: wrap;
    gap: .35rem;
    margin-top: .4rem;
}
.user-info__stats {
    gap: 1.5rem;
    text-align: right;
}
.user-info__tabs {
    flex-wrap: nowrap;
    overflow-x: auto;
    overflow-y: hidden;
}
.user-info__tabs .nav-link {
    white-space: nowrap;
}
.user-info__body {
    padding-top: 1rem;
}
.user-info__counters {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(120px, 1fr));
    gap: .5rem;
    margin-bottom: 1rem;
}
.user-info__counter {
    display: flex;
    flex-direction: column;
    align-items: flex-start;
    padding: .6rem .75rem;
    border: 1px solid var(--bs-border-color);
    border-radius: var(--bs-border-radius);
    background: var(--bs-body-bg);
    text-align: left;
}
button.user-info__counter:hover {
    border-color: var(--cg-accent);
}
.user-info__counter-num {
    font-size: 1.25rem;
    font-weight: 700;
    line-height: 1.2;
}
.user-info__counter-label {
    font-size: .78rem;
    color: var(--bs-secondary-color);
}
.info-card {
    border: 1px solid var(--bs-border-color);
    border-radius: var(--bs-border-radius);
    padding: .75rem;
    height: 100%;
}
.info-card__title {
    font-weight: 600;
    margin-bottom: .5rem;
}
.info-dl {
    display: grid;
    grid-template-columns: minmax(110px, 35%) 1fr;
    gap: .3rem .75rem;
    margin: 0;
    font-size: .9rem;
}
.info-dl dt {
    font-weight: 500;
    color: var(--bs-secondary-color);
}
.info-dl dd {
    margin: 0;
    word-break: break-word;
}
.info-dl__pre {
    white-space: pre-line;
}
</style>
