<template>
    <div class="col-md-12">
        <div class="row edit_buttom">
            <div class="col-md-12">
                <p class="text-center">{{ $t('user.notification_settings.manage_notifications_hint') }}</p>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="form-groupe">
                    <button class="btn btn-primary" @click="activate_all_notifications">{{ $t('user.notification_settings.enable_all_btn') }}</button>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-groupe">
                    <button class="btn btn-danger float-right" @click="cancel_all_notifications">{{ $t('user.notification_settings.disable_all_btn') }}</button>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="form-groupe">
                    <button class="btn btn-primary" @click="update_user_notification_data" >{{ $t('user.notification_settings.save_preferences_btn') }}</button>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-groupe">
                    <button @click="get_user_notification_data" class="btn btn-success float-right" v-if="!notification_is_refresh">{{ $t('user.notification_settings.refresh_btn', { id: notification_reset_id }) }}</button>
                    <span class="badge badge-primare mb-1 float-right" v-if="notification_is_refresh">{{ $t('admin.articles.updating_ellipsis') }}</span>
                </div>
            </div>
        </div>

        <!-- <div class="row"> -->
            <table class="table table-hover" id="dev-table">
                <thead>
                    <tr>
                        <th>{{ $t('user.notification_settings.col_notification_type') }}</th>
                        <th></th>
                        <th>{{ $t('user.notification_settings.col_enable') }}</th>
                    </tr>
                </thead>

                <tbody >
                    <tr>
                        <td>{{ $t('user.notification_settings.favorite_outdoor_activity') }}</td>
                        <td></td>
                        <td>
                            <input type="checkbox" v-model="data.favorite_outdoor">
                        </td>
                    </tr>
                    <tr>
                        <td>{{ $t('user.notification_settings.favorite_product') }}</td>
                        <td></td>
                        <td>
                            <input type="checkbox" v-model="data.favorite_product">
                        </td>
                    </tr>
                    <tr>
                        <td>{{ $t('user.notification_settings.favorite_film') }}</td>
                        <td></td>
                        <td>
                            <input type="checkbox" v-model="data.favorite_film">
                        </td>
                    </tr>
                    <tr>
                        <td>{{ $t('user.notification_settings.interested_event') }}</td>
                        <td></td>
                        <td>
                            <input type="checkbox" v-model="data.interested_event">
                        </td>
                    </tr>
                    <tr>
                        <td>--</td>
                        <td>--</td>
                        <td>--</td>
                    </tr>
                    <tr>
                        <td>{{ $t('user.notification_settings.new_sector_added') }}</td>
                        <td></td>
                        <td>
                            <input type="checkbox" v-model="data.add_new_sector">
                        </td>
                    </tr>
                    <tr>
                        <td>--</td>
                        <td>--</td>
                        <td>--</td>
                    </tr>
                    <tr>
                        <td>{{ $t('user.notification_settings.news_updates') }}</td>
                        <td></td>
                        <td>
                            <input type="checkbox" v-model="data.news">
                        </td>
                    </tr>
                    <tr>
                        <td>{{ $t('user.notification_settings.new_outdoor_climbing_spot') }}</td>
                        <td></td>
                        <td>
                            <input type="checkbox" v-model="data.add_new_outdoor_spot">
                        </td>
                    </tr>
                    <tr>
                        <td>{{ $t('user.notification_settings.new_ice_climbing_spot') }}</td>
                        <td></td>
                        <td>
                            <input type="checkbox" v-model="data.add_new_ice_spot">
                        </td>
                    </tr>
                    <tr>
                        <td>{{ $t('user.notification_settings.new_tech_tip') }}</td>
                        <td></td>
                        <td>
                            <input type="checkbox" v-model="data.add_new_techtip">
                        </td>
                    </tr>
                    <tr>
                        <td>{{ $t('user.notification_settings.new_gym_added') }}</td>
                        <td></td>
                        <td>
                            <input type="checkbox" v-model="data.add_new_gym">
                        </td>
                    </tr>
                    <tr>
                        <td>--</td>
                        <td>--</td>
                        <td>--</td>
                    </tr>
                    <tr>
                        <td>{{ $t('user.notification_settings.new_product_added') }}</td>
                        <td></td>
                        <td>
                            <input type="checkbox" v-model="data.add_new_product">
                        </td>
                    </tr>
                    <tr>
                        <td>{{ $t('user.notification_settings.new_service_added') }}</td>
                        <td></td>
                        <td>
                            <input type="checkbox" v-model="data.add_new_service">
                        </td>
                    </tr>
                    <tr>
                        <td>{{ $t('user.notification_settings.new_tour_added') }}</td>
                        <td></td>
                        <td>
                            <input type="checkbox" v-model="data.add_new_tour">
                        </td>
                    </tr>
                    <tr>
                        <td>--</td>
                        <td>--</td>
                        <td>--</td>
                    </tr>
                    <tr>
                        <td>{{ $t('user.notification_settings.new_mount_added') }}</td>
                        <td></td>
                        <td>
                            <input type="checkbox" v-model="data.add_new_mount">
                        </td>
                    </tr>
                    <tr>
                        <td>{{ $t('user.notification_settings.new_summit_added') }}</td>
                        <td></td>
                        <td>
                            <input type="checkbox" v-model="data.add_new_summit">
                        </td>
                    </tr>
                    <tr>
                        <td>--</td>
                        <td>--</td>
                        <td>--</td>
                    </tr>
                    <tr>
                        <td>{{ $t('user.notification_settings.new_blog_post_added') }}</td>
                        <td></td>
                        <td>
                            <input type="checkbox" v-model="data.add_new_blog_post">
                        </td>
                    </tr>
                    <tr>
                        <td>{{ $t('user.notification_settings.new_film_added') }}</td>
                        <td></td>
                        <td>
                            <input type="checkbox" v-model="data.add_new_film">
                        </td>
                    </tr>

                    <tr>
                        <td colspan="3"><strong>{{ $t('user.notification_settings.content_updates_section_label') }}</strong></td>
                    </tr>
                    <tr>
                        <td>{{ $t('user.notification_settings.sector_updated') }}</td>
                        <td></td>
                        <td>
                            <input type="checkbox" v-model="data.sector_updated">
                        </td>
                    </tr>
                    <tr>
                        <td>--</td>
                        <td>--</td>
                        <td>--</td>
                    </tr>
                    <tr>
                        <td>{{ $t('user.notification_settings.news_content_updated') }}</td>
                        <td></td>
                        <td>
                            <input type="checkbox" v-model="data.news_updated">
                        </td>
                    </tr>
                    <tr>
                        <td>{{ $t('user.notification_settings.outdoor_spot_updated') }}</td>
                        <td></td>
                        <td>
                            <input type="checkbox" v-model="data.outdoor_spot_updated">
                        </td>
                    </tr>
                    <tr>
                        <td>{{ $t('user.notification_settings.ice_spot_updated') }}</td>
                        <td></td>
                        <td>
                            <input type="checkbox" v-model="data.ice_spot_updated">
                        </td>
                    </tr>
                    <tr>
                        <td>{{ $t('user.notification_settings.techtip_updated') }}</td>
                        <td></td>
                        <td>
                            <input type="checkbox" v-model="data.techtip_updated">
                        </td>
                    </tr>
                    <tr>
                        <td>{{ $t('user.notification_settings.gym_updated') }}</td>
                        <td></td>
                        <td>
                            <input type="checkbox" v-model="data.gym_updated">
                        </td>
                    </tr>
                    <tr>
                        <td>--</td>
                        <td>--</td>
                        <td>--</td>
                    </tr>
                    <tr>
                        <td>{{ $t('user.notification_settings.product_updated') }}</td>
                        <td></td>
                        <td>
                            <input type="checkbox" v-model="data.product_updated">
                        </td>
                    </tr>
                    <tr>
                        <td>{{ $t('user.notification_settings.service_updated') }}</td>
                        <td></td>
                        <td>
                            <input type="checkbox" v-model="data.service_updated">
                        </td>
                    </tr>
                    <tr>
                        <td>{{ $t('user.notification_settings.tour_updated') }}</td>
                        <td></td>
                        <td>
                            <input type="checkbox" v-model="data.tour_updated">
                        </td>
                    </tr>
                    <tr>
                        <td>--</td>
                        <td>--</td>
                        <td>--</td>
                    </tr>
                    <tr>
                        <td>{{ $t('user.notification_settings.mount_updated') }}</td>
                        <td></td>
                        <td>
                            <input type="checkbox" v-model="data.mount_updated">
                        </td>
                    </tr>
                    <tr>
                        <td>{{ $t('user.notification_settings.summit_updated') }}</td>
                        <td></td>
                        <td>
                            <input type="checkbox" v-model="data.summit_updated">
                        </td>
                    </tr>
                    <tr>
                        <td>--</td>
                        <td>--</td>
                        <td>--</td>
                    </tr>
                    <tr>
                        <td>{{ $t('user.notification_settings.blog_post_updated') }}</td>
                        <td></td>
                        <td>
                            <input type="checkbox" v-model="data.blog_post_updated">
                        </td>
                    </tr>
                    <tr>
                        <td>{{ $t('user.notification_settings.film_updated') }}</td>
                        <td></td>
                        <td>
                            <input type="checkbox" v-model="data.film_updated">
                        </td>
                    </tr>
                </tbody>
            </table>
        <!-- </div> -->
    </div>
