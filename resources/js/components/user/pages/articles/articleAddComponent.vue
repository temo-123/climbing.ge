<template>
    <div class="tabs"> 
        <div class="row" v-if="!is_mail_sending_procesing">
            <div class="form-group">
                <button type="submit" class="btn btn-primary" @click="go_back()">Beck</button>
            </div>
        </div>
        <div class="row" v-if="!is_mail_sending_procesing">
            <div class="form-group">  
                <button type="submit" class="btn btn-primary" v-on:click="save()" >Save</button>
            </div>
        </div>
        <div class="row" v-if="!is_mail_sending_procesing">
            <div class="col-md-12" v-if="error.length != 0">
                <div class="alert alert-danger" role="alert" v-if="error.global_info_validation.published">
                    Published - {{ error.global_info_validation.published[0] }}
                </div>

                <div class="alert alert-danger" role="alert" v-if="error.us_info_validation.title">
                    English title - {{ error.us_info_validation.title[0] }}
                </div>
                <div class="alert alert-danger" role="alert" v-if="error.us_info_validation.short_description">
                    English description - {{ error.us_info_validation.short_description[0] }}
                </div>
                <div class="alert alert-danger" role="alert" v-if="error.us_info_validation.text">
                    English text - {{ error.us_info_validation.text[0] }}
                </div>

                <div class="alert alert-danger" role="alert" v-if="error.ka_info_validation.title">
                    Georgian title - {{ error.ka_info_validation.title[0] }}
                </div>
                <div class="alert alert-danger" role="alert" v-if="error.ka_info_validation.short_description">
                    Georgian description - {{ error.ka_info_validation.short_description[0] }}
                </div>
                <div class="alert alert-danger" role="alert" v-if="error.ka_info_validation.text">
                    Georgian text - {{ error.ka_info_validation.text[0] }}
                </div>

                <div class="alert alert-danger" role="alert" v-if="error.ru_info_validation.title">
                    Russion title - {{ error.ru_info_validation.title[0] }}
                </div>
                <div class="alert alert-danger" role="alert" v-if="error.ru_info_validation.short_description">
                    Russiondescription - {{ error.ru_info_validation.short_description[0] }}
                </div>
                <div class="alert alert-danger" role="alert" v-if="error.ru_info_validation.text">
                    Russion text - {{ error.ru_info_validation.text[0] }}
                </div>
            </div>
        </div>
        <div class="row" v-if="!is_mail_sending_procesing">
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
            <div class="col-md-12" v-show="tab_num == 1">
                <GlobalDataForm 
                    @global_form_data="article_data.global_data = $event" 
                />

                <div class="col-md-12">
                    <ArticleImage ref="ArticleImage" @upload_img="upload_adticle_image"/>
                </div>

                <SectorsImagesForm      v-if="this.category == 'outdoor'"       :category="this.category" @upload_img="upload_area_images"/>
                <MountRouteImagesForm   v-if="this.category == 'mount_route'"   :category="this.category" @upload_img="upload_mount_route_images"/>
            </div>
            <div class="col-md-12" v-show="tab_num == 2">
                <LocaleDataForm 
                    @locale_form_data="article_data.en_data = $event"
                    @global_blocks="global_blocks_action"

                    :global_blocks_prop="global_blocks"

                    :title="$t('user add en article title')"
                    :description="$t('user add en article description')"
                />
            </div>
            <div class="col-md-12" v-show="tab_num == 3">
                <LocaleDataForm 
                    @locale_form_data="article_data.ka_data = $event"
                    @global_blocks="global_blocks_action"

                    :global_blocks_prop="global_blocks"

                    :title="$t('user add ka article title')"
                    :description="$t('user add ka article description')"
                />
            </div>
            <div class="col-md-12" v-show="tab_num == 4">
                <LocaleDataForm 
                    @locale_form_data="article_data.ru_data = $event"
                    @global_blocks="global_blocks_action"

                    :global_blocks_prop="global_blocks"

                    :title="$t('user add ru article title')"
                    :description="$t('user add ru article description')"
                />
            </div>
        </div>

        <div class="row justify-content-center" v-if="is_mail_sending_procesing">
            <div class="col-md-4">
                <img :src="'../../../../../../public/images/site_img/loading.gif'" alt="loading">
                <p>Pless wait! sanding notifications</p>
            </div>
        </div>
    </div>
