<template>
    <div class="col-md-12">
        <div class="row edit_buttom">
            <div class="col-md-12">
                <p class="text-center">This your notifications. You can select only this notifications which one you want get on your email.</p>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="form-groupe">
                    <button class="btn btn-primary" @click="activate_all_notifications">Activate all notifications</button>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-groupe">
                    <button class="btn btn-danger float-right" @click="cancel_all_notifications">Cancel all notifications</button>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="form-groupe">
                    <button class="btn btn-primary" @click="update_user_notification_data" >Save</button>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-groupe">
                    <button @click="get_user_notification_data" class="btn btn-success float-right" v-if="!notification_is_refresh">Refresh ({{notification_reset_id}})</button>
                    <span class="badge badge-primare mb-1 float-right" v-if="notification_is_refresh">Updating...</span>
                </div>
            </div>
        </div>

        <!-- <div class="row"> -->
            <table class="table table-hover" id="dev-table">
                <thead>
                    <tr>
                        <th>Notification</th>
                        <th>|</th>
                        <th>Action</th>
                    </tr>
                </thead>
                
                <tbody >
                    <tr>
                        <td>Favorite outdoor</td>
                        <td>|</td>
                        <td>
                            <input type="checkbox" v-model="data.favorite_outdoor">
                        </td>
                    </tr>
                    <tr>
                        <td>favorite product</td>
                        <td>|</td>
                        <td>
                            <input type="checkbox" v-model="data.favorite_product">
                        </td>
                    </tr>
                    <tr>
                        <td>Favorite film</td>
                        <td>|</td>
                        <td>
                            <input type="checkbox" v-model="data.favorite_film">
                        </td>
                    </tr>
                    <tr>
                        <td>interested event</td>
                        <td>|</td>
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
                        <td>Add new sector</td>
                        <td>|</td>
                        <td>
                            <input type="checkbox" v-model="data.add_new_sector">
                        </td>
                    </tr>
                    <tr>
                        <td>Add new outdoor climbing spot</td>
                        <td>|</td>
                        <td>
                            <input type="checkbox" v-model="data.add_new_outdoor_spot">
                        </td>
                    </tr>
                    <tr>
                        <td>Add new ice climbing spot</td>
                        <td>|</td>
                        <td>
                            <input type="checkbox" v-model="data.add_new_ice_spot">
                        </td>
                    </tr>
                    <tr>
                        <td>Add new techtip</td>
                        <td>|</td>
                        <td>
                            <input type="checkbox" v-model="data.add_new_techtip">
                        </td>
                    </tr>
                    <tr>
                        <td>Add new gym</td>
                        <td>|</td>
                        <td>
                            <input type="checkbox" v-model="data.add_new_gym">
                        </td>
                    </tr>
                    <tr>
                        <td>Add new product</td>
                        <td>|</td>
                        <td>
                            <input type="checkbox" v-model="data.add_new_product">
                        </td>
                    </tr>
                    <tr>
                        <td>Add new service</td>
                        <td>|</td>
                        <td>
                            <input type="checkbox" v-model="data.add_new_service">
                        </td>
                    </tr>
                </tbody>
            </table>
        <!-- </div> -->
    </div>
</template>

<script>
    import { SlickList, SlickItem } from 'vue-slicksort'; //https://github.com/Jexordexan/vue-slicksort
    import StackModal from '@innologica/vue-stackable-modal'  //https://innologica.github.io/vue-stackable-modal/#sample-css

    export default {
        components: {
            StackModal,
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
                    favorite_film: false,
                    favorite_outdoor: false,
                    favorite_product: false,
                    interested_event: false,
                },

                action_id: 0,

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
                .get("../../api/options/get_user_notification_data/")
                .then(response => {
                    this.action_id = response.data.id
                    this.data = {
                        add_new_gym: response.data.add_new_gym,
                        add_new_ice_spot: response.data.add_new_ice_spot,
                        add_new_outdoor_spot: response.data.add_new_outdoor_spot,
                        add_new_product: response.data.add_new_product,
                        add_new_sector: response.data.add_new_sector,
                        add_new_service: response.data.add_new_service,
                        add_new_techtip: response.data.add_new_techtip,
                        favorite_film: response.data.favorite_film,
                        favorite_outdoor: response.data.favorite_outdoor,
                        favorite_product: response.data.favorite_product,
                        interested_event: response.data.interested_event,
                    },

                    this.notification_is_refresh = false
                    this.notification_reset_id++
                })
                .catch(
                    error => console.log(error)
                );
            },

            update_user_notification_data: function(){
                this.user_is_refresh = true
                axios
                .post("../../api/options/update_user_notification_data/"+this.action_id,{
                    data: this.data,

                    _method: 'POST'
                })
                .then(response => {
                    this.get_user_notification_data()
                })
                .catch(
                    error => console.log(error)
                );
            },

            activate_all_notifications(){
                if (window.confirm('Are you sure, you want activate all notifications?')) {
                    this.data = {
                        add_new_gym: true,
                        add_new_ice_spot: true,
                        add_new_outdoor_spot: true,
                        add_new_product: true,
                        add_new_sector: true,
                        add_new_service: true,
                        add_new_techtip: true,
                        favorite_film: true,
                        favorite_outdoor: true,
                        favorite_product: true,
                        interested_event: true,
                    }
                    this.update_user_notification_data()
                }
            },

            cancel_all_notifications(){
                if (window.confirm('Are you sure, you want canceled all notifications?')) {
                    this.data = {
                        add_new_gym: false,
                        add_new_ice_spot: false,
                        add_new_outdoor_spot: false,
                        add_new_product: false,
                        add_new_sector: false,
                        add_new_service: false,
                        add_new_techtip: false,
                        favorite_film: false,
                        favorite_outdoor: false,
                        favorite_product: false,
                        interested_event: false,
                    }
                    this.update_user_notification_data()
                }
            }
        }
    }
</script>

<style>

</style>