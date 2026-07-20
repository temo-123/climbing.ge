<template>
    <div class="container admin-alerts">
        <div class="row">
            <div class="col-md-12">
                <div class="form-groupe d-flex justify-content-between align-items-center mb-2">
                    <h5 class="mb-0"><i class="fa fa-bell" aria-hidden="true"></i> {{ $t('admin.notifications.admin_panel.alerts.title') }}</h5>
                    <button @click="refresh()" class="btn btn-success btn-sm" v-if="!is_admin_panel_refresh">{{ $t('admin.notifications.admin_panel.refresh_notifications_btn', { id: admin_refresh_id }) }}</button>
                    <span class="badge badge-primare mb-1" v-if="is_admin_panel_refresh">{{ $t('admin.articles.updating_ellipsis') }}</span>
                </div>
            </div>

            <div class="col-md-12">
                <div class="alert alert-success admin-alert-empty" role="alert" v-if="!is_admin_panel_refresh && visible_cards.length === 0">
                    <i class="fa fa-check-circle" aria-hidden="true"></i> {{ $t('admin.notifications.admin_panel.alerts.all_caught_up') }}
                </div>

                <div
                    class="admin-alert-card"
                    :class="'admin-alert-card--' + card.severity"
                    v-for="card in visible_cards"
                    :key="card.key"
                >
                    <div class="admin-alert-card__row">
                        <span class="admin-alert-card__icon"><i :class="'fa ' + card.icon" aria-hidden="true"></i></span>

                        <span class="admin-alert-card__message">
                            <strong v-if="card.severity === 'danger'">{{ $t('admin.notifications.admin_panel.danger_label') }}</strong>
                            <strong v-else>{{ $t('admin.notifications.admin_panel.alerts.info_label') }}</strong>
                            {{ card.message }}
                        </span>

                        <span class="admin-alert-card__count" v-if="card.count">{{ card.count }}</span>

                        <button
                            v-if="card.expandable"
                            class="btn btn-outline-secondary btn-sm ml-2"
                            @click="toggle_expanded(card.key)"
                        >
                            {{ expanded[card.key] ? $t('admin.notifications.admin_panel.alerts.show_less') : $t('admin.notifications.admin_panel.alerts.review_btn') }}
                        </button>

                        <router-link
                            v-else-if="card.route"
                            :to="{ name: card.route }"
                            class="btn btn-outline-secondary btn-sm ml-2"
                            @click="on_alert_link_click(card)"
                        >
                            {{ $t('admin.notifications.admin_panel.alerts.view_all_btn') }}
                        </router-link>
                    </div>

                    <!-- Expanded individual complaint rows (comment / feedback complaints only) -->
                    <div class="admin-alert-card__details" v-if="card.expandable && expanded[card.key]">
                        <div class="admin-alert-detail-row" v-for="row in card.rows" :key="row.id">
                            <span>#{{ row.id }}</span>
                            <button class="btn btn-primary btn-sm" @click="show_desidion_model(card.decision_type, row.decision_id, row.id)">
                                {{ $t('admin.notifications.admin_panel.check_comment_btn') }}
                            </button>
                        </div>
                    </div>
                </div>

                <button
                    v-if="hidden_count > 0"
                    class="btn btn-link p-0 mt-1"
                    @click="show_all = true"
                >
                    {{ $t('admin.notifications.admin_panel.alerts.show_more', { count: hidden_count }) }}
                </button>
                <button
                    v-else-if="show_all && cards.length > collapse_after"
                    class="btn btn-link p-0 mt-1"
                    @click="show_all = false"
                >
                    {{ $t('admin.notifications.admin_panel.alerts.show_less') }}
                </button>

                <span v-if="$can('edit', 'site_data')">
                    <div class="admin-alert-card admin-alert-card--danger" v-if="site_info_incomplete">
                        <div class="admin-alert-card__row">
                            <span class="admin-alert-card__icon"><i class="fa fa-info-circle" aria-hidden="true"></i></span>
                            <span class="admin-alert-card__message">
                                <strong>{{ $t('admin.notifications.admin_panel.danger_label') }}</strong>
                                {{ $t('admin.notifications.admin_panel.site_info_incomplete_prefix') }}
                                <router-link :to="{name: 'siteInfo'}" exact>
                                    {{ $t('admin.notifications.admin_panel.about_us_link_label') }}
                                </router-link>
                                {{ $t('admin.notifications.admin_panel.site_info_incomplete_suffix') }}
                            </span>
                        </div>
                    </div>
                </span>
            </div>
        </div>

        <stack-modal
                :show="is_desidion_model"
                :title="$t('admin.notifications.admin_panel.show_comment_title')"
                @close="clouse_model()"
                :saveButton="{ visible: true, title: $t('common.save'), btnClass: { 'btn btn-primary': true } }"
                :cancelButton="{ visible: false, title: $t('common.close'), btnClass: { 'btn btn-danger': true } }"
            >
            <div>
                <div class="row justify-content-center" v-if="decision_loader">
                    <div class="col-md-4">
                        <img :src="'/images/site_img/loading.gif'" alt="loading">
                    </div>
                </div>

                <span v-if="!decision_loader">
                    <h1>{{ $t('admin.notifications.admin_panel.show_comment_heading') }}</h1>

                    {{ $t('admin.notifications.admin_panel.commentator_label') }} {{ actyve_comment.name }} {{ actyve_comment.suenmae }}
                    {{ $t('admin.notifications.admin_panel.email_label') }} {{ actyve_comment.email }}
                    {{ $t('admin.notifications.admin_panel.date_to_comment_label') }} {{ actyve_comment.created_at }}

                    {{ actyve_comment.text }}

                    <h1>{{ $t('admin.notifications.admin_panel.comment_complaint_heading') }}</h1>
                    {{ $t('admin.notifications.admin_panel.email_label') }} {{ actyve_comment.email }}
                    {{ $t('admin.notifications.admin_panel.complaint_status_label') }} {{ actyve_comment.complaint }}

                    <h1>{{ $t('admin.notifications.admin_panel.make_decision_heading') }}</h1>

                    <form v-on:submit.prevent="make_decision" id="make_decision" class="form">
                        <select class="form-control" v-model="comment_decision" name="comment delete cause" >
                            <option value="select_decision" disabled>{{ $t('admin.notifications.admin_panel.select_decision_placeholder') }}</option>
                            <option value="approve_request">{{ $t('admin.notifications.admin_panel.approve_request_option') }}</option>
                            <option value="reject_request">{{ $t('admin.notifications.admin_panel.reject_request_option') }}</option>
                        </select>
                        <div class="alert alert-danger" role="alert" v-if="is_comment_decision_selected">{{ $t('admin.notifications.admin_panel.please_select_decision') }}</div>
                    </form>
                </span>
            </div>
            <div slot="modal-footer">
                <div class="modal-footer" >
                    <button
                        type="submit"
                        :class="{'btn btn-primary': true}"
                        form="make_decision"
                    >
                    {{ $t('admin.notifications.admin_panel.make_decision_btn') }}
                    </button>
                </div>
            </div>
        </stack-modal>
    </div>
