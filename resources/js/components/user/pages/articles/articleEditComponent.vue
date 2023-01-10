<template>
  <div class="tabs"> 
        <div class="row">
            <div class="form-group">
                <button type="submit" class="btn btn-primary" @click="go_back()">Beck</button>
            </div>
        </div>

        <div class="row">
            <div class="form-group">  
                <button type="submit" class="btn btn-primary" v-on:click="edit_article()" >Save</button>
            </div>
        </div>
        <div class="row" >
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
                <div class="row">
                    <!-- <div class="alert alert-danger" role="alert" v-if="ka_article_error.ka_info">
                        {{ ka_article_error.ka_info[0] }}
                    </div> -->
                </div>
            </div>
            <div class="col-md-12" v-show="tab_num == 1">
                <GlobalDataForm 
                    @global_form_data="article_data.global_data = $event" 

                    :global_data_prop="editing_data.global_article"
                    :category_prop="this.category"

                    :region_id_prop="region_id"
                    :mount_id_prop="mount_id"

                    :title_prop="$t('user edit en article title')"
                    :description_prop="$t('user edit en article description')"
                />

                <!-- ref="ArticleImage" -->

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

                    @mount_route_images="article_image = $event" 
                />
               
            </div>
            <div class="col-md-12" v-show="tab_num == 2">
                <LocaleDataForm 
                    @locale_form_data="article_data.us_data = $event"
                    @global_blocks="global_blocks_action"

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
                    @locale_form_data="article_data.ka_data = $event"
                    @global_blocks="global_blocks_action"

                    :global_blocks_prop="global_blocks"
                    :locale_data_prop="editing_data.ka_article"
                    :category_prop="this.category"
                    :locale_prop="'ka'"

                    :title_prop="$t('user edit ka article title')"
                    :description_prop="$t('user edit ka article description')"
                />
                
            </div>
            <div class="col-md-12" v-show="tab_num == 4">
                <LocaleDataForm 
                    @locale_form_data="article_data.ru_data = $event"
                    @global_blocks="global_blocks_action"

                    :global_blocks_prop="global_blocks"
                    :locale_data_prop="editing_data.ru_article"
                    :category_prop="this.category"
                    :locale_prop="'ru'"

                    :title_prop="$t('user edit ru article title')"
                    :description_prop="$t('user edit ru article description')"
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
            // 'back_url',
            // 'category',
            // 'editing_article_id'
        ],
        components: {
            GlobalDataForm,
            LocaleDataForm,
            ArticleImage,
            SectorsImagesForm,
            MountRouteImagesForm
        },
        data(){
            return {
                article_id: this.$route.params.id,
                tab_num: 1,

                errors: [],

                editing_data: [],

                article_image: '',
                area_rocks_images: [],
                mount_route_images: [],

                region_id: 0,
                mount_id: 0,

                article_old_image: '',

                is_dala_geting: true,

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
        },
        methods: {
            get_editing_data() {
                axios
                .get('../../../api/articles/get_editing_data/'+this.$route.params.id)
                .then(response => {
                    this.editing_data = response.data
                    this.category = response.data.global_article.category
                    this.article_old_image = this.editing_data.global_article.image

                    this.region_id = this.editing_data.region_id
                    this.mount_id = this.editing_data.mount_id

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
                    error => console.log(error)
                )
                .finally(() => this.is_dala_geting = false);
            },

            edit_article() {
                this.errors = []

                let formData = new FormData();
                formData.append('image', this.article_image);
                formData.append('data', JSON.stringify(this.editing_data))
                formData.append('global_blocks', JSON.stringify(this.global_blocks))

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
                            formData.append('mountain_route_images['+loop_num+']', mount_route_image.image)
                            loop_num++
                        });
                        loop_num = 0
                    }
                }

                axios
                // .post('/api/article/add_article/' + this.category, 
                .post('../../../api/article/edit_article/' + this.article_id, 
                    formData,
                )
                .then(response => {
                    // this.is_back_action = true
                    // this.$refs.ArticleImage.checkForm()
                    this.go_back(true)
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

            global_blocks_action(event){
                this.global_blocks = event
            },

            go_back: function(back_action = false) {
                if(back_action == false){
                    if(confirm('Are you sure, you want go back?')){
                        this.$router.go(-1)
                    }
                }
                else{
                    this.$router.go(-1)
                }
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