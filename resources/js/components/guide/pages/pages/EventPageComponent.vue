<template>
    <div class="container">
        <span v-if="article_loading">
            <articlePreloader />
        </span>
        <div class="row" v-if="!article_loading">
            <div class="offset-md-3 col-md-offset-3" v-if="!(!end_day) && start_time_h > 0">
                <p class="calendar start_calendar">
                    <i class="fa fa-calendar"></i>
                    {{ start_day }}
                    <span class="calendar_monthe">{{ start_month }}</span>
                    <span class="calendar_monthe">{{ start_time }}</span>
                    <em>Start</em>
                </p>
                <p class="calendar end_calendar">
                    <i class="fa fa-calendar"></i>
                    {{ end_day }}
                    <span class="calendar_monthe">{{ end_month }}</span>
                    <span class="calendar_monthe">{{ end_time }}</span>
                    <em>Finish</em>
                </p>
            </div>
            <div class="offset-md-6 col-md-offset-6" v-else>
                <p class="calendar start_calendar">
                    <i class="fa fa-calendar"></i>
                    {{ start_month }}
                    <span class="calendar_monthe">{{ start_year }}</span>
                    <em>Start</em>
                </p>
            </div>
        </div>
        <div class='row' v-if="!article_loading">
            <div class="col-md-12">
                
                <breadcrumb />
                
                <h1>{{ event.locale_event.title }} <span @click="toggle_interested_status(event.global_event.id)"> <i :class="is_interested ? 'fa fa-heart favorite_icon add_to_favorite active' : 'fa fa-heart-o favorite_icon add_to_favorite'"></i> </span></h1>
                                            
                <span v-html="event.locale_event.text" v-if="this.event.locale_event.text != null"></span>

            </div>
        <!-- </div>

        <div class='row'> -->
            
            <div class="col-md-12">

                <span v-if="this.event.global_event.map != null && this.event.global_event.map != ''">
                    <h2 id="map">{{ $t('guide.article.title.locatione')}}</h2>
                    <span v-html="event.global_event.map"></span>
                </span>

                <div v-if="this.event.locale_event.info || this.event.general_info">
                    <h2 id="how_to_get_there">{{ $t('guide.article.title.info')}}</h2>
                    <span v-if="!this.event.general_info">
                        <span v-html="this.event.locale_event.info"></span>
                    </span>
                    <span v-else>
                        <span v-if="this.event.general_info.info_block.block_action == 'befor'">
                            <span v-html="this.event.general_info.info_block.text"></span>
                            <span v-html="this.event.locale_event.info"></span>
                        </span>
                        <span v-if="this.event.general_info.info_block.block_action == 'after'">
                            <span v-html="this.event.locale_event.info"></span>
                            <span v-html="this.event.general_info.info_block.text"></span>
                        </span>
                        <span v-if="this.event.general_info.info_block.block_action == 'instead'">
                            <span v-html="this.event.general_info.info_block.text"></span>
                        </span>
                    </span>
                </div>

            </div>
        </div>
        
        <metaData 
            :title = "event.locale_event.title"
            :description = "event.locale_event.description"
            :image = "'/public/images/event_img/'+event.image"
        />

    </div>
</template>

