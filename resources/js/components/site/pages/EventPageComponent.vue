<template>
    <div class="container">
        <div class='row'>
            <div class="col-md-12">
                
                <breadcrumb />

                <p class="calendar" v-if="start_day != 0 && start_month != 0">
                    {{ start_day }}
                    <span class="calendar_monthe">{{ start_month }}</span>
                    <em>Start</em>
                </p>
                <!-- <p class="calendar" v-if="end_day != 0 && end_month != 0">
                    {{ end_day }}
                    <span class="calendar_monthe">{{ end_month }}</span>
                    <em>Finish</em>
                </p> -->
                
                <h1>{{ event.locale_event.title }} <span @click="add_to_interestid_event(event.global_event.id)"> <i class="fa fa-heart-o favorite_icon add_to_favorite" ></i> </span></h1>
                                            
                <span v-html="event.locale_event.text" v-if="this.event.locale_event.text != null"></span>

            </div>
        </div>

        <div class='row'>
            <div class="col-md-12">

                <div class="row" v-if="this.event.global_event.map != null">
                    <h2 id="map">{{ $t('map')}}</h2>
                    <span v-html="event.global_event.map"></span>
                </div>

                <div class="row" v-if="this.event.locale_event.info != null">
                    <h2 id="info">{{ $t('info')}}</h2>
                    <span v-html="event.locale_event.info"></span>
                </div>

            </div>
        </div>
        <div class="row">
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
            :image = "'../../../../public/images/event_img/'+event.image"
        />

    </div>
</template>

<script>
    import commentForm from '../items/CommentFormComponent'
    import metaData from '../items/MetaDataComponent'
    import breadcrumb from '../items/BreadcrumbComponent.vue'

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
            };
        },
        components: {
            metaData,
            commentForm,
            breadcrumb,
        },
        mounted() {
            this.get_event()
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
                })
                .catch(error =>{
                })
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
</style>