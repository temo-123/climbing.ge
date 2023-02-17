<template>
    <div class="row">
        <div class="col-sm-3">
            <left-menu />
        </div>
        <div class="col-sm-9">
            <div class="row">
                <div class="col-md-12">
                    <breadcrumb />
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">

                    <div class="tabs"> 
                        <div class="row">
                            <div class="col-md-12">
                                <div class="row">
                                    <div class="col" >
                                        <input type="radio" id="1" :value="1" v-model="tab_num">
                                        
                                        <label for="1" >Content</label>
                                    </div>
                                    <div class="col" >
                                        <input type="radio" id="2" :value="2" v-model="tab_num">
                                        
                                        <label for="2" >Orders</label>
                                    </div>
                                    <div class="col" >
                                        <input type="radio" id="3" :value="3" v-model="tab_num">
                                        
                                        <label for="3" >Notifications and Mails</label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row" v-if="tab_num == 3">

                            <div class="col-md-12" >
                                <div class="card">
                                    <div class="card-body">
                                        <button class="btn btn-primary" @click="open_notifay_modal()">Make mail notification for user</button>
                                    </div>
                                </div>
                            </div>

                            <user_notifications_list />
                        </div>

                        <div class="row" v-if="tab_num == 2">
                            <order_list />
                        </div>

                        <div class="row" v-if="tab_num == 1">
                            <data_counts_list />
                        </div>

                    </div>

                    <stack-modal
                        :show="is_notifay_modal"
                        title="Send mail notification"
                        @close="is_notifay_modal=false"
                        :saveButton="{ visible: true, title: 'Sand', btnClass: { 'btn btn-primary': true } }"
                        :cancelButton="{ visible: false, title: 'Close', btnClass: { 'btn btn-danger': true } }"
                    >
                        <pre class="language-vue">
                            <form id="sund_user_notification" v-on:submit.prevent="send_mail" >
                                <div class="container">

                                    <div class="form-group clearfix row">
                                        <div class="col-md-12 image_add_modal_form">
                                            <select class="form-control" name="filter" v-model="notification_type" @change="selected_notification_action()" required>
                                                <option value="0" disabled>Select notification type</option> 

                                                <option value="favorite_outdoor">favorite_outdoor</option>
                                                <option value="favorite_product">favorite_product</option>
                                                <option value="interested_event">interested_event</option>
                                                
                                                <option value="favorite_film" disabled>favorite_film</option>

                                            </select> 
                                        </div>
                                    </div>

                                    <div class="alert alert-danger" role="alert" v-if="is_select_notification_type_error">
                                        Select notification type
                                    </div>

                                    <div class="form-group clearfix row" v-if="notification_type == 'interested_event' && events.length != 0">
                                        <div class="col-md-12 image_add_modal_form">
                                            <select class="form-control" name="filter" v-model="event_notification_type">
                                                <option value="0" disabled>Select notification type</option> 
                                                <option value="next">Next event</option> 
                                                <option value="select_event">Select event</option> 
                                            </select> 
                                        </div>
                                    </div>

                                    <div class="form-group clearfix row" v-if="notification_type == 'interested_event' && event_notification_type == 'select_event' && events.length != 0">
                                        <div class="col-md-12 image_add_modal_form">
                                            <select class="form-control" name="filter" v-model="notification_id">
                                                <option value="0" disabled>Select event</option> 

                                                <option v-for="event in events" :key="event.id" :value="event.id">{{ event.url_title }}</option>
                                            </select> 
                                        </div>
                                    </div>
                                    
                                    <div class="alert alert-danger" role="alert" v-if="notification_type == 'interested_event' && events.length == 0">
                                        No events for notification
                                    </div>

                                    <div class="form-group clearfix row" v-if="notification_type == 'favorite_product' && products.length != 0">
                                        <div class="col-md-12 image_add_modal_form">
                                            <select class="form-control" name="filter" v-model="notification_id">
                                                <option value="0" disabled>Select product</option> 

                                                <option v-for="product in products" :key="product.id" :value="product.id">{{ product.url_title }}</option>
                                            </select> 
                                        </div>
                                    </div>
                                    
                                    <div class="alert alert-danger" role="alert" v-if="notification_type == 'favorite_product' && products.length == 0">
                                        No products for notification
                                    </div>

                                    <div class="form-group clearfix row" v-if="notification_type == 'favorite_film' && film.length != 0">
                                        <div class="col-md-12 image_add_modal_form">
                                            <select class="form-control" name="filter" v-model="notification_id">
                                                <option value="0" disabled>Select films</option>

                                                <option v-for="film in films" :key="film.id" :value="film.id">{{ film.url_title }}</option>
                                            </select> 
                                        </div>
                                    </div>
                                    
                                    <div class="alert alert-danger" role="alert" v-if="notification_type == 'favorite_film' && film.length == 0">
                                        No films for notification
                                    </div>

                                    <div class="form-group clearfix row" v-if="notification_type == 'favorite_outdoor' && outdoors.length != 0">
                                        <div class="col-md-12 image_add_modal_form">
                                            <select class="form-control" name="filter" v-model="notification_id">
                                                <option value="0" disabled>Select outdoor area</option> 

                                                <option v-for="outdoor in outdoors" :key="outdoor.id" :value="outdoor.id">{{ outdoor.url_title }}</option>
                                            </select> 
                                        </div>
                                    </div>
                                    
                                    <div class="alert alert-danger" role="alert" v-if="notification_type == 'favorite_outdoor' && outdoors.length == 0">
                                        No films for notification
                                    </div>

                                </div>
                            </form>
                        </pre>
                        <div slot="modal-footer">
                            <div class="modal-footer">
                                <button
                                    type="submit"
                                    form="sund_user_notification"
                                    :class="{'btn btn-primary': true}"
                                >
                                Send
                                </button>
                            </div>
                        </div>
                    </stack-modal>

                </div>
            </div>
        </div>
    </div>
