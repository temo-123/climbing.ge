<template>
    <div class="col-md-12 notification-settings">
        <p class="text-center text-muted mb-4">{{ $t('user.notification_settings.manage_notifications_hint') }}</p>

        <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 notification-settings__toolbar">
            <div class="btn-group mb-2">
                <button type="button" class="btn btn-outline-primary" @click="activate_all_notifications">
                    {{ $t('user.notification_settings.enable_all_btn') }}
                </button>
                <button type="button" class="btn btn-outline-danger" @click="cancel_all_notifications">
                    {{ $t('user.notification_settings.disable_all_btn') }}
                </button>
            </div>

            <div class="mb-2">
                <button
                    type="button"
                    class="btn btn-success"
                    @click="get_user_notification_data"
                    :disabled="notification_is_refresh || notification_is_saving"
                >
                    {{ notification_is_refresh ? $t('admin.articles.updating_ellipsis') : $t('user.notification_settings.refresh_btn', { id: notification_reset_id }) }}
                </button>
                <button
                    type="button"
                    class="btn btn-primary ml-2"
                    @click="update_user_notification_data"
                    :disabled="notification_is_saving || notification_is_refresh"
                >
                    {{ notification_is_saving ? $t('admin.articles.updating_ellipsis') : $t('user.notification_settings.save_preferences_btn') }}
                </button>
            </div>
        </div>

        <div class="card notification-settings__group mb-3" v-for="group in groups" :key="group.key">
            <div class="card-header">
                <strong>{{ $t(group.titleKey) }}</strong>
                <div class="text-muted small">{{ $t(group.descriptionKey) }}</div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-sm-6 col-lg-4 mb-2" v-for="item in group.items" :key="item.key">
                        <div class="form-check form-switch notification-settings__item">
                            <input
                                type="checkbox"
                                class="form-check-input"
                                role="switch"
                                :id="'notif_' + item.key"
                                v-model="data[item.key]"
                            >
                            <label class="form-check-label" :for="'notif_' + item.key">
                                {{ $t(item.labelKey) }}
                            </label>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        data(){
            return {
                data: {
                    add_new_gym: false,
                    add_new_ice_spot: false,
                    add_new_outdoor_spot: false,
                    add_new_product: false,
                    add_new_sector: false,
                    add_new_service: false,
                    add_new_techtip: false,
                    add_new_tour: false,
                    add_new_mount: false,
                    add_new_summit: false,
                    add_new_blog_post: false,
                    add_new_film: false,
                    news: false,
                    favorite_film: false,
                    favorite_outdoor: false,
                    favorite_product: false,
                    interested_event: false,
                    sector_updated: false,
                    news_updated: false,
                    outdoor_spot_updated: false,
                    ice_spot_updated: false,
                    techtip_updated: false,
                    gym_updated: false,
                    product_updated: false,
                    service_updated: false,
                    tour_updated: false,
                    mount_updated: false,
                    summit_updated: false,
                    blog_post_updated: false,
                    film_updated: false,
                },

                groups: [
                    {
                        key: 'favorites',
                        titleKey: 'user.notification_settings.favorites_section_label',
                        descriptionKey: 'user.notification_settings.favorites_section_desc',
                        items: [
                            { key: 'favorite_outdoor', labelKey: 'user.notification_settings.favorite_outdoor_activity' },
                            { key: 'favorite_product', labelKey: 'user.notification_settings.favorite_product' },
                            { key: 'favorite_film', labelKey: 'user.notification_settings.favorite_film' },
                            { key: 'interested_event', labelKey: 'user.notification_settings.interested_event' },
                        ],
                    },
                    {
                        key: 'new_content',
                        titleKey: 'user.notification_settings.new_content_section_label',
                        descriptionKey: 'user.notification_settings.new_content_section_desc',
                        items: [
                            { key: 'news', labelKey: 'user.notification_settings.news_updates' },
                            { key: 'add_new_sector', labelKey: 'user.notification_settings.new_sector_added' },
                            { key: 'add_new_outdoor_spot', labelKey: 'user.notification_settings.new_outdoor_climbing_spot' },
                            { key: 'add_new_ice_spot', labelKey: 'user.notification_settings.new_ice_climbing_spot' },
                            { key: 'add_new_techtip', labelKey: 'user.notification_settings.new_tech_tip' },
                            { key: 'add_new_gym', labelKey: 'user.notification_settings.new_gym_added' },
                            { key: 'add_new_product', labelKey: 'user.notification_settings.new_product_added' },
                            { key: 'add_new_service', labelKey: 'user.notification_settings.new_service_added' },
                            { key: 'add_new_tour', labelKey: 'user.notification_settings.new_tour_added' },
                            { key: 'add_new_mount', labelKey: 'user.notification_settings.new_mount_added' },
                            { key: 'add_new_summit', labelKey: 'user.notification_settings.new_summit_added' },
                            { key: 'add_new_blog_post', labelKey: 'user.notification_settings.new_blog_post_added' },
                            { key: 'add_new_film', labelKey: 'user.notification_settings.new_film_added' },
                        ],
                    },
                    {
                        key: 'content_updates',
                        titleKey: 'user.notification_settings.content_updates_section_label',
                        descriptionKey: 'user.notification_settings.content_updates_section_desc',
                        items: [
                            { key: 'news_updated', labelKey: 'user.notification_settings.news_content_updated' },
                            { key: 'sector_updated', labelKey: 'user.notification_settings.sector_updated' },
                            { key: 'outdoor_spot_updated', labelKey: 'user.notification_settings.outdoor_spot_updated' },
                            { key: 'ice_spot_updated', labelKey: 'user.notification_settings.ice_spot_updated' },
                            { key: 'techtip_updated', labelKey: 'user.notification_settings.techtip_updated' },
                            { key: 'gym_updated', labelKey: 'user.notification_settings.gym_updated' },
                            { key: 'product_updated', labelKey: 'user.notification_settings.product_updated' },
                            { key: 'service_updated', labelKey: 'user.notification_settings.service_updated' },
                            { key: 'tour_updated', labelKey: 'user.notification_settings.tour_updated' },
                            { key: 'mount_updated', labelKey: 'user.notification_settings.mount_updated' },
                            { key: 'summit_updated', labelKey: 'user.notification_settings.summit_updated' },
                            { key: 'blog_post_updated', labelKey: 'user.notification_settings.blog_post_updated' },
                            { key: 'film_updated', labelKey: 'user.notification_settings.film_updated' },
                        ],
                    },
                ],

                notification_is_refresh: false,
                notification_is_saving: false,
                notification_reset_id: 0,
            }
        },
        mounted() {
            this.get_user_notification_data()
        },

        methods: {
            get_user_notification_data: function(){
                this.notification_is_refresh = true
                axios
                .get("get_options/get_user_notification_data")
                .then(response => {
                    this.data = response.data
                    this.notification_reset_id++
                })
                .catch(error => {
                    console.log('Error fetching notification data:', error)
                    this.$bus.$emit('toast', {
                        type: 'danger',
                        title: this.$t('user.notification_settings.toast_title'),
                        message: this.$t('user.notification_settings.fetch_error_message'),
                    })
                })
                .finally(() => {
                    this.notification_is_refresh = false
                })
            },

            update_user_notification_data: function(){
                this.notification_is_saving = true
                axios
                .post("get_options/update_user_notification_data", {
                    data: this.data,
                    _method: 'POST'
                })
                .then(response => {
                    this.$bus.$emit('toast', {
                        type: 'success',
                        title: this.$t('user.notification_settings.toast_title'),
                        message: this.$t('user.notification_settings.saved_success_message'),
                    })
                    return this.get_user_notification_data()
                })
                .catch(error => {
                    console.log('Error updating notification data:', error)
                    this.$bus.$emit('toast', {
                        type: 'danger',
                        title: this.$t('user.notification_settings.toast_title'),
                        message: this.$t('user.notification_settings.saved_error_message'),
                    })
                })
                .finally(() => {
                    this.notification_is_saving = false
                })
            },

            activate_all_notifications(){
                if (window.confirm(this.$t('user.notification_settings.confirm_enable_all'))) {
                    for (const key in this.data) {
                        this.data[key] = true
                    }
                    this.update_user_notification_data()
                }
            },

            cancel_all_notifications(){
                if (window.confirm(this.$t('user.notification_settings.confirm_disable_all'))) {
                    for (const key in this.data) {
                        this.data[key] = false
                    }
                    this.update_user_notification_data()
                }
            }
        }
    }
</script>

<style>
    .notification-settings__group .card-header {
        background-color: rgba(0, 0, 0, 0.03);
    }

    .notification-settings__item {
        padding-left: 2.5em;
    }
</style>
