<template>
    <div class="tabs"> 
        <div class="row">
            <div class="form-group">
                <button type="submit" class="btn btn-primary" @click="go_back()">Beck</button>
            </div>
        </div>
        <div class="row">
            <div class="form-group">  
                <button type="submit" class="btn btn-primary" v-on:click="add_article()" >Save</button>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="alert alert-danger" role="alert" v-if="error.global_article_error.published">
                    {{ error.global_article_error.published[0] }}
                </div>

                <div class="alert alert-danger" role="alert" v-if="error.us_article_error.us_short_description">
                    {{ error.us_article_error.us_short_description[0] }}
                </div>
                <div class="alert alert-danger" role="alert" v-if="error.us_article_error.us_short_description">
                    {{ error.us_article_error.us_short_description[0] }}
                </div>
                <div class="alert alert-danger" role="alert" v-if="error.us_article_error.us_text">
                    {{ error.us_article_error.us_text[0] }}
                </div>

                <div class="alert alert-danger" role="alert" v-if="error.ka_article_error.ka_short_description">
                    {{ error.ka_article_error.ka_short_description[0] }}
                </div>
                <div class="alert alert-danger" role="alert" v-if="error.ka_article_error.ka_short_description">
                    {{ error.ka_article_error.ka_short_description[0] }}
                </div>
                <div class="alert alert-danger" role="alert" v-if="error.ka_article_error.ka_text">
                    {{ error.ka_article_error.ka_text[0] }}
                </div>

                <div class="alert alert-danger" role="alert" v-if="error.ru_article_error.ru_short_description">
                    {{ error.ru_article_error.ru_short_description[0] }}
                </div>
                <div class="alert alert-danger" role="alert" v-if="error.ru_article_error.ru_short_description">
                    {{ error.ru_article_error.ru_short_description[0] }}
                </div>
                <div class="alert alert-danger" role="alert" v-if="error.ru_article_error.ru_text">
                    {{ error.ru_article_error.ru_text[0] }}
                </div>
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
                        
                        <label for="3" >Georgian text</label>
                    </div>
                    <div class="col" >
                        <input type="radio" id="4" :value="4" v-model="tab_num">
                        
                        <label for="4" >Russion text</label>
                    </div>
                </div>
            </div>
            <div class="row" v-show="tab_num == 1">
                <GlobalDataForm 
                    @global_form_data="article_data.global_data = $event" 
                />

                <ArticleImage ref="ArticleImage"/>

                <!-- <SectorsImagesForm      v-if="this.category == 'outdoor'"       :category="this.category"/>
                <MountRouteImagesForm   v-if="this.category == 'mount_route'"   :category="this.category"/> -->
            </div>
            <div class="row" v-show="tab_num == 2">
                <LocaleDataForm 
                    @locale_form_data="article_data.en_data = $event"
                    @global_blocks="global_blocks_action"

                    :global_blocks_prop="global_blocks"

                    :title="$t('user add en article title')"
                    :description="$t('user add en article description')"
                />
            </div>
            <div class="row" v-show="tab_num == 3">
                <LocaleDataForm 
                    @locale_form_data="article_data.ka_data = $event"
                    @global_blocks="global_blocks_action"

                    :global_blocks_prop="global_blocks"

                    :title="$t('user add ka article title')"
                    :description="$t('user add ka article description')"
                />
            </div>
            <div class="row" v-show="tab_num == 4">
                <LocaleDataForm 
                    @locale_form_data="article_data.ru_data = $event"
                    @global_blocks="global_blocks_action"

                    :global_blocks_prop="global_blocks"

                    :title="$t('user add ru article title')"
                    :description="$t('user add ru article description')"
                />
            </div>
        </div>

    </div>
</template>

