<template>
  <div class="tabs"> 
        <div class="row">
            <div class="form-group">
                <button type="submit" class="btn btn-primary" @click="go_back()">Beck</button>
            </div>
        </div>

        <div class="row">
            <div class="form-group">  
                <button type="submit" class="btn btn-primary" v-on:click="save_all()" >Save</button>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="row">
                    <div class="col" >
                        <input type="radio" id="1" :value="1" v-model="tab_num">
                        
                        <label for="1" >Global info</label>
                    </div>
                    <div class="col" >
                        <input type="radio" id="2" :value="2" v-model="tab_num">
                        
                        <label for="2" >English text</label>
                    </div>
                    <div class="col" >
                        <input type="radio" id="3" :value="3" v-model="tab_num">
                        
                        <label for="3" >Russion text</label>
                    </div>
                    <div class="col" >
                        <input type="radio" id="4" :value="4" v-model="tab_num">
                        
                        <label for="4" >Georgian text</label>
                    </div>
                </div>
                <div class="ro">
                    <div class="alert alert-danger" role="alert" v-if="ka_article_error.ka_info">
                        {{ ka_article_error.ka_info[0] }}
                    </div>
                </div>
            </div>
            <div class="row" v-show="tab_num == 1">
                <GlobalDataForm 
                    @global_form_data="article_data.global_data = $event" 

                    :global_data="editing_data.global_article"
                    :category="editing_data.global_article.category"
                />

                <ArticleImage ref="ArticleImage"/>

                <SectorsImagesForm      
                    v-if="editing_data.global_article.category == 'outdoor'"
                    :category="editing_data.global_article.category"
                />
                <MountRouteImagesForm
                    v-if="editing_data.global_article.category == 'mount_route'"
                    :category="editing_data.global_article.category"
                />
               
            </div>
            <div class="row" v-show="tab_num == 2">
                <LocaleDataForm 
                    @locale_form_data="article_data.us_data = $event"
                    @global_blocks="global_blocks_action"

                    :global_blocks_prop="global_blocks"
                    :locale_data="editing_data.us_article"
                    :category="editing_data.global_article.category"

                    :title="$t('user edit en article title')"
                    :description="$t('user edit en article description')"
                />
                
            </div>
            <div class="row" v-show="tab_num == 3">
                <LocaleDataForm 
                    @locale_form_data="article_data.ka_data = $event"
                    @global_blocks="global_blocks_action"

                    :global_blocks_prop="global_blocks"
                    :locale_data="editing_data.ka_article"
                    :category="editing_data.global_article.category"

                    :title="$t('user edit ka article title')"
                    :description="$t('user edit ka article description')"
                />
                
            </div>
            <div class="row" v-show="tab_num == 4">
                <LocaleDataForm 
                    @locale_form_data="article_data.ru_data = $event"
                    @global_blocks="global_blocks_action"

                    :global_blocks_prop="global_blocks"
                    :locale_data="editing_data.ru_article"
                    :category="editing_data.global_article.category"

                    :title="$t('user edit ru article title')"
                    :description="$t('user edit ru article description')"
                />
               
            </div>

        </div>
    </div>
</template>