<script>
    import articlePreloader from "../../items/article/ArticlePreloaderComponent.vue";
    import metaData from '../../items/MetaDataComponent'
    import breadcrumb from '../../items/BreadcrumbComponent.vue'
    import moment from "moment"; // https://www.npmjs.com/package/vue-moment

    export default {
        props: [
            // 'article',
        ],
        data: function () {
            return {
                event: [],

                start_day: 0,
                end_day: 0,

                start_month: 0,
                end_month: 0,

                start_year: 0,
                end_year: 0,

                article_loading: false,
                is_interested: false,
            };
        },
        components: {
            articlePreloader,
            metaData,
            breadcrumb,
            moment
        },
        mounted() {
            this.get_event()

            this.start_day = moment(this.event.global_event.start_data).format("D")
            this.end_day = moment(this.event.global_event.end_data).format("D")

            this.start_month = moment(this.event.global_event.start_data).format("MMM")
            this.end_month = moment(this.event.global_event.end_data).format("MMM")

            // Check if event is in user's interested events
            this.$nextTick(() => {
                if (this.event.global_event && this.event.global_event.id) {
                    this.check_interested_status(this.event.global_event.id);
                }
            });
        },
        watch: {
            '$route' (to, from) {
                this.get_event(),
                window.scrollTo(0,0)
            }
        },
        methods: {
            get_event(){
                axios
                .get('/get_event/get_event_on_site_page/'+localStorage.getItem('lang')+'/'+this.$route.params.url_title)
                .then(response => {
                    this.event = response.data

                    this.start_day = moment(response.data.global_event.start_data).format("D")
                    this.end_day = moment(response.data.global_event.end_data).format("D")

                    this.start_month = moment(response.data.global_event.start_data).format("MMM")
                    this.end_month = moment(response.data.global_event.end_data).format("MMM")

                    this.start_year = moment(response.data.global_event.start_data).format("Y")
                    this.end_year = moment(response.data.global_event.end_data).format("Y")

                    this.start_time = moment(this.event.global_event.start_data).format("H:MM A")
                    this.end_time = moment(response.data.global_event.end_data).format("H:MM A")

                    this.start_time_h = moment(this.event.global_event.start_data).format("H")

                    // Check if event is in user's interested events
                    this.check_interested_status(this.event.global_event.id)
                })
                .catch(error =>{
                })
                .finally(() => this.article_loading = false);
            },

            check_interested_status(event_id){
                axios
                .get('/get_faworite/check_interested_status/' + event_id)
                .then(response => {
                    this.is_interested = response.data.is_interested;
                })
                .catch(error => {
                    console.log('Error checking interested status:', error);
                    this.is_interested = false;
                });
            },

            toggle_interested_status(event_id){
                if(this.is_interested) {
                    // Remove from interested events
                    axios
                    .delete('/set_faworite/del_interested_event/' + event_id)
                    .then(response => {
                        this.is_interested = false;
                        alert(response.data);
                    })
                    .catch(error => {
                        if(error.response && error.response.status === 401) {
                            if(confirm('You are not logged in. Do you want to log in?')){
                                window.open(process.env.MIX_APP_SSH + 'user.' + process.env.MIX_SITE_URL);
                            }
                        }
                        else if (error.response && error.response.status === 404) {
                            // Event was already removed, update UI
                            this.is_interested = false;
                        }
                        else{
                            console.log('Error removing from favorites:', error);
                        }
                    });
                } else {
                    // Add to interested events
                    axios
                    .post('/set_faworite_by_user/add_to_interested_events', {
                        event_id: event_id,
                    })
                    .then(response => {
                        this.is_interested = true;
                        alert(response.data);
                    })
                    .catch(error => {
                        if(error.response && error.response.status === 401) {
                            if(confirm('You are not logged in. Do you want to log in?')){
                                window.open(process.env.MIX_APP_SSH + 'user.' + process.env.MIX_SITE_URL);
                            }
                        }
                        else{
                            console.log('Error adding to favorites:', error);
                        }
                    });
                }
            }
        }
    }
</script>

<style scoped>
    .container {
        margin-top: 20px;
    }

    h1 {
        font-size: 2.5em;
        color: #555;
        margin-bottom: 20px;
        font-weight: 300;
    }

    .add_to_favorite {
        float: right;
        cursor: pointer;
        color: #ccc;
        transition: color 0.3s ease;
        font-size: 24px;
    }

    .add_to_favorite:hover {
        color: #999;
    }

    /* Active favorite heart styling */
    .add_to_favorite.active {
        color: #e74c3c !important;
        transition: color 0.3s ease;
    }

    .calendar {
        background: #f0f8ff;
        border-radius: 5px;
        /* padding: 12px; */
        display: inline-block;
        margin: 10px;
        color: #31708f;
        font-size: 20px;
        /* line-height: 1.3; */
        text-align: center;
    }

    .calendar i {
        margin-right: 5px;
        color: #6c757d;
        font-size: 16px;
    }

    .calendar em {
        color: #6c757d;
        font-style: normal;
        font-weight: bold;
        font-size: 12px;
        display: block;
        margin-top: 3px;
    }

    .calendar_monthe {
        display: block;
        margin-top: -20px;
        margin-bottom: -3px;
        font-size: 12px;
    }

    .end_calendar {
        float: right;
        margin-right: 25%;
    }

    h2 {
        color: #666;
        margin-top: 30px;
        font-size: 1.8em;
        font-weight: 300;
    }

    .row {
        margin-bottom: 20px;
    }

    .btn-success {
        background: #e9ecef;
        border: 1px solid #ced4da;
        border-radius: 5px;
        padding: 15px 30px;
        font-size: 18px;
        color: #495057;
        transition: background-color 0.2s ease;
    }

    .btn-success:hover {
        background: #dee2e6;
    }

    span {
        line-height: 1.6;
        font-size: 16px;
    }
</style>