</template>

<script>
    // import Editor from '../items/canvas/EditorComponent.vue'
    import { SlickList, SlickItem } from 'vue-slicksort'; //https://github.com/Jexordexan/vue-slicksort
    import StackModal from '@innologica/vue-stackable-modal'  //https://innologica.github.io/vue-stackable-modal/#sample-css
    import breadcrumb from '../items/BreadcrumbComponent.vue'

    import user_notifications_list from '../items/UserNotificationsListComponent.vue'
    import order_list from '../items/OrderDiogramComponent.vue'
    import data_counts_list from '../items/SiteDataCountListComponent.vue'

    export default {
        components: {
            StackModal,
            SlickItem,
            SlickList,
            breadcrumb,

            user_notifications_list,
            order_list,
            data_counts_list,
        },
        data(){
            return{
                tab_num: 1,
                is_notifay_modal: false,
                is_select_notification_type_error: false,

                notification_type: 0,
                event_notification_type: 0,
                notification_id: 0,

                outdoors: [],
                events: [],
                products: [],
                films: [],
            }
        },
        mounted(){
            // 
        },
        methods: {
            open_notifay_modal(){
                this.is_notifay_modal = true
            },

            close_notifay_modal(){
                this.is_notifay_modal = false

                this.notification_type = 0
                this.event_notification_type = 0
                this.notification_id = 0
            },

            send_mail(){
                this.is_select_notification_type_error = false

                if(this.notification_type != 0 && this.notification_id != 0 || this.event_notification_type != 0){
                    axios
                    .post('../../../api/user/notifications/send_user_favorites_notification/' + this.notification_type, {
                        id: this.notification_id,
                        event_notification_type: this.event_notification_type
                    })
                    .then(response => {
                        alert(response.data)
                        this.close_notifay_modal()
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

            selected_notification_action(){
                if(this.notification_type == "favorite_outdoor"){
                    this.get_outdoor_articles()
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

            get_outdoor_articles(){
                axios
                .post("../api/article/", {
                    category: 'outdoor',
                })
                .then(response => {
                    this.outdoors = response.data
                })
                .catch(
                    error => console.log(error)
                )
                .finally(() => this.article_loading = false);
            },

            get_events(){
                axios
                .get("../api/event/get_all_events/")
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
                .get("../api/products/en/")
                .then(response => {
                    this.products = response.data
                })
                .catch(
                    error => console.log(error)
                );
            },

            get_films(){
                axios
                .get("../api/films/")
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

</style>