</template>

<script>
    export default {
        data(){
            return{
                complaint_loader: false,

                comment_complaints: [],
                feedback_complaints: [],
                tracked_alerts: [],

                actyve_comment: [],
                comment_decision: 'select_decision',
                is_comment_decision_selected: false,

                is_desidion_model: false,
                active_decision_type: 'comment', // 'comment' | 'feedback'
                action_comment_id: 0,
                complaint_id: 0,

                is_admin_panel_refresh: false,
                admin_refresh_id: 0,
                decision_loader: false,

                expanded: {},
                show_all: false,
                collapse_after: 4,
            }
        },
        computed: {
            site_info_incomplete() {
                return this.$siteData && this.$siteData.data && this.$siteData.data.data && (
                    !this.$siteData.data.data['text'] ||
                    !this.$siteData.data.data['text_ru'] ||
                    !this.$siteData.data.data['text_ka'] ||
                    !this.$siteData.data.data['short_description_ru'] ||
                    !this.$siteData.data.data['short_description_ka'] ||
                    !this.$siteData.data.data['short_description']
                );
            },
            cards() {
                const cards = [];

                if ((this.$can('show', 'comments') || this.$can('del_comment', 'comments')) && this.comment_complaints.length) {
                    cards.push({
                        key: 'comment_complaints',
                        severity: 'danger',
                        icon: 'fa-book',
                        count: this.comment_complaints.length,
                        message: this.$t('admin.notifications.admin_panel.alerts.comment_complaints', { count: this.comment_complaints.length }),
                        expandable: true,
                        decision_type: 'comment',
                        rows: this.comment_complaints.map(c => ({ id: c.id, decision_id: c.comment_id })),
                    });
                }

                if ((this.$can('show', 'comments') || this.$can('del_comment', 'comments')) && this.feedback_complaints.length) {
                    cards.push({
                        key: 'feedback_complaints',
                        severity: 'danger',
                        icon: 'fa-shopping-bag',
                        count: this.feedback_complaints.length,
                        message: this.$t('admin.notifications.admin_panel.alerts.feedback_complaints', { count: this.feedback_complaints.length }),
                        expandable: true,
                        decision_type: 'feedback',
                        rows: this.feedback_complaints.map(c => ({ id: c.id, decision_id: c.feedback_id })),
                    });
                }

                for (const alert of this.tracked_alerts) {
                    cards.push({
                        key: alert.key,
                        severity: alert.severity,
                        icon: alert.icon,
                        count: alert.count,
                        message: this.$t('admin.notifications.admin_panel.alerts.' + alert.key, { count: alert.count }),
                        expandable: false,
                        route: alert.route,
                        dismissible: alert.dismissible,
                    });
                }

                // Danger alerts first so the most urgent items are always visible.
                return cards.sort((a, b) => (a.severity === b.severity ? 0 : (a.severity === 'danger' ? -1 : 1)));
            },
            visible_cards() {
                return this.show_all ? this.cards : this.cards.slice(0, this.collapse_after);
            },
            hidden_count() {
                return this.show_all ? 0 : Math.max(0, this.cards.length - this.collapse_after);
            },
        },
        mounted(){
            this.refresh()
        },
        methods: {
            toggle_expanded(key) {
                this.expanded = { ...this.expanded, [key]: !this.expanded[key] };
            },

            on_alert_link_click(card) {
                if (card.dismissible) {
                    axios.post('set_admin_alerts/mark_viewed', { key: card.key }).catch(() => {});
                }
            },

            show_desidion_model(type, comment_id, complaint_id){
                this.active_decision_type = type
                this.is_desidion_model = true

                this.get_action_comment(type, comment_id)

                this.action_comment_id = comment_id
                this.complaint_id = complaint_id
            },

            clouse_model(){
                this.comment_decision = 'select_decision',
                this.is_desidion_model = false
            },

            get_article_comments_complaints(){
                axios
                .get('set_article/set_guide_comment/get_comments_complaints')
                .then(response => {
                    this.comment_complaints = response.data
                })
            },

            get_product_feedback_complaints(){
                axios
                .get('set_product/set_product_feedback/get_feedbacks_complaints')
                .then(response => {
                    this.feedback_complaints = response.data
                })
            },

            get_admin_alerts_summary(){
                axios
                .get('set_admin_alerts/summary')
                .then(response => {
                    this.tracked_alerts = response.data
                })
            },

            refresh(){
                this.admin_refresh_id++

                this.get_article_comments_complaints()
                this.get_product_feedback_complaints()
                this.get_admin_alerts_summary()
            },

            make_decision(){
                if(this.comment_decision == 'select_decision'){
                    this.is_comment_decision_selected = true
                }
                else{
                    this.decision_loader = true
                    const endpoint = this.active_decision_type === 'feedback'
                        ? 'set_product/set_product_feedback/make_decision'
                        : 'set_article/set_guide_comment/make_decision'
                    axios
                    .post(endpoint,{
                        decision: this.comment_decision,
                        comment_id: this.action_comment_id,
                        complaint_id: this.complaint_id,
                    })
                    .then(response => {
                        this.is_desidion_model = false
                        this.refresh()
                    })
                    .finally(() => this.decision_loader = false);
                }
            },
            get_action_comment(type, id){
                this.actyve_comment = []
                const endpoint = type === 'feedback'
                    ? 'set_product/set_product_feedback/get_actyve_feedback/' + id
                    : 'set_article/set_guide_comment/get_actyve_comment/' + id
                axios
                .get(endpoint)
                .then(response => {
                    this.actyve_comment = response.data
                })
                .catch(
                    error => console.log(error)
                );
            },
        }
    }
</script>

<style>
.admin-alerts .admin-alert-card {
    border-radius: 6px;
    border-left: 4px solid #ccc;
    background: #f8f9fa;
    padding: 10px 14px;
    margin-bottom: 8px;
}
.admin-alerts .admin-alert-card--danger {
    border-left-color: #dc3545;
    background: #fdf2f3;
}
.admin-alerts .admin-alert-card--info {
    border-left-color: #17a2b8;
    background: #f1fafc;
}
.admin-alerts .admin-alert-card__row {
    display: flex;
    align-items: center;
    gap: 8px;
}
.admin-alerts .admin-alert-card__icon {
    font-size: 1.1rem;
    opacity: .75;
}
.admin-alerts .admin-alert-card__message {
    flex: 1 1 auto;
}
.admin-alerts .admin-alert-card__count {
    font-weight: 700;
    min-width: 1.5em;
    text-align: center;
}
.admin-alerts .admin-alert-card__details {
    margin-top: 8px;
    padding-top: 8px;
    border-top: 1px solid rgba(0,0,0,.08);
}
.admin-alerts .admin-alert-detail-row {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 4px 0;
}
.admin-alerts .admin-alert-empty {
    text-align: center;
}
</style>