</template>

<script>
    import GlobalDataForm from './forms/add_forms/GlobalDataFormComponent.vue'
    import LocaleDataForm from './forms/add_forms/LocaleDataFormComponent.vue'
    import ArticleImage from './forms/add_forms/ArticleImageFormComponent.vue'
    import SectorsImagesForm from './forms/add_forms/SectorImagesFormComponent.vue'
    import MountRouteImagesForm from './forms/add_forms/MountRouteImageFormComponent.vue'

    export default {
        components: {

            GlobalDataForm,
            LocaleDataForm,
            ArticleImage,
            SectorsImagesForm,
            MountRouteImagesForm
        },
        props: [
            // 'back_url',
        ],
        data(){
            return {
                tab_num: 1,
                category: this.$route.params.article_category,

                error: [],

                is_back_action: false,
                is_mail_sending_procesing: false,

                article_data: {
                    global_data: [],
                    en_data: [],
                    ka_data: [],
                    ru_data: [],
                },
                article_image: '',

                global_blocks: {
                    info_block: 'new_info',
                    routes_info: "new_info",
                    what_need_info: 'new_info',
                    best_time: 'new_info',

                    info_block_id: 0,
                    routes_info_id: 0,
                    what_need_info_id: 0,
                    best_time_id: 0,
                },

                area_images: [],
                mount_route_images: []
            }
        },
        mounted() {
            //
        },
        beforeRouteLeave (to, from, next) {
            if(this.is_back_action){
                next()
            }
            else if (window.confirm('Added information will be deleted!!! Are you sure, you want go back?')) {
                next()
            } 
            else {
                next(false)
            }
        },
        methods: {
            global_blocks_action(event){
                this.global_blocks = event
            },

            upload_adticle_image(event){
                this.article_image = event
            },

            upload_area_images(event){
                this.area_images = event
            },

            upload_mount_route_images(event){
                this.mount_route_images = event
            },
            
            save(){
                this.article_data.global_data.us_title_for_url_title = this.article_data.en_data.title,

                this.error = []

                let formData = new FormData();
                formData.append('image', this.article_image);
                formData.append('data', JSON.stringify(this.article_data))
                formData.append('global_blocks', JSON.stringify(this.global_blocks))

                if(this.category == 'outdoor'){
                    let loop_num = 0
                    this.area_images.forEach(area_image => {
                        formData.append('outdoor_area_images['+loop_num+']', area_image.image)
                        loop_num++
                    });
                    loop_num = 0
                }
                else if(this.category == 'mount_route'){
                    let loop_num = 0
                    this.mount_route_images.forEach(mount_image => {
                        formData.append('mountain_route_images['+loop_num+']', mount_image.image)
                        loop_num++
                    });
                    loop_num = 0
                }

                axios
                .post('/api/article/add_article/' + this.category, 
                    formData,
                )
                .then(response => {
                    if(confirm('Do you want send notification about new article?')){
                        this.sand_notification()
                    }
                    else{
                        this.go_back(true)
                    }
                })
                .catch(error => {
                    if (error.response.status == 422) {
                        this.error = error.response.data.validation
                    }
                })
            },

            sand_notification() {
                this.is_mail_sending_procesing = true

                axios
                .post('../../../api/user/notifications/send_article_notification',{
                    notification_category: this.category
                } )
                .then(response => {
                    this.go_back(true)
                })
                .catch(err => {
                    console.log(err);
                })
                .finally(() => this.is_mail_sending_procesing = false);
            },
            
            go_back: function(back_action = false) {
                this.is_back_action = true
                if(back_action == false){
                    if(confirm('Are you sure, you want go back?')){
                        this.$router.go(-1)
                    }
                }
                else{
                    this.$router.go(-1)
                }
            },
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