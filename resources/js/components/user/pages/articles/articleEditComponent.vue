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
                <button type="submit" class="btn btn-primary" v-on:click="edit_article()" >Save</button>
            </div>
        </div>
        <div class="row" v-show="!is_loading" v-if="error.length != 0">

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
            <div class="col-md-12" v-show="tab_num == 1">
                <GlobalDataForm 

                    :global_data_prop="editing_data.global_article"
                    :category_prop="this.category"

                    :title_prop="$t('user edit en article title')"
                    :description_prop="$t('user edit en article description')"
                />

                <ArticleImage 
                    @upload_img="article_image = $event" 

                    :image_prop="article_old_image"
                    :category_prop="this.category"
                />

                <SectorsImagesForm      
                    v-if="this.category == 'outdoor'"

                    @area_images="area_rocks_images = $event" 
                />
                <MountRouteImagesForm
                    v-if="this.category == 'mount_route'"

                    @mount_route_img="mount_route_images = $event" 
                />

                <gallery_images_edit 
                    @update_gallery_images="update_gallery_images"

                    :image_path_prop="'images/article_gallery_img/'"
                    :image_del_route_prop="'gallery_image/del_image/'"
                    :get_images_route_prop="'gallery_image/get_editing_images/'"
                />
               
            </div>
            <div class="col-md-12" v-show="tab_num == 2">
                <LocaleDataForm 
                    @global_blocks="global_blocks_action"
                    @send_data="get_locale_data"

                    :global_blocks_prop="global_blocks"
                    :locale_data_prop="editing_data.us_article"
                    :category_prop="this.category"
                    :locale_prop="'us'"

                    :title_prop="$t('user edit en article title')"
                    :description_prop="$t('user edit en article description')"
                />
                
            </div>
            <div class="col-md-12" v-show="tab_num == 3">
                <LocaleDataForm 
                    @global_blocks="global_blocks_action"

                    :global_blocks_prop="global_blocks"
                    :locale_data_prop="editing_data.ka_article"
                    :category_prop="this.category"
                    :locale_prop="'ka'"

                    :title_prop="$t('user edit ka article title')"
                    :description_prop="$t('user edit ka article description')"
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

    import gallery_images_edit from '../../items/gallery/galleryImageEditComponent.vue'

    import validator_alerts_component from '../../items/validator_alerts_component.vue'
    export default {
        props: [
            // 'back_url',
            // 'category',
            // 'editing_article_id'
        ],
        components: {
            GlobalDataForm,
            LocaleDataForm,
            ArticleImage,
            SectorsImagesForm,
            MountRouteImagesForm,

            gallery_images_edit,
            validator_alerts_component
        },
        data(){
            return {
                article_id: this.$route.params.id,
                tab_num: 1,

                error: [],

                editing_data: [],

                article_image: '',
                article_gallery_image: [],
                area_rocks_images: [],
                mount_route_images: [],

                category: '',
                // mount_id: 0,
                
                is_back_action_query: true,

                article_old_image: '',

                is_dala_geting: true,

                is_loading: false,

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
            }
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
        mounted() {
            this.get_editing_data()

            document.querySelector('body').style.marginLeft = '0';
            document.querySelector('.admin_page_header_navbar').style.marginLeft = '0';
        },
        methods: {
            get_editing_data() {
                this.is_loading = true
                axios
                .get('/set_article/get_editing_data/'+this.$route.params.id)
                .then(response => {
                    this.editing_data = response.data
                    this.category = response.data.global_article.category
                    this.article_old_image = this.editing_data.global_article.image

                    this.editing_data.general_data.forEach(general_text => {
                        if(general_text.position.block == 'info_block'){
                            this.global_blocks.info_block = general_text.position.block_action
                            this.global_blocks.info_block_id = general_text.data.id
                        }

                        else if(general_text.position.block == 'routes_info'){
                            this.global_blocks.routes_info = general_text.position.block_action
                            this.global_blocks.routes_info_id = general_text.data.id
                        }

                        else if(general_text.position.block == 'what_need_info'){
                            this.global_blocks.what_need_info = general_text.position.block_action
                            this.global_blocks.what_need_info_id = general_text.data.id
                        }

                        else if(general_text.position.block == 'best_time'){
                            this.global_blocks.best_time = general_text.position.block_action
                            this.global_blocks.best_time_id = general_text.data.id
                        }
                    });
                })
                .catch(
                //     error => console.log(error)
                )
                .finally(() => this.is_loading = false);
            },

            update_gallery_images(images){
                this.article_gallery_image = images
            },

            edit_article() {
                this.is_loading = true
                this.errors = []

                let formData = new FormData();

                // formData.append('gallery_images', this.article_gallery_image);
                formData.append('image', this.article_image);
                formData.append('data', JSON.stringify(this.editing_data))
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
                    if(this.area_rocks_images){
                        var loop_num = 0
                        this.area_rocks_images.forEach(area_image => {
                            formData.append('outdoor_area_images['+loop_num+']', area_image.image)
                            loop_num++
                        });
                        loop_num = 0
                    }
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
                .post('/set_article/edit_article/' + this.article_id, 
                    formData,
                )
                .then(response => {
                    this.go_back(true)
                })
                .catch(error => {
                    if (error.response.status == 422) {
                        this.error = error.response.data.validation
                    }
                })
                .finally(() => this.is_loading = false);
            },

            global_blocks_action(event){
                this.global_blocks = event
            },

            get_locale_data({locale, data}){
                this.editing_data[locale+'_article'] = data
            },

            go_back: function(action = false) {
                this.is_back_action_query = this.$going.back(this, action)
            },
        },
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