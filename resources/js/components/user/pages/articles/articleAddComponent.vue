<template>
    <div class="tabs">
        <div class="row justify-content-center" v-show="is_loading">
            <div class="col-md-4">
                <img :src="'../../../../../../public/images/site_img/loading.gif'" alt="loading">
            </div>
        </div>

        <div class="row" v-show="!is_loading">
            <div class="form-group">
                <button type="submit" class="btn btn-primary" @click="go_back()">Beck</button>
            </div>
        </div>
        <div class="row" v-show="!is_loading">
            <div class="form-group">  
                <button type="submit" class="btn btn-primary" v-on:click="save()" >Save</button>
            </div>
        </div>
        <div class="row" v-show="!is_loading">
            <validator_alerts_component
                :errors_prop="error"
            />
        </div>
        <div class="row" v-show="!is_loading">
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
                </div>
            </div>
            <div v-show="tab_num == 1">
                <GlobalDataForm 
                    @global_form_data="data.global_article = $event" 
                />

                <div class="col-md-12">
                    <ArticleImage ref="ArticleImage" @upload_img="upload_adticle_image"/>
                </div>

                <SectorsImagesForm      
                    v-if="this.category == 'outdoor'"  

                    :category="this.category" 

                    @upload_img="upload_area_images"
                />
                
                <!-- <MountRouteImagesForm   v-if="this.category == 'mount_route'"   :category="this.category" @upload_img="upload_mount_route_images"/> -->
                <MountRouteImagesForm
                    v-if="this.category == 'mount_route'"

                    @mount_route_img="mount_route_images = $event" 
                />
                
                <gallery_images_add
                    @update_gallery_images="update_gallery_images"

                    :image_path_prop="'images/article_gallery_img/'"
                />
                
            </div>
            <div v-show="tab_num == 2">
                <LocaleDataForm 
                    @locale_form_data="data.us_article = $event"
                    @global_blocks="global_blocks_action"

                    :global_blocks_prop="global_blocks"

                    :title="$t('user add en article title')"
                    :description="$t('user add en article description')"
                />
            </div>
            <div v-show="tab_num == 3">
                <LocaleDataForm 
                    @locale_form_data="data.ka_article = $event"
                    @global_blocks="global_blocks_action"

                    :global_blocks_prop="global_blocks"

                    :title="$t('user add ka article title')"
                    :description="$t('user add ka article description')"
                />
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

    // import gallery_images_add from './items/galleryImageAddComponent.vue'
    import gallery_images_add from '../../items/gallery/galleryImageAddComponent.vue'

    import validator_alerts_component from '../../items/validator_alerts_component.vue'
    export default {
        components: {
            validator_alerts_component,

            GlobalDataForm,
            LocaleDataForm,
            ArticleImage,
            SectorsImagesForm,
            MountRouteImagesForm,

            gallery_images_add
        },
        props: [
            // 'back_url',
        ],
        data(){
            return {
                tab_num: 1,
                category: this.$route.params.article_category,

                error: [],

                is_back_action_query: true,
                is_loading: false,

                data: {
                    global_article: [],
                    us_article: [],
                    ka_article: [],
                    // ru_article: [],
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
                mount_route_images: [],
                article_gallery_image: []
            }
        },
        mounted() {
            document.querySelector('body').style.marginLeft = '0';
            document.querySelector('.admin_page_header_navbar').style.marginLeft = '0';
        },
        beforeRouteLeave (to, from, next) {
            if(this.is_back_action_query == true){
                if (window.confirm('Added information will be deleted!!! Are you sure, you want go back?')) {
                    this.is_back_action_query = false;
                    next()
                } else {
                    next(false)
                }
            }else {
                next()
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
            
            update_gallery_images(images){
                this.article_gallery_image = images
            },

            save(){
                this.is_loading = true
                // this.data.global_article.us_title_for_url_title = this.data.us_article.title,

                this.error = []

                let formData = new FormData();
                formData.append('image', this.article_image);
                formData.append('data', JSON.stringify(this.data))
                formData.append('global_blocks', JSON.stringify(this.global_blocks))

                if(this.article_gallery_image){
                    var loop_num = 0
                    this.article_gallery_image.forEach(gallery_image => {
                        formData.append('gallery_images['+loop_num+']', gallery_image.image)
                        loop_num++
                    });
                    loop_num = 0
                }

                if(this.category == 'outdoor'){
                    let loop_num = 0
                    this.area_images.forEach(area_image => {
                        formData.append('outdoor_area_images['+loop_num+']', area_image.image)
                        loop_num++
                    });
                    loop_num = 0
                }
                else if(this.category == 'mount_route'){
                    if(this.mount_route_images){
                        var loop_num = 0
                        this.mount_route_images.forEach(mount_route_image => {
                            formData.append('mount_route_images['+loop_num+']', mount_route_image.image)
                            loop_num++
                        });
                        loop_num = 0
                    }
                }

                axios
                .post('/article/add_article/' + this.category, 
                    formData,
                )
                .then(response => {
                    if(this.category == 'outdoor' || this.category == 'news' || this.category == 'tech_tip' || this.category == 'indoor' || this.category == 'ice'){
                        // if(confirm('Do you want send notification about new article?')){
                        //     this.sand_notification()
                        // }
                        // else{
                            this.go_back(true)
                        // }
                    }
                    else{
                        this.go_back(true)
                    }
                })
                .catch(error => {
                    if (error.response.status == 422) {
                        this.error = error.response.data.validation
                    }
                    else{
                        // alert(error)
                    }
                })
                .finally(() => this.is_loading = false);
            },

            // sand_notification() {
            //     this.is_loading = true

            //     axios
            //     .post('/user/notifications/send_article_adding_notification',{
            //         notification_category: this.category
            //     } )
            //     .then(response => {
            //         this.go_back(true)
            //     })
            //     .catch(err => {
            //         console.log(err);
            //     })
            //     .finally(() => this.is_loading = false);
            // },
            
            go_back: function(action = false) {
                this.is_back_action_query = this.$going.back(this, action)
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