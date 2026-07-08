<template>
    <stack-modal
        :show="is_notifay_modal"
        :title="$t('admin.notifications.send_mail_notification_title')"
        @close="close_modal()"
        :saveButton="{ visible: true, title: $t('admin.notifications.send_btn'), btnClass: { 'btn btn-primary': true } }"
        :cancelButton="{ visible: false, title: $t('common.close'), btnClass: { 'btn btn-danger': true } }"
    >
        <div>
            <form id="sund_user_notification" v-on:submit.prevent="send_mail" >
                <div class="container">

                    <div class="form-group clearfix row">
                        <div class="col-md-12 image_add_modal_form">
                            <select class="form-control" name="filter" v-model="notification_type" @change="selected_notification_action()" required>
                                <option value="0" disabled>{{ $t('admin.notifications.select_notification_type_placeholder') }}</option>
                                <option value="favorite_film" disabled>favorite_film</option>

                                <option value="news">{{ $t('admin.notifications.last_news_notification_option') }}</option>
                                <option value="favorite_outdoor">{{ $t('admin.notifications.favorite_outdoor_regions_option') }}</option>
                                <option value="favorite_product">{{ $t('admin.notifications.favorite_products_option') }}</option>
                                <option value="interested_event">{{ $t('admin.notifications.interested_events_option') }}</option>
                                <option value="special_articles">{{ $t('admin.notifications.special_articles_option') }}</option>
                            </select>
                        </div>
                    </div>

                    <div class="alert alert-danger" role="alert" v-if="is_select_notification_type_error">
                        {{ $t('admin.notifications.select_notification_type_error') }}
                    </div>

                    <div class="form-group clearfix row" v-if="notification_type == 'interested_event' && events.length != 0">
                        <div class="col-md-12 image_add_modal_form">
                            <select class="form-control" name="filter" v-model="event_notification_type">
                                <option value="0" disabled>{{ $t('admin.notifications.select_notification_type_placeholder') }}</option>
                                <option value="next">{{ $t('admin.notifications.next_event_option') }}</option>
                                <option value="select_event">{{ $t('admin.notifications.select_event_option') }}</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group clearfix row" v-if="notification_type == 'interested_event' && event_notification_type == 'select_event' && events.length != 0">
                        <div class="col-md-12 image_add_modal_form">
                            <select class="form-control" name="filter" v-model="notification_id">
                                <option value="0" disabled>{{ $t('admin.notifications.select_event_placeholder') }}</option>

                                <option v-for="event in events" :key="event.id" :value="event.id">{{ event.url_title }}</option>
                            </select>
                        </div>
                    </div>

                    <div class="alert alert-danger" role="alert" v-if="notification_type == 'interested_event' && events.length == 0">
                        {{ $t('admin.notifications.no_events_for_notification') }}
                    </div>

                    <div class="form-group clearfix row" v-if="notification_type == 'favorite_product' && products.length != 0">
                        <div class="col-md-12 image_add_modal_form">
                            <select class="form-control" name="filter" v-model="notification_id">
                                <option value="0" disabled>{{ $t('admin.orders.select_product_placeholder') }}</option>

                                <option v-for="product in products" :key="product.id" :value="product.id">{{ product.url_title }}</option>
                            </select>
                        </div>
                    </div>

                    <div class="alert alert-danger" role="alert" v-if="notification_type == 'favorite_product' && products.length == 0">
                        {{ $t('admin.notifications.no_products_for_notification') }}
                    </div>

                    <div class="form-group clearfix row" v-if="notification_type == 'favorite_film' && film.length != 0">
                        <div class="col-md-12 image_add_modal_form">
                            <select class="form-control" name="filter" v-model="notification_id">
                                <option value="0" disabled>{{ $t('admin.notifications.select_films_placeholder') }}</option>

                                <option v-for="film in films" :key="film.id" :value="film.id">{{ film.url_title }}</option>
                            </select>
                        </div>
                    </div>

                    <div class="alert alert-danger" role="alert" v-if="notification_type == 'favorite_film' && film.length == 0">
                        {{ $t('admin.notifications.no_films_for_notification') }}
                    </div>

                    <div class="form-group clearfix row" v-if="notification_type == 'favorite_outdoor' && outdoors.length != 0">
                        <div class="col-md-12 image_add_modal_form">
                            <select class="form-control" name="filter" v-model="notification_id">
                                <option value="0" disabled>{{ $t('admin.routes_sectors.select_outdoor_area') }}</option>

                                <option v-for="outdoor in outdoors" :key="outdoor.id" :value="outdoor.id">{{ outdoor.url_title }}</option>
                            </select>
                        </div>
                    </div>

                    <div class="alert alert-danger" role="alert" v-if="notification_type == 'favorite_outdoor' && outdoors.length == 0">
                        {{ $t('admin.notifications.no_films_for_notification') }}
                    </div>

                </div>
            </form>
        </div>
        <div slot="modal-footer">
            <div class="modal-footer">
                <button
                    type="submit"
                    form="sund_user_notification"
                    :class="{'btn btn-primary': true}"
                >
                {{ $t('admin.notifications.send_btn') }}
                </button>
            </div>
        </div>
    </stack-modal>
</template>

<script>
    // import StackModal from '@innologica/vue-stackable-modal'  // Global now

    export default {
        components: {
        },
        data(){
            return{
                outdoors: [],
                events: [],
                products: [],
                films: [],

                is_notifay_modal: false,
                is_select_notification_type_error: false,

                notification_type: 0,
                notification_id: 0
            }
        },
        mounted(){
            // 
        },
        methods: {
            show_modal(){
                this.is_notifay_modal = true
            },

            close_modal(){
                this.is_notifay_modal = false
                this.notification_type = false
            },

            send_mail(){
                this.is_select_notification_type_error = false

                if(this.notification_type != 0 && this.notification_id != 0 || this.event_notification_type != 0){
                    axios
                    .post('/user/notifications/send_user_favorites_notification/' + this.notification_type, {
                        id: this.notification_id,
                        event_notification_type: this.event_notification_type
                    })
                    .then(response => {
                        alert(response.data)
                        this.close_modal()
                    })
                    .catch(err => {
                        console.log(err);
                    })
                    .finally(() => this.is_mail_sending_procesing = false);
                }
                else{
                    this.is_select_notification_type_error = true
                }
            },
            get_events(){
                axios
                .get("/event/get_all_events/")
                .then(response => {
                    this.events = response.data
                })
                .catch(
                    error => console.log(error)
                )
                .finally(() => this.event_loading = false);
            },

            get_products(){
                axios
                .get("/products/en/")
                .then(response => {
                    this.products = response.data
                })
                .catch(
                    error => console.log(error)
                );
            },

            selected_notification_action(){
                if(this.notification_type == "favorite_outdoor"){
                    this.get_articles('outdoor')
                }
                if(this.notification_type == "special_articles"){
                    this.get_articles('special')
                }
                else if(this.notification_type == "favorite_product"){
                    this.get_products()
                }
                else if(this.notification_type == "interested_event"){
                    this.get_events()
                }
                else if(this.notification_type == "favorite_film"){
                    this.get_films()
                }
            },

            get_articles(category){
                axios
                .get("/article/get_category_articles/" + category)
                .then(response => {
                    this.outdoors = response.data
                })
                .catch(
                    error => console.log(error)
                )
                .finally(() => this.article_loading = false);
            },

            get_films(){
                axios
                .get("/films/")
                .then(response => {
                    this.events = response.data
                })
                .catch(
                    error => console.log(error)
                );
            },
        }
    }
</script>

<style scoped>
.control-label{
    float: left;
}
</style>