</template>

<script>
    import { SlickList, SlickItem } from 'vue-slicksort'; //https://github.com/Jexordexan/vue-slicksort


    export default {
        components: {
            SlickItem,
            SlickList,
        },
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

                // action_id: 0,

                notification_is_refresh: false,
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
                    // this.action_id = response.data.id
                    // this.data = {
                    //     add_new_gym: response.data.add_new_gym,
                    //     add_new_ice_spot: response.data.add_new_ice_spot,
                    //     add_new_outdoor_spot: response.data.add_new_outdoor_spot,
                    //     add_new_product: response.data.add_new_product,
                    //     add_new_sector: response.data.add_new_sector,
                    //     add_new_service: response.data.add_new_service,
                    //     add_new_techtip: response.data.add_new_techtip,
                    //     favorite_film: response.data.favorite_film,
                    //     favorite_outdoor: response.data.favorite_outdoor,
                    //     favorite_product: response.data.favorite_product,
                    //     interested_event: response.data.interested_event,
                    // },

                    this.data = response.data

                    this.notification_is_refresh = false
                    this.notification_reset_id++
                })
                .catch(
                    error => console.log('Error fetching notification data:', error)
                );
            },

            update_user_notification_data: function(){
                this.user_is_refresh = true
                axios
                .post("get_options/update_user_notification_data", {
                    data: this.data,

                    _method: 'POST'
                })
                .then(response => {
                    this.get_user_notification_data()
                })
                .catch(
                    error => console.log('Error updating notification data:', error)
                );
            },

            activate_all_notifications(){
                if (window.confirm(this.$t('user.notification_settings.confirm_enable_all'))) {
                    this.data = {
                        add_new_gym: true,
                        news: true,
                        add_new_ice_spot: true,
                        add_new_outdoor_spot: true,
                        add_new_product: true,
                        add_new_sector: true,
                        add_new_service: true,
                        add_new_techtip: true,
                        add_new_tour: true,
                        add_new_mount: true,
                        add_new_summit: true,
                        add_new_blog_post: true,
                        add_new_film: true,
                        favorite_film: true,
                        favorite_outdoor: true,
                        favorite_product: true,
                        interested_event: true,
                        sector_updated: true,
                        news_updated: true,
                        outdoor_spot_updated: true,
                        ice_spot_updated: true,
                        techtip_updated: true,
                        gym_updated: true,
                        product_updated: true,
                        service_updated: true,
                        tour_updated: true,
                        mount_updated: true,
                        summit_updated: true,
                        blog_post_updated: true,
                        film_updated: true,
                    }
                    this.update_user_notification_data()
                }
            },

            cancel_all_notifications(){
                if (window.confirm(this.$t('user.notification_settings.confirm_disable_all'))) {
                    this.data = {
                        add_new_gym: false,
                        news: false,
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
                    }
                    this.update_user_notification_data()
                }
            }
        }
    }
</script>

<style>

</style>