<script>
    // import { SlickList, SlickItem } from 'vue-slicksort';
    // import StackModal from '@innologica/vue-stackable-modal'  //https://innologica.github.io/vue-stackable-modal/#sample-css

    import GlobalDataForm from './forms/add_forms/GlobalDataFormComponent.vue'
    import LocaleDataForm from './forms/add_forms/LocaleDataFormComponent.vue'
    import ArticleImage from './forms/add_forms/ArticleImageFormComponent.vue'
    import SectorsImagesForm from './forms/add_forms/SectorImagesFormComponent.vue'
    import MountRouteImagesForm from './forms/add_forms/MountRouteImageFormComponent.vue'

    export default {
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
        props: [
            // 'back_url',
            // 'category'
        ],
        data(){
            return {
                // myModal: false,
                // errors: [],
                // image_errors: [],
                // without_info: false,

                // is_add_image: false,

                tab_num: 1,
                category: this.$route.params.article_category,

                // editorConfig: {
                    // toolbar: [ [ 'Bold' ] ]
                // },

                // info_block: 'new_info',
                // routes_info: "new_info",
                // what_need_info: 'new_info',
                // best_time: 'new_info',

                error: {
                    global_article_error: [],
                    ka_article_error: [],
                    ru_article_error: [],
                    us_article_error: [],
                },

                // test: "",

                // image_is_refresh: false,
                // image_reset_id: 0,

                // general_infos: [],
                // regions: [],
                // ru_article_error: [],
                // us_article_error: [],

                // name: '',
                // images: [],
                // success: '',

                is_back_action: false,

                // test_data: '',

                // us_title: null,

                article_data: {
                    global_data: [],
                    en_data: [],
                    ka_data: [],
                    ru_data: [],
                },

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

                // temporary_article_id: '',
            }
        },
        mounted() {

            // if(this.article_data.global_data.us_title_for_url_title != ""){
            //     this.article_data.global_data.us_title_for_url_title = this.article_data.en_data.title
            // }

            // this.create_temporary_article()
            // this.check_permission()
            // if (this.category == 'outdoor') {
            //     this.get_regions()
            // }
            // this.get_general_info()
        },
        // beforeDestroy () {
        //     document.removeEventListener("backbutton", this.yourCallBackFunction());
        // },
        // watch:{
        //     '$route' (to) {
        //         if(to.currentRoute.meta.reload==true){
        //             alert('test')
        //         }
        //     }
        // },
        beforeRouteLeave (to, from, next) {
            if(this.is_back_action = true){
                if (window.confirm('Added information will be deleted!!! Are you sure, you want go back?')) {
                    this.is_back_action = false;
                    next()
                } else {
                    next(false)
                }
            }
        },
        methods: {
            global_blocks_action(event){
                this.global_blocks = event
            },


            // add_image_modal(){
            //     this.is_add_image = true
            // },

            // add_image(){
            //     var myFormData = new FormData(this.$refs.myForm)
            //     axios({
            //         method: 'post',
            //         url: '/gallery/add/',
            //         data: myFormData,
            //         config: { 
            //             headers: {'Content-Type': 'multipart/form-data' },
            //         },
            //     })
            //     .then((response)=>  {
            //         this.is_add_image = false
            //         this.get_gallery_data()
            //     });
            // },


            // get_mount_massive_data: function(){
            //     axios
            //     .get("/mountaineering/get_mount_data/")
            //     .then(response => {
            //         this.mount_data = response.data
            //     })
            //     .catch(
            //     error => console.log(error)
            //     );
            // },

            // get_regions(category){
            //     if(this.category == 'outdoor'){
            //         axios
            //         .get("/api/regions/en/")
            //         .then(response => {
            //             this.regions = response.data
            //         })
            //         .catch(
            //             error => console.log(error)
            //         );
            //     }
            // },

            // get_general_info(){
            //     axios
            //     .get('/api/general_info')
            //     .then(response => {
            //         this.general_infos = response.data
            //     })
            //     .catch(
            //         error => console.log(error)
            //     );
            // },


            add_article() {
                this.article_data.global_data.us_title_for_url_title = this.article_data.en_data.title,

                this.is_us_article_error = []
                this.error.global_article_error = [],
                this.error.ka_article_error = [],
                this.error.ru_article_error = [],
                this.error.us_article_error = [],

                axios
                .post('/api/article/' + this.category, {
                    data: this.article_data,

                    global_blocks: this.global_blocks,

                    _method: 'PATCH'
                })
                .then(response => {
                    this.$refs.ArticleImage.checkForm()
                    // this.if_isset_go_beck()
                })
                .catch(err => {
                    console.log(err);
                    // if (error.response.status == 422) {
                    //     this.error.global_article_error = error.response.data['global_data']
                    //     this.error.ka_article_error = error.response.data['ka_data']
                    //     this.error.ru_article_error = error.response.data['ru_data']
                    //     this.error.us_article_error = error.response.data['us_data']
                    // }
                    // this.is_us_article_error = true
                })
            },

            // checkForm: function () {
            //     var myFormData = new FormData(this.$refs.myForm)
            //     axios({
            //         method: 'post',
            //         url: '/articles/upload_image/',
            //         data: myFormData,
            //         config: { 
            //             headers: {'Content-Type': 'multipart/form-data' },
            //         },
            //     })
            //     .then((response)=>  {
                    
            //     });
            // },

            // upload_region_image: function () {
            //     // var mySectorRegionImageData = new Array(this.$refs.sectorRegionImage)
            //     var mySectorRegionImageData = new FormData(this.$refs.sectorRegionImage)
            //     axios({
            //         method: 'post',
            //         url: '/articles/region_sectors_image_upload/',
            //         data: mySectorRegionImageData,
            //         config: { 
            //             headers: {'Content-Type': 'multipart/form-data' },
            //         },
            //     })
            //     .then((response)=>  {
                    
            //     });
            // },
            // upload_mount_route_image: function () {
            //     var myMountRouteImageData = new FormData(this.$refs.mountRouteImage)
            //     axios({
            //         method: 'post',
            //         url: '/articles/mount_route_image_upload/',
            //         data: myMountRouteImageData,
            //         config: { 
            //             headers: {'Content-Type': 'multipart/form-data' },
            //         },
            //     })
            //     .then((response)=>  {
                    
            //     });
            // },
            
            go_back: function() {
                this.is_back_action = true

                this.$router.go(-1)
            },

            // save_all() {
            //     this.add_article()
            // },

            if_isset_go_beck() {
                // window.location.href = this.back_url;
                // this.go_back()
                alert('go back')
            },

            // check_permission() {
            //     axios
            //     .get('/users/get_user_parmisions/', { })
            //     .then((response)=> { 
                    
            //     })
            //     .catch(error =>{
                    
            //     })
            // }
        }
    }
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