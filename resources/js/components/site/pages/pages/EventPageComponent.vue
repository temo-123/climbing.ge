<template>
    <div class="container">
        <span v-if="article_loading">
            <articlePreloader />
        </span>
        <div class="row" v-if="!article_loading">
            <div class="offset-md-3 col-md-offset-3" v-if="!(!end_day) && start_time_h > 0">
                <p class="calendar start_calendar">
                    {{ start_day }}
                    <span class="calendar_monthe">{{ start_month }}</span>
                    <span class="calendar_monthe">{{ start_time }}</span>
                    <em>Start</em>
                </p>
                <p class="calendar end_calendar">
                    {{ end_day }}
                    <span class="calendar_monthe">{{ end_month }}</span>
                    <span class="calendar_monthe">{{ end_time }}</span>
                    <em>Finish</em>
                </p>
            </div>
            <div class="offset-md-6 col-md-offset-6" v-else>
                <p class="calendar start_calendar">
                    {{ start_month }}
                    <span class="calendar_monthe">{{ start_year }}</span>
                    <em>Start</em>
                </p>
            </div>
        </div>
        <div class='row' v-if="!article_loading">
            <div class="col-md-12">
                
                <breadcrumb />
                
                <h1>{{ event.locale_event.title }} <span @click="add_to_interestid_event(event.global_event.id)"> <i class="fa fa-heart-o favorite_icon add_to_favorite" ></i> </span></h1>
                                            
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
        <div class="row" v-if="!article_loading">
            <div class="col-md-12"> 
                <div class="row">
                    <div style="text-align: center; margin: 4% 30%;">
                        <a @click="add_to_interestid_event(event.global_event.id)" type="button" class="btn btn-success" style="width: 100%;">{{ $t('interested event') }}</a>
                    </div>
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
                .get('../../api/event/get_event_on_site_page/'+localStorage.getItem('lang')+'/'+this.$route.params.url_title)
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
                })
                .catch(error =>{
                })
                .finally(() => this.article_loading = false);
            },

            add_to_interestid_event(article_id){
                axios
                .post('../../api/event/add_to_interested_events/', {
                    event_id: article_id,
                })
                .then(response => {
                    alert(response.data)
                })
                .catch(error =>{
                })
            }
        }
    }
</script>

<style scoped>
    .add_to_favorite{
        float: right; 
        cursor: pointer;
    }

    .calendar_monthe{
        display: block;
        margin-top: -48%;
        margin-bottom: -15%;
        font-size: 60%;
    }

    /* .start_calendar{

    } */
    .end_calendar{
        float: right;
        margin-right: 25%;
    }
</style>