<script>
    import GlobalDataForm from './forms/edit_forms/GlobalDataFormComponent.vue'
    import LocaleDataForm from './forms/edit_forms/LocaleDataFormComponent.vue'
    import ArticleImage from './forms/edit_forms/ArticleImageFormComponent.vue'
    import SectorsImagesForm from './forms/edit_forms/SectorImagesFormComponent.vue'
    import MountRouteImagesForm from './forms/edit_forms/MountRouteImageFormComponent.vue'

    export default {
        props: [
            'back_url',
            'category',
            // 'editing_article_id'
        ],
        components: {
            // StackModal,
            // SlickItem,
            // SlickList,

            GlobalDataForm,
            LocaleDataForm,
            ArticleImage,
            SectorsImagesForm,
            MountRouteImagesForm
        },
        data(){
            return {
                // editing_url: '/articles/get_editing_data/',
                tab_num: 1,

                global_article_error: [],
                is_global_article_error: true,

                ka_article_error: [],
                is_ka_article_error: true,

                ru_article_error: [],
                is_ru_article_error: true,

                us_article_error: [],
                is_us_article_error: true,

                // global_data: [],
                // us_data: [],
                // ka_data: [],
                // ru_data: [],
                editing_data: [],

                global_blocks: {
                    info_block: 'new_info',
                    routes_info: "new_info",
                    what_need_info: 'new_info',
                    best_time: 'new_info',

                    info_block_id: 0,
                    routes_info_id: 0,
                    what_need_info_id: 0,
                    best_time_id: 0,
                }
            }
        },
        mounted() {
            this.get_editing_data()
            // if (this.category == 'mount_route') {
            //     this.get_mount_massive_data()
            // }
            // this.check_permission()
        },
        methods: {
            get_editing_data() {
                axios
                .get('../../../api/articles/get_editing_data/'+this.$route.params.id)
                .then(response => {
                    this.editing_data = response.data

                    this.editing_data.general_data.forEach(general_text => {
                        if(general_text.block == 'info_block'){
                            this.global_blocks.info_block = general_text.block_action
                            this.global_blocks.info_block_id = general_text.info_id
                        }

                        else if(general_text.block == 'routes_info'){
                            this.global_blocks.routes_info = general_text.block_action
                            this.global_blocks.routes_info_id = general_text.info_id
                        }

                        else if(general_text.block == 'what_need_info'){
                            this.global_blocks.what_need_info = general_text.block_action
                            this.global_blocks.what_need_info_id = general_text.info_id
                        }

                        else if(general_text.block == 'best_time'){
                            this.global_blocks.best_time = general_text.block_action
                            this.global_blocks.best_time_id = general_text.info_id
                        }
                    });
                })
                .catch(
                    error => console.log(error)
                );
            },
            edit_global_article() {
                axios
                .post('/articles/global/edit/' + this.editing_article_id, {
                    us_title_for_url_title: this.us_title,

                    published: this.published,
                    mount_id: this.mount_id,
                    completed: this.completed,
                    map: this.map,
                    weather: this.weather,

                    start_data_day: this.start_data_day,
                    end_data_day: this.end_data_day,
                    start_data_month: this.start_data_month,
                    end_data_month: this.end_data_month,

                    working_time: this.working_time,
                    price_from: this.price_from,

                    fb_link: this.fb_link,
                    twit_link: this.twit_link,
                    google_link: this.google_link,
                    inst_link: this.inst_link,
                    web_link: this.web_link,
                })
                .then(Response => {
                    this.is_global_article_error = false
                    this.if_isset_go_beck(this.is_global_article_error)
                })
                .catch(error =>{
                    if (error.response.status == 422) {
                        this.global_article_error = error.response.data.errors
                    }
                    this.is_global_article_error = true
                })
            },

            global_blocks_action(event){
                this.global_blocks = event
            },
        },

    }


    // export default {
    //     props: [
    //         'back_url',
    //         'category',
    //         'editing_article_id'
    //     ],
    //             global_article_error: [],
    //             is_global_article_error: true,
    //             ka_article_error: [],
    //             is_ka_article_error: true,
    //             ru_article_error: [],
    //             is_ru_article_error: true,
    //             us_article_error: [],
    //             is_us_article_error: true,

    //             editor: 'editor',
    //             editorConfig:{},

    //             permission: '',

    //             url: '',

    //             editing_data: '',

    //             us_title_for_url_title: "",
    //             published: "",
    //             completed: "",
    //             map: "",
    //             weather: "",
    //             start_data_day: "",
    //             end_data_day: "",
    //             end_data_month: "",
    //             end_data_month: "",
    //             fb_link: "",
    //             twit_link: "",
    //             google_link: "",
    //             inst_link: "",
    //             web_link: "",
    //             working_time: "",
    //             price_from: "",

    //             name: '',
    //             image_name: '',
    //             region_sectors_image_name: '',
    //             mount_route_description_image_name: '',
    //             success: '',


    //             // 
    //             // 
    //             // 
    //             us_title: "",
    //             us_short_description: "",
    //             us_text: "",
    //             us_route: "",
    //             us_how_get: "",
    //             us_best_time: "",
    //             us_what_need: "",
    //             us_info: "",
    //             us_meta_keyword: "",


    //             // 
    //             // 
    //             // 
    //             ka_title: "",
    //             ka_short_description: "",
    //             ka_text: "",
    //             ka_route: "",
    //             ka_how_get: "",
    //             ka_best_time: "",
    //             ka_what_need: "",
    //             ka_info: "",
    //             ka_meta_keyword: "",


    //             // 
    //             // 
    //             // 
    //             ru_title: "",
    //             ru_short_description: "",
    //             ru_text: "",
    //             ru_route: "",
    //             ru_how_get: "",
    //             ru_best_time: "",
    //             ru_what_need: "",
    //             ru_info: "",
    //             ru_meta_keyword: "",

    //             us_article_id: "",
    //             ru_article_id: "",
    //             ka_article_id: "",
    //         }
    //     },
    
    //     

    //     methods: {
    //         check_permission(){
    //             axios
    //             .get('../../../check_permission/')
    //             .then(Response => {
    //                 this.permission =  Response.data
    //             })
    //             .catch(error =>{
    //             })
    //         },

    //         
    //         edit_ru_article() {
    //             axios
    //             .post('/articles/ru/edit/' + this.ru_article_id, {
    //                 ru_title: this.ru_title,
    //                 ru_short_description: this.ru_short_description,
    //                 ru_text: this.ru_text,
    //                 ru_route: this.ru_route,
    //                 ru_how_get: this.ru_how_get,
    //                 ru_best_time: this.ru_best_time,
    //                 ru_what_need: this.ru_what_need,
    //                 ru_info: this.ru_info,
    //                 ru_meta_keyword: this.ru_meta_keyword,
    //             })
    //             .then(Response => {
    //                 this.is_ru_article_error = false
    //                 this.if_isset_go_beck(this.is_ru_article_error)
    //             })
    //             .catch(error =>{
    //                 if (error.response.status == 422) {
    //                     this.ru_article_error = error.response.data.errors
    //                 }
    //                 this.is_ru_article_error = true
    //             })
    //         },
    //         edit_us_article() {
    //             axios
    //             .post('/articles/us/edit/' + this.us_article_id, {
    //                 us_title: this.us_title,
    //                 us_short_description: this.us_short_description,
    //                 us_text: this.us_text,
    //                 us_route: this.us_route,
    //                 us_how_get: this.us_how_get,
    //                 us_best_time: this.us_best_time,
    //                 us_what_need: this.us_what_need,
    //                 us_info: this.us_info,
    //                 us_meta_keyword: this.us_meta_keyword,
    //             })
    //             .then(Response => {
    //                 this.is_us_article_error = false
    //                 this.if_isset_go_beck(this.is_us_article_error)
    //             })
    //             .catch(error =>{
    //                 if (error.response.status == 422) {
    //                     this.us_article_error = error.response.data.errors
    //                 }
    //                 this.is_us_article_error = true
    //             })
    //         },
    //         edit_ka_article() {
    //             axios
    //             .post('/articles/ka/edit/' + this.ka_article_id, {
    //                 ka_title: this.ka_title,
    //                 ka_short_description: this.ka_short_description,
    //                 ka_text: this.ka_text,
    //                 ka_route: this.ka_route,
    //                 ka_how_get: this.ka_how_get,
    //                 ka_best_time: this.ka_best_time,
    //                 ka_what_need: this.ka_what_need,
    //                 ka_info: this.ka_info,
    //                 ka_meta_keyword: this.ka_meta_keyword,
    //             })
    //             .then(Response => {
    //                 this.is_ka_article_error = false
    //                 this.if_isset_go_beck(this.is_ka_article_error)
    //             })
    //             .catch(error =>{
    //                 if (error.response.status == 422) {
    //                     this.ka_article_error = error.response.data.errors
    //                 }
    //                 this.is_ka_article_error = true
    //             })
    //         },

    //         get_mount_massive_data: function(){
    //             axios
    //             .get("/mountaineering/get_mount_data/")
    //             .then(response => {
    //                 this.mount_data = response.data
    //             })
    //             .catch(
    //             error => console.log(error)
    //             );
    //         },

    //         checkForm: function (e) {
    //             var myFormData = new FormData(this.$refs.myForm)
    //             axios({
    //                 method: 'post',
    //                 url: '/articles/update_image/' + this.editing_article_id,
    //                 data: myFormData,
    //                 config: { 
    //                     headers: {'Content-Type': 'multipart/form-data' },
    //                 },
    //             })
    //             .then((response)=>  {
    //                 // this.is_image_succes = 1;
    //                 // alert(response.data.message);
    //             });
    //             // e.preventDefault();
    //         },
    //         upload_region_image: function () {
    //             // var mySectorRegionImageData = new Array(this.$refs.sectorRegionImage)
    //             var mySectorRegionImageData = new FormData(this.$refs.sectorRegionImage)
    //             axios({
    //                 method: 'post',
    //                 url: '/articles/region_sectors_image_update/' + this.editing_article_id,
    //                 data: mySectorRegionImageData,
    //                 config: { 
    //                     headers: {'Content-Type': 'multipart/form-data' },
    //                 },
    //             })
    //             .then((response)=>  {
                    
    //             });
    //         },
    //         upload_mount_route_image: function () {
    //             var myMountRouteImageData = new FormData(this.$refs.mountRouteImage)
    //             axios({
    //                 method: 'post',
    //                 url: '/articles/mount_route_image_update/' + this.editing_article_id,
    //                 data: myMountRouteImageData,
    //                 config: { 
    //                     headers: {'Content-Type': 'multipart/form-data' },
    //                 },
    //             })
    //             .then((response)=>  {
                    
    //             });
    //         },

    //         get_editing_data() {
    //             this.url = this.editing_url + this.editing_article_id

    //             axios
    //             .get(this.url)
    //             .then(response => {
    //                 this.editing_data = response.data
                    
    //                 this.us_article_id = this.editing_data.global_article['us_article_id'],
    //                 this.ru_article_id = this.editing_data.global_article['ru_article_id'],
    //                 this.ka_article_id = this.editing_data.global_article['ka_article_id'],
                    
    //                 // send data in editing form value
    //                 this.published = this.editing_data.global_article['published'],
    //                 this.mount_id = this.editing_data.global_article['mount_id'],
    //                 this.completed = this.editing_data.global_article['completed'],
    //                 this.map = this.editing_data.global_article['map'],
    //                 this.weather = this.editing_data.global_article['weather'],
    //                 this.start_data_day = this.editing_data.global_article['start_data_day'],
    //                 this.end_data_day = this.editing_data.global_article['end_data_day'],
    //                 this.start_data_month = this.editing_data.global_article['start_data_month'],
    //                 this.end_data_month = this.editing_data.global_article['end_data_month'],
    //                 this.fb_link = this.editing_data.global_article['fb_link'],
    //                 this.twit_link = this.editing_data.global_article['twit_link'],
    //                 this.google_link = this.editing_data.global_article['google_link'],
    //                 this.inst_link = this.editing_data.global_article['inst_link'],
    //                 this.web_link = this.editing_data.global_article['web_link'],
    //                 this.us_price_from = this.editing_data.global_article['price_from'],
    //                 this.image_name = this.editing_data.global_article['image'],
    //                 this.region_sectors_image_name = this.editing_data.global_article['climbing_area_image']
    //                 this.mount_route_description_image_name = this.editing_data.global_article['mount_route_image']

    //                 this.price_from = this.editing_data.global_article['price_from']
    //                 this.working_time = this.editing_data.global_article['working_time']
                    
    //                 // 
    //                 // 
    //                 // 
    //                 this.us_title = this.editing_data.us_article['title'],
    //                 this.us_short_description = this.editing_data.us_article['short_description'],
    //                 this.us_text = this.editing_data.us_article['text'],
    //                 this.us_route = this.editing_data.us_article['route'],
    //                 this.us_how_get = this.editing_data.us_article['how_get'],
    //                 this.us_best_time = this.editing_data.us_article['best_time'],
    //                 this.us_what_need = this.editing_data.us_article['what_need'],
    //                 this.us_info = this.editing_data.us_article['info'],
    //                 this.us_meta_keyword = this.editing_data.us_article['meta_keyword'],

    //                 // 
    //                 // 
    //                 // 
    //                 this.ru_title = this.editing_data.ru_article['title'],
    //                 this.ru_short_description = this.editing_data.ru_article['short_description'],
    //                 this.ru_text = this.editing_data.ru_article['text'],
    //                 this.ru_route = this.editing_data.ru_article['route'],
    //                 this.ru_how_get = this.editing_data.ru_article['how_get'],
    //                 this.ru_best_time = this.editing_data.ru_article['best_time'],
    //                 this.ru_what_need = this.editing_data.ru_article['what_need'],
    //                 this.ru_info = this.editing_data.ru_article['info'],
    //                 this.ru_meta_keyword = this.editing_data.ru_article['meta_keyword']

    //                 // 
    //                 // 
    //                 // 
    //                 this.ka_title = this.editing_data.ka_article['title'],
    //                 this.ka_short_description = this.editing_data.ka_article['short_description'],
    //                 this.ka_text = this.editing_data.ka_article['text'],
    //                 this.ka_route = this.editing_data.ka_article['route'],
    //                 this.ka_how_get = this.editing_data.ka_article['how_get'],
    //                 this.ka_best_time = this.editing_data.ka_article['best_time'],
    //                 this.ka_what_need = this.editing_data.ka_article['what_need'],
    //                 this.ka_info = this.editing_data.ka_article['info'],
    //                 this.ka_meta_keyword = this.editing_data.ka_article['meta_keyword']
    //             })
    //             .catch(
    //                 error => console.log(error)
    //             );
    //         },

    //         save_all() {
    //             this.edit_global_article();
    //             this.edit_us_article();
    //             this.edit_ka_article();
    //             this.edit_ru_article();

    //             this.checkForm();

    //             if (this.category == 'outdoor') {
    //                 this.upload_region_image()
    //             }
    //             else if (this.category == 'mount_route') {
    //                 this.upload_mount_route_image()
    //             }
    //         },

    //         if_isset_go_beck() {
    //             if (
    //                 this.is_global_article_error == false &&
    //                 this.is_ka_article_error == false &&
    //                 this.is_ru_article_error == false &&
    //                 this.is_us_article_error == false
    //             ) {
    //                 window.location.href = this.back_url;
    //             }
    //         }
    //     }
    // }
</script>


<style scoped>
    .tabs {
        /* display: flex; */
        flex-wrap: wrap;
    }
    .tabs label {
        order: 1;
        display: block;
        padding: 1rem 2rem;
        margin-right: 0.2rem;
        cursor: pointer;
        background: #ccced0;
        font-weight: bold;
        transition: background ease 0.2s;
    }
    .tabs .tab {
        order: 99;
        flex-grow: 1;
        width: 100%;
        display: none;
        padding: 1rem;
        background: #fff;
        /* border: 1px solid #ccc!important; */
    }
    .tabs input[type="radio"] {
        display: none;
    }
    .tabs input[type="radio"]:checked + label {
        background: #fff;
        border: 1px solid #ccc !important;
    }
    .tabs input[type="radio"]:checked + label + .tab {
        display: block;
    }

    @media (max-width: 45em) {
        .tabs .tab,
        .tabs label {
            order: initial;
        }
        .tabs label {
            width: 100%;
            margin-right: 0;
            margin-top: 0.2rem;
        }
    }
</style>