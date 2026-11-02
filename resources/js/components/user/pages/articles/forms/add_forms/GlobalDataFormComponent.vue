<template>
    <div class="col-md-12" >
        <div class="row">
            <div class="col-md-12">
                <div class="jumbotron width_100">
                    <div class="container">
                        <h2 class="display-4"><span style="text-transform: capitalize">{{this.category}}</span> {{ $t('admin.articles.global_form.title_suffix') }}</h2>
                        <p class="lead">{{ $t('admin.articles.global_form.subtitle') }}</p>
                    </div>
                </div>
                
                <form method="POST" @submit.prevent="add_article">
                    <!-- <div class="form-group clearfix row">
                        <label for="published" class='col-md-2 control-label '> {{ $t('admin.articles.global_form.publish') }} </label>
                        <div class="col-md-10">
                            <select class="form-control" v-model="data.published" name="published" > 
                                <option value="0">Not public</option> 
                                <option value="1">Public</option> 
                            </select> 
                        </div>
                    </div> -->
                    
                    <published_item
                        :published_prop = data.published
                        @item_data="data.published = $event"
                    />

                    <div class="form-group clearfix row">
                        <label for="show_donation" class='col-md-2 control-label'> {{ $t('admin.articles.global_form.show_donation_label') }} </label>
                        <div class="col-md-10">
                            <input type="checkbox" id="show_donation" name="show_donation" v-model="data.show_donation" :true-value="1" :false-value="0">
                        </div>
                    </div>

                    <div class="form-group clearfix row" v-if="['outdoor', 'indoor', 'ice'].includes(this.category)">
                        <label for="region" class='col-md-2 control-label '> {{ $t('admin.articles.global_form.regions_label') }} </label>
                        <div class="col-md-10">
                            <select class="form-control" v-model="data.region_id" name="region" >
                                <option :value="0" disabled>{{ $t('admin.articles.global_form.select_region') }}</option>
                                <option :value="null" style="color:red">{{ $t('admin.articles.global_form.without_region') }}</option>
                                <option  v-for="region in regions" :key="region.id" :value="region.id">{{ region.us_name }}</option>
                            </select> 
                        </div>
                    </div>

                    <div class="form-group clearfix row" v-if="this.category == 'mount_route'">
                        <label for="name" class='col-md-2 control-label'> {{ $t('admin.articles.global_form.mountain_label') }} </label>
                        <div class="col-md-10">
                            <select class="form-control" v-model="mount_id" name="mount_id">
                                <option :value="0" disabled>{{ $t('admin.articles.global_form.select_mount') }}</option>
                                <option :value="null" style="color:red">{{ $t('admin.articles.global_form.without_mount') }}</option>
                                <option v-for="mount in mount_masive" :key="mount.id" :value="mount.global_data.id">{{mount.global_data.name}}</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group clearfix row" v-if="this.category == 'mount_route'">
                        <label for="mount_grade" class='col-md-2 control-label'> {{ $t('admin.articles.global_form.route_grade') }} </label>
                        <div class="col-md-10">
                            <select class="form-control" v-model="data.mount_grade" name="mount_grade">
                                <option :value="null">{{ $t('admin.articles.global_form.no_grade') }}</option>
                                <optgroup :label="$t('admin.articles.global_form.french_alpine')">
                                    <option v-for="g in french_grades" :key="g" :value="g">{{ g }}</option>
                                </optgroup>
                                <optgroup :label="$t('admin.articles.global_form.russian_cis')">
                                    <option v-for="g in russian_grades" :key="g" :value="g">{{ g }}</option>
                                </optgroup>
                            </select>
                        </div>
                    </div>

                    <!-- <div class="form-group clearfix row" v-if="this.category == 'event'">
                        <label for="name" class='col-md-2 control-label'> Completed </label>
                        <div class="col-md-10">
                            <select class="form-control" v-model="data.completed" name="completed"> 
                                <option value="0">No complited</option> 
                                <option value="1">Complited</option> 
                            </select> 
                        </div>
                    </div> -->


                    <div class="form-group clearfix row" v-if="this.category != 'mount_route' && category != 'partners'">
                        <label for="name" class='col-md-2 control-label'> {{ $t('admin.articles.global_form.map_label') }} </label>
                        <div class="col-md-10">
                            <input type="text" v-model="data.map" name="map" class="form-control"> 
                        </div>
                    </div>


                    <div class="form-group clearfix row"  v-if="this.category == 'outdoor' || this.category == 'ice'">
                        <label for="name" class='col-md-2 control-label'> {{ $t('admin.articles.global_form.weather_label') }} <small class="text-muted">({{ $t('admin.articles.global_form.optional') }})</small></label>
                        <div class="col-md-10">
                            <input type="text" v-model="data.weather" name="weather" class="form-control"> 
                        </div>
                    </div>


                    <div class="form-group clearfix row" v-if="category == 'outdoor' || category == 'ice' || category == 'indoor'">
                        <label class='col-md-2 control-label'> {{ $t('admin.articles.global_form.parking_label') }} <small class="text-muted">({{ $t('admin.articles.global_form.optional') }})</small></label>
                        <div class="col-md-10">
                            <input type="text" name="parking" v-model="data.parking" class="form-control" maxlength="500" placeholder="41.7151, 44.8271">
                            <small class="form-text text-muted">{{ $t('admin.articles.global_form.parking_help') }}</small>
                        </div>
                    </div>


                    <div class="form-group clearfix row" v-if="this.category == 'indoor'">
                        <label for="name" class='col-md-2 control-label'> {{ $t('admin.articles.global_form.minimal_price') }} </label>
                        <div class="col-md-10">
                            <!-- a price status replaces the minimal price (the server clears it) -->
                            <select class="form-control" v-model="data.price_status" name="price_status">
                                <option :value="null">{{ $t('admin.articles.global_form.price_status_normal') }}</option>
                                <option value="no_info">{{ $t('guide.article.no_info_price') }}</option>
                            </select>
                            <input v-if="!data.price_status" type="text" name="price_from" v-model="data.price_from" class="form-control" style="margin-top: 8px;">
                        </div>
                    </div>

                    <div class="form-group clearfix row" v-if="this.category == 'indoor'">
                        <label for="name" class='col-md-2 control-label'> {{ $t('admin.articles.global_form.working_time') }} </label>
                        <div class="col-md-10">
                            <!-- normal = the same hours every day; "different hours on different days" = the per-day
                                 table (article_working_hours, saved with the article); any other status replaces the hours -->
                            <select class="form-control" v-model="data.work_status" name="work_status">
                                <option :value="null">{{ $t('admin.articles.global_form.work_status_normal') }}</option>
                                <option value="per_day">{{ $t('admin.articles.global_form.work_status_per_day') }}</option>
                                <option value="temporarily_closed">{{ $t('guide.article.work_status.temporarily_closed') }}</option>
                                <option value="sport_session_closed">{{ $t('guide.article.work_status.sport_session_closed') }}</option>
                                <option value="coming_soon">{{ $t('guide.article.work_status.coming_soon') }}</option>
                                <option value="no_info">{{ $t('guide.article.work_status.no_info') }}</option>
                            </select>
                            <div v-if="!data.work_status" class="row" style="margin-top: 8px;">
                                <div class="col-md-6">
                                    <input type="time" name="open_time" class="form-control" v-model="data.open_time" :placeholder="$t('admin.articles.global_form.start_time_placeholder')">
                                </div>
                                <div class="col-md-6">
                                    <input type="time" name="closed_time" class="form-control" v-model="data.closed_time" :placeholder="$t('admin.articles.global_form.end_time_placeholder')">
                                </div>
                            </div>
                            <weeklyHoursInput
                                v-else-if="data.work_status == 'per_day'"
                                v-model="data.weekly_hours"
                                :default_open="data.open_time"
                                :default_close="data.closed_time"
                            />
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>
<script>
    // import { SlickList, SlickItem } from 'vue-slicksort';
    // import StackModal from '@innologica/vue-stackable-modal'  //https://innologica.github.io/vue-stackable-modal/#sample-css

    import published_item from '../../../../items/form/parts/PublishedValueComponent.vue'
    import weeklyHoursInput from '../../../../items/form/parts/WeeklyHoursInputComponent.vue'
    import { same_week } from '../../../../../../services/guide/gymHours.js'
    import { FRENCH_ALPINE_GRADES, RUSSIAN_ALPINE_GRADES } from '../../../../../../mixins/grade_chart_mixin.js'
    export default {
        components: {
            // StackModal,
            // SlickItem,
            // SlickList,

            published_item,
            weeklyHoursInput,
        },
        props: [
            // 'back_url',
            // 'category'
        ],
        data(){
            return {
                category: this.$route.params.article_category,

                editorConfig: {
                    // toolbar: [ [ 'Bold' ] ]
                },

                error: [],
                
                regions: [],
                mount_masive: [],

                french_grades: FRENCH_ALPINE_GRADES,
                russian_grades: RUSSIAN_ALPINE_GRADES,

                data: {
                    category: this.$route.params.article_category,
                    // us_title_for_url_title: "",
                    published: 0,
                    show_donation: 0,
                    // Not an articles column — saved as the article_region relation (ArticleController::description_img)
                    region_id: null,
                    work_status: null,
                    price_status: null,
                    // indoor: per-day hours when work_status is 'per_day' (ArticleController::save_weekly_hours)
                    weekly_hours: null,
                    // completed: "",
                    // map: "",
                    // weather: "",
                    // open_timen: "",
                    // closed_time: "",
                    // price_from: "",
                    // start_data: "",
                    // end_data: "",
                    // fb_link: "",
                    // twit_link: "",
                    // google_link: "",
                    // inst_link: "",
                    // web_link: "",
                },
                    mount_id: 0,
            }
        },
        watch: {
            // "No information about prices" replaces the minimal price
            'data.price_status': function(status) {
                if (status) this.data.price_from = null
            },
            // A status replaces the working hours — clear them (the server does the same);
            // "different hours on different days" starts from the every-day times
            'data.work_status': function(status) {
                if (status == 'per_day') {
                    if (!this.data.weekly_hours) this.data.weekly_hours = same_week(this.data.open_time, this.data.closed_time)
                }
                else if (status) {
                    this.data.open_time = null
                    this.data.closed_time = null
                }
            },
        },
        mounted() {
            if (['outdoor', 'indoor', 'ice'].includes(this.category)) {
                this.get_regions(this.category)
            }
            if (this.category == 'mount_route') {
                this.get_mount_massive_data('mount_route')
            }

            this.$emit('global_form_data', this.data)
        },
        methods: {
            get_mount_massive_data: function(category){
                if(category == 'mount_route'){
                    axios
                    .get("/get_mount/get_all_mount/")
                    .then(response => {
                        this.mount_masive = response.data
                    })
                    .catch(
                        error => console.log(error)
                    );
                }
            },

            get_regions(category){
                if(['outdoor', 'indoor', 'ice'].includes(category)){
                    axios
                    .get("/get_region/get_all_outdoor_regions/")
                    .then(response => {
                        this.regions = response.data
                    })
                    .catch(
                        error => console.log(error)
                    );
                }
            },
        }
    }
</script>

<style>